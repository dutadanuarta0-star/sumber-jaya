<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6">
	<h1 class="ds-display-md">Pengaturan Toko</h1>
	<p class="ds-body mt-1 text-ds-ink-soft">Identitas toko dipakai di seluruh halaman toko dan pada pesan checkout WhatsApp.</p>
</div>

<?php if (validation_errors()): ?>
	<div class="ds-alert ds-alert--error mb-6" role="alert">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
		</svg>
		<span><?= validation_errors() ?></span>
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

<div class="ds-panel max-w-2xl space-y-5">
	<div>
		<label for="store_name" class="ds-label-text">Nama toko <span class="text-ds-danger">*</span></label>
		<input type="text" name="store_name" id="store_name" maxlength="100"
		       value="<?= esc(set_value('store_name', $store['store_name'])) ?>"
		       class="ds-input">
	</div>

	<div>
		<label for="whatsapp_number" class="ds-label-text">Nomor WhatsApp <span class="text-ds-danger">*</span></label>
		<input type="text" name="whatsapp_number" id="whatsapp_number" maxlength="30" inputmode="tel"
		       value="<?= esc(set_value('whatsapp_number', $store['whatsapp_number'])) ?>"
		       class="ds-input">
		<p class="ds-caption mt-2 text-ds-ink-soft">
			Boleh ditulis <span class="font-bold">0812-3456-7890</span>; otomatis disimpan menjadi <span class="font-bold">6281234567890</span>.
		</p>
	</div>

	<div class="grid gap-4 sm:grid-cols-2">
		<div>
			<label for="phone" class="ds-label-text">Telepon</label>
			<input type="tel" name="phone" id="phone" maxlength="30"
			       value="<?= esc(set_value('phone', $store['phone'])) ?>"
			       class="ds-input">
		</div>

		<div>
			<label for="email" class="ds-label-text">Email</label>
			<input type="email" name="email" id="email" maxlength="100"
			       value="<?= esc(set_value('email', $store['email'])) ?>"
			       class="ds-input">
		</div>
	</div>

	<div>
		<label for="address" class="ds-label-text">Alamat</label>
		<input type="text" name="address" id="address" maxlength="255"
		       value="<?= esc(set_value('address', $store['address'])) ?>"
		       class="ds-input">
	</div>

	<div>
		<label for="about_text" class="ds-label-text">Tentang kami</label>
		<textarea name="about_text" id="about_text" rows="6"
		          class="ds-textarea"><?= esc(set_value('about_text', $store['about_text'])) ?></textarea>
		<p class="ds-caption mt-2 text-ds-ink-soft">Teks ini tampil di halaman "Tentang Kami" pada situs toko.</p>
	</div>
</div>

<button type="submit" class="ds-btn ds-btn--primary">Simpan Pengaturan</button>

<?= form_close() ?>
