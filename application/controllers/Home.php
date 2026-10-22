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

		$data = array(
			'pageTitle'    => '',
			'categories'   => $this->Category_model->getAllWithCount(),
			'featured'     => $this->Product_model->getPublished(NULL, 4),
			'heroSlides'   => $this->Hero_slide_model->getActive(),
		);

		$this->render('home', $data);
	}
}
