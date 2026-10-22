<?php
// Escape HTML, lalu bungkus huruf Mandarin/CJK dengan font Noto Sans SC (Dompdf tidak
// berpindah font per huruf, jadi bagian Mandarin harus ditandai sendiri).
$e = function ($teks) {
    $h = htmlspecialchars((string) $teks);
    return preg_replace('/[\x{2E80}-\x{9FFF}\x{F900}-\x{FAFF}\x{FF00}-\x{FFEF}\x{3000}-\x{303F}]+/u', '<span class="cjk">$0</span>', $h);
};
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>List Tamu <?= $e($clients->client_name) ?></title>
    <style>
        @page { margin: 40px 45px; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12px; color: #000; }
        .judul { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .judul td { padding: 0; vertical-align: top; }
        .acara { font-size: 17px; font-weight: bold; }
        .nama-klien { font-size: 17px; font-weight: bold; text-align: right; }
        .info { margin: 0 0 14px 0; line-height: 1.3; }
        .judul-daftar { font-weight: bold; font-size: 13px; margin: 0 0 6px 3px; }
        table.tamu { width: 92%; border-collapse: collapse; }
        table.tamu th, table.tamu td { border: 1px solid #000; padding: 2px 3px; font-size: 12px; }
        table.tamu thead { display: table-header-group; }
        table.tamu tr { page-break-inside: avoid; }
        table.tamu th { text-align: center; font-weight: bold; }
        .c { text-align: center; }
        .cjk { font-family: "Noto Sans SC", sans-serif; }
        .kotak { display: inline-block; width: 8px; height: 8px; border: 1px solid #000; margin-right: 3px; }
        .ket { line-height: 1.35; }
    </style>
</head>
<body>
    <table class="judul">
        <tr>
            <td class="acara"><?= $e($judul_acara) ?></td>
            <td class="nama-klien"><?= $e(mb_strtoupper($clients->client_name)) ?></td>
        </tr>
    </table>

    <p class="info">
        <?= $e($clients->location) ?><br>
        <?= $e(format_tanggal_acara($clients->wedding_date)) ?>
    </p>

    <p class="judul-daftar"><?= $e($judul_daftar) ?></p>

    <table class="tamu">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:12%">Honorific</th>
                <th style="width:30%">Guest Name</th>
                <th style="width:31%">Category</th>
                <th style="width:22%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Baris kosong bernomor (bisa diisi tulisan tangan): data dipenuhkan sampai halaman
            // terakhirnya penuh, lalu ditambah SATU halaman kosong lagi. Halaman 1 memuat lebih
            // sedikit baris (ada header acara), halaman lanjutan memuat lebih banyak.
            $baris_halaman_1 = 23;
            $baris_halaman_lanjutan = 27;
            $jumlah_tamu = count($tamu);
            $halaman_data = $jumlah_tamu <= $baris_halaman_1
                ? 1
                : 1 + (int) ceil(($jumlah_tamu - $baris_halaman_1) / $baris_halaman_lanjutan);
            $jumlah_baris = $baris_halaman_1 + $halaman_data * $baris_halaman_lanjutan;
            ?>
            <?php for ($i = 0; $i < $jumlah_baris; $i++): ?>
                <?php $t = $tamu[$i] ?? null; ?>
                <tr>
                    <td class="c"><?= $i + 1 ?></td>
                    <td class="c"><?= $t ? $e($t->honorifics) : '&nbsp;' ?></td>
                    <td><?= $t ? $e($t->nama) : '&nbsp;' ?></td>
                    <td class="c"><?= $t ? $e($t->kategori) : '&nbsp;' ?></td>
                    <td class="ket"><span class="kotak"></span>Present<br><span class="kotak"></span>Photo Taken</td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</body>
</html>
