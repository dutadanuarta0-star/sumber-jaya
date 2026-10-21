<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php // Form tambah/edit produk. Harga & stok diisi per varian. ?>

<div class="flex items-center justify-between mb-5">
	<h1 class="text-xl font-bold"><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h1>
	<a href="<?= site_url('admin/products') ?>" class="text-sm text-stone-500 hover:underline">&larr; Kembali</a>
</div>

<?php if ( ! $hasCategory): ?>
	<div class="rounded-md bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 text-sm mb-5">
		Belum ada kategori. <a href="<?= site_url('admin/categories/create') ?>" class="underline font-medium">Tambah kategori dulu</a>.
	</div>
<?php endif; ?>

<?php if (validation_errors()): ?>
	<div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-5">
		<?= validation_errors() ?>
	</div>
<?php endif; ?>

<?php
$action = $isEdit ? site_url('admin/products/update/'.$product['id']) : site_url('admin/products/store');
echo form_open($action, array('class' => 'space-y-6'));
?>

<div class="bg-white border border-stone-200 rounded-lg p-5 space-y-4">
	<h2 class="font-semibold">Informasi Produk</h2>

	<div class="grid gap-4 sm:grid-cols-2">
		<div>
			<label for="name" class="block text-sm font-medium mb-1.5">Nama produk <span class="text-red-600">*</span></label>
			<input type="text" name="name" id="name" maxlength="150"
			       value="<?= esc($product ? $product['name'] : set_value('name')) ?>"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		</div>

		<div>
			<label for="category_id" class="block text-sm font-medium mb-1.5">Kategori <span class="text-red-600">*</span></label>
			<select name="category_id" id="category_id" class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
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
		<label for="image_url" class="block text-sm font-medium mb-1.5">Gambar</label>
		<input type="text" name="image_url" id="image_url" maxlength="500" placeholder="assets/img/produk/nama-gambar.jpg"
		       value="<?= esc($product ? $product['image_url'] : set_value('image_url')) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		<p class="text-xs text-stone-500 mt-1">
			Isi path gambar di server (<code>assets/img/produk/...</code>) atau URL lengkap (<code>https://...</code>).
			Kosongkan jika produk belum punya gambar.
		</p>
	</div>

	<div>
		<label for="description" class="block text-sm font-medium mb-1.5">Deskripsi</label>
		<textarea name="description" id="description" rows="4"
		          class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm"><?= esc($product ? $product['description'] : set_value('description')) ?></textarea>
	</div>

	<label class="inline-flex items-center gap-2 text-sm">
		<input type="checkbox" name="is_active" value="1" class="accent-amber-800"
		       <?= ( ! $product OR (int) $product['is_active'] === 1) ? 'checked' : '' ?>>
		Tampilkan produk di toko (aktif)
	</label>
</div>

<div class="bg-white border border-stone-200 rounded-lg p-5">
	<div class="flex items-start justify-between gap-4 mb-4">
		<div>
			<h2 class="font-semibold">Varian &amp; Stok</h2>
			<p class="text-xs text-stone-500 mt-1">
				Minimal satu varian wajib diisi. Baris yang dibiarkan kosong akan diabaikan.
			</p>
		</div>
	</div>

	<div class="overflow-x-auto">
		<table class="w-full text-sm" id="variantTable">
			<thead class="text-left text-stone-500">
				<tr>
					<th class="py-2 font-medium w-1/2">Ukuran / Nama varian</th>
					<th class="py-2 font-medium w-1/4">Harga (Rp)</th>
					<th class="py-2 font-medium w-1/4">Stok</th>
				</tr>
			</thead>
			<tbody>
				<?php $variants = ($product AND isset($product['variants'])) ? $product['variants'] : array(); ?>

				<?php foreach ($variants as $variant): ?>
					<tr>
						<td class="py-1.5 pr-3">
							<input type="text" name="variant_label[]" maxlength="50" value="<?= esc($variant['label']) ?>"
							       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
						</td>
						<td class="py-1.5 pr-3">
							<input type="number" name="variant_price[]" min="0" step="500" value="<?= (int) $variant['price'] ?>"
							       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
						</td>
						<td class="py-1.5">
							<input type="number" name="variant_stock[]" min="0" value="<?= (int) $variant['stock'] ?>"
							       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
						</td>
					</tr>
				<?php endforeach; ?>

				<?php // Baris kosong tambahan agar admin bisa langsung menambah varian baru. ?>
				<?php for ($i = 0; $i < $emptyRows; $i++): ?>
					<tr>
						<td class="py-1.5 pr-3">
							<input type="text" name="variant_label[]" maxlength="50" placeholder="contoh: 250g"
							       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
						</td>
						<td class="py-1.5 pr-3">
							<input type="number" name="variant_price[]" min="0" step="500" placeholder="0"
							       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
						</td>
						<td class="py-1.5">
							<input type="number" name="variant_stock[]" min="0" placeholder="0"
							       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
						</td>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>
	</div>
</div>

<div class="flex items-center gap-3">
	<button type="submit" class="bg-amber-800 text-white text-sm font-semibold px-5 py-2.5 rounded-md hover:bg-amber-900">
		<?= $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' ?>
	</button>
	<a href="<?= site_url('admin/products') ?>" class="text-sm text-stone-500 hover:underline">Batal</a>
</div>

<?= form_close() ?>
