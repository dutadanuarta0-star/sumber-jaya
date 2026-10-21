<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin — tabel `admins`.
 */
class Admin_model extends CI_Model {

	/**
	 * Ambil admin berdasar username (case-insensitive tidak diperlukan,
	 * username disimpan apa adanya).
	 *
	 * @return	array|null
	 */
	public function getByUsername($username)
	{
		$row = $this->db->where('username', $username)->get('admins')->row_array();
		return $row ? $row : NULL;
	}

	/**
	 * Perbarui hash password (dipakai saat rehash setelah login sukses).
	 *
	 * @return	bool
	 */
	public function updatePasswordHash($id, $hash)
	{
		$this->db->where('id', (int) $id);
		return $this->db->update('admins', array('password_hash' => $hash));
	}
}
