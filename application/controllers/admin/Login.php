<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Login / logout admin.
 *
 * Rate limit sederhana berbasis session: maksimal 5 percobaan gagal
 * per 15 menit untuk mencegah brute force tanpa perlu tabel tambahan.
 */
class Login extends MY_Controller {

	const MAX_ATTEMPTS = 5;
	const LOCK_SECONDS = 900; // 15 menit

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
	}

	public function index()
	{
		// Sudah login: langsung ke dashboard.
		if ($this->session->userdata('adminId'))
		{
			redirect('admin/dashboard');
		}

		$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[100]');

		if ($this->form_validation->run() === FALSE)
		{
			$this->load->view('admin/login', array(
				'store'     => $this->storeData,
				'pageTitle' => 'Login Admin',
				'lockedFor' => $this->lockedFor(),
			));
			return;
		}

		if ($this->lockedFor() > 0)
		{
			$this->load->view('admin/login', array(
				'store'     => $this->storeData,
				'pageTitle' => 'Login Admin',
				'lockedFor' => $this->lockedFor(),
			));
			return;
		}

		$username = (string) $this->input->post('username', TRUE);
		$password = (string) $this->input->post('password');

		$admin = $this->Admin_model->getByUsername($username);

		// Pesan error dibuat generik agar tidak membocorkan username yang valid.
		if ( ! $admin OR ! password_verify($password, $admin['password_hash']))
		{
			$this->registerFailedAttempt();

			$this->session->set_flashdata('error', 'Username atau password salah.');
			redirect('admin/login');
		}

		// Login berhasil: perbarui hash bila algoritmanya sudah usang, lalu
		// regenerasi session id untuk mencegah session fixation.
		if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT))
		{
			$this->Admin_model->updatePasswordHash($admin['id'], password_hash($password, PASSWORD_DEFAULT));
		}

		$this->session->sess_regenerate(TRUE);
		$this->session->unset_userdata('login_attempts');
		$this->session->set_userdata(array(
			'adminId'   => (int) $admin['id'],
			'adminName' => (string) ($admin['name'] ?: $admin['username']),
		));

		$this->session->set_flashdata('success', 'Selamat datang, '.($admin['name'] ?: $admin['username']).'.');
		redirect('admin/dashboard');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('admin/login');
	}

	/**
	 * Catat percobaan login gagal + waktu percobaan terakhir.
	 */
	private function registerFailedAttempt()
	{
		$attempts = (int) $this->session->userdata('login_attempts') + 1;

		$this->session->set_userdata(array(
			'login_attempts'      => $attempts,
			'login_last_attempt'  => time(),
		));
	}

	/**
	 * Sisa waktu terkunci (detik). 0 = tidak terkunci.
	 *
	 * @return	int
	 */
	private function lockedFor()
	{
		$attempts = (int) $this->session->userdata('login_attempts');

		if ($attempts < self::MAX_ATTEMPTS)
		{
			return 0;
		}

		$remaining = self::LOCK_SECONDS - (time() - (int) $this->session->userdata('login_last_attempt'));

		if ($remaining <= 0)
		{
			// Masa kunci berakhir: reset hitungan.
			$this->session->unset_userdata(array('login_attempts', 'login_last_attempt'));
			return 0;
		}

		return $remaining;
	}
}
