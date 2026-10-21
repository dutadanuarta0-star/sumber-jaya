<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Identitas toko — tabel `settings` (hanya 1 baris dengan id = 1).
 */
class Setting_model extends CI_Model {

	/**
	 * Ambil baris settings, dengan fallback default bila belum ada.
	 *
	 * @return	array
	 */
	public function getRow()
	{
		$row = $this->db->where('id', 1)->get('settings')->row_array();

		if ($row)
		{
			return $row;
		}

		return array(
			'store_name'      => 'Sumber Jaya',
			'whatsapp_number' => '',
			'phone'           => '',
			'address'         => '',
			'email'           => '',
			'about_text'      => '',
		);
	}

	/**
	 * Update baris settings.
	 *
	 * @return	bool
	 */
	public function updateRow(array $data)
	{
		$this->db->where('id', 1);
		return $this->db->update('settings', $data);
	}
}
