<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper umum aplikasi. Di-autoload lewat config/autoload.php.
 */

	if ( ! function_exists('esc'))
{
	/**
	 * Escape output HTML (pengganti helper esc() milik CI4).
	 *
	 * Dipakai di semua view agar output aman dari XSS. Nama sengaja
	 * dibuat singkat supaya view tetap ringkas.
	 *
	 * @param	mixed  $value
	 * @param	string $context 'html' atau 'attr'
	 * @return	string
	 */
	function esc($value, $context = 'html')
	{
		$value = html_escape($value);

		// Untuk atribut, kutip tunggal juga perlu di-encode karena
		// sebagian atribut memakai kutip tunggal.
		if ($context === 'attr')
		{
			$value = str_replace("'", '&#39;', $value);
		}

		return $value;
	}
}

if ( ! function_exists('rupiah'))
{
	/**
	 * Format angka rupiah: 45000 -> "Rp45.000"
	 */
	function rupiah($number)
	{
		return 'Rp'.number_format((int) $number, 0, ',', '.');
	}
}

if ( ! function_exists('wa_link'))
{
	/**
	 * Tautan chat WhatsApp dari nomor toko.
	 *
	 * Nomor dinormalisasi menjadi digit saja supaya nilai lama seperti
	 * "+62 813-..." tetap menghasilkan tautan wa.me yang valid. Pesan
	 * opsional ikut di-encode sebagai parameter `text`.
	 *
	 * @param	string $number  Nomor WhatsApp toko
	 * @param	string $message Pesan pembuka (opsional)
	 * @return	string Tautan wa.me, atau string kosong bila nomor tidak valid
	 */
	function wa_link($number, $message = '')
	{
		$digits = preg_replace('/[^0-9]/', '', (string) $number);

		if ($digits === '')
		{
			return '';
		}

		return 'https://wa.me/'.$digits
			.($message !== '' ? '?text='.rawurlencode($message) : '');
	}
}

if ( ! function_exists('img_src'))
{
	/**
	 * Sumber gambar produk: URL eksternal dipakai apa adanya,
	 * path lokal (diawali "assets/...") dibaca dari base_url.
	 *
	 * Contoh lokal: assets/img/produk/gayo.jpg
	 * -> http://localhost/Sumber%20jaya/assets/img/produk/gayo.jpg
	 *
	 * @param	string $value Isi kolom products.image_url
	 * @return	string
	 */
	function img_src($value)
	{
		$value = trim((string) $value);
		if ($value === '' OR preg_match('#^https?://#i', $value))
		{
			return $value;
		}

		// Spasi pada nama file di-encode agar valid sebagai URL.
		return str_replace(' ', '%20', base_url($value));
	}
}
