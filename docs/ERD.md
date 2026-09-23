# Entity Relationship Diagram (ERD) — PO CAN Travel

Dokumen ini memuat diagram hubungan antar-entitas (Entity Relationship Diagram) aktual dari database sistem pemesanan tiket bus **PO CAN Travel**.

## Diagram ERD (Mermaid)

```mermaid
erDiagram
    users ||--o{ orders : "places"
    buses ||--o{ seats : "has"
    buses ||--o{ trips : "assigned to"
    routes ||--o{ trips : "has"
    trips ||--o{ orders : "booked in"
    orders ||--|{ order_items : "contains"
    seats ||--o{ order_items : "assigned in"

    users {
        bigint id PK
        varchar name
        varchar email UK
        timestamp email_verified_at
        varchar password
        varchar role
        varchar remember_token
        timestamp created_at
        timestamp updated_at
    }

    buses {
        bigint id PK
        varchar name
        varchar code UK
        int unsigned total_seats
        timestamp created_at
        timestamp updated_at
    }

    seats {
        bigint id PK
        bigint bus_id FK
        varchar seat_number
        timestamp created_at
        timestamp updated_at
    }

    routes {
        bigint id PK
        varchar origin
        varchar destination
        int unsigned duration
        timestamp created_at
        timestamp updated_at
    }

    trips {
        bigint id PK
        bigint bus_id FK
        bigint route_id FK
        datetime departure_at
        datetime arrival_at
        bigint unsigned price
        enum status
        timestamp created_at
        timestamp updated_at
    }

    orders {
        bigint id PK
        bigint user_id FK
        bigint trip_id FK
        varchar order_code UK
        bigint unsigned total_amount
        enum status
        timestamp created_at
        timestamp updated_at
    }

    order_items {
        bigint id PK
        bigint order_id FK
        bigint seat_id FK
        varchar passenger_name
        varchar passenger_identity
        bigint unsigned price
        timestamp created_at
        timestamp updated_at
    }
```

## Relasi & Integritas Referensial

1. **`users` &rarr; `orders` (1 : N)**
   - Setiap pengguna (customer) dapat memiliki banyak pesanan.
   - Foreign key: `orders.user_id` mereferensikan `users.id` (`restrictOnDelete`).

2. **`buses` &rarr; `seats` (1 : N)**
   - Setiap armada bus memiliki sejumlah kursi sesuai kapasitasnya.
   - Foreign key: `seats.bus_id` mereferensikan `buses.id` (`cascadeOnDelete`).
   - Unique Constraint: `(bus_id, seat_number)` memastikan tidak ada nomor kursi ganda pada armada yang sama.

3. **`buses` &rarr; `trips` (1 : N)**
   - Satu armada bus dapat dijadwalkan untuk beberapa perjalanan.
   - Foreign key: `trips.bus_id` mereferensikan `buses.id` (`restrictOnDelete`).

4. **`routes` &rarr; `trips` (1 : N)**
   - Satu rute (asal &ndash; tujuan) dapat digunakan oleh banyak jadwal perjalanan.
   - Foreign key: `trips.route_id` mereferensikan `routes.id` (`restrictOnDelete`).

5. **`trips` &rarr; `orders` (1 : N)**
   - Setiap perjalanan dapat memiliki banyak pesanan tiket dari berbagai customer.
   - Foreign key: `orders.trip_id` mereferensikan `trips.id` (`restrictOnDelete`).

6. **`orders` &rarr; `order_items` (1 : N)**
   - Satu pesanan tiket dapat mencakup satu atau beberapa penumpang/kursi.
   - Foreign key: `order_items.order_id` mereferensikan `orders.id` (`cascadeOnDelete`).

7. **`seats` &rarr; `order_items` (1 : N)**
   - Setiap item pesanan tiket mereferensikan nomor kursi tertentu pada bus.
   - Foreign key: `order_items.seat_id` mereferensikan `seats.id` (`restrictOnDelete`).
   - Snapshot Harga: `order_items.price` menyimpan harga tiket pada saat transaksi dilakukan untuk integritas historis.
