<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unggah gambar dari komputer admin ke folder aset.
 *
 * Dipakai form produk (assets/img/produk) dan slider beranda (assets/img/slider).
 * Nama file diacak (encrypt_name) dan tipe dibatasi ke gambar, sehingga file
 * berbahaya tidak bisa masuk. Path manual/URL lama tetap didukung — file yang
 * dipilih menang bila keduanya diisi.
 */
class Image_upload {

	/** @var CI_Controller */
	protected $CI;

	/** @var string Ekstensi yang diterima (mimes.php harus punya pemetaannya). */
	protected $allowedTypes = 'jpg|jpeg|png|webp';

	/** @var int Ukuran maksimal file dalam KB. */
	protected $maxSizeKb = 3072;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * Proses file dari input tertentu (bila ada).
	 *
	 * @param	string $field  Nama input file (mis. "image_file")
	 * @param	string $folder Folder tujuan relatif terhadap root proyek
	 * @return	array  array('path' => string|NULL, 'error' => string|NULL)
	 *                path & error dua-duanya NULL = tidak ada file dipilih.
	 */
	public function upload($field, $folder)
	{
		$result = array('path' => NULL, 'error' => NULL);

		if (empty($_FILES[$field]['name']))
		{
			return $result;
		}

		$folder = trim(str_replace('\\', '/', (string) $folder), '/');
		$uploadPath = FCPATH.$folder.'/';

		if ( ! is_dir($uploadPath) && ! @mkdir($uploadPath, 0755, TRUE))
		{
			$result['error'] = 'Folder gambar tidak bisa dibuat.';

			return $result;
		}

		$this->CI->load->library('upload', array(
			'upload_path'   => $uploadPath,
			'allowed_types' => $this->allowedTypes,
			'max_size'      => $this->maxSizeKb,
			'encrypt_name'  => TRUE,
			'remove_spaces' => TRUE,
		));

		if ( ! $this->CI->upload->do_upload($field))
		{
			$result['error'] = $this->translateError($this->CI->upload->display_errors('', ''));

			return $result;
		}

		$data = $this->CI->upload->data();
		$result['path'] = $folder.'/'.$data['file_name'];

		return $result;
	}

	/**
	 * Terjemahkan pesan bawaan CI3 ke bahasa Indonesia untuk kasus umum.
	 *
	 * @param	string $error
	 * @return	string
	 */
	protected function translateError($error)
	{
		$error = trim(strip_tags((string) $error));

		if (stripos($error, 'larger than') !== FALSE)
		{
			return 'Ukuran gambar terlalu besar (maksimal 3 MB).';
		}

		if (stripos($error, 'filetype') !== FALSE)
		{
			return 'Format gambar tidak didukung. Gunakan file JPG, PNG, atau WEBP.';
		}

		if (stripos($error, 'not writable') !== FALSE)
		{
			return 'Folder gambar tidak bisa ditulis. Periksa izin folder assets/img.';
		}

		return ($error === '') ? 'Gambar gagal diunggah.' : $error;
	}
}
