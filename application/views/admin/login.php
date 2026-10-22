<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login Admin — <?= esc($store['store_name']) ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=Space+Mono:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet">

	<script src="https://cdn.tailwindcss.com"></script>
	<script src="<?= base_url('assets/js/ds-tailwind-config.js') ?>"></script>
	<link rel="stylesheet" href="<?= base_url('assets/css/design-system.css') ?>">
</head>
<body class="flex min-h-screen items-center justify-center px-4 py-10">

<div class="w-full max-w-sm">

	<div class="mb-6 text-center">
		<span class="ds-logo mb-4 inline-flex">
			<span class="ds-logo__dot" aria-hidden="true"></span>
			<span><?= esc($store['store_name']) ?></span>
		</span>
		<p class="ds-label text-ds-ink-soft">Masuk ke panel admin</p>
	</div>

	<div class="ds-panel shadow-ds-md">
		<?php if ($this->session->flashdata('error')): ?>
			<div class="ds-alert ds-alert--error mb-4" role="alert">
				<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
				</svg>
				<span><?= esc($this->session->flashdata('error')) ?></span>
			</div>
		<?php endif; ?>

		<?php if ($nothing = validation_errors()): ?>
			<div class="ds-alert ds-alert--error mb-4" role="alert">
				<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
				</svg>
				<span><?= $nothing // sudah di-escape oleh CI3 di dalam validation_errors() ?></span>
			</div>
		<?php endif; ?>

		<?php if ($lockedFor > 0): ?>
			<div class="ds-alert ds-alert--info mb-4" role="status">
				<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
				</svg>
				<span>Terlalu banyak percobaan gagal. Coba lagi dalam <?= (int) ceil($lockedFor / 60) ?> menit.</span>
			</div>
		<?php endif; ?>

		<?= form_open('admin/login', array('class' => 'space-y-4')) ?>
			<div>
				<label for="username" class="ds-label-text">Username</label>
				<input type="text" name="username" id="username" value="<?= esc(set_value('username')) ?>" autofocus
				       autocomplete="username" class="ds-input">
			</div>

			<div>
				<label for="password" class="ds-label-text">Password</label>
				<input type="password" name="password" id="password" autocomplete="current-password" class="ds-input">
			</div>

			<button type="submit" class="ds-btn ds-btn--primary ds-btn--block mt-2">Masuk</button>
		<?= form_close() ?>
	</div>

	<p class="mt-4 text-center">
		<a href="<?= site_url() ?>" class="ds-nav-link">&larr; Kembali ke toko</a>
	</p>
</div>

</body>
</html>
