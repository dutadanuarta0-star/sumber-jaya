<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin — kelola produk, varian, dan stok.
 *
 * Harga & stok berada di varian, jadi form produk sekaligus mengelola
 * daftar varian (nama ukuran, harga, stok) dalam satu kali simpan.
 */
class Products extends Admin_Controller {

	/** Jumlah baris varian minimal yang ditampilkan di form. */
	const VARIANT_ROWS = 3;

	/** Batas logis jumlah varian per produk. */
	const MAX_VARIANTS = 10;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Product_model', 'Category_model'));
	}

	/**
	 * Daftar semua produk.
	 */
	public function index()
	{
		$this->renderAdmin('admin/products/index', array(
			'pageTitle' => 'Produk',
			'products'  => $this->Product_model->getAllAdmin(),
		));
	}

	/**
	 * Form tambah produk.
	 */
	public function create()
	{
		$this->renderForm();
	}

	/**
	 * Form edit produk.
	 *
	 * @param	int $id
	 */
	public function edit($id = 0)
	{
		$product = $this->Product_model->getDetail((int) $id);

		if ( ! $product)
		{
			$this->session->set_flashdata('error', 'Produk tidak ditemukan.');
			redirect('admin/products');
		}

		$this->renderForm($product);
	}

	/**
	 * Simpan produk baru (POST).
	 */
	public function store()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/products');
		}

		$this->setValidationRules();

		if ($this->form_validation->run() === FALSE)
		{
			return $this->renderForm(NULL, TRUE);
		}

		$name = (string) $this->input->post('name', TRUE);
		$product = array(
			'category_id' => (int) $this->input->post('category_id', TRUE),
			'name'        => $name,
			'slug'        => $this->Product_model->uniqueSlug($name),
			'description' => (string) $this->input->post('description', TRUE),
			'image_url'   => (string) $this->input->post('image_url', TRUE),
			'is_active'   => $this->input->post('is_active') ? 1 : 0,
			'created_at'  => date('Y-m-d H:i:s'),
			'updated_at'  => date('Y-m-d H:i:s'),
		);

		$productId = $this->Product_model->create($product, $this->collectVariants());

		if ($productId === FALSE)
		{
			$this->session->set_flashdata('error', 'Produk gagal disimpan. Coba lagi.');
			return $this->renderForm(NULL, TRUE);
		}

		$this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
		redirect('admin/products');
	}

	/**
	 * Update produk (POST).
	 *
	 * @param	int $id
	 */
	public function update($id = 0)
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/products');
		}

		$product = $this->Product_model->getDetail((int) $id);

		if ( ! $product)
		{
			$this->session->set_flashdata('error', 'Produk tidak ditemukan.');
			redirect('admin/products');
		}

		$this->setValidationRules();

		if ($this->form_validation->run() === FALSE)
		{
			return $this->renderForm($product, TRUE);
		}

		$name = (string) $this->input->post('name', TRUE);
		$data = array(
			'category_id' => (int) $this->input->post('category_id', TRUE),
			'name'        => $name,
			'slug'        => $this->Product_model->uniqueSlug($name, $product['id']),
			'description' => (string) $this->input->post('description', TRUE),
			'image_url'   => (string) $this->input->post('image_url', TRUE),
			'is_active'   => $this->input->post('is_active') ? 1 : 0,
			'updated_at'  => date('Y-m-d H:i:s'),
		);

		if ( ! $this->Product_model->update($product['id'], $data, $this->collectVariants()))
		{
			$this->session->set_flashdata('error', 'Produk gagal diperbarui. Coba lagi.');
			return $this->renderForm($product, TRUE);
		}

		$this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
		redirect('admin/products');
	}

	/**
	 * Hapus produk (POST). Varian ikut terhapus (FK CASCADE).
	 */
	public function delete()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/products');
		}

		$id = (int) $this->input->post('id', TRUE);

		if ($this->Product_model->delete($id))
		{
			$this->session->set_flashdata('success', 'Produk dihapus.');
		}
		else
		{
			$this->session->set_flashdata('error', 'Produk gagal dihapus.');
		}

		redirect('admin/products');
	}

	/**
	 * Render form tambah/edit produk.
	 *
	 * @param	array|null $product  Data produk (NULL = tambah baru)
	 * @param	bool       $repopulate Ambil ulang input lama setelah validasi gagal
	 */
	private function renderForm($product = NULL, $repopulate = FALSE)
	{
		if ($repopulate)
		{
			$product = array(
				'id'          => $product ? $product['id'] : 0,
				'category_id' => (int) $this->input->post('category_id', TRUE),
				'name'        => (string) $this->input->post('name', TRUE),
				'description' => (string) $this->input->post('description', TRUE),
				'image_url'   => (string) $this->input->post('image_url', TRUE),
				'is_active'   => $this->input->post('is_active') ? 1 : 0,
				'variants'    => $this->collectVariants(),
			);
		}

		$isEdit = ($product AND ! empty($product['id']));

		// Selalu tampilkan beberapa baris kosong agar admin bisa menambah varian.
		$existing = ($product AND isset($product['variants'])) ? count($product['variants']) : 0;
		$emptyRows = max(0, self::VARIANT_ROWS - $existing);

		$this->renderAdmin('admin/products/form', array(
			'pageTitle'  => $isEdit ? 'Edit Produk' : 'Tambah Produk',
			'product'    => $product,
			'isEdit'     => $isEdit,
			'emptyRows'  => $emptyRows,
			'maxVariants'=> self::MAX_VARIANTS,
			'categories' => $this->Category_model->getAll(),
			'hasCategory'=> count($this->Category_model->getAll()) > 0,
		));
	}

	/**
	 * Aturan validasi form produk (varian divalidasi manual karena berbentuk array).
	 */
	private function setValidationRules()
	{
		$this->form_validation->set_rules('name', 'Nama produk', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('category_id', 'Kategori', 'required|integer');
		$this->form_validation->set_rules('description', 'Deskripsi', 'trim');
		// Diterima: URL lengkap (https://...) ATAU path lokal di dalam assets/
		// (penulisan gambar manual oleh admin, lihat helper img_src()).
		// CATATAN urutan cabang OR: parser CI3 memecah rule pada "|" kecuali diikuti
		// "]" tanpa "[" di antaranya — cabang path lokal (ada "[A-Za-z...]") wajib
		// berada SEBELUM "|" agar regex_match tidak terpotong.
		$this->form_validation->set_rules('image_url', 'Gambar', 'trim|max_length[500]|regex_match[#^(assets/(?!.*\.\.)[A-Za-z0-9._/ -]+|https?://\S+)$#i]');
	}

	/**
	 * Ambil baris varian dari input, buang baris yang seluruh fieldnya kosong.
	 *
	 * Format input:
	 *   variant_label[]  Nama ukuran (mis. "250g")
	 *   variant_price[]  Harga
	 *   variant_stock[]  Stok
	 *
	 * @return	array Baris siap insert: array('label', 'price', 'stock')
	 */
	private function collectVariants()
	{
		$labels = $this->input->post('variant_label');
		$prices = $this->input->post('variant_price');
		$stocks = $this->input->post('variant_stock');

		if ( ! is_array($labels))
		{
			return array();
		}

		$variants = array();

		foreach ($labels as $index => $label)
		{
			if (count($variants) >= self::MAX_VARIANTS)
			{
				break;
			}

			$label = trim((string) $label);
			$priceRaw = isset($prices[$index]) ? trim((string) $prices[$index]) : '';
			$stockRaw = isset($stocks[$index]) ? trim((string) $stocks[$index]) : '';

			// Baris kosong (tidak diisi admin) diabaikan.
			if ($label === '' AND $priceRaw === '' AND $stockRaw === '')
			{
				continue;
			}

			$variants[] = array(
				'label' => character_limiter($label === '' ? 'Standar' : $label, 50),
				'price' => max(0, (int) $priceRaw),
				'stock' => max(0, (int) $stockRaw),
			);
		}

		return $variants;
	}
}
