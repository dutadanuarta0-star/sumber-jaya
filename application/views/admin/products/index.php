<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="flex items-center justify-between mb-5">
	<h1 class="text-xl font-bold">Produk</h1>
	<a href="<?= site_url('admin/products/create') ?>" class="bg-amber-800 text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-amber-900">
		+ Tambah Produk
	</a>
</div>

<?php if ( ! $products): ?>
	<div class="bg-white border border-stone-200 rounded-lg p-8 text-center text-sm text-stone-500">
		Belum ada produk. Klik "Tambah Produk" untuk mulai.
	</div>
<?php else: ?>
	<div class="bg-white border border-stone-200 rounded-lg overflow-x-auto">
		<table class="w-full text-sm">
			<thead class="bg-stone-50 text-stone-500 text-left">
				<tr>
					<th class="px-4 py-3 font-medium">Produk</th>
					<th class="px-4 py-3 font-medium">Kategori</th>
					<th class="px-4 py-3 font-medium text-right">Varian</th>
					<th class="px-4 py-3 font-medium text-right">Total Stok</th>
					<th class="px-4 py-3 font-medium">Status</th>
					<th class="px-4 py-3 font-medium text-right">Aksi</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-stone-100">
				<?php foreach ($products as $product): ?>
					<tr class="hover:bg-stone-50">
						<td class="px-4 py-3">
							<p class="font-medium"><?= esc($product['name']) ?></p>
							<p class="text-xs text-stone-400">/<?= esc($product['slug']) ?></p>
						</td>
						<td class="px-4 py-3"><?= esc((string) $product['category_name']) ?></td>
						<td class="px-4 py-3 text-right"><?= (int) $product['variant_count'] ?></td>
						<td class="px-4 py-3 text-right"><?= (int) $product['total_stock'] ?></td>
						<td class="px-4 py-3">
							<?php if ((int) $product['is_active'] === 1): ?>
								<span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">Aktif</span>
							<?php else: ?>
								<span class="text-xs px-2 py-1 rounded bg-stone-200 text-stone-600">Nonaktif</span>
							<?php endif; ?>
						</td>
						<td class="px-4 py-3">
							<div class="flex items-center justify-end gap-3">
								<a href="<?= site_url('admin/products/edit/'.$product['id']) ?>" class="text-amber-800 hover:underline">Edit</a>

								<form action="<?= site_url('admin/products/delete') ?>" method="post"
								      onsubmit="return confirm('Hapus produk &quot;<?= esc($product['name'], 'attr') ?>&quot; beserta semua variannya?');">
									<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
									<?= form_hidden('id', (int) $product['id']) ?>
									<button type="submit" class="text-red-600 hover:underline">Hapus</button>
								</form>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
