<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Statistik kunjungan — tabel `page_views`.
 *
 * visitor_id berasal dari cookie sj_vid sehingga "pengunjung unik"
 * bisa dihitung per hari dengan COUNT(DISTINCT visitor_id).
 */
class PageView_model extends CI_Model {

	/**
	 * Rekam satu kunjungan halaman.
	 *
	 * @param	string    $visitorId Cookie pengunjung (32 char hex)
	 * @param	int|null  $productId ID produk bila halaman detail produk
	 */
	public function record($visitorId, $productId = NULL)
	{
		$this->db->insert('page_views', array(
			'visitor_id' => (string) $visitorId,
			'product_id' => $productId ? (int) $productId : NULL,
			'created_at' => date('Y-m-d H:i:s'),
		));
	}

	/**
	 * Jumlah pengunjung unik pada N hari terakhir (termasuk hari ini).
	 *
	 * @return	int
	 */
	public function countUniqueLastDays($days)
	{
		$since = date('Y-m-d 00:00:00', strtotime('-'.((int) $days - 1).' days'));

		$row = $this->db
			->select('COUNT(DISTINCT visitor_id) AS visitors', FALSE)
			->where('created_at >=', $since)
			->get('page_views')
			->row_array();

		return (int) ($row['visitors'] ?? 0);
	}

	/**
	 * Pengunjung unik per hari untuk N hari terakhir.
	 *
	 * @return	array Baris: array('day' => 'Y-m-d', 'visitors' => int)
	 */
	public function dailyUnique($days = 7)
	{
		$since = date('Y-m-d 00:00:00', strtotime('-'.((int) $days - 1).' days'));

		return $this->db
			->select('DATE(created_at) AS day, COUNT(DISTINCT visitor_id) AS visitors', FALSE)
			->where('created_at >=', $since)
			->group_by('day')
			->order_by('day', 'DESC')
			->get('page_views')
			->result_array();
	}
}
