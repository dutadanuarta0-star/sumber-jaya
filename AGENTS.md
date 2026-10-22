# AGENTS.md

Panduan teknis proyek **Sumber Jaya** (toko online kopi) di `C:\xampp\htdocs\Sumber jaya`.
Stack: CodeIgniter 3.1.13 + Tailwind CSS (CDN) + MySQL/MariaDB. Bukan git repo.

## Struktur proyek

```
application/
  config/        config.php, autoload.php, database.php, routes.php
  controllers/   Home.php, Products.php, About.php, Cart.php
    admin/       Login.php, Dashboard.php, Products.php, Categories.php, Slider.php, Settings.php
  core/          MY_Controller.php  (MY_Controller, Public_Controller, Admin_Controller)
  helpers/       app_helper.php     (esc(), rupiah(), img_src())
  libraries/     Cart_lib.php       (keranjang berbasis session)
  models/        Setting_model, Category_model, Product_model, PageView_model, Admin_model, Hero_slide_model
  views/         layout/, home.php, products/, about.php, cart/, admin/
system/          framework CI3 — SUDAH DI-PATCH (lihat bawah); jangan ditimpa rilis CI baru
assets/img/produk/  folder gambar produk (diisi manual, lihat konvensi "Gambar produk")
assets/img/slider/  folder gambar latar slider beranda (diisi manual, aturan sama)
database.sql     skema + seed; membuat database `sumber_jaya` sendiri saat di-import
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
- **Gambar produk (manual, tanpa upload)**: file ditaruh di `assets/img/produk/`, lalu admin mengisi
  path relatifnya (`assets/img/produk/nama.jpg`) di field Gambar — URL `https://...` juga diterima.
  View wajib memakai helper `img_src()` (path lokal → prefix `base_url`).
  **Awas aturan `regex_match` di `admin/Products`**: cabang OR path lokal harus SEBELUM `|` —
  parser CI3 memecah rule pada `|` kecuali diikuti `]` tanpa `[` di antaranya; regex dengan
  `[` setelah `|` akan terpotong.
- **Slider hero beranda dikelola dari admin** (`admin/Slider`, tabel `hero_slides`): jumlah slide =
  jumlah baris, gambar latar manual di `assets/img/slider/` (aturan & validasi `regex_match` sama
  dengan gambar produk). `Home::index()` mengirim slide aktif urut `sort_order`; view
  `home.php` memakai fallback 1 slide bila tidak ada slide aktif. Arahkan tautan tombol ke path
  internal (`products`) atau URL `https://`, bukan `javascript:`.
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
- Jangan ubah `AGENTS.md` untuk perubahan biasa; perbarui hanya bila ada aturan/konvensi/
  struktur/arsitektur penting yang berubah.
