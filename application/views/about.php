<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="max-w-3xl">
	<h1 class="text-2xl font-bold mb-4">Tentang <?= esc($store['store_name']) ?></h1>

	<?php if ( ! empty($store['about_text'])): ?>
		<div class="bg-white border border-stone-200 rounded-lg p-6 text-stone-700 leading-relaxed">
			<?= nl2br(esc($store['about_text'])) ?>
		</div>
	<?php else: ?>
		<p class="text-stone-500 text-sm">Deskripsi toko belum diisi oleh admin.</p>
	<?php endif; ?>

	<?php if ( ! empty($store['address']) OR ! empty($store['phone']) OR ! empty($store['email'])): ?>
		<div class="bg-white border border-stone-200 rounded-lg p-6 mt-6">
			<h2 class="font-semibold mb-3">Hubungi Kami</h2>
			<ul class="text-sm text-stone-700 space-y-1">
				<?php if ( ! empty($store['address'])): ?>
					<li><span class="text-stone-500">Alamat:</span> <?= esc($store['address']) ?></li>
				<?php endif; ?>
				<?php if ( ! empty($store['phone'])): ?>
					<li><span class="text-stone-500">Telepon:</span> <?= esc($store['phone']) ?></li>
				<?php endif; ?>
				<?php if ( ! empty($store['email'])): ?>
					<li><span class="text-stone-500">Email:</span> <?= esc($store['email']) ?></li>
				<?php endif; ?>
			</ul>

			<?php if ( ! empty($store['whatsapp_number'])): ?>
				<a href="https://wa.me/<?= esc(rawurlencode($store['whatsapp_number']), 'attr') ?>" target="_blank" rel="noopener noreferrer"
				   class="inline-block mt-4 bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-green-700">
					Chat via WhatsApp
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
