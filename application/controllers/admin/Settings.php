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
		$this->load->model('Admin_model');
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

	/**
	 * Form ubah username & password admin yang sedang login.
	 *
	 * Password saat ini wajib diverifikasi sebelum perubahan disimpan.
	 */
	public function account()
	{
		$adminId = (int) $this->session->userdata('adminId');
		$admin = $this->Admin_model->getById($adminId);

		// Sesi basi (admin dihapus) — paksa keluar.
		if ( ! $admin)
		{
			redirect('admin/logout');
			return;
		}

		$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('current_password', 'Password saat ini', 'trim|required');
		$this->form_validation->set_rules('new_password', 'Password baru', 'trim|min_length[8]|max_length[255]');
		$this->form_validation->set_rules('confirm_password', 'Ulangi password baru', 'trim|matches[new_password]');

		$formError = '';

		if ($this->form_validation->run() === TRUE)
		{
			$username = (string) $this->input->post('username', TRUE);
			$newPassword = (string) $this->input->post('new_password', TRUE);

			// Verifikasi password saat ini SEBELUM ada perubahan tersimpan.
			if ( ! password_verify((string) $this->input->post('current_password', TRUE), $admin['password_hash']))
			{
				$formError = 'Password saat ini salah. Perubahan tidak disimpan.';
			}
			elseif ($this->Admin_model->usernameExists($username, $adminId))
			{
				$formError = 'Username tersebut sudah dipakai admin lain.';
			}
			else
			{
				$this->Admin_model->updateAccount(
					$adminId,
					$username,
					$newPassword !== '' ? password_hash($newPassword, PASSWORD_DEFAULT) : NULL
				);

				$this->session->set_flashdata('success', $newPassword !== ''
					? 'Username dan password berhasil diubah.'
					: 'Username berhasil diubah.');
				redirect('admin/settings/account');
				return;
			}
		}

		// Gagal validasi/manual: render ulang form dengan input yang sudah diisi.
		$this->renderAdmin('admin/settings/account', array(
			'pageTitle' => 'Ubah Akun',
			'admin'     => $admin,
			'formError' => $formError,
		));
	}

	/**
	 * Proses form ubah akun (POST).
	 */
	public function accountUpdate()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/settings/account');
			return;
		}

		$this->account();
	}
}
