<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $pekerjaan = mysqli_real_escape_string($conn, $_POST['pekerjaan']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    $sql = "INSERT INTO alternatif (nama, jenis_kelamin, tanggal_lahir, status, pekerjaan, alamat, no_telp) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssss", $nama, $jenis_kelamin, $tanggal_lahir, $status, $pekerjaan, $alamat, $no_telp);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: alternatif.php');
        exit();
    } else {
        die("Error: " . mysqli_error($conn));
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>