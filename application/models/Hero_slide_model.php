<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Slider hero beranda (tabel `hero_slides`).
 *
 * Jumlah slide bebas — admin menambah/menghapus baris di sini.
 * Urutan tampil mengikuti `sort_order`, lalu `id`.
 */
class Hero_slide_model extends CI_Model {

	/**
	 * Slide yang aktif, siap ditampilkan di beranda.
	 *
	 * @return	array
	 */
	public function getActive()
	{
		return $this->db
			->where('is_active', 1)
			->order_by('sort_order', 'ASC')
			->order_by('id', 'ASC')
			->get('hero_slides')
			->result_array();
	}

	/**
	 * Semua slide (termasuk yang nonaktif) untuk halaman admin.
	 *
	 * @return	array
	 */
	public function getAll()
	{
		return $this->db
			->order_by('sort_order', 'ASC')
			->order_by('id', 'ASC')
			->get('hero_slides')
			->result_array();
	}

	/**
	 * @param	int $id
	 * @return	array|NULL
	 */
	public function getById($id)
	{
		return $this->db
			->where('id', (int) $id)
			->get('hero_slides')
			->row_array();
	}

	/**
	 * @param	array $data
	 * @return	int ID slide baru
	 */
	public function create(array $data)
	{
		$this->db->insert('hero_slides', $data);

		return (int) $this->db->insert_id();
	}

	/**
	 * @param	int   $id
	 * @param	array $data
	 */
	public function update($id, array $data)
	{
		$this->db
			->where('id', (int) $id)
			->update('hero_slides', $data);
	}

	/**
	 * @param	int $id
	 */
	public function delete($id)
	{
		$this->db
			->where('id', (int) $id)
			->delete('hero_slides');
	}
}
