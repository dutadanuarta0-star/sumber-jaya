<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Slide hero dimuat dari database (admin > Slider Beranda).
$heroSlides = isset($heroSlides) ? $heroSlides : array();

// Bila belum ada slide aktif, tampilkan satu slide bawaan agar beranda tetap utuh.
if ( ! $heroSlides)
{
	$heroSlides = array(array(
		'label'     => 'Roasted fresh every week',
		'title'     => 'Kopi pilihan dari petani lokal Indonesia',
		'text'      => 'Dari Gayo sampai Toraja. Pilih ukuran favoritmu, tambahkan ke keranjang, lalu checkout langsung lewat WhatsApp.',
		'image_url' => NULL,
		'cta_text'  => 'Lihat Katalog',
		'cta_url'   => 'products',
	));
}

$heroTotal = count($heroSlides);
// Warna latar cadangan saat slide tidak punya gambar (semua nada gelap agar
// teks krem + tombol terang tetap terbaca).
$heroColors = array('bg-ds-forest', 'bg-ds-frame', 'bg-ds-ink');

// Produk yang dipakai sebagai foto feature card hijau.
$featureProduct = $featured ? $featured[0] : NULL;
?>

<!-- ============ HERO (slider Swiper) ============ -->
<section id="heroSlider" data-ds-hero
         class="ds-hero swiper relative overflow-hidden rounded-xl bg-ds-forest"
         aria-roledescription="carousel" aria-label="Sorotan toko">
	<div class="swiper-wrapper">
		<?php foreach ($heroSlides as $i => $slide): ?>
			<?php
			$ctaText = trim((string) $slide['cta_text']);
			$ctaUrl  = trim((string) $slide['cta_url']);

			if ($ctaUrl !== '' && ! preg_match('#^https?://#i', $ctaUrl))
			{
				$ctaUrl = site_url($ctaUrl);
			}
			?>
			<div class="swiper-slide relative flex min-h-[26rem] flex-col md:min-h-[32rem] <?= esc($heroColors[$i % count($heroColors)], 'attr') ?>"
			     role="group" aria-roledescription="slide"
			     aria-label="Slide <?= $i + 1 ?> dari <?= $heroTotal ?>">
				<?php if ( ! empty($slide['image_url'])): ?>
					<img src="<?= esc(img_src($slide['image_url']), 'attr') ?>" alt=""
					     class="absolute inset-0 h-full w-full object-cover" aria-hidden="true">
					<div class="absolute inset-0" style="background: var(--color-overlay)" aria-hidden="true"></div>
				<?php endif; ?>

				<div class="relative flex flex-1 items-end px-5 py-10 md:items-center md:px-10 md:py-16">
					<div class="w-full max-w-3xl">
						<?php if (trim((string) $slide['label']) !== ''): ?>
							<p class="ds-label mb-3 text-ds-mint"><?= esc($slide['label']) ?></p>
						<?php endif; ?>

						<?php if ($i === 0): ?>
							<h1 class="ds-display-xl mb-4 text-ds-on-forest"><?= esc($slide['title']) ?></h1>
						<?php else: ?>
							<h2 class="ds-display-xl mb-4 text-ds-on-forest"><?= esc($slide['title']) ?></h2>
						<?php endif; ?>

						<?php if (trim((string) $slide['text']) !== ''): ?>
							<p class="ds-body ds-measure mb-6 text-ds-on-forest/85"><?= esc($slide['text']) ?></p>
						<?php endif; ?>

						<?php if ($ctaText !== '' && $ctaUrl !== ''): ?>
							<a href="<?= esc($ctaUrl, 'attr') ?>" class="ds-btn ds-btn--light">
								<?= esc($ctaText) ?>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ($heroTotal > 1): ?>
	<div class="swiper-button-prev" role="button" aria-label="Slide sebelumnya"></div>
	<div class="swiper-button-next" role="button" aria-label="Slide berikutnya"></div>
	<div class="swiper-pagination"></div>
	<?php endif; ?>
</section>

<!-- ============ KATEGORI (kartu: gambar kiri, teks kanan) ============ -->
<section class="mt-12 md:mt-16">
	<div class="mb-6 flex items-end justify-between gap-4 md:mb-8">
		<h2 class="ds-display-lg">Kategori</h2>
		<a href="<?= site_url('products') ?>" class="ds-nav-link shrink-0">Semua produk &rarr;</a>
	</div>

	<?php if ( ! $categories): ?>
		<div class="ds-panel text-center text-ds-ink-soft">
			Belum ada kategori. Silakan tambahkan lewat halaman admin.
		</div>
	<?php else: ?>
		<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">
			<?php foreach ($categories as $category): ?>
				<?php
				$categoryUrl   = site_url('products?kategori='.$category['slug']);
				$categoryImage = trim((string) $category['image_url']);
				?>
				<article class="ds-cat-card">
					<div class="ds-cat-card__media">
						<span class="ds-cat-card__fallback" aria-hidden="true">
							<svg viewBox="0 0 24 24" class="h-9 w-9" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M17 8h1a4 4 0 0 1 0 8h-1"/>
								<path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4z"/>
								<path d="M6 2v2M10 2v2M14 2v2"/>
							</svg>
						</span>
						<?php if ($categoryImage !== ''): ?>
							<img src="<?= esc(img_src($categoryImage), 'attr') ?>" alt="" loading="lazy"
							     onerror="this.style.display='none'">
						<?php endif; ?>
					</div>

					<div class="ds-cat-card__body">
						<h3 class="ds-title-sm ds-cat-card__name">
							<a href="<?= esc($categoryUrl, 'attr') ?>" class="ds-cat-card__link"><?= esc($category['name']) ?></a>
						</h3>

						<?php if (trim((string) $category['description']) !== ''): ?>
							<p class="ds-cat-card__desc"><?= esc((string) $category['description']) ?></p>
						<?php endif; ?>

						<div class="ds-cat-card__foot">
							<span class="ds-badge ds-badge--muted"><?= (int) $category['product_count'] ?> produk</span>
							<span class="ds-cat-card__cta">
								Lihat produk
								<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<path d="M5 12h14M13 6l6 6-6 6"/>
								</svg>
							</span>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>

<!-- ============ BEST PRODUCTS ============ -->
<section class="mt-12 md:mt-16">
	<div class="mb-6 flex items-end justify-between gap-4 md:mb-8">
		<h2 class="ds-display-lg">Kopi Pilihan</h2>
		<a href="<?= site_url('products') ?>" class="ds-nav-link shrink-0">Semua produk &rarr;</a>
	</div>

	<?php if ( ! $featured): ?>
		<div class="ds-panel text-center text-ds-ink-soft">
			Belum ada produk. Silakan tambahkan lewat halaman admin.
		</div>
	<?php else: ?>
		<div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-4 lg:grid-cols-3 lg:gap-6">
			<?php foreach ($featured as $product): ?>
				<?php $this->load->view('products/_card', array('product' => $product)); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>

<!-- ============ FEATURE CARD (hijau) ============ -->
<?php if ( ! empty($store['whatsapp_number'])): ?>
<section class="mt-12 md:mt-16">
	<div class="ds-feature">
		<div>
			<p class="ds-label mb-3 text-ds-mint">Dibuat tiap minggu</p>
			<h2 class="ds-display-lg mb-4 text-ds-mint">Secangkir Cerita Kopi</h2>
			<p class="ds-body ds-measure mb-6 text-ds-on-forest">
				Setiap biji punya asal, musim panen, dan cerita petani di balik-nya.
				Kami Roast ringan sampai sedang agar rasa-asli kopi Indonesia tetap terasa
				di setiap cangkir.
			</p>
			<a href="https://wa.me/<?= esc($store['whatsapp_number'], 'attr') ?>?text=<?= rawurlencode('Halo '.$store['store_name'].', saya ingin bertanya tentang produk.') ?>"
			   target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--light">
				Tanya PRODUK Kami
			</a>
		</div>

		<figure class="ds-feature__media">
			<?php if ($featureProduct && ! empty($featureProduct['image_url'])): ?>
				<img src="<?= esc(img_src($featureProduct['image_url']), 'attr') ?>" alt="<?= esc($featureProduct['name']) ?>"
				     class="aspect-[4/3] h-full w-full object-cover"
				     onerror="this.onerror=null;this.src='https://placehold.co/800x600/f5f5f4/78716c?text=Gambar+tidak+tersedia'">
				<figcaption class="ds-feature__caption">
					<?= esc($featureProduct['name']) ?>
					<?php if ( ! empty($featureProduct['category_name'])): ?>
						&mdash; <?= esc((string) $featureProduct['category_name']) ?>
					<?php endif; ?>
				</figcaption>
			<?php else: ?>
				<div class="flex aspect-[4/3] items-center justify-center bg-ds-forest/30 text-ds-on-forest/70">
					<svg viewBox="0 0 24 24" class="h-12 w-12" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M17 8h1a4 4 0 0 1 0 8h-1"/>
						<path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4z"/>
						<path d="M6 2v2M10 2v2M14 2v2"/>
					</svg>
				</div>
			<?php endif; ?>
		</figure>
	</div>
</section>

<!-- ============ CTA BANNER (tan) ============ -->
<section class="mt-12 md:mt-16">
	<div class="ds-cta">
		<svg viewBox="0 0 64 64" class="ds-doodle ds-doodle--left h-16 w-16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
			<path d="M10 22h36v18a10 10 0 0 1-10 10H20a10 10 0 0 1-10-10z"/>
			<path d="M46 28h6a6 6 0 0 1 0 12h-6"/>
			<path d="M22 14c0-3 3-3 3-6M32 14c0-3 3-3 3-6"/>
		</svg>

		<p class="ds-label text-ds-ink-soft">Tanpa daftar akun</p>
		<h2 class="ds-display-lg max-w-[18ch]">Gabung Klan Kopi</h2>
		<p class="ds-body max-w-[48ch] text-ds-ink/80">
			Dapatkan kabar stok baru, jadwal roasting mingguan, dan penawaran khusus
			lewat WhatsApp. Cukup chat &mdash; cepat dan tanpa formality.
		</p>
		<a href="https://wa.me/<?= esc($store['whatsapp_number'], 'attr') ?>?text=<?= rawurlencode('Halo '.$store['store_name'].', saya ingin bergabung dengan Klan Kopi.') ?>"
		   target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--primary">
			Chat WhatsApp
		</a>

		<svg viewBox="0 0 64 64" class="ds-doodle ds-doodle--right h-16 w-16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
			<circle cx="32" cy="32" r="20"/>
			<circle cx="26" cy="26" r="2.2"/>
			<circle cx="38" cy="29" r="2.2"/>
			<circle cx="29" cy="39" r="2.2"/>
		</svg>
	</div>
</section>
<?php endif; ?>
