<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= esc($pageTitle !== '' ? $pageTitle.' — '.$store['store_name'] : $store['store_name']) ?></title>
	<meta name="description" content="<?= esc(character_limiter((string) $store['about_text'], 150)) ?>">
	<script src="https://cdn.tailwindcss.com"></script>
	<style>[x-cloak]{display:none}</style>
</head>
<body class="bg-stone-50 text-stone-800 flex flex-col min-h-screen">

<header class="bg-white border-b border-stone-200 sticky top-0 z-10">
	<div class="max-w-5xl mx-auto px-4">
		<div class="flex items-center justify-between h-16 gap-4">
			<a href="<?= site_url() ?>" class="font-bold text-lg text-amber-900"><?= esc($store['store_name']) ?></a>

			<nav class="hidden sm:flex items-center gap-6 text-sm">
				<a href="<?= site_url() ?>" class="hover:text-amber-700">Beranda</a>
				<a href="<?= site_url('products') ?>" class="hover:text-amber-700">Produk</a>
				<a href="<?= site_url('about') ?>" class="hover:text-amber-700">Tentang Kami</a>
			</nav>

			<a href="<?= site_url('cart') ?>" class="relative inline-flex items-center gap-2 text-sm px-3 py-2 rounded-md bg-amber-800 text-white hover:bg-amber-900">
				Keranjang
				<?php if ($cartCount > 0): ?>
					<span class="ml-1 inline-flex items-center justify-center min-w-5 h-5 px-1 rounded-full bg-white text-amber-900 text-xs font-semibold"><?= (int) $cartCount ?></span>
				<?php endif; ?>
			</a>
		</div>

		<nav class="sm:hidden flex items-center gap-5 text-sm pb-3 overflow-x-auto">
			<a href="<?= site_url() ?>" class="hover:text-amber-700">Beranda</a>
			<a href="<?= site_url('products') ?>" class="hover:text-amber-700">Produk</a>
			<a href="<?= site_url('about') ?>" class="hover:text-amber-700">Tentang Kami</a>
		</nav>
	</div>
</header>

<main class="flex-1 max-w-5xl w-full mx-auto px-4 py-8">
	<?php if ($this->session->flashdata('success')): ?>
		<div class="mb-6 rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
			<?= esc($this->session->flashdata('success')) ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error')): ?>
		<div class="mb-6 rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
			<?= esc($this->session->flashdata('error')) ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('info')): ?>
		<div class="mb-6 rounded-md bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 text-sm">
			<?= esc($this->session->flashdata('info')) ?>
		</div>
	<?php endif; ?>
