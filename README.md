
# SPK TOPSIS - Pemilihan Karyawan Terbaik

Sistem Pendukung Keputusan (SPK) ini menggunakan metode TOPSIS untuk melakukan penilaian terhadap karyawan dan menentukan karyawan terbaik berdasarkan kriteria yang telah ditentukan. Web ini dirancang dengan bahasa pemrograman PHP 7.4.1.

## Fitur Utama
- **Manajemen Alternatif**: Tambah, edit, dan hapus data alternatif (karyawan).
- **Manajemen Kriteria**: Tambah, edit, dan hapus kriteria penilaian.
- **Manajemen Matriks Keputusan**: Edit matriks keputusan untuk pemrosesan data TOPSIS.
- **Penghitungan Hasil**: Proses perhitungan menggunakan metode TOPSIS untuk menentukan karyawan terbaik.
- **Pembuatan Surat**: Fitur untuk menghasilkan surat keputusan berdasarkan hasil pemilihan karyawan.

## Instalasi
1. Pastikan server Anda menggunakan PHP versi 7.4.1 atau yang lebih baru.
2. Clone atau unduh repository ini.
3. Pindahkan file ke direktori server web (contoh: `htdocs` di XAMPP).
4. Konfigurasikan database dan sesuaikan koneksi database di file konfigurasi (jika ada).
5. Jalankan aplikasi dengan membuka URL di browser, misalnya `http://localhost/spk-pegawai-terbaik`.

## Struktur Direktori
- `alternatif.php`: Mengelola data alternatif (karyawan).
- `kriteria.php`: Mengelola kriteria penilaian.
- `matriks.php`: Mengelola matriks keputusan untuk proses TOPSIS.
- `hasil.php`: Melakukan proses perhitungan dan menampilkan hasil pemilihan karyawan terbaik.
- `buat_surat.php`: Menghasilkan surat keputusan berdasarkan hasil pemilihan.

## Penggunaan
1. Tambahkan kriteria dan alternatif yang akan dinilai.
2. Atur nilai matriks keputusan untuk setiap alternatif terhadap kriteria.
3. Lakukan perhitungan dan lihat hasilnya di halaman hasil.

