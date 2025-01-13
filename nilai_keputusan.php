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
        <form class="card" method="post" action="tambah_nilai_keputusan.php">
            <h2 class="card-header py-5 text-center">INPUT NILAI</h2>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Alternatif</label>
                    <select class="form-select" name="id_alternatif">
                        <?php

                        include('koneksi.php');

                        $sql = 'SELECT * FROM alternatif';
                        $result = mysqli_query($conn, $sql);

                        $i = 1;
                        while ($row = mysqli_fetch_array($result)) {
                            $sql1 = 'SELECT * FROM matrix WHERE id_alternatif = ';
                            $sql1 = $sql1 . $row['id'];
                            $result1 = mysqli_query($conn, $sql1);
                            if (mysqli_num_rows($result1) > 0) {
                                continue;
                            } else {

                        ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['nama'] ?></option>


                        <?php
                            }
                        }

                        ?>
                    </select>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Kriteria</th>
                            <th scope="col">Bobot</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql = 'SELECT * FROM kriteria';
                        $result = mysqli_query($conn, $sql);

                        $i = 1;
                        while ($row = mysqli_fetch_array($result)) {

                        ?>

                            <tr>
                                <th scope="row"><?php echo $row['id'] ?></th>
                                <td><?php echo $row['nama'] ?></td>
                                <td><?php echo $row['bobot'] ?></td>
                                <td><?php echo $row['jenis'] ?></td>
                                <td><input class="form-control" type="number" name="nilai_<?php echo $row['id'] ?>" /></td>
                            </tr>

                        <?php

                        }

                        ?>
                        <!-- Num rows -->
                        <input type="hidden" name="num_rows" value="<?php echo mysqli_num_rows($result) ?>" />
                    </tbody>
                </table>
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="bi bi-save-fill"></i>
                    Simpan</button>
            </div>
        </form>
    </main>
    <?php

    ?>

    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>