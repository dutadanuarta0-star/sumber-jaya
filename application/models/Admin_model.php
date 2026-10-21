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

	/**
	 * Ambil admin berdasar ID.
	 *
	 * @return	array|null
	 */
	public function getById($id)
	{
		$row = $this->db->where('id', (int) $id)->get('admins')->row_array();
		return $row ? $row : NULL;
	}

	/**
	 * Apakah username sudah dipakai admin lain?
	 *
	 * @param	int $excludeId ID admin yang sedang dikecualikan (diri sendiri)
	 * @return	bool
	 */
	public function usernameExists($username, $excludeId)
	{
		return $this->db
			->where('username', $username)
			->where('id !=', (int) $excludeId)
			->count_all_results('admins') > 0;
	}

	/**
	 * Simpan username; password_hash hanya diupdate bila $hash diisi.
	 *
	 * @return	bool
	 */
	public function updateAccount($id, $username, $hash = NULL)
	{
		$data = array('username' => $username);
		if ($hash !== NULL)
		{
			$data['password_hash'] = $hash;
		}

		$this->db->where('id', (int) $id);
		return $this->db->update('admins', $data);
	}
}
