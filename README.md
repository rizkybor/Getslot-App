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

Proyek ini adalah hak cipta pribadi (c) 2024 oleh Rizky Ajie Kurniawan. Semua hak dilindungi undang-undang.

Penggunaan, distribusi, dan modifikasi dari proyek ini memerlukan izin tertulis dari pemilik hak cipta. Untuk informasi lebih lanjut atau permintaan lisensi, silakan hubungi [rizkyak994@gmail.com].

Ini memberikan konteks yang jelas tentang bagaimana proyek dapat digunakan oleh orang lain.

# Versi 1.0.1

Updated: 1.0.1

Release 15 September 2024 :
	•	Perbaikan bug minor dan peningkatan performa.
	•	Fitur menambahkan Class List berdasarkan Type.
	•	Fitur menambahkan Type berdasarkan Tiket.
	•	Fitur Multiple Participants.
	•	Penyesuaian UI Client.

Dengan README ini, pengguna tidak hanya memahami cara menginstal dan menggunakan proyek, tetapi juga memiliki informasi yang jelas mengenai lisensi dan pembaruan versi terbaru.


