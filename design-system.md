# Design System — Co-Fi (Coffee Brand Website)

> Dokumen ini diturunkan dari satu screenshot referensi (tampilan desktop: landing page + section "Brew. Pair. Enjoy." + loyalty CTA).
> Screenshot adalah **referensi visual**, bukan target pixel-perfect.
> Pendekatan: **mobile-first**. Semua nilai default di bawah adalah nilai mobile, lalu ditingkatkan lewat `min-width` breakpoint.
> Tanda **[ASUMSI]** = detail yang tidak terlihat di gambar dan ditentukan secara masuk akal.

---

## 1. Design Direction

**Karakter:** hangat, artisan, playful-editorial. Perpaduan tipografi poster yang tegas (huruf kondensed super tebal) dengan warna tanah/kopi yang lembut dan ilustrasi doodle tangan.

**Kata kunci:** warm, crafted, bold headline, soft container, friendly, community/loyalty.

**Prinsip utama:**
1. **Headline sebagai poster** — judul besar, uppercase, rapat; ini elemen identitas utama.
2. **Konten dalam "wadah" lembut** — hampir semua konten berada di dalam container/kartu dengan sudut sangat membulat di atas background krem.
3. **Warna bumi, bukan warna digital** — krem, tan, hijau hutan, cokelat espresso. Warna terang/jenuh hanya muncul dari foto produk.
4. **Detail tangan** — doodle line-art (cookie, cupcake, croissant) memberi kehangatan; dipakai sebagai dekorasi, tidak pernah menutupi konten.
5. **Fotografi manusiawi** — foto orang sungguhan (barista, pelanggan), bukan stok produk sterile.

**Struktur halaman (urutan section):**
Navigation → Hero (tagline kecil + headline + foto besar) → Partner/brand strip → Best Products → "Brew. Pair. Enjoy." (photo grid) → Feature card hijau ("Sips worth sharing") → Loyalty CTA → Footer **[ASUMSI: footer tidak terlihat]**.

---

## 2. Color Palette

### 2.1 Token warna

| Token | Hex (perkiraan) | Peran |
|---|---|---|
| `--color-bg-page` | `#E9E0D0` | Background utama halaman (krem hangat) |
| `--color-bg-surface` | `#F7F2E8` | Kartu terang, strip brand, product card default |
| `--color-bg-surface-alt` | `#D9CBB2` | Product card highlight/hover, section sekunder |
| `--color-bg-tan` | `#B9A685` | Section CTA loyalty (khaki/tan) |
| `--color-bg-green` | `#2F6A52` | Feature card hijau hutan |
| `--color-bg-frame` | `#3A2B25` | Latar luar/gutter (cokelat gelap, hanya tampak di tepi) |
| `--color-text-primary` | `#1C1714` | Headline, body, tombol gelap (espresso hampir hitam) |
| `--color-text-secondary` | `#5B4F45` | Teks pendukung, caption **[ASUMSI]** |
| `--color-text-on-green` | `#F2EBDD` | Teks body di atas hijau |
| `--color-accent-mint` | `#8ED8B6` | Headline di atas hijau ("Sips worth sharing") |
| `--color-accent-green` | `#2F6A52` | Badge logo, ikon aktif (sama dengan bg-green) |
| `--color-border-subtle` | `#CFC2AA` | Garis tipis/pemisah **[ASUMSI]** |
| `--color-danger` | `#B3261E` | Error form **[ASUMSI]** |
| `--color-success` | `#2F6A52` | Sukses **[ASUMSI]** |
| `--color-overlay` | `rgba(28,23,20,.55)` | Backdrop modal / caption di atas foto |

> Warna merah/oranye pada foto produk (kaleng) berasal dari brand pihak ketiga — **bukan** bagian palet UI. Jangan dipakai sebagai warna aksen komponen.

### 2.2 Aturan penggunaan
- Proporsi: ±70% krem/netral, ±15% tan, ±10% hijau, ±5% hitam (teks + tombol).
- Satu section = satu warna latar dominan. Jangan campur tan dan hijau di satu container.
- Tombol primer selalu gelap (`text-primary`) dengan teks krem; di atas background gelap/hijau gunakan tombol terang.
- **Kontras:** `text-primary` di atas semua krem/tan lolos AA. Mint di atas hijau lolos untuk teks besar (≥24px) — jangan dipakai untuk teks kecil. Teks kecil di atas hijau wajib `text-on-green`.

### 2.3 CSS variables

```css
:root {
  --color-bg-page: #E9E0D0;
  --color-bg-surface: #F7F2E8;
  --color-bg-surface-alt: #D9CBB2;
  --color-bg-tan: #B9A685;
  --color-bg-green: #2F6A52;
  --color-bg-frame: #3A2B25;
  --color-text-primary: #1C1714;
  --color-text-secondary: #5B4F45;
  --color-text-on-green: #F2EBDD;
  --color-accent-mint: #8ED8B6;
  --color-border-subtle: #CFC2AA;
  --color-danger: #B3261E;
  --color-overlay: rgba(28, 23, 20, .55);
}
```

---

## 3. Typography

### 3.1 Font family

| Peran | Karakter di referensi | Rekomendasi (Google Fonts) | Fallback |
|---|---|---|---|
| **Display / Headline** | Grotesque ultra-kondensed, sangat tebal, uppercase | **Anton** (alternatif: Bebas Neue, Oswald 700) | `Impact, 'Arial Narrow', sans-serif` |
| **Body / UI / Caption** | Monospace, kecil, rapi | **Space Mono** (alternatif: JetBrains Mono, IBM Plex Mono) | `ui-monospace, Menlo, monospace` |

**[ASUMSI]** Font persis tidak bisa dipastikan; rekomendasi di atas paling mendekati. Untuk paragraf panjang (>3 baris) boleh memakai font sans netral (mis. *Inter*) agar mudah dibaca — namun gunakan mono untuk caption, label, harga, dan teks pendek agar identitas terjaga.

### 3.2 Type scale (fluid, mobile-first)

| Token | Mobile | Tablet (≥768) | Desktop (≥1200) | Font | Weight | Line-height | Letter-spacing | Transform |
|---|---|---|---|---|---|---|---|---|
| `display-xl` (hero) | 44px | 72px | 104px | Display | 400* | 0.95 | -0.01em | UPPERCASE |
| `display-lg` (section title) | 32px | 44px | 56px | Display | 400* | 1.0 | 0 | UPPERCASE |
| `display-md` (card title) | 28px | 34px | 40px | Display | 400* | 1.0 | 0 | UPPERCASE |
| `title-sm` (nama produk) | 15px | 16px | 16px | Mono | 700 | 1.2 | 0 | UPPERCASE |
| `body` | 14px | 15px | 16px | Mono/Sans | 400 | 1.6 | 0 | none |
| `caption` | 12px | 12px | 12px | Mono | 400 | 1.5 | 0.02em | none |
| `label` (nav, tag) | 12px | 12px | 12px | Mono | 400–700 | 1.2 | 0.06em | UPPERCASE |

\* Anton hanya punya satu weight (400) yang sudah tampil sangat tebal; jangan tambahkan `font-weight: bold` (menyebabkan faux-bold).

> Teks terkecil di referensi tampak ±9–10px. **Jangan ikuti**: minimum 12px untuk keterbacaan & aksesibilitas.

Fluid alternatif dengan `clamp()`:
```css
--fs-display-xl: clamp(2.75rem, 1.6rem + 5.5vw, 6.5rem);
--fs-display-lg: clamp(2rem, 1.4rem + 2.5vw, 3.5rem);
```

### 3.3 Aturan
- Headline selalu **uppercase**, rapat, maksimal 2–3 baris, rata kiri di mobile; boleh rata tengah untuk section judul pendek (mis. "Best Products", "Brew. Pair. Enjoy.").
- Tagline kecil di atas headline hero (`label`, rata tengah, tipis).
- Judul bertitik ("Brew. Pair. Enjoy.") dipertahankan sebagai gaya copy.
- Maksimal lebar baris paragraf: 60ch.

---

## 4. Spacing

Basis grid **4px**, skala dominan kelipatan 8.

| Token | Nilai |
|---|---|
| `--space-1` | 4px |
| `--space-2` | 8px |
| `--space-3` | 12px |
| `--space-4` | 16px |
| `--space-5` | 24px |
| `--space-6` | 32px |
| `--space-7` | 48px |
| `--space-8` | 64px |
| `--space-9` | 96px |

**Penerapan:**

| Konteks | Mobile | Tablet | Desktop |
|---|---|---|---|
| Padding horizontal halaman (gutter) | 16px | 24px | 32px |
| Jarak antar section (vertical) | 48px | 64px | 96px |
| Padding dalam container besar (hero, feature card) | 16px | 24px | 32–40px |
| Padding product card | 12px | 16px | 16px |
| Gap antar card / grid | 12px | 16px | 24px |
| Jarak headline → konten | 24px | 32px | 40px |

Container maksimum: `max-width: 1200px` (konten), dengan margin otomatis. **[ASUMSI]** Di referensi, halaman tampak seperti "frame" dengan margin tipis dari tepi — pada layar >1440px, tetap batasi lebar dan biarkan background meluas.

---

## 5. Border Radius

| Token | Nilai | Penggunaan |
|---|---|---|
| `--radius-sm` | 8px | Chip kecil, input kecil **[ASUMSI]** |
| `--radius-md` | 16px | Product card, thumbnail foto |
| `--radius-lg` | 24px | Container besar (strip brand, hero image) |
| `--radius-xl` | 32px | Feature card hijau, hero frame |
| `--radius-pill` | 999px | Tombol, tag, chip ikon, nav-pill |

**Aturan:** radius container luar > radius elemen di dalamnya (nested radius = outer − padding). Semua sudut membulat; tidak ada sudut tajam kecuali garis pemisah.

---

## 6. Shadow & Elevation

Referensi nyaris **flat** — kedalaman dibuat lewat perbedaan warna latar, bukan bayangan. Pertahankan prinsip ini.

| Token | Nilai | Penggunaan |
|---|---|---|
| `--shadow-none` | none | Default semua kartu/container |
| `--shadow-sm` | `0 1px 2px rgba(28,23,20,.08)` | Kartu saat hover **[ASUMSI]** |
| `--shadow-md` | `0 8px 24px rgba(28,23,20,.12)` | Dropdown, cart drawer **[ASUMSI]** |
| `--shadow-lg` | `0 16px 48px rgba(28,23,20,.20)` | Modal **[ASUMSI]** |

Elevasi utama = **tone shift**: kartu `surface` di atas `bg-page`, hover → `surface-alt`.

---

## 7. Components

### 7.1 Button

**Varian (dari referensi):**
| Varian | Tampilan | Contoh |
|---|---|---|
| **Primary (solid dark)** | Bg `text-primary`, teks krem, pill, uppercase mono kecil | "JOIN THE CLUB", "ADD TO CART" (state hover/aktif) |
| **Ghost / text** | Tanpa background, teks gelap, uppercase | "ADD TO CART" (default di card) |
| **Secondary (light)** **[ASUMSI]** | Bg `surface`, teks gelap, border tipis | Di atas background gelap/hijau |
| **Icon chip** | Pill kecil berisi ikon, bg gelap semi-solid | Chip ikon di hero (kategori/aksi cepat) |

**Spesifikasi:**
- Tinggi: **44px** (mobile, minimum touch target), 40px desktop untuk tombol sekunder.
- Padding horizontal 20–24px, radius `pill`.
- Font: `label` (mono 12–13px, uppercase, `letter-spacing: .06em`).
- State: hover → naik satu tone (primary tetap gelap, `opacity .9` atau bg `#000`); focus → outline 2px `accent-green` offset 2px; active → `scale(.98)`; disabled → opacity .4, `cursor: not-allowed`.
- Transisi 150–200ms ease-out.

**Responsive:**
| | Perilaku |
|---|---|
| Mobile | CTA utama **full-width** dalam container; "Add to cart" full-width di dalam card atau berupa ikon-tombol 44×44 jika card 2 kolom |
| Tablet | Lebar mengikuti konten (auto), CTA utama boleh tetap full-width di card sempit |
| Desktop | Lebar auto, rata kiri/tengah sesuai section |

### 7.2 Form (Input, Select, Checkbox)

Tidak ada form di screenshot (hanya tombol "Join the club"). **[ASUMSI]** untuk form newsletter/join club/checkout, mengikuti bahasa visual yang sama:

- **Input:** tinggi 48px, bg `surface`, border 1px `border-subtle`, radius `pill` (untuk input satu baris pendek seperti email) atau `radius-md` (textarea/field panjang), teks mono 14px. Placeholder `text-secondary`.
- **Label:** `label` style (mono 12px uppercase) di atas input, jarak 8px.
- **Focus:** border `text-primary` 1.5px + ring 3px `rgba(47,106,82,.25)`.
- **Error:** border `danger`, pesan 12px di bawah, ikon opsional. Jangan hanya mengandalkan warna.
- **Checkbox/radio:** 20px, radius 6px (checkbox), warna terpilih `accent-green`.
- **Disabled:** bg `surface-alt`, teks `text-secondary`.

**Responsive:**
| | Perilaku |
|---|---|
| Mobile | Semua field 1 kolom, full-width; tombol submit full-width di bawah. Font input **min 16px** (mencegah zoom otomatis iOS). Gunakan `inputmode`/`type` yang tepat (`email`, `tel`). |
| Tablet | Field pendek boleh 2 kolom (mis. nama depan/belakang); form newsletter bisa inline (input + tombol satu baris) |
| Desktop | Form multi-field 2 kolom; newsletter inline; lebar form maks 480–560px |

### 7.3 Card

**A. Product Card** (dari "Best Products")
- Container: bg `surface` (default) / `surface-alt` (hover, highlight), radius `md` (16px), padding 12–16px, tanpa shadow.
- Anatomi dari atas: **tag kecil** (mis. "BEST CAPPUCCINO", "DARK ROAST" — `label` 10→12px, `text-secondary`) → **nama produk** (`title-sm`, uppercase, bold, rata tengah) → **gambar produk** (rasio ±1:1, `object-fit: contain`, rata tengah, ruang napas di sekitarnya) → **baris aksi**: "ADD TO CART" (kiri, ghost) + **harga** (kanan; harga coret/diskon `-80$` berdampingan dengan harga akhir).
- Harga: harga akhir `text-primary` bold; harga lama coret `text-secondary`; badge diskon boleh pill kecil. **[ASUMSI]** Format mata uang menyesuaikan lokal (mis. `Rp`).
- Hover/focus: bg → `surface-alt`, tombol "Add to cart" berubah solid gelap (seperti kartu tengah pada referensi), transisi 200ms.

**B. Feature Card** (hijau — "Sips worth sharing")
- Bg `bg-green`, radius `xl`, padding 24–40px, judul `display-lg` warna `accent-mint`, foto dalam frame radius `lg`, caption di panel gelap semi-transparan (`rgba(0,0,0,.25)`, radius `md`, teks mono 12px `text-on-green`).
- Doodle (cookie) sebagai dekorasi absolut di pojok, `opacity` penuh garis tipis, **tidak menimpa teks**.

**C. Photo Tile** (grid "Brew. Pair. Enjoy.")
- Hanya gambar, radius `md`, rasio 1:1 atau 4:5, `object-fit: cover`. Tidak ada teks.

**D. Brand/Partner Strip**
- Satu container `surface`, radius `lg`, berisi 4 logo mono-gelap sejajar, jarak merata. Logo grayscale/gelap agar konsisten.

**E. CTA Banner** (loyalty)
- Section bg `bg-tan`, headline `display-lg` ("BUY 10 DRINKS, GET 1 FREE"), paragraf mono pendek rata tengah (max 48ch), tombol primer pill, doodle kiri-kanan (cupcake, croissant).

**F. Category Card** (kartu kategori di beranda — gambar kiri, teks kanan)
- Container bg `surface`, border 1px `border-subtle`, radius `lg`, tanpa shadow. Grid 2 kolom: **media** (38–40%, gambar `object-fit: cover`, minimum tinggi 140px) + **body** (judul, deskripsi, baris meta).
- Judul kategori: `title-sm` (mono 700 uppercase). Deskripsi: `body`, warna `text-secondary`, dipotong 3 baris (`-webkit-line-clamp: 3`).
- Baris bawah: badge jumlah produk (`ds-badge--muted`) + CTA "Lihat produk" (label mono uppercase + ikon panah).
- Tanpa gambar: kolom media menampilkan ikon biji kopi di atas `surface-alt` (bukan gambar rusak).
- Tautan memakai pola *stretched link*: `<h3>` berisi `<a>` yang `::after`-nya menutupi seluruh kartu, sehingga satu klik bisa di mana saja pada kartu (tanpa elemen interaktif bersarang).
- Hover: bg `surface`. Fokus: `focus-within` memberi outline hijau 2px.

**Responsive Card:**
| Komponen | Mobile | Tablet | Desktop |
|---|---|---|---|
| Product grid | **1 kolom** untuk card penuh, atau horizontal scroll-snap (card lebar ~80%) **[disarankan]**; alternatif 2 kolom bila kartu ringkas | 2–3 kolom | 3 kolom (bisa 4 jika produk banyak) |
| Photo tiles | 2×2 grid | 4 kolom 1 baris | 4 kolom 1 baris |
| Feature card | Stack vertikal: judul → foto → caption (caption di bawah foto, bukan overlay) | Stack atau 2 kolom (teks kiri, foto kanan) | Layout editorial: judul kiri-atas, foto tengah, caption overlay kanan-bawah (seperti referensi) |
| Brand strip | 2×2 grid logo, atau scroll horizontal/marquee | 1 baris 4 logo | 1 baris 4 logo, lebar penuh container |
| Category card | 1 kartu per baris (gambar + teks), deskripsi 3 baris | 2 kartu per baris | 2 kartu per baris |
| CTA banner | Stack tengah; doodle diperkecil/disembunyikan salah satu | Doodle mengapit teks (kecil) | Doodle besar mengapit teks |

### 7.4 Navigation

**Referensi (desktop):** bar atas minimal, logo "Co-Fi" di **tengah**; link kiri (`MENU`, `FOODS`, `HISTORY`); link kanan (`ACCOUNT`, `BLOG`, `CART (0)`). Teks mono uppercase kecil, tanpa background (transparan di atas bg krem), tanpa garis.

**Mobile-first:**
| | Perilaku |
|---|---|
| **Mobile** | Bar atas: **logo kiri/tengah**, kanan: ikon **cart (dengan badge jumlah)** + **ikon hamburger**. Menu membuka **full-screen sheet / drawer kanan** bg `bg-page`, link besar (display-md, uppercase) tersusun vertikal, jarak 16–24px, Account & Blog di bagian bawah. Tombol tutup 44×44. Body scroll terkunci saat terbuka. Cart tetap terlihat di bar (aksi penting, jangan disembunyikan ke dalam menu). |
| **Tablet** | Logo tengah, link utama inline 3 item kiri, cart + account ikon kanan; item sekunder (Blog) masuk menu "More" atau hamburger jika sempit (<900px) |
| **Desktop** | Persis pola referensi: 3 link kiri · logo tengah · 3 link kanan. Header `sticky` dengan bg `bg-page` + blur halus saat scroll **[ASUMSI]** |

- State link: hover → underline 1px; aktif → underline tebal 2px / bold. Focus ring terlihat.
- Tinggi bar: 56px mobile, 64–72px desktop.
- Semua link teks min target sentuh 44px tinggi di mobile.

### 7.5 Table

Tidak ada di screenshot. **[ASUMSI]** untuk kebutuhan seperti riwayat pesanan, info nutrisi, atau ringkasan keranjang:

- Header baris: `label` (mono uppercase 12px), bg `surface-alt`, tanpa border vertikal.
- Baris: bg `surface`, pemisah 1px `border-subtle`, padding vertikal 12–16px, angka rata kanan (mono, tabular-nums).
- Container tabel: radius `md`, `overflow: hidden`.
- Zebra tidak diperlukan; hover baris → bg `surface-alt`.

**Responsive:**
| | Perilaku |
|---|---|
| Mobile | **Ubah menjadi daftar kartu** (setiap baris jadi kartu: label di kiri, nilai di kanan via `data-label`). Untuk tabel data besar yang harus dibandingkan, bungkus dengan `overflow-x: auto` + kolom pertama sticky. |
| Tablet | Tabel penuh, sembunyikan kolom non-esensial (kolom sekunder → di bawah nilai utama) |
| Desktop | Tabel penuh semua kolom |

### 7.6 Modal / Dialog / Drawer

Tidak ada di screenshot. **[ASUMSI]**, mengikuti bahasa visual:

- Backdrop `--color-overlay`. Panel bg `surface`, radius `xl`, `shadow-lg`, padding 24px, judul `display-md`.
- Tombol tutup (ikon X) 44×44 di pojok kanan atas; aksi di bawah (primary + ghost/secondary).
- Anim: fade + translate 8px, 200ms; hormati `prefers-reduced-motion`.
- Aksesibilitas: `role="dialog"`, `aria-modal="true"`, focus trap, tutup dengan `Esc`, fokus kembali ke pemicu.

**Responsive:**
| | Perilaku |
|---|---|
| Mobile | **Bottom sheet** (menempel bawah, radius atas 24px, tinggi maks 90dvh, scroll internal, handle bar kecil, tombol aksi full-width & sticky di bawah). Untuk alur panjang (checkout) → full-screen. |
| Tablet | Dialog tengah, lebar 480–560px; cart sebagai drawer samping kanan 400px |
| Desktop | Dialog tengah 480–640px; cart drawer kanan 420px |

### 7.7 Elemen Pendukung

- **Icon chip (hero):** kelompok pill gelap kecil (±40×32px) berisi ikon line putih, di pojok kanan-atas foto hero (kemungkinan pilihan metode seduh/kategori). **[ASUMSI]** Interaktif → target sentuh min 44px di mobile; pindah ke baris di bawah foto jika sempit atau jadikan horizontal scroll.
- **Badge logo "Co-Fi" di foto:** pill hijau pojok kiri-bawah foto hero; di mobile diperkecil (±40px).
- **Doodle ilustrasi:** SVG line-art 1.5–2px stroke, warna `text-primary`. Dekoratif → `aria-hidden="true"`, `pointer-events: none`.

### 7.8 Slider hero (Swiper)

Slider utama beranda. Implementasi memakai **Swiper 14** (`swiper-bundle`), dimuat dari CDN
hanya pada halaman yang memakainya.

- **Wadah:** `section` ber-`role="region"` + `aria-roledescription="carousel"`, `aria-label` deskriptif,
  radius `xl`, `overflow: hidden`, latar `forest`.
- **Slide:** tiap slide punya tinggi minimum 416px (mobile) / 512px (≥768px); konten rata bawah di
  mobile, rata tengah di desktop. Foto hero full-bleed + overlay `--color-overlay`.
- **Navigasi:**
  - Panah kiri/kanan — bulat 48px (56px di ≥768px), bg `frame` 72% (hover 92%), garis tepi tipis,
    ikon `on-green`, 12px dari tepi (20px di ≥768px); sengaja besar agar mudah diklik/disentuh.
  - Titik (pagination) — bulat 10px, `on-forest` 40%, aktif `on-forest` 100%, jarak 8px, 16px dari bawah.
  - Penanda aktif diberi `aria-current="true"`; titik adalah `<button>` (bisa di-fokus & di-tekan).
- **Perilaku:** geser otomatis tiap 5 detik (berhenti saat kursor di atas slider), loop, bisa di-drag
  (swipe/drag mouse di desktop; geser singkat ±25% lebar slide sudah pindah), bisa digeser dengan
  keyboard panah kiri/kanan saat slider difokus.
- **Aksesibilitas:** slide aktif `aria-hidden="false"`, slide lain `aria-hidden="true"` + `inert`
  (tidak bisa difokus); pesan a11y berbahasa Indonesia ("Slide sebelumnya", "Ke slide N").
- **Fallback:** bila CDN gagal dimuat, hanya slide pertama yang tampil (tombol & titik disembunyikan)
  — halaman tetap utuh.
- **Reduced motion:** `prefers-reduced-motion: reduce` → autoplay mati dan durasi geser 0.

---

## 8. Layout

### 8.1 Grid & container
- Mobile: 4 kolom, gutter 16px, margin 16px.
- Tablet: 8 kolom, gutter 16–24px, margin 24px.
- Desktop: 12 kolom, gutter 24px, margin 32px, `max-width: 1200px`.
- Gunakan CSS Grid + Flexbox; `gap` bukan margin untuk jarak antar item.

### 8.2 Pola layout per section

| Section | Mobile | Tablet | Desktop (referensi) |
|---|---|---|---|
| **Hero** | Tagline kecil → headline (3 baris, rata kiri/tengah) → foto hero (rasio 4:5 atau 1:1, radius xl) → CTA full-width | Headline lebih besar, foto rasio 4:3 | Headline satu baris lebar penuh → foto landscape lebar dengan icon chip & badge |
| **Brand strip** | 2×2 | 1 baris | 1 baris |
| **Kategori (category card)** | 1 kartu/baris: gambar kiri, teks kanan | 2 kartu/baris | 2 kartu/baris |
| **Best Products** | Judul tengah → card scroll-snap / 1 kolom | 2–3 kolom | 3 kolom |
| **Brew. Pair. Enjoy.** | Judul → grid foto 2×2 | 4 kolom | 4 kolom |
| **Feature (hijau)** | Stack vertikal | Stack/2 kolom | Komposisi editorial berlapis |
| **Loyalty CTA** | Stack tengah | Stack tengah | Tengah, doodle samping |

### 8.3 Urutan konten & prioritas mobile
1. Headline + CTA hero harus terlihat di layar pertama (`above the fold`) tanpa scroll.
2. Produk dan tombol beli lebih penting daripada dekorasi; doodle dan foto sekunder boleh disederhanakan di mobile.
3. Jangan mempertahankan komposisi overlap/absolute desktop di mobile — ubah menjadi urutan vertikal yang bisa dibaca.

### 8.4 Gambar
- Gunakan `<img>` dengan `srcset`/`sizes`, format WebP/AVIF, `loading="lazy"` kecuali hero (`fetchpriority="high"`).
- Selalu tentukan `width`/`height` atau `aspect-ratio` untuk mencegah layout shift.
- Alt text deskriptif untuk foto produk dan orang; kosong (`alt=""`) untuk dekoratif.

### 8.5 Halaman pratinjau desktop (1440 × 900)
Halaman khusus untuk melihat beranda pada ukuran desktop tetap, berapa pun lebar jendela browser.

- Latar luar `bg-frame` (cokelat gelap), `min-width: 1440px` → di layar sempit halaman digeser horizontal.
- Bingkai: 1440 × 900, radius `lg`, shadow `lg`, hairline terang 1px, `overflow: hidden`.
- Baris judul di atas bingkai: `label` uppercase (judul + ukuran "1440 × 900 px"), tanpa tautan.
- **Isi dimuat lewat `<iframe>` 1440 × 900** — wajib, bukan pilihan: media query mengevaluasi lebar
  jendela browser, bukan lebar wadah, sehingga tanpa iframe tampilan ini akan ikut memakai layout mobile.
- Konten yang lebih panjang digulir di dalam iframe (seperti jendela browser), halaman luar tetap diam.
- Halaman ini `noindex` dan tidak diberi tautan di footer — hanya dibuka lewat URL.

---

## 9. Responsive Behavior (Ringkasan)

### 9.1 Breakpoints (mobile-first, `min-width`)

| Nama | Lebar | Catatan |
|---|---|---|
| `base` | 0–599px | Mobile (default, tanpa media query) |
| `sm` | ≥600px | Mobile besar / tablet kecil |
| `md` | ≥768px | **Tablet** |
| `lg` | ≥1024px | Laptop kecil / tablet landscape |
| `xl` | ≥1200px | **Desktop** |
| `2xl` | ≥1536px | Layar lebar (container tetap 1200px, hanya margin bertambah) |

```css
/* Mobile-first */
.product-grid { display: grid; gap: var(--space-3); grid-template-columns: 1fr; }
@media (min-width: 768px)  { .product-grid { grid-template-columns: repeat(2, 1fr); gap: var(--space-4); } }
@media (min-width: 1024px) { .product-grid { grid-template-columns: repeat(3, 1fr); gap: var(--space-5); } }
```

### 9.2 Matriks adaptasi komponen

| Komponen | Mobile | Tablet | Desktop |
|---|---|---|---|
| Navigation | Logo + cart + hamburger → full-screen menu | Hybrid: link inline utama, sisanya di menu | 3 link · logo tengah · 3 link |
| Hero headline | 44px, 2–3 baris | 72px, 2 baris | 104px, 1 baris |
| Hero image | 4:5, penuh lebar | 4:3 | 16:7 landscape + chip/badge |
| Buttons | Full-width, 44px+ | Auto/full-width di card | Auto |
| Product cards | Scroll-snap / 1 kolom | 2–3 kolom | 3 kolom |
| Photo grid | 2×2 | 1×4 | 1×4 |
| Feature card | Vertikal | Vertikal/2 kolom | Berlapis editorial |
| Forms | 1 kolom, field 16px | 2 kolom parsial | 2 kolom, inline newsletter |
| Table | Kartu bertumpuk / scroll-x | Tabel, kolom dikurangi | Tabel penuh |
| Modal | Bottom sheet / full-screen | Dialog tengah + drawer | Dialog tengah + drawer |
| Doodle dekoratif | Kecil/sebagian disembunyikan | Sedang | Penuh |

### 9.3 Aturan umum responsive
1. **Touch target** ≥44×44px; jarak antar target ≥8px.
2. **Tidak ada scroll horizontal** pada halaman (kecuali carousel yang jelas, dengan `scroll-snap` dan petunjuk bahwa ada konten berikutnya).
3. Gunakan satuan relatif (`rem`, `%`, `clamp()`), hindari lebar piksel tetap.
4. Tinggi section pakai `min-height` dan `dvh`, bukan `vh` tetap, agar aman terhadap address bar mobile.
5. Hormati `safe-area-inset-*` untuk header/footer sticky di perangkat dengan notch.
6. Uji pada 360px, 390px, 768px, 1024px, 1280px, 1440px.
7. Dukung zoom teks 200% tanpa kehilangan konten/fungsi.

---

## 10. Motion & Interaction **[ASUMSI]**

- Durasi: 150ms (micro), 200–300ms (kartu/menu), easing `cubic-bezier(.2,.8,.2,1)`.
- Hover kartu: perubahan warna latar + tombol menjadi solid.
- Reveal saat scroll: fade-up 12px, sekali saja, tidak untuk elemen kritikal.
- Hormati `prefers-reduced-motion: reduce` (matikan animasi non-esensial).

---

## 11. Aksesibilitas (Checklist)

- Kontras teks ≥4.5:1 (normal) / ≥3:1 (besar ≥24px atau bold ≥19px).
- Fokus keyboard terlihat di semua elemen interaktif.
- Heading hierarki benar (satu `h1` di hero; section judul `h2`).
- Landmark: `header`, `nav`, `main`, `section`, `footer`.
- Badge keranjang diumumkan lewat `aria-live="polite"` saat berubah.
- Ikon-tombol memiliki `aria-label` (mis. "Buka menu", "Keranjang, 2 item").
- Teks uppercase besar: gunakan `text-transform` di CSS (bukan mengetik huruf kapital) agar pembaca layar tidak mengeja huruf per huruf.

---

## 12. Daftar Asumsi

1. Font persis tidak diketahui; Anton + Space Mono dipilih sebagai padanan terdekat.
2. Nilai hex diperkirakan dari screenshot (kompresi gambar memengaruhi akurasi).
3. Form, table, modal, footer, dan state interaktif tidak terlihat → dirancang mengikuti bahasa visual yang sama.
4. Ukuran teks terkecil di referensi (±9–10px) dinaikkan ke minimum 12px.
5. Fungsi icon chip di hero diasumsikan sebagai pilihan kategori/metode seduh.
6. Mata uang pada referensi (`$`) diasumsikan bisa diganti lokal sesuai pasar.
7. Header diasumsikan `sticky`; di referensi tidak dapat dipastikan.
