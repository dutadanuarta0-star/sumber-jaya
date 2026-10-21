<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller aplikasi.
 *
 * - MY_Controller      : data identitas toko untuk semua halaman.
 * - Public_Controller  : halaman publik (cookie pengunjung, tracking, layout situs).
 * - Admin_Controller   : halaman admin (guard login + layout admin).
 *
 * Semua file di application/core/ dimuat otomatis oleh CI3.
 */
class MY_Controller extends CI_Controller {

	/** @var array Baris tabel `settings` (identitas toko). */
	protected $storeData = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Setting_model');
		$this->storeData = $this->Setting_model->getRow();
	}
}

class Public_Controller extends MY_Controller {

	/** @var string ID pengunjung unik (cookie sj_vid, umur 1 tahun). */
	protected $visitorId = '';

	/** @var bool Cegah 1 request menulis lebih dari 1 baris page_views. */
	private $viewRecorded = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Category_model', 'Product_model', 'PageView_model'));
		$this->load->library('cart_lib');
		$this->initVisitorId();
	}

	/**
	 * Render halaman publik: layout header + konten + footer.
	 */
	protected function render($view, $data = array())
	{
		$data = array_merge(array(
			'store'          => $this->storeData,
			'navCategories'  => $this->Category_model->getAll(),
			'cartCount'      => $this->cart_lib->count(),
			'pageTitle'      => '',
		), $data);

		$this->load->view('layout/header', $data);
		$this->load->view($view, $data);
		$this->load->view('layout/footer', $data);
	}

	/**
	 * Rekam kunjungan (untuk statistik dashboard admin).
	 *
	 * @param	int|null $productId ID produk jika sedang membuka halaman detail.
	 */
	protected function recordView($productId = NULL)
	{
		if ($this->viewRecorded)
		{
			return;
		}
		$this->viewRecorded = TRUE;
		$this->PageView_model->record($this->visitorId, $productId);
	}

	/**
	 * Ambil/buat cookie pengunjung untuk hitung unik per hari.
	 */
	private function initVisitorId()
	{
		$vid = (string) $this->input->cookie('sj_vid', TRUE);

		// Cookie lama/aneh dianggap tidak ada, lalu dibuat baru dengan masa
		// berlaku 1 tahun. $expire dikirim dalam detik (startTimestamp berlaku
		// di server ini) sehingga maksudnya eksplisit dan tidak bergantung
		// pada tafsir "absolute timestamp" setcookie().
		if ($vid === '' OR ! preg_match('/^[a-f0-9]{32}$/', $vid))
		{
			$vid = bin2hex(random_bytes(16));
			$this->input->set_cookie(array(
				'name'     => 'sj_vid',
				'value'    => $vid,
				'expire'   => 60 * 60 * 24 * 365,
				'path'     => '/',
				'httponly' => TRUE,
			));
		}

		$this->visitorId = $vid;
	}
}

class Admin_Controller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		// Guard: semua method turunan wajib sudah login.
		if ( ! $this->session->userdata('adminId'))
		{
			redirect('admin/login');
		}
	}

	/**
	 * Render halaman admin dengan layout admin.
	 */
	protected function renderAdmin($view, $data = array())
	{
		$data = array_merge(array(
			'store'     => $this->storeData,
			'adminName' => (string) $this->session->userdata('adminName'),
			'pageTitle' => '',
		), $data);

		$this->load->view('admin/layout/header', $data);
		$this->load->view($view, $data);
		$this->load->view('admin/layout/footer', $data);
	}
}
