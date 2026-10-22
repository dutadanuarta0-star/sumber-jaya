<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php // Form tambah/edit kategori. ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
	<h1 class="ds-display-md"><?= $isEdit ? 'Edit Kategori' : 'Tambah Kategori' ?></h1>
	<a href="<?= site_url('admin/categories') ?>" class="ds-nav-link">&larr; Kembali</a>
</div>

<?php if (validation_errors()): ?>
	<div class="ds-alert ds-alert--error mb-6" role="alert">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
		</svg>
		<span><?= validation_errors() ?></span>
	</div>
<?php endif; ?>

<?php if ( ! empty($uploadError)): ?>
	<div class="ds-alert ds-alert--error mb-6" role="alert">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
		</svg>
		<span><?= esc($uploadError) ?></span>
	</div>
<?php endif; ?>

<?php
$action = $isEdit ? site_url('admin/categories/update/'.$category['id']) : site_url('admin/categories/store');
echo form_open_multipart($action, array('class' => 'space-y-6'));
?>

<div class="ds-panel max-w-2xl space-y-5">
	<div>
		<label for="name" class="ds-label-text">Nama kategori <span class="text-ds-danger">*</span></label>
		<input type="text" name="name" id="name" maxlength="100"
		       value="<?= esc($category ? $category['name'] : set_value('name')) ?>"
		       class="ds-input">
		<?php if ($isEdit): ?>
			<p class="ds-caption mt-2 text-ds-ink-soft">Slug saat ini: <span class="font-bold"><?= esc($category['slug']) ?></span> (diperbarui otomatis dari nama).</p>
		<?php endif; ?>
	</div>

	<div>
		<label for="description" class="ds-label-text">Deskripsi singkat</label>
		<input type="text" name="description" id="description" maxlength="255"
		       value="<?= esc($category ? $category['description'] : set_value('description')) ?>"
		       class="ds-input">
	</div>

	<div>
		<label for="image_file" class="ds-label-text">Upload gambar dari komputer</label>
		<input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/webp" class="ds-file">
		<p class="ds-caption mt-2 text-ds-ink-soft">
			Pilih file JPG, PNG, atau WEBP (maksimal 3 MB). Gambar ini yang tampil di beranda pada kartu kategori.
		</p>
	</div>

	<div>
		<label for="image_url" class="ds-label-text">Atau path / URL gambar yang sudah ada</label>
		<input type="text" name="image_url" id="image_url" maxlength="500"
		       value="<?= esc($category ? (string) $category['image_url'] : set_value('image_url')) ?>"
		       class="ds-input"
		       placeholder="assets/img/kategori/kopi-arabika.jpg">
		<p class="ds-caption mt-2 text-ds-ink-soft">
			Boleh dikosongkan jika memilih file di atas. Path server
			(<span class="font-bold">assets/img/kategori/...</span>) atau URL yang menunjuk langsung ke file
			gambar (<span class="font-bold">https://...</span>) — bukan halaman web.
			Kosongkan bila kategori tidak memakai gambar (beranda menampilkan ikon bawaan).
		</p>
		<?php if ($category && ! empty($category['image_url'])): ?>
			<img src="<?= esc(img_src($category['image_url']), 'attr') ?>" alt="Pratinjau gambar kategori"
			     class="mt-3 h-28 w-full max-w-sm rounded-md border border-ds-line object-cover">
		<?php endif; ?>
	</div>
</div>

<div class="flex flex-wrap items-center gap-3">
	<button type="submit" class="ds-btn ds-btn--primary">
		<?= $isEdit ? 'Simpan Perubahan' : 'Simpan Kategori' ?>
	</button>
	<a href="<?= site_url('admin/categories') ?>" class="ds-btn ds-btn--ghost">Batal</a>
</div>

<?= form_close() ?>
