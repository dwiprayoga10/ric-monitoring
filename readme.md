# RIC MONITORING SWDKLLJ

RIC MONITORING merupakan platform pengelolaan data SWDKLLJ dan surat pemberitahuan pajak kendaraan berbasis import data untuk mendukung proses monitoring, validasi, dan pengelolaan data secara terstruktur di Jasa Raharja Cabang Semarang.

---

## Features

- Import data SWDKLLJ
- Monitoring data kendaraan
- Validasi data pajak kendaraan
- Manajemen surat pemberitahuan
- Export data laporan
- Dashboard monitoring

---

## Tech Stack

- Laravel
- MySQL
- Tailwind CSS
- JavaScript

---

## Installation

```bash
git clone https://github.com/dwiprayoga10/ric-monitoring.git

cd ric-monitoring

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

npm install

npm run dev

php artisan serve
```

---

## Usage

Jalankan aplikasi menggunakan:

```bash
php artisan serve
```

Akses aplikasi melalui:

```txt
http://127.0.0.1:8000
```

---

## Author

**Dwi Prayoga**
