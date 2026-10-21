<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin — kelola slider hero beranda (jumlah slide & gambar).
 */
class Slider extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Hero_slide_model');
	}

	/**
	 * Daftar slide.
	 */
	public function index()
	{
		$this->renderAdmin('admin/slider/index', array(
			'pageTitle' => 'Slider Beranda',
			'slides'    => $this->Hero_slide_model->getAll(),
		));
	}

	/**
	 * Form tambah slide.
	 */
	public function create()
	{
		$this->renderAdmin('admin/slider/form', array(
			'pageTitle' => 'Tambah Slide',
			'slide'     => NULL,
			'isEdit'    => FALSE,
		));
	}

	/**
	 * Form edit slide.
	 *
	 * @param	int $id
	 */
	public function edit($id = 0)
	{
		$slide = $this->Hero_slide_model->getById((int) $id);

		if ( ! $slide)
		{
			$this->session->set_flashdata('error', 'Slide tidak ditemukan.');
			redirect('admin/slider');
		}

		$this->renderAdmin('admin/slider/form', array(
			'pageTitle' => 'Edit Slide',
			'slide'     => $slide,
			'isEdit'    => TRUE,
		));
	}

	/**
	 * Simpan slide baru (POST).
	 */
	public function store()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/slider');
		}

		$this->setValidationRules();

		if ($this->form_validation->run() === FALSE)
		{
			return $this->renderAdmin('admin/slider/form', array(
				'pageTitle' => 'Tambah Slide',
				'slide'     => NULL,
				'isEdit'    => FALSE,
			));
		}

		$data = $this->collectData();
		$data['created_at'] = date('Y-m-d H:i:s');

		$this->Hero_slide_model->create($data);

		$this->session->set_flashdata('success', 'Slide berhasil ditambahkan.');
		redirect('admin/slider');
	}

	/**
	 * Update slide (POST).
	 *
	 * @param	int $id
	 */
	public function update($id = 0)
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/slider');
		}

		$slide = $this->Hero_slide_model->getById((int) $id);

		if ( ! $slide)
		{
			$this->session->set_flashdata('error', 'Slide tidak ditemukan.');
			redirect('admin/slider');
		}

		$this->setValidationRules();

		if ($this->form_validation->run() === FALSE)
		{
			return $this->renderAdmin('admin/slider/form', array(
				'pageTitle' => 'Edit Slide',
				'slide'     => $slide,
				'isEdit'    => TRUE,
			));
		}

		$this->Hero_slide_model->update($slide['id'], $this->collectData());

		$this->session->set_flashdata('success', 'Slide berhasil diperbarui.');
		redirect('admin/slider');
	}

	/**
	 * Hapus slide (POST).
	 */
	public function delete()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/slider');
		}

		$id = (int) $this->input->post('id', TRUE);
		$slide = $this->Hero_slide_model->getById($id);

		if ( ! $slide)
		{
			$this->session->set_flashdata('error', 'Slide tidak ditemukan.');
			redirect('admin/slider');
		}

		$this->Hero_slide_model->delete($id);
		$this->session->set_flashdata('success', 'Slide dihapus.');
		redirect('admin/slider');
	}

	/**
	 * Aturan validasi form slide.
	 */
	private function setValidationRules()
	{
		$this->form_validation->set_rules('label', 'Label', 'trim|max_length[100]');
		$this->form_validation->set_rules('title', 'Judul', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('text', 'Deskripsi', 'trim|max_length[300]');
		// Cabang path lokal HARUS sebelum "|" agar regex tidak terpotong parser CI3.
		$this->form_validation->set_rules('image_url', 'Gambar', 'trim|max_length[500]|regex_match[#^(assets/(?!.*\.\.)[A-Za-z0-9._/ -]+|https?://\S+)$#i]');
		$this->form_validation->set_rules('cta_text', 'Teks tombol', 'trim|max_length[50]');
		$this->form_validation->set_rules('cta_url', 'Tautan tombol', 'trim|max_length[255]|regex_match[~^((?!/)[A-Za-z0-9._/?=&%#-]+|https?://\S+)$~i]');
		$this->form_validation->set_rules('sort_order', 'Urutan', 'trim|is_natural');
	}

	/**
	 * Ambil input form yang sudah tervalidasi menjadi array kolom.
	 *
	 * @return	array
	 */
	private function collectData()
	{
		$sortOrder = $this->input->post('sort_order', TRUE);

		return array(
			'label'      => $this->postOrNull('label'),
			'title'      => trim((string) $this->input->post('title', TRUE)),
			'text'       => $this->postOrNull('text'),
			'image_url'  => $this->postOrNull('image_url'),
			'cta_text'   => $this->postOrNull('cta_text'),
			'cta_url'    => $this->postOrNull('cta_url'),
			'sort_order' => ($sortOrder === '' OR $sortOrder === NULL) ? 0 : (int) $sortOrder,
			'is_active'  => $this->input->post('is_active') ? 1 : 0,
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
