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

## 9. Fitur tambahan
- [x] Tombol chat WhatsApp mengambang di semua halaman publik (kanan-bawah),
  nomor dari `settings.whatsapp_number`, pesan pembuka "Halo ... saya ingin bertanya
  tentang produk" — `views/layout/footer.php`
- [x] Gambar produk manual (tanpa sistem upload): folder `assets/img/produk/`,
  helper `img_src()` (path lokal → base_url), field Gambar form admin menerima
  path lokal atau URL https, validasi `regex_match` — uji e2e lulus (tolak `../x.jpg`,
  terima path assets, render + akses file OK)
