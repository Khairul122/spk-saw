<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $pekerjaan = mysqli_real_escape_string($conn, $_POST['pekerjaan']);
    $penghasilan = mysqli_real_escape_string($conn, $_POST['penghasilan']);
    $uang_muka = mysqli_real_escape_string($conn, $_POST['uang_muka']);
    $angsuran = mysqli_real_escape_string($conn, $_POST['angsuran']);

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM matrix")) > 0) {
        echo '<div class="alert alert-danger">Harap hapus data pada halaman nilai keputusan terlebih dahulu. 
              <a href="alternatif.php" class="alert-link">Kembali</a></div>';
    } else {
        $sql = "INSERT INTO alternatif (nama, jenis_kelamin, pekerjaan, penghasilan, uang_muka, angsuran) 
                VALUES (?, ?, ?, ?, ?, ?)";
                
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssddd", $nama, $jenis_kelamin, $pekerjaan, $penghasilan, $uang_muka, $angsuran);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: alternatif.php');
            exit();
        } else {
            echo '<div class="alert alert-danger">Gagal menambah data. ' . mysqli_error($conn) . '</div>';
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conn);
?>