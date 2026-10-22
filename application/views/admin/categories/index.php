<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
	<h1 class="ds-display-md">Kategori</h1>
	<a href="<?= site_url('admin/categories/create') ?>" class="ds-btn ds-btn--primary">
		+ Tambah Kategori
	</a>
</div>

<?php if ( ! $categories): ?>
	<div class="ds-panel py-12 text-center text-ds-ink-soft">
		Belum ada kategori. Klik "Tambah Kategori" untuk mulai.
	</div>
<?php else: ?>
	<div class="ds-table-wrap">
		<div class="ds-table-scroll">
			<table class="ds-table ds-table--sticky">
				<thead>
					<tr>
						<th>Nama</th>
						<th>Gambar</th>
						<th>Slug</th>
						<th>Deskripsi</th>
						<th class="num">Produk</th>
						<th class="num">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($categories as $category): ?>
						<tr>
							<td class="font-bold"><?= esc($category['name']) ?></td>
							<td>
								<?php if ( ! empty($category['image_url'])): ?>
									<img src="<?= esc(img_src($category['image_url']), 'attr') ?>"
									     alt="Gambar kategori <?= esc($category['name'], 'attr') ?>"
									     class="h-12 w-12 rounded-md border border-ds-line object-cover">
								<?php else: ?>
									<span class="ds-caption text-ds-ink-soft">—</span>
								<?php endif; ?>
							</td>
							<td class="text-ds-ink-soft"><?= esc($category['slug']) ?></td>
							<td class="text-ds-ink-soft"><?= esc((string) $category['description']) ?></td>
							<td class="num"><?= (int) $category['product_count'] ?></td>
							<td>
								<div class="flex items-center justify-end gap-2">
									<a href="<?= site_url('admin/categories/edit/'.$category['id']) ?>" class="ds-btn ds-btn--ghost ds-btn--sm">Edit</a>

									<form action="<?= site_url('admin/categories/delete') ?>" method="post"
									      onsubmit="return confirm('Hapus kategori &quot;<?= esc($category['name'], 'attr') ?>&quot;?');">
										<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
										<?= form_hidden('id', (int) $category['id']) ?>
										<button type="submit" class="ds-btn ds-btn--danger ds-btn--sm">Hapus</button>
									</form>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>
