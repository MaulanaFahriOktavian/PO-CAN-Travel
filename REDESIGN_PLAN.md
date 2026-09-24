# REDESIGN & IMPLEMENTATION PLAN — PO CAN TRAVEL
**Versi:** 2.0  
**Status:** Menunggu Persetujuan User (Awaiting User Approval)  
**Target:** Transformasi PO CAN Travel menjadi Platform Transportasi Antarkota Resmi yang Matang & Profesional.

---

## 1. Hasil Audit Desain & Arsitektur Saat Ini

### A. Masalah Desain & Pola Lama yang Ditinggalkan:
1. **Kesan "AI Template" & Landing Page SaaS**:
   - Terdapat sisa elemen card bersusun, badge berlebih, dan layout yang menyerupai template produk digital generic daripada website perusahaan otobus resmi.
2. **Hero Terlalu Dominan Tanpa Efisiensi Pencarian**:
   - Headline dan ruang kosong menyita layar tanpa menghubungkan pengguna secara langsung dengan jadwal aktual di bawahnya.
3. **Katalog Armada Belum Menjadi Aset Utama**:
   - Informasi fisik bus (jumlah kursi, tata letak denah, gambar armada) masih terpecah dan belum memberikan gambaran teknis yang meyakinkan penumpang.
4. **Pola Card Berulang**:
   - Terlalu banyak komponen kotak berulang (card dalam card) yang membuat pengguna lelah memindai (*visual fatigue*).
5. **Kurangnya Panduan & Edukasi Operasional**:
   - Calon penumpang memerlukan panduan nyata mengenai apa yang harus dilakukan sebelum naik bus di terminal dan pemahaman atas status tiket mereka.

### B. Pondasi Kuat yang Harus Dipertahankan 100%:
- **Integritas Logic & Database**:
  - Laravel 10 monolith dengan Eloquent ORM.
  - Alur transaksi atomik (`DB::transaction`) dengan pencegahan double booking kursi (*seat conflict lock*).
  - Snapshot harga historis pada tabel `order_items`.
  - Sistem peran otorisasi ketat: `guest`, `customer`, `admin`.
  - 242 unit & feature test yang sudah ada dan berstatus 100% PASS.

---

## 2. Struktur Website & Navigasi Baru

### A. Arsitektur Domain Publik
- `/` — Beranda Utama (11 Section Berstruktur Transportasi Terpadu).
- `/perjalanan` — Katalog & Mesin Pencari Jadwal Perjalanan (Pencarian Asal, Tujuan, Tanggal, dan Filter).
- `/rute` — Direktori Jaringan Rute Trayek (Dikelompokkan berdasarkan kota asal dengan data durasi dan jadwal aktual).
- `/armada` — Direktori Armada Bus Resmi (Spesifikasi teknis, foto kendaraan nyata, kapasitas, dan layout kursi).
- `/cara-pemesanan` — Panduan Visual Alur Pemesanan 5 Langkah.
- `/tentang` — Informasi Resmi Perusahaan PO CAN Travel & Standar Operasional.
- `/faq` — Tanya Jawab berdasarkan fitur sistem aktual (Pemesanan, Kursi, Jadwal, Verifikasi Tiket).

### B. Arsitektur Area Pelanggan (Customer)
- `/customer/dashboard` — "My Travel Dashboard": Menampilkan tiket aktif yang menunggu keberangkatan/pembayaran dan riwayat perjalanan.
- `/customer/trips/{trip}` — Halaman Keputusan Perjalanan (Rincian waktu, armada yang melayani, harga, dan sisa kursi).
- `/customer/trips/{trip}/seats` — Pemilihan Kursi Interaktif (Skema kabin bus nyata 2+2 dengan indikator pengemudi dan koridor tengah).
- `/customer/trips/{trip}/booking` — Ringkasan Perjalanan & Formulir Data Penumpang per Nomor Kursi.
- `/customer/orders` — Riwayat Lengkap Seluruh Pemesanan dengan Pencarian Kode Tiket.
- `/customer/orders/{order}` — Dokumen Perjalanan Digital (Karcis Bus Resmi yang Siap Dicetak).

### C. Arsitektur Area Administrator (Admin)
- `/admin/dashboard` — Konsol Kerja Operasional: Prioritas verifikasi pesanan pending dan jadwal bus hari ini.
- `/admin/buses` — Manajemen Kendaraan & Konfigurasi Kursi.
- `/admin/routes` — Manajemen Trayek & Durasi.
- `/admin/trips` — Manajemen Jadwal Keberangkatan & Tarif Resmi.
- `/admin/orders` — Validasi Tiket & Perubahan Status Pesanan.

---

## 3. Struktur 11 Section Beranda Baru

1. **Section 1: Hero & Search Module**
   - Latar belakang foto bus resolusi tinggi penuh dengan scrim gradien gelap untuk keterbacaan tipografi maksimal.
   - Headline editorial: *"Perjalanan antarkota, lebih mudah dipesan."*
   - Modul pencarian horizontal: Dari, Tombol Tukar, Ke, Tanggal Keberangkatan, CTA *"Cari Perjalanan"*.
2. **Section 2: Pencarian Cepat (Route Shortcuts)**
   - Jalur pintas cepat untuk rute ramai: `Jakarta → Jepara`, `Jepara → Jakarta`, `Jakarta → Semarang`.
   - Satu klik langsung mengarahkan dan memfilter jadwal keberangkatan.
3. **Section 3: Jadwal Perjalanan Aktual (Live Database Trips)**
   - Menampilkan perjalanan berstatus `scheduled` dengan waktu keberangkatan mendatang.
   - Data lengkap: Kota Asal, Jam Berangkat, Kota Tujuan, Jam Tiba, Estimasi Durasi, Bus yang Bertugas, Sisa Kursi Tersedia, Harga Tiket Resmi, CTA *"Pilih Kursi"*.
4. **Section 4: Jaringan Rute (Node Tree Connectivity)**
   - Representasi visual hierarki rute antarkota berdasarkan data database trayek.
   - Setiap simpul dapat diklik untuk membuka jadwal terkait.
5. **Section 5: Armada PO CAN Travel**
   - Menampilkan armada nyata dari database (`Bus`).
   - Format: Foto kendaraan nyata + data teknis (Nama Bus, Kode Bus, Jumlah Kursi, Skema Konfigurasi 2+2).
6. **Section 6: Pengalaman Perjalanan (Visual Timeline)**
   - Garis waktu horizontal 5 tahap reservasi: Cari → Pilih Kursi → Data Penumpang → Konfirmasi → Tiket Digital.
7. **Section 7: Pusat Informasi Perjalanan**
   - Panduan sebelum berangkat ke terminal (pengecekan tanggal, lokasi terminal, identitas diri, kode tiket).
   - Penjelasan transparan makna 4 status pesanan (`Pending`, `Confirmed`, `Completed`, `Cancelled`).
8. **Section 8: Tentang PO CAN Travel (Editorial Asymmetric)**
   - Layout editorial 2 sisi: Foto operasional perjalanan berdampingan dengan profil legal dan komitmen layanan.
9. **Section 9: Pertanyaan Umum (FAQ Berdasarkan Sistem)**
   - Akordeon tanya jawab jujur seputar alur booking, pemilihan kursi, metode verifikasi tiket, dan kebijakan pembatalan.
10. **Section 10: Final Booking Area**
    - Penutup ramah: *"Sudah menentukan tujuan perjalanan Anda?"* dengan tombol CTA *"Cari Jadwal Perjalanan"*.
11. **Section 11: Footer Resmi Perusahaan Transportasi**
    - Grid 4 kolom profesional: Profil Perusahaan, Menu Perjalanan, Informasi & Panduan, dan Akses Pelanggan Resmi.

---

## 4. Perubahan Pengalaman Pengguna (Customer & Admin Experience)

### A. Customer Experience (CX):
- **Predictable Discovery**: Pengguna tidak lagi bingung mencari rute atau armada; seluruh menu utama di navbar langsung membawa ke direktori yang relevan.
- **Seat Map Ergonomics**: Denah pemilihan kursi dirancang menyerupai kabin asli dengan nomor baris di tengah koridor, tombol besar yang mudah ditekan di layar sentuh, dan sticky bar ringkasan biaya.
- **Digital Boarding Pass**: Detail pesanan diformat seperti karcis bus sungguhan lengkap dengan stempel status, rincian tiap kursi dan nama penumpang, serta mode ramah cetak (`window.print()`).

### B. Admin Experience (AX):
- **Zero Fluff**: Tidak ada grafik penjualan imajiner atau kartu metrik hampa.
- **Action-Oriented Dashboard**: Pesanan *Pending* yang membutuhkan konfirmasi diletakkan di posisi paling atas agar langsung dapat ditindaklanjuti.
- **Fleet & Trip Quick Monitor**: Memantau tingkat keterisian kursi per bus pada jadwal keberangkatan hari ini.

---

## 5. Fitur Baru vs. Fitur yang Sengaja Dikecualikan (Future Features)

### A. Fitur Baru yang Diimplementasikan:
- Route quick shortcuts (pencarian cepat 1-klik).
- Visual node tree jaringan rute.
- Direktori armada lengkap dengan skema layout kursi teknis.
- Checklist persiapan keberangkatan penumpang & panduan status tiket.
- Filter pencarian dan sorting jadwal perjalanan.
- Tampilan digital travel pass yang siap dicetak.

### B. Fitur yang Sengaja TIDAK Dibuat Sekarang (Future Features / Catatan Roadmap):
- **Payment Gateway Otomatis** (Midtrans/Xendit): Sistem saat ini menggunakan alur verifikasi pesanan oleh admin (`pending` → `confirmed`).
- **Live GPS Bus Tracking**: Belum ada integrasi perangkat telematika GPS pada bus fisik.
- **WhatsApp Bot Gateway**: Belum tersedia webhook atau API resmi WhatsApp Business.
- **Sistem Refund & Reschedule Mandiri**: Memerlukan kebijakan keuangan operasional dan tabel log pengembalian dana khusus.
- **Ulasan & Rating Bintang**: Ditiadakan karena dilarang membuat fake review / rating palsu.

---

## 6. Rencana Implementasi Bertahap (Phase A – Phase H)

```
[ Phase A: Global Design System ]
  ├── Penyelarasan Palet Royal Blue (#1D4ED8) & Deep Navy (#0F172A)
  ├── Tipografi Plus Jakarta Sans terstruktur
  └── Pembaruan Navbar & Footer resmi PO CAN Travel
        │
        ▼
[ Phase B: Public Information Architecture ]
  ├── Penyempurnaan rute publik (/perjalanan, /rute, /armada, /tentang, /cara-pemesanan, /faq)
  └── Integrasi breadcrumb & meta title resmi
        │
        ▼
[ Phase C: Homepage Rebuild ]
  ├── Pembangunan ulang 11 Section Beranda sesuai urutan Information First
  ├── Integrasi data rute, trip aktual, dan armada aktif dari database
  └── Penghapusan seluruh elemen card-in-card dan AI template generic
        │
        ▼
[ Phase D: Route & Fleet Directory Rebuild ]
  ├── Halaman /rute: Route Directory dengan pengelompokan kota asal
  └── Halaman /armada: Spesifikasi armada teknis & preview tata letak kursi 2+2
        │
        ▼
[ Phase E: Customer Experience Rebuild ]
  ├── Halaman /customer/trips & /customer/trips/{trip} (Trip decision page)
  ├── Halaman /customer/trips/{trip}/seats (Interactive 2+2 cabin map)
  ├── Halaman /customer/trips/{trip}/booking (Passenger forms)
  └── Halaman /customer/orders/{order} (Digital Travel Document ticket)
        │
        ▼
[ Phase F: Admin Experience Rebuild ]
  ├── Halaman /admin/dashboard (Operational console: Pending orders first)
  └── Penguatan konsistensi tabel operasional armada, rute, dan jadwal
        │
        ▼
[ Phase G: Responsive Design & Accessibility ]
  ├── Pengujian tampilan pada 360px, 390px, 768px, 1024px, 1280px, 1440px
  ├── Pencegahan horizontal overflow (100% overflow-safe)
  └── Standar kontras teks WCAG AAA & ukuran target sentuh tombol >= 44px
        │
        ▼
[ Phase H: Testing, Smoke Verification, & Visual QA ]
  ├── Eksekusi penuh php artisan test (Memastikan 242+ tests tetap 100% PASS)
  ├── Eksekusi npm run build (Vite asset packaging sukses)
  └── Pengujian smoke HTTP di browser lokal
```

---

## 7. Analisis Risiko & Mitigasi Teknis

| Risiko | Potensi Dampak | Mitigasi |
|---|---|---|
| **Kerusakan Sesi Pemilihan Kursi** | Pelanggan gagal memesan tiket saat beralih halaman kursi ke formulir penumpang. | Menjaga struktur session key `selected_seats` dan validasi controller `SeatSelectionController` tetap 100% utuh tanpa modifikasi payload. |
| **Double Booking / Race Condition Kursi** | Kursi yang sama terbeli oleh dua pengguna berbeda secara bersamaan. | Tetap mengandalkan query atomik dan lock di level database (`OrderItem::join('orders', ...)` dengan status `pending`, `confirmed`, `completed`). |
| **Regresi Test Suite (242 Tests)** | Kegagalan uji otomatis jika ada id input atau URL yang berubah nama. | Semua atribut form, nama route (`route('customer.trips.seats')`, dll.), dan nama field database dipertahankan secara presisi. |
| **Overflow Pada Layar Kecil (360px)** | Tampilan terpotong atau horizontal scroll tidak diinginkan. | Menggunakan CSS grid responsif, flex-wrap pada metadata, dan kontainer denah kursi yang terisolasi dengan aman. |
