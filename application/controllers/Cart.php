<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keranjang belanja + checkout lewat WhatsApp.
 *
 * Pesanan tidak disimpan ke database. Saat checkout, isi keranjang
 * diformat menjadi teks lalu dibuka di WhatsApp (wa.me).
 */
class Cart extends Public_Controller {

	/** Batas wajar jumlah per varian dalam satu transaksi. */
	const MAX_QTY = 20;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Halaman keranjang.
	 *
	 * Data harga/nama/stok selalu dibaca ulang dari database supaya
	 * perubahan harga atau stok di admin langsung terlihat.
	 */
	public function index()
	{
		$rawItems = $this->cart_lib->items();
		$variants = $this->Product_model->getVariantsByIds(array_keys($rawItems));

		$items = array();
		$subtotal = 0;
		$hasIssue = FALSE;

		foreach ($rawItems as $variantId => $snapshot)
		{
			// Varian/produk sudah tidak ada atau produk dinonaktifkan.
			if ( ! isset($variants[$variantId]))
			{
				$this->cart_lib->remove($variantId);
				$hasIssue = TRUE;
				continue;
			}

			$variant = $variants[$variantId];
			$qty = (int) $snapshot['qty'];
			$stock = (int) $variant['stock'];
			$note = '';

			// Sesuaikan jumlah dengan stok yang tersedia.
			if ($stock <= 0)
			{
				$note = 'Stok habis — produk ini akan dilewati saat checkout.';
				$hasIssue = TRUE;
			}
			elseif ($qty > $stock)
			{
				$qty = $stock;
				$this->cart_lib->setQty($variantId, $qty);
				$note = 'Jumlah disesuaikan dengan stok tersedia.';
				$hasIssue = TRUE;
			}
			elseif ($qty > self::MAX_QTY)
			{
				$qty = self::MAX_QTY;
				$this->cart_lib->setQty($variantId, $qty);
				$note = 'Jumlah dibatasi maksimal '.self::MAX_QTY.' per varian.';
				$hasIssue = TRUE;
			}

			$lineTotal = (int) $variant['price'] * $qty;
			if ($stock > 0)
			{
				$subtotal += $lineTotal;
			}

			$items[] = array(
				'variantId'    => (int) $variantId,
				'productId'    => (int) $variant['product_id'],
				'productName'  => $variant['product_name'],
				'variantLabel' => $variant['label'],
				'price'        => (int) $variant['price'],
				'qty'          => $qty,
				'stock'        => $stock,
				'lineTotal'    => $lineTotal,
				'note'         => $note,
			);
		}

		if ($hasIssue)
		{
			$this->session->set_flashdata('info', 'Sebagian isi keranjang disesuaikan dengan stok terbaru. Silakan periksa kembali.');
		}

		$this->render('cart/index', array(
			'pageTitle'  => 'Keranjang',
			'items'      => $items,
			'subtotal'   => $subtotal,
			'maxQty'     => self::MAX_QTY,
			'checkoutOk' => $this->isCheckoutReady(),
		));
	}

	/**
	 * Tambah produk ke keranjang (POST).
	 */
	public function add()
	{
		$productId = (int) $this->input->post('product_id', TRUE);
		$variantId = (int) $this->input->post('variant_id', TRUE);
		$qty = (int) $this->input->post('qty', TRUE);

		// Validasi keluaran ada di sini (bukan lewat $this->form_validation)
		// karena form tersebar di halaman detail produk.
		if ($productId <= 0 OR $variantId <= 0 OR $qty <= 0)
		{
			$this->session->set_flashdata('error', 'Pilihan produk atau jumlah tidak valid.');
			redirect($productId > 0 ? 'products/detail/'.$productId : 'products');
		}

		$variant = $this->Product_model->getVariantsByIds(array($variantId));

		// Pastikan varian benar-benar milik produk yang dipilih.
		if ( ! isset($variant[$variantId]) OR (int) $variant[$variantId]['product_id'] !== $productId)
		{
			$this->session->set_flashdata('error', 'Varian produk tidak ditemukan.');
			redirect('products');
		}

		$variant = $variant[$variantId];
		$stock = (int) $variant['stock'];

		if ($stock <= 0)
		{
			$this->session->set_flashdata('error', 'Stok varian ini sedang habis.');
			redirect('products/detail/'.$productId);
		}

		if ($qty > self::MAX_QTY)
		{
			$qty = self::MAX_QTY;
		}

		$currentQty = isset($this->cart_lib->items()[$variantId]) ? (int) $this->cart_lib->items()[$variantId]['qty'] : 0;
		$newQty = min($currentQty + $qty, $stock, self::MAX_QTY);

		$this->cart_lib->add(array(
			'variantId'    => $variantId,
			'productId'    => (int) $variant['product_id'],
			'productName'  => $variant['product_name'],
			'variantLabel' => $variant['label'],
			'price'        => (int) $variant['price'],
			'stock'        => $stock,
		), $newQty - $currentQty);

		if ($newQty < $currentQty + $qty)
		{
			$this->session->set_flashdata('info', 'Jumlah di keranjang dibatasi oleh stok yang tersedia.');
		}
		else
		{
			$this->session->set_flashdata('success', 'Produk ditambahkan ke keranjang.');
		}

		redirect('cart');
	}

	/**
	 * Ubah jumlah item (POST) dari halaman keranjang.
	 */
	public function update()
	{
		$variantId = (int) $this->input->post('variant_id', TRUE);
		$qty = (int) $this->input->post('qty', TRUE);

		$variant = $this->Product_model->getVariantsByIds(array($variantId));

		if ( ! isset($variant[$variantId]))
		{
			$this->session->set_flashdata('error', 'Item tidak ditemukan di keranjang.');
			redirect('cart');
		}

		$stock = (int) $variant[$variantId]['stock'];

		if ($qty <= 0)
		{
			$this->cart_lib->remove($variantId);
			$this->session->set_flashdata('success', 'Item dihapus dari keranjang.');
		}
		else
		{
			$qty = min($qty, max($stock, 1), self::MAX_QTY);
			$this->cart_lib->setQty($variantId, $qty);
			$this->session->set_flashdata('success', 'Jumlah diperbarui.');
		}

		redirect('cart');
	}

	/**
	 * Hapus satu item (POST).
	 */
	public function remove()
	{
		$variantId = (int) $this->input->post('variant_id', TRUE);
		$this->cart_lib->remove($variantId);
		$this->session->set_flashdata('success', 'Item dihapus dari keranjang.');
		redirect('cart');
	}

	/**
	 * Kosongkan keranjang (POST).
	 */
	public function clear()
	{
		$this->cart_lib->clear();
		$this->session->set_flashdata('success', 'Keranjang dikosongkan.');
		redirect('cart');
	}

	/**
	 * Checkout: susun pesan pesanan lalu buka WhatsApp.
	 *
	 * Diproses sebagai POST + CSRF; stok divalidasi ulang di sini agar
	 * pesanan tidak berisi jumlah melebihi stok.
	 */
	public function checkout()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('cart');
		}

		$items = $this->cart_lib->items();

		if ( ! $items)
		{
			$this->session->set_flashdata('error', 'Keranjang masih kosong.');
			redirect('cart');
		}

		$variants = $this->Product_model->getVariantsByIds(array_keys($items));
		$lines = array();
		$subtotal = 0;
		$index = 1;

		foreach ($items as $variantId => $snapshot)
		{
			if ( ! isset($variants[$variantId]))
			{
				continue;
			}

			$variant = $variants[$variantId];
			$stock = (int) $variant['stock'];

			// Lewati varian yang stoknya habis.
			if ($stock <= 0)
			{
				continue;
			}

			$qty = min((int) $snapshot['qty'], $stock, self::MAX_QTY);
			$lineTotal = (int) $variant['price'] * $qty;
			$subtotal += $lineTotal;

			$lines[] = $index.'. '.$variant['product_name'].' - '.$variant['label']
				.' x'.$qty.' = '.rupiah($lineTotal);
			$index++;
		}

		if ( ! $lines)
		{
			$this->session->set_flashdata('error', 'Tidak ada item yang bisa dipesan (stok habis).');
			redirect('cart');
		}

		$number = preg_replace('/[^0-9]/', '', (string) $this->storeData['whatsapp_number']);

		if ($number === '')
		{
			$this->session->set_flashdata('error', 'Nomor WhatsApp toko belum diatur. Hubungi admin.');
			redirect('cart');
		}

		$message = "Halo ".$this->storeData['store_name'].", saya ingin memesan:\n\n"
			.implode("\n", $lines)
			."\n\nSubtotal: ".rupiah($subtotal)
			."\n\nMohon dikonfirmasi ketersediaan dan total akhirnya. Terima kasih.";

		// Pesan hanya dibuka di aplikasi WhatsApp; keranjang sengaja tidak
		// dikosongkan otomatis karena pesanan belum tentu terkirim final.
		redirect('https://wa.me/'.$number.'?text='.rawurlencode($message));
	}

	/**
	 * Apakah keranjang berisi minimal satu item dengan stok tersedia?
	 *
	 * @return	bool
	 */
	private function isCheckoutReady()
	{
		$items = $this->cart_lib->items();

		if ( ! $items)
		{
			return FALSE;
		}

		$variants = $this->Product_model->getVariantsByIds(array_keys($items));

		foreach ($variants as $variant)
		{
			if ((int) $variant['stock'] > 0)
			{
				return TRUE;
			}
		}

		return FALSE;
	}
}
