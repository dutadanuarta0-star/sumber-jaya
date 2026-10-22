<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php // Form tambah/edit slide hero beranda. ?>

<div class="flex items-center justify-between mb-5">
	<h1 class="text-xl font-bold"><?= $isEdit ? 'Edit Slide' : 'Tambah Slide' ?></h1>
	<a href="<?= site_url('admin/slider') ?>" class="text-sm text-stone-500 hover:underline">&larr; Kembali</a>
</div>

<?php if (validation_errors()): ?>
	<div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-5">
		<?= validation_errors() ?>
	</div>
<?php endif; ?>

<?php if ( ! empty($uploadError)): ?>
	<div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-5">
		<?= esc($uploadError) ?>
	</div>
<?php endif; ?>

<?php
$action = $isEdit ? site_url('admin/slider/update/'.$slide['id']) : site_url('admin/slider/store');
echo form_open_multipart($action, array('class' => 'space-y-6'));
?>

<div class="bg-white border border-stone-200 rounded-lg p-5 space-y-4 max-w-2xl">
	<div>
		<label for="title" class="block text-sm font-medium mb-1.5">Judul <span class="text-red-600">*</span></label>
		<input type="text" name="title" id="title" maxlength="150"
		       value="<?= esc($slide ? $slide['title'] : set_value('title')) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
	</div>

	<div>
		<label for="label" class="block text-sm font-medium mb-1.5">Label kecil di atas judul</label>
		<input type="text" name="label" id="label" maxlength="100"
		       value="<?= esc($slide ? (string) $slide['label'] : set_value('label')) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm"
		       placeholder="mis. Roasted fresh every week">
	</div>

	<div>
		<label for="text" class="block text-sm font-medium mb-1.5">Deskripsi singkat</label>
		<textarea name="text" id="text" rows="3" maxlength="300"
		          class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm"><?= esc($slide ? (string) $slide['text'] : set_value('text')) ?></textarea>
	</div>

	<div>
		<label for="image_file" class="block text-sm font-medium mb-1.5">Upload gambar dari komputer</label>
		<input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/webp"
		       class="block w-full text-sm text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-800 file:text-white hover:file:bg-amber-900">
		<p class="text-xs text-stone-500 mt-1">
			Pilih file JPG, PNG, atau WEBP (maksimal 3 MB). File yang dipilih akan menggantikan gambar lama.
		</p>
	</div>

	<div>
		<label for="image_url" class="block text-sm font-medium mb-1.5">Atau path / URL gambar yang sudah ada</label>
		<input type="text" name="image_url" id="image_url" maxlength="500"
		       value="<?= esc($slide ? (string) $slide['image_url'] : set_value('image_url')) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm"
		       placeholder="assets/img/slider/promo.jpg">
		<p class="text-xs text-stone-500 mt-1">
			Boleh dikosongkan jika memilih file di atas. Path server
			(<span class="font-mono">assets/img/slider/...</span>) atau URL yang menunjuk langsung ke file
			gambar (<span class="font-mono">https://...</span>) — bukan halaman web.
			Kosongkan keduanya untuk memakai warna latar bawaan.
			Disarankan gambar lebar (mis. 1600&times;800 px) agar tidak pecah.
		</p>
		<?php if ($slide && ! empty($slide['image_url'])): ?>
			<img src="<?= esc(img_src($slide['image_url']), 'attr') ?>" alt="Pratinjau gambar slide"
			     class="mt-2 h-28 w-full max-w-sm object-cover rounded border border-stone-200">
		<?php endif; ?>
	</div>

	<div class="grid sm:grid-cols-2 gap-4">
		<div>
			<label for="cta_text" class="block text-sm font-medium mb-1.5">Teks tombol</label>
			<input type="text" name="cta_text" id="cta_text" maxlength="50"
			       value="<?= esc($slide ? (string) $slide['cta_text'] : set_value('cta_text')) ?>"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm"
			       placeholder="Lihat Katalog">
		</div>
		<div>
			<label for="cta_url" class="block text-sm font-medium mb-1.5">Tautan tombol</label>
			<input type="text" name="cta_url" id="cta_url" maxlength="255"
			       value="<?= esc($slide ? (string) $slide['cta_url'] : set_value('cta_url')) ?>"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm"
			       placeholder="products">
			<p class="text-xs text-stone-500 mt-1">
				Path internal (mis. <span class="font-mono">products</span>,
				<span class="font-mono">products?kategori=arabika</span>, <span class="font-mono">about</span>)
				atau URL lengkap <span class="font-mono">https://...</span>.
			</p>
		</div>
	</div>

	<div class="grid sm:grid-cols-2 gap-4 items-end">
		<div>
			<label for="sort_order" class="block text-sm font-medium mb-1.5">Urutan tampil</label>
			<input type="number" name="sort_order" id="sort_order" min="0"
			       value="<?= esc($slide ? (string) $slide['sort_order'] : set_value('sort_order', '0')) ?>"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
			<p class="text-xs text-stone-500 mt-1">Angka kecil tampil lebih dulu.</p>
		</div>
		<div class="pb-5">
			<label class="inline-flex items-center gap-2 text-sm">
				<input type="checkbox" name="is_active" value="1" class="accent-amber-800"
				       <?= ($slide === NULL OR (int) $slide['is_active'] === 1) ? 'checked' : '' ?>>
				Aktif (tampil di beranda)
			</label>
		</div>
	</div>
</div>

<div class="flex items-center gap-3">
	<button type="submit" class="bg-amber-800 text-white text-sm font-semibold px-5 py-2.5 rounded-md hover:bg-amber-900">
		<?= $isEdit ? 'Simpan Perubahan' : 'Simpan Slide' ?>
	</button>
	<a href="<?= site_url('admin/slider') ?>" class="text-sm text-stone-500 hover:underline">Batal</a>
</div>

<?= form_close() ?>
