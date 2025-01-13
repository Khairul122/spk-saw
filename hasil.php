<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPK SAW</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/r-2.2.9/datatables.min.css" />
    
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/r-2.2.9/datatables.min.js"></script>
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
        <main class="container py-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Laporan</h4>
                </div>
                <div class="card-body">
                    <a href="cetak_laporan_alternatif.php" class="btn btn-primary mb-2"><i class="bi bi-printer-fill" target="_blank"></i> Cetak Laporan Alternatif</a>
                    <a href="cetak_laporan_kriteria.php" class="btn btn-primary mb-2"><i class="bi bi-printer-fill" target="_blank"></i> Cetak Laporan Kriteria</a>
                    <a href="cetak_laporan_penilaian.php" class="btn btn-primary mb-2"><i class="bi bi-printer-fill" target="_blank"></i> Cetak Laporan Penilaian</a>
                    <a href="cetak_laporan_perhitungan_saw.php" class="btn btn-primary mb-2"><i class="bi bi-printer-fill" target="_blank"></i> Cetak Laporan Perhitungan SAW</a>
                </div>
            </div>
        </main>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                responsive: true,
                order: [[0, 'asc']]
            });
        });
    </script>
</body>
</html>
