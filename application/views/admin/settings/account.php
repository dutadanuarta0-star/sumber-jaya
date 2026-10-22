<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php // Form ubah username & password admin yang sedang login. ?>

<?php
// Halaman ini hanya boleh tampil untuk admin yang sudah login.
if ( ! isset($store) OR ! is_array($store))
{
	redirect('admin/login');
	exit;
}
?>

<div class="mb-6">
	<h1 class="ds-display-md">Ubah Akun Admin</h1>
	<p class="ds-body mt-1 max-w-[60ch] text-ds-ink-soft">
		Username dipakai untuk login. Password baru minimal 8 karakter — kosongkan bila hanya ingin mengubah username.
	</p>
</div>

<?php if ($this->session->flashdata('success')): ?>
	<div class="ds-alert ds-alert--success mb-6" role="status">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M20 6 9 17l-5-5"/>
		</svg>
		<span><?= esc($this->session->flashdata('success')) ?></span>
	</div>
<?php endif ?>

<?php if ( ! empty($formError)): ?>
	<div class="ds-alert ds-alert--error mb-6" role="alert">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
		</svg>
		<span><?= esc($formError) ?></span>
	</div>
<?php elseif (validation_errors()): ?>
	<div class="ds-alert ds-alert--error mb-6" role="alert">
		<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
		</svg>
		<span><?= validation_errors() ?></span>
	</div>
<?php endif ?>

<?= form_open('admin/settings/accountUpdate', array('class' => 'space-y-6')) ?>

<div class="ds-panel max-w-2xl space-y-5">
	<h2 class="ds-panel-title">Username</h2>
	<div>
		<label for="username" class="ds-label-text">Username <span class="text-ds-danger">*</span></label>
		<input type="text" name="username" id="username" maxlength="50" autocomplete="username" autofocus
		       value="<?= esc(set_value('username', $admin['username'])) ?>"
		       class="ds-input">
	</div>
</div>

<div class="ds-panel max-w-2xl space-y-5">
	<h2 class="ds-panel-title">Ganti Password</h2>
	<div>
		<label for="current_password" class="ds-label-text">Password saat ini <span class="text-ds-danger">*</span></label>
		<input type="password" name="current_password" id="current_password" autocomplete="current-password" required
		       class="ds-input">
		<p class="ds-caption mt-2 text-ds-ink-soft">Wajib diisi sebagai konfirmasi keamanan, meski hanya username yang diubah.</p>
	</div>

	<div class="grid gap-4 sm:grid-cols-2">
		<div>
			<label for="new_password" class="ds-label-text">Password baru</label>
			<input type="password" name="new_password" id="new_password" minlength="8" autocomplete="new-password"
			       class="ds-input">
		</div>
		<div>
			<label for="confirm_password" class="ds-label-text">Ulangi password baru</label>
			<input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password"
			       class="ds-input">
		</div>
	</div>
</div>

<button type="submit" class="ds-btn ds-btn--primary">Simpan Perubahan</button>
<?= form_close() ?>
