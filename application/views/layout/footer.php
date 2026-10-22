<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	</div>
</main>

<footer class="relative mt-12 overflow-hidden bg-ds-frame text-ds-on-forest md:mt-16">
	<div class="mx-auto max-w-container px-4 py-10 md:px-6 md:py-14 xl:px-8">
		<div class="grid gap-8 md:grid-cols-3 md:gap-10">

			<div>
				<a href="<?= site_url() ?>" class="ds-logo mb-4 inline-flex">
					<span class="ds-logo__dot" aria-hidden="true"></span>
					<span><?= esc($store['store_name']) ?></span>
				</a>
				<p class="ds-body ds-measure text-ds-on-forest/75">
					Biji kopi pilihan dari petani lokal Indonesia, roasting segar setiap minggu.
				</p>
			</div>

			<div>
				<p class="ds-label mb-3 text-ds-on-forest/60">Kontak</p>
				<ul class="ds-body space-y-1 text-ds-on-forest/80">
					<?php if ( ! empty($store['phone'])): ?>
						<li>Telepon: <?= esc($store['phone']) ?></li>
					<?php endif; ?>
					<?php if ( ! empty($store['email'])): ?>
						<li>Email: <?= esc($store['email']) ?></li>
					<?php endif; ?>
					<?php if ( ! empty($store['address'])): ?>
						<li><?= esc($store['address']) ?></li>
					<?php endif; ?>
				</ul>
			</div>

			<div>
				<p class="ds-label mb-3 text-ds-on-forest/60">Navigasi</p>
				<nav class="flex flex-col items-start gap-2" aria-label="Navigasi footer">
					<a href="<?= site_url() ?>" class="ds-nav-link ds-nav-link--light">Beranda</a>
					<a href="<?= site_url('products') ?>" class="ds-nav-link ds-nav-link--light">Produk</a>
					<a href="<?= site_url('about') ?>" class="ds-nav-link ds-nav-link--light">Tentang Kami</a>
					<a href="<?= site_url('cart') ?>" class="ds-nav-link ds-nav-link--light">Keranjang</a>
				</nav>
			</div>
		</div>
	</div>

	<div class="border-t border-ds-on-forest/15">
		<div class="mx-auto flex max-w-container flex-col gap-2 px-4 py-5 text-ds-on-forest/60 md:flex-row md:items-center md:justify-between md:px-6 xl:px-8">
			<p class="ds-caption">
				&copy; <?= date('Y') ?> <?= esc($store['store_name']) ?>. Semua hak dilindungi.
			</p>
			<a href="<?= site_url('admin/login') ?>" class="ds-nav-link ds-nav-link--light">Login Admin</a>
		</div>
	</div>
</footer>

<?php if ( ! empty($store['whatsapp_number'])): ?>
<!-- Tombol chat WhatsApp mengambang (nomor dari menu Pengaturan).
     Warna mengikuti palet design system: hijau hutan, bukan hijau brand WA. -->
<a href="https://wa.me/<?= esc($store['whatsapp_number'], 'attr') ?>?text=<?= rawurlencode('Halo '.$store['store_name'].', saya ingin bertanya tentang produk.') ?>"
   target="_blank" rel="noopener"
   aria-label="Chat WhatsApp dengan <?= esc($store['store_name'], 'attr') ?>"
   class="ds-btn ds-btn--primary fixed bottom-5 right-5 z-50 h-14 w-14 !px-0 shadow-ds-md sm:h-auto sm:w-auto sm:!px-5">
	<svg viewBox="0 0 32 32" aria-hidden="true" class="h-6 w-6 fill-current">
		<path d="M16.1 3C9 3 3.2 8.8 3.2 15.9c0 2.3.6 4.5 1.8 6.4L3 29.5l7.4-1.9c1.8 1 3.9 1.5 6 1.5h.1c7 0 12.9-5.8 12.9-12.9C29.4 8.8 23.2 3 16.1 3zm0 23.5h-.1c-1.9 0-3.7-.5-5.3-1.5l-.4-.2-4.4 1.1 1.2-4.3-.3-.4c-1.1-1.7-1.6-3.6-1.6-5.6 0-5.8 4.7-10.5 10.5-10.5 2.8 0 5.4 1.1 7.4 3.1 2 2 3.1 4.6 3.1 7.4 0 5.8-4.8 10.9-10.1 10.9zm5.8-7.9c-.3-.2-1.9-.9-2.2-1s-.5-.2-.7.2-.8 1-1 1.2-.4.2-.7.1c-.3-.2-1.4-.5-2.6-1.6-1-.9-1.6-1.9-1.8-2.3s0-.5.2-.7c.2-.2.4-.5.7-.7.2-.2.2-.4.3-.6s.1-.5 0-.7c-.1-.2-.7-1.8-1-2.4-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.1-1.2 2.8s1.2 3.2 1.4 3.4c.2.2 2.4 3.7 5.9 5.1.8.4 1.5.6 2 .7.8.3 1.6.2 2.2.1.7-.1 1.9-.8 2.2-1.5.3-.8.3-1.4.2-1.5-.2-.2-.3-.3-.6-.4z"/>
	</svg>
	<span class="hidden sm:inline">Chat WhatsApp</span>
</a>
<?php endif ?>

<script src="<?= base_url('assets/js/ds-ui.js') ?>"></script>

<?php if ( ! empty($useSwiper)): ?>
<!-- Swiper 14 + inisialisasi slider hero (harus setelah Swiper termuat). -->
<script src="https://cdn.jsdelivr.net/npm/swiper@14.3.0/swiper-bundle.min.js" referrerpolicy="no-referrer"></script>
<script src="<?= base_url('assets/js/ds-slider.js') ?>"></script>
<?php endif; ?>

</body>
</html>
