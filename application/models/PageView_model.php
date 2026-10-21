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
	 * Selalu mengembalikan N baris urut lama -> baru (termasuk hari ini),
	 * hari tanpa kunjungan diisi 0 agar siap digambar sebagai chart.
	 *
	 * @return	array Baris: array('day' => 'Y-m-d', 'label' => 'd M', 'visitors' => int)
	 */
	public function dailyUnique($days = 7)
	{
		$days = (int) $days;
		$since = date('Y-m-d 00:00:00', strtotime('-'.($days - 1).' days'));

		$rows = $this->db
			->select('DATE(created_at) AS day, COUNT(DISTINCT visitor_id) AS visitors', FALSE)
			->where('created_at >=', $since)
			->group_by('day')
			->get('page_views')
			->result_array();

		$byDay = array();
		foreach ($rows as $row)
		{
			$byDay[$row['day']] = (int) $row['visitors'];
		}

		$series = array();
		for ($i = $days - 1; $i >= 0; $i--)
		{
			$date = date('Y-m-d', strtotime('-'.$i.' days'));
			$series[] = array(
				'day'      => $date,
				'label'    => date('d M', strtotime($date)),
				'visitors' => isset($byDay[$date]) ? $byDay[$date] : 0,
			);
		}

		return $series;
	}
}
