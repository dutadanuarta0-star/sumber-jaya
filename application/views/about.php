<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="max-w-3xl">
	<p class="ds-label mb-3 text-ds-ink-soft">Tentang toko</p>
	<h1 class="ds-display-lg mb-8">Tentang <?= esc($store['store_name']) ?></h1>

	<?php if ( ! empty($store['about_text'])): ?>
		<div class="ds-panel ds-body mb-6 max-w-[60ch] text-ds-ink-soft">
			<?= nl2br(esc($store['about_text'])) ?>
		</div>
	<?php else: ?>
		<div class="ds-panel mb-6 text-ds-ink-soft">Deskripsi toko belum diisi oleh admin.</div>
	<?php endif; ?>

	<?php if ( ! empty($store['address']) OR ! empty($store['phone']) OR ! empty($store['email'])): ?>
		<div class="ds-panel">
			<h2 class="ds-panel-title mb-4">Hubungi Kami</h2>
			<ul class="ds-body space-y-1 text-ds-ink-soft">
				<?php if ( ! empty($store['address'])): ?>
					<li><span class="text-ds-ink">Alamat:</span> <?= esc($store['address']) ?></li>
				<?php endif; ?>
				<?php if ( ! empty($store['phone'])): ?>
					<li><span class="text-ds-ink">Telepon:</span> <?= esc($store['phone']) ?></li>
				<?php endif; ?>
				<?php if ( ! empty($store['email'])): ?>
					<li><span class="text-ds-ink">Email:</span> <?= esc($store['email']) ?></li>
				<?php endif; ?>
			</ul>

			<?php if ( ! empty($store['whatsapp_number'])): ?>
				<a href="https://wa.me/<?= esc(rawurlencode($store['whatsapp_number']), 'attr') ?>" target="_blank" rel="noopener noreferrer"
				   class="ds-btn ds-btn--primary mt-6">
					Chat via WhatsApp
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
