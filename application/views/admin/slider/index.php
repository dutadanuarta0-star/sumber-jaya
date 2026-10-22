<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php // Daftar slide hero beranda. Jumlah slide = jumlah baris di tabel ini. ?>

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
	<h1 class="ds-display-md">Slider Beranda</h1>
	<a href="<?= site_url('admin/slider/create') ?>" class="ds-btn ds-btn--primary">
		+ Tambah Slide
	</a>
</div>

<p class="ds-body mb-6 max-w-[60ch] text-ds-ink-soft">
	Jumlah slide di beranda mengikuti jumlah baris di bawah. Hapus baris untuk mengurangi slide,
	tambah baris untuk menambah slide. Slide yang tidak aktif tidak tampil di beranda.
</p>

<?php if ( ! $slides): ?>
	<div class="ds-panel py-12 text-center text-ds-ink-soft">
		Belum ada slide. Beranda akan memakai tampilan bawaan sampai Anda menambahkan slide.
	</div>
<?php else: ?>
	<div class="ds-table-wrap">
		<div class="ds-table-scroll">
			<table class="ds-table ds-table--sticky">
				<thead>
					<tr>
						<th>Gambar</th>
						<th>Judul</th>
						<th>Tombol</th>
						<th class="num">Urutan</th>
						<th>Status</th>
						<th class="num">Aksi</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($slides as $slide): ?>
						<tr>
							<td>
								<?php if ( ! empty($slide['image_url'])): ?>
									<img src="<?= esc(img_src($slide['image_url']), 'attr') ?>" alt=""
									     class="h-10 w-16 rounded border border-ds-line object-cover">
								<?php else: ?>
									<span class="inline-block h-10 w-16 rounded border border-dashed border-ds-line bg-ds-forest"></span>
								<?php endif; ?>
							</td>
							<td>
								<p class="font-bold"><?= esc($slide['title']) ?></p>
								<?php if ( ! empty($slide['label'])): ?>
									<p class="ds-caption text-ds-ink-soft"><?= esc($slide['label']) ?></p>
								<?php endif; ?>
							</td>
							<td class="text-ds-ink-soft">
								<?php if ( ! empty($slide['cta_text'])): ?>
									<?= esc($slide['cta_text']) ?>
									<?php if ( ! empty($slide['cta_url'])): ?>
										<span class="ds-caption"><?= esc($slide['cta_url']) ?></span>
									<?php endif; ?>
								<?php else: ?>
									<span class="ds-caption">&mdash;</span>
								<?php endif; ?>
							</td>
							<td class="num"><?= (int) $slide['sort_order'] ?></td>
							<td>
								<?php if ((int) $slide['is_active'] === 1): ?>
									<span class="ds-badge ds-badge--ok">Aktif</span>
								<?php else: ?>
									<span class="ds-badge ds-badge--muted">Nonaktif</span>
								<?php endif; ?>
							</td>
							<td>
								<div class="flex items-center justify-end gap-2">
									<a href="<?= site_url('admin/slider/edit/'.$slide['id']) ?>" class="ds-btn ds-btn--ghost ds-btn--sm">Edit</a>

									<form action="<?= site_url('admin/slider/delete') ?>" method="post"
									      onsubmit="return confirm('Hapus slide &quot;<?= esc($slide['title'], 'attr') ?>&quot;?');">
										<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
										<?= form_hidden('id', (int) $slide['id']) ?>
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
