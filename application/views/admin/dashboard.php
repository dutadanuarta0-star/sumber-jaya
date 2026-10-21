<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="text-xl font-bold mb-1">Dashboard</h1>
<p class="text-sm text-stone-500 mb-6">Ringkasan aktivitas toko dan produk.</p>

<?php // --- Statistik pengunjung unik ------------------------------------- ?>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<p class="text-sm text-stone-500">Pengunjung hari ini</p>
		<p class="text-3xl font-bold mt-1"><?= (int) $visitorsToday ?></p>
	</div>
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<p class="text-sm text-stone-500">Pengunjung 7 hari terakhir</p>
		<p class="text-3xl font-bold mt-1"><?= (int) $visitors7 ?></p>
	</div>
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<p class="text-sm text-stone-500">Pengunjung 30 hari terakhir</p>
		<p class="text-3xl font-bold mt-1"><?= (int) $visitors30 ?></p>
	</div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
	<?php // --- Chart pengunjung unik per hari (7 hari terakhir) --------- ?>
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<h2 class="font-semibold mb-4">Pengunjung unik 7 hari terakhir</h2>

		<?php
		// Skala chart: bar tertinggi = 85% tinggi area agar label angka
		// di atas bar tetap muat; hari tanpa kunjungan dapat stub kecil.
		$maxVisits = 0;
		$totalVisits = 0;
		foreach ($dailyVisitors as $day)
		{
			$maxVisits = max($maxVisits, (int) $day['visitors']);
			$totalVisits += (int) $day['visitors'];
		}
		$today = date('Y-m-d');
		?>

		<?php if ($totalVisits === 0): ?>
			<p class="text-sm text-stone-500">Belum ada data kunjungan.</p>
		<?php else: ?>
			<div class="flex items-end gap-2 h-40" role="img" aria-label="Chart pengunjung unik 7 hari terakhir">
				<?php foreach ($dailyVisitors as $day): ?>
					<?php
					$isToday = ($day['day'] === $today);
					$height  = max(2, (int) round((int) $day['visitors'] / $maxVisits * 85));
					?>
					<div class="flex-1 h-full flex flex-col justify-end items-center"
					     title="<?= esc($day['label'].' — '.$day['visitors'].' pengunjung') ?>">
						<span class="text-xs mb-1 <?= $isToday ? 'font-semibold' : 'text-stone-500' ?>">
							<?= (int) $day['visitors'] ?>
						</span>
						<div class="w-full max-w-10 rounded-t <?= $isToday ? 'bg-amber-800' : 'bg-amber-300' ?>"
						     style="height: <?= $height ?>%"></div>
					</div>
				<?php endforeach ?>
			</div>
			<div class="flex gap-2 mt-2 border-t border-stone-100 pt-2">
				<?php foreach ($dailyVisitors as $day): ?>
					<span class="flex-1 text-center text-[11px] <?= $day['day'] === $today ? 'font-semibold text-stone-800' : 'text-stone-500' ?>">
						<?= $day['day'] === $today ? 'Hari ini' : esc($day['label']) ?>
					</span>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</div>

	<?php // --- Produk paling sering dilihat --------------------------- ?>
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<h2 class="font-semibold mb-4">Produk paling sering dilihat</h2>

		<?php if ( ! $topProducts): ?>
			<p class="text-sm text-stone-500">Belum ada data produk yang dilihat.</p>
		<?php else: ?>
			<table class="w-full text-sm">
				<thead>
					<tr class="text-left text-stone-500 border-b border-stone-200">
						<th class="py-2 font-medium">Produk</th>
						<th class="py-2 font-medium text-right">Dilihat</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($topProducts as $product): ?>
						<tr class="border-b border-stone-100 last:border-b-0">
							<td class="py-2">
								<a href="<?= site_url('products/detail/'.$product['id']) ?>" target="_blank" rel="noopener noreferrer" class="hover:underline">
									<?= esc($product['name']) ?>
								</a>
							</td>
							<td class="py-2 text-right font-medium"><?= (int) $product['view_count'] ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>

	<?php // --- Ringkasan katalog --------------------------------------- ?>
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<h2 class="font-semibold mb-4">Ringkasan katalog</h2>
		<div class="flex gap-8 text-sm">
			<div>
				<p class="text-stone-500">Total produk</p>
				<p class="text-2xl font-bold"><?= (int) $totalProducts ?></p>
			</div>
			<div>
				<p class="text-stone-500">Total kategori</p>
				<p class="text-2xl font-bold"><?= (int) $totalCategories ?></p>
			</div>
		</div>
	</div>

	<?php // --- Stok menipis ------------------------------------------- ?>
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<h2 class="font-semibold mb-4">Stok menipis (&le; 5)</h2>

		<?php if ( ! $lowStock): ?>
			<p class="text-sm text-stone-500">Semua varian stoknya masih aman.</p>
		<?php else: ?>
			<ul class="text-sm divide-y divide-stone-100">
				<?php foreach ($lowStock as $variant): ?>
					<li class="py-2 flex justify-between gap-4">
						<span>
							<a href="<?= site_url('admin/products/edit/'.$variant['product_id']) ?>" class="hover:underline">
								<?= esc($variant['product_name']) ?>
							</a>
							<span class="text-stone-500">— <?= esc($variant['label']) ?></span>
						</span>
						<span class="<?= (int) $variant['stock'] <= 0 ? 'text-red-600 font-medium' : 'text-amber-700 font-medium' ?>">
							<?= (int) $variant['stock'] ?> pcs
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
