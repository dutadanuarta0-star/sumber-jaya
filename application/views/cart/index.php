<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

<?php if ( ! $items): ?>
	<div class="bg-white border border-stone-200 rounded-lg p-10 text-center">
		<p class="text-stone-600 mb-4">Keranjang Anda masih kosong.</p>
		<a href="<?= site_url('products') ?>" class="inline-block bg-amber-800 text-white text-sm font-semibold px-5 py-2.5 rounded-md hover:bg-amber-900">
			Mulai Belanja
		</a>
	</div>
<?php else: ?>
	<div class="grid gap-6 lg:grid-cols-3">
		<div class="lg:col-span-2 space-y-4">
			<?php foreach ($items as $item): ?>
				<div class="bg-white border border-stone-200 rounded-lg p-4">
					<div class="flex justify-between gap-4">
						<div>
							<a href="<?= site_url('products/detail/'.$item['productId']) ?>" class="font-medium hover:text-amber-800">
								<?= esc($item['productName']) ?>
							</a>
							<p class="text-sm text-stone-500 mt-0.5">
								Ukuran <?= esc($item['variantLabel']) ?> &middot; <?= rupiah($item['price']) ?> / pcs
							</p>
							<?php if ($item['note'] !== ''): ?>
								<p class="text-xs text-amber-800 mt-1"><?= esc($item['note']) ?></p>
							<?php endif; ?>
						</div>
						<p class="font-semibold whitespace-nowrap"><?= rupiah($item['lineTotal']) ?></p>
					</div>

					<div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-4 border-t border-stone-100">
						<form action="<?= site_url('cart/update') ?>" method="post" class="flex items-end gap-2">
							<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
							<?= form_hidden('variant_id', $item['variantId']) ?>
							<div>
								<label for="qty-<?= (int) $item['variantId'] ?>" class="block text-xs text-stone-500 mb-1">Jumlah</label>
								<input type="number" id="qty-<?= (int) $item['variantId'] ?>" name="qty" value="<?= (int) $item['qty'] ?>"
								       min="0" max="<?= min((int) $item['stock'], (int) $maxQty) ?>"
								       class="w-20 border border-stone-300 rounded-md px-2 py-1.5 text-sm">
							</div>
							<button type="submit" class="text-sm px-3 py-1.5 rounded-md border border-stone-300 hover:border-amber-700">
								Perbarui
							</button>
							<span class="text-xs text-stone-500 pb-2">stok: <?= (int) $item['stock'] ?></span>
						</form>

						<form action="<?= site_url('cart/remove') ?>" method="post">
							<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
							<?= form_hidden('variant_id', $item['variantId']) ?>
							<button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
						</form>
					</div>
				</div>
			<?php endforeach; ?>

			<form action="<?= site_url('cart/clear') ?>" method="post">
				<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
				<button type="submit" class="text-sm text-stone-500 hover:text-red-600 hover:underline">Kosongkan keranjang</button>
			</form>
		</div>

		<div class="lg:col-span-1">
			<div class="bg-white border border-stone-200 rounded-lg p-5 sticky top-24">
				<h2 class="font-semibold mb-4">Ringkasan Pesanan</h2>

				<div class="flex justify-between text-sm mb-2">
					<span class="text-stone-600">Subtotal</span>
					<span class="font-semibold"><?= rupiah($subtotal) ?></span>
				</div>
				<p class="text-xs text-stone-500 mb-5">
					Ongkos kirim dan total akhir dikonfirmasi oleh admin lewat WhatsApp.
				</p>

				<?php if ($checkoutOk): ?>
					<form action="<?= site_url('cart/checkout') ?>" method="post">
						<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
						<button type="submit" class="w-full bg-green-600 text-white font-semibold px-4 py-2.5 rounded-md hover:bg-green-700">
							Checkout via WhatsApp
						</button>
					</form>
				<?php else: ?>
					<button type="button" disabled class="w-full bg-stone-300 text-stone-500 font-semibold px-4 py-2.5 rounded-md cursor-not-allowed">
						Checkout via WhatsApp
					</button>
					<p class="text-xs text-stone-500 mt-3">Tidak ada item yang tersedia untuk dipesan saat ini.</p>
				<?php endif; ?>

				<a href="<?= site_url('products') ?>" class="block text-center text-sm text-amber-800 hover:underline mt-4">
					Lanjut belanja
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>
