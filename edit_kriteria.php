<?php
// Mulai sesi
session_start();

// Sertakan file koneksi
include('koneksi.php');

// Periksa apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan lakukan sanitasi
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $kriteria = mysqli_real_escape_string($conn, $_POST['kriteria']);
    $bobot = (float) $_POST['bobot']; // Pastikan bobot berupa angka
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);

    // Validasi input
    if (!empty($id) && !empty($kriteria) && !empty($jenis) && $bobot > 0) {
        // Buat query
        $sql = "UPDATE kriteria SET nama='$kriteria', bobot=$bobot, jenis='$jenis' WHERE id='$id'";

        // Eksekusi query dan cek hasilnya
        if (mysqli_query($conn, $sql)) {
            // Redirect jika berhasil
            $_SESSION['success'] = "Data berhasil diperbarui.";
            header('Location: kriteria.php');
            exit();
        } else {
            // Tampilkan pesan error jika query gagal
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Tampilkan pesan jika data tidak valid
        echo "Harap isi semua data dengan benar.";
    }
} else {
    // Jika tidak ada data POST, kembalikan ke halaman utama
    header('Location: kriteria.php');
    exit();
}
?>
