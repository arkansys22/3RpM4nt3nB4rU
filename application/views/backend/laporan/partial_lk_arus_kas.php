<?php
// Laporan Arus Kas (metode langsung). Variabel: $d (data laporan), $excel (bool)
$a = $d['arus'];
$pb = $d['pembanding'];
$a2 = $pb ? $pb['arus'] : null;
$kolom = $pb ? 3 : 2;

$judul = function ($teks) use ($kolom) {
    echo '<tr class="bg-gray-100 dark:bg-neutral-700"><td colspan="' . $kolom . '" class="px-3 py-2 font-semibold">' . lk_h($teks) . '</td></tr>';
};
$baris = function ($label, $jumlah, $jumlah2, $gaya = '') use ($pb) {
    $kelas = $gaya === 'besar' ? 'bg-blue-50 dark:bg-neutral-700 font-bold' : ($gaya === 'total' ? 'font-semibold bg-gray-50 dark:bg-neutral-800' : '');
    $pad = $gaya === '' ? 'pl-6' : '';
    echo '<tr class="border-b border-gray-200 dark:border-neutral-600 ' . $kelas . '">';
    echo '<td class="px-3 py-2 ' . $pad . '">' . lk_h($label) . '</td>';
    echo '<td class="px-3 py-2 text-right whitespace-nowrap ' . ($jumlah < 0 ? 'text-red-600' : '') . '">' . lk_rp($jumlah) . '</td>';
    if ($pb) {
        echo '<td class="px-3 py-2 text-right whitespace-nowrap text-gray-500">' . lk_rp($jumlah2) . '</td>';
    }
    echo '</tr>';
};
// daftar baris (uang keluar ditampilkan negatif)
$daftar = function ($kunci, $tanda) use ($a, $a2, $baris) {
    foreach ($a[$kunci] as $i => $r) {
        $baris($r['label'], $tanda * $r['jumlah'], $a2 ? $tanda * $a2[$kunci][$i]['jumlah'] : 0);
    }
};
$g = function ($k) use ($a2) { return $a2 ? $a2[$k] : 0; };
?>
<div class="mb-2">
  <h2 class="text-xl font-bold">Laporan Arus Kas</h2>
  <p class="text-sm text-gray-500">Metode langsung &middot; periode <?= lk_tgl($d['dari']) ?> s/d <?= lk_tgl($d['sampai']) ?>
    <?= $pb ? ' &middot; pembanding ' . lk_tgl($pb['dari']) . ' s/d ' . lk_tgl($pb['sampai']) : '' ?> &middot; dalam Rupiah</p>
</div>
<?php if ($pb && !empty($pb['cakupan_rendah'])): ?>
  <p class="mb-2 text-sm text-amber-700">&#9888; Data COA periode pembanding baru <?= lk_pct($pb['cakupan'], 0) ?> lengkap (invoice yang sudah masuk COA), sehingga perbandingan kurang bermakna.</p>
<?php endif; ?>
<div class="overflow-x-auto mb-4">
  <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
    <thead class="bg-blue-600 text-white">
      <tr>
        <th class="px-3 py-2 text-left">Keterangan</th>
        <th class="px-3 py-2 text-right">Periode Ini</th>
        <?php if ($pb): ?><th class="px-3 py-2 text-right">Pembanding</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $judul('ARUS KAS DARI AKTIVITAS OPERASI');
      $daftar('op_masuk', 1);
      $daftar('op_keluar', -1);
      $baris('Kas bersih dari aktivitas operasi', $a['kas_operasi'], $g('kas_operasi'), 'total');

      $judul('ARUS KAS DARI AKTIVITAS INVESTASI');
      $daftar('inv_keluar', -1);
      $baris('Kas bersih dari aktivitas investasi', $a['kas_investasi'], $g('kas_investasi'), 'total');

      $judul('ARUS KAS DARI AKTIVITAS PENDANAAN');
      $daftar('fin_masuk', 1);
      $daftar('fin_keluar', -1);
      $baris('Kas bersih dari aktivitas pendanaan', $a['kas_pendanaan'], $g('kas_pendanaan'), 'total');

      $baris('KENAIKAN (PENURUNAN) KAS BERSIH', $a['perubahan'], $g('perubahan'), 'besar');
      $baris('Kas & bank awal periode', $a['kas_awal'], $g('kas_awal'), 'total');
      $baris('KAS & BANK AKHIR PERIODE', $a['kas_akhir'], $g('kas_akhir'), 'besar');
      ?>
    </tbody>
  </table>
</div>
<?php if (abs($a['kas_akhir'] - $d['neraca_akhir']['kas']) < 1): ?>
  <p class="text-sm text-green-700 mb-2">&#10003; Kas akhir periode sama dengan Kas &amp; Bank di Neraca.</p>
<?php else: ?>
  <p class="text-sm text-amber-700 mb-2">&#9888; Kas akhir arus kas berbeda dari Neraca &mdash; hubungi pengembang.</p>
<?php endif; ?>

<?php if (!empty($d['tren'])): ?>
  <h3 class="font-semibold mb-2 mt-4">Uang Masuk &amp; Keluar per Bulan (operasional)</h3>
  <?php if (!empty($d['tren_terpotong'])): ?><p class="text-xs text-amber-700 mb-1">Rentang lebih dari 36 bulan: hanya 36 bulan terakhir yang ditampilkan.</p><?php endif; ?>
  <div class="overflow-x-auto mb-2">
    <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
      <thead class="bg-gray-100 dark:bg-neutral-700">
        <tr>
          <th class="px-3 py-2 text-left">Bulan</th>
          <th class="px-3 py-2 text-right">Uang Masuk</th>
          <th class="px-3 py-2 text-right">Uang Keluar</th>
          <th class="px-3 py-2 text-right">Selisih</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($d['tren'] as $ym => $b): $s = $b['kas_masuk'] - $b['kas_keluar']; ?>
          <tr class="border-b border-gray-200 dark:border-neutral-600">
            <td class="px-3 py-2"><?= lk_bulan($ym) ?></td>
            <td class="px-3 py-2 text-right"><?= lk_rp($b['kas_masuk']) ?></td>
            <td class="px-3 py-2 text-right"><?= lk_rp($b['kas_keluar']) ?></td>
            <td class="px-3 py-2 text-right font-semibold <?= $s < 0 ? 'text-red-600' : '' ?>"><?= lk_rp($s) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
<p class="text-xs text-gray-500">
  Uang masuk operasi = kwitansi Paid di akun Kas &amp; Bank + pendapatan di luar usaha. Uang keluar = biaya yang dicatat saat dibayar (event, gaji, pemasaran, umum, dll) dan uang muka.
  Kwitansi berstatus Pending bukan uang masuk dan tidak dihitung.
</p>
