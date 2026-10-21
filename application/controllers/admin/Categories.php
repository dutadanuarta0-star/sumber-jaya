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
			'pageTitle' => 'Tambah Kategori',
			'category'  => NULL,
			'isEdit'    => FALSE,
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
			'pageTitle' => 'Edit Kategori',
			'category'  => $category,
			'isEdit'    => TRUE,
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
				'pageTitle' => 'Tambah Kategori',
				'category'  => NULL,
				'isEdit'    => FALSE,
			));
		}

		$name = (string) $this->input->post('name', TRUE);

		$this->Category_model->create(array(
			'name'        => $name,
			'slug'        => $this->Category_model->uniqueSlug($name),
			'description' => (string) $this->input->post('description', TRUE),
			'created_at'  => date('Y-m-d H:i:s'),
		));

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
				'pageTitle' => 'Edit Kategori',
				'category'  => $category,
				'isEdit'    => TRUE,
			));
		}

		$name = (string) $this->input->post('name', TRUE);

		$this->Category_model->update($category['id'], array(
			'name'        => $name,
			'slug'        => $this->Category_model->uniqueSlug($name, $category['id']),
			'description' => (string) $this->input->post('description', TRUE),
		));

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
	}
}
