<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav class="ds-caption mb-6 text-ds-ink-soft" aria-label="Remah roti">
	<a href="<?= site_url() ?>" class="underline decoration-ds-line underline-offset-4 transition-colors hover:text-ds-ink">Beranda</a>
	<span aria-hidden="true"> / </span>
	<a href="<?= site_url('products') ?>" class="underline decoration-ds-line underline-offset-4 transition-colors hover:text-ds-ink">Produk</a>
	<span aria-hidden="true"> / </span>
	<a href="<?= site_url('products?kategori='.$product['category_slug']) ?>" class="underline decoration-ds-line underline-offset-4 transition-colors hover:text-ds-ink"><?= esc((string) $product['category_name']) ?></a>
	<span aria-hidden="true"> / </span>
	<span><?= esc($product['name']) ?></span>
</nav>

<div class="grid gap-8 md:grid-cols-2 md:gap-10">
	<div>
		<figure class="m-0 overflow-hidden rounded-lg border border-ds-line bg-ds-surface">
			<?php if ( ! empty($product['image_url'])): ?>
				<img src="<?= esc(img_src($product['image_url']), 'attr') ?>" alt="<?= esc($product['name']) ?>"
				     class="aspect-[4/3] h-full w-full object-cover"
				     onerror="this.onerror=null;this.src='https://placehold.co/800x600/f5f5f4/78716c?text=Gambar+tidak+tersedia'">
			<?php else: ?>
				<div class="flex aspect-[4/3] items-center justify-center text-ds-ink-soft">
					Tanpa gambar
				</div>
			<?php endif; ?>
		</figure>
	</div>

	<div>
		<p class="ds-label mb-2 text-ds-ink-soft"><?= esc((string) $product['category_name']) ?></p>
		<h1 class="ds-display-md mb-4"><?= esc($product['name']) ?></h1>

		<?php if ( ! empty($product['description'])): ?>
			<div class="ds-body mb-6 max-w-[60ch] text-ds-ink-soft"><?= nl2br(esc($product['description'])) ?></div>
		<?php endif; ?>

		<?php if ( ! $product['variants']): ?>
			<div class="ds-alert ds-alert--info">
				<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>
				</svg>
				<span>Varian produk ini belum tersedia. Silakan cek kembali nanti.</span>
			</div>
		<?php else: ?>
			<?php
			// Pilih varian pertama yang masih ada stok sebagai pilihan awal.
			// Varian pertama bisa saja habis, jadi tidak boleh bergantung pada index 0.
			$firstInStock = NULL;
			$hasStock = FALSE;
			foreach ($product['variants'] as $variant)
			{
				if ((int) $variant['stock'] > 0)
				{
					$hasStock = TRUE;
					if ($firstInStock === NULL)
					{
						$firstInStock = (int) $variant['id'];
					}
				}
			}
			?>

			<form action="<?= site_url('cart/add') ?>" method="post" id="addToCartForm" class="border-t border-ds-line pt-6">
				<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
				<?= form_hidden('product_id', (int) $product['id']) ?>

				<p class="ds-label mb-3 text-ds-ink-soft">Pilih ukuran</p>

				<?php // Pilihan ukuran + harga. Varian habis tidak bisa dipilih. ?>
				<div class="mb-6 space-y-2">
					<?php foreach ($product['variants'] as $variant): ?>
						<?php $inStock = (int) $variant['stock'] > 0; ?>
						<label class="flex items-center justify-between gap-3 rounded-md border px-4 py-3 text-sm transition-colors
							<?= $inStock
								? 'cursor-pointer border-ds-line bg-ds-surface hover:border-ds-ink has-[:checked]:border-ds-ink has-[:checked]:bg-ds-surface-alt'
								: 'cursor-not-allowed border-ds-line bg-ds-surface-alt text-ds-ink-soft' ?>">
							<span class="flex items-center gap-3">
								<input type="radio" name="variant_id" value="<?= (int) $variant['id'] ?>"
								       data-price="<?= (int) $variant['price'] ?>"
								       data-stock="<?= (int) $variant['stock'] ?>"
								       data-label="<?= esc($variant['label'], 'attr') ?>"
								       <?= $inStock ? 'required' : 'disabled' ?>
								       <?= ($inStock AND (int) $variant['id'] === $firstInStock) ? 'checked' : '' ?>
								       class="ds-check">
								<span class="font-bold uppercase"><?= esc($variant['label']) ?></span>
								<?php if ( ! $inStock): ?>
									<span class="ds-badge ds-badge--muted">habis</span>
								<?php elseif ((int) $variant['stock'] <= 5): ?>
									<span class="ds-badge ds-badge--warn">sisa <?= (int) $variant['stock'] ?></span>
								<?php endif; ?>
							</span>
							<span class="font-bold"><?= rupiah($variant['price']) ?></span>
						</label>
					<?php endforeach; ?>
				</div>

				<div class="mb-6 flex flex-wrap items-end gap-4">
					<div>
						<span class="ds-label-text">Jumlah</span>
						<div class="mt-1 flex items-center gap-2">
							<button type="button" id="qtyMinus" class="ds-btn ds-btn--ghost ds-btn--sm !h-10 !w-10 !px-0" aria-label="Kurangi jumlah">
								<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
									<path d="M5 12h14"/>
								</svg>
							</button>
							<input type="number" name="qty" id="qty" value="1" min="1" max="<?= (int) $maxQty ?>" readonly
							       class="ds-input w-16 text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none">
							<button type="button" id="qtyPlus" class="ds-btn ds-btn--ghost ds-btn--sm !h-10 !w-10 !px-0" aria-label="Tambah jumlah">
								<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
									<path d="M12 5v14M5 12h14"/>
								</svg>
							</button>
						</div>
					</div>
					<p class="ds-caption pb-3 text-ds-ink-soft">
						Stok tersedia: <span id="stockInfo" class="font-bold text-ds-ink">-</span>
					</p>
				</div>

				<button type="submit" <?= $hasStock ? '' : 'disabled' ?>
				        class="ds-btn ds-btn--primary ds-btn--block sm:!w-auto sm:!px-6">
					<?= $hasStock ? 'Tambah ke Keranjang' : 'Stok Habis' ?>
				</button>
			</form>
		<?php endif; ?>
	</div>
</div>

<?php if ($related): ?>
	<section class="mt-14 md:mt-20">
		<h2 class="ds-display-md mb-6 md:mb-8">Produk lainnya di kategori ini</h2>
		<div class="grid grid-cols-1 gap-3 md:grid-cols-2 md:gap-4 lg:grid-cols-4 lg:gap-6">
			<?php foreach ($related as $item): ?>
				<?php $this->load->view('products/_card', array('product' => $item)); ?>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<script>
// Info stok mengikuti varian yang dipilih (harga tidak dihitung di sini;
// perhitungan akhir tetap dilakukan di server saat checkout).
(function () {
	var form = document.getElementById('addToCartForm');
	if (!form) return;

	var stockInfo = document.getElementById('stockInfo');
	var qtyInput = document.getElementById('qty');
	var qtyMinus = document.getElementById('qtyMinus');
	var qtyPlus = document.getElementById('qtyPlus');
	var radios = form.querySelectorAll('input[name="variant_id"]');
	var maxQtyGlobal = <?= (int) $maxQty ?>;

	function getMaxQty() {
		var checked = form.querySelector('input[name="variant_id"]:checked');
		var stock = checked ? (parseInt(checked.dataset.stock, 10) || 0) : 0;
		if (stock <= 0) {
			return 0;
		}
		return Math.max(1, Math.min(stock, maxQtyGlobal));
	}

	function sync() {
		var checked = form.querySelector('input[name="variant_id"]:checked');
		if (!checked) {
			stockInfo.textContent = '-';
			qtyInput.max = maxQtyGlobal;
			qtyMinus.disabled = true;
			qtyPlus.disabled = true;
			return;
		}
		var stock = parseInt(checked.dataset.stock, 10) || 0;
		stockInfo.textContent = stock + ' pcs';
		var maxQty = getMaxQty();
		qtyInput.max = maxQty;
		if (maxQty <= 0) {
			qtyInput.value = 0;
			qtyMinus.disabled = true;
			qtyPlus.disabled = true;
			return;
		}
		var val = parseInt(qtyInput.value, 10) || 1;
		if (val < 1) val = 1;
		if (val > maxQty) val = maxQty;
		qtyInput.value = val;
		qtyMinus.disabled = (val <= 1);
		qtyPlus.disabled = (val >= maxQty);
	}

	if (qtyMinus) {
		qtyMinus.addEventListener('click', function () {
			var val = parseInt(qtyInput.value, 10) || 1;
			if (val > 1) {
				qtyInput.value = val - 1;
			}
			sync();
		});
	}

	if (qtyPlus) {
		qtyPlus.addEventListener('click', function () {
			var val = parseInt(qtyInput.value, 10) || 1;
			var maxQty = getMaxQty();
			if (maxQty > 0 && val < maxQty) {
				qtyInput.value = val + 1;
			}
			sync();
		});
	}

	radios.forEach(function (radio) { radio.addEventListener('change', sync); });
	sync();
})();
</script>
