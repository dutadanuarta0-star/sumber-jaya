<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php // Kartu produk, dipakai beranda, katalog, dan produk terkait. ?>
<a href="<?= site_url('products/detail/'.$product['id']) ?>"
   class="bg-white border border-stone-200 rounded-lg overflow-hidden hover:border-amber-700 hover:shadow-sm flex flex-col">
	<?php if ( ! empty($product['image_url'])): ?>
		<img src="<?= esc(img_src($product['image_url'])) ?>" alt="<?= esc($product['name']) ?>" class="w-full h-40 object-cover bg-stone-100" loading="lazy">
	<?php else: ?>
		<div class="w-full h-40 bg-stone-100 flex items-center justify-center text-stone-400 text-sm">Tanpa gambar</div>
	<?php endif; ?>

	<div class="p-4 flex flex-col flex-1">
		<p class="text-xs text-amber-800 mb-1"><?= esc((string) $product['category_name']) ?></p>
		<p class="font-medium leading-snug mb-2"><?= esc($product['name']) ?></p>

		<div class="mt-auto flex items-center justify-between">
			<p class="font-semibold">
				<?php if ($product['min_price'] !== NULL): ?>
					<?= rupiah($product['min_price']) ?>
					<span class="text-xs font-normal text-stone-500">/ mulai</span>
				<?php else: ?>
					<span class="text-sm text-stone-500">Harga belum diisi</span>
				<?php endif; ?>
			</p>
			<?php if ((int) $product['total_stock'] <= 0): ?>
				<span class="text-xs px-2 py-1 rounded bg-stone-200 text-stone-600">Habis</span>
			<?php endif; ?>
		</div>
	</div>
</a>
