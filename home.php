<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-device=1.0" />
    <title>SPK SAW</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <?php
    include('navbar.php');
    ?>
    <main class="container py-5">
        <div class="alert alert-primary" role="alert">
            <h1>
                <?php 
                if(!empty($_SESSION['status'])) {
                    echo "Selamat Datang, " . 
                         ($_SESSION['status'] == 'admin' ? 'Administrator' : 'Pimpinan');
                } else {
                    echo "Selamat Datang";
                }
                ?>
            </h1>
            <hr />
            <p>SPK (Sistem Pendukung Keputusan) adalah sistem yang dirancang untuk membantu dalam proses pengambilan keputusan dengan menggunakan kriteria-kriteria yang telah ditentukan. Sistem ini mengimplementasikan metode SAW (Simple Additive Weighting), sebuah metode penjumlahan terbobot yang sederhana namun efektif untuk mengevaluasi beberapa alternatif berdasarkan sejumlah kriteria yang ditetapkan.</p>
        </div>
    </main>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>