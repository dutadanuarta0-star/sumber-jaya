<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php // Form tambah/edit produk. Harga & stok diisi per varian. ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
	<h1 class="ds-display-md"><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h1>
	<a href="<?= site_url('admin/products') ?>" class="ds-nav-link">&larr; Kembali</a>
</div>

<?php if ( ! $hasCategory): ?>
	<div class="ds-alert ds-alert--info mb-6">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
		</svg>
		<span>Belum ada kategori. <a href="<?= site_url('admin/categories/create') ?>" class="underline underline-offset-4">Tambah kategori dulu</a>.</span>
	</div>
<?php endif; ?>

<?php if (validation_errors()): ?>
	<div class="ds-alert ds-alert--error mb-6" role="alert">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
		</svg>
		<span><?= validation_errors() ?></span>
	</div>
<?php endif; ?>

<?php if ( ! empty($formError)): ?>
	<div class="ds-alert ds-alert--error mb-6" role="alert">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
		</svg>
		<span><?= esc($formError) ?></span>
	</div>
<?php endif; ?>

<?php
$action = $isEdit ? site_url('admin/products/update/'.$product['id']) : site_url('admin/products/store');
echo form_open_multipart($action, array('class' => 'space-y-6'));
?>

<div class="ds-panel space-y-5">
	<h2 class="ds-panel-title">Informasi Produk</h2>

	<div class="grid gap-4 sm:grid-cols-2">
		<div>
			<label for="name" class="ds-label-text">Nama produk <span class="text-ds-danger">*</span></label>
			<input type="text" name="name" id="name" maxlength="150"
			       value="<?= esc($product ? $product['name'] : set_value('name')) ?>"
			       class="ds-input">
		</div>

		<div>
			<label for="category_id" class="ds-label-text">Kategori <span class="text-ds-danger">*</span></label>
			<select name="category_id" id="category_id" class="ds-select">
				<option value="">— Pilih kategori —</option>
				<?php foreach ($categories as $category): ?>
					<option value="<?= (int) $category['id'] ?>"
						<?= ((int) ($product ? $product['category_id'] : set_value('category_id')) === (int) $category['id']) ? 'selected' : '' ?>>
						<?= esc($category['name']) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
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
		<input type="text" name="image_url" id="image_url" maxlength="500" placeholder="assets/img/produk/nama-gambar.jpg"
		       value="<?= esc($product ? $product['image_url'] : set_value('image_url')) ?>"
		       class="ds-input">
		<p class="ds-caption mt-2 text-ds-ink-soft">
			Boleh dikosongkan jika memilih file di atas. Path server (<code>assets/img/produk/...</code>) atau URL
			yang menunjuk langsung ke file gambar (<code>https://.../foto.jpg</code>) — bukan halaman web
			(mis. halaman marketplace berakhiran <code>.html</code>).
		</p>
		<?php if ($product && ! empty($product['image_url'])): ?>
			<img src="<?= esc(img_src($product['image_url']), 'attr') ?>" alt="Gambar saat ini"
			     class="mt-3 h-28 w-40 rounded-md border border-ds-line object-cover">
		<?php endif; ?>
	</div>

	<div>
		<label for="description" class="ds-label-text">Deskripsi</label>
		<textarea name="description" id="description" rows="4"
		          class="ds-textarea"><?= esc($product ? $product['description'] : set_value('description')) ?></textarea>
	</div>

	<label class="ds-label-text inline-flex items-center gap-2">
		<input type="checkbox" name="is_active" value="1" class="ds-check"
		       <?= ( ! $product OR (int) $product['is_active'] === 1) ? 'checked' : '' ?>>
		Tampilkan produk di toko (aktif)
	</label>
</div>

<div class="ds-panel">
	<div class="mb-4 flex items-start justify-between gap-4">
		<div>
			<h2 class="ds-panel-title">Varian &amp; Stok</h2>
			<p class="ds-caption mt-1 text-ds-ink-soft">
				Minimal satu varian wajib diisi. Baris yang dibiarkan kosong akan diabaikan.
			</p>
		</div>
	</div>

	<div class="ds-table-wrap">
		<div class="ds-table-scroll">
			<table class="ds-table ds-table--fluid" id="variantTable">
				<thead>
					<tr>
						<th class="w-1/2">Ukuran / Nama varian</th>
						<th class="w-1/4">Harga (Rp)</th>
						<th class="w-1/4">Stok</th>
					</tr>
				</thead>
				<tbody>
					<?php $variants = ($product AND isset($product['variants'])) ? $product['variants'] : array(); ?>

					<?php foreach ($variants as $variant): ?>
						<tr>
							<td class="py-2 pr-3">
								<input type="text" name="variant_label[]" maxlength="50" value="<?= esc($variant['label']) ?>"
								       class="ds-input ds-input--sm">
							</td>
							<td class="py-2 pr-3">
								<input type="number" name="variant_price[]" min="0" step="500" value="<?= (int) $variant['price'] ?>"
								       class="ds-input ds-input--sm">
							</td>
							<td class="py-2">
								<input type="number" name="variant_stock[]" min="0" value="<?= (int) $variant['stock'] ?>"
								       class="ds-input ds-input--sm">
							</td>
						</tr>
					<?php endforeach; ?>

					<?php // Baris kosong tambahan agar admin bisa langsung menambah varian baru. ?>
					<?php for ($i = 0; $i < $emptyRows; $i++): ?>
						<tr>
							<td class="py-2 pr-3">
								<input type="text" name="variant_label[]" maxlength="50" placeholder="contoh: 250g"
								       class="ds-input ds-input--sm">
							</td>
							<td class="py-2 pr-3">
								<input type="number" name="variant_price[]" min="0" step="500" placeholder="0"
								       class="ds-input ds-input--sm">
							</td>
							<td class="py-2">
								<input type="number" name="variant_stock[]" min="0" placeholder="0"
								       class="ds-input ds-input--sm">
							</td>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="flex flex-wrap items-center gap-3">
	<button type="submit" class="ds-btn ds-btn--primary">
		<?= $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' ?>
	</button>
	<a href="<?= site_url('admin/products') ?>" class="ds-btn ds-btn--ghost">Batal</a>
</div>

<?= form_close() ?>
