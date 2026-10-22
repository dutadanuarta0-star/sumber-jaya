<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<h1 class="ds-display-md mb-1">Dashboard</h1>
<p class="ds-body mb-8 text-ds-ink-soft">Ringkasan aktivitas toko dan produk.</p>

<?php // --- Statistik pengunjung unik ------------------------------------- ?>
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
	<div class="ds-panel">
		<p class="ds-label mb-2 text-ds-ink-soft">Pengunjung hari ini</p>
		<p class="ds-display-md"><?= (int) $visitorsToday ?></p>
	</div>
	<div class="ds-panel">
		<p class="ds-label mb-2 text-ds-ink-soft">Pengunjung 7 hari terakhir</p>
		<p class="ds-display-md"><?= (int) $visitors7 ?></p>
	</div>
	<div class="ds-panel">
		<p class="ds-label mb-2 text-ds-ink-soft">Pengunjung 30 hari terakhir</p>
		<p class="ds-display-md"><?= (int) $visitors30 ?></p>
	</div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
	<?php // --- Chart pengunjung unik per hari (7 hari terakhir) --------- ?>
	<div class="ds-panel">
		<h2 class="ds-panel-title mb-4">Pengunjung unik 7 hari terakhir</h2>

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
			<p class="ds-body text-ds-ink-soft">Belum ada data kunjungan.</p>
		<?php else: ?>
			<div class="flex h-40 items-end gap-2" role="img" aria-label="Chart pengunjung unik 7 hari terakhir">
				<?php foreach ($dailyVisitors as $day): ?>
					<?php
					$isToday = ($day['day'] === $today);
					$height  = max(2, (int) round((int) $day['visitors'] / $maxVisits * 85));
					?>
					<div class="flex h-full flex-1 flex-col items-center justify-end"
					     title="<?= esc($day['label'].' — '.$day['visitors'].' pengunjung') ?>">
						<span class="ds-caption mb-1 <?= $isToday ? 'font-bold text-ds-ink' : 'text-ds-ink-soft' ?>">
							<?= (int) $day['visitors'] ?>
						</span>
						<div class="w-full max-w-10 rounded-t <?= $isToday ? 'bg-ds-forest' : 'bg-ds-surface-alt' ?>"
						     style="height: <?= $height ?>%"></div>
					</div>
				<?php endforeach ?>
			</div>
			<div class="mt-2 flex gap-2 border-t border-ds-line pt-2">
				<?php foreach ($dailyVisitors as $day): ?>
					<span class="ds-caption flex-1 text-center <?= $day['day'] === $today ? 'font-bold text-ds-ink' : 'text-ds-ink-soft' ?>">
						<?= $day['day'] === $today ? 'Hari ini' : esc($day['label']) ?>
					</span>
				<?php endforeach ?>
			</div>
		<?php endif; ?>
	</div>

	<?php // --- Produk paling sering dilihat --------------------------- ?>
	<div class="ds-panel">
		<h2 class="ds-panel-title mb-4">Produk paling sering dilihat</h2>

		<?php if ( ! $topProducts): ?>
			<p class="ds-body text-ds-ink-soft">Belum ada data produk yang dilihat.</p>
		<?php else: ?>
			<div class="ds-table-wrap">
				<div class="ds-table-scroll">
					<table class="ds-table">
						<thead>
							<tr>
								<th>Produk</th>
								<th class="num">Dilihat</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($topProducts as $product): ?>
								<tr>
									<td>
										<a href="<?= site_url('products/detail/'.$product['id']) ?>" target="_blank" rel="noopener noreferrer" class="transition-colors hover:text-ds-forest">
											<?= esc($product['name']) ?>
										</a>
									</td>
									<td class="num font-bold"><?= (int) $product['view_count'] ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php // --- Ringkasan katalog --------------------------------------- ?>
	<div class="ds-panel">
		<h2 class="ds-panel-title mb-4">Ringkasan katalog</h2>
		<div class="flex gap-10">
			<div>
				<p class="ds-caption text-ds-ink-soft">Total produk</p>
				<p class="ds-display-md"><?= (int) $totalProducts ?></p>
			</div>
			<div>
				<p class="ds-caption text-ds-ink-soft">Total kategori</p>
				<p class="ds-display-md"><?= (int) $totalCategories ?></p>
			</div>
		</div>
	</div>

	<?php // --- Stok menipis ------------------------------------------- ?>
	<div class="ds-panel">
		<h2 class="ds-panel-title mb-4">Stok menipis (&le; 5)</h2>

		<?php if ( ! $lowStock): ?>
			<p class="ds-body text-ds-ink-soft">Semua varian stoknya masih aman.</p>
		<?php else: ?>
			<ul class="ds-body divide-y divide-ds-line">
				<?php foreach ($lowStock as $variant): ?>
					<li class="flex justify-between gap-4 py-2">
						<span>
							<a href="<?= site_url('admin/products/edit/'.$variant['product_id']) ?>" class="transition-colors hover:text-ds-forest">
								<?= esc($variant['product_name']) ?>
							</a>
							<span class="text-ds-ink-soft">&mdash; <?= esc($variant['label']) ?></span>
						</span>
						<span class="<?= (int) $variant['stock'] <= 0 ? 'ds-badge ds-badge--danger' : 'ds-badge ds-badge--warn' ?>">
							<?= (int) $variant['stock'] ?> pcs
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
