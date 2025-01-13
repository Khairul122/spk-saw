<?php

include('koneksi.php');

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nrk = $_POST['nrk'];
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $masa_aktif = $_POST['masa_aktif'];

    $sql = "UPDATE alternatif SET 
    nrk='$nrk', 
    nama='$nama', 
    jabatan='$jabatan', 
    masa_aktif='$masa_aktif' 
    WHERE id='$id'";

    mysqli_query($conn, $sql);
    header('location:alternatif.php');
}