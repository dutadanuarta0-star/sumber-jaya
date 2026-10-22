<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= esc(($pageTitle ?? '') !== '' ? $pageTitle.' — Admin' : 'Admin') ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=Space+Mono:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet">

	<script src="https://cdn.tailwindcss.com"></script>
	<script src="<?= base_url('assets/js/ds-tailwind-config.js') ?>"></script>
	<link rel="stylesheet" href="<?= base_url('assets/css/design-system.css') ?>">
</head>
<body class="flex min-h-screen flex-col">

<header class="sticky top-0 z-40 bg-ds-frame text-ds-on-forest">
	<div class="mx-auto max-w-container px-4 md:px-6 xl:px-8">
		<div class="flex h-14 items-center justify-between gap-4">
			<a href="<?= site_url('admin/dashboard') ?>" class="ds-logo text-ds-on-forest">
				<span class="ds-logo__dot" aria-hidden="true"></span>
				<span>Admin <span class="hidden sm:inline"><?= esc($store['store_name']) ?></span></span>
			</a>
			<div class="flex items-center gap-4 text-ds-on-forest/70">
				<span class="ds-label hidden sm:inline"><?= esc($adminName) ?></span>
				<a href="<?= site_url() ?>" target="_blank" rel="noopener noreferrer" class="ds-nav-link ds-nav-link--light">Lihat Toko</a>
				<a href="<?= site_url('admin/logout') ?>" class="ds-nav-link ds-nav-link--light">Keluar</a>
			</div>
		</div>
	</div>
</header>

<div class="mx-auto grid w-full max-w-container flex-1 gap-6 px-4 py-6 md:grid-cols-[13rem_minmax(0,1fr)] md:gap-8 md:py-8 xl:px-8">
	<aside class="md:w-52 md:shrink-0">
		<?php
		// Menu admin; item aktif ditandai dari segmen URI pertama setelah "admin".
		$activeMenu = $this->uri->segment(2, 'dashboard');
		// Halaman ubah akun berada di bawah settings (admin/settings/account).
		if ($activeMenu === 'settings' && $this->uri->segment(3) === 'account')
		{
			$activeMenu = 'account';
		}
		$menus = array(
			'dashboard'  => 'Dashboard',
			'products'   => 'Produk',
			'categories' => 'Kategori',
			'slider'     => 'Slider Beranda',
			'settings'   => 'Pengaturan Toko',
			'account'    => 'Ubah Akun',
		);
		?>
		<nav class="ds-menu" aria-label="Menu admin">
			<?php foreach ($menus as $segment => $label): ?>
				<a href="<?= site_url('admin/'.$segment) ?>"
				   class="ds-menu-item" <?= $activeMenu === $segment ? 'aria-current="page"' : '' ?>>
					<?= esc($label) ?>
				</a>
			<?php endforeach; ?>
		</nav>
	</aside>

	<section class="min-w-0">
		<?php if ($this->session->flashdata('success')): ?>
			<div class="ds-alert ds-alert--success mb-6" role="status">
				<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<path d="M20 6 9 17l-5-5"/>
				</svg>
				<span><?= esc($this->session->flashdata('success')) ?></span>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('error')): ?>
			<div class="ds-alert ds-alert--error mb-6" role="alert">
				<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
				</svg>
				<span><?= esc($this->session->flashdata('error')) ?></span>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('info')): ?>
			<div class="ds-alert ds-alert--info mb-6" role="status">
				<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
				</svg>
				<span><?= esc($this->session->flashdata('info')) ?></span>
			</div>
		<?php endif; ?>
