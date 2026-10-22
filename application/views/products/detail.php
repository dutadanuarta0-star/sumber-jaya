<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<nav class="text-xs text-stone-500 mb-4">
	<a href="<?= site_url() ?>" class="hover:underline">Beranda</a> /
	<a href="<?= site_url('products') ?>" class="hover:underline">Produk</a> /
	<a href="<?= site_url('products?kategori='.$product['category_slug']) ?>" class="hover:underline"><?= esc((string) $product['category_name']) ?></a> /
	<span><?= esc($product['name']) ?></span>
</nav>

<div class="grid gap-8 md:grid-cols-2">
	<div>
		<?php if ( ! empty($product['image_url'])): ?>
			<img src="<?= esc(img_src($product['image_url'])) ?>" alt="<?= esc($product['name']) ?>"
			     class="w-full h-72 object-cover rounded-lg bg-stone-100 border border-stone-200"
			     onerror="this.onerror=null;this.src='https://placehold.co/800x600/f5f5f4/78716c?text=Gambar+tidak+tersedia'">
		<?php else: ?>
			<div class="w-full h-72 rounded-lg bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-400">
				Tanpa gambar
			</div>
		<?php endif; ?>
	</div>

	<div>
		<p class="text-xs text-amber-800 mb-1"><?= esc((string) $product['category_name']) ?></p>
		<h1 class="text-2xl font-bold mb-3"><?= esc($product['name']) ?></h1>

		<?php if ( ! empty($product['description'])): ?>
			<p class="text-stone-600 text-sm leading-relaxed mb-6"><?= nl2br(esc($product['description'])) ?></p>
		<?php endif; ?>

		<?php if ( ! $product['variants']): ?>
			<div class="rounded-md bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 text-sm">
				Varian produk ini belum tersedia. Silakan cek kembali nanti.
			</div>
		<?php else: ?>
			<?php $hasStock = FALSE; ?>
			<?php foreach ($product['variants'] as $variant): ?>
				<?php if ((int) $variant['stock'] > 0) { $hasStock = TRUE; break; } ?>
			<?php endforeach; ?>

			<form action="<?= site_url('cart/add') ?>" method="post" id="addToCartForm" class="border-t border-stone-200 pt-6">
				<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
				<?= form_hidden('product_id', (int) $product['id']) ?>

				<p class="text-sm font-medium mb-2">Pilih ukuran</p>

				<?php // Pilihan ukuran + harga. Varian habis tidak bisa dipilih. ?>
				<div class="space-y-2 mb-5">
					<?php foreach ($product['variants'] as $index => $variant): ?>
						<?php $inStock = (int) $variant['stock'] > 0; ?>
						<label class="flex items-center justify-between gap-3 border rounded-md px-3 py-2.5 text-sm cursor-pointer
							<?= $inStock ? 'border-stone-300 hover:border-amber-700 has-[:checked]:border-amber-700 has-[:checked]:bg-amber-50' : 'border-stone-200 bg-stone-100 text-stone-400 cursor-not-allowed' ?>">
							<span class="flex items-center gap-3">
								<input type="radio" name="variant_id" value="<?= (int) $variant['id'] ?>"
								       data-price="<?= (int) $variant['price'] ?>"
								       data-stock="<?= (int) $variant['stock'] ?>"
								       data-label="<?= esc($variant['label'], 'attr') ?>"
								       <?= ( ! $inStock) ? 'disabled' : '' ?>
								       <?= ($inStock AND $index === 0) ? 'checked' : '' ?>
								       class="accent-amber-800">
								<span class="font-medium"><?= esc($variant['label']) ?></span>
								<?php if ( ! $inStock): ?>
									<span class="text-xs">(habis)</span>
								<?php elseif ((int) $variant['stock'] <= 5): ?>
									<span class="text-xs text-red-600">sisa <?= (int) $variant['stock'] ?></span>
								<?php endif; ?>
							</span>
							<span class="font-semibold"><?= rupiah($variant['price']) ?></span>
						</label>
					<?php endforeach; ?>
				</div>

				<div class="flex items-end gap-3 mb-6">
					<div>
						<label for="qty" class="block text-sm font-medium mb-2">Jumlah</label>
						<input type="number" name="qty" id="qty" value="1" min="1" max="<?= (int) $maxQty ?>"
						       class="w-24 border border-stone-300 rounded-md px-3 py-2 text-sm">
					</div>
					<div class="pb-2 text-sm text-stone-600">
						Stok tersedia: <span id="stockInfo">-</span>
					</div>
				</div>

				<button type="submit" <?= $hasStock ? '' : 'disabled' ?>
				        class="w-full sm:w-auto px-6 py-2.5 rounded-md font-semibold <?= $hasStock ? 'bg-amber-800 text-white hover:bg-amber-900' : 'bg-stone-300 text-stone-500 cursor-not-allowed' ?>">
					<?= $hasStock ? 'Tambah ke Keranjang' : 'Stok Habis' ?>
				</button>
			</form>
		<?php endif; ?>
	</div>
</div>

<?php if ($related): ?>
	<section class="mt-14">
		<h2 class="text-lg font-semibold mb-4">Produk lainnya di kategori ini</h2>
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
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
	var radios = form.querySelectorAll('input[name="variant_id"]');

	function sync() {
		var checked = form.querySelector('input[name="variant_id"]:checked');
		if (!checked) {
			stockInfo.textContent = '-';
			return;
		}
		var stock = parseInt(checked.dataset.stock, 10) || 0;
		stockInfo.textContent = stock + ' pcs';
		qtyInput.max = Math.max(1, Math.min(stock, <?= (int) $maxQty ?>));
		if (parseInt(qtyInput.value, 10) > parseInt(qtyInput.max, 10)) {
			qtyInput.value = qtyInput.max;
		}
	}

	radios.forEach(function (radio) { radio.addEventListener('change', sync); });
	sync();
})();
</script>
