# Arsitektur Aplikasi — PO CAN Travel

Dokumen ini menjelaskan rancangan arsitektur, siklus hidup request (*request lifecycle*), pemisahan peran (*role-based authorization*), serta mekanisme konkurensi pada aplikasi **PO CAN Travel**.

---

## 1. Siklus Hidup Request (Request Lifecycle)

Aplikasi PO CAN Travel dibangun dengan pendekatan Server-Rendered Application (Monolith) menggunakan Laravel 10, Blade, Tailwind CSS, Alpine.js, dan MySQL. Alur pemrosesan request adalah sebagai berikut:

```text
Browser (Client HTTP Request)
      │
      ▼
Laravel Router (routes/web.php)
      │
      ▼
Middleware Layer
├── Session & EncryptCookies
├── VerifyCsrfToken
├── Authenticate (auth)
└── RoleMiddleware (role:admin / role:customer)
      │
      ▼
Form Request Layer (Validation & Business Rules)
├── Validasi Format, Tipe Data, Bound
└── Custom Validator (State transition, Seat availability, Ownership)
      │
      ▼
Controller Layer (HTTP Coordinator)
├── Eager loading relasi Eloquent
├── DB Transaction & Row Locking (Pessimistic Concurrency)
└── Data presentation preparation
      │
      ▼
Eloquent ORM Layer & Model Helpers
      │
      ▼
MySQL Database (ACID Transactions & Foreign Key Constraints)
      │
      ▼
Blade Templating Engine (HTML + Tailwind CSS + Alpine.js View Response)
      │
      ▼
Browser (Rendered Interactive Page)
```

---

## 2. Alur Pengguna (User Flows)

### A. Alur Pelanggan (Customer Flow)

```text
1. Beranda / Landing Page (/)
   └─ Pelanggan melihat informasi layanan dan klik pencarian
2. Registrasi / Login (/register, /login)
   └─ Akun baru otomatis memperoleh peran 'customer'
3. Dasbor Pelanggan (/customer/dashboard)
   └─ Informasi profil & pintasan pencarian
4. Cari Jadwal Perjalanan (/customer/trips)
   └─ Filter asal, tujuan, tanggal (hanya menampilkan trip 'scheduled')
5. Detail Perjalanan (/customer/trips/{trip})
   └─ Rute, estimasi durasi, tarif per kursi, dan armada bus
6. Pemilihan Kursi Bus (/customer/trips/{trip}/seats)
   └─ Denah interaktif Alpine.js, status kursi realtime, estimasi total
7. Pengisian Data Penumpang (/customer/trips/{trip}/booking)
   └─ Nama & nomor identitas (KTP/SIM/Paspor) tiap kursi
8. Konfirmasi & Penyimpanan Pesanan (POST /customer/trips/{trip}/booking)
   └─ DB::transaction() + pessimistic lock, snapshot harga, order_code unik
9. Detail Pesanan (/customer/orders/{order})
   └─ Tiket pesanan, nomor kursi, status (Pending), total bayar
10. Riwayat Pesanan (/customer/orders)
    └─ Daftar seluruh pesanan milik akun yang sedang login dengan filter status
```

---

### B. Alur Administrator (Admin Flow)

```text
1. Login Admin (/login)
   └─ Masuk menggunakan kredensial admin
2. Dasbor Admin (/admin/dashboard)
   └─ Ringkasan akun dan akses ke 4 modul operasional
3. Kelola Armada Bus (/admin/buses)
   ├── Tambah armada: auto-generate nomor kursi (1A, 1B, 1C, 1D...)
   ├── Edit armada: sinkronisasi kursi aman (penurunan kursi dicek histori pesanan)
   └── Hapus armada: dilindungi cek eksistensi jadwal perjalanan
4. Kelola Rute Perjalanan (/admin/routes)
   ├── Tambah rute: validasi kota asal & tujuan tidak boleh sama
   ├── Edit rute: pembaruan durasi tempuh
   └── Hapus rute: dilindungi cek eksistensi jadwal perjalanan
5. Kelola Jadwal Perjalanan (/admin/trips)
   ├── Tambah jadwal: validasi arrival_at > departure_at, harga non-negatif
   ├── Edit jadwal & status: scheduled, departed, completed, cancelled
   └── Hapus jadwal: dilindungi cek eksistensi pesanan
6. Kelola Pesanan Pelanggan (/admin/orders)
   ├── Daftar pesanan: pencarian (kode, nama, email) & filter status
   ├── Detail pesanan: rincian penumpang, snapshot harga historis
   └── Pembaruan status (PATCH /admin/orders/{order}/status):
       └── Mesin transisi status yang ketat (pending -> confirmed/cancelled, confirmed -> completed/cancelled)
```

---

## 3. Desain Konkurensi & Integritas Transaksi

Untuk mencegah celah *double booking* pada kursi bus ketika banyak pelanggan melakukan reservasi bersamaan, pemesanan tiket menggunakan transaksi database dengan *pessimistic row locking*:

```text
BEGIN TRANSACTION
  │
  ├─ 1. Lock Baris Trip:
  │     Trip::where('id', $trip->id)->lockForUpdate()->first()
  │     (Memastikan trip berstatus 'scheduled')
  │
  ├─ 2. Lock Baris Kursi yang Dipilih:
  │     Seat::whereIn('id', $sessionSeatIds)->lockForUpdate()->get()
  │     (Memastikan semua kursi milik armada trip ini)
  │
  ├─ 3. Cek Eksistensi Pesanan Aktif:
  │     Mengecek apakah salah satu kursi memiliki OrderItem pada order
  │     berstatus 'pending', 'confirmed', atau 'completed' pada trip tersebut.
  │     Jika sudah terpesan -> Lempar ValidationException (ROLLBACK).
  │
  ├─ 4. Kalkulasi Server-Side:
  │     total_amount = count(seats) * trip.price
  │     order_code = Order::generateOrderCode()
  │
  ├─ 5. Simpan Order (status: 'pending')
  │
  ├─ 6. Simpan OrderItems:
  │     Menyimpan passenger_name, passenger_identity, dan snapshot harga (price).
  │
COMMIT TRANSACTION
```

---

## 4. Keamanan & Proteksi Akses

1. **Role-Based Access Control (RBAC)**:
   - Ditegakkan melalui middleware kustom `RoleMiddleware` (`role:admin` dan `role:customer`).
   - Customer dilarang mengakses seluruh endpoint administrasi (`403 Forbidden`).
   - Admin dilarang mengakses alur pemesanan customer (`403 Forbidden`).
   - Pengguna tamu (guest) otomatis dialihkan ke halaman login (`302 Redirect`).

2. **Insecure Direct Object Reference (IDOR) Protection**:
   - Customer hanya dapat mengakses riwayat dan detail pesanan miliknya sendiri (`user_id === Auth::id()`). Mengakses pesanan pengguna lain akan menghasilkan `403 Forbidden`.

3. **Mass Assignment Protection**:
   - Kolom sensitif seperti `role` pada pengguna ditentukan secara ketat di controller (`'role' => 'customer'`) dan tidak dapat dimanipulasi melalui request form.
   - Kolom `total_amount`, `price`, dan `status` dihitung dan ditetapkan sepihak oleh server.

4. **Snapshot Harga Historis**:
   - Nilai tarif tiket pada saat transaksi dicatat langsung ke dalam tabel `order_items.price`. Jika tarif pada jadwal perjalanan (`trips.price`) di kemudian hari diubah oleh admin, riwayat total tagihan pesanan lama tetap tidak berubah.
