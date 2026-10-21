<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php // Form tambah/edit kategori. ?>

<div class="flex items-center justify-between mb-5">
	<h1 class="text-xl font-bold"><?= $isEdit ? 'Edit Kategori' : 'Tambah Kategori' ?></h1>
	<a href="<?= site_url('admin/categories') ?>" class="text-sm text-stone-500 hover:underline">&larr; Kembali</a>
</div>

<?php if (validation_errors()): ?>
	<div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-5">
		<?= validation_errors() ?>
	</div>
<?php endif; ?>

<?php
$action = $isEdit ? site_url('admin/categories/update/'.$category['id']) : site_url('admin/categories/store');
echo form_open($action, array('class' => 'space-y-6'));
?>

<div class="bg-white border border-stone-200 rounded-lg p-5 space-y-4 max-w-2xl">
	<div>
		<label for="name" class="block text-sm font-medium mb-1.5">Nama kategori <span class="text-red-600">*</span></label>
		<input type="text" name="name" id="name" maxlength="100"
		       value="<?= esc($category ? $category['name'] : set_value('name')) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		<?php if ($isEdit): ?>
			<p class="text-xs text-stone-500 mt-1">Slug saat ini: <span class="font-mono"><?= esc($category['slug']) ?></span> (diperbarui otomatis dari nama).</p>
		<?php endif; ?>
	</div>

	<div>
		<label for="description" class="block text-sm font-medium mb-1.5">Deskripsi singkat</label>
		<input type="text" name="description" id="description" maxlength="255"
		       value="<?= esc($category ? $category['description'] : set_value('description')) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
	</div>
</div>

<div class="flex items-center gap-3">
	<button type="submit" class="bg-amber-800 text-white text-sm font-semibold px-5 py-2.5 rounded-md hover:bg-amber-900">
		<?= $isEdit ? 'Simpan Perubahan' : 'Simpan Kategori' ?>
	</button>
	<a href="<?= site_url('admin/categories') ?>" class="text-sm text-stone-500 hover:underline">Batal</a>
</div>

<?= form_close() ?>
