<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form dan lakukan sanitasi
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $kriteria = mysqli_real_escape_string($conn, $_POST['kriteria']);
    $bobot = (float) $_POST['bobot']; // Pastikan bobot berupa angka
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);

    // Validasi input
    if (!empty($id) && !empty($kriteria) && !empty($jenis) && $bobot > 0) {
        // Cek apakah ID sudah ada di database
        $checkQuery = "SELECT * FROM kriteria WHERE id = '$id'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="alert alert-danger">ID kriteria sudah ada. Harap gunakan ID lain. 
                  <a href="kriteria.php" class="alert-link">Kembali</a></div>';
        } else {
            // Masukkan data ke dalam database
            $sql = "INSERT INTO kriteria (id, nama, bobot, jenis) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssds", $id, $kriteria, $bobot, $jenis);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect jika berhasil
                header('Location: kriteria.php');
                exit();
            } else {
                echo '<div class="alert alert-danger">Gagal menambah data. ' . mysqli_error($conn) . '</div>';
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        echo '<div class="alert alert-danger">Harap isi semua data dengan benar.</div>';
    }
}
mysqli_close($conn);
?>
