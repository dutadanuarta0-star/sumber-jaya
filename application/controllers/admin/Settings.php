<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin — pengaturan identitas toko (tabel `settings`, 1 baris).
 *
 * `about_text` sekaligus menjadi isi halaman "Tentang Kami" di sisi publik.
 */
class Settings extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->form_validation->set_rules('store_name', 'Nama toko', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('whatsapp_number', 'Nomor WhatsApp', 'trim|required|max_length[30]|regex_match[/^[0-9+\-\s()]+$/]');
		$this->form_validation->set_rules('phone', 'Telepon', 'trim|max_length[30]');
		$this->form_validation->set_rules('address', 'Alamat', 'trim|max_length[255]');
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]|valid_email');
		$this->form_validation->set_rules('about_text', 'Tentang kami', 'trim');

		if ($this->form_validation->run() === TRUE)
		{
			// Simpan nomor WhatsApp dalam format internasional tanpa simbol
			// agar langsung bisa dipakai pada tautan wa.me.
			$number = preg_replace('/[^0-9]/', '', (string) $this->input->post('whatsapp_number', TRUE));

			// "08xx" -> "628xx" (awalan Indonesia).
			if (strpos($number, '0') === 0)
			{
				$number = '62'.substr($number, 1);
			}

			$this->Setting_model->updateRow(array(
				'store_name'      => (string) $this->input->post('store_name', TRUE),
				'whatsapp_number' => $number,
				'phone'           => (string) $this->input->post('phone', TRUE),
				'address'         => (string) $this->input->post('address', TRUE),
				'email'           => (string) $this->input->post('email', TRUE),
				'about_text'      => (string) $this->input->post('about_text', TRUE),
			));

			$this->session->set_flashdata('success', 'Pengaturan toko disimpan.');
			redirect('admin/settings');
		}

		$this->renderAdmin('admin/settings', array(
			'pageTitle' => 'Pengaturan Toko',
			'store'     => $this->storeData,
		));
	}
}
