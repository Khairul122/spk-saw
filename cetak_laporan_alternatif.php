<?php
require_once('tcpdf/tcpdf.php');
include('koneksi.php');

// Inisialisasi PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Konfigurasi dasar
$pdf->SetCreator('PT. Zaffiliate Property Indonesia');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Laporan Data Alternatif');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Kop surat (tetap center untuk kop)
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 7, 'PT. ZAFFILIATE PROPERTY INDONESIA', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Jl. Jambi No.5 Ulak Karang, Padang-Sumatera Barat-Indonesia', 0, 1, 'C');
$pdf->Cell(0, 5, 'Telp: 082389036946', 0, 1, 'C');

// Garis pemisah
$pdf->Cell(0, 2, '', 0, 1);
$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY());
$pdf->Cell(0, 5, '', 0, 1);

// Judul Laporan (tetap center untuk judul)
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'LAPORAN DATA ALTERNATIF', 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1);

// Header Tabel
$pdf->SetFont('helvetica', 'B', 9);
$header = array('No', 'Nama', 'JK', 'Tgl Lahir', 'Status', 'Pekerjaan', 'Alamat', 'Telp');
foreach($header as $col) {
    $pdf->Cell(23.75, 7, $col, 1, 0, 'C');
}
$pdf->Ln();

// Isi Tabel
$pdf->SetFont('helvetica', '', 8);
$sql = "SELECT * FROM alternatif ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$no = 1;

while($row = mysqli_fetch_array($result)) {
    $pdf->Cell(23.75, 6, $no++, 1, 0, 'C');
    $pdf->Cell(23.75, 6, $row['nama'], 1, 0, 'L');
    $pdf->Cell(23.75, 6, $row['jenis_kelamin'], 1, 0, 'C');
    $pdf->Cell(23.75, 6, date('d/m/Y', strtotime($row['tanggal_lahir'])), 1, 0, 'C');
    $pdf->Cell(23.75, 6, $row['status'], 1, 0, 'L');
    $pdf->Cell(23.75, 6, $row['pekerjaan'], 1, 0, 'L');
    $pdf->Cell(23.75, 6, $row['alamat'], 1, 0, 'L');
    $pdf->Cell(23.75, 6, $row['no_telp'], 1, 0, 'L');
    $pdf->Ln();
}

// Bagian Tanda Tangan (rata kiri dengan offset untuk posisi di kanan)
$pdf->Cell(0, 15, '', 0, 1);
$pdf->SetFont('helvetica', '', 10);

// Menggunakan MultiCell dengan width spesifik dan alignment kiri
$leftMargin = $pdf->GetX();
$currentY = $pdf->GetY();
$signatureWidth = 50; // Lebar area tanda tangan

// Memindahkan cursor ke posisi yang tepat untuk tanda tangan di kanan
$pdf->SetXY($pdf->GetPageWidth() - $signatureWidth - 20, $currentY);

// Menambahkan elemen tanda tangan dengan alignment kiri
$pdf->MultiCell($signatureWidth, 5, "Padang, " . date('d/m/Y') . "\nManager,\n\n\n\nNama", 0, 'L');

// Output PDF
$pdf->Output('laporan_alternatif.pdf', 'I');
?>