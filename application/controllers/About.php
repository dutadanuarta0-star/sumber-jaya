<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Halaman "Tentang Kami". Konten diambil dari pengaturan identitas toko admin.
 */
class About extends Public_Controller {

	public function index()
	{
		$this->recordView();

		$this->render('about', array('pageTitle' => 'Tentang Kami'));
	}
}
