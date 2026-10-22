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
// Warna latar cadangan saat slide tidak punya gambar.
$heroColors = array('bg-amber-900', 'bg-stone-800', 'bg-amber-800');
?>

<section id="heroSlider" class="relative rounded-lg overflow-hidden mb-10" aria-roledescription="carousel" aria-label="Sorotan toko">
	<div class="grid">
		<?php foreach ($heroSlides as $i => $slide): ?>
			<div data-hero-slide
			     class="col-start-1 row-start-1 transition-opacity duration-700 <?= $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' ?>"
			     aria-roledescription="slide"
			     aria-label="Slide <?= $i + 1 ?> dari <?= count($heroSlides) ?>"
			     aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>"
			     <?= $i === 0 ? '' : 'inert' ?>>
				<?php
				$ctaText = trim((string) $slide['cta_text']);
				$ctaUrl  = trim((string) $slide['cta_url']);

				if ($ctaUrl !== '' && ! preg_match('#^https?://#i', $ctaUrl))
				{
					$ctaUrl = site_url($ctaUrl);
				}
				?>
				<div class="relative <?= esc($heroColors[$i % count($heroColors)], 'attr') ?> text-white">
					<?php if ( ! empty($slide['image_url'])): ?>
						<img src="<?= esc(img_src($slide['image_url']), 'attr') ?>" alt=""
						     class="absolute inset-0 w-full h-full object-cover" aria-hidden="true">
						<div class="absolute inset-0 bg-black/50" aria-hidden="true"></div>
					<?php endif; ?>

					<div class="relative px-6 py-12 sm:px-10 min-h-[20rem] flex items-center">
						<div class="w-full">
						<?php if (trim((string) $slide['label']) !== ''): ?>
							<p class="text-amber-200 text-sm mb-2"><?= esc($slide['label']) ?></p>
						<?php endif; ?>

						<?php if ($i === 0): ?>
							<h1 class="text-3xl sm:text-4xl font-bold mb-3"><?= esc($slide['title']) ?></h1>
						<?php else: ?>
							<h2 class="text-3xl sm:text-4xl font-bold mb-3"><?= esc($slide['title']) ?></h2>
						<?php endif; ?>

						<?php if (trim((string) $slide['text']) !== ''): ?>
							<p class="max-w-2xl text-amber-100 mb-6"><?= esc($slide['text']) ?></p>
						<?php endif; ?>

						<?php if ($ctaText !== '' && $ctaUrl !== ''): ?>
							<a href="<?= esc($ctaUrl, 'attr') ?>"
							   class="inline-block bg-white text-amber-900 font-semibold px-5 py-2.5 rounded-md hover:bg-amber-100">
								<?= esc($ctaText) ?>
							</a>
						<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ($heroTotal > 1): ?>
	<button type="button" data-hero-prev aria-label="Slide sebelumnya"
	        class="absolute left-3 top-1/2 -translate-y-1/2 z-10 h-9 w-9 inline-flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white">
		<svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
			<path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>
	<button type="button" data-hero-next aria-label="Slide berikutnya"
	        class="absolute right-3 top-1/2 -translate-y-1/2 z-10 h-9 w-9 inline-flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white">
		<svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
			<path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>

	<div class="absolute bottom-4 inset-x-0 z-10 flex justify-center gap-1">
		<?php foreach ($heroSlides as $i => $slide): ?>
			<button type="button" data-hero-dot="<?= $i ?>" aria-label="Ke slide <?= $i + 1 ?>"
			        aria-current="<?= $i === 0 ? 'true' : 'false' ?>"
			        class="h-7 w-7 inline-flex items-center justify-center rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
				<span class="h-2.5 w-2.5 rounded-full <?= $i === 0 ? 'bg-white' : 'bg-white/40' ?>"></span>
			</button>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</section>

<script>
(function () {
	var root = document.getElementById('heroSlider');
	if (!root) return;

	var slides = root.querySelectorAll('[data-hero-slide]');
	var dots = root.querySelectorAll('[data-hero-dot]');
	var nextBtn = root.querySelector('[data-hero-next]');
	var prevBtn = root.querySelector('[data-hero-prev]');
	var index = 0;
	var timer = null;
	var intervalMs = 5000; // geser otomatis tiap 5 detik
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function show(n) {
		index = (n + slides.length) % slides.length;

		slides.forEach(function (slide, i) {
			var active = (i === index);
			slide.classList.toggle('opacity-0', !active);
			slide.classList.toggle('pointer-events-none', !active);
			slide.setAttribute('aria-hidden', active ? 'false' : 'true');
			slide.toggleAttribute('inert', !active);
		});

		dots.forEach(function (dot, i) {
			var active = (i === index);
			var bullet = dot.querySelector('span');
			bullet.classList.toggle('bg-white', active);
			bullet.classList.toggle('bg-white/40', !active);
			dot.setAttribute('aria-current', active ? 'true' : 'false');
		});
	}

	function next() { show(index + 1); }
	function prev() { show(index - 1); }
	function stop() { if (timer) { clearInterval(timer); timer = null; } }
	function start() {
		if (reduceMotion) return;
		stop();
		timer = setInterval(next, intervalMs);
	}

	if (slides.length < 2 || ! nextBtn || ! prevBtn)
	{
		return;
	}

	nextBtn.addEventListener('click', function () { next(); start(); });
	prevBtn.addEventListener('click', function () { prev(); start(); });

	dots.forEach(function (dot, i) {
		dot.addEventListener('click', function () { show(i); start(); });
	});

	// Berhenti saat kursor/fokus di atas slider, lanjut lagi setelahnya.
	root.addEventListener('mouseenter', stop);
	root.addEventListener('mouseleave', start);
	root.addEventListener('focusin', stop);
	root.addEventListener('focusout', start);

	start();
})();
</script>

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
