<?php
require_once('tcpdf/tcpdf.php');
include('koneksi.php');

// Inisialisasi PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Konfigurasi dasar
$pdf->SetCreator('PT. Zaffiliate Property Indonesia');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Laporan Data Kriteria SPK');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Kop surat
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 7, 'PT. ZAFFILIATE PROPERTY INDONESIA', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Jl. Jambi No.5 Ulak Karang, Padang-Sumatera Barat-Indonesia', 0, 1, 'C');
$pdf->Cell(0, 5, 'Telp: 082389036946', 0, 1, 'C');

// Garis pemisah
$pdf->Cell(0, 2, '', 0, 1);
$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY());
$pdf->Cell(0, 5, '', 0, 1);

// Judul Laporan
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'LAPORAN KRITERIA', 0, 1, 'C');
$pdf->Cell(0, 5, '', 0, 1);

// Header Tabel
$pdf->SetFont('helvetica', 'B', 10);
$header = array('No', 'ID Kriteria', 'Nama Kriteria', 'Bobot', 'Jenis');
$w = array(15, 35, 60, 40, 40); // Lebar kolom

// Header tabel dengan border dan centering
foreach($header as $i => $h) {
    $pdf->Cell($w[$i], 7, $h, 1, 0, 'C');
}
$pdf->Ln();

// Isi Tabel
$pdf->SetFont('helvetica', '', 9);
$sql = "SELECT * FROM kriteria ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
$no = 1;

while($row = mysqli_fetch_array($result)) {
    $pdf->Cell($w[0], 6, $no++, 1, 0, 'C');
    $pdf->Cell($w[1], 6, $row['id'], 1, 0, 'L');
    $pdf->Cell($w[2], 6, $row['nama'], 1, 0, 'L');
    $pdf->Cell($w[3], 6, $row['bobot'], 1, 0, 'C');
    $pdf->Cell($w[4], 6, $row['jenis'], 1, 0, 'C');
    $pdf->Ln();
}

// Total Bobot
$sql_total = "SELECT SUM(bobot) as total_bobot FROM kriteria";
$result_total = mysqli_query($conn, $sql_total);
$row_total = mysqli_fetch_array($result_total);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(array_sum($w) - 40, 6, 'Total Bobot', 1, 0, 'R');
$pdf->Cell(40, 6, number_format($row_total['total_bobot'], 2), 1, 1, 'C');

// Bagian Tanda Tangan
$pdf->Cell(0, 20, '', 0, 1);
$pdf->SetFont('helvetica', '', 10);

// Posisi tanda tangan di kanan dengan alignment kiri
$rightPosition = 130; // Posisi X untuk bagian kanan
$pdf->SetX($rightPosition);
$pdf->Cell(60, 5, 'Padang, ' . date('d/m/Y'), 0, 1, 'L');
$pdf->SetX($rightPosition);
$pdf->Cell(60, 5, 'Manager,', 0, 1, 'L');
$pdf->Cell(0, 20, '', 0, 1);
$pdf->SetX($rightPosition);
$pdf->Cell(60, 5, '___________________', 0, 1, 'L');

// Output PDF
$pdf->Output('laporan_kriteria_spk.pdf', 'I');
?>