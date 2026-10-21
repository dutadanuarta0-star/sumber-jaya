<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= esc(($pageTitle ?? '') !== '' ? $pageTitle.' — Admin' : 'Admin') ?></title>
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-800 min-h-screen">

<header class="bg-stone-900 text-white">
	<div class="max-w-6xl mx-auto px-4">
		<div class="flex items-center justify-between h-14 gap-4">
			<a href="<?= site_url('admin/dashboard') ?>" class="font-semibold">Admin <?= esc($store['store_name']) ?></a>
			<div class="flex items-center gap-4 text-sm">
				<span class="text-stone-400 hidden sm:inline"><?= esc($adminName) ?></span>
				<a href="<?= site_url() ?>" target="_blank" rel="noopener noreferrer" class="hover:text-amber-300">Lihat Toko</a>
				<a href="<?= site_url('admin/logout') ?>" class="text-red-300 hover:text-red-200">Keluar</a>
			</div>
		</div>
	</div>
</header>

<div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row gap-6">
	<aside class="md:w-52 shrink-0">
		<?php
		// Menu admin; item aktif ditandai dari segmen URI pertama setelah "admin".
		$activeMenu = $this->uri->segment(2, 'dashboard');
		$menus = array(
			'dashboard'  => 'Dashboard',
			'products'   => 'Produk',
			'categories' => 'Kategori',
			'settings'   => 'Pengaturan Toko',
		);
		?>
		<nav class="bg-white border border-stone-200 rounded-lg overflow-hidden">
			<?php foreach ($menus as $segment => $label): ?>
				<a href="<?= site_url('admin/'.$segment) ?>"
				   class="block px-4 py-2.5 text-sm border-b border-stone-100 last:border-b-0 <?= $activeMenu === $segment ? 'bg-amber-800 text-white font-medium' : 'hover:bg-stone-50' ?>">
					<?= esc($label) ?>
				</a>
			<?php endforeach; ?>
		</nav>
	</aside>

	<section class="flex-1 min-w-0">
		<?php if ($this->session->flashdata('success')): ?>
			<div class="mb-5 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
				<?= esc($this->session->flashdata('success')) ?>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('error')): ?>
			<div class="mb-5 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
				<?= esc($this->session->flashdata('error')) ?>
			</div>
		<?php endif; ?>

		<?php if ($this->session->flashdata('info')): ?>
			<div class="mb-5 rounded-md bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 text-sm">
				<?= esc($this->session->flashdata('info')) ?>
			</div>
		<?php endif; ?>
