<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="flex items-center justify-between mb-5">
	<h1 class="text-xl font-bold">Kategori</h1>
	<a href="<?= site_url('admin/categories/create') ?>" class="bg-amber-800 text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-amber-900">
		+ Tambah Kategori
	</a>
</div>

<?php if ( ! $categories): ?>
	<div class="bg-white border border-stone-200 rounded-lg p-8 text-center text-sm text-stone-500">
		Belum ada kategori. Klik "Tambah Kategori" untuk mulai.
	</div>
<?php else: ?>
	<div class="bg-white border border-stone-200 rounded-lg overflow-x-auto">
		<table class="w-full text-sm">
			<thead class="bg-stone-50 text-stone-500 text-left">
				<tr>
					<th class="px-4 py-3 font-medium">Nama</th>
					<th class="px-4 py-3 font-medium">Slug</th>
					<th class="px-4 py-3 font-medium">Deskripsi</th>
					<th class="px-4 py-3 font-medium text-right">Produk</th>
					<th class="px-4 py-3 font-medium text-right">Aksi</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-stone-100">
				<?php foreach ($categories as $category): ?>
					<tr class="hover:bg-stone-50">
						<td class="px-4 py-3 font-medium"><?= esc($category['name']) ?></td>
						<td class="px-4 py-3 text-stone-500"><?= esc($category['slug']) ?></td>
						<td class="px-4 py-3 text-stone-600"><?= esc((string) $category['description']) ?></td>
						<td class="px-4 py-3 text-right"><?= (int) $category['product_count'] ?></td>
						<td class="px-4 py-3">
							<div class="flex items-center justify-end gap-3">
								<a href="<?= site_url('admin/categories/edit/'.$category['id']) ?>" class="text-amber-800 hover:underline">Edit</a>

								<form action="<?= site_url('admin/categories/delete') ?>" method="post"
								      onsubmit="return confirm('Hapus kategori &quot;<?= esc($category['name'], 'attr') ?>&quot;?');">
									<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
									<?= form_hidden('id', (int) $category['id']) ?>
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
