<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="ds-display-lg mb-8">Keranjang Belanja</h1>

<?php if ( ! $items): ?>
	<div class="ds-panel py-16 text-center">
		<p class="ds-body mb-6 text-ds-ink-soft">Keranjang Anda masih kosong.</p>
		<a href="<?= site_url('products') ?>" class="ds-btn ds-btn--primary">
			Mulai Belanja
		</a>
	</div>
<?php else: ?>
	<div class="grid gap-6 lg:grid-cols-3 lg:gap-8">
		<div class="space-y-4 lg:col-span-2">
			<?php foreach ($items as $item): ?>
				<div class="ds-panel ds-panel--tight">
					<div class="flex flex-wrap justify-between gap-4">
						<div class="min-w-0">
							<a href="<?= site_url('products/detail/'.$item['productId']) ?>" class="ds-title-sm block transition-colors hover:text-ds-forest">
								<?= esc($item['productName']) ?>
							</a>
							<p class="ds-caption mt-1 text-ds-ink-soft">
								Ukuran <?= esc($item['variantLabel']) ?> &middot; <?= rupiah($item['price']) ?> / pcs
							</p>
							<?php if ($item['note'] !== ''): ?>
								<p class="ds-caption mt-1 text-ds-forest"><?= esc($item['note']) ?></p>
							<?php endif; ?>
						</div>
						<p class="ds-title-sm whitespace-nowrap"><?= rupiah($item['lineTotal']) ?></p>
					</div>

					<div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-ds-line pt-4">
						<form action="<?= site_url('cart/update') ?>" method="post" class="flex flex-wrap items-end gap-2">
							<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
							<?= form_hidden('variant_id', $item['variantId']) ?>
							<div>
								<label for="qty-<?= (int) $item['variantId'] ?>" class="ds-label-text">Jumlah</label>
								<input type="number" id="qty-<?= (int) $item['variantId'] ?>" name="qty" value="<?= (int) $item['qty'] ?>"
								       min="0" max="<?= min((int) $item['stock'], (int) $maxQty) ?>"
								       class="ds-input ds-input--sm w-24">
							</div>
							<button type="submit" class="ds-btn ds-btn--ghost ds-btn--sm">Perbarui</button>
							<span class="ds-caption pb-2 text-ds-ink-soft">stok: <?= (int) $item['stock'] ?></span>
						</form>

						<form action="<?= site_url('cart/remove') ?>" method="post">
							<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
							<?= form_hidden('variant_id', $item['variantId']) ?>
							<button type="submit" class="ds-btn ds-btn--danger ds-btn--sm">Hapus</button>
						</form>
					</div>
				</div>
			<?php endforeach; ?>

			<form action="<?= site_url('cart/clear') ?>" method="post">
				<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
				<button type="submit" class="ds-btn ds-btn--ghost ds-btn--sm">Kosongkan keranjang</button>
			</form>
		</div>

		<div class="lg:col-span-1">
			<div class="ds-panel sticky top-24">
				<h2 class="ds-panel-title mb-4">Ringkasan Pesanan</h2>

				<div class="mb-2 flex items-center justify-between">
					<span class="ds-body text-ds-ink-soft">Subtotal</span>
					<span class="ds-title-sm"><?= rupiah($subtotal) ?></span>
				</div>
				<p class="ds-caption mb-6 text-ds-ink-soft">
					Ongkos kirim dan total akhir dikonfirmasi oleh admin lewat WhatsApp.
				</p>

				<?php if ($checkoutOk): ?>
					<form action="<?= site_url('cart/checkout') ?>" method="post">
						<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
						<button type="submit" class="ds-btn ds-btn--primary ds-btn--block">
							Checkout via WhatsApp
						</button>
					</form>
				<?php else: ?>
					<button type="button" disabled class="ds-btn ds-btn--primary ds-btn--block">
						Checkout via WhatsApp
					</button>
					<p class="ds-caption mt-3 text-ds-ink-soft">Tidak ada item yang tersedia untuk dipesan saat ini.</p>
				<?php endif; ?>

				<a href="<?= site_url('products') ?>" class="ds-btn ds-btn--ghost ds-btn--block mt-3">
					Lanjut belanja
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>
