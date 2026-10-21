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
	<?php // --- Pengunjung unik per hari (7 hari) ------------------------ ?>
	<div class="bg-white border border-stone-200 rounded-lg p-5">
		<h2 class="font-semibold mb-4">Pengunjung unik per hari</h2>

		<?php if ( ! $dailyVisitors): ?>
			<p class="text-sm text-stone-500">Belum ada data kunjungan.</p>
		<?php else: ?>
			<table class="w-full text-sm">
				<thead>
					<tr class="text-left text-stone-500 border-b border-stone-200">
						<th class="py-2 font-medium">Tanggal</th>
						<th class="py-2 font-medium text-right">Pengunjung</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($dailyVisitors as $day): ?>
						<tr class="border-b border-stone-100 last:border-b-0">
							<td class="py-2"><?= esc(date('d M Y', strtotime($day['day']))) ?></td>
							<td class="py-2 text-right font-medium"><?= (int) $day['visitors'] ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
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
