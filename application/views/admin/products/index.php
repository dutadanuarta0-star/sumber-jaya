<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
	<h1 class="ds-display-md">Produk</h1>
	<a href="<?= site_url('admin/products/create') ?>" class="ds-btn ds-btn--primary">
		+ Tambah Produk
	</a>
</div>

<?php if ( ! $products): ?>
	<div class="ds-panel py-12 text-center text-ds-ink-soft">
		Belum ada produk. Klik "Tambah Produk" untuk mulai.
	</div>
<?php else: ?>
	<div class="ds-table-wrap">
		<div class="ds-table-scroll">
			<table class="ds-table ds-table--sticky">
				<thead>
					<tr>
						<th>Produk</th>
						<th>Kategori</th>
						<th class="num">Varian</th>
						<th class="num">Total Stok</th>
						<th>Status</th>
						<th class="num">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $product): ?>
						<tr>
							<td>
								<p class="font-bold"><?= esc($product['name']) ?></p>
								<p class="ds-caption text-ds-ink-soft">/<?= esc($product['slug']) ?></p>
							</td>
							<td><?= esc((string) $product['category_name']) ?></td>
							<td class="num"><?= (int) $product['variant_count'] ?></td>
							<td class="num"><?= (int) $product['total_stock'] ?></td>
							<td>
								<?php if ((int) $product['is_active'] === 1): ?>
									<span class="ds-badge ds-badge--ok">Aktif</span>
								<?php else: ?>
									<span class="ds-badge ds-badge--muted">Nonaktif</span>
								<?php endif; ?>
							</td>
							<td>
								<div class="flex items-center justify-end gap-2">
									<a href="<?= site_url('admin/products/edit/'.$product['id']) ?>" class="ds-btn ds-btn--ghost ds-btn--sm">Edit</a>

									<form action="<?= site_url('admin/products/delete') ?>" method="post"
									      onsubmit="return confirm('Hapus produk &quot;<?= esc($product['name'], 'attr') ?>&quot; beserta semua variannya?');">
										<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
										<?= form_hidden('id', (int) $product['id']) ?>
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
