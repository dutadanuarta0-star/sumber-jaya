<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</main>

<footer class="bg-stone-800 text-stone-300 text-sm mt-8">
	<div class="max-w-5xl mx-auto px-4 py-8 grid gap-6 sm:grid-cols-3">
		<div>
			<p class="font-semibold text-white mb-2"><?= esc($store['store_name']) ?></p>
			<p>Biji kopi pilihan dari petani lokal Indonesia.</p>
		</div>
		<div>
			<p class="font-semibold text-white mb-2">Kontak</p>
			<?php if ( ! empty($store['phone'])): ?>
				<p>Telepon: <?= esc($store['phone']) ?></p>
			<?php endif; ?>
			<?php if ( ! empty($store['email'])): ?>
				<p>Email: <?= esc($store['email']) ?></p>
			<?php endif; ?>
			<?php if ( ! empty($store['address'])): ?>
				<p><?= esc($store['address']) ?></p>
			<?php endif; ?>
		</div>
		<div>
			<p class="font-semibold text-white mb-2">Navigasi</p>
			<a href="<?= site_url() ?>" class="block hover:text-white">Beranda</a>
			<a href="<?= site_url('products') ?>" class="block hover:text-white">Produk</a>
			<a href="<?= site_url('about') ?>" class="block hover:text-white">Tentang Kami</a>
		</div>
	</div>
	<div class="border-t border-stone-700">
		<p class="max-w-5xl mx-auto px-4 py-4 text-xs text-stone-400">
			&copy; <?= date('Y') ?> <?= esc($store['store_name']) ?>.
			<a href="<?= site_url('admin/login') ?>" class="hover:text-stone-200">Login Admin</a>
		</p>
	</div>
</footer>

<?php if ( ! empty($store['whatsapp_number'])): ?>
<!-- Tombol chat WhatsApp mengambang (nomor dari menu Pengaturan) -->
<a href="https://wa.me/<?= esc($store['whatsapp_number'], 'attr') ?>?text=<?= rawurlencode('Halo '.$store['store_name'].', saya ingin bertanya tentang produk.') ?>"
   target="_blank" rel="noopener"
   class="fixed bottom-5 right-5 z-50 inline-flex items-center gap-2 bg-[#25D366] hover:bg-[#1ebe5b] text-white font-semibold text-sm px-4 py-3 rounded-full shadow-lg">
	<svg viewBox="0 0 32 32" aria-hidden="true" class="w-5 h-5 fill-current">
		<path d="M16.1 3C9 3 3.2 8.8 3.2 15.9c0 2.3.6 4.5 1.8 6.4L3 29.5l7.4-1.9c1.8 1 3.9 1.5 6 1.5h.1c7 0 12.9-5.8 12.9-12.9C29.4 8.8 23.2 3 16.1 3zm0 23.5h-.1c-1.9 0-3.7-.5-5.3-1.5l-.4-.2-4.4 1.1 1.2-4.3-.3-.4c-1.1-1.7-1.6-3.6-1.6-5.6 0-5.8 4.7-10.5 10.5-10.5 2.8 0 5.4 1.1 7.4 3.1 2 2 3.1 4.6 3.1 7.4 0 5.8-4.8 10.9-10.1 10.9zm5.8-7.9c-.3-.2-1.9-.9-2.2-1s-.5-.2-.7.2-.8 1-1 1.2-.4.2-.7.1c-.3-.2-1.4-.5-2.6-1.6-1-.9-1.6-1.9-1.8-2.3s0-.5.2-.7c.2-.2.4-.5.7-.7.2-.2.2-.4.3-.6s.1-.5 0-.7c-.1-.2-.7-1.8-1-2.4-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.1-1.2 2.8s1.2 3.2 1.4 3.4c.2.2 2.4 3.7 5.9 5.1.8.4 1.5.6 2 .7.8.3 1.6.2 2.2.1.7-.1 1.9-.8 2.2-1.5.3-.8.3-1.4.2-1.5-.1-.2-.3-.3-.6-.4z"/>
	</svg>
	<span class="hidden sm:inline">Chat WhatsApp</span>
</a>
<?php endif ?>

</body>
</html>
