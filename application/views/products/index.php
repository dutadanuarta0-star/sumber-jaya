<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="text-2xl font-bold mb-1">Katalog Produk</h1>
<p class="text-stone-500 text-sm mb-6">Pilih kategori atau telusuri semua produk kami.</p>

<div class="flex flex-wrap gap-2 mb-6">
	<a href="<?= site_url('products') ?>"
	   class="px-3 py-1.5 rounded-full text-sm border <?= $activeCategory === NULL ? 'bg-amber-800 text-white border-amber-800' : 'bg-white border-stone-300 hover:border-amber-700' ?>">
		Semua
	</a>
	<?php foreach ($navCategories as $category): ?>
		<a href="<?= site_url('products?kategori='.$category['slug']) ?>"
		   class="px-3 py-1.5 rounded-full text-sm border <?= ($activeCategory AND $activeCategory['id'] == $category['id']) ? 'bg-amber-800 text-white border-amber-800' : 'bg-white border-stone-300 hover:border-amber-700' ?>">
			<?= esc($category['name']) ?>
		</a>
	<?php endforeach; ?>
</div>

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
		Tidak ada produk pada kategori ini.
	</div>
<?php else: ?>
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
		<?php foreach ($products as $product): ?>
			<?php $this->load->view('products/_card', array('product' => $product)); ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
