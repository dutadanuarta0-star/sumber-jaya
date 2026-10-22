<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php // Daftar slide hero beranda. Jumlah slide = jumlah baris di tabel ini. ?>

<div class="flex items-center justify-between mb-5">
	<h1 class="text-xl font-bold">Slider Beranda</h1>
	<a href="<?= site_url('admin/slider/create') ?>" class="bg-amber-800 text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-amber-900">
		+ Tambah Slide
	</a>
</div>

<p class="text-sm text-stone-500 mb-5">
	Jumlah slide di beranda mengikuti jumlah baris di bawah. Hapus baris untuk mengurangi slide,
	tambah baris untuk menambah slide. Slide yang tidak aktif tidak tampil di beranda.
</p>

<?php if ( ! $slides): ?>
	<div class="bg-white border border-stone-200 rounded-lg p-8 text-center text-sm text-stone-500">
		Belum ada slide. Beranda akan memakai tampilan bawaan sampai Anda menambahkan slide.
	</div>
<?php else: ?>
	<div class="bg-white border border-stone-200 rounded-lg overflow-x-auto">
		<table class="w-full text-sm">
			<thead class="bg-stone-50 text-stone-500 text-left">
				<tr>
					<th class="px-4 py-3 font-medium">Gambar</th>
					<th class="px-4 py-3 font-medium">Judul</th>
					<th class="px-4 py-3 font-medium">Tombol</th>
					<th class="px-4 py-3 font-medium text-right">Urutan</th>
					<th class="px-4 py-3 font-medium">Status</th>
					<th class="px-4 py-3 font-medium text-right">Aksi</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-stone-100">
				<?php foreach ($slides as $slide): ?>
					<tr class="hover:bg-stone-50">
						<td class="px-4 py-3">
							<?php if ( ! empty($slide['image_url'])): ?>
								<img src="<?= esc(img_src($slide['image_url']), 'attr') ?>" alt=""
								     class="h-10 w-16 object-cover rounded border border-stone-200">
							<?php else: ?>
								<span class="inline-block h-10 w-16 rounded border border-dashed border-stone-300 bg-amber-900"></span>
							<?php endif; ?>
						</td>
						<td class="px-4 py-3">
							<p class="font-medium"><?= esc($slide['title']) ?></p>
							<?php if ( ! empty($slide['label'])): ?>
								<p class="text-xs text-stone-500"><?= esc($slide['label']) ?></p>
							<?php endif; ?>
						</td>
						<td class="px-4 py-3 text-stone-600">
							<?php if ( ! empty($slide['cta_text'])): ?>
								<?= esc($slide['cta_text']) ?>
								<?php if ( ! empty($slide['cta_url'])): ?>
									<span class="text-xs text-stone-400">(<?= esc($slide['cta_url']) ?>)</span>
								<?php endif; ?>
							<?php else: ?>
								<span class="text-stone-400">&mdash;</span>
							<?php endif; ?>
						</td>
						<td class="px-4 py-3 text-right"><?= (int) $slide['sort_order'] ?></td>
						<td class="px-4 py-3">
							<?php if ((int) $slide['is_active'] === 1): ?>
								<span class="inline-block text-xs font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded">Aktif</span>
							<?php else: ?>
								<span class="inline-block text-xs font-medium bg-stone-100 text-stone-600 px-2 py-0.5 rounded">Nonaktif</span>
							<?php endif; ?>
						</td>
						<td class="px-4 py-3">
							<div class="flex items-center justify-end gap-3">
								<a href="<?= site_url('admin/slider/edit/'.$slide['id']) ?>" class="text-amber-800 hover:underline">Edit</a>

								<form action="<?= site_url('admin/slider/delete') ?>" method="post"
								      onsubmit="return confirm('Hapus slide &quot;<?= esc($slide['title'], 'attr') ?>&quot;?');">
									<?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
									<?= form_hidden('id', (int) $slide['id']) ?>
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
