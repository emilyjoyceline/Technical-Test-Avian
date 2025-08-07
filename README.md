# CRUD Database Manager
Technical Test Avian - Emily Joyceline Gunawan

Website CRUD sederhana untuk mengelola 4 tabel database dengan fitur lengkap.

## Fitur

✅ **CRUD Lengkap** - Create, Read, Update, Delete untuk semua tabel  
✅ **Form Manual** - Input data melalui form HTML  
✅ **Upload Excel/CSV** - Import data dari file Excel atau CSV  
✅ **Download Excel** - Export data ke format Excel  
✅ **Download PDF** - Export data ke format PDF  
✅ **Design Kreatif** - UI modern dengan Bootstrap dan gradient colors  
✅ **Single Page** - Semua fitur dalam satu halaman dengan tabs  

## Setup & Instalasi

### 1. Persiapan XAMPP
- Pastikan XAMPP sudah terinstall
- Jalankan Apache dan MySQL dari XAMPP Control Panel

### 2. Setup Database
- Buka phpMyAdmin (http://localhost/phpmyadmin)
- Import file `database_setup.sql` atau copy-paste isinya ke SQL tab
- Database `test` akan dibuat otomatis dengan 4 tabel

### 3. File Website
- Copy semua file ke folder `htdocs/namaproject/` di XAMPP
- Struktur file:
  ```
  htdocs/crud-emily/
  ├── index.php (file utama)
  ├── config.php (konfigurasi database)
  ├── database_setup.sql (setup database)
  └── README.md (panduan ini)
  ```

### 4. Akses Website
- Buka browser dan ke: `http://localhost/crud-emily/`

## Cara Penggunaan

### 1. Navigasi
- Gunakan tab di atas untuk switch antar tabel:
  - **Table A**: Kode Toko (kode_toko_baru, kode_toko_lama)
  - **Table B**: Transaksi (kode_toko, nominal_transaksi)
  - **Table C**: Area Sales (kode_toko, area_sales)
  - **Table D**: Data Sales (kode_sales, nama_sales)

### 2. Tambah Data Manual
- Isi form di sebelah kiri
- Klik "Simpan Data"

### 3. Upload Excel/CSV
- Siapkan file Excel/CSV dengan kolom sesuai urutan tabel
- Pilih file dan klik "Upload File"
- **Format CSV untuk Table A**: kode_toko_baru,kode_toko_lama
- **Format CSV untuk Table B**: kode_toko,nominal_transaksi
- **Format CSV untuk Table C**: kode_toko,area_sales
- **Format CSV untuk Table D**: kode_sales,nama_sales

### 4. Edit Data
- Klik tombol kuning (edit) di kolom Aksi
- Form akan terisi otomatis dengan data yang dipilih
- Ubah data dan klik "Simpan Data"

### 5. Hapus Data
- Klik tombol merah (hapus) di kolom Aksi
- Konfirmasi penghapusan

### 6. Download Data
- **Excel**: Klik tombol hijau "Excel" untuk download .xls
- **PDF**: Klik tombol merah "PDF" untuk download .pdf

## Contoh File CSV untuk Upload

### Table A (Kode Toko)
```csv
kode_toko_baru,kode_toko_lama
6,10
7,11
8,
```

### Table B (Transaksi)
```csv
kode_toko,nominal_transaksi
8,1500.00
9,2000.50
```

### Table C (Area Sales)
```csv
kode_toko,area_sales
6,A
7,B
```

### Table D (Data Sales)
```csv
kode_sales,nama_sales
A4,Foxtrot
B3,Golf
```

## Troubleshooting

### Error "Connection failed"
- Pastikan MySQL di XAMPP sudah running
- Cek username/password di `config.php` (default: root/kosong)

### Upload Excel gagal
- Pastikan format file .csv, .xls, atau .xlsx
- Cek format kolom sesuai dengan struktur tabel
- File harus memiliki header di baris pertama

### Download tidak berfungsi
- Pastikan tidak ada output sebelum header download
- Cek permission folder untuk write access

## Fitur Tambahan

- **Responsive Design**: Bekerja di desktop dan mobile
- **Alert System**: Notifikasi sukses/error untuk setiap aksi
- **Form Validation**: Validasi input di frontend dan backend
- **Auto-increment**: ID otomatis untuk primary key
- **Null Handling**: Support untuk nilai null/kosong

## Teknologi

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Bootstrap 5.3
- **Icons**: Font Awesome 6.0
- **XAMPP**: Apache + MySQL + PHP



