<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Produk + varian (harga & stok ada di varian) — tabel `products`, `product_variants`.
 */
class Product_model extends CI_Model {

	/**
	 * Daftar produk aktif untuk katalog.
	 * Menyertakan nama kategori, harga terendah, dan total stok (agregasi varian).
	 *
	 * @param	int|null $categoryId Filter kategori (NULL = semua)
	 * @param	int       $limit      0 = tanpa limit
	 * @return	array
	 */
	public function getPublished($categoryId = NULL, $limit = 0)
	{
		$this->db
			->select('products.*, categories.name AS category_name, MIN(product_variants.price) AS min_price, SUM(product_variants.stock) AS total_stock', FALSE)
			->join('categories', 'categories.id = products.category_id', 'left')
			->join('product_variants', 'product_variants.product_id = products.id', 'left')
			->where('products.is_active', 1);

		if ($categoryId)
		{
			$this->db->where('products.category_id', (int) $categoryId);
		}

		$this->db->group_by('products.id')->order_by('products.id', 'DESC');

		if ((int) $limit > 0)
		{
			$this->db->limit((int) $limit);
		}

		return $this->db->get('products')->result_array();
	}

	/**
	 * Semua produk (termasuk non-aktif) untuk halaman admin.
	 *
	 * @return	array
	 */
	public function getAllAdmin()
	{
		return $this->db
			->select('products.*, categories.name AS category_name, COUNT(product_variants.id) AS variant_count, SUM(product_variants.stock) AS total_stock', FALSE)
			->join('categories', 'categories.id = products.category_id', 'left')
			->join('product_variants', 'product_variants.product_id = products.id', 'left')
			->group_by('products.id')
			->order_by('products.name', 'ASC')
			->get('products')
			->result_array();
	}

	/**
	 * Detail produk + daftar variannya.
	 *
	 * @return	array|null
	 */
	public function getDetail($id)
	{
		$product = $this->db
			->select('products.*, categories.name AS category_name', FALSE)
			->join('categories', 'categories.id = products.category_id', 'left')
			->where('products.id', (int) $id)
			->get('products')
			->row_array();

		if ( ! $product)
		{
			return NULL;
		}

		$product['variants'] = $this->getVariants($product['id']);
		return $product;
	}

	/**
	 * Varian milik satu produk, termurah dulu.
	 *
	 * @return	array
	 */
	public function getVariants($productId)
	{
		return $this->db
			->where('product_id', (int) $productId)
			->order_by('price', 'ASC')
			->get('product_variants')
			->result_array();
	}

	/**
	 * Ambil beberapa varian sekaligus (dipakai keranjang), hasil di-key variant_id.
	 * Hanya varian milik produk aktif.
	 *
	 * @return	array
	 */
	public function getVariantsByIds(array $ids)
	{
		$ids = array_filter(array_map('intval', $ids));
		if ( ! $ids)
		{
			return array();
		}

		$rows = $this->db
			->select('product_variants.*, products.name AS product_name, products.is_active', FALSE)
			->join('products', 'products.id = product_variants.product_id', 'left')
			->where_in('product_variants.id', $ids)
			->where('products.is_active', 1)
			->get('product_variants')
			->result_array();

		$keyed = array();
		foreach ($rows as $row)
		{
			$keyed[(int) $row['id']] = $row;
		}

		return $keyed;
	}

	/**
	 * Produk sekategori (untuk "Produk lainnya" di halaman detail).
	 *
	 * @return	array
	 */
	public function getRelated($productId, $categoryId, $limit = 4)
	{
		return $this->db
			->select('products.*, categories.name AS category_name, MIN(product_variants.price) AS min_price, SUM(product_variants.stock) AS total_stock', FALSE)
			->join('categories', 'categories.id = products.category_id', 'left')
			->join('product_variants', 'product_variants.product_id = products.id', 'left')
			->where('products.is_active', 1)
			->where('products.category_id', (int) $categoryId)
			->where('products.id !=', (int) $productId)
			->group_by('products.id')
			->order_by('products.id', 'DESC')
			->limit((int) $limit)
			->get('products')
			->result_array();
	}

	/**
	 * 10 produk yang paling sering dilihat (untuk dashboard admin).
	 *
	 * @return	array
	 */
	public function getTopViewed($limit = 10)
	{
		return $this->db
			->select('products.id, products.name, products.category_id, COUNT(page_views.id) AS view_count', FALSE)
			->join('page_views', 'page_views.product_id = products.id', 'inner')
			->group_by('products.id')
			->order_by('view_count', 'DESC')
			->limit((int) $limit)
			->get('products')
			->result_array();
	}

	/**
	 * Varian dengan stok menipis (untuk dashboard admin).
	 *
	 * @return	array
	 */
	public function getLowStock($threshold = 5)
	{
		return $this->db
			->select('product_variants.*, products.name AS product_name', FALSE)
			->join('products', 'products.id = product_variants.product_id', 'left')
			->where('product_variants.stock <=', (int) $threshold)
			->order_by('product_variants.stock', 'ASC')
			->limit(10)
			->get('product_variants')
			->result_array();
	}

	/**
	 * @return	int
	 */
	public function countAll()
	{
		return (int) $this->db->count_all('products');
	}

	/**
	 * Cek duplikasi slug produk.
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
		return $this->db->count_all_results('products') > 0;
	}

	/**
	 * Slug unik dari nama produk: "Arabika Gayo" -> "arabika-gayo", dst.
	 *
	 * @return	string
	 */
	public function uniqueSlug($name, $excludeId = 0)
	{
		$slug = url_title($name, '-', TRUE);
		if ($slug === '')
		{
			$slug = 'produk';
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
	 * Simpan produk baru + variannya dalam satu transaksi.
	 *
	 * @return	int|FALSE ID produk baru, FALSE bila gagal
	 */
	public function create(array $product, array $variants)
	{
		$this->db->trans_start();
		$this->db->insert('products', $product);
		$productId = (int) $this->db->insert_id();
		$this->insertVariants($productId, $variants);
		$this->db->trans_complete();

		return ($this->db->trans_status() === FALSE) ? FALSE : $productId;
	}

	/**
	 * Update produk + ganti seluruh variannya dalam satu transaksi.
	 *
	 * @return	bool
	 */
	public function update($id, array $product, array $variants)
	{
		$this->db->trans_start();
		$this->db->where('id', (int) $id)->update('products', $product);
		$this->db->where('product_id', (int) $id)->delete('product_variants');
		$this->insertVariants((int) $id, $variants);
		$this->db->trans_complete();

		return $this->db->trans_status() !== FALSE;
	}

	/**
	 * Hapus produk (varian ikut terhapus via FK CASCADE).
	 *
	 * @return	bool
	 */
	public function delete($id)
	{
		$this->db->where('id', (int) $id);
		return $this->db->delete('products');
	}

	/**
	 * Insert massal varian milik satu produk.
	 *
	 * @param	array $variants Baris: array('label' => ..., 'price' => ..., 'stock' => ...)
	 */
	private function insertVariants($productId, array $variants)
	{
		if ( ! $variants OR ! $productId)
		{
			return;
		}

		$rows = array();
		foreach ($variants as $variant)
		{
			$rows[] = array(
				'product_id' => (int) $productId,
				'label'      => $variant['label'],
				'price'      => (int) $variant['price'],
				'stock'      => (int) $variant['stock'],
			);
		}

		$this->db->insert_batch('product_variants', $rows);
	}
}
