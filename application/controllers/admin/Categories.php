<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin — kelola kategori produk.
 */
class Categories extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Category_model');
		$this->load->library('image_upload');
	}

	/**
	 * Daftar kategori + jumlah produknya.
	 */
	public function index()
	{
		$this->renderAdmin('admin/categories/index', array(
			'pageTitle'  => 'Kategori',
			'categories' => $this->Category_model->getAllWithCount(),
		));
	}

	/**
	 * Form tambah kategori.
	 */
	public function create()
	{
		$this->renderAdmin('admin/categories/form', array(
			'pageTitle'   => 'Tambah Kategori',
			'category'    => NULL,
			'isEdit'      => FALSE,
			'uploadError' => NULL,
		));
	}

	/**
	 * Form edit kategori.
	 *
	 * @param	int $id
	 */
	public function edit($id = 0)
	{
		$category = $this->Category_model->getById((int) $id);

		if ( ! $category)
		{
			$this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
			redirect('admin/categories');
		}

		$this->renderAdmin('admin/categories/form', array(
			'pageTitle'   => 'Edit Kategori',
			'category'    => $category,
			'isEdit'      => TRUE,
			'uploadError' => NULL,
		));
	}

	/**
	 * Simpan kategori baru (POST).
	 */
	public function store()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/categories');
		}

		$this->setValidationRules();

		if ($this->form_validation->run() === FALSE)
		{
			return $this->renderAdmin('admin/categories/form', array(
				'pageTitle'   => 'Tambah Kategori',
				'category'    => NULL,
				'isEdit'      => FALSE,
				'uploadError' => NULL,
			));
		}

		$image = $this->image_upload->upload('image_file', 'assets/img/kategori');

		if ($image['error'] !== NULL)
		{
			return $this->renderAdmin('admin/categories/form', array(
				'pageTitle'   => 'Tambah Kategori',
				'category'    => NULL,
				'isEdit'      => FALSE,
				'uploadError' => $image['error'],
			));
		}

		$data = $this->collectData();

		// File yang diunggah menang bila path/URL juga diisi.
		if ($image['path'] !== NULL)
		{
			$data['image_url'] = $image['path'];
		}

		$data['created_at'] = date('Y-m-d H:i:s');

		$this->Category_model->create($data);

		$this->session->set_flashdata('success', 'Kategori berhasil ditambahkan.');
		redirect('admin/categories');
	}

	/**
	 * Update kategori (POST).
	 *
	 * @param	int $id
	 */
	public function update($id = 0)
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/categories');
		}

		$category = $this->Category_model->getById((int) $id);

		if ( ! $category)
		{
			$this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
			redirect('admin/categories');
		}

		$this->setValidationRules();

		if ($this->form_validation->run() === FALSE)
		{
			return $this->renderAdmin('admin/categories/form', array(
				'pageTitle'   => 'Edit Kategori',
				'category'    => $category,
				'isEdit'      => TRUE,
				'uploadError' => NULL,
			));
		}

		$image = $this->image_upload->upload('image_file', 'assets/img/kategori');

		if ($image['error'] !== NULL)
		{
			return $this->renderAdmin('admin/categories/form', array(
				'pageTitle'   => 'Edit Kategori',
				'category'    => $category,
				'isEdit'      => TRUE,
				'uploadError' => $image['error'],
			));
		}

		$data = $this->collectData($category['id']);

		// File yang diunggah menang bila path/URL juga diisi.
		if ($image['path'] !== NULL)
		{
			$data['image_url'] = $image['path'];
		}

		$this->Category_model->update($category['id'], $data);

		$this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
		redirect('admin/categories');
	}

	/**
	 * Hapus kategori (POST).
	 *
	 * Ditolak bila masih ada produk di dalamnya, karena FK memakai
	 * ON DELETE CASCADE (produk akan ikut terhapus tanpa peringatan).
	 */
	public function delete()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/categories');
		}

		$id = (int) $this->input->post('id', TRUE);
		$category = $this->Category_model->getById($id);

		if ( ! $category)
		{
			$this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
			redirect('admin/categories');
		}

		$productCount = $this->Category_model->countProducts($id);

		if ($productCount > 0)
		{
			$this->session->set_flashdata('error', 'Kategori masih dipakai oleh '.$productCount.' produk. Pindahkan atau hapus produknya dulu.');
			redirect('admin/categories');
		}

		$this->Category_model->delete($id);
		$this->session->set_flashdata('success', 'Kategori dihapus.');
		redirect('admin/categories');
	}

	/**
	 * Aturan validasi form kategori.
	 */
	private function setValidationRules()
	{
		$this->form_validation->set_rules('name', 'Nama kategori', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('description', 'Deskripsi', 'trim|max_length[255]');
		// Cabang path lokal HARUS sebelum "|" agar regex tidak terpotong parser CI3.
		$this->form_validation->set_rules('image_url', 'Gambar', 'trim|max_length[500]|regex_match[#^(assets/(?!.*\.\.)[A-Za-z0-9._/ -]+|https?://\S+)$#i]');
	}

	/**
	 * Ambil input form yang sudah tervalidasi menjadi array kolom.
	 *
	 * @param	int $excludeId Kategori yang sedang diedit (agar slug tidak bentrok dengan dirinya sendiri)
	 * @return	array
	 */
	private function collectData($excludeId = 0)
	{
		$name = (string) $this->input->post('name', TRUE);

		return array(
			'name'        => $name,
			'slug'        => $this->Category_model->uniqueSlug($name, $excludeId),
			'description' => $this->postOrNull('description'),
			'image_url'   => $this->postOrNull('image_url'),
		);
	}

	/**
	 * Nilai POST yang sudah di-trim; string kosong menjadi NULL.
	 *
	 * @param	string $field
	 * @return	string|NULL
	 */
	private function postOrNull($field)
	{
		$value = trim((string) $this->input->post($field, TRUE));

		return ($value === '') ? NULL : $value;
	}
}
