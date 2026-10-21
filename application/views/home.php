<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="bg-amber-900 text-white rounded-lg px-6 py-12 mb-10">
	<p class="text-amber-200 text-sm mb-2">Roasted fresh every week</p>
	<h1 class="text-3xl sm:text-4xl font-bold mb-3">Kopi pilihan dari petani lokal Indonesia</h1>
	<p class="max-w-2xl text-amber-100 mb-6">
		Dari Gayo sampai Toraja. Pilih ukuran favoritmu, tambahkan ke keranjang, lalu checkout langsung lewat WhatsApp.
	</p>
	<a href="<?= site_url('products') ?>" class="inline-block bg-white text-amber-900 font-semibold px-5 py-2.5 rounded-md hover:bg-amber-100">
		Lihat Katalog
	</a>
</section>

<section class="mb-10">
	<h2 class="text-xl font-semibold mb-4">Kategori</h2>
	<div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
		<?php foreach ($categories as $category): ?>
			<a href="<?= site_url('products?kategori='.$category['slug']) ?>"
			   class="bg-white border border-stone-200 rounded-lg p-4 hover:border-amber-700 hover:shadow-sm">
				<p class="font-medium text-stone-800"><?= esc($category['name']) ?></p>
				<p class="text-xs text-stone-500 mt-1"><?= (int) $category['product_count'] ?> produk</p>
			</a>
		<?php endforeach; ?>
	</div>
</section>

<section>
	<div class="flex items-end justify-between mb-4">
		<h2 class="text-xl font-semibold">Produk Terbaru</h2>
		<a href="<?= site_url('products') ?>" class="text-sm text-amber-800 hover:underline">Semua produk &rarr;</a>
	</div>

	<?php if ( ! $featured): ?>
		<p class="text-stone-500 text-sm">Belum ada produk. Silakan tambahkan lewat halaman admin.</p>
	<?php else: ?>
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ($featured as $product): ?>
				<?php $this->load->view('products/_card', array('product' => $product)); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
