<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman beranda.
 */
class Home extends Public_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Hero_slide_model');
	}

	public function index()
	{
		$this->recordView();

		$this->render('home', $this->homeData());
	}

	/**
	 * Halaman bingkai beranda ukuran desktop (1440 x 900).
	 *
	 * Isi beranda dimuat lewat iframe supaya layout desktop tetap terpakai
	 * berapa pun lebar jendela browser (media query mengikuti viewport, bukan
	 * lebar kotak). Halaman ini tidak merekam kunjungan.
	 */
	public function desktop()
	{
		$this->renderDesktop();
	}

	/**
	 * Isi beranda untuk iframe pada halaman tampilan desktop.
	 *
	 * Sama persis dengan beranda biasa, hanya tanpa perekaman kunjungan
	 * (supaya statistik tidak dobel) dan tanpa bingkai.
	 */
	public function desktopIsi()
	{
		$this->render('home', $this->homeData());
	}

	/**
	 * Data yang dipakai beranda: kategori, produk unggulan, dan slide hero.
	 *
	 * @return	array
	 */
	private function homeData()
	{
		return array(
			'pageTitle'  => '',
			'useSwiper'  => TRUE,
			'categories' => $this->Category_model->getAllWithCount(),
			'featured'   => $this->Product_model->getPublished(NULL, 4),
			'heroSlides' => $this->Hero_slide_model->getActive(),
		);
	}

	/**
	 * Render halaman shell tampilan desktop (bingkai + iframe).
	 */
	private function renderDesktop()
	{
		$this->load->view('home/desktop', array(
			'store'       => $this->storeData,
			'pageTitle'   => 'Beranda — Tampilan Desktop',
			'frameUrl'    => site_url('beranda-desktop/isi'),
			'frameWidth'  => 1440,
			'frameHeight' => 900,
		));
	}
}
