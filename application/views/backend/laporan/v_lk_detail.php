<?php
// Potongan HTML "Lihat detail": dari mana angka di Ringkasan berasal.
// Variabel: $kunci, $dari, $sampai + data per kunci (lihat Crud_laporan_keuangan::detail)
$rp = function ($n) { return lk_rp_penuh($n); };
$qs_periode = http_build_query(['dari' => $dari, 'sampai' => $sampai]);

// tombol "lihat transaksi" (memuat daftar transaksi di baris di bawahnya)
$lihat = function ($query, $label = 'lihat transaksi') {
    return '<button type="button" data-lk-trx="' . lk_h($query) . '" class="text-xs text-blue-600 hover:underline whitespace-nowrap">' . lk_h($label) . ' &rsaquo;</button>';
};
// baris tempat daftar transaksi dimuat
$baris_trx = function ($kolom) {
    return '<tr class="lk-trx-baris hidden"><td colspan="' . (int) $kolom . '" class="px-2 py-2 bg-gray-50"><div class="lk-trx-isi text-xs text-gray-500">Memuat...</div></td></tr>';
};
$th = 'px-3 py-2 text-left';
$judul = [
    'pendapatan' => 'Pendapatan', 'laba_kotor' => 'Laba Kotor', 'laba_bersih' => 'Laba Bersih', 'uang_masuk' => 'Uang Masuk (Pelanggan)',
    'kas' => 'Estimasi Kas & Bank', 'piutang' => 'Piutang Usaha', 'liabilitas' => 'Liabilitas (akun COA)', 'ekuitas' => 'Ekuitas',
][$kunci];

// tabel per akun dengan tombol lihat transaksi. $kum: transaksi dari awal pencatatan (posisi neraca) atau hanya periode
$tabel_akun = function ($akun, $kum, $kolom_n = true) use ($lihat, $baris_trx, $rp, $qs_periode, $th) {
    $h = '<div class="overflow-x-auto rounded border border-gray-200 mb-4"><table class="min-w-full text-sm"><thead class="bg-gray-100 text-gray-700"><tr>'
        . '<th class="' . $th . '">Akun COA</th>' . ($kolom_n ? '<th class="px-3 py-2 text-right">Transaksi</th>' : '') . '<th class="px-3 py-2 text-right">Jumlah</th><th class="px-3 py-2"></th></tr></thead><tbody>';
    if (!$akun) {
        $h .= '<tr><td colspan="4" class="px-3 py-4 text-center text-gray-500">Tidak ada data pada akun ini.</td></tr>';
    }
    foreach ($akun as $a) {
        $h .= '<tr class="border-t border-gray-200"><td class="px-3 py-2"><span class="text-gray-400 mr-2">' . lk_h($a['kode']) . '</span>' . lk_h($a['nama']) . '</td>'
            . ($kolom_n ? '<td class="px-3 py-2 text-right text-gray-500">' . ($a['n'] === null ? '' : (int) $a['n']) . '</td>' : '')
            . '<td class="px-3 py-2 text-right font-semibold whitespace-nowrap' . ($a['jumlah'] < 0 ? ' text-red-600' : '') . '">' . $rp($a['jumlah']) . '</td>'
            . '<td class="px-3 py-2 text-right">' . $lihat('kode=' . $a['kode'] . '&kum=' . ($kum ? '1' : '0') . '&' . $qs_periode) . '</td></tr>'
            . $baris_trx(4);
    }
    return $h . '</tbody></table></div>';
};

// tabel per project/klien (maks $batas baris)
$tabel_project = function ($project, $batas = 25) use ($rp) {
    $h = '<div class="overflow-x-auto rounded border border-gray-200 mb-4"><table class="min-w-full text-sm"><thead class="bg-gray-100 text-gray-700"><tr>'
        . '<th class="px-3 py-2 text-left">Project</th><th class="px-3 py-2 text-left">Klien</th><th class="px-3 py-2 text-right">Transaksi</th><th class="px-3 py-2 text-right">Jumlah</th></tr></thead><tbody>';
    foreach (array_slice($project, 0, $batas) as $p) {
        $h .= '<tr class="border-t border-gray-200"><td class="px-3 py-2">' . lk_h($p['project']) . '</td><td class="px-3 py-2 text-gray-500">' . lk_h($p['klien']) . '</td>'
            . '<td class="px-3 py-2 text-right text-gray-500">' . (int) $p['n'] . '</td><td class="px-3 py-2 text-right font-semibold whitespace-nowrap">' . $rp($p['jumlah']) . '</td></tr>';
    }
    if (count($project) > $batas) {
        $sisa = array_slice($project, $batas);
        $h .= '<tr class="border-t border-gray-200 text-gray-500"><td class="px-3 py-2" colspan="3">dan ' . count($sisa) . ' project lainnya</td><td class="px-3 py-2 text-right whitespace-nowrap">' . $rp(array_sum(array_column($sisa, 'jumlah'))) . '</td></tr>';
    }
    return $h . '</tbody></table></div>';
};

// bagian "seksi laba rugi": judul + akun + subtotal
$seksi_lr = function ($sec, $minus = false) use ($lihat, $baris_trx, $rp, $qs_periode) {
    $h = '<tr class="bg-gray-100"><td colspan="3" class="px-3 py-2 font-semibold">' . ($minus ? '&minus; ' : '') . lk_h($sec['label']) . '</td></tr>';
    if (!$sec['akun']) {
        $h .= '<tr class="border-t border-gray-200"><td colspan="3" class="px-3 py-2 pl-6 text-gray-400 italic">Tidak ada transaksi</td></tr>';
    }
    foreach ($sec['akun'] as $a) {
        $h .= '<tr class="border-t border-gray-200"><td class="px-3 py-2 pl-6"><span class="text-gray-400 mr-2">' . lk_h($a['kode']) . '</span>' . lk_h($a['nama']) . '</td>'
            . '<td class="px-3 py-2 text-right whitespace-nowrap">' . $rp($a['jumlah']) . '</td>'
            . '<td class="px-3 py-2 text-right">' . $lihat('kode=' . $a['kode'] . '&kum=0&' . $qs_periode) . '</td></tr>' . $baris_trx(3);
    }
    return $h . '<tr class="border-t border-gray-300 font-semibold bg-gray-50"><td class="px-3 py-2">Total ' . lk_h($sec['label']) . '</td><td class="px-3 py-2 text-right whitespace-nowrap">' . $rp($sec['total']) . '</td><td></td></tr>';
};
$baris_total = function ($label, $nilai, $besar = false) use ($rp) {
    return '<tr class="border-t-2 border-gray-300 ' . ($besar ? 'bg-blue-50 font-bold' : 'font-semibold') . '"><td class="px-3 py-2">' . lk_h($label) . '</td><td class="px-3 py-2 text-right whitespace-nowrap ' . ($nilai < 0 ? 'text-red-600' : '') . '">' . $rp($nilai) . '</td><td></td></tr>';
};
?>
<div data-lk-fragmen="1">
  <h2 class="text-lg font-bold mb-1"><?= lk_h($judul) ?></h2>
  <p class="text-xs text-gray-500 mb-3">
    <?= in_array($kunci, ['kas', 'piutang', 'liabilitas', 'ekuitas'], true) ? 'Posisi per ' . lk_tgl($sampai) : 'Periode ' . lk_tgl($dari) . ' s/d ' . lk_tgl($sampai) ?>
    &middot; klik &quot;lihat transaksi&quot; untuk melihat transaksi aslinya
  </p>

<?php if ($kunci === 'pendapatan'): ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    <b><?= $rp($total) ?></b> berasal dari <b><?= (int) $n ?> invoice</b> yang diterbitkan pada periode ini (akun Pendapatan 4xxx). Pendapatan diakui saat invoice dibuat, bukan saat dibayar.
  </div>
  <h3 class="font-semibold text-sm mb-1">Per akun pendapatan</h3>
  <?= $tabel_akun($akun, false) ?>
  <h3 class="font-semibold text-sm mb-1">Per project / klien</h3>
  <?= $tabel_project($project) ?>

<?php elseif ($kunci === 'laba_kotor'): ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    Laba Kotor = Pendapatan Usaha &minus; Beban Pokok Pendapatan (harga pokok + biaya operasional event: gedung, catering, MUA, dekorasi, hiburan, MC, freelance).
  </div>
  <div class="overflow-x-auto rounded border border-gray-200 mb-3"><table class="min-w-full text-sm"><tbody>
    <?= $seksi_lr($lr['pendapatan']) ?>
    <?= $seksi_lr($lr['hpp'], true) ?>
    <?= $seksi_lr($lr['event'], true) ?>
    <?= $baris_total('LABA KOTOR', $lr['laba_kotor'], true) ?>
  </tbody></table></div>
  <p class="text-sm text-gray-600">Margin laba kotor: <b><?= $lr['total_pendapatan'] > 0 ? lk_pct($lr['laba_kotor'] / $lr['total_pendapatan'] * 100) : '-' ?></b></p>

<?php elseif ($kunci === 'laba_bersih'): ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    Laba Bersih = Pendapatan &minus; Beban Pokok &minus; Beban Usaha (pemasaran, gaji, umum &amp; administrasi) &plusmn; pendapatan/beban di luar usaha.
    Biaya diakui saat dibayar. Belum termasuk penyusutan dan pajak.
  </div>
  <div class="overflow-x-auto rounded border border-gray-200 mb-3"><table class="min-w-full text-sm"><tbody>
    <?= $seksi_lr($lr['pendapatan']) ?>
    <?= $seksi_lr($lr['hpp'], true) ?>
    <?= $seksi_lr($lr['event'], true) ?>
    <?= $baris_total('LABA KOTOR', $lr['laba_kotor']) ?>
    <?= $seksi_lr($lr['pemasaran'], true) ?>
    <?= $seksi_lr($lr['gaji'], true) ?>
    <?= $seksi_lr($lr['umum'], true) ?>
    <?= $baris_total('LABA USAHA', $lr['laba_usaha']) ?>
    <?= $seksi_lr($lr['lain_pendapatan']) ?>
    <?= $seksi_lr($lr['lain_beban'], true) ?>
    <?= $baris_total('LABA (RUGI) BERSIH', $lr['laba_bersih'], true) ?>
  </tbody></table></div>

<?php elseif ($kunci === 'uang_masuk'): ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    <b><?= $rp($total) ?></b> = kwitansi berstatus <b>Paid</b> yang dicatat di akun Kas &amp; Bank (1000.xx) ditambah pendapatan di luar usaha (7100) pada periode ini.
    Total <?= (int) $n ?> transaksi.
  </div>
  <h3 class="font-semibold text-sm mb-1">Per akun kas / bank</h3>
  <?= $tabel_akun($akun, false) ?>
  <h3 class="font-semibold text-sm mb-1">Siapa yang membayar (per project / klien)</h3>
  <?= $tabel_project($project) ?>
  <p class="text-xs text-gray-500">Tidak dihitung: kwitansi berstatus <b>Pending</b> (belum diterima) pada periode ini &mdash; <?= (int) $pending['n'] ?> kwitansi senilai <?= $rp($pending['nilai']) ?>.
    Kwitansi Paid yang belum dipilihkan akun Kas &amp; Bank juga belum ikut (lihat tab Catatan).</p>

<?php elseif ($kunci === 'kas'): ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    COA hanya mencatat uang <b>masuk</b> di akun Kas, jadi saldo kas <b>dihitung</b> dari seluruh arus transaksi sejak awal pencatatan sampai <?= lk_tgl($sampai) ?>:
    uang masuk + modal + pinjaman &minus; semua biaya, uang muka, aset, dan penarikan. Cocokkan hasilnya dengan saldo rekening bank.
  </div>
  <div class="overflow-x-auto rounded border border-gray-200 mb-3"><table class="min-w-full text-sm"><tbody>
    <?php foreach ($baris as $b): $tanda = $b[1] > 0 ? '+' : '&minus;'; ?>
      <tr class="border-t border-gray-200">
        <td class="px-3 py-2"><span class="inline-block w-5 font-bold <?= $b[1] > 0 ? 'text-green-600' : 'text-red-600' ?>"><?= $tanda ?></span><?= lk_h($b[0]) ?></td>
        <td class="px-3 py-2 text-right whitespace-nowrap <?= $b[2] == 0 ? 'text-gray-400' : '' ?>"><?= $rp($b[2]) ?></td>
        <td class="px-3 py-2 text-right"><?= $b[2] != 0 ? $lihat('jenis=' . $b[3] . '&kum=1&' . $qs_periode) : '' ?></td>
      </tr>
      <?= $b[2] != 0 ? $baris_trx(3) : '' ?>
    <?php endforeach; ?>
    <?= $baris_total('ESTIMASI KAS & BANK', $total, true) ?>
  </tbody></table></div>

<?php elseif ($kunci === 'piutang'): $pt = $piutang; ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    Piutang dihitung per project: <b>total invoice</b> yang sudah diberi akun COA dikurangi <b>kwitansi berstatus Paid</b> proyek yang sama, sampai <?= lk_tgl($sampai) ?>.
    Total sisa tagihan <b><?= $rp($pt['total']) ?></b> dari <b><?= count($pt['baris']) ?> project</b> (<?= (int) $pt['lunas_n'] ?> project sudah lunas).
  </div>
  <div class="overflow-x-auto rounded border border-gray-200 mb-3"><table class="min-w-full text-sm">
    <thead class="bg-gray-100 text-gray-700"><tr>
      <th class="px-3 py-2 text-left">Project / Klien</th><th class="px-3 py-2 text-left">Invoice</th>
      <th class="px-3 py-2 text-right">Tagihan</th><th class="px-3 py-2 text-right">Sudah dibayar</th><th class="px-3 py-2 text-right">Sisa</th>
    </tr></thead>
    <tbody>
      <?php if (empty($pt['baris'])): ?><tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">Tidak ada piutang.</td></tr><?php endif; ?>
      <?php foreach ($pt['baris'] as $r): ?>
        <tr class="border-t border-gray-200 align-top">
          <td class="px-3 py-2"><?= lk_h($r->project_name ?: '(project tidak ditemukan)') ?><div class="text-xs text-gray-400"><?= lk_h($r->client_name) ?><?= $r->event_date ? ' &middot; acara ' . lk_tgl($r->event_date) : '' ?></div></td>
          <td class="px-3 py-2 text-xs text-gray-500"><?= lk_h($r->no_invoice) ?><div><?= lk_tgl($r->tgl_invoice) ?></div></td>
          <td class="px-3 py-2 text-right whitespace-nowrap"><?= $rp($r->tagihan) ?></td>
          <td class="px-3 py-2 text-right whitespace-nowrap text-green-700"><?= $rp($r->dibayar) ?><?= $r->terakhir_bayar ? '<div class="text-xs text-gray-400">terakhir ' . lk_tgl($r->terakhir_bayar) . '</div>' : '' ?></td>
          <td class="px-3 py-2 text-right whitespace-nowrap font-semibold"><?= $rp($r->sisa) ?></td>
        </tr>
      <?php endforeach; ?>
      <tr class="border-t-2 border-gray-300 font-bold bg-blue-50"><td class="px-3 py-2" colspan="4">TOTAL PIUTANG USAHA</td><td class="px-3 py-2 text-right whitespace-nowrap"><?= $rp($pt['total']) ?></td></tr>
    </tbody>
  </table></div>
  <?php if (abs($piutang_coa - $pt['total']) >= 1): ?>
    <p class="text-xs text-amber-700">&#9888; Saldo akun Piutang (110302.xx) di halaman Chart Of Account = <?= $rp($piutang_coa) ?>. Selisihnya karena sebagian baris Piutang di COA tidak ikut berkurang saat kwitansi dibayar; laporan memakai angka hasil hitung di atas.</p>
  <?php endif; ?>

<?php elseif ($kunci === 'liabilitas'): ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    Liabilitas = saldo akun 2000 Utang dan 2300 Hutang Jangka Panjang sejak awal pencatatan sampai <?= lk_tgl($sampai) ?>
    (pokok yang diterima dikurangi cicilan yang sudah dibayar). Total <b><?= $rp($total) ?></b>.
    Utang ke vendor per event (210102.xx) tidak dihitung karena baru berupa komitmen.
  </div>
  <h3 class="font-semibold text-sm mb-1">Per akun</h3>
  <?= $tabel_akun($akun, true, false) ?>
  <?php if (!empty($hutang)): ?>
    <h3 class="font-semibold text-sm mb-1">Rincian dari menu Hutang</h3>
    <div class="overflow-x-auto rounded border border-gray-200 mb-3"><table class="min-w-full text-sm">
      <thead class="bg-gray-100 text-gray-700"><tr><th class="px-3 py-2 text-left">Hutang</th><th class="px-3 py-2 text-left">Akun</th><th class="px-3 py-2 text-right">Pokok</th><th class="px-3 py-2 text-right">Dibayar</th><th class="px-3 py-2 text-right">Sisa</th></tr></thead>
      <tbody>
        <?php foreach ($hutang as $h): ?>
          <tr class="border-t border-gray-200">
            <td class="px-3 py-2"><?= lk_h($h->nama_hutang) ?><div class="text-xs text-gray-400"><?= lk_h($h->kreditur) ?> &middot; <?= lk_tgl($h->tanggal_hutang) ?></div></td>
            <td class="px-3 py-2 text-gray-500"><?= lk_h($h->kategori) ?></td>
            <td class="px-3 py-2 text-right whitespace-nowrap"><?= $rp($h->nominal_pokok) ?></td>
            <td class="px-3 py-2 text-right whitespace-nowrap text-green-700"><?= $rp($h->dibayar) ?></td>
            <td class="px-3 py-2 text-right whitespace-nowrap font-semibold"><?= $rp(max(0, $h->nominal_pokok - $h->dibayar)) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
  <?php endif; ?>
  <p class="text-xs text-gray-500">Baris di akun 2000/2300 yang tidak berasal dari menu Hutang adalah catatan lama dari Finance Operational (klik &quot;lihat transaksi&quot; pada akunnya).</p>

<?php elseif ($kunci === 'ekuitas'): ?>
  <div class="mb-3 p-3 rounded bg-blue-50 text-sm text-blue-900">
    Ekuitas = Modal disetor + Saldo awal modal + Laba kumulatif sejak awal pencatatan &minus; Penarikan modal/deviden, per <?= lk_tgl($sampai) ?>.
  </div>
  <div class="overflow-x-auto rounded border border-gray-200 mb-3"><table class="min-w-full text-sm"><tbody>
    <tr class="border-t border-gray-200"><td class="px-3 py-2"><span class="inline-block w-5 font-bold text-green-600">+</span>Modal disetor (akun 3000)</td><td class="px-3 py-2 text-right whitespace-nowrap"><?= $rp($modal) ?></td><td class="px-3 py-2 text-right"><?= $modal != 0 ? $lihat('jenis=modal&kum=1&' . $qs_periode) : '' ?></td></tr>
    <?= $modal != 0 ? $baris_trx(3) : '' ?>
    <tr class="border-t border-gray-200"><td class="px-3 py-2"><span class="inline-block w-5 font-bold text-green-600">+</span>Saldo awal modal (akun 3100)</td><td class="px-3 py-2 text-right whitespace-nowrap"><?= $rp($saldo_awal) ?></td><td class="px-3 py-2 text-right"><?= $saldo_awal != 0 ? $lihat('jenis=saldo_awal&kum=1&' . $qs_periode) : '' ?></td></tr>
    <?= $saldo_awal != 0 ? $baris_trx(3) : '' ?>
    <tr class="border-t border-gray-200"><td class="px-3 py-2"><span class="inline-block w-5 font-bold <?= $lr['laba_bersih'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">+</span>Laba (rugi) kumulatif sejak awal
        <div class="text-xs text-gray-400 pl-5">pendapatan <?= $rp($lr['total_pendapatan'] + $lr['lain_pendapatan']['total']) ?> &minus; seluruh biaya <?= $rp($lr['total_beban']) ?></div></td>
      <td class="px-3 py-2 text-right whitespace-nowrap <?= $lr['laba_bersih'] < 0 ? 'text-red-600' : '' ?>"><?= $rp($lr['laba_bersih']) ?></td><td class="px-3 py-2 text-right"><?= $lihat('jenis=pendapatan&kum=1&' . $qs_periode, 'transaksi pendapatan') ?></td></tr>
    <?= $baris_trx(3) ?>
    <tr class="border-t border-gray-200"><td class="px-3 py-2"><span class="inline-block w-5 font-bold text-red-600">&minus;</span>Penarikan modal / deviden (akun 320002 / 3200.xx)</td><td class="px-3 py-2 text-right whitespace-nowrap"><?= $rp($deviden) ?></td><td class="px-3 py-2 text-right"><?= $deviden != 0 ? $lihat('jenis=deviden&kum=1&' . $qs_periode) : '' ?></td></tr>
    <?= $deviden != 0 ? $baris_trx(3) : '' ?>
    <?= $baris_total('EKUITAS', $total, true) ?>
  </tbody></table></div>
  <p class="text-xs text-gray-500">Laba kumulatif dirinci per akun di kartu Laba Bersih (untuk periode terpilih) dan tab Laba Rugi.</p>
<?php endif; ?>
</div>
