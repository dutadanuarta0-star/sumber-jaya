# TASKS — Sumber Jaya

Toko online kopi. Stack: CodeIgniter 3 (PHP 8.2+), Tailwind CSS via CDN, MySQL.
Database schema: `database.sql` (di root, di-import manual).

Status: `[ ]` belum, `[~]` sedang, `[x]` selesai.

---

## 1. Setup
- [x] Konfigurasi `config.php` (base_url, CSRF on), `autoload.php`, `database.php`, `routes.php`
- [x] Buat `database.sql` (tabel + seed) lalu import ke MySQL
- [x] `application/core/MY_Controller.php` (Public_Controller + Admin_Controller)
- [x] Layout `views/layout/header.php` + `footer.php` (navbar, Tailwind CDN)
- [x] Buat TASKS.md & AGENTS.md

## 2. Halaman publik
- [x] Beranda (`Home`) — hero, kategori, produk unggulan
- [x] Katalog produk (`Products::index`) + filter kategori via `?kategori=slug`
- [x] Detail produk (`Products::detail`) — pilih varian, harga, cek stok, qty
- [x] Tentang kami (`About`) — konten dari tabel settings

## 3. Keranjang & checkout WhatsApp
- [x] Model keranjang (session) : add / update / remove
- [x] Halaman keranjang — ringkasan, subtotal, validasi stok
- [x] Checkout: format pesan → redirect `wa.me/<nomor-toko>`

## 4. Admin auth
- [x] Login / logout (`admin/Login`) + rate limit session (5x / 15 menit)
- [x] Guard halaman admin via `Admin_Controller`

## 5. Admin CRUD
- [x] Produk: list, tambah, edit, hapus (varian + stok di form yang sama)
- [x] Kategori: list, tambah, edit, hapus
- [x] Pengaturan identitas toko (`admin/Settings`)

## 6. Dashboard admin
- [x] Tracking kunjungan unik (cookie `sj_vid` + `page_views`)
- [x] Total kunjungan (hari ini / 7 hari / 30 hari), top 10 produk dilihat
- [x] Ringkasan: jumlah produk, kategori, stok menipis

## 7. Finalisasi
- [x] Cek escaping output (XSS), CSRF semua form, validasi server-side
- [x] Uji manual seluruh alur (publik + admin)
- [x] Update AGENTS.md (struktur, cara import DB, konvensi penting)

## 8. Perbaikan bug (audit)
- [x] Login admin gagal di browser dengan "The action you have requested is not allowed"
  (penyebab: `base_url` kosong/auto-detect → action form menarget host `[::1]`, cookie CSRF
  hilang. Fixed: `base_url` eksplisit di `config.php`; dicatat di AGENTS.md)
- [x] Halaman detail produk menampilkan warning "Undefined array key category_slug"
  (PHP 8): breadcrumb memakai `$product['category_slug']` tapi `Product_model::getDetail()`
  hanya memilih `category_name`. Fixed: tambah `categories.slug AS category_slug`;
  semua 8 halaman detail diuji ulang — 200 tanpa error

## 9. Fitur tambahan
- [x] Tombol chat WhatsApp mengambang di semua halaman publik (kanan-bawah),
  nomor dari `settings.whatsapp_number`, pesan pembuka "Halo ... saya ingin bertanya
  tentang produk" — `views/layout/footer.php`
- [x] Gambar produk manual (tanpa sistem upload): folder `assets/img/produk/`,
  helper `img_src()` (path lokal → base_url), field Gambar form admin menerima
  path lokal atau URL https, validasi `regex_match` — uji e2e lulus (tolak `../x.jpg`,
  terima path assets, render + akses file OK)
- [x] Ubah username & password admin — halaman "Ubah Akun" (`admin/settings/account`,
  metode `account`/`accountUpdate` di controller Settings): wajib password saat ini,
  password baru min 8 karakter + konfirmasi, cek username kembar, `password_hash()`.
  Uji e2e lulus (password salah ditolak, password pendek ditolak, ubah sukses,
  login kredensial baru sukses, dikembalikan ke `admin`/`admin123`)
- [x] Perbaikan: menu sidebar "Ubah Akun" menaut ke `admin/account` (404) — ditambahkan
  rute eksplisit `admin/account` & `admin/accountUpdate` di `routes.php`; halaman
  terbuka 200, menu aktif tersorot, alur ubah password lewat menu lulus uji
- [x] Dashboard: kunjungan 7 hari terakhir diubah dari tabel menjadi bar chart
  (HTML/Tailwind, tanpa library): `dailyUnique()` kini mengembalikan deret 7 hari
  lengkap urut lama→baru dengan hari kosong = 0; bar hari ini disorot, tooltip
  tanggal + jumlah; uji live lulus (data 20 Oct = 96, 21 Oct = 1, 5 hari lain = 0)
- [x] Pencarian produk: kolom cari di navbar (semua halaman publik, desktop + mobile)
  dan halaman katalog; parameter `?q=` bisa digabung dengan `?kategori=`,
  `like()` Query Builder (wildcard di-escape), chip kategori mempertahankan kata
  kunci, hasil kosong diberi pesan + tautan hapus pencarian. Uji lulus: arabika=4,
  ROBUSTA=2 (case-insensitive), besemah=1, zzz=0, q=% aman, XSS ter-escape,
  gabungan kategori+pencarian benar
- [x] Hero beranda jadi slider: 3 slide (fade 700ms), panah + dots, auto-geser
  6 detik, berhenti saat hover/fokus, hormati `prefers-reduced-motion`;
  vanilla JS inline (tanpa library), konten dari array `$heroSlides` di
  `views/home.php`; slide non-aktif `inert` + `aria-hidden`
- [x] Gambar produk "kopi Besemah" (id 6): foto asli dataran tinggi Pagaralam
  (Wikimedia Commons, CC BY-SA 4.0 — kredit di deskripsi),
  file `assets/img/produk/kopi-besemah.jpg`
