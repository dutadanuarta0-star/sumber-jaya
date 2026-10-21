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
	 * Katalog produk, dengan filter opsional ?kategori=slug dan pencarian ?q=kata.
	 */
	public function index()
	{
		$this->recordView();

		$activeCategory = NULL;
		$slug = (string) $this->input->get('kategori', TRUE);
		$search = trim((string) $this->input->get('q', TRUE));

		// Batasi panjang kata kunci agar query tetap wajar.
		if (mb_strlen($search) > 100)
		{
			$search = mb_substr($search, 0, 100);
		}

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
			'products'       => $this->Product_model->getPublished($activeCategory ? $activeCategory['id'] : NULL, 0, $search),
			'activeCategory' => $activeCategory,
			'search'         => $search,
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
