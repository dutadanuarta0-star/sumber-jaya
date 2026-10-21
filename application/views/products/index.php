<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="text-2xl font-bold mb-1">Katalog Produk</h1>
<p class="text-stone-500 text-sm mb-6">Cari produk atau pilih kategori di bawah ini.</p>

<?php
// Pertahankan kata kunci pada tautan filter, dan sebaliknya.
$qParam   = ($search !== '') ? 'q='.rawurlencode($search) : '';
$clearUrl = 'products'.($activeCategory ? '?kategori='.$activeCategory['slug'] : '');
?>

<form action="<?= site_url('products') ?>" method="get" class="mb-5">
	<div class="flex gap-2 max-w-lg">
		<?php if ($activeCategory): ?>
			<input type="hidden" name="kategori" value="<?= esc($activeCategory['slug'], 'attr') ?>">
		<?php endif ?>
		<input type="search" name="q" value="<?= esc($search, 'attr') ?>" maxlength="100"
		       placeholder="Cari nama produk..."
		       class="flex-1 border border-stone-300 rounded-md px-3 py-2 text-sm bg-white">
		<button type="submit" class="bg-amber-800 text-white text-sm font-medium px-4 py-2 rounded-md hover:bg-amber-900">
			Cari
		</button>
	</div>
</form>

<div class="flex flex-wrap gap-2 mb-6">
	<a href="<?= site_url('products'.($qParam !== '' ? '?'.$qParam : '')) ?>"
	   class="px-3 py-1.5 rounded-full text-sm border <?= $activeCategory === NULL ? 'bg-amber-800 text-white border-amber-800' : 'bg-white border-stone-300 hover:border-amber-700' ?>">
		Semua
	</a>
	<?php foreach ($navCategories as $category): ?>
		<a href="<?= site_url('products?kategori='.$category['slug'].($qParam !== '' ? '&'.$qParam : '')) ?>"
		   class="px-3 py-1.5 rounded-full text-sm border <?= ($activeCategory AND $activeCategory['id'] == $category['id']) ? 'bg-amber-800 text-white border-amber-800' : 'bg-white border-stone-300 hover:border-amber-700' ?>">
			<?= esc($category['name']) ?>
		</a>
	<?php endforeach; ?>
</div>

<?php if ($search !== ''): ?>
	<p class="text-sm text-stone-600 mb-4">
		Hasil pencarian <span class="font-medium">&ldquo;<?= esc($search) ?>&rdquo;</span>:
		<span class="font-medium"><?= count($products) ?></span> produk.
		&middot; <a href="<?= site_url($clearUrl) ?>" class="underline hover:text-amber-800">Hapus pencarian</a>
	</p>
<?php endif; ?>

<?php if ($activeCategory): ?>
	<p class="text-sm text-stone-600 mb-4">
		Menampilkan kategori: <span class="font-medium"><?= esc($activeCategory['name']) ?></span>
		<?php if ( ! empty($activeCategory['description'])): ?>
			&mdash; <?= esc($activeCategory['description']) ?>
		<?php endif; ?>
	</p>
<?php endif; ?>

<?php if ( ! $products): ?>
	<div class="bg-white border border-stone-200 rounded-lg p-8 text-center text-stone-500">
		<?php if ($search !== ''): ?>
			Tidak ada produk yang cocok dengan <span class="font-medium">&ldquo;<?= esc($search) ?>&rdquo;</span>.
			Coba kata kunci lain.
		<?php else: ?>
			Tidak ada produk pada kategori ini.
		<?php endif; ?>
	</div>
<?php else: ?>
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
		<?php foreach ($products as $product): ?>
			<?php $this->load->view('products/_card', array('product' => $product)); ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
