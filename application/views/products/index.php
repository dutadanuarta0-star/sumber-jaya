<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="ds-display-lg mb-2">Katalog Produk</h1>
<p class="ds-body mb-8 text-ds-ink-soft">Cari produk atau pilih kategori di bawah ini.</p>

<?php
// Pertahankan kata kunci pada tautan filter, dan sebaliknya.
$qParam   = ($search !== '') ? 'q='.rawurlencode($search) : '';
$clearUrl = 'products'.($activeCategory ? '?kategori='.$activeCategory['slug'] : '');
?>

<form action="<?= site_url('products') ?>" method="get" class="mb-6 max-w-xl" role="search">
	<div class="flex flex-col gap-3 sm:flex-row">
		<?php if ($activeCategory): ?>
			<input type="hidden" name="kategori" value="<?= esc($activeCategory['slug'], 'attr') ?>">
		<?php endif ?>
		<label for="catalog-search" class="visually-hidden">Cari nama produk</label>
		<input type="search" id="catalog-search" name="q" value="<?= esc($search, 'attr') ?>" maxlength="100"
		       placeholder="Cari nama produk..."
		       class="ds-input flex-1">
		<button type="submit" class="ds-btn ds-btn--primary">Cari</button>
	</div>
</form>

<div class="mb-8 flex flex-wrap gap-2">
	<a href="<?= site_url('products'.($qParam !== '' ? '?'.$qParam : '')) ?>"
	   class="ds-chip" <?= $activeCategory === NULL ? 'aria-current="true"' : '' ?>>
		Semua
	</a>
	<?php foreach ($navCategories as $category): ?>
		<a href="<?= site_url('products?kategori='.$category['slug'].($qParam !== '' ? '&'.$qParam : '')) ?>"
		   class="ds-chip" <?= ($activeCategory AND $activeCategory['id'] == $category['id']) ? 'aria-current="true"' : '' ?>>
			<?= esc($category['name']) ?>
		</a>
	<?php endforeach; ?>
</div>

<?php if ($search !== ''): ?>
	<p class="ds-body mb-4 text-ds-ink-soft">
		Hasil pencarian <span class="font-bold text-ds-ink">&ldquo;<?= esc($search) ?>&rdquo;</span>:
		<span class="font-bold text-ds-ink"><?= count($products) ?></span> produk.
		&middot; <a href="<?= site_url($clearUrl) ?>" class="ds-nav-link">Hapus pencarian</a>
	</p>
<?php endif; ?>

<?php if ($activeCategory): ?>
	<p class="ds-body mb-4 text-ds-ink-soft">
		Menampilkan kategori: <span class="font-bold text-ds-ink"><?= esc($activeCategory['name']) ?></span>
		<?php if ( ! empty($activeCategory['description'])): ?>
			&mdash; <?= esc($activeCategory['description']) ?>
		<?php endif; ?>
	</p>
<?php endif; ?>

<?php if ( ! $products): ?>
	<div class="ds-panel py-12 text-center text-ds-ink-soft">
		<?php if ($search !== ''): ?>
			Tidak ada produk yang cocok dengan <span class="font-bold text-ds-ink">&ldquo;<?= esc($search) ?>&rdquo;</span>.
			Coba kata kunci lain.
		<?php else: ?>
			Tidak ada produk pada kategori ini.
		<?php endif; ?>
	</div>
<?php else: ?>
	<div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-4 lg:grid-cols-3 lg:gap-6">
		<?php foreach ($products as $product): ?>
			<?php $this->load->view('products/_card', array('product' => $product)); ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
