# INFORMATION ARCHITECTURE — PO CAN TRAVEL
**Versi:** 2.0  
**Klasifikasi:** Official Intercity Bus Transportation System  
**Prinsip Desain:** *Information First, Predictable Hierarchy, Zero Dead Ends*

---

## 1. Peta Situs & Arsitektur Navigasi (Sitemap)

Arsitektur aplikasi terbagi menjadi 3 domain akses yang jelas: **Publik**, **Area Pelanggan (Customer)**, dan **Area Administrator (Admin)**.

```
PO CAN TRAVEL
│
├── 1. PUBLIC DOMAIN (Informasi, Eksplorasi, & Edukasi)
│   ├── / ............................ Beranda Utama (11 Section Berstruktur Transportasi)
│   ├── /perjalanan .................. Pusat Pencarian & Jadwal Perjalanan
│   │   └── /perjalanan/{trip} ....... Detail Perjalanan Publik
│   ├── /rute ........................ Direktori Jaringan Rute Antarkota
│   │   └── /rute/{route} ............ Detail Rute, Estimasi Waktu & Jadwal Terkait
│   ├── /armada ...................... Katalog Armada Bus Resmi & Layout Kursi
│   │   └── /armada/{bus} ............ Spesifikasi Armada, Denah Kabin & Jadwal Aktif
│   ├── /tentang ..................... Profil Operasional & Standar Layanan
│   ├── /cara-pemesanan .............. Panduan Alur Pemesanan Langkah Demi Langkah
│   ├── /faq ......................... Pusat Bantuan & Pertanyaan Umum Berdasarkan Sistem
│   ├── /login ....................... Masuk Akun
│   └── /register .................... Registrasi Pelanggan Baru
│
├── 2. CUSTOMER DOMAIN (Transaksi, Tiket, & Perjalanan Saya)
│   ├── /customer/dashboard .......... Dasbor Pelanggan (Pesanan Aktif & Riwayat Terdekat)
│   ├── /customer/trips .............. Pencarian Jadwal & Pemilihan Perjalanan
│   │   ├── /{trip} .................. Detail Perjalanan (Asal, Tujuan, Bus, Harga, Fasilitas)
│   │   ├── /{trip}/seats ............ Pemilihan Kursi Interaktif (Denah Kabin 2+2)
│   │   └── /{trip}/booking .......... Formulir Pengisian Data Penumpang & Konfirmasi Tiket
│   └── /customer/orders ............. Riwayat Seluruh Pesanan (Filter Status & Search)
│       └── /{order} ................. Karcis / Tiket Digital Resmi (Cetak / Simpan)
│
└── 3. ADMIN DOMAIN (Operasional, Manajemen Armada, & Verifikasi)
    ├── /admin/dashboard ............. Dasbor Operasional (Pesanan Pending Butuh Tindakan & Jadwal Hari Ini)
    ├── /admin/buses ................. Manajemen Armada (Data Kendaraan, Kode, Total Kursi)
    ├── /admin/routes ................ Manajemen Rute Trayek (Asal, Tujuan, Durasi Jam/Menit)
    ├── /admin/trips ................. Manajemen Jadwal Perjalanan (Penjadwalan Bus, Waktu, & Tarif)
    ├── /admin/orders ................ Verifikasi & Pembaruan Status Pesanan (Pending → Confirmed → Completed / Cancelled)
    └── /admin/users ................. Manajemen Pengguna Sistem
```

---

## 2. Struktur Navigasi (Navbar & Footer)

### A. Desktop Navigation
- **Kiri**: Logo Resmi PO CAN Travel (Teks tegas dengan aksen Royal Blue) + Subtitle "Layanan Antarkota".
- **Tengah (Menu Utama)**:
  - Beranda (`/`)
  - Perjalanan (`/perjalanan`)
  - Rute (`/rute`)
  - Armada (`/armada`)
  - Cara Memesan (`/cara-pemesanan`)
  - Tentang (`/tentang`)
- **Kanan**:
  - Tombol Ikon Pencarian Cepat
  - *Jika Belum Login (Guest)*:
    - Tombol "Masuk" (Outline tipis slate)
    - Tombol "Daftar" (Solid Royal Blue `#1D4ED8`)
  - *Jika Login Sebagai Pelanggan*:
    - Pesanan Saya (`/customer/orders`)
    - Dasbor Saya (`/customer/dashboard`)
    - Menu Profil & Tombol Logout
  - *Jika Login Sebagai Admin*:
    - Dasbor Admin (`/admin/dashboard`)
    - Kelola Pesanan (`/admin/orders`)
    - Tombol Logout

### B. Mobile Navigation (Drawer yang Ergonomis)
- Header ringkas: Logo, tombol menu hamburger, dan tombol cepat "Masuk / Akun".
- Drawer menu slide-in dengan pengelompokan yang jelas:
  1. Menu Eksplorasi (Beranda, Perjalanan, Rute, Armada)
  2. Menu Panduan (Cara Pesan, FAQ, Info Keberangkatan)
  3. Menu Akun Pelanggan / Akses Cepat Tiket

### C. Footer Arsitektural Transportasi
Disusun dalam 4 kolom informatif tanpa link kosong:
1. **Identitas Perusahaan**: PO CAN Travel, deskripsi layanan antarkota resmi, komitmen keselamatan dan ketepatan waktu.
2. **Layanan Perjalanan**:
   - Cari Perjalanan
   - Jaringan Rute
   - Katalog Armada
3. **Pusat Informasi**:
   - Cara Memesan Tiket
   - Panduan Keberangkatan
   - Pertanyaan Umum (FAQ)
   - Tentang Kami
4. **Area Pelanggan & Keamanan**:
   - Masuk / Daftar Akun
   - Pesanan Saya
   - Status & Validasi Tiket Digital
   - Hak Cipta Resmi & Kebijakan Data

---

## 3. Cetak Biru Beranda (11 Sections Architecture)

Beranda dirancang mengikuti urutan psikologis penumpang bus antarkota:

| No | Section | Tujuan Informasi | Elemen Kunci | Data Source |
|---|---|---|---|---|
| **01** | **Hero & Search Engine** | Menangkap kebutuhan utama: penumpang ingin segera mencari rute & waktu keberangkatan. | Background foto bus nyata, headline tegas "Perjalanan antarkota, lebih mudah dipesan", form pencarian horizontal (Dari, Ke, Tanggal, CTA Cari). | Database Routes (Origins, Destinations) |
| **02** | **Pencarian Cepat (Route Shortcuts)** | Memberikan jalan pintas instan untuk rute terpopuler penumpang tanpa mengetik. | Shortcut tags: Jakarta → Jepara, Jepara → Jakarta, Jakarta → Semarang. Sekali klik langsung memfilter jadwal. | Data Rute Utama |
| **03** | **Jadwal Perjalanan Aktual** | Memberikan bukti nyata bahwa layanan aktif dan tiket siap dipesan. | Tabel kartu jadwal: Kota Asal, Jam Berangkat, Kota Tujuan, Jam Tiba, Durasi, Bus yang bertugas, Sisa Kursi, Harga resmi, Tombol "Pilih Kursi". | Database Trips (status scheduled, departure >= now) |
| **04** | **Jaringan Rute (Node Visualization)** | Memberi gambaran konektivitas trayek tanpa membingungkan penumpang. | Visualisasi simpul jaringan (misal Jakarta menghubungkan Jepara & Semarang), daftar durasi dan tarif awal. | Database Routes & Trips Min Price |
| **05** | **Katalog Armada Resmi** | Membangun keyakinan terhadap kualitas fisik dan kelayakan armada. | Foto bus nyata ukuran proporsional, nama armada (CAN Executive 01), kode unit, kapasitas kursi total, dan skema denah kabin. | Database Buses, Bus Images, Seats |
| **06** | **Timeline Pengalaman Perjalanan** | Mengedukasi alur transaksi agar calon penumpang tidak ragu. | 5 Tahap linear: 1. Cari Jadwal → 2. Pilih Kursi Mandiri → 3. Isi Data Penumpang → 4. Konfirmasi Tiket → 5. Akses Tiket Digital. | Dokumentasi Sistem |
| **07** | **Pusat Informasi Perjalanan** | Menyiapkan penumpang sebelum menuju ke terminal. | Checklist sebelum berangkat (cek tanggal, cek terminal asal/tujuan, bawa identitas) + Penjelasan arti 4 status pesanan (Pending, Confirmed, Completed, Cancelled). | Aturan Operasional PO CAN Travel |
| **08** | **Tentang PO CAN Travel (Editorial Layout)** | Memperkenalkan legalitas dan standar perusahaan transportasi secara elegan. | Layout 2 kolom: foto nyata operasional di sisi kiri, profil perusahaan dan filosofi pelayanan di sisi kanan. | Data Resmi PO CAN Travel |
| **09** | **Pusat Bantuan & FAQ** | Mengurangi beban CS dengan menjawab pertanyaan kritis. | Akordeon terstruktur: Pemesanan, Pemilihan Kursi, Verifikasi Tiket, & Kebijakan Keberangkatan. | Standar Operasional |
| **10** | **Final Action Banner** | Memberi penutup yang mengarahkan kembali ke tujuan utama. | "Sudah menentukan tujuan perjalanan Anda?", Tombol besar: "Cari Jadwal Perjalanan". | Internal Link /perjalanan |
| **11** | **Footer Resmi** | Navigasi sekunder & pengesahan hukum digital. | Grid 4 kolom lengkap dengan legalitas dan saluran bantuan pelanggan. | Layout Footer |

---

## 4. Alur Transaksi Pelanggan (Customer Flow)

```
[ Beranda / Rute / Perjalanan ]
         │
         ▼
[ Pilih Jadwal Perjalanan ] ─── Cek Waktu, Armada & Tarif
         │
         ▼
[ Pilih Kursi Sendiri ] ────── Denah Kursi 2+2 (Real-time locked seat check)
         │                     Pilih 1 s.d. beberapa kursi sesuai kebutuhan
         │
         ▼
[ Isi Data Penumpang ] ─────── Validasi Nama & No. Identitas per Kursi
         │                     Ringkasan Biaya Berdasarkan Database Trip Price
         │
         ▼
[ Konfirmasi Pesanan ] ─────── Transaksi Database Atomik (DB Transaction)
         │                     Status: PENDING
         │
         ▼
[ Tiket Digital Diterbitkan ] ─ Tampil di /customer/orders/{order}
                               Download / Cetak Dokumen Tiket
```

---

## 5. Arsitektur Konsol Operasional Admin

Konsol admin dirancang murni untuk **utilitas kerja cepat**, bukan dashboard marketing:
1. **Urutan Prioritas Layar**:
   - Baris 1: Tabel Pesanan yang Membutuhkan Perhatian Segera (Status *Pending* yang perlu diverifikasi atau dibatalkan).
   - Baris 2: Jadwal Keberangkatan Hari Ini (Menampilkan bus yang bertugas, jam berangkat, dan okupansi kursi penumpang).
   - Baris 3: Ringkasan Kapasitas Armada & Manajemen Trayek.
2. **Zero Vanity Data**:
   - Dilarang menampilkan diagram garis omzet palsu atau proyeksi pertumbuhan jika tidak ada sumber data pembukuan.
   - Fokus murni pada siklus pesanan: Validasi ID Pelanggan, update status, dan pengawasan ketersediaan kursi bus.
