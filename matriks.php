<?php
session_start();
include('koneksi.php');

function executeQuery($conn, $query)
{
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Error executing query: " . mysqli_error($conn));
    }
    return $result;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SPK Metode SAW</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <style>
        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .calculation {
            font-size: 0.9em;
            color: #666;
        }

        .bg-header {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>

<body class="bg-light">
    <?php include('navbar.php'); ?>
    <main class="container py-5">
        <?php
        if (isset($_SESSION['status']) && $_SESSION['status'] == 'admin') {
            try {
                // Ambil data kriteria
                $kriteriaQuery = "SELECT id, nama, bobot, jenis as tipe FROM kriteria ORDER BY id";
                $kriteriaResult = executeQuery($conn, $kriteriaQuery);

                $kriteria = [];
                while ($row = mysqli_fetch_assoc($kriteriaResult)) {
                    $kriteria[] = $row;
                }

                // Ambil data alternatif
                $alternatifQuery = "SELECT id, nama FROM alternatif ORDER BY id";
                $alternatifResult = executeQuery($conn, $alternatifQuery);

                $alternatif = [];
                while ($row = mysqli_fetch_assoc($alternatifResult)) {
                    $alternatif[] = $row;
                }

                // Ambil data matrix
                $matrixQuery = "SELECT id_alternatif, id_kriteria, nilai FROM matrix";
                $matrixResult = executeQuery($conn, $matrixQuery);

                $matriksKeputusan = [];
                while ($row = mysqli_fetch_assoc($matrixResult)) {
                    $matriksKeputusan[$row['id_alternatif']][$row['id_kriteria']] = floatval($row['nilai']);
                }

                // Menghitung nilai min dan max
                $minMax = [];
                foreach ($kriteria as $k) {
                    $nilai_kriteria = [];
                    foreach ($matriksKeputusan as $nilai) {
                        if (isset($nilai[$k['id']])) {
                            $nilai_kriteria[] = $nilai[$k['id']];
                        }
                    }
                    $minMax[$k['id']] = [
                        'min' => !empty($nilai_kriteria) ? min($nilai_kriteria) : 1,
                        'max' => !empty($nilai_kriteria) ? max($nilai_kriteria) : 1
                    ];
                }

                // Normalisasi matriks
                $matriksNormalisasi = [];
                foreach ($alternatif as $a) {
                    foreach ($kriteria as $k) {
                        if (isset($matriksKeputusan[$a['id']][$k['id']])) {
                            $nilai = $matriksKeputusan[$a['id']][$k['id']];
                            if ($k['tipe'] == 'benefit') {
                                $matriksNormalisasi[$a['id']][$k['id']] = $nilai / $minMax[$k['id']]['max'];
                            } else {
                                $matriksNormalisasi[$a['id']][$k['id']] = $minMax[$k['id']]['min'] / $nilai;
                            }
                        }
                    }
                }

                // Hitung nilai preferensi
                $nilaiPreferensi = [];
                foreach ($alternatif as $a) {
                    $nilaiPreferensi[$a['id']] = 0;
                    $perhitungan = [];
                    foreach ($kriteria as $k) {
                        if (isset($matriksNormalisasi[$a['id']][$k['id']])) {
                            $nilai = $matriksNormalisasi[$a['id']][$k['id']] * $k['bobot'];
                            $nilaiPreferensi[$a['id']] += $nilai;
                            $perhitungan[$k['id']] = [
                                'normalisasi' => $matriksNormalisasi[$a['id']][$k['id']],
                                'bobot' => $k['bobot'],
                                'hasil' => $nilai
                            ];
                        }
                    }
                    $preferensiDetail[$a['id']] = $perhitungan;
                }
        ?>

                <!-- Bobot Preferensi -->
                <div class="card mb-4">
                    <h5 class="card-header bg-header">Bobot Preferensi (W)</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kriteria</th>
                                        <?php foreach ($kriteria as $k): ?>
                                            <th>C<?= substr($k['id'], 1) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Bobot</td>
                                        <?php foreach ($kriteria as $k): ?>
                                            <td><?= $k['bobot'] ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td>Jenis</td>
                                        <?php foreach ($kriteria as $k): ?>
                                            <td><?= ucfirst($k['tipe']) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matrix Keputusan -->
                <div class="card mb-4">
                    <h5 class="card-header bg-header">Matrix Keputusan (X)</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Alternatif</th>
                                        <?php foreach ($kriteria as $k): ?>
                                            <th>C<?= substr($k['id'], 1) ?><br>
                                                <small>(<?= ucfirst($k['tipe']) ?>)</small>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($alternatif as $a):
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td class="text-start"><?= htmlspecialchars($a['nama']) ?></td>
                                            <?php foreach ($kriteria as $k): ?>
                                                <td><?= isset($matriksKeputusan[$a['id']][$k['id']]) ?
                                                        number_format($matriksKeputusan[$a['id']][$k['id']], 0) : '0' ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-header">
                                        <td colspan="2">Max</td>
                                        <?php foreach ($kriteria as $k): ?>
                                            <td><?= number_format($minMax[$k['id']]['max'], 0) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr class="bg-header">
                                        <td colspan="2">Min</td>
                                        <?php foreach ($kriteria as $k): ?>
                                            <td><?= number_format($minMax[$k['id']]['min'], 0) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matriks Ternormalisasi -->
                <div class="card mb-4">
                    <h5 class="card-header bg-header">Matriks Ternormalisasi (R)</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Alternatif</th>
                                        <?php foreach ($kriteria as $k): ?>
                                            <th>R<?= substr($k['id'], 1) ?><br>
                                                <small>(<?= ucfirst($k['tipe']) ?>)</small>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($alternatif as $a):
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td class="text-start"><?= htmlspecialchars($a['nama']) ?></td>
                                            <?php foreach ($kriteria as $k): ?>
                                                <td>
                                                    <?php
                                                    if (isset($matriksNormalisasi[$a['id']][$k['id']])) {
                                                        $normalisasi = $matriksNormalisasi[$a['id']][$k['id']];
                                                        echo number_format($normalisasi, 2);

                                                        // Tampilkan perhitungan
                                                        $nilai = $matriksKeputusan[$a['id']][$k['id']];
                                                        if ($k['tipe'] == 'benefit') {
                                                            echo "<br><small class='calculation'>$nilai/{$minMax[$k['id']]['max']}</small>";
                                                        } else {
                                                            echo "<br><small class='calculation'>{$minMax[$k['id']]['min']}/$nilai</small>";
                                                        }
                                                    } else {
                                                        echo "0";
                                                    }
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-header">
                                        <td colspan="2">Bobot</td>
                                        <?php foreach ($kriteria as $k): ?>
                                            <td><?= $k['bobot'] ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Nilai Preferensi -->
                <div class="card mb-4">
                    <h5 class="card-header bg-header">Menghitung Nilai Preferensi (V)</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Alternatif</th>
                                        <?php foreach ($kriteria as $k): ?>
                                            <th>V<?= substr($k['id'], 1) ?></th>
                                        <?php endforeach; ?>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($alternatif as $a):
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td class="text-start"><?= htmlspecialchars($a['nama']) ?></td>
                                            <?php
                                            foreach ($kriteria as $k):
                                                if (isset($preferensiDetail[$a['id']][$k['id']])) {
                                                    $detail = $preferensiDetail[$a['id']][$k['id']];
                                            ?>
                                                    <td>
                                                        <?= number_format($detail['hasil'], 2) ?>
                                                        <br>
                                                        <small class="calculation">
                                                            (<?= number_format($detail['normalisasi'], 2) ?> × <?= $detail['bobot'] ?>)
                                                        </small>
                                                    </td>
                                                <?php } else { ?>
                                                    <td>0</td>
                                            <?php }
                                            endforeach; ?>
                                            <td class="fw-bold"><?= number_format($nilaiPreferensi[$a['id']], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <h5 class="card-header bg-header">Hasil Perangkingan</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Nama Alternatif</th>
                                        <th>Nilai</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ranking = 1;
                                    arsort($nilaiPreferensi);
                                    foreach ($nilaiPreferensi as $id => $nilai):
                                        $nama = '';
                                        foreach ($alternatif as $a) {
                                            if ($a['id'] == $id) {
                                                $nama = $a['nama'];
                                                break;
                                            }
                                        }
                                        // Tentukan keterangan berdasarkan nilai
                                        $keterangan = '';
                                        if ($nilai >= 0.5) {
                                            $keterangan = 'Layak';
                                        } else {
                                            $keterangan = 'Tidak Layak';
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $ranking++ ?></td>
                                            <td class="text-start"><?= htmlspecialchars($nama) ?></td>
                                            <td><?= number_format($nilai, 2) ?></td>
                                            <td><?= $keterangan ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

        <?php
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>" . htmlspecialchars($e->getMessage()) . "</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Anda belum login, silahkan login terlebih dahulu</div>";
        }
        ?>
    </main>

    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>