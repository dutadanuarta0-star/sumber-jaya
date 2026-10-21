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

<div class="mb-5">
	<h1 class="text-xl font-bold">Ubah Akun Admin</h1>
	<p class="text-sm text-stone-500 mt-1">
		Username dipakai untuk login. Password baru minimal 8 karakter — kosongkan bila hanya ingin mengubah username.
	</p>
</div>

<?php if ($this->session->flashdata('success')): ?>
	<div class="rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm mb-5">
		<?= esc($this->session->flashdata('success')) ?>
	</div>
<?php endif ?>

<?php if ( ! empty($formError)): ?>
	<div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-5">
		<?= esc($formError) ?>
	</div>
<?php elseif (validation_errors()): ?>
	<div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-5">
		<?= validation_errors() ?>
	</div>
<?php endif ?>

<?= form_open('admin/settings/accountUpdate', array('class' => 'space-y-6')) ?>

<div class="bg-white border border-stone-200 rounded-lg p-5 space-y-4 max-w-2xl">
	<h2 class="font-semibold">Username</h2>
	<div>
		<label for="username" class="block text-sm font-medium mb-1.5">Username <span class="text-red-600">*</span></label>
		<input type="text" name="username" id="username" maxlength="50" autocomplete="username" autofocus
		       value="<?= esc(set_value('username', $admin['username'])) ?>"
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
	</div>
</div>

<div class="bg-white border border-stone-200 rounded-lg p-5 space-y-4 max-w-2xl">
	<h2 class="font-semibold">Ganti Password</h2>
	<div>
		<label for="current_password" class="block text-sm font-medium mb-1.5">Password saat ini <span class="text-red-600">*</span></label>
		<input type="password" name="current_password" id="current_password" autocomplete="current-password" required
		       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		<p class="text-xs text-stone-500 mt-1">Wajib diisi sebagai konfirmasi keamanan, meski hanya username yang diubah.</p>
	</div>

	<div class="grid gap-4 sm:grid-cols-2">
		<div>
			<label for="new_password" class="block text-sm font-medium mb-1.5">Password baru</label>
			<input type="password" name="new_password" id="new_password" minlength="8" autocomplete="new-password"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		</div>
		<div>
			<label for="confirm_password" class="block text-sm font-medium mb-1.5">Ulangi password baru</label>
			<input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password"
			       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
		</div>
	</div>
</div>

<button type="submit" class="bg-amber-800 text-white font-semibold px-5 py-2.5 rounded-md hover:bg-amber-900 text-sm">
	Simpan Perubahan
</button>
<?= form_close() ?>
