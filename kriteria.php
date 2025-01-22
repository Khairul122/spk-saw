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
    <?php include('navbar.php'); ?>

    <main class="container py-5">
        <!-- Button to Trigger Add Modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-circle"></i> Tambah Kriteria
        </button>

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kriteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="tambah_kriteria.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">ID</label>
                                <input class="form-control" name="id" type="text" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kriteria</label>
                                <input class="form-control" name="kriteria" type="text" required />
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col">
                                    <label class="form-label">Bobot</label>
                                    <input class="form-control" name="bobot" type="float" step="0.01" required />
                                </div>
                                <div class="col">
                                    <label class="form-label">Jenis</label>
                                    <select class="form-select" name="jenis" required>
                                        <option value="benefit">benefit</option>
                                        <option value="cost">cost</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kriteria Table -->
        <div class="card">
            <h2 class="card-header py-4 text-center">TABEL KRITERIA</h2>
            <div class="card-body">
                <table class="table nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kriteria</th>
                            <th>Bobot</th>
                            <th>Jenis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('koneksi.php');
                        $sql = 'SELECT * FROM kriteria';
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['bobot']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenis']); ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row['id']; ?>">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <a class="btn btn-danger btn-sm" href="hapus_kriteria.php?id=<?php echo $row['id']; ?>"
                                        onclick="return confirm('Apakah anda ingin menghapus kriteria <?php echo $row['nama']; ?>')">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </a>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Kriteria</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="post" action="edit_kriteria.php" >
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">ID</label>
                                                    <input class="form-control" name="id" type="text" value="<?php echo htmlspecialchars($row['id']); ?>" readonly />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kriteria</label>
                                                    <input class="form-control" name="kriteria" type="text" value="<?php echo htmlspecialchars($row['nama']); ?>" required />
                                                </div>
                                                <div class="row g-3 mb-3">
                                                    <div class="col">
                                                        <label class="form-label">Bobot</label>
                                                        <input class="form-control" name="bobot" type="float" step="0.01" value="<?php echo htmlspecialchars($row['bobot']); ?>" required />
                                                    </div>
                                                    <div class="col">
                                                        <label class="form-label">Jenis</label>
                                                        <select class="form-select" name="jenis" required>
                                                            <option value="benefit" <?php echo ($row['jenis'] == 'benefit') ? 'selected' : ''; ?>>benefit</option>
                                                            <option value="cost" <?php echo ($row['jenis'] == 'cost') ? 'selected' : ''; ?>>cost</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
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

    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                responsive: true
            });
        });
    </script>
</body>

</html>
