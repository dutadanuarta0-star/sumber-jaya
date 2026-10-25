<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= esc($pageTitle !== '' ? $pageTitle.' — '.$store['store_name'] : $store['store_name']) ?></title>
	<meta name="description" content="<?= esc(character_limiter((string) $store['about_text'], 150)) ?>">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=Space+Mono:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet">

	<script src="https://cdn.tailwindcss.com"></script>
	<script src="<?= base_url('assets/js/ds-tailwind-config.js') ?>"></script>
	<?php if ( ! empty($useSwiper)): ?>
	<!-- Swiper 14 — slider hero beranda (dimuat hanya pada halaman yang memakainya). -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@14.3.0/swiper-bundle.min.css" referrerpolicy="no-referrer">
	<?php endif; ?>
	<link rel="stylesheet" href="<?= base_url('assets/css/design-system.css') ?>">
</head>
<body class="flex min-h-screen flex-col">

<?php
// Menu aktif ditentukan dari segmen URI pertama.
$seg1    = (string) $this->uri->segment(1);
$isHome  = ($seg1 === '' OR $seg1 === 'home' OR $seg1 === 'index' OR $seg1 === 'beranda-desktop');
$waLink  = wa_link($store['whatsapp_number'], 'Halo '.$store['store_name'].', saya ingin bertanya tentang produk.');
?>

<header class="sticky top-0 z-40 border-b border-ds-line bg-ds-page/90 backdrop-blur">
	<div class="mx-auto max-w-container px-4 md:px-6 xl:px-8">
		<div class="relative flex h-14 items-center justify-between gap-3 lg:h-[72px]">

			<!-- Kiri (desktop): 3 link utama -->
			<nav class="hidden flex-1 items-center gap-6 lg:flex" aria-label="Navigasi utama">
				<a href="<?= site_url() ?>" class="ds-nav-link" <?= $isHome ? 'aria-current="page"' : '' ?>>Beranda</a>
				<a href="<?= site_url('products') ?>" class="ds-nav-link" <?= ($seg1 === 'products') ? 'aria-current="page"' : '' ?>>Produk</a>
				<a href="<?= site_url('about') ?>" class="ds-nav-link" <?= ($seg1 === 'about') ? 'aria-current="page"' : '' ?>>Tentang Kami</a>
			</nav>

			<!-- Logo (mobile: kiri, desktop: tengah) -->
			<a href="<?= site_url() ?>" class="ds-logo lg:hidden">
				<span class="ds-logo__dot" aria-hidden="true"></span>
				<span><?= esc($store['store_name']) ?></span>
			</a>

			<!-- Kanan (desktop): cari, WhatsApp, keranjang -->
			<div class="flex flex-1 items-center justify-end gap-3 lg:gap-6">

				<form action="<?= site_url('products') ?>" method="get" class="hidden lg:block" role="search">
					<label for="nav-search" class="visually-hidden">Cari produk</label>
					<input type="search" id="nav-search" name="q" maxlength="100" placeholder="Cari produk..."
					       value="<?= esc((string) $this->input->get('q', TRUE), 'attr') ?>"
					       class="ds-input ds-input--sm w-44 xl:w-56">
				</form>

				<?php if ($waLink !== ''): ?>
					<a href="<?= esc($waLink, 'attr') ?>" target="_blank" rel="noopener noreferrer" class="ds-nav-link hidden lg:inline-flex">
						WhatsApp
					</a>
				<?php endif; ?>

				<a href="<?= site_url('cart') ?>" class="ds-nav-link" aria-label="Keranjang<?= $cartCount > 0 ? ', '.(int) $cartCount.' item' : ', kosong' ?>">
					<span class="hidden lg:inline">Keranjang<?= $cartCount > 0 ? ' ('.(int) $cartCount.')' : '' ?></span>
					<span class="lg:hidden relative inline-flex items-center justify-center">
						<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
							<path d="M3 6h18"/>
							<path d="M16 10a4 4 0 0 1-8 0"/>
						</svg>
						<?php if ($cartCount > 0): ?>
							<span class="ds-cart-badge" aria-live="polite"><?= (int) $cartCount ?></span>
						<?php endif; ?>
					</span>
				</a>

				<button type="button" class="ds-icon-btn lg:hidden" data-ds-drawer-open aria-expanded="false" aria-controls="siteDrawer" aria-label="Buka menu">
					<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
						<path d="M3 6h18M3 12h18M3 18h18"/>
					</svg>
				</button>
			</div>

			<!-- Logo (desktop: tengah) -->
			<a href="<?= site_url() ?>" class="ds-logo absolute left-1/2 hidden -translate-x-1/2 lg:inline-flex">
				<span class="ds-logo__dot" aria-hidden="true"></span>
				<span><?= esc($store['store_name']) ?></span>
			</a>
		</div>
	</div>
</header>

<!-- Drawer menu mobile -->
<div id="siteDrawer" class="ds-drawer" data-ds-drawer aria-hidden="true" role="dialog" aria-modal="true" aria-label="Menu utama">
	<div class="ds-drawer__backdrop" data-ds-drawer-backdrop></div>
	<div class="ds-drawer__panel">
		<div class="flex items-center justify-between gap-3 pb-4">
			<span class="ds-logo">
				<span class="ds-logo__dot" aria-hidden="true"></span>
				<span><?= esc($store['store_name']) ?></span>
			</span>
			<button type="button" class="ds-icon-btn" data-ds-drawer-close aria-label="Tutup menu">
				<svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
					<path d="M18 6 6 18M6 6l12 12"/>
				</svg>
			</button>
		</div>

		<nav class="mb-6" aria-label="Navigasi mobile">
			<a href="<?= site_url() ?>" class="ds-drawer__link">Beranda</a>
			<a href="<?= site_url('products') ?>" class="ds-drawer__link">Produk</a>
			<a href="<?= site_url('about') ?>" class="ds-drawer__link">Tentang</a>
		</nav>

		<form action="<?= site_url('products') ?>" method="get" class="mb-6" role="search">
			<label for="drawer-search" class="ds-label-text">Cari produk</label>
			<input type="search" id="drawer-search" name="q" maxlength="100" placeholder="Cari nama produk..."
			       value="<?= esc((string) $this->input->get('q', TRUE), 'attr') ?>"
			       class="ds-input">
		</form>

		<div class="border-t border-ds-line pt-4">
			<?php if ($waLink !== ''): ?>
				<a href="<?= esc($waLink, 'attr') ?>" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--primary ds-btn--block mb-2">
					Chat WhatsApp
				</a>
			<?php endif; ?>
			<a href="<?= site_url('admin/login') ?>" class="ds-nav-link">Login Admin</a>
		</div>
	</div>
</div>

<main class="flex-1">
	<div class="mx-auto max-w-container px-4 py-8 md:px-6 md:py-12 xl:px-8">
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
