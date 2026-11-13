<?php
// Laporan Laba Rugi. Variabel: $d (data laporan), $excel (bool)
$lr = $d['lr'];
$pb = $d['pembanding'];
$lr2 = $pb ? $pb['lr'] : null;
$pend = $lr['total_pendapatan'];
$kolom = $pb ? 6 : 3;

// satu baris angka. $biaya = true untuk baris biaya: kenaikan biaya berwarna merah, penurunan hijau
$baris = function ($label, $jumlah, $jumlah2, $gaya = '', $kode = '', $biaya = false) use ($pb, $pend) {
    $kelas = $gaya === 'besar' ? 'bg-blue-50 dark:bg-neutral-700 font-bold' : ($gaya === 'total' ? 'font-semibold bg-gray-50 dark:bg-neutral-800' : '');
    $pad = $gaya === 'akun' ? 'pl-8' : ($gaya === '' ? 'pl-4' : '');
    $kls_angka = $jumlah < 0 ? 'text-red-600' : '';
    echo '<tr class="border-b border-gray-200 dark:border-neutral-600 ' . $kelas . '">';
    echo '<td class="px-3 py-2 ' . $pad . '">' . ($kode !== '' ? '<span class="text-gray-400 mr-2">' . lk_h($kode) . '</span>' : '') . lk_h($label) . '</td>';
    echo '<td class="px-3 py-2 text-right whitespace-nowrap ' . $kls_angka . '">' . lk_rp($jumlah) . '</td>';
    echo '<td class="px-3 py-2 text-right whitespace-nowrap text-gray-500">' . ($pend > 0 ? lk_pct($jumlah / $pend * 100) : '-') . '</td>';
    if ($pb) {
        echo '<td class="px-3 py-2 text-right whitespace-nowrap">' . lk_rp($jumlah2) . '</td>';
        echo '<td class="px-3 py-2 text-right whitespace-nowrap">' . lk_rp($jumlah - $jumlah2) . '</td>';
        $p = lk_selisih_pct($jumlah, $jumlah2);
        $buruk = $biaya ? ($p !== null && $p > 0) : ($p !== null && $p < 0);
        echo '<td class="px-3 py-2 text-right whitespace-nowrap ' . ($p === null ? '' : ($buruk ? 'text-red-600' : 'text-green-600')) . '">' . ($p === null ? '-' : lk_pct($p)) . '</td>';
    }
    echo '</tr>';
};

// satu seksi (judul + akun-akun + subtotal)
$seksi = function ($kunci, $biaya = false) use ($lr, $lr2, $baris, $kolom) {
    $s = $lr[$kunci];
    $s2 = $lr2 ? $lr2[$kunci] : ['akun' => [], 'total' => 0];
    echo '<tr class="bg-gray-100 dark:bg-neutral-700"><td colspan="' . $kolom . '" class="px-3 py-2 font-semibold">' . lk_h($s['label']) . '</td></tr>';
    $semua = $s['akun'] + $s2['akun'];
    ksort($semua, SORT_NATURAL);
    if (!$semua) {
        echo '<tr class="border-b border-gray-200 dark:border-neutral-600"><td colspan="' . $kolom . '" class="px-3 py-2 pl-8 text-gray-400 italic">Tidak ada transaksi</td></tr>';
    }
    foreach ($semua as $kode => $a) {
        $baris($a['nama'], isset($s['akun'][$kode]) ? $s['akun'][$kode]['jumlah'] : 0, isset($s2['akun'][$kode]) ? $s2['akun'][$kode]['jumlah'] : 0, 'akun', $kode, $biaya);
    }
    $baris('Total ' . $s['label'], $s['total'], $s2['total'], 'total', '', $biaya);
};

$j2 = function ($k) use ($lr2) { return $lr2 ? $lr2[$k] : 0; };
?>
<div class="mb-2">
  <h2 class="text-xl font-bold">Laporan Laba Rugi</h2>
  <p class="text-sm text-gray-500">Periode <?= lk_tgl($d['dari']) ?> s/d <?= lk_tgl($d['sampai']) ?>
    <?= $pb ? ' &middot; pembanding ' . lk_tgl($pb['dari']) . ' s/d ' . lk_tgl($pb['sampai']) : '' ?> &middot; dalam Rupiah</p>
</div>
<?php if ($pb && !empty($pb['cakupan_rendah'])): ?>
  <p class="mb-2 text-sm text-amber-700">&#9888; Data COA periode pembanding baru <?= lk_pct($pb['cakupan'], 0) ?> lengkap (invoice yang sudah masuk COA), sehingga perbandingan kurang bermakna.</p>
<?php endif; ?>
<div class="overflow-x-auto mb-6">
  <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
    <thead class="bg-blue-600 text-white">
      <tr>
        <th class="px-3 py-2 text-left">Keterangan</th>
        <th class="px-3 py-2 text-right">Periode Ini</th>
        <th class="px-3 py-2 text-right">% Pendapatan</th>
        <?php if ($pb): ?>
          <th class="px-3 py-2 text-right">Pembanding</th>
          <th class="px-3 py-2 text-right">Selisih</th>
          <th class="px-3 py-2 text-right">Perubahan</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $seksi('pendapatan');
      $seksi('hpp', true);
      $seksi('event', true);
      $baris('Total Beban Pokok Pendapatan', $lr['total_beban_pokok'], $j2('total_beban_pokok'), 'total', '', true);
      $baris('LABA KOTOR', $lr['laba_kotor'], $j2('laba_kotor'), 'besar');
      $seksi('pemasaran', true);
      $seksi('gaji', true);
      $seksi('umum', true);
      $baris('Total Beban Usaha', $lr['total_beban_usaha'], $j2('total_beban_usaha'), 'total', '', true);
      $baris('LABA USAHA', $lr['laba_usaha'], $j2('laba_usaha'), 'besar');
      $seksi('lain_pendapatan');
      $seksi('lain_beban', true);
      $baris('LABA (RUGI) BERSIH', $lr['laba_bersih'], $j2('laba_bersih'), 'besar');
      ?>
    </tbody>
  </table>
  <p class="text-xs text-gray-500 mt-2">
    Pendapatan diakui saat invoice diterbitkan; biaya diakui saat dibayar. Biaya vendor yang sudah dikomitmenkan tetapi belum dibayar
    belum termasuk (lihat tab Catatan). Belum memperhitungkan penyusutan &amp; pajak penghasilan badan.
  </p>
</div>

<?php if (!empty($d['tren'])): ?>
  <h3 class="font-semibold mb-2">Ikhtisar Bulanan</h3>
  <?php if (!empty($d['tren_terpotong'])): ?><p class="text-xs text-amber-700 mb-1">Rentang lebih dari 36 bulan: hanya 36 bulan terakhir yang ditampilkan.</p><?php endif; ?>
  <div class="overflow-x-auto mb-2">
    <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
      <thead class="bg-gray-100 dark:bg-neutral-700">
        <tr>
          <th class="px-3 py-2 text-left">Bulan</th>
          <th class="px-3 py-2 text-right">Pendapatan Usaha</th>
          <th class="px-3 py-2 text-right">Pendapatan Lain</th>
          <th class="px-3 py-2 text-right">Total Beban</th>
          <th class="px-3 py-2 text-right">Laba (Rugi) Bersih</th>
          <th class="px-3 py-2 text-right">Margin</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($d['tren'] as $ym => $b): $dasar = $b['pendapatan'] + $b['lain']; ?>
          <tr class="border-b border-gray-200 dark:border-neutral-600">
            <td class="px-3 py-2"><?= lk_bulan($ym) ?></td>
            <td class="px-3 py-2 text-right"><?= lk_rp($b['pendapatan']) ?></td>
            <td class="px-3 py-2 text-right"><?= lk_rp($b['lain']) ?></td>
            <td class="px-3 py-2 text-right"><?= lk_rp($b['beban']) ?></td>
            <td class="px-3 py-2 text-right font-semibold <?= $b['laba'] < 0 ? 'text-red-600' : '' ?>"><?= lk_rp($b['laba']) ?></td>
            <td class="px-3 py-2 text-right text-gray-500"><?= $dasar > 0 ? lk_pct($b['laba'] / $dasar * 100) : '-' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
