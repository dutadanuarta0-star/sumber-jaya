<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Katalog + detail produk.
 *
 * Catatan: add-to-cart diproses oleh Cart::add (POST + CSRF),
 * jadi form di halaman detail mengarah ke controller Cart.
 */
class Products extends Public_Controller {

	/**
	 * Katalog produk, dengan filter opsional ?kategori=slug.
	 */
	public function index()
	{
		$this->recordView();

		$activeCategory = NULL;
		$slug = (string) $this->input->get('kategori', TRUE);

		if ($slug !== '')
		{
			$activeCategory = $this->Category_model->getBySlug($slug);

			// Slug tidak dikenal: kembali ke katalog penuh.
			if ( ! $activeCategory)
			{
				redirect('products');
			}
		}

		$data = array(
			'pageTitle'      => 'Produk',
			'products'       => $this->Product_model->getPublished($activeCategory ? $activeCategory['id'] : NULL),
			'activeCategory' => $activeCategory,
		);

		$this->render('products/index', $data);
	}

	/**
	 * Detail produk + pilihan varian.
	 *
	 * @param	int $id
	 */
	public function detail($id = 0)
	{
		$product = $this->Product_model->getDetail((int) $id);

		if ( ! $product OR (int) $product['is_active'] !== 1)
		{
			show_404();
		}

		$this->recordView($product['id']);

		$data = array(
			'pageTitle' => $product['name'],
			'product'   => $product,
			'related'   => $this->Product_model->getRelated($product['id'], $product['category_id']),
			'maxQty'    => 20, // batas wajar per varian per transaksi
		);

		$this->render('products/detail', $data);
	}
}
