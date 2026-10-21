<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login Admin — <?= esc($store['store_name']) ?></title>
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 min-h-screen flex items-center justify-center px-4">

<div class="w-full max-w-sm">
	<div class="text-center mb-6">
		<h1 class="text-xl font-bold"><?= esc($store['store_name']) ?></h1>
		<p class="text-sm text-stone-500">Masuk ke panel admin</p>
	</div>

	<div class="bg-white border border-stone-200 rounded-lg p-6">
		<?php if ($this->session->flashdata('error')): ?>
			<div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-3 py-2 text-sm">
				<?= esc($this->session->flashdata('error')) ?>
			</div>
		<?php endif; ?>

		<?php if ($nothing = validation_errors()): ?>
			<div class="mb-4 rounded-md bg-red-50 border border-red-200 text-red-800 px-3 py-2 text-sm">
				<?= $nothing // sudah di-escape oleh CI3 di dalam validation_errors() ?>
			</div>
		<?php endif; ?>

		<?php if ($lockedFor > 0): ?>
			<div class="mb-4 rounded-md bg-amber-50 border border-amber-200 text-amber-900 px-3 py-2 text-sm">
				Terlalu banyak percobaan gagal. Coba lagi dalam <?= (int) ceil($lockedFor / 60) ?> menit.
			</div>
		<?php endif; ?>

		<?= form_open('admin/login', array('class' => 'space-y-4')) ?>
			<div>
				<label for="username" class="block text-sm font-medium mb-1.5">Username</label>
				<input type="text" name="username" id="username" value="<?= esc(set_value('username')) ?>" autofocus
				       class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
			</div>

			<div>
				<label for="password" class="block text-sm font-medium mb-1.5">Password</label>
				<input type="password" name="password" id="password" class="w-full border border-stone-300 rounded-md px-3 py-2 text-sm">
			</div>

			<button type="submit" class="w-full bg-amber-800 text-white font-semibold py-2.5 rounded-md hover:bg-amber-900">
				Masuk
			</button>
		<?= form_close() ?>
	</div>

	<p class="text-center text-xs text-stone-500 mt-4">
		<a href="<?= site_url() ?>" class="hover:underline">&larr; Kembali ke toko</a>
	</p>
</div>

</body>
</html>
