# Dokumentasi Skema Database — PO CAN Travel

Dokumen ini merinci seluruh struktur tabel, tipe data, indeks, dan deskripsi kolom pada database sistem **PO CAN Travel**.

---

## 1. Tabel `users`

Menyimpan data akun pengguna baik administrator maupun pelanggan (customer).

| Field | Type | Nullable | Key | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Primary Key identitas pengguna |
| `name` | VARCHAR(255) | No | | Nama lengkap pengguna |
| `email` | VARCHAR(255) | No | UNI | Alamat email unik untuk autentikasi |
| `email_verified_at` | TIMESTAMP | Yes | | Waktu verifikasi email (opsional) |
| `password` | VARCHAR(255) | No | | Hash kata sandi pengguna (Bcrypt) |
| `role` | VARCHAR(255) | No | | Peran hak akses: `admin` atau `customer` (default: `customer`) |
| `remember_token` | VARCHAR(100) | Yes | | Token "ingat saya" untuk sesi login |
| `created_at` | TIMESTAMP | Yes | | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | Yes | | Waktu pembaruan record terakhir |

---

## 2. Tabel `buses`

Menyimpan data master armada bus yang dioperasikan oleh PO CAN Travel.

| Field | Type | Nullable | Key | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Primary Key armada bus |
| `name` | VARCHAR(255) | No | | Nama armada bus (contoh: *CAN Executive 01*) |
| `code` | VARCHAR(50) | No | UNI | Kode unik armada bus (contoh: *CAN-EX01*) |
| `total_seats` | INT UNSIGNED | No | | Jumlah kapasitas kursi penumpang (1 - 100) |
| `created_at` | TIMESTAMP | Yes | | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | Yes | | Waktu pembaruan record terakhir |

---

## 3. Tabel `seats`

Menyimpan data nomor kursi fisik pada setiap armada bus.

| Field | Type | Nullable | Key | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Primary Key kursi |
| `bus_id` | BIGINT UNSIGNED | No | FK | Foreign Key ke `buses.id` (`cascadeOnDelete`) |
| `seat_number` | VARCHAR(255) | No | MUL | Nomor kursi format baris-kolom (contoh: *1A*, *1B*, *2C*) |
| `created_at` | TIMESTAMP | Yes | | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | Yes | | Waktu pembaruan record terakhir |

> **Unique Constraint**: Kombinasi `(bus_id, seat_number)` bersifat unik untuk mencegah nomor kursi ganda dalam satu bus.

---

## 4. Tabel `routes`

Menyimpan data rute perjalanan antar-kota yang dilayani.

| Field | Type | Nullable | Key | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Primary Key rute |
| `origin` | VARCHAR(100) | No | | Kota titik keberangkatan (contoh: *Jakarta*) |
| `destination` | VARCHAR(100) | No | | Kota titik tujuan akhir (contoh: *Jepara*) |
| `duration` | INT UNSIGNED | No | | Estimasi durasi tempuh perjalanan dalam satuan menit |
| `created_at` | TIMESTAMP | Yes | | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | Yes | | Waktu pembaruan record terakhir |

---

## 5. Tabel `trips`

Menyimpan jadwal keberangkatan perjalanan bus beserta tarif dan statusnya.

| Field | Type | Nullable | Key | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Primary Key jadwal perjalanan |
| `bus_id` | BIGINT UNSIGNED | No | FK | Foreign Key ke `buses.id` (`restrictOnDelete`) |
| `route_id` | BIGINT UNSIGNED | No | FK | Foreign Key ke `routes.id` (`restrictOnDelete`) |
| `departure_at` | DATETIME | No | MUL | Waktu keberangkatan bus (WIB / Asia/Jakarta) |
| `arrival_at` | DATETIME | No | | Estimasi waktu tiba bus di kota tujuan |
| `price` | BIGINT UNSIGNED | No | | Tarif tiket per kursi dalam mata uang Rupiah |
| `status` | ENUM | No | | Status perjalanan: `scheduled`, `departed`, `completed`, `cancelled` |
| `created_at` | TIMESTAMP | Yes | | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | Yes | | Waktu pembaruan record terakhir |

> **Indeks**: Terdapat indeks komposit pada `(route_id, departure_at)` untuk mempercepat query pencarian jadwal tiket pelanggan.

---

## 6. Tabel `orders`

Menyimpan data transaksi pemesanan tiket yang dilakukan pelanggan.

| Field | Type | Nullable | Key | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Primary Key pesanan |
| `user_id` | BIGINT UNSIGNED | No | FK / MUL | Foreign Key ke `users.id` (`restrictOnDelete`) |
| `trip_id` | BIGINT UNSIGNED | No | FK | Foreign Key ke `trips.id` (`restrictOnDelete`) |
| `order_code` | VARCHAR(255) | No | UNI | Kode transaksi unik berformat `PCT-YYYYMMDD-XXXXXX` |
| `total_amount` | BIGINT UNSIGNED | No | | Total biaya pemesanan dihitung server (Rupiah) |
| `status` | ENUM | No | MUL | Status transaksi: `pending`, `confirmed`, `cancelled`, `completed` |
| `created_at` | TIMESTAMP | Yes | | Waktu pembuatan transaksi |
| `updated_at` | TIMESTAMP | Yes | | Waktu pembaruan status transaksi terakhir |

---

## 7. Tabel `order_items`

Menyimpan rincian setiap tiket penumpang dalam sebuah pesanan.

| Field | Type | Nullable | Key | Deskripsi |
| :--- | :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | No | PK | Primary Key item pesanan |
| `order_id` | BIGINT UNSIGNED | No | FK | Foreign Key ke `orders.id` (`cascadeOnDelete`) |
| `seat_id` | BIGINT UNSIGNED | No | FK | Foreign Key ke `seats.id` (`restrictOnDelete`) |
| `passenger_name` | VARCHAR(100) | No | | Nama lengkap penumpang |
| `passenger_identity` | VARCHAR(50) | No | | Nomor identitas resmi penumpang (KTP/SIM/Paspor) |
| `price` | BIGINT UNSIGNED | No | | **Snapshot harga tiket** per kursi saat pemesanan dibuat |
| `created_at` | TIMESTAMP | Yes | | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | Yes | | Waktu pembaruan record terakhir |
