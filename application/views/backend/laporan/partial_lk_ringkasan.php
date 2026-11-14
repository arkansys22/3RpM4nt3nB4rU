<?php
// Ringkasan Eksekutif. Variabel: $d (data laporan), $excel (bool)
$k = $d['kpi'];
$op = $d['operasional'];
$pb = $d['pembanding'];
$n = $d['neraca_akhir'];
$q = $d['kualitas'];

$kartu = function ($judul, $nilai, $sub = '', $warna = '', $kunci = '') use ($excel) {
    echo '<div class="rounded-lg border border-stroke dark:border-strokedark p-4">';
    echo '<p class="text-sm text-gray-500 dark:text-gray-400">' . lk_h($judul) . '</p>';
    echo '<p class="text-xl font-bold ' . $warna . '">' . $nilai . '</p>';
    if ($sub !== '') {
        echo '<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">' . $sub . '</p>';
    }
    if ($kunci !== '' && !$excel) {
        // membuka jendela rincian asal angka (lihat skrip di v_laporan_keuangan.php)
        echo '<button type="button" data-lk-detail="' . $kunci . '" aria-label="Lihat detail ' . lk_h($judul) . '" class="no-print mt-2 text-xs font-medium text-blue-600 hover:underline">Lihat detail &rsaquo;</button>';
    }
    echo '</div>';
};
$naik = function ($pct) {
    if ($pct === null) return '';
    return ($pct >= 0 ? '&#9650; ' : '&#9660; ') . lk_pct(abs($pct)) . ' vs pembanding';
};

// Kalimat ringkasan otomatis (bisa langsung dipakai sebagai narasi laporan)
$kalimat = 'Pada periode ' . lk_tgl($d['dari']) . ' s/d ' . lk_tgl($d['sampai']) . ', usaha membukukan pendapatan ' . lk_rp_penuh($k['pendapatan']);
if ($k['pertumbuhan'] !== null) {
    $kalimat .= ' (' . ($k['pertumbuhan'] >= 0 ? 'naik ' : 'turun ') . lk_pct(abs($k['pertumbuhan'])) . ' dibanding periode pembanding)';
}
$kalimat .= ', laba kotor ' . lk_rp_penuh($k['laba_kotor']) . ($k['margin_kotor'] !== null ? ' (margin ' . lk_pct($k['margin_kotor']) . ')' : '')
    . ' dan ' . ($k['laba_bersih'] >= 0 ? 'laba bersih ' : 'rugi bersih ') . lk_rp_penuh(abs($k['laba_bersih']))
    . ($k['margin_bersih'] !== null ? ' (margin ' . lk_pct($k['margin_bersih']) . ')' : '') . '. '
    . 'Uang masuk dari pelanggan dan pendapatan lain ' . lk_rp_penuh($k['penerimaan']) . ' dengan kas bersih aktivitas operasi ' . lk_rp_penuh($k['kas_operasi']) . '. '
    . 'Per ' . lk_tgl($d['sampai']) . ', estimasi kas & bank (hasil perhitungan) ' . lk_rp_penuh($k['kas_akhir']) . ', piutang usaha ' . lk_rp_penuh($k['piutang'])
    . ', liabilitas dari akun COA ' . lk_rp_penuh($k['liabilitas']) . ' dan ekuitas ' . lk_rp_penuh($k['ekuitas']) . '.'
    . ($k['selisih'] != 0 ? ' Neraca memuat selisih rekonsiliasi ' . lk_rp_penuh(abs($k['selisih'])) . ' karena sebagian data COA belum lengkap (lihat tab Catatan).' : '');

// status kelengkapan data (ringkas)
$kel = $q['kelengkapan_total'];
$pct_inv = $kel['inv_nilai'] > 0 ? $kel['inv_coa_nilai'] / $kel['inv_nilai'] * 100 : null;
$pct_kw = $kel['kw_nilai'] > 0 ? $kel['kw_coa_nilai'] / $kel['kw_nilai'] * 100 : null;
$tb = $q['biaya_total'];
$pct_biaya = $tb['nilai'] > 0 ? $tb['nilai_coa'] / $tb['nilai'] * 100 : null;
$data_kurang = ($pct_biaya !== null && $pct_biaya < 99.5) || ($pct_inv !== null && $pct_inv < 99.5) || ($pct_kw !== null && $pct_kw < 99.5) || $n['selisih'] != 0 || count($q['tanpa_kategori']) > 0;
?>
<div class="mb-2">
  <h2 class="text-xl font-bold">Ringkasan Eksekutif</h2>
  <p class="text-sm text-gray-500">Periode <?= lk_tgl($d['dari']) ?> s/d <?= lk_tgl($d['sampai']) ?>
    <?= $pb ? ' &middot; pembanding ' . lk_tgl($pb['dari']) . ' s/d ' . lk_tgl($pb['sampai']) : '' ?></p>
</div>

<p class="mb-4 text-sm leading-relaxed text-gray-700 dark:text-gray-300"><?= lk_h($kalimat) ?></p>

<?php if ($data_kurang): ?>
  <div class="mb-4 p-3 rounded-md bg-amber-100 text-amber-800 text-sm">
    &#9888; <b>Data COA pada periode ini belum sepenuhnya lengkap</b>
    <?php if ($pct_inv !== null): ?>(invoice yang masuk COA: <?= lk_pct($pct_inv, 0) ?> dari nilai invoice<?= $pct_kw !== null ? ', kwitansi Paid: ' . lk_pct($pct_kw, 0) : '' ?><?= $pct_biaya !== null ? ', transaksi Finance Project/Operasional: ' . lk_pct($pct_biaya, 0) : '' ?>).<?php endif; ?>
    Angka laporan mengikuti data COA yang sudah diinput. Rincian dan daftar yang perlu dilengkapi ada di tab <b>Catatan</b>.
  </div>
<?php endif; ?>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-4">
  <?php
  $kartu('Pendapatan', lk_rp_penuh($k['pendapatan']), $naik($k['pertumbuhan']), '', 'pendapatan');
  $kartu('Laba Kotor', lk_rp_penuh($k['laba_kotor']), $k['margin_kotor'] !== null ? 'Margin ' . lk_pct($k['margin_kotor']) : '', $k['laba_kotor'] < 0 ? 'text-red-600' : '', 'laba_kotor');
  $kartu($k['laba_bersih'] >= 0 ? 'Laba Bersih' : 'Rugi Bersih', lk_rp_penuh($k['laba_bersih']), $k['margin_bersih'] !== null ? 'Margin ' . lk_pct($k['margin_bersih']) : '', $k['laba_bersih'] < 0 ? 'text-red-600' : 'text-green-600', 'laba_bersih');
  $kartu('Uang Masuk (Pelanggan)', lk_rp_penuh($k['penerimaan']), 'Kas bersih operasi ' . lk_rp_penuh($k['kas_operasi']), '', 'uang_masuk');
  $kartu('Estimasi Kas & Bank (perhitungan)', lk_rp_penuh($k['kas_akhir']), 'Per ' . lk_tgl($d['sampai']), $k['kas_akhir'] < 0 ? 'text-red-600' : '', 'kas');
  $kartu('Piutang Usaha', lk_rp_penuh($k['piutang']), $k['dso'] !== null ? number_format($k['dso'], 0, ',', '.') . ' hari penjualan belum tertagih' : '', '', 'piutang');
  $kartu('Liabilitas (akun COA)', lk_rp_penuh($k['liabilitas']), 'Hutang jangka panjang ' . lk_rp_penuh($n['liab_panjang']) . ($k['selisih'] != 0 ? ' &middot; belum termasuk selisih rekonsiliasi ' . lk_rp_penuh(abs($k['selisih'])) : ''), '', 'liabilitas');
  $kartu('Ekuitas', lk_rp_penuh($k['ekuitas']), 'Modal disetor ' . lk_rp_penuh($n['ekuitas']['modal']['jumlah']), $k['ekuitas'] < 0 ? 'text-red-600' : '', 'ekuitas');
  ?>
</div>

<?php if (!$excel): ?>
  <?php if (!empty($d['tren_terpotong'])): ?><p class="text-xs text-amber-700 mb-1">Rentang lebih dari 36 bulan: grafik hanya menampilkan 36 bulan terakhir, jadi total bulanan tidak sama dengan total periode.</p><?php endif; ?>
  <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-6">
    <div class="rounded-lg border border-stroke dark:border-strokedark p-4 bg-white">
      <p class="font-semibold mb-2 text-gray-800">Pendapatan, Beban &amp; Laba per Bulan</p>
      <div style="position:relative;height:260px"><canvas id="lk-chart-laba" aria-label="Grafik pendapatan, beban dan laba bersih per bulan" role="img"></canvas></div>
    </div>
    <div class="rounded-lg border border-stroke dark:border-strokedark p-4 bg-white">
      <p class="font-semibold mb-2 text-gray-800">Uang Masuk vs Uang Keluar per Bulan</p>
      <div style="position:relative;height:260px"><canvas id="lk-chart-kas" aria-label="Grafik uang masuk dan uang keluar per bulan" role="img"></canvas></div>
    </div>
  </div>
<?php endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-4">
  <div>
    <h3 class="font-semibold mb-2">Rasio &amp; Indikator</h3>
    <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
      <tbody>
        <?php
        $rasio = [
            ['Margin laba kotor', $k['margin_kotor'] === null ? '-' : lk_pct($k['margin_kotor'])],
            ['Margin laba bersih', $k['margin_bersih'] === null ? '-' : lk_pct($k['margin_bersih'])],
            ['Pertumbuhan pendapatan vs pembanding', $k['pertumbuhan'] === null ? ($pb && $pb['cakupan_rendah'] ? 'n/a (data pembanding belum lengkap)' : '-') : lk_pct($k['pertumbuhan'])],
            ['Kas terhadap liabilitas akun COA (tanpa selisih rekonsiliasi)', $k['rasio_kas_liabilitas'] === null ? 'tidak ada liabilitas' : number_format($k['rasio_kas_liabilitas'], 2, ',', '.') . 'x'],
            ['Rasio liabilitas akun COA terhadap ekuitas', $k['rasio_hutang_ekuitas'] === null ? '-' : number_format($k['rasio_hutang_ekuitas'], 2, ',', '.') . 'x'],
            ['Rata-rata uang keluar operasional / bulan (' . (int) $k['burn_bulan'] . ' bulan penuh terakhir yang tercatat)', $k['burn_bulan'] > 0 ? lk_rp_penuh($k['burn']) : '-'],
            ['Kas cukup untuk (pengeluaran operasional)', $k['runway'] === null ? ($k['kas_akhir'] <= 0 ? 'kas negatif / habis' : '-') : number_format($k['runway'], 1, ',', '.') . ' bulan'],
        ];
        foreach ($rasio as $r): ?>
          <tr class="border-b border-gray-200 dark:border-neutral-600">
            <td class="px-3 py-2"><?= lk_h($r[0]) ?></td>
            <td class="px-3 py-2 text-right whitespace-nowrap font-semibold"><?= lk_h($r[1]) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div>
    <h3 class="font-semibold mb-2">Informasi Operasional <span class="text-xs font-normal text-gray-500">(dari data Project, di luar COA)</span></h3>
    <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
      <tbody>
        <tr class="border-b border-gray-200 dark:border-neutral-600"><td class="px-3 py-2">Event dengan tanggal acara dalam periode</td><td class="px-3 py-2 text-right font-semibold"><?= (int) $op['event_n'] ?> event</td></tr>
        <tr class="border-b border-gray-200 dark:border-neutral-600"><td class="px-3 py-2">Nilai kontrak event dalam periode</td><td class="px-3 py-2 text-right font-semibold"><?= lk_rp_penuh($op['event_nilai']) ?></td></tr>
        <tr class="border-b border-gray-200 dark:border-neutral-600"><td class="px-3 py-2">Event terjadwal setelah <?= lk_tgl($d['sampai']) ?> (backlog)</td><td class="px-3 py-2 text-right font-semibold"><?= (int) $op['mendatang_n'] ?> event</td></tr>
        <tr class="border-b border-gray-200 dark:border-neutral-600"><td class="px-3 py-2">Nilai kontrak backlog</td><td class="px-3 py-2 text-right font-semibold"><?= lk_rp_penuh($op['mendatang_nilai']) ?></td></tr>
        <tr class="border-b border-gray-200 dark:border-neutral-600"><td class="px-3 py-2">Pendapatan periode ini dari <?= (int) $q['pendapatan_dimuka']['n'] ?> event yang baru berlangsung setelah <?= lk_tgl($d['sampai']) ?> (diakui di muka saat invoice)</td><td class="px-3 py-2 text-right font-semibold"><?= lk_rp_penuh($q['pendapatan_dimuka']['nilai']) ?></td></tr>
        <tr class="border-b border-gray-200 dark:border-neutral-600"><td class="px-3 py-2">Komitmen biaya vendor tercatat belum dibayar &mdash; <?= (int) $q['komitmen']['project'] ?> project yang punya catatan utang, project lain belum tentu tercatat (di luar Laba Rugi &amp; Neraca)</td><td class="px-3 py-2 text-right font-semibold"><?= lk_rp_penuh($q['komitmen']['sisa']) ?></td></tr>
      </tbody>
    </table>
  </div>
</div>
