<?php 
require_once 'koneksi.php';

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "DELETE FROM alternatif WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

$message = $result 
    ? 'Data berhasil dihapus!' 
    : 'Gagal menghapus data: ' . mysqli_error($conn);

echo "<script>
        alert('$message');
        window.location.href = 'alternatif.php';
      </script>";

mysqli_close($conn);
?>