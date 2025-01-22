<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Alternatif - SPK SAW</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/r-2.2.9/datatables.min.css" />

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/r-2.2.9/datatables.min.js"></script>
</head>

<body class="bg-light">
    <?php include('navbar.php'); ?>

    <main class="container py-5">
        <!-- Button to trigger Add Modal -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-circle"></i> Tambah Data Alternatif
        </button>

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data Alternatif</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="tambah_alternatif.php" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input class="form-control" name="nama" type="text" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input class="form-control" name="tanggal_lahir" type="date" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input class="form-control" name="status" type="text" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pekerjaan</label>
                                <input class="form-control" name="pekerjaan" type="text" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No Telepon</label>
                                <input class="form-control" name="no_telp" type="text" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="card-header text-center py-4 bg-primary text-white">DATA ALTERNATIF</h2>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dataTable">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Status</th>
                                <th>Pekerjaan</th>
                                <th>Alamat</th>
                                <th>No Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include('koneksi.php');
                            $sql = 'SELECT * FROM alternatif ORDER BY id DESC';
                            $result = mysqli_query($conn, $sql);
                            $i = 1;
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal_lahir'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pekerjaan']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <a href="hapus_alternatif.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Data Alternatif</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="edit_alternatif.php" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label>
                                                        <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Jenis Kelamin</label>
                                                        <select class="form-select" name="jenis_kelamin" required>
                                                            <option value="L" <?php echo ($row['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                                            <option value="P" <?php echo ($row['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal Lahir</label>
                                                        <input type="date" class="form-control" name="tanggal_lahir" value="<?php echo $row['tanggal_lahir']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <input type="text" class="form-control" name="status" value="<?php echo htmlspecialchars($row['status']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Pekerjaan</label>
                                                        <input type="text" class="form-control" name="pekerjaan" value="<?php echo htmlspecialchars($row['pekerjaan']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat</label>
                                                        <textarea class="form-control" name="alamat" required><?php echo htmlspecialchars($row['alamat']); ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">No Telepon</label>
                                                        <input type="text" class="form-control" name="no_telp" value="<?php echo htmlspecialchars($row['no_telp']); ?>" required>
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
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                responsive: true,
                order: [
                    [0, 'asc']
                ]
            });
        });
    </script>
</body>

</html>
