<?php
include('koneksi.php');

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: alternatif.php');
    exit();
}

try {
    // Sanitasi input
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nama = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
    $jenis_kelamin = filter_input(INPUT_POST, 'jenis_kelamin', FILTER_SANITIZE_STRING);
    $tanggal_lahir = filter_input(INPUT_POST, 'tanggal_lahir', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $pekerjaan = filter_input(INPUT_POST, 'pekerjaan', FILTER_SANITIZE_STRING);
    $alamat = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
    $no_telp = filter_input(INPUT_POST, 'no_telp', FILTER_SANITIZE_STRING);

    // Prepared statement untuk mencegah SQL injection
    $stmt = $conn->prepare("UPDATE alternatif SET 
        nama=?, 
        jenis_kelamin=?, 
        tanggal_lahir=?, 
        status=?,
        pekerjaan=?,
        alamat=?,
        no_telp=? 
        WHERE id=?");
    
    $stmt->bind_param("sssssssi", 
        $nama, 
        $jenis_kelamin, 
        $tanggal_lahir, 
        $status,
        $pekerjaan,
        $alamat,
        $no_telp,
        $id
    );

    if ($stmt->execute()) {
        header('Location: alternatif.php?status=success');
        exit();
    } else {
        throw new Exception("Gagal mengupdate data");
    }

} catch (Exception $e) {
    // Redirect dengan pesan error
    header('Location: alternatif.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>