<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php // Kartu produk, dipakai beranda, katalog, dan produk terkait. ?>
<article class="ds-product-card">
	<a href="<?= site_url('products/detail/'.$product['id']) ?>" class="ds-product-card__link">

		<span class="flex items-center justify-between gap-2">
			<span class="ds-label text-ds-ink-soft"><?= esc((string) $product['category_name']) ?></span>
			<?php if ((int) $product['total_stock'] <= 0): ?>
				<span class="ds-badge ds-badge--danger">Habis</span>
			<?php endif; ?>
		</span>

		<h3 class="ds-title-sm text-center"><?= esc($product['name']) ?></h3>

		<div class="ds-product-card__media">
			<?php if ( ! empty($product['image_url'])): ?>
				<img src="<?= esc(img_src($product['image_url']), 'attr') ?>" alt="<?= esc($product['name']) ?>"
				     class="h-full w-full object-contain" loading="lazy" width="600" height="600"
				     onerror="this.onerror=null;this.src='https://placehold.co/600x600/f5f5f4/78716c?text=Gambar+tidak+tersedia'">
			<?php else: ?>
				<span class="ds-caption text-ds-ink-soft">Tanpa gambar</span>
			<?php endif; ?>
		</div>

		<div class="ds-product-card__foot">
			<span class="ds-product-card__cta">Lihat Produk</span>

			<?php if ($product['min_price'] !== NULL): ?>
				<span class="text-right">
					<span class="ds-product-card__price block"><?= rupiah($product['min_price']) ?></span>
					<span class="ds-caption text-ds-ink-soft">mulai</span>
				</span>
			<?php else: ?>
				<span class="ds-badge ds-badge--muted">Harga belum diisi</span>
			<?php endif; ?>
		</div>

	</a>
</article>
