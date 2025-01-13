<?php
require_once('tcpdf/tcpdf.php');
include('koneksi.php');

class MYPDF extends TCPDF {
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . 
            ' dari ' . $this->getAliasNbPages(), 0, false, 'C', 0);
    }
}

// Inisialisasi PDF
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('PT. Zaffiliate Property Indonesia');
$pdf->SetTitle('Laporan Hasil Perangkingan SPK');
$pdf->setPrintHeader(false);
$pdf->AddPage();

// Kop Surat
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 7, 'PT. ZAFFILIATE PROPERTY INDONESIA', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Jl. Jambi No.5 Ulak Karang, Padang-Sumatera Barat-Indonesia', 0, 1, 'C');
$pdf->Cell(0, 5, 'Telp: 082389036946', 0, 1, 'C');

// Garis Pembatas
$pdf->Cell(0, 2, '', 0, 1);
$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 190, $pdf->GetY());
$pdf->Cell(0, 5, '', 0, 1);

// Judul Laporan
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'LAPORAN PENILAIAN', 0, 1, 'C');
$pdf->Ln(10);

// Informasi Tanggal
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d/m/Y'), 0, 1, 'L');
$pdf->Ln(5);

// Tabel Hasil Perangkingan
$pdf->SetFont('helvetica', 'B', 10);

// Header Tabel
$w = array(15, 85, 30, 45); // Lebar kolom
$header = array('Ranking', 'Nama', 'Nilai', 'Keterangan');

// Warna header
$pdf->SetFillColor(210, 210, 210);
foreach($header as $i => $h) {
    $pdf->Cell($w[$i], 7, $h, 1, 0, 'C', true);
}
$pdf->Ln();

// Isi Tabel
$pdf->SetFont('helvetica', '', 10);
$pdf->SetFillColor(245, 245, 245);

// Ambil data untuk perangkingan
$query = "SELECT 
            a.nama,
            ROUND(SUM(
                CASE 
                    WHEN k.jenis = 'benefit' 
                    THEN (m.nilai / (SELECT MAX(nilai) FROM matrix WHERE id_kriteria = m.id_kriteria))
                    ELSE ((SELECT MIN(nilai) FROM matrix WHERE id_kriteria = m.id_kriteria) / m.nilai)
                END * k.bobot
            ), 2) as nilai_akhir
          FROM alternatif a
          JOIN matrix m ON a.id = m.id_alternatif
          JOIN kriteria k ON m.id_kriteria = k.id
          GROUP BY a.id, a.nama
          ORDER BY nilai_akhir DESC";

$result = mysqli_query($conn, $query);
$rank = 1;
$fill = false;

while($row = mysqli_fetch_array($result)) {
    $keterangan = ($row['nilai_akhir'] >= 0.5) ? 'Layak' : 'Tidak Layak';
    
    $pdf->Cell($w[0], 6, $rank++, 1, 0, 'C', $fill);
    $pdf->Cell($w[1], 6, $row['nama'], 1, 0, 'L', $fill);
    $pdf->Cell($w[2], 6, number_format($row['nilai_akhir'], 2), 1, 0, 'C', $fill);
    $pdf->Cell($w[3], 6, $keterangan, 1, 0, 'C', $fill);
    $pdf->Ln();
    $fill = !$fill;
}

// Keterangan
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 6, 'Keterangan:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, '- Nilai >= 0.5 : Layak', 0, 1, 'L');
$pdf->Cell(0, 6, '- Nilai < 0.5 : Tidak Layak', 0, 1, 'L');

// Tanda Tangan
$pdf->Ln(20);
$pdf->SetFont('helvetica', '', 10);

// Posisi tanda tangan di kanan dengan alignment kiri
$rightPosition = 130;
$pdf->SetX($rightPosition);
$pdf->Cell(60, 5, 'Padang, ' . date('d/m/Y'), 0, 1, 'L');
$pdf->SetX($rightPosition);
$pdf->Cell(60, 5, 'Manager,', 0, 1, 'L');
$pdf->Cell(0, 20, '', 0, 1);
$pdf->SetX($rightPosition);
$pdf->Cell(60, 5, '___________________', 0, 1, 'L');

// Output PDF
$pdf->Output('hasil_perangkingan_spk.pdf', 'I');
?>