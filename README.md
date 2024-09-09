# Getslot App - Ticketing Portal

getslot adalah portal ticketing yang dirancang untuk mempermudah pengelolaan tiket acara, reservasi, dan manajemen peserta. Portal ini menawarkan solusi yang efisien dan user-friendly untuk penyelenggara acara dalam mengatur dan melacak penjualan tiket, mengelola peserta, serta memberikan kemudahan dalam pembuatan laporan.

Fitur Utama

	•	Manajemen Tiket: Buat, kelola, dan distribusikan tiket acara dengan mudah.
	•	Reservasi Online: Izinkan pengguna untuk melakukan reservasi tiket secara online.
	•	Laporan dan Analisis: Dapatkan wawasan dari laporan penjualan tiket dan partisipasi acara.
	•	Notifikasi: Kirim pengingat dan informasi penting kepada peserta acara.
	•	User-Friendly Interface: Desain antarmuka yang mudah digunakan dan diakses oleh semua pengguna.

Prasyarat

Sebelum menginstal dan menjalankan proyek ini, pastikan Anda telah menginstal prasyarat berikut:

	•	PHP >= 8.2
	•	Composer
	•	Laravel >= 9.x
	•	MySQL atau database lain yang kompatibel
	•	Server Web (Apache, Nginx, dll.)
	•	Node.js dan npm (untuk menjalankan frontend build)

Cara Penginstalan

Berikut adalah langkah-langkah untuk menginstal dan menjalankan proyek “getslot”:


# 1. Clone Repository
```
git clone https://github.com/username/getslot.git
cd getslot
```


# 2. Install Dependencies
```
composer install
npm install
```

# 3. Configure Environment File
```
cp .env.example .env
```

Adjust the values in the .env file, such as database configuration, email services, and others.

# 4. Generate Application Key
```
php artisan key:generate
```

# 5. Migrate and Seed Database
```
php artisan migrate --seed
```

# 6. Build Frontend Assets
```
npm run dev
```

# For production build, use:
```
npm run prod
```

# 7. Run the Application
```
php artisan serve
```

Access the application at http://localhost:8000

# Lisensi

Proyek ini dibuat oleh Rizky Ajie Kurniawan.

Dengan README ini, pengguna baru akan memiliki pemahaman yang jelas tentang apa itu Getslot,” serta bagaimana mereka dapat menginstal dan mulai menggunakan proyek ini.


