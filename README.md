# DentalCare — Sistem Reservasi Dokter Gigi

![Laravel](https://img.shields.io/badge/Framework-Laravel-FF2D20?logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/UI-Bootstrap_5-7952B3?logo=bootstrap&logoColor=white)

DentalCare merupakan aplikasi web reservasi jadwal pemeriksaan dokter gigi yang membantu pasien melakukan pemesanan jadwal dan konfirmasi pembayaran secara online, serta membantu administrator mengelola pendaftaran pasien, reservasi, pembayaran, dan pengumuman klinik.

DentalCare mengintegrasikan pengelolaan jadwal praktik, verifikasi pembayaran, dan komunikasi klinik dalam satu platform dengan antarmuka responsif untuk pasien dan administrator.

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi dan Kebutuhan Sistem](#teknologi-dan-kebutuhan-sistem)
- [Instalasi](#instalasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Alur Bisnis](#alur-bisnis)
- [Database](#database)
- [Struktur Proyek](#struktur-proyek)
- [Keamanan dan Validasi](#keamanan-dan-validasi)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Ketentuan Operasional](#ketentuan-operasional)

## Fitur

### Pasien

<p align="center">
  <img src="ximage/image.png" width="48%" alt="Registrasi Pasien">
  <img src="ximage/image-1.png" width="48%" alt="Status Pendaftaran">
</p>

<p align="center">
  <img src="ximage/image-2.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-3.png" width="48%" alt="Tampilan Pasien">
</p>

- Registrasi akun dan pemantauan status pendaftaran.
- Login setelah akun disetujui administrator.

<p align="center">
  <img src="ximage/image-4.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-5.png" width="48%" alt="Tampilan Pasien">
</p>
<p align="center">
  <img src="ximage/image-6.png" width="48%" alt="Tampilan Pasien">
</p>

- Dashboard dokter, kalender ketersediaan jadwal tujuh hari, dan informasi biaya konsultasi.

<p align="center">
  <img src="ximage/image-7.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-8.png" width="48%" alt="Tampilan Pasien">
</p>

- Pemilihan dokter, tanggal, slot pemeriksaan, dan pengisian keluhan.

<p align="center">
  <img src="ximage/image-9.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-10.png" width="48%" alt="Tampilan Pasien">
</p>

- Konfirmasi pembayaran melalui unggahan bukti transfer.
- Countdown pembayaran selama lima menit.

<p align="center">
  <img src="ximage/image-11.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-12.png" width="48%" alt="Tampilan Pasien">
</p>

- Pemantauan progres reservasi, alasan penolakan, dan riwayat reservasi.

<p align="center">
  <img src="ximage/image-13.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-14.png" width="48%" alt="Tampilan Pasien">
</p>

- Membaca pengumuman klinik yang telah dipublikasikan.

### Administrator

<p align="center">
  <img src="ximage/image-15.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-16.png" width="48%" alt="Tampilan Pasien">
</p>

- Login dan dashboard ringkasan operasional klinik.

<p align="center">
  <img src="ximage/image-17.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-18.png" width="48%" alt="Tampilan Pasien">
</p>

- Verifikasi pendaftaran pasien (menerima atau menolak).

<p align="center">
  <img src="ximage/image-19.png" width="48%" alt="Tampilan Pasien">
</p>

- Informasi dokter dan pengaturan status aktif dokter.

<p align="center">
  <img src="ximage/image-20.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-21.png" width="48%" alt="Tampilan Pasien">
</p>
<p align="center">
  <img src="ximage/image-22.png" width="48%" alt="Tampilan Pasien">
</p>

- Verifikasi bukti pembayaran (menerima atau menolak).

<p align="center">
  <img src="ximage/image-23.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-24.png" width="48%" alt="Tampilan Pasien">
</p>

- Verifikasi reservasi setelah pembayaran disetujui.

<p align="center">
  <img src="ximage/image-25.png" width="48%" alt="Tampilan Pasien">
  <img src="ximage/image-26.png" width="48%" alt="Tampilan Pasien">
</p>

- CRUD pengumuman dengan gambar opsional dan status draft/published.

### Otomatisasi

- Penguncian sementara slot untuk mencegah pemesanan ganda.
- Pelepasan slot setelah pembayaran ditolak atau melewati deadline.
- Scheduler untuk memperbarui status pembayaran kedaluwarsa dan reservasi yang jadwalnya berakhir.

## Teknologi dan Kebutuhan Sistem

| Komponen | Teknologi |
| --- | --- |
| Backend | PHP 8.5.10 , Laravel |
| Frontend | Blade, HTML, CSS, JavaScript |
| CSS framework | Bootstrap 5 |
| Ikon | Bootstrap Icons |
| Database | MySQL |
| ORM | Eloquent |
| Asset bundler | Vite |
| Dependency manager | Composer, npm |
| Penjadwalan | Laravel Scheduler |
| Version control | Git dan GitHub |

Siapkan PHP beserta ekstensi yang dibutuhkan Laravel (termasuk PDO MySQL, OpenSSL, Mbstring, XML, Ctype, Fileinfo, dan Tokenizer), Composer, Node.js/npm, MySQL, serta Git. Versi minimum PHP dan dependensi yang tepat mengikuti `composer.json`, `composer.lock`, `package.json`, dan lockfile npm dalam repository.

Periksa lingkungan lokal:

```bash
php -v
composer --version
node -v
npm -v
mysql --version
git --version
```

## Instalasi

### 1. Clone repository

Ganti URL contoh berikut dengan URL repository DentalCare yang sebenarnya.

```bash
git clone https://github.com/MigelOrvin/LSPMigel.git
cd LSPMigel
```

### 2. Instal dependensi

```bash
composer install
npm install
```

Jika `package-lock.json` tersedia dan konsisten, `npm ci` dapat digunakan untuk instalasi yang lebih reproduktif.

### 3. Konfigurasi environment

```bash
cp .env.example .env
php artisan key:generate
```

Buat database MySQL:

```sql
CREATE DATABASE reservasi_dental CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Sesuaikan konfigurasi `.env` dengan kredensial MySQL lokal:

```dotenv
APP_NAME=DentalCare
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservasi_dental
DB_USERNAME=root
DB_PASSWORD=

PAYMENT_BANK_NAME="Bank Demo"
PAYMENT_ACCOUNT_NUMBER=1234567890
PAYMENT_ACCOUNT_HOLDER=DentalCare
PAYMENT_DEMO=true
PAYMENT_EXPIRATION_MINUTES=5
```

Konfigurasi tersebut menggunakan rekening contoh. Untuk transaksi nyata, masukkan rekening resmi klinik dan nonaktifkan `PAYMENT_DEMO`. Jangan memasukkan `.env` atau kredensial asli ke repository.

### 4. Migrasi dan seeding

```bash
php artisan migrate --seed
```

Seeder digunakan untuk menyediakan akun administrator, dokter, dan jadwal praktik awal. Periksa `DatabaseSeeder.php` dan `AdminSeeder.php` untuk konfigurasi akun administrator awal. Segera ubah kredensial administrator awal sebelum membuka akses aplikasi kepada pengguna. Pasien dapat membuat akun melalui halaman registrasi.


### 5. Konfigurasi storage

```bash
php artisan storage:link
php artisan optimize:clear
```

Symbolic link digunakan untuk gambar pengumuman pada public storage. Bukti pembayaran disimpan pada private storage dan diakses melalui endpoint yang dilindungi otorisasi.

## Menjalankan Aplikasi

Jalankan tiga terminal dari direktori proyek:

```bash
# Terminal 1 — Backend
php artisan serve
```

```bash
# Terminal 2 — Frontend
npm run dev
```

```bash
# Terminal 3 — Scheduler
php artisan schedule:work
```

Buka <http://localhost:8000> pada browser.

Untuk membangun aset frontend tanpa Vite development server:

```bash
npm run build
```

### Scheduler

| Command | Fungsi |
| --- | --- |
| `reservations:expire` | Mengubah reservasi tanpa bukti pembayaran yang melewati deadline menjadi expired. |
| `reservations:complete` | Mengubah reservasi disetujui yang telah melewati jam akhir pemeriksaan menjadi completed. |

Kedua command dijadwalkan setiap menit pada `routes/console.php`.

```bash
php artisan schedule:list
php artisan reservations:expire
php artisan reservations:complete
```

Pada production, gunakan cron dengan path proyek dan PHP yang sesuai:

```cron
* * * * * cd /var/www/dentalcare && php artisan schedule:run >> /dev/null 2>&1
```

Untuk deployment, arahkan document root web server ke direktori `public/`, jalankan `npm run build`, gunakan `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, dan kredensial database yang aman. Pastikan direktori `storage` dan `bootstrap/cache` dapat ditulis oleh proses aplikasi serta scheduler aktif pada server. Konfigurasikan rekening resmi klinik sebelum menerima pembayaran. Gunakan pencadangan database dan storage secara berkala.

## Alur Bisnis

### Pendaftaran akun

Pasien mendaftar → status pending → admin memverifikasi → akun disetujui dapat login; akun ditolak tidak mendapat akses portal pasien.

### Reservasi dan pembayaran

```mermaid
flowchart TD
    A[Pasien memilih dokter, tanggal dan slot] --> B[Reservasi pending dan slot dikunci]
    B --> C{Bukti dikirim sebelum deadline?}
    C -->|Tidak| D[Expired dan slot dilepaskan]
    C -->|Ya| E[Verifikasi pembayaran admin]
    E -->|Ditolak| F[Pembayaran dan reservasi ditolak]
    F --> G[Slot dilepaskan dan masuk riwayat]
    E -->|Disetujui| H[Verifikasi reservasi admin]
    H -->|Ditolak| I[Reservasi ditolak dan refund manual]
    I --> G
    H -->|Disetujui| J[Reservasi aktif]
    J --> K[Jam pemeriksaan berakhir]
    K --> L[Completed dan masuk riwayat]
```

Batas lima menit berlaku untuk pengiriman bukti pembayaran, bukan durasi verifikasi administrator. Setelah bukti diterima tepat waktu, slot tetap terkunci selama proses verifikasi. Penolakan pembayaran mengakhiri reservasi; pasien harus membuat reservasi baru apabila ingin memesan kembali.

### Pencegahan double booking

Ketersediaan slot dihitung berdasarkan dokter, tanggal aktual, rentang waktu, status reservasi, status pembayaran, dan deadline. Dua interval dianggap bertabrakan apabila waktu mulai reservasi lama lebih kecil dari waktu akhir reservasi baru dan waktu akhirnya lebih besar dari waktu mulai reservasi baru.

Pembuatan reservasi dan proses pembayaran menggunakan database transaction serta pessimistic locking (`lockForUpdate()`) untuk menjaga konsistensi ketika beberapa request berjalan bersamaan. Backend memeriksa ulang ketersediaan sebelum menyimpan reservasi; countdown JavaScript bukan sumber otoritas deadline.

### Status reservasi

| Status database | Keterangan |
| --- | --- |
| `pending` | Menunggu pembayaran atau verifikasi |
| `approved` | Reservasi disetujui |
| `rejected` | Pembayaran atau reservasi ditolak |
| `confirmed` | Status konfirmasi yang didukung skema |
| `completed` | Jadwal reservasi telah berakhir sesuai aturan aplikasi |
| `cancelled` | Reservasi dibatalkan |
| `expired` | Batas waktu pembayaran habis |

Status pembayaran terdiri dari `pending`, `approved`, dan `rejected`. Status yang ditampilkan kepada pasien menggabungkan status reservasi dan pembayaran.

## Database

Aplikasi menggunakan enam tabel bisnis utama, di luar tabel infrastruktur bawaan Laravel.

| Tabel | Fungsi |
| --- | --- |
| `users` | Akun admin dan pasien, role, status pendaftaran |
| `doctors` | Profil dokter, foto, biaya konsultasi, status aktif |
| `schedules` | Hari dan jam praktik dokter |
| `reservations` | Kode reservasi, pasien, jadwal, keluhan, biaya, status, deadline |
| `payments` | Nominal, bukti transfer, status dan hasil verifikasi |
| `announcements` | Judul, isi, gambar, penulis dan status publikasi |

```mermaid
erDiagram
    USERS ||--o{ RESERVATIONS : makes
    DOCTORS ||--o{ SCHEDULES : has
    SCHEDULES ||--o{ RESERVATIONS : booked_for
    RESERVATIONS ||--o| PAYMENTS : has
    USERS ||--o{ ANNOUNCEMENTS : authors
    USERS o|--o{ RESERVATIONS : verifies
    USERS o|--o{ PAYMENTS : verifies
```

Diagram menunjukkan relasi utama, bukan seluruh kolom. Definisi skema lengkap tersedia dalam migration.

## Struktur Proyek

```text
app/
├── Console/Commands/        # Expiration dan completion scheduler
├── Http/
│   ├── Controllers/        # Controller admin, pasien, autentikasi
│   └── Middleware/         # Pembatasan akses berdasarkan role
├── Models/                 # Model Eloquent dan relasi
└── Services/               # Business logic reservasi dan pembayaran
config/                     # Konfigurasi aplikasi dan pembayaran
database/
├── migrations/             # Definisi struktur database
└── seeders/                # Data awal
resources/
├── css/                    # Bootstrap dan styling aplikasi
├── js/                     # Countdown, modal, preview gambar
└── views/                  # Template Blade admin dan pasien
routes/
├── web.php                 # Routing aplikasi
└── console.php             # Penjadwalan command
storage/                    # Penyimpanan file aplikasi
```

## Keamanan dan Validasi

- Password disimpan menggunakan hashing Laravel.
- Middleware membatasi akses admin dan pasien berdasarkan role serta status akun.
- Akses reservasi dan pembayaran dibatasi berdasarkan kepemilikan data dan otorisasi.
- Form menggunakan CSRF protection dan validasi server-side.
- Bukti pembayaran menggunakan private storage; nominal tagihan berasal dari data reservasi.
- Upload gambar dibatasi pada JPG/JPEG, PNG, dan WEBP dengan ukuran maksimal 2 MB sesuai konfigurasi implementasi.
- Pengumuman draft tidak ditampilkan melalui endpoint pasien. Gambar pengumuman di public storage tidak ditujukan untuk informasi rahasia.
- Pesan validasi, flash message, dan alert membantu pengguna memahami kesalahan aplikasi.

## Panduan Penggunaan

### Portal Pasien

1. Daftarkan akun dan pantau status persetujuan pendaftaran.
2. Masuk setelah akun disetujui administrator.
3. Pilih dokter melalui dashboard atau kalender ketersediaan jadwal.
4. Tentukan tanggal dan jam pemeriksaan, lalu isi keluhan.
5. Lakukan transfer sesuai informasi pembayaran dan unggah bukti sebelum batas waktu berakhir.
6. Pantau hasil verifikasi pembayaran dan persetujuan reservasi melalui menu Reservasi Saya.
7. Datang ke klinik sesuai jadwal yang disetujui. Informasi reservasi yang telah berakhir dapat ditemukan pada bagian riwayat.

Informasi layanan dan pengumuman terbaru tersedia melalui menu Pengumuman.

### Portal Administrator

| Menu | Fungsi |
| --- | --- |
| Dashboard | Ringkasan operasional, aktivitas reservasi, jadwal hari ini, dan pengajuan yang memerlukan tindakan. |
| Pasien | Meninjau dan memverifikasi pendaftaran akun pasien. |
| Dokter | Melihat profil dan jadwal praktik serta mengatur status aktif dokter. |
| Pembayaran | Memeriksa bukti transfer dan memberikan keputusan verifikasi. |
| Reservasi | Meninjau reservasi dengan pembayaran terverifikasi dan memberikan keputusan persetujuan. |
| Pengumuman | Membuat, mengedit, memublikasikan, dan menghapus informasi klinik. |

Administrator perlu mencocokkan bukti transfer dengan transaksi pada rekening klinik sebelum menyetujui pembayaran. Alasan penolakan dicatat agar dapat diketahui pasien.

## Ketentuan Operasional

- Pembayaran menggunakan konfirmasi transfer manual, bukan payment gateway.
- Refund memerlukan penanganan manual oleh administrator/klinik.
- Status completed otomatis menunjukkan jadwal telah berakhir, bukan bukti kehadiran pasien.
- Ketersediaan kalender diperbarui ketika halaman dimuat; pengecekan akhir dilakukan oleh backend.
- Aplikasi menggunakan durasi slot pemeriksaan 60 menit dan batas pembayaran default lima menit yang dapat diatur melalui konfigurasi pembayaran.

---

**DentalCare** — Sistem Reservasi Jadwal Periksa Dokter Gigi.

Satu platform untuk reservasi pemeriksaan gigi dan pengelolaan operasional klinik.
