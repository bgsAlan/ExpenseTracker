Expense Tracker API

REST API sederhana untuk mencatat dan mengelola pengeluaran (expenses), dibangun dengan Laravel dan diamankan dengan JWT Authentication. Project ini dibuat sebagai latihan implementasi CRUD dan sistem autentikasi berbasis token di Laravel.

    Fitur
    1. Autentikasi menggunakan JWT (register, login, get current user)
    2. CRUD data account (rekening/dompet pengguna)
    3. Kelola kategori transaksi (create, read, update)
    4. Catat transaksi pengeluaran/pemasukan
    5. Endpoint utama diproteksi middleware JWT (auth:api)
    6. Tech Stack
    Framework: Laravel
    Auth: JWT (tymon/jwt-auth)
    Database: MySQL
    Tools: Postman (untuk testing API)
    🚀 Instalasi & Setup
    bash
# 1. Clone repository
git clone https://github.com/<username>/expense-tracker.git
cd expense-tracker

# 2. Install dependency
composer install

# 3. Copy .env dan generate app key
cp .env.example .env
php artisan key:generate

# 4. Generate JWT secret
php artisan jwt:secret

# 5. Setup database di file .env, lalu migrate
php artisan migrate

# 6. Jalankan server
## Cara Menjalankan

```bash
php artisan serve
```

## Environment Variables

Pastikan konfigurasi berikut sudah diisi di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_tracker
DB_USERNAME=root
DB_PASSWORD=
JWT_SECRET=your_generated_secret
```

## API Endpoints

### Auth
| Method | Endpoint | Deskripsi | Auth |
| :--- | :--- | :--- | :---: |
| POST | `/api/register` | Registrasi user baru | ❌ |
| POST | `/api/login` | Login & dapatkan JWT token | ❌ |
| GET | `/api/me` | Ambil data user yang login | ✅ |
| POST | `/api/logout` | Hapus token user yang login | ✅ |

### Account
| Method | Endpoint | Deskripsi | Auth |
| :--- | :--- | :--- | :---: |
| GET | `/api/account` | Ambil semua account | ✅ |
| POST | `/api/account` | Tambah account baru | ✅ |
| GET | `/api/account/{account}` | Detail satu account | ✅ |
| PUT | `/api/account/{account}` | Update account | ✅ |
| DELETE | `/api/account/{account}` | Hapus account | ✅ |

### Category
| Method | Endpoint | Deskripsi | Auth |
| :--- | :--- | :--- | :---: |
| GET | `/api/category` | Ambil semua kategori | ✅ |
| POST | `/api/category` | Tambah kategori baru | ✅ |
| GET | `/api/category/{category}` | Detail satu kategori | ✅ |
| PUT | `/api/category/{category}` | Update kategori | ✅ |
| DELETE | `/api/category/{category}` | Delete kategori | ✅ |

### Transaction
| Method | Endpoint | Deskripsi | Auth |
| :--- | :--- | :--- | :---: |
| GET    | `/api/transaction` | Lihat semua transaksi | ✅ |
| GET    | `/api/transaction/{transaction}` | Lihat Transaksi sesuai ID | ✅ |
| POST   | `/api/transaction` | Tambah transaksi baru | ✅ |
| PUT    | `/api/transaction/{transaction}` | Edit transaksi sesuai ID | ✅ |
| DELETE | `/api/transaction` | Hapus transaksi sesuai ID | ✅ |

### CRUD untuk transaction masih belum lengkap (baru store) — bisa jadi salah satu item di roadmap.

Untuk endpoint yang memerlukan auth (✅), sertakan header: Authorization: Bearer <your_token>

Contoh Request — Login
``` json
POST /api/login
{
  "email": "user@example.com",
  "password": "password"
}

Response:

json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600
}
Contoh Request — Tambah Account
json
POST /api/account
{
  "name": "Dompet Utama",
  "balance": 500000
}
Contoh Request — Tambah Transaction
json
POST /api/transaction
{
  "account_id": 1,
  "category_id": 2,
  "amount": 25000,
  "type": "expense",
  "description": "Makan siang",
  "date": "2026-08-14"
}
```
Sesuaikan field di atas dengan struktur TransactionController@store dan validasi yang sebenarnya kamu pakai.

🧪 Testing

Import collection Postman (jika ada) atau test manual tiap endpoint di atas menggunakan Postman/Insomnia dengan menyertakan Bearer token setelah login.

📌 Roadmap / Rencana Pengembangan
 Lengkapi CRUD transaction (index, show, update, delete)
 Filter & pencarian transaksi berdasarkan tanggal/kategori/account
 Laporan/summary pengeluaran & pemasukan bulanan
 Delete endpoint untuk category
 Dokumentasi API dengan Swagger


Project ini masih dalam tahap belajar, jadi masukan dan saran sangat terbuka lewat issue atau pull request.