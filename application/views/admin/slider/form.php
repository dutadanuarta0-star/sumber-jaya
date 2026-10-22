<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php // Form tambah/edit slide hero beranda. ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
	<h1 class="ds-display-md"><?= $isEdit ? 'Edit Slide' : 'Tambah Slide' ?></h1>
	<a href="<?= site_url('admin/slider') ?>" class="ds-nav-link">&larr; Kembali</a>
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
$action = $isEdit ? site_url('admin/slider/update/'.$slide['id']) : site_url('admin/slider/store');
echo form_open_multipart($action, array('class' => 'space-y-6'));
?>

<div class="ds-panel max-w-2xl space-y-5">
	<div>
		<label for="title" class="ds-label-text">Judul <span class="text-ds-danger">*</span></label>
		<input type="text" name="title" id="title" maxlength="150"
		       value="<?= esc($slide ? $slide['title'] : set_value('title')) ?>"
		       class="ds-input">
	</div>

	<div>
		<label for="label" class="ds-label-text">Label kecil di atas judul</label>
		<input type="text" name="label" id="label" maxlength="100"
		       value="<?= esc($slide ? (string) $slide['label'] : set_value('label')) ?>"
		       class="ds-input"
		       placeholder="mis. Roasted fresh every week">
	</div>

	<div>
		<label for="text" class="ds-label-text">Deskripsi singkat</label>
		<textarea name="text" id="text" rows="3" maxlength="300"
		          class="ds-textarea"><?= esc($slide ? (string) $slide['text'] : set_value('text')) ?></textarea>
	</div>

	<div>
		<label for="image_file" class="ds-label-text">Upload gambar dari komputer</label>
		<input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/webp" class="ds-file">
		<p class="ds-caption mt-2 text-ds-ink-soft">
			Pilih file JPG, PNG, atau WEBP (maksimal 3 MB). File yang dipilih akan menggantikan gambar lama.
		</p>
	</div>

	<div>
		<label for="image_url" class="ds-label-text">Atau path / URL gambar yang sudah ada</label>
		<input type="text" name="image_url" id="image_url" maxlength="500"
		       value="<?= esc($slide ? (string) $slide['image_url'] : set_value('image_url')) ?>"
		       class="ds-input"
		       placeholder="assets/img/slider/promo.jpg">
		<p class="ds-caption mt-2 text-ds-ink-soft">
			Boleh dikosongkan jika memilih file di atas. Path server
			(<span class="font-bold">assets/img/slider/...</span>) atau URL yang menunjuk langsung ke file
			gambar (<span class="font-bold">https://...</span>) — bukan halaman web.
			Kosongkan keduanya untuk memakai warna latar bawaan.
			Disarankan gambar lebar (mis. 1600&times;800 px) agar tidak pecah.
		</p>
		<?php if ($slide && ! empty($slide['image_url'])): ?>
			<img src="<?= esc(img_src($slide['image_url']), 'attr') ?>" alt="Pratinjau gambar slide"
			     class="mt-3 h-28 w-full max-w-sm rounded-md border border-ds-line object-cover">
		<?php endif; ?>
	</div>

	<div class="grid gap-4 sm:grid-cols-2">
		<div>
			<label for="cta_text" class="ds-label-text">Teks tombol</label>
			<input type="text" name="cta_text" id="cta_text" maxlength="50"
			       value="<?= esc($slide ? (string) $slide['cta_text'] : set_value('cta_text')) ?>"
			       class="ds-input"
			       placeholder="Lihat Katalog">
		</div>
		<div>
			<label for="cta_url" class="ds-label-text">Tautan tombol</label>
			<input type="text" name="cta_url" id="cta_url" maxlength="255"
			       value="<?= esc($slide ? (string) $slide['cta_url'] : set_value('cta_url')) ?>"
			       class="ds-input"
			       placeholder="products">
			<p class="ds-caption mt-2 text-ds-ink-soft">
				Path internal (mis. <span class="font-bold">products</span>,
				<span class="font-bold">products?kategori=arabika</span>, <span class="font-bold">about</span>)
				atau URL lengkap <span class="font-bold">https://...</span>.
			</p>
		</div>
	</div>

	<div class="grid items-end gap-4 sm:grid-cols-2">
		<div>
			<label for="sort_order" class="ds-label-text">Urutan tampil</label>
			<input type="number" name="sort_order" id="sort_order" min="0"
			       value="<?= esc($slide ? (string) $slide['sort_order'] : set_value('sort_order', '0')) ?>"
			       class="ds-input">
			<p class="ds-caption mt-2 text-ds-ink-soft">Angka kecil tampil lebih dulu.</p>
		</div>
		<div class="pb-2">
			<label class="ds-label-text inline-flex items-center gap-2">
				<input type="checkbox" name="is_active" value="1" class="ds-check"
				       <?= ($slide === NULL OR (int) $slide['is_active'] === 1) ? 'checked' : '' ?>>
				Aktif (tampil di beranda)
			</label>
		</div>
	</div>
</div>

<div class="flex flex-wrap items-center gap-3">
	<button type="submit" class="ds-btn ds-btn--primary">
		<?= $isEdit ? 'Simpan Perubahan' : 'Simpan Slide' ?>
	</button>
	<a href="<?= site_url('admin/slider') ?>" class="ds-btn ds-btn--ghost">Batal</a>
</div>

<?= form_close() ?>
