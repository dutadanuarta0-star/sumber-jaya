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
- [x] Beranda (`Home`) — hero, kategori (kartu 2 kolom: gambar kiri, teks kanan), produk unggulan
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
- [x] Kategori: list, tambah, edit, hapus (gambar kategori bisa di-upload dari komputer
  atau diisi lewat path/URL, tampil di kartu kategori beranda)
- [x] Slider beranda: list, tambah, edit, hapus (jumlah slide & gambar diatur admin,
  termasuk upload gambar dari komputer)
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
- [x] Gambar produk Arabika Gayo tidak tampil: field Gambar diisi URL halaman produk
  marketplace (`.html`), bukan file gambar. Fixed: foto Gayo asli
  `assets/img/produk/arabika-gayo.jpg` (Wikimedia Commons, CC BY-SA 4.0 — kredit di
  deskripsi), `image_url` diperbarui; teks bantuan form admin diperjelas (URL harus
  menunjuk file gambar); kartu & detail produk diberi fallback `onerror` ke placeholder
  agar URL rusak tidak menampilkan ikon gambar pecah

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
- [x] Hero beranda jadi slider: fade 700ms, panah + dots, auto-geser
  5 detik, berhenti saat hover/fokus, hormati `prefers-reduced-motion`;
  vanilla JS inline (tanpa library); slide non-aktif `inert` + `aria-hidden`;
  panah/dots otomatis disembunyikan bila hanya 1 slide aktif
- [x] Slider beranda dikelola dari admin (`admin/Slider`, tabel `hero_slides`):
  tambah/hapus slide = mengubah jumlah slide; field Gambar latar menerima path
  lokal `assets/img/slider/...` atau URL https; label, judul, deskripsi, teks +
  tautan tombol, urutan tampil, dan toggle aktif. Gambar opsional — tanpa gambar
  memakai warna latar bergiliran. Beranda otomatis memakai slide aktif urut
  `sort_order`; bila belum ada slide aktif, tampil 1 slide bawaan. Uji e2e lulus:
  tambah/ubah/hapus slide, gambar tampil di beranda, tolak `../evil.jpg` dan
  `javascript:`, slide nonaktif tidak tampil, kondisi 1 slide tanpa panah/dots,
  halaman admin & beranda tanpa error PHP
- [x] Gambar produk "kopi Besemah" (id 6): foto asli dataran tinggi Pagaralam
  (Wikimedia Commons, CC BY-SA 4.0 — kredit di deskripsi),
  file `assets/img/produk/kopi-besemah.jpg`
- [x] Upload gambar langsung dari komputer (produk & slider) — tidak perlu menyalin path:
  `application/libraries/Image_upload.php` menerima JPG/PNG/WEBP maks 3 MB, nama file diacak
  (`encrypt_name`), disimpan ke `assets/img/produk/` atau `assets/img/slider/`; form memakai
  `form_open_multipart` + input file. Path/URL manual tetap didukung (upload menang bila
  keduanya diisi); `webp` ditambahkan ke `config/mimes.php`; `.htaccess` di folder gambar
  memblokir eksekusi skrip. Uji e2e lulus: upload JPG → tersimpan, tampil di katalog/beranda,
  akses web 200 `image/jpeg`; file `.txt` berisi PHP ditolak + tidak tersimpan; path manual
  tanpa file tetap jalan

## 10. Design system Co-Fi (implementasi `design-system.md`)

Spesifikasi: `design-system.md` (root project) — warna krem/tan/hijau hutan, tipografi
Anton + Space Mono, radius besar, komponen ramah. Semua perubahan **styling only**:
tidak ada perubahan controller/model/library/`config/`/`system/`/`database.sql`.

- [x] Dokumen spesifikasi disalin ke root project sebagai sumber kebenaran
- [x] Aset dasar:
  - `assets/css/design-system.css` — token (`:root`), kelas komponen `.ds-*`, focus-visible,
    `prefers-reduced-motion`
  - `assets/js/ds-tailwind-config.js` — token warna `ds-*`, breakpoint (sm 600 / md 768 /
    lg 1024 / xl 1200 / 2xl 1536), skala radius monotonik (8/16/24/32/40/48 + pill),
    skala tipografi `display-xl/lg/md`
  - `assets/js/ds-ui.js` — drawer menu mobile (focus trap + Escape + kembalikan fokus)
- [x] Halaman publik ditulis ulang: `layout/header.php` (nav 3+logo+3, drawer, search,
  badge keranjang `aria-live`), `layout/footer.php` (frame gelap + tombol WA mengambang),
  `home.php` (slider hero, brand strip, best products, feature card hijau, CTA banner tan),
  `products/_card.php`, `products/index.php`, `products/detail.php`, `cart/index.php`, `about.php`
- [x] Halaman admin ditulis ulang: `admin/layout/header.php` + `footer.php`, `admin/login.php`,
  `admin/dashboard.php`, `admin/products/*`, `admin/categories/*`, `admin/slider/*`,
  `admin/settings.php`, `admin/settings/account.php` — tabel tetap tabel (DS §7.5) dengan
  `overflow-x: auto` + kolom pertama sticky
- [x] Keputusan: kartu produk publik memakai tautan ghost "LIHAT PRODUK" (tanpa endpoint
  POST add-to-cart baru); tombol WhatsApp memakai hijau DS `#2F6A52` (ikon WA tetap);
  CTA banner = "GABUNG KLAN KOPI" (tanpa janji diskon fiktif)
- [x] Uji lint PHP bersih di semua view; semua rute publik & 12 rute admin balas 200 tanpa error
- [x] Uji responsif/a11y (`tools/verify.js`, Chrome DevTools Protocol): 6 viewport
  (360/390/768/1024/1280/1440) × halaman publik + admin — tanpa overflow horizontal, skala
  tipografi sesuai DS, radius 16/24/pill sesuai, pergantian nav/burger benar, tanpa error konsol
- [x] Uji fungsional e2e: add/update/remove keranjang + checkout `wa.me` (isi pesan & subtotal
  benar), pencarian & filter kategori, CRUD admin (produk + varian, slider, pengaturan) —
  record uji dibuat lalu dihapus, jumlah data kembali ke baseline
- [x] `AGENTS.md` diperbarui: struktur aset baru, konvensi token/design system, harness verifikasi

## 11. Beranda tampilan desktop (1440 × 900)

- [x] Halaman terpisah `/beranda-desktop` — beranda dalam bingkai 1440 × 900 (pratinjau/screenshot)
- [x] Isi dimuat lewat `<iframe>` ke `/beranda-desktop/isi` (wajib: media query mengevaluasi lebar
  jendela browser, bukan lebar wadah — tanpa iframe layout mobile ikut terpakai di HP)
- [x] Rute eksplisit di `routes.php` (`beranda-desktop`, `beranda-desktop/isi`) karena CI3 membaca
  segmen ke-2 sebagai controller
- [x] Controller `Home::desktop()` (shell bingkai) + `Home::desktopIsi()` (isi beranda, tanpa
  `recordView()` supaya statistik tidak dobel); view baru `views/home/desktop.php` berdiri sendiri
- [x] CSS `.ds-canvas-page` / `.ds-canvas-bar` / `.ds-canvas-frame` di `design-system.css`;
  `body` min-width 1440px → di layar sempit (< 1440px) halaman luar bisa digeser horizontal
- [x] Halaman `noindex`, tanpa tautan di footer (hanya dibuka lewat URL)
- [x] Uji (Chrome DevTools Protocol): bingkai selalu 1440 × 900 di outer 390/1440/1920 px; di dalam
  iframe nav desktop tampil, burger mobile disembunyikan, kartu kategori 2 kolom, gambar di kiri;
  tinggi isi 3676px digulir di dalam iframe, header sticky menempel di atas iframe; tanpa error konsol

## 12. Slider hero pakai Swiper

- [x] Slider hero beranda (dari admin > Slider Beranda) diganti dari JS manual (opacity + timer)
  menjadi **Swiper 14** (`swiper-bundle`, CDN jsDelivr, versi dipin `14.3.0`)
- [x] Markup Swiper di `application/views/home.php` (`swiper` / `swiper-wrapper` / `swiper-slide` /
  `swiper-button-prev|next` / `swiper-pagination`); konfigurasi + a11y di `assets/js/ds-slider.js`
- [x] Aset Swiper dimuat **kondisional** lewat `$useSwiper` (hanya beranda): CSS di `<head>`,
  JS di akhir `<body>` — halaman lain tidak menambah bobot
- [x] Gaya komponen `.ds-hero` di `design-system.css` (panah 44px `frame` 60%, titik 10px `on-forest`
  40%/100% aktif, radius `xl`, fallback `.ds-hero--ready`)
- [x] Perilaku: loop, autoplay 5 detik (berhenti saat kursor di atas), swipe/drag, keyboard panah,
  `prefers-reduced-motion` → autoplay mati + durasi 0
- [x] Aksesibilitas: slide aktif `aria-hidden="false"`, slide lain `aria-hidden="true"` + `inert`;
  pesan berbahasa Indonesia ("Slide sebelumnya", "Slide berikutnya", "Ke slide N"); titik = `<button>`
  dengan `aria-current`; panah punya `role="button"` + `aria-label`
- [x] Fallback: bila CDN diblokir, hanya slide pertama tampil, tombol/titik disembunyikan —
  halaman tetap utuh (diuji dengan memblokir `cdn.jsdelivr.net`)
- [x] `tools/verify.js` diperbarui: menunggu event `Page.loadEventFired` (bukan sleep tetap)
  supaya halaman beranda tidak diukur sebelum Swiper termuat — 30 pemeriksaan lulus, tanpa error konsol
- [x] Perbaikan: slider sempat terkunci (panah, autoplay, dan drag tidak berfungsi) karena CSS
  fallback menyembunyikan slide (`display: none`) saat init sehingga Swiper mengukur lebar 0 —
  `init` kini melepas kelas fallback lebih dulu lalu memanggil `swiper.update()`
  (diuji: `isLocked` false, tombol next pindah slide, autoplay berjalan)
- [x] Navigasi kiri/kanan dibuat lebih mudah: panah 48px (56px desktop) dengan latar lebih pekat,
  garis tepi, bayangan, dan focus ring; geser horizontal eksplisit (`effect: slide`,
  `slidesPerView: 1`, `simulateTouch`, `longSwipesRatio: 0.25`) sehingga bisa di-drag dengan mouse
  maupun sentuhan dan geseran singkat sudah pindah slide

## 13. Audit keamanan & fungsional admin

- [x] Audit akses: semua halaman admin tanpa login dialihkan ke `admin/login`; CSRF aktif di semua
  form POST; POST tanpa token ditolak 403; rate limit 5 percobaan gagal / 15 menit bekerja
- [x] Perbaikan kebocoran: folder `.git/` dan `tools/` diblokir dari web, directory listing dimatikan,
  berkas sisa uji (`diag.php`, `c2.txt`, `cj.txt`, `rl.txt`, `cart.html`, `rl.html`) dihapus
- [x] Header keamanan dasar (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`) dan
  `Cache-Control: no-store` untuk halaman admin/login
- [x] Session: `sess_regenerate_destroy = TRUE`; cookie `Secure` otomatis saat HTTPS, `HttpOnly` aktif
- [x] Integritas produk: produk wajib punya minimal 1 varian (dicek di controller & model); upload
  gambar divalidasi sebelum file disimpan
- [x] Validasi: nomor WhatsApp wajib mengandung angka; `sort_order` slider boleh kosong (= 0)
- [x] Uji otomatis 28 pemeriksaan: login/CSRF, CRUD produk/kategori/slider, guard hapus kategori,
  pengaturan, akun, upload file PHP menyamar sebagai JPG (ditolak) — semua lulus
- [x] `node tools/verify.js` dengan login admin: 30 pemeriksaan tampilan + 7 halaman admin lulus,
  tanpa error konsol
- [ ] **Tindakan pemilik:** password admin masih `admin123` — segera ganti lewat `admin/akun`

## 14. Audit fungsional sisi pelanggan

- [x] Audit alur publik: beranda, katalog + filter kategori + pencarian, detail produk, keranjang,
  update/hapus item, dan checkout WhatsApp (redirect `wa.me` berisi daftar pesanan + subtotal)
- [x] Perbaikan tautan WhatsApp di header/drawer: parameter pesan memakai `?text=` (sebelumnya
  `?Halo%20...` sehingga pesan tidak terisi). Semua tautan WA kini lewat helper `wa_link()` yang
  menormalkan nomor menjadi digit dan meng-encode pesan; tautan kosong tidak dirender
- [x] Perbaikan halaman "Tentang Kami": blok kontak + tombol WhatsApp tetap tampil bila toko hanya
  mengisi nomor WhatsApp (tanpa alamat/telepon/email)
- [x] Perbaikan detail produk: varian pertama yang **masih ada stok** dipilih otomatis; sebelumnya
  bila varian termurah habis tidak ada radio yang tercentang dan form bisa terkirim tanpa varian.
  Radio stok tersedia kini `required`
- [x] Perbaikan keranjang: update jumlah pada varian stok habis tidak lagi menyimpan jumlah 1
  (item dihapus dengan pesan yang sesuai); form update disembunyikan untuk item stok habis;
  item session tanpa jumlah dibuang; checkout melewati item berjumlah 0
- [x] Perbaikan notifikasi keranjang: flash "Sebagian isi keranjang disesuaikan..." hanya muncul
  saat ada perubahan nyata (tidak berulang setiap halaman dimuat)
- [x] Perbaikan hitungan produk pada kartu kategori beranda: hanya produk aktif yang dihitung
  (halaman admin tetap menghitung semua produk)
- [x] Verifikasi: lint PHP semua berkas yang diubah; uji manual tambah/update/hapus/checkout;
  skenario varian pertama habis; skenario stok berubah menjadi 0; `node tools/verify.js`
  30 pemeriksaan tampilan lulus tanpa error konsol
