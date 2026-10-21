<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kategori produk — tabel `categories`.
 */
class Category_model extends CI_Model {

	/**
	 * Semua kategori (untuk navbar & filter).
	 *
	 * @return	array
	 */
	public function getAll()
	{
		return $this->db->order_by('name', 'ASC')->get('categories')->result_array();
	}

	/**
	 * Semua kategori + jumlah produknya (untuk halaman admin).
	 *
	 * @return	array
	 */
	public function getAllWithCount()
	{
		return $this->db
			->select('categories.*, COUNT(products.id) AS product_count', FALSE)
			->join('products', 'products.category_id = categories.id', 'left')
			->group_by('categories.id')
			->order_by('categories.name', 'ASC')
			->get('categories')
			->result_array();
	}

	/**
	 * Kategori berdasar ID.
	 *
	 * @return	array|null
	 */
	public function getById($id)
	{
		$row = $this->db->where('id', (int) $id)->get('categories')->row_array();
		return $row ? $row : NULL;
	}

	/**
	 * Kategori berdasar slug.
	 *
	 * @return	array|null
	 */
	public function getBySlug($slug)
	{
		$row = $this->db->where('slug', $slug)->get('categories')->row_array();
		return $row ? $row : NULL;
	}

	/**
	 * Jumlah produk pada kategori.
	 *
	 * @return	int
	 */
	public function countProducts($id)
	{
		return (int) $this->db
			->where('category_id', (int) $id)
			->count_all_results('products');
	}

	/**
	 * Cek duplikasi slug (untuk validasi & pembuat slug unik).
	 *
	 * @return	bool
	 */
	public function slugExists($slug, $excludeId = 0)
	{
		$this->db->where('slug', $slug);
		if ((int) $excludeId > 0)
		{
			$this->db->where('id !=', (int) $excludeId);
		}
		return $this->db->count_all_results('categories') > 0;
	}

	/**
	 * Buat slug unik dari nama kategori: "Kopi Susu" -> "kopi-susu", "kopi-susu-2", dst.
	 *
	 * @return	string
	 */
	public function uniqueSlug($name, $excludeId = 0)
	{
		$slug = url_title($name, '-', TRUE);
		if ($slug === '')
		{
			$slug = 'kategori';
		}

		$base = $slug;
		$i = 2;
		while ($this->slugExists($slug, $excludeId))
		{
			$slug = $base.'-'.$i;
			$i++;
		}

		return $slug;
	}

	/**
	 * @return	int insert_id
	 */
	public function create(array $data)
	{
		$this->db->insert('categories', $data);
		return (int) $this->db->insert_id();
	}

	/**
	 * @return	bool
	 */
	public function update($id, array $data)
	{
		$this->db->where('id', (int) $id);
		return $this->db->update('categories', $data);
	}

	/**
	 * Hapus kategori. Produk ikut terhapus bila ada (FK CASCADE),
	 * karena itu controller menolak hapus kategori yang masih berisi produk.
	 *
	 * @return	bool
	 */
	public function delete($id)
	{
		$this->db->where('id', (int) $id);
		return $this->db->delete('categories');
	}
}
