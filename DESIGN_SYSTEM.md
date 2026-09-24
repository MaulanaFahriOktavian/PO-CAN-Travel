# DESIGN SYSTEM — PO CAN TRAVEL
**Versi:** 2.0 (Product Redesign & Information Architecture)  
**Tipe Produk:** Official Intercity Bus Transportation Platform  
**Filosofi Inti:** *Information First, Clarity Over Decoration, Real Data Over Template Slop*

---

## 1. Prinsip Desain & Filosofi Produk

PO CAN Travel bukan landing page SaaS, bukan template AI, dan bukan sekadar formulir booking dengan gambar dekoratif. Platform ini adalah **antarmuka resmi perusahaan transportasi antarkota** yang mengutamakan kepercayaan (*trust*), kejelasan informasi (*clarity*), dan efisiensi operasional.

### 4 Pilar Utama:
1. **Information First**: Setiap piksel, tabel, dan label memiliki fungsi logis. Sebelum menaruh suatu elemen, pastikan: *Informasi apa yang dibutuhkan pengguna? Keputusan apa yang dibantu? Aksi apa yang diambil selanjutnya?*
2. **Anti-AI-Slop & Editorial Transport Aesthetic**:
   - Dilarang menggunakan *card-in-card nesting*, neon glow, blob gradien acak, glassmorphism buram yang mengaburkan teks, maupun angka dekoratif kosong (*01, 02, SMART, NEXT-GEN*).
   - Menggunakan tata letak editorial yang asimetris, garis pembatas tipis (*1px hairline border* `#E2E8F0`), kontras tinggi untuk keterbacaan, dan fotografi operasional/perjalanan nyata.
3. **Real Data Integrity**:
   - Seluruh data yang tampil (rute, bus, tarif, jam berangkat/tiba, sisa kursi, status pesanan) berasal dari database MySQL aktual.
   - Tidak ada metrik palsu (jumlah penumpang fiktif, rating bintang palsu, testimoni karangan).
4. **Controlled Rhythm & Visual Breathing**:
   - Halaman panjang dikendalikan dengan ritme warna berstruktur: `white` → `light slate` (`#F8FAFC`) → `editorial photographic banner` → `deep navy` (`#0F172A`) → `white`.

---

## 2. Color System (Sistem Warna)

Sistem warna dibangun dengan identitas **Royal Blue & Deep Navy**, menjaga keseimbangan antara legitimasi maskapai/operator armada darat dan kemudahan pandang (*eye comfort*).

### A. Palet Inti
| Token Nama | Hex Code | Peran & Penggunaan Khusus |
|---|---|---|
| **Primary (Royal Blue)** | `#1D4ED8` | Tombol CTA utama, status aktif navigasi, aksen interaktif penting. |
| **Primary Dark** | `#1E40AF` | Hover state tombol CTA, header tabel aktif. |
| **Deep Navy** | `#0F172A` | Background Hero utama, container malam/footer, headline teks kontras tertinggi. |
| **Navy Accent** | `#172554` | Sub-panel navy, badge solid status netral gelap. |
| **Surface Background** | `#F8FAFC` | Latar belakang canvas halaman aplikasi, selang-seling section kontras. |
| **Card / Surface White** | `#FFFFFF` | Kontainer data, kartu jadwal, denah kabin kursi, form input. |
| **Hairline Border** | `#E2E8F0` | Garis batas tipis pembatas section, tabel border, divider navigasi. |
| **Muted Text** | `#64748B` | Label sekunder, waktu perjalanan, keterangan sub-jadwal, metadata rute. |
| **Dark Body Text** | `#0F172A` | Teks paragraf utama, nama kota, angka tarif, kode pesanan (tingkat kontras WCAG AAA). |

### B. Feedback & Status Sistem (Sesuai State Mesin Database)
| State Sistem | Hex Code | Background Tint | Border Tint | Penggunaan |
|---|---|---|---|---|
| **Pending** | `#D97706` (Warning Amber) | `#FFFBEB` | `#FDE68A` | Menunggu pembayaran / verifikasi tiket. |
| **Confirmed** | `#059669` (Success Emerald) | `#ECFDF5` | `#A7F3D0` | Pesanan diverifikasi, kursi terkunci aman. |
| **Completed** | `#1D4ED8` (Royal Blue) | `#EFF6FF` | `#BFDBFE` | Perjalanan telah selesai terlaksana. |
| **Cancelled** | `#DC2626` (Danger Crimson) | `#FEF2F2` | `#FECACA` | Pesanan dibatalkan / kedaluwarsa. |

### C. Aturan Larangan Penggunaan Warna:
- **Dilarang** mewarnai seluruh halaman menjadi biru solid. Biru hanya untuk focal point (titik aksi dan titik fokus pandangan).
- **Dilarang** menggunakan gradien pelangi atau gradien saturasi tinggi (misal pink-to-purple).

---

## 3. Typography & Hierarchy (Tipografi)

Font keluarga yang digunakan adalah **Plus Jakarta Sans** (dengan fallback `Inter, system-ui, -apple-system, sans-serif`).

| Level | Ukuran & Weight | Leading / Line Height | Tracking | Penggunaan |
|---|---|---|---|---|
| **Display** | `48px – 54px` (font-black) | `1.12` | `-0.025em` | Headline Hero Beranda |
| **H1** | `30px – 36px` (font-extrabold) | `1.2` | `-0.02em` | Judul Halaman Utama (/rute, /armada, /perjalanan) |
| **H2** | `22px – 26px` (font-bold) | `1.25` | `-0.015em` | Judul Section, Nama Rute Trayek, Detail Pesanan |
| **H3** | `16px – 18px` (font-bold) | `1.3` | normal | Nama Bus, Sub-heading modul, Label tahap pemesanan |
| **Body Lead** | `16px – 18px` (font-normal) | `1.6` | normal | Lead paragraf hero, deskripsi pengantar rute |
| **Body Regular** | `14px` (font-normal / font-medium) | `1.5` | normal | Teks umum, detail penumpang, FAQ content |
| **Small / Label** | `12px` (font-semibold / font-bold) | `1.4` | `+0.025em` | Label input form (Dari, Ke, Tanggal), badge status, header tabel |
| **Micro / Caption** | `11px` (font-bold uppercase) | `1.3` | `+0.05em` | Overline kategori rute, jam WIB, ID legal operasional |
| **Monospace** | `13px – 15px` (font-mono font-bold) | `1.2` | normal | Kode tiket (`ORD-CAN-...`), kode bus (`CAN-EX-01`), nomor kursi (`1A`, `2B`) |

---

## 4. Layout Grid & Spatial Rhythm (Tata Ruang & Jarak)

- **Max Container Width**: `max-w-7xl` (`1280px`) untuk halaman umum, `max-w-5xl` (`1024px`) untuk dashboard & booking, `max-w-3xl` (`768px`) untuk tiket digital/order detail.
- **Section Spacing**:
  - Desktop: `py-14 sm:py-16 lg:py-20`
  - Mobile: `py-10 sm:py-12`
- **Card & Data Container**:
  - Sudut melengkung yang terkontrol: `rounded-xl` (`12px`) atau `rounded-2xl` (`16px`). Tidak menggunakan pill/bulat ekstrem untuk kontainer persegi.
  - Border standar: `1px solid #E2E8F0`.
  - Shadow: sangat halus (`shadow-xs` atau `shadow-sm`). Tidak menggunakan drop shadow pekat/berlebihan.

---

## 5. Visual Asset Guidelines (Pedoman Fotografi Operasional)

1. **Fotografi Nyata Sebagai Arsitektur Layout**:
   - Gambar bus nyata digunakan pada Hero (`/public/images/hero/hero-bus.jpg`) dengan teknik overlay gelap tergradasi agar teks putih tetap terbaca jernih (rasio kontras > 7:1).
   - Foto thumbnail kota destinasi (`jakarta-thumb.jpg`, `semarang-thumb.jpg`, `jepara-thumb.jpg`, `bandung-thumb.jpg`, `yogyakarta-thumb.jpg`) disajikan dengan rasio editorial asimetris (16:9 atau 4:3), bukan lingkaran atau kartu avatar kecil.
2. **Ketiadaan Ilustrasi AI Kartun**:
   - Seluruh penjelasan sistem disajikan dalam format:
     - Tabel jadwal aktual
     - Denah tata letak kursi 2+2 kabin nyata
     - Diagram garis percabangan rute (node tree)
     - Timeline tahapan reservasi terstruktur

---

## 6. Komponen Inti Produk

### A. Search Engine Modul (Modul Pencari Jadwal)
- Desain mendatar (*horizontal strip*) pada desktop, bertumpuk bersih pada mobile.
- Terdiri atas:
  - Input Dari (Kota Asal — Dropdown dinamis dari database)
  - Tombol Swap (Tukar Kota)
  - Input Ke (Kota Tujuan — Dropdown dinamis dari database)
  - Input Tanggal Keberangkatan (Date picker format standar Indonesia)
  - Tombol Submit CTA: "Cari Perjalanan" (`#1D4ED8`)

### B. Bus Cabin Seat Map (Denah Kursi Bus)
- Menggambarkan kabin bus sungguhan:
  - Area depan: Pintu Depan & Ruang Pengemudi (*Driver Area*).
  - Konfigurasi lajur 2+2: Kursi A & B (sisi kiri), Gang koridor pejalan kaki, Kursi C & D (sisi kanan).
  - Setiap baris memiliki nomor baris yang simetris di tengah.
  - Legend jelas:
    - Putih border abu-abu: Tersedia
    - Biru Royal `#1D4ED8`: Dipilih (User selection)
    - Abu-abu coret: Terisi (Terkunci oleh transaksi lain)
  - Sticky summary bar: Menghitung total kursi dan total harga secara instan via Alpine.js.

### C. Digital Travel Document (Karcis / Tiket Digital)
- Menghilangkan kartu generic SaaS; disajikan seperti *boarding pass / official travel pass*:
  - Header resmi PO CAN Travel + Kode Pesanan Monospace Besar.
  - Rute Perjalanan terarah: Asal [Waktu Berangkat] → Tujuan [Waktu Tiba].
  - Rincian armada: Nama bus, kode bus, nomor kursi spesifik per nama penumpang.
  - Tombol cetak tiket (`window.print()`) dengan CSS print yang membersihkan navigasi dan footer.

---

## 7. Responsive Breakpoint Rules

| Breakpoint | Target Layar | Perlakuan Layout |
|---|---|---|
| `< 640px` (`sm`) | Smartphone (360px - 414px) | Satu kolom vertikal, topbar mini diringkas, tombol filter full-width, denah kursi memiliki horizontal scroll safe-zone. |
| `640px - 768px` | Tablet Portrait | Grid 2 kolom, search box semi-vertikal, sticky summary bottom bar. |
| `768px - 1024px` (`md/lg`) | Tablet Landscape & Laptop Kecil | Navbar penuh, search box 3 kolom mendatar, jadwal split view. |
| `> 1024px` (`lg/xl`) | Desktop Monitor | Grid asimetris editorial (misal 5:7 atau 4:8), foto armada resolusi tinggi, pohon relasi rute node-based. |
