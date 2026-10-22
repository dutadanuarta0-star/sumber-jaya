# AGENTS.md

Panduan teknis proyek **Sumber Jaya** (toko online kopi) di `C:\xampp\htdocs\Sumber jaya`.
Stack: CodeIgniter 3.1.13 + Tailwind CSS (CDN) + MySQL/MariaDB. Repository git (history awal
"first commit" s.d.; ada perubahan lokal yang belum di-commit).

## Struktur proyek

```
application/
  config/        config.php, autoload.php, database.php, routes.php
  controllers/   Home.php, Products.php, About.php, Cart.php
    admin/       Login.php, Dashboard.php, Products.php, Categories.php, Slider.php, Settings.php
  core/          MY_Controller.php  (MY_Controller, Public_Controller, Admin_Controller)
  helpers/       app_helper.php     (esc(), rupiah(), img_src())
  libraries/     Cart_lib.php       (keranjang berbasis session), Image_upload.php (upload gambar)
  models/        Setting_model, Category_model, Product_model, PageView_model, Admin_model, Hero_slide_model
  views/         layout/, home.php, products/, about.php, cart/, admin/
system/          framework CI3 — SUDAH DI-PATCH (lihat bawah); jangan ditimpa rilis CI baru
assets/css/design-system.css   token + komponen design system (WAJIB dimuat di semua view)
assets/js/ds-tailwind-config.js konfigurasi token Tailwind (dimuat SETELAH script CDN Tailwind)
assets/js/ds-ui.js              perilaku drawer menu mobile + fokus
assets/img/produk/  folder gambar produk (diisi manual, lihat konvensi "Gambar produk")
assets/img/slider/  folder gambar latar slider beranda (diisi manual, aturan sama)
tools/verify.js   harness verifikasi tampilan (Chrome DevTools Protocol) — alat bantu, bukan aplikasi
database.sql     skema + seed; membuat database `sumber_jaya` sendiri saat di-import
design-system.md SPESIFIKASI DESIGN SYSTEM — sumber kebenaran visual UI
TASKS.md         daftar tugas
```

Sisa berkas di root bukan bagian aplikasi, aman diabaikan/dihapus: `diag.php` (harness debug router),
`c2.txt`/`cj.txt`/`rl.txt` (cookie jar curl), `cart.html`/`rl.html` (halaman tersimpan),
`controllers/Welcome.php` + `views/welcome_message.php` (template bawaan CI, tidak dirutekan).

## Menjalankan & menguji

- Lint PHP: `"C:/xampp/php/php.exe" -l <file>` — runtime terpasang **PHP 8.0.30** (XAMPP).
  TASKS.md menyebut PHP 8.2+, tapi yang terpasang 8.0: hindari sintaks 8.1+ (`readonly`, enum, first-class callable).
- Dev server cepat: `"C:/xampp/php/php.exe" -S 127.0.0.1:8899 -t .` lalu buka `http://127.0.0.1:8899`
  - URL bersih (`/products`, `/admin/login`) berfungsi tanpa router script.
  - `base_url` dikonfigurasi **eksplisit** (`http://localhost/Sumber%20jaya/`), jadi semua URL yang
    dihasilkan (redirect, action form) menunjuk ke Apache port 80 — bukan port 8899. `php -S` aman
    untuk melihat halaman; untuk alur login/checkout yang butuh redirect, pastikan Apache jalan.
- Apache/XAMPP: `http://localhost/Sumber%20jaya/` (perhatikan spasi pada nama folder).
- MySQL client: `C:/xampp/mysql/bin/mysql.exe` (user `root`, tanpa password, MariaDB).
- Import DB: `"C:/xampp/mysql/bin/mysql.exe" -u root < database.sql`
  (dump membuat + memilih database sendiri; jangan beri nama DB di CLI — gagal pada DB kosong).
- Tidak ada test otomatis; pengujian dilakukan manual. Tidak ada build/lint tool JS (Tailwind via CDN).
- Verifikasi tampilan (opsional, butuh Chrome + Node): `node tools/verify.js` — memeriksa overflow
  horizontal, font Anton/Space Mono, skala tipografi, radius, dan error konsol pada 6 viewport
  (360/390/768/1024/1280/1440) untuk halaman publik + admin. Set `ADMIN_USER`/`ADMIN_PASS`
  untuk ikut menguji halaman admin.
- `composer.json` = berkas bawaan framework CI3; tidak ada `vendor/` dan tidak dipakai — abaikan.
- Autoload (`config/autoload.php`): library `database`, `session`, `form_validation`; helper
  `url`, `form`, `security`, `text`, `app` — tidak perlu di-load ulang di controller.

## Konvensi penting (WAJIB diikuti)

- **MVC CI3**: Controller → Model → View. Gunakan Query Builder, jangan SQL mentah.
- **Penamaan**: Controller `PascalCase`, method/variabel `camelCase`, tabel/kolom `snake_case`.
- **Gaya kode**: indentasi tab, LF (lihat `.editorconfig`); ikuti gaya file sekitar.
- **`base_url` WAJIB eksplisit** (`config.php`): `http://localhost/Sumber%20jaya/`. Jangan dikosongkan
  (auto-detect memakai `SERVER_ADDR` yang bisa `[::1]`/`127.0.0.1`) — action form menarget host
  berbeda dari yang dibuka browser, cookie CSRF tidak terkirim, dan login gagal dengan
  "The action you have requested is not allowed". Akses dari perangkat lain: ganti `localhost` dengan IP mesin.
- **Gambar produk (upload dari komputer / path manual)**: field "Upload gambar" menerima
  JPG/PNG/WEBP maks 3 MB dan menyimpannya di `assets/img/produk/` dengan nama acak
  (`application/libraries/Image_upload.php`; folder gambar punya `.htaccess` yang memblokir
  eksekusi skrip). Field path/URL (`assets/img/produk/nama.jpg` atau `https://...`) tetap ada
  untuk gambar lama; bila keduanya diisi, **file upload yang dipakai**. View wajib memakai
  helper `img_src()` (path lokal → prefix `base_url`). File gambar tidak dihapus otomatis saat
  produk/slide dihapus (sengaja, agar tidak salah hapus).
  **Awas aturan `regex_match` di `admin/Products`**: cabang OR path lokal harus SEBELUM `|` —
  parser CI3 memecah rule pada `|` kecuali diikuti `]` tanpa `[` di antaranya; regex dengan
  `[` setelah `|` akan terpotong.
- **Gambar kategori** mengikuti pola yang sama dengan produk: kolom `categories.image_url`,
  upload dari `admin/Categories` ke `assets/img/kategori/`, field path/URL manual tetap ada, dan
  file upload menang bila keduanya diisi. Kartu kategori di beranda memakai `.ds-cat-card`
  (gambar kiri, teks kanan); bila `image_url` kosong/gagal dimuat, view menampilkan ikon cadangan
  (bukan gambar rusak).
- **Slider hero beranda dikelola dari admin** (`admin/Slider`, tabel `hero_slides`): jumlah slide =
  jumlah baris, gambar bisa di-upload dari komputer (folder `assets/img/slider/`) atau path/URL
  manual (aturan & validasi sama dengan gambar produk). `Home::index()` mengirim slide aktif urut
  `sort_order`; view `home.php` memakai fallback 1 slide bila tidak ada slide aktif. Arahkan
  tautan tombol ke path internal (`products`) atau URL `https://`, bukan `javascript:`.
- **Harga & stok HANYA ada di `product_variants`.** Setiap produk wajib punya minimal 1 varian.
- **Tidak ada tabel `orders`.** Checkout murni redirect ke `wa.me`; stok tidak berkurang otomatis
  dan dikelola manual oleh admin. Stok divalidasi saat add/update keranjang.
- **Escaping output**: semua output view memakai `esc($val)` / `esc($val, 'attr')` (dari `app_helper.php`).
  Helper ini kustom karena CI 3.1.13 tidak punya `esc()` (itu CI4).
- Format Rupiah: `rupiah($angka)`.
- Validasi sisi server memakai **CI3 Form Validation**; semua form menyertakan **CSRF** (`csrf_test_name`).
- Endpoint sensitif (login) punya rate limit session: 5 percobaan gagal / 15 menit.
- Cookie pelacak kunjungan: `sj_vid` (HttpOnly, 1 tahun) + tabel `page_views`.
  Kunjungan unik = `COUNT(DISTINCT visitor_id)`; produk terpopuler = hitung `page_views` dengan `product_id` tidak NULL.
- Hapus kategori ditolak bila masih punya produk (FK `ON DELETE CASCADE` — guard di
  `admin/Categories::delete()` mencegah mass-delete senyap).

## Design system (WAJIB diikuti)

`design-system.md` (root project) = **sumber kebenaran visual**. Semua token, skala tipografi,
radius, dan pola komponen berasal dari sana. Ringkasan implementasi:

- **Token warna** ada dua tempat yang harus sinkron: CSS variable di
  `assets/css/design-system.css` (`:root`) dan nama `ds-*` di `assets/js/ds-tailwind-config.js`
  (`bg-ds-page`, `text-ds-ink`, `border-ds-line`, `bg-ds-forest`, `text-ds-on-forest`, …).
  Mengubah satu berarti mengubah yang lain.
- **Urutan muat aset di `<head>`** (semua view):
  1. `<script src="https://cdn.tailwindcss.com"></script>`
  2. `<script src="assets/js/ds-tailwind-config.js"></script>` — WAJIB setelah CDN, kalau tidak
     token `ds-*`/breakpoint/radius tidak dikenal Tailwind.
  3. `<link rel="stylesheet" href="assets/css/design-system.css">` — WAJIB setelah CDN agar
     kelas komponen menang saat specificity seri.
  4. Font Google: `Anton` (display) + `Space Mono` (mono) dengan `preconnect` + `display=swap`.
- **Kelas komponen `.ds-*`** (jangan ganti dengan utility Tailwind yang bentrok): `.ds-btn`
  (+ `--primary/--ghost/--light/--danger/--sm/--block`), `.ds-input`, `.ds-textarea`, `.ds-select`,
  `.ds-file`, `.ds-label`, `.ds-field`, `.ds-chip`, `.ds-badge`, `.ds-card`, `.ds-panel`, `.ds-table`
  (+ `--sticky/--fluid`), `.ds-table-wrap`, `.ds-alert`, `.ds-product-card`, `.ds-cat-card`,
  `.ds-cta`, `.ds-feature`, `.ds-menu`, `.ds-drawer`, `.ds-icon-btn`, `.ds-display-*`.
  Prinsip: **jangan menumpuk utility Tailwind yang bertabrakan dengan komponen `.ds-*`** di elemen
  yang sama (mis. `rounded-lg` pada `.ds-card`) — pakai modifier/variannya.
- **Tipografi**: judul = Anton uppercase (`.ds-display-xl/lg/md`, `font-display`); body = Space Mono
  (`.ds-body`). Font CSS = Anton + Space Mono (bukan sans/mono sistem).
- **Breakpoint** (mobile-first, min-width): sm 600 · md 768 · lg 1024 · xl 1200 · 2xl 1536.
- **Radius**: sm 8 · md 16 · lg 24 · xl 32 · 2xl 40 · 3xl 48 · pill 9999.
- **Halaman admin** memakai tabel (bukan kartu) + `overflow-x: auto` + kolom pertama sticky
  (DS §7.5). Daftar produk publik memakai `.ds-product-card` dengan tautan "LIHAT PRODUK" (ghost) —
  tidak ada endpoint POST baru untuk add-to-cart.
- **Aksesibilitas**: fokus terlihat (`:focus-visible` ring), tombol WA/ikon punya `aria-label`,
  badge keranjang `aria-live="polite"`, drawer mobile mengembalikan fokus ke tombol pemicu saat ditutup
  dan bisa ditutup dengan Escape (`assets/js/ds-ui.js`).
- **Ganti gaya?** Tentukan dulu token di `design-system.md`, baru implementasikan di
  `design-system.css` + `ds-tailwind-config.js` + kelas `.ds-*`; jangan hardcode hex/px baru di view.

## Catatan khusus: folder mengandung spasi

Folder proyek bernama `Sumber jaya` (ada spasi). Apache mengirim `REQUEST_URI` ter-encode
(`/Sumber%20jaya/...`) sedangkan `SCRIPT_NAME` tidak ter-encode, sehingga routing bawaan CI3 meleset.
Solusinya **patch pada `system/core/URI.php`** (`_parse_project_prefix()`, preferensi PATH_INFO,
strip prefix `/index.php`). `system/core/Router.php` tetap bawaan — routing admin diselesaikan lewat
rute eksplisit di bawah. **Jangan ganti file `system/` dengan rilis CI baru tanpa menerapkan ulang patch ini.**

Rute admin eksplisit di `application/config/routes.php` (karena CI3 membaca segmen ke-2 sebagai
controller, bukan method dari controller di folder):

```php
$route['admin']                    = 'admin/dashboard';
$route['admin/logout']             = 'admin/login/logout';
$route['admin/account']            = 'admin/settings/account';
$route['admin/accountUpdate']      = 'admin/settings/accountUpdate';
$route['admin/products/edit/(:num)']   = 'admin/products/edit/$1';
$route['admin/categories/edit/(:num)'] = 'admin/categories/edit/$1';
$route['admin/slider/edit/(:num)']     = 'admin/slider/edit/$1';
```

## Kredensial & data placeholder (harus diganti oleh pemilik)

- Admin default: `admin` / `admin123` — **ganti setelah login pertama.**
- Nomor WhatsApp toko di `settings.whatsapp_number` masih placeholder `6281234567890`.

## Aturan perawatan dokumen

- `TASKS.md` = manajemen tugas. `AGENTS.md` = panduan teknis ini.
- `design-system.md` = spesifikasi design system (sumber kebenaran visual). Dokumen ini ikut
  dibawa ke dalam repo; bila berubah, perbarui `assets/css/design-system.css`,
  `assets/js/ds-tailwind-config.js`, dan kelas `.ds-*` di view.
- Jangan ubah `AGENTS.md` untuk perubahan biasa; perbarui hanya bila ada aturan/konvensi/
  struktur/arsitektur penting yang berubah.
