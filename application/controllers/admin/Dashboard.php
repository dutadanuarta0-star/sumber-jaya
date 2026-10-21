<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard admin: statistik kunjungan + produk paling sering dilihat.
 */
class Dashboard extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('PageView_model', 'Product_model', 'Category_model'));
	}

	public function index()
	{
		$data = array(
			'pageTitle'      => 'Dashboard',
			'visitorsToday'  => $this->PageView_model->countUniqueLastDays(1),
			'visitors7'      => $this->PageView_model->countUniqueLastDays(7),
			'visitors30'     => $this->PageView_model->countUniqueLastDays(30),
			'dailyVisitors'  => $this->PageView_model->dailyUnique(7),
			'topProducts'    => $this->Product_model->getTopViewed(10),
			'lowStock'       => $this->Product_model->getLowStock(5),
			'totalProducts'  => $this->Product_model->countAll(),
			'totalCategories'=> count($this->Category_model->getAll()),
		);

		$this->renderAdmin('admin/dashboard', $data);
	}
}
