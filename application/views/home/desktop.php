<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/**
 * Halaman bingkai beranda ukuran desktop (1440 x 900).
 *
 * Isi beranda dimuat lewat iframe supaya layout desktop (navbar penuh, grid
 * kategori 2 kolom, dst.) tetap terpakai berapa pun lebar jendela browser.
 * Halaman ini berdiri sendiri — tidak memakai layout/header.php.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?= esc($pageTitle) ?> — <?= esc($store['store_name']) ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Anton&amp;family=Space+Mono:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet">

	<script src="https://cdn.tailwindcss.com"></script>
	<script src="<?= base_url('assets/js/ds-tailwind-config.js') ?>"></script>
	<link rel="stylesheet" href="<?= base_url('assets/css/design-system.css') ?>">
</head>
<body class="ds-canvas-page">

	<div class="ds-canvas-bar">
		<span class="ds-canvas-bar__title">Beranda — Tampilan Desktop</span>
		<span class="ds-canvas-bar__meta"><?= (int) $frameWidth ?> &times; <?= (int) $frameHeight ?> px</span>
	</div>

	<div class="ds-canvas-frame">
		<iframe src="<?= esc($frameUrl, 'attr') ?>"
		        width="<?= (int) $frameWidth ?>"
		        height="<?= (int) $frameHeight ?>"
		        title="Beranda dalam tampilan desktop <?= (int) $frameWidth ?> x <?= (int) $frameHeight ?> piksel"
		        loading="eager"
		        referrerpolicy="same-origin"></iframe>
	</div>

</body>
</html>
