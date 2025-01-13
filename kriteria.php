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
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.11.3/r-2.2.9/datatables.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/r-2.2.9/datatables.min.js"></script>
</head>

<body class="bg-light">
    <?php
    include('navbar.php');
    ?>
    <main class="container py-5">
        <form class="card mb-3" method="post" action="tambah_kriteria.php">
            <h2 class="card-header py-5 text-center">TAMBAH KRITERIA</h2>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">ID</label>
                    <input class="form-control" name="id" type="text" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Kriteria</label>
                    <input class="form-control" name="kriteria" type="text" />
                </div>
                <div class="row g-3 mb-3">
                    <div class="col">
                        <label class="form-label">Bobot</label>
                        <input class="form-control" name="bobot" type="float" />
                    </div>
                    <div class="col">
                        <label class="form-label">Jenis</label>
                        <select class="form-select" name="jenis">
                            <option value="benefit">benefit</option>
                            <option value="cost">cost</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="bi bi-save-fill"></i>
                    Simpan</button>
            </div>
        </form>

        <div class="card">
            <h2 class="card-header py-5 text-center">TABEL KRITERIA</h2>
            <div class="card-body">
                <table class="table nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Kriteria</th>
                            <th scope="col">Bobot</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        include('koneksi.php');

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
                                <td><a type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit<?php echo $row['id']; ?>"><i
                                            class="bi bi-pencil-square"></i> Edit</a>
                                    <a class="btn btn-danger" href="hapus_kriteria.php?id=<?php echo $row['id'] ?>"
                                        onclick="return confirm('Apakah anda ingin menghapus kriteria <?php echo $row['nama'] ?>')"><i
                                            class="bi bi-trash-fill"></i> Hapus</a>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?php echo $row['id']; ?>" tabindex="-1"
                                aria-labelledby="modelEditLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Kriteria</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="edit_kriteria.php">
                                            <div class="modal-body">
                                                <?php
                                                $id = $row['id'];
                                                $sql1 = "SELECT * FROM kriteria WHERE id = '$id'";
                                                $result1 = mysqli_query($conn, $sql1);
                                                while ($row1 = mysqli_fetch_array($result1)) {
                                                ?>
                                                    <div class="mb-3">
                                                        <label class="form-label">ID</label>
                                                        <input class="form-control" name="id" type="text"
                                                            value="<?php echo $row1['id']; ?>" readonly />
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kriteria</label>
                                                        <input class="form-control" name="kriteria" type="text"
                                                            value="<?php echo $row1['nama']; ?>" />
                                                    </div>
                                                    <div class="row g-3 mb-3">
                                                        <div class="col">
                                                            <label class="form-label">Bobot</label>
                                                            <input class="form-control" name="bobot" type="float"
                                                                value="<?php echo $row1['bobot'] ?>" />
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label">Jenis</label>
                                                            <select class="form-select" name="jenis">
                                                                <option value="benefit" <?php if ($row1['jenis'] == 'benefit') {
                                                                                            echo 'selected';
                                                                                        } ?>>benefit</option>
                                                                <option value="cost" <?php if ($row1['jenis'] == 'cost') {
                                                                                            echo 'selected';
                                                                                        } ?>>cost</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php

                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php

    ?>

    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        $('document').ready(function() {
            $('.table').DataTable({
                responsive: true,
                columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                }, ]
            })
        })
    </script>
</body>

</html>