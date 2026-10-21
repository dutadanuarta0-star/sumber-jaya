<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-5">
	<h1 class="text-xl font-bold">Pengaturan Toko</h1>
	<p class="text-sm text-stone-500 mt-1">Identitas toko dipakai di seluruh halaman toko dan pada pesan checkout WhatsApp.</p>
</div>

<?php if (validation_errors()): ?>
	<div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-5">
		<?= validation_errors() ?>
	</div>
<?php endif; ?>

<?php
// Halaman ini hanya boleh tampil untuk admin yang sudah login.
// Pemeriksaan dilakukan di sini agar akses langsung ke file view terblokir.
if ( ! isset($store) OR ! is_array($store))
{
	redirect('admin/login');
	exit;
}
?>
<?= form_open('admin/settings', array('class' => 'space-y-6')) ?>

<div class="bg-white border border-stone-200 rounded-lg p-5 space-y-4 max-w-2xl">
	<div>
		<label for="store_name" class="block text-sm font-medium mb-1.5">Nama toko <span class="text-red-600">*</span></label>
		<input type="text" name="store_name" id="store_name" maxlength="100"
		       value="<?= esc(set_value('store_name', $store['store_name'])) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
	</div>

	<div>
		<label for="whatsapp_number" class="block text-sm font-medium mb-1.5">Nomor WhatsApp <span class="text-red-600">*</span></label>
		<input type="text" name="whatsapp_number" id="whatsapp_number" maxlength="30"
		       value="<?= esc(set_value('whatsapp_number', $store['whatsapp_number'])) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		<p class="text-xs text-stone-500 mt-1">
			Boleh ditulis <span class="font-mono">0812-3456-7890</span>; otomatis disimpan menjadi <span class="font-mono">6281234567890</span>.
		</p>
	</div>

	<div class="grid gap-4 sm:grid-cols-2">
		<div>
			<label for="phone" class="block text-sm font-medium mb-1.5">Telepon</label>
			<input type="text" name="phone" id="phone" maxlength="30"
			       value="<?= esc(set_value('phone', $store['phone'])) ?>"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		</div>

		<div>
			<label for="email" class="block text-sm font-medium mb-1.5">Email</label>
			<input type="email" name="email" id="email" maxlength="100"
			       value="<?= esc(set_value('email', $store['email'])) ?>"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		</div>
	</div>

	<div>
		<label for="address" class="block text-sm font-medium mb-1.5">Alamat</label>
		<input type="text" name="address" id="address" maxlength="255"
		       value="<?= esc(set_value('address', $store['address'])) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
	</div>

	<div>
		<label for="about_text" class="block text-sm font-medium mb-1.5">Tentang kami</label>
		<textarea name="about_text" id="about_text" rows="6"
		          class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm"><?= esc(set_value('about_text', $store['about_text'])) ?></textarea>
		<p class="text-xs text-stone-500 mt-1">Teks ini tampil di halaman "Tentang Kami" pada situs toko.</p>
	</div>
</div>

<button type="submit" class="bg-amber-800 text-white text-sm font-semibold px-5 py-2.5 rounded-md hover:bg-amber-900">
	Simpan Pengaturan
</button>

<?= form_close() ?>
