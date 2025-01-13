<?php
require_once('tcpdf/tcpdf.php');
include('koneksi.php');

function executeQuery($conn, $query) {
    $result = mysqli_query($conn, $query);
    if (!$result) {
        throw new Exception("Error executing query: " . mysqli_error($conn));
    }
    return $result;
}

// Inisialisasi PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('PT. Zaffiliate Property Indonesia');
$pdf->SetTitle('Laporan Perhitungan SPK');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Halaman Pertama
$pdf->AddPage();

// Kop Surat
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 5, 'PT. ZAFFILIATE PROPERTY INDONESIA', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 4, 'Jl. Jambi No.5 Ulak Karang, Padang-Sumatera Barat-Indonesia', 0, 1, 'C');
$pdf->Cell(0, 4, 'Telp: 082389036946', 0, 1, 'C');

// Garis Pembatas
$pdf->Ln(2);
$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 267, $pdf->GetY());
$pdf->Ln(1);
$pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 267, $pdf->GetY());
$pdf->Ln(3);

// Judul
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 5, 'LAPORAN PERHITUNGAN', 0, 1, 'C');
$pdf->Ln(5);

try {
    // Ambil Data Kriteria
    $kriteriaQuery = "SELECT id, nama, bobot, jenis as tipe FROM kriteria ORDER BY id";
    $kriteriaResult = executeQuery($conn, $kriteriaQuery);
    $kriteria = [];
    while ($row = mysqli_fetch_assoc($kriteriaResult)) {
        $kriteria[] = $row;
    }

    // Ambil Data Alternatif
    $alternatifQuery = "SELECT id, nama FROM alternatif ORDER BY id";
    $alternatifResult = executeQuery($conn, $alternatifQuery);
    $alternatif = [];
    while ($row = mysqli_fetch_assoc($alternatifResult)) {
        $alternatif[] = $row;
    }

    // Ambil Data Matrix
    $matrixQuery = "SELECT id_alternatif, id_kriteria, nilai FROM matrix";
    $matrixResult = executeQuery($conn, $matrixQuery);
    $matriksKeputusan = [];
    while ($row = mysqli_fetch_assoc($matrixResult)) {
        $matriksKeputusan[$row['id_alternatif']][$row['id_kriteria']] = floatval($row['nilai']);
    }

    // 1. Tabel Bobot Preferensi
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 5, '1. BOBOT PREFERENSI (W)', 0, 1, 'L');
    $pdf->Ln(2);

    $pdf->SetFont('helvetica', '', 9);
    $col_width = 267 / (count($kriteria) + 1);
    
    // Header
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell($col_width, 5, 'Kriteria', 1, 0, 'C', true);
    foreach ($kriteria as $k) {
        $pdf->Cell($col_width, 5, 'C' . substr($k['id'], 1), 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Bobot
    $pdf->Cell($col_width, 5, 'Bobot', 1, 0, 'C');
    foreach ($kriteria as $k) {
        $pdf->Cell($col_width, 5, $k['bobot'], 1, 0, 'C');
    }
    $pdf->Ln();

    // Jenis
    $pdf->Cell($col_width, 5, 'Jenis', 1, 0, 'C');
    foreach ($kriteria as $k) {
        $pdf->Cell($col_width, 5, ucfirst($k['tipe']), 1, 0, 'C');
    }
    $pdf->Ln(8);

    // 2. Matrix Keputusan
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 5, '2. MATRIX KEPUTUSAN (X)', 0, 1, 'L');
    $pdf->Ln(2);

    // Header Matrix
    $pdf->SetFont('helvetica', '', 9);
    $w = array(10, 60);
    $remaining_width = (267 - 70) / count($kriteria);
    
    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell($w[0], 5, 'No', 1, 0, 'C', true);
    $pdf->Cell($w[1], 5, 'Alternatif', 1, 0, 'C', true);
    foreach ($kriteria as $k) {
        $pdf->Cell($remaining_width, 5, 'C' . substr($k['id'], 1), 1, 0, 'C', true);
    }
    $pdf->Ln();

    // Isi Matrix
    $no = 1;
    foreach ($alternatif as $a) {
        $pdf->Cell($w[0], 5, $no++, 1, 0, 'C');
        $pdf->Cell($w[1], 5, $a['nama'], 1, 0, 'L');
        foreach ($kriteria as $k) {
            $nilai = isset($matriksKeputusan[$a['id']][$k['id']]) ? 
                     number_format($matriksKeputusan[$a['id']][$k['id']], 0) : '0';
            $pdf->Cell($remaining_width, 5, $nilai, 1, 0, 'C');
        }
        $pdf->Ln();
    }

    // Min Max
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

    $pdf->SetFillColor(220, 220, 220);
    $pdf->Cell($w[0] + $w[1], 5, 'Maximum', 1, 0, 'C', true);
    foreach ($kriteria as $k) {
        $pdf->Cell($remaining_width, 5, number_format($minMax[$k['id']]['max'], 0), 1, 0, 'C', true);
    }
    $pdf->Ln();
    
    $pdf->Cell($w[0] + $w[1], 5, 'Minimum', 1, 0, 'C', true);
    foreach ($kriteria as $k) {
        $pdf->Cell($remaining_width, 5, number_format($minMax[$k['id']]['min'], 0), 1, 0, 'C', true);
    }
    $pdf->Ln(8);

    // 3. Matrix Ternormalisasi
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 5, '3. MATRIX TERNORMALISASI (R)', 0, 1, 'L');
    $pdf->Ln(2);

    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell($w[0], 5, 'No', 1, 0, 'C', true);
    $pdf->Cell($w[1], 5, 'Alternatif', 1, 0, 'C', true);
    foreach ($kriteria as $k) {
        $pdf->Cell($remaining_width, 5, 'R' . substr($k['id'], 1), 1, 0, 'C', true);
    }
    $pdf->Ln();

    $no = 1;
    foreach ($alternatif as $a) {
        $pdf->Cell($w[0], 5, $no++, 1, 0, 'C');
        $pdf->Cell($w[1], 5, $a['nama'], 1, 0, 'L');
        foreach ($kriteria as $k) {
            if (isset($matriksKeputusan[$a['id']][$k['id']])) {
                $nilai = $matriksKeputusan[$a['id']][$k['id']];
                if ($k['tipe'] == 'benefit') {
                    $normalisasi = $nilai / $minMax[$k['id']]['max'];
                } else {
                    $normalisasi = $minMax[$k['id']]['min'] / $nilai;
                }
                $pdf->Cell($remaining_width, 5, number_format($normalisasi, 2), 1, 0, 'C');
            } else {
                $pdf->Cell($remaining_width, 5, '0', 1, 0, 'C');
            }
        }
        $pdf->Ln();
    }
    $pdf->Ln(8);

    // 4. Hasil Perangkingan
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 5, '4. HASIL PERANGKINGAN', 0, 1, 'L');
    $pdf->Ln(2);

    // Query Hasil
    $queryHasil = "SELECT 
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

    $resultHasil = executeQuery($conn, $queryHasil);

    // Header Tabel Hasil
    $pdf->SetFont('helvetica', '', 9);
    $w2 = array(15, 100, 30, 40);
    
    $pdf->Cell($w2[0], 5, 'Rank', 1, 0, 'C', true);
    $pdf->Cell($w2[1], 5, 'Nama Alternatif', 1, 0, 'C', true);
    $pdf->Cell($w2[2], 5, 'Nilai', 1, 0, 'C', true);
    $pdf->Cell($w2[3], 5, 'Keterangan', 1, 0, 'C', true);
    $pdf->Ln();

    $rank = 1;
    while($row = mysqli_fetch_array($resultHasil)) {
        $keterangan = ($row['nilai_akhir'] >= 0.5) ? 'Layak' : 'Tidak Layak';
        $pdf->Cell($w2[0], 5, $rank++, 1, 0, 'C');
        $pdf->Cell($w2[1], 5, $row['nama'], 1, 0, 'L');
        $pdf->Cell($w2[2], 5, number_format($row['nilai_akhir'], 2), 1, 0, 'C');
        $pdf->Cell($w2[3], 5, $keterangan, 1, 0, 'C');
        $pdf->Ln();
    }

    // Tanda Tangan
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', '', 10);
    $rightPosition = 200;
    $pdf->SetX($rightPosition);
    $pdf->Cell(60, 4, 'Padang, ' . date('d/m/Y'), 0, 1, 'L');
    $pdf->SetX($rightPosition);
    $pdf->Cell(60, 4, 'Manager,', 0, 1, 'L');
    $pdf->Ln(15);
    $pdf->SetX($rightPosition);
    $pdf->Cell(60, 4, '___________________', 0, 1, 'L');

    // Output PDF
    $pdf->Output('laporan_spk.pdf', 'I');

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>