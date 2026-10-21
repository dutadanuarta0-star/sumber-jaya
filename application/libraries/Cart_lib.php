<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keranjang belanja berbasis session.
 *
 * Struktur item (di-key dengan variant_id):
 *   $cart[variantId] = array(
 *     'productId', 'productName', 'variantLabel', 'price', 'qty', 'stock'
 *   );
 *
 * Nama/harga/stok di session hanya snapshot; halaman keranjang selalu
 * membaca ulang data terbaru dari database (lihat Product_model::getVariantsByIds).
 */
class Cart_lib {

	/** @var object Instance CI untuk akses library session. */
	protected $ci;

	const SESSION_KEY = 'cart';

	public function __construct()
	{
		$this->ci =& get_instance();
	}

	/**
	 * Semua item keranjang.
	 *
	 * @return	array
	 */
	public function items()
	{
		$cart = $this->ci->session->userdata(self::SESSION_KEY);
		return is_array($cart) ? $cart : array();
	}

	/**
	 * Total jumlah barang di keranjang.
	 *
	 * @return	int
	 */
	public function count()
	{
		return (int) array_sum(array_column($this->items(), 'qty'));
	}

	/**
	 * Tambah item. Jika varian sama sudah ada, jumlahnya dijumlahkan.
	 *
	 * @param	array $itemData Data snapshot item (productId, productName, variantLabel, price, stock)
	 * @param	int   $qty      Jumlah yang ditambahkan
	 */
	public function add(array $itemData, $qty)
	{
		$cart = $this->items();
		$variantId = (int) $itemData['variantId'];

		if (isset($cart[$variantId]))
		{
			$cart[$variantId]['qty'] += (int) $qty;
		}
		else
		{
			$itemData['qty'] = (int) $qty;
			$cart[$variantId] = $itemData;
		}

		$this->save($cart);
	}

	/**
	 * Set jumlah item; qty <= 0 berarti hapus item.
	 */
	public function setQty($variantId, $qty)
	{
		$cart = $this->items();
		$variantId = (int) $variantId;
		$qty = (int) $qty;

		if ( ! isset($cart[$variantId]))
		{
			return;
		}

		if ($qty <= 0)
		{
			unset($cart[$variantId]);
		}
		else
		{
			$cart[$variantId]['qty'] = $qty;
		}

		$this->save($cart);
	}

	/**
	 * Hapus satu item.
	 */
	public function remove($variantId)
	{
		$cart = $this->items();
		unset($cart[(int) $variantId]);
		$this->save($cart);
	}

	/**
	 * Kosongkan keranjang.
	 */
	public function clear()
	{
		$this->save(array());
	}

	/**
	 * Simpan keranjang ke session.
	 */
	private function save(array $cart)
	{
		$this->ci->session->set_userdata(self::SESSION_KEY, $cart);
	}
}
