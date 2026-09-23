# PO CAN Travel

Sistem informasi dan platform pemesanan tiket bus antarkota berbasis web yang dikembangkan menggunakan **Laravel 10**, **Blade**, **Tailwind CSS**, **Alpine.js**, dan **MySQL**.

---

## Tentang Project

**PO CAN Travel** adalah aplikasi pemesanan tiket bus sederhana yang dibangun sebagai bagian dari uji teknis seleksi Full-Stack / Backend Intern di **CAN Creative**. Sistem ini mencakup alur lengkap operasional bus antarkota: mulai dari pengelolaan master data armada, rute, dan jadwal oleh administrator, hingga pencarian tiket, pemilihan denah kursi bus secara interaktif, pengisian identitas penumpang, pencegahan *double booking* berbasis database row locking, hingga riwayat dan manajemen status pesanan.

Aplikasi ini menggunakan arsitektur server-rendered monolitik dengan sesi autentikasi native Laravel tanpa dependensi framework frontend SPA yang berlebihan.

---

## Fitur Aplikasi

### 1. Pelanggan (Customer)
- **Registrasi Akun**: Pendaftaran akun pelanggan baru dengan validasi format dan enkripsi kata sandi.
- **Login & Logout**: Autentikasi sesi aman dengan pengalihan otomatis berdasarkan peran.
- **Pencarian Jadwal Perjalanan**: Filter jadwal bus berdasarkan kota asal, kota tujuan, dan tanggal keberangkatan.
- **Detail Perjalanan**: Informasi jadwal, estimasi durasi tempuh, nama armada bus, dan tarif per kursi.
- **Pemilihan Kursi Interaktif**: Denah kursi bus 2-2 interaktif menggunakan Alpine.js dengan indikator status (*tersedia*, *dipilih*, *sudah dipesan*) dan kalkulasi estimasi total biaya secara realtime.
- **Pengisian Data Penumpang**: Formulir pengisian nama lengkap dan nomor identitas resmi (KTP/SIM/Paspor) untuk setiap kursi yang dipilih.
- **Pemesanan Tiket & Row Locking**: Transaksi atomik dengan penguncian baris (*pessimistic locking*) untuk mencegah reservasi ganda (*race condition*) pada kursi yang sama.
- **Detail Tiket Pesanan**: Rincian nomor pesanan unik (`order_code`), status, rute, waktu, daftar penumpang, dan snapshot harga.
- **Riwayat Pesanan**: Daftar seluruh pesanan milik akun pelanggan dengan tab filter status (*Semua*, *Pending*, *Confirmed*, *Completed*, *Cancelled*) dan penomoran halaman (*pagination*).

### 2. Administrator (Admin)
- **Dasbor Admin**: Pusat informasi dan navigasi cepat menuju seluruh modul operasional.
- **Kelola Armada Bus**: Tambah, edit, dan hapus data armada bus dengan sinkronisasi kapasitas kursi secara otomatis. Penurunan jumlah kursi dilindungi verifikasi riwayat pesanan.
- **Kelola Rute Perjalanan**: Tambah, edit, dan hapus rute perjalanan antarkota beserta estimasi durasi tempuh dalam menit.
- **Kelola Jadwal Perjalanan**: Penjadwalan trip bus, pemilihan rute dan armada, penetapan tarif tiket, serta pembaruan status jadwal (*scheduled*, *departed*, *completed*, *cancelled*).
- **Kelola Pesanan Pelanggan**:
  - Daftar seluruh pesanan pelanggan dari semua perjalanan.
  - Pencarian fleksibel berdasarkan kode pesanan, nama pelanggan, maupun alamat email.
  - Filter pesanan berdasarkan status transaksi dengan paginasi.
  - Detail komprehensif pesanan pelanggan beserta rincian kursi dan identitas penumpang.
  - Pembaruan status pesanan yang dikontrol oleh mesin transisi status (*state transition rules*).

---

## Tech Stack

- **Framework**: Laravel 10
- **Bahasa Pemrograman**: PHP 8.1+
- **Database**: MySQL (InnoDB Engine)
- **Frontend Template**: Laravel Blade
- **Styling / CSS**: Tailwind CSS (via Vite)
- **Interaktivitas UI**: Alpine.js
- **Asset Bundler**: Vite
- **ORM**: Eloquent ORM
- **Testing Framework**: PHPUnit

---

## Requirements

Sebelum menjalankan aplikasi, pastikan sistem komputer Anda telah terinstal:
- PHP >= 8.1 dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer >= 2.0
- Node.js >= 18.0 & npm
- MySQL Server >= 8.0 (atau MariaDB >= 10.4)

---

## Panduan Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lingkungan lokal:

1. **Clone Repository**
   ```bash
   git clone https://github.com/MaulanaFahriOktavian/PO-CAN-Travel.git
   cd PO-CAN-Travel
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Salin Berkas Lingkungan (.env)**
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Encryption Key**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi Database**
   Buka berkas `.env` dan sesuaikan parameter koneksi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=po_can_travel
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *Pastikan database dengan nama tersebut sudah dibuat terlebih dahulu di server MySQL Anda.*

6. **Migrasi Database & Seeder Data Awal**
   ```bash
   php artisan migrate --seed
   ```

7. **Instal Dependensi Node.js & Build Asset Frontend**
   ```bash
   npm install
   npm run build
   ```

8. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   Buka peramban (*browser*) dan akses aplikasi di: `http://localhost:8000`

---

## Akun Bawaan (Default Accounts)

Database seeder telah menyediakan dua akun untuk keperluan demonstrasi dan pengujian lokal:

| Peran (Role) | Alamat Email | Kata Sandi | Akses Halaman |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@pocantravel.com` | `password123` | `/admin/dashboard` |
| **Customer** | `customer@pocantravel.com` | `password123` | `/customer/dashboard` |

> *Catatan: Kredensial di atas khusus digunakan pada lingkungan pengembangan (development) lokal.*

---

## Struktur Database & Relasi Utama

Relasi antar-tabel utama di dalam sistem PO CAN Travel:

```text
User ──< Order ──< OrderItem >── Seat >── Bus
           │
         Trip >── Route
           │
          Bus
```

- **User &rarr; Order**: Satu pelanggan dapat membuat banyak pesanan tiket.
- **Order &rarr; OrderItem**: Satu pesanan tiket terdiri atas satu atau beberapa penumpang/kursi.
- **Seat &rarr; Bus**: Setiap kursi terhubung ke satu armada bus dengan nomor unik per bus.
- **Trip &rarr; Bus & Route**: Setiap perjalanan menjadwalkan satu armada bus pada satu rute tertentu.
- **OrderItem &rarr; Seat**: Setiap tiket menempati satu kursi bus dan merekam snapshot harga pada saat transaksi.

Detail dokumentasi skema dan diagram visual dapat dilihat pada:
- [Entity Relationship Diagram (Mermaid)](docs/ERD.md)
- [Dokumentasi Lengkap Skema Database](docs/DATABASE.md)
- [Arsitektur Aplikasi & Desain Konkurensi](docs/ARCHITECTURE.md)

---

## Aturan Bisnis Penting (Business Rules)

1. **Kapasitas Kursi Bus**: Satu armada bus memiliki kapasitas kursi tetap. Kursi diberi nomor otomatis berformat baris-kolom (1A, 1B, 1C, 1D...).
2. **Jadwal Perjalanan**: Satu trip mengombinasikan satu bus dan satu rute. Waktu kedatangan (`arrival_at`) harus selalu setelah waktu keberangkatan (`departure_at`).
3. **Kepemilikan Pesanan**: Setiap pesanan wajib dimiliki oleh customer yang terautentikasi dan tidak dapat diakses oleh customer lain.
4. **Pencegahan Double Booking**: Kursi yang sudah terikat pada pesanan aktif berstatus `pending`, `confirmed`, atau `completed` tidak dapat dipesan kembali oleh pelanggan lain pada trip yang sama. Perlindungan ini ditegakkan di level database menggunakan transaksi atomik dan `lockForUpdate()`.
5. **Snapshot Harga Historis**: Tarif tiket pada saat reservasi disimpan langsung pada `order_items.price`. Perubahan tarif perjalanan di masa mendatang tidak akan mengubah nilai historis transaksi yang sudah terbentuk.
6. **Kalkulasi Total di Server**: Total harga pesanan (`total_amount`) dihitung sepihak oleh server (`jumlah_kursi × trip.price`), mengabaikan manipulasi harga dari sisi client.
7. **Mesin Transisi Status Pesanan**:
   - `pending` &rarr; `confirmed`, `cancelled`
   - `confirmed` &rarr; `completed`, `cancelled`
   - `completed` &rarr; *Status final (tidak dapat diubah kembali)*
   - `cancelled` &rarr; *Status final (tidak dapat diubah kembali)*
   - Setiap upaya perubahan status yang tidak sah akan ditolak di server.

---

## Pengujian Otomatis (Automated Testing)

Aplikasi dilengkapi dengan pengujian fitur komprehensif (*feature tests*) mencakup autentikasi, otorisasi RBAC, transaksi pemesanan, validasi input, pencegahan *race condition*, dan transisi status.

Untuk menjalankan seluruh test suite:

```bash
php artisan test
```

Hasil verifikasi pengujian:
```text
Tests:    227 passed (635 assertions)
Duration: ~20s
Status:   ALL GREEN
```

Untuk menguji build aset frontend:
```bash
npm run build
```

---

## Struktur Direktori Utama

```text
PO_CAN_Travel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Bus, Route, Trip, Order Controllers
│   │   │   ├── Customer/      # Trip, SeatSelection, Order Controllers
│   │   │   └── AuthController.php
│   │   ├── Middleware/        # RoleMiddleware & Authentication
│   │   └── Requests/          # Form Requests Admin & Customer
│   └── Models/                # Bus, Seat, Route, Trip, Order, OrderItem, User
├── database/
│   ├── migrations/            # Skema tabel database (7 tabel domain)
│   └── seeders/               # Data awal rute, armada, jadwal, & user demo
├── docs/                      # Dokumentasi ERD, Database, & Arsitektur
│   ├── ARCHITECTURE.md
│   ├── DATABASE.md
│   └── ERD.md
├── resources/
│   ├── css/                   # Tailwind CSS styling
│   ├── js/                    # JavaScript entrypoint
│   └── views/                 # Blade template (layouts, admin, customer, auth)
├── routes/
│   └── web.php                # Web routes berstruktur grup middleware
└── tests/
    └── Feature/               # 10 file pengujian fitur otomatis (227 tests)
```

---

## Panduan Deployment ke Lingkungan Production

Jika aplikasi hendak dideploy ke lingkungan server produksi, pastikan konfigurasi berikut diterapkan:

1. **Konfigurasi Environment (.env)**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://nama-domain-anda.com
   ```
   > **PENTING**: Selalu pastikan `APP_DEBUG=false` pada production agar detail pengecualian, jejak stack trace, dan data konfigurasi server tidak terekspos kepada publik.

2. **Optimasi Cache Framework**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Kompilasi Aset Frontend**:
   ```bash
   npm ci
   npm run build
   ```

4. **Migrasi Database Production**:
   ```bash
   php artisan migrate --force
   ```

---

## Batasan Sistem & Rencana Integrasi Mendatang (Limitations & Roadmap)

Secara arsitektural dan fungsional, fondasi sistem transaksi pemesanan tiket, konkurensi kursi (*pessimistic locking*), hak akses (*RBAC*), dan antarmuka pengguna telah siap digunakan (*production-ready foundation*). Namun demikian, untuk implementasi komersial berskala penuh, beberapa modul eksternal berikut dirancang sebagai langkah integrasi selanjutnya:

1. **Payment Gateway Otomatis**:
   - *Status Saat Ini*: Siklus pembayaran tiket saat ini menggunakan verifikasi manual oleh administrator (`pending` &rarr; `confirmed`).
   - *Rencana Mendatang*: Integrasi payment gateway (seperti Midtrans, Xendit, atau DOKU) menggunakan webhook callback untuk konfirmasi otomatis instan (Virtual Account, QRIS, E-Wallet).
2. **Notifikasi Otomatis (Omnichannel)**:
   - *Status Saat Ini*: Konfirmasi tiket dan kode pesanan dapat dipantau langsung pada dasbor dan riwayat pesanan akun pelanggan.
   - *Rencana Mendatang*: Pengiriman tiket elektronik berbentuk PDF via Email (Laravel Mailables) dan notifikasi status pemesanan via WhatsApp Business API.
3. **Pembaruan Kursi Realtime (WebSockets)**:
   - *Status Saat Ini*: Ketersediaan kursi diperiksa secara atomik di server saat halaman diakses dan dikunci dengan `lockForUpdate()` saat pemesanan disubmit.
   - *Rencana Mendatang*: Integrasi Laravel Echo dan Pusher/Reverb untuk memutakhirkan denah kursi secara langsung (*real-time*) di browser tanpa memuat ulang halaman.
4. **Verifikasi Tiket di Terminal Keberangkatan**:
   - *Status Saat Ini*: Pemeriksaan manifes penumpang dilakukan oleh administrator melalui menu Kelola Pesanan.
   - *Rencana Mendatang*: Penerbitan kode QR pada detail pesanan dan aplikasi pemindai (*scanner*) untuk staf loket/kondektur di terminal.

---

## Lisensi

Proyek ini dikembangkan untuk keperluan pengujian dan evaluasi teknis.
Hak cipta dilindungi.
