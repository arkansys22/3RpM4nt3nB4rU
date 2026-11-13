<?php
// Catatan atas Laporan Keuangan & Kualitas Data. Variabel: $d (data laporan), $excel (bool)
$q = $d['kualitas'];
$n = $d['neraca_akhir'];
$t = $q['kelengkapan_total'];
// "29 · 1.162.725.000": jumlah & nilai dalam satu sel teks, sama di layar dan Excel
$gab = function ($n, $v) { return (int) $n . ' · ' . number_format((float) $v, 0, ',', '.'); };
$pct = function ($a, $b) { return $b > 0 ? lk_pct($a / $b * 100, 0) : '-'; };
?>
<div class="mb-2">
  <h2 class="text-xl font-bold">Catatan atas Laporan Keuangan &amp; Kualitas Data</h2>
  <p class="text-sm text-gray-500">Dasar penyusunan, asumsi, dan daftar data COA yang perlu dilengkapi supaya laporan semakin akurat.</p>
</div>

<h3 class="font-semibold mt-4 mb-2">1. Dasar Penyusunan</h3>
<ul class="list-disc pl-6 text-sm space-y-1 mb-4">
  <li>Seluruh angka bersumber dari data <b>Chart Of Account</b> yang sudah diinput (tabel transaksi COA), dikelompokkan menurut nomor akun. Laporan ini hanya membaca data, tidak mengubah apa pun.</li>
  <li><b>Pendapatan</b> (akun 4xxx) diakui saat invoice diterbitkan. <b>Biaya</b> (5xxx, 6xxx, 7200) diakui saat dibayar karena COA mencatat biaya pada saat pembayaran.</li>
  <li><b>Beban Pokok Pendapatan</b> = Harga Pokok (5xxx) + Biaya Operasional Event (6205 foto &amp; 6207 wedding: gedung, catering, MUA, dekorasi, hiburan, MC, freelance). Beban Usaha = pemasaran (6100), gaji (6201) dan umum &amp; administrasi (6200, 6203).</li>
  <li><b>Piutang Usaha</b> dihitung per proyek: total invoice yang sudah berakun COA dikurangi kwitansi berstatus Paid proyek yang sama sampai tanggal laporan. Nominal baris Piutang (110302.xx) di COA tidak dipakai karena tidak selalu ikut berkurang saat kwitansi dibayar (lihat bagian 4).</li>
  <li>Transaksi biaya yang diedit dulu tercatat ganda di COA (setiap edit menambah baris baru). Laporan hanya memakai baris terbaru dari tiap transaksi biaya; jumlah baris lama yang diabaikan dilaporkan di bagian 8.</li>
  <li><b>Kas &amp; Bank</b> dihitung dari arus transaksi karena COA hanya mencatat uang masuk di akun Kas. Rumusnya: kwitansi Paid + pendapatan lain + setoran modal + saldo awal modal + pinjaman &minus; seluruh biaya &minus; uang muka/aset &minus; cicilan &minus; deviden. Cocokkan dengan saldo rekening bank.</li>
  <li><b>Liabilitas</b> = akun 2000 Utang dan 2300 Hutang Jangka Panjang (menu Hutang: pokok bertambah, cicilan mengurangi). Data lama di dua akun ini dianggap pinjaman yang diterima.</li>
  <li><b>Ekuitas</b> = Modal (3000) + Saldo Awal Modal (3100) + laba kumulatif &minus; Penarikan Modal/Deviden (akun 320002; kode lama 3200.02 juga dikenali). Akun 320001 Retained Earning tidak diinput, nilainya dihitung dari laba.</li>
  <li><b>Utang ke vendor per event</b> (210102.xx) hanya komitmen: belum menjadi biaya dan belum uang keluar sehingga tidak masuk Neraca. Biayanya baru tercatat di 6207.xx saat dibayar.</li>
  <li>Belum termasuk penyusutan aset tetap dan pajak penghasilan badan.</li>
</ul>

<h3 class="font-semibold mt-4 mb-2">2. Cakupan Data COA</h3>
<p class="text-sm mb-2">
  Transaksi COA tercatat dari <b><?= lk_tgl($q['coa']['awal']) ?></b> sampai <b><?= lk_tgl($q['coa']['akhir']) ?></b> (<?= number_format($q['coa']['baris'], 0, ',', '.') ?> baris).
  <?php if ((int) $q['masa_depan']->n > 0): ?>
    Terdapat <?= (int) $q['masa_depan']->n ?> baris bertanggal setelah hari ini (jatuh tempo / pembayaran terjadwal); baris itu baru masuk laporan bila periode mencakup tanggalnya.
  <?php endif; ?>
</p>

<h3 class="font-semibold mt-4 mb-2">3. Kelengkapan Input Invoice &amp; Kwitansi ke COA (per bulan)</h3>
<p class="text-xs text-gray-500 mb-2">
  Invoice dan kwitansi yang tidak dipilihkan akun COA-nya <b>tidak ikut</b> dihitung di laporan. Bulan dengan persentase di bawah 100% berarti pendapatan/uang masuk sebenarnya lebih besar daripada yang tampil di laporan.
</p>
<div class="overflow-x-auto mb-4">
  <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
    <thead class="bg-gray-100 dark:bg-neutral-700">
      <tr>
        <th class="px-3 py-2 text-left">Bulan</th>
        <th class="px-3 py-2 text-right">Invoice (semua)</th>
        <th class="px-3 py-2 text-right">Invoice masuk COA</th>
        <th class="px-3 py-2 text-right">%</th>
        <th class="px-3 py-2 text-right">Kwitansi Paid (semua)</th>
        <th class="px-3 py-2 text-right">Kwitansi Paid masuk COA</th>
        <th class="px-3 py-2 text-right">%</th>
        <th class="px-3 py-2 text-right">Kwitansi Pending</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($q['kelengkapan_bulanan'])): ?>
        <tr><td colspan="8" class="px-3 py-3 text-center text-gray-500">Tidak ada invoice/kwitansi pada periode ini.</td></tr>
      <?php endif; ?>
      <?php foreach ($q['kelengkapan_bulanan'] as $r):
        $lengkap = $r->inv_nilai > 0 ? $r->inv_coa_nilai / $r->inv_nilai : 1; ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600">
          <td class="px-3 py-2"><?= lk_bulan($r->ym) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($r->inv_n, $r->inv_nilai) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($r->inv_coa_n, $r->inv_coa_nilai) ?></td>
          <td class="px-3 py-2 text-right font-semibold <?= $lengkap < 0.995 ? 'text-amber-600' : 'text-green-600' ?>"><?= $pct($r->inv_coa_nilai, $r->inv_nilai) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($r->kw_n, $r->kw_nilai) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($r->kw_coa_n, $r->kw_coa_nilai) ?></td>
          <td class="px-3 py-2 text-right font-semibold"><?= $pct($r->kw_coa_nilai, $r->kw_nilai) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($r->pend_n, $r->pend_nilai) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!empty($q['kelengkapan_bulanan'])): ?>
        <tr class="font-semibold bg-gray-50 dark:bg-neutral-800">
          <td class="px-3 py-2">Total periode</td>
          <td class="px-3 py-2 text-right"><?= $gab($t['inv_n'], $t['inv_nilai']) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($t['inv_coa_n'], $t['inv_coa_nilai']) ?></td>
          <td class="px-3 py-2 text-right"><?= $pct($t['inv_coa_nilai'], $t['inv_nilai']) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($t['kw_n'], $t['kw_nilai']) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($t['kw_coa_n'], $t['kw_coa_nilai']) ?></td>
          <td class="px-3 py-2 text-right"><?= $pct($t['kw_coa_nilai'], $t['kw_nilai']) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($t['pend_n'], $t['pend_nilai']) ?></td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<h3 class="font-semibold mt-4 mb-2">3b. Kelengkapan Transaksi Finance Project &amp; Operasional ke COA (per bulan)</h3>
<p class="text-xs text-gray-500 mb-2">
  Biaya, gaji, uang muka, dan pinjaman yang dicatat di menu Finance Project / Finance Operational tetapi tidak punya baris COA <b>tidak ikut</b> Laba Rugi. Bulan di bawah 100% berarti biaya sebenarnya lebih besar daripada yang tampil, sehingga laba terlihat lebih tinggi.
</p>
<?php $tb = $q['biaya_total']; ?>
<div class="overflow-x-auto mb-4">
  <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
    <thead class="bg-gray-100 dark:bg-neutral-700">
      <tr>
        <th class="px-3 py-2 text-left">Bulan</th>
        <th class="px-3 py-2 text-right">Tercatat di Finance (jumlah &middot; nilai)</th>
        <th class="px-3 py-2 text-right">Sudah masuk COA</th>
        <th class="px-3 py-2 text-right">%</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($q['biaya_bulanan'])): ?>
        <tr><td colspan="4" class="px-3 py-3 text-center text-gray-500">Tidak ada transaksi Finance pada periode ini.</td></tr>
      <?php endif; ?>
      <?php foreach ($q['biaya_bulanan'] as $r): $lengkap = $r->nilai > 0 ? $r->nilai_coa / $r->nilai : 1; ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600">
          <td class="px-3 py-2"><?= lk_bulan($r->ym) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($r->n, $r->nilai) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($r->n_coa, $r->nilai_coa) ?></td>
          <td class="px-3 py-2 text-right font-semibold <?= $lengkap < 0.995 ? 'text-amber-600' : 'text-green-600' ?>"><?= $pct($r->nilai_coa, $r->nilai) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!empty($q['biaya_bulanan'])): ?>
        <tr class="font-semibold bg-gray-50 dark:bg-neutral-800">
          <td class="px-3 py-2">Total periode</td>
          <td class="px-3 py-2 text-right"><?= $gab($tb['n'], $tb['nilai']) ?></td>
          <td class="px-3 py-2 text-right"><?= $gab($tb['n_coa'], $tb['nilai_coa']) ?></td>
          <td class="px-3 py-2 text-right"><?= $pct($tb['nilai_coa'], $tb['nilai']) ?></td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<h3 class="font-semibold mt-4 mb-2">4. Selisih Rekonsiliasi &amp; Saldo Piutang di COA</h3>
<?php if ($n['selisih'] == 0): ?>
  <p class="text-sm text-green-700 mb-2">&#10003; Tidak ada selisih: Neraca seimbang.</p>
<?php else: ?>
  <p class="text-sm mb-2">
    Selisih <b>Rp <?= number_format(abs($n['selisih']), 0, ',', '.') ?></b> per <?= lk_tgl($d['sampai']) ?>. Secara hitungan, selisih = (uang masuk di akun Kas + sisa Piutang hasil hitung) &minus; Pendapatan yang tercatat di COA.
    <?= $n['selisih'] > 0
        ? 'Nilainya positif: uang masuk di akun Kas lebih besar daripada pembayaran yang terhubung ke invoice berakun COA (mis. kwitansi untuk invoice lama yang belum dikategorikan, atau kwitansi yang dikategorikan ke Kas tanpa invoice berakun).'
        : 'Nilainya negatif: pendapatan di COA lebih besar daripada uang masuk di akun Kas + sisa piutang (mis. kwitansi Paid yang belum diberi akun Kas).' ?>
    Selisih mengecil seiring data lama dilengkapi akunnya.
  </p>
<?php endif; ?>
<?php $beda_piutang = $d['piutang_coa'] - $n['aset']['piutang_usaha']['jumlah']; ?>
<?php if (abs($beda_piutang) >= 1): ?>
  <p class="text-sm mb-4 text-amber-700">
    &#9888; Saldo akun Piutang (110302.xx) di halaman Chart Of Account = <b><?= lk_rp_penuh($d['piutang_coa']) ?></b>, sedangkan sisa tagihan sebenarnya (invoice dikurangi kwitansi Paid) = <b><?= lk_rp_penuh($n['aset']['piutang_usaha']['jumlah']) ?></b>.
    Selisih <?= lk_rp_penuh(abs($beda_piutang)) ?> terjadi karena sebagian baris Piutang di COA tidak ikut berkurang ketika kwitansi dibayar. Laporan ini memakai angka hasil hitung; sebaiknya baris Piutang di COA diperbaiki agar sama.
  </p>
<?php endif; ?>
<h3 class="font-semibold mt-4 mb-2">5. Komitmen Biaya Vendor (di luar Laba Rugi &amp; Neraca)</h3>
<p class="text-sm mb-4">
  Utang ke vendor yang dicatat di Finance Project (akun 210102.xx) total <b><?= lk_rp_penuh($q['komitmen']['total']) ?></b> pada <?= (int) $q['komitmen']['project'] ?> project (hanya project yang punya catatan utang; project lain belum tentu tercatat);
  yang belum dibayar (dihitung per project: komitmen &minus; biaya yang sudah dicatat) sebesar <b><?= lk_rp_penuh($q['komitmen']['sisa']) ?></b>.
  Jumlah ini akan menjadi biaya dan uang keluar di periode saat dibayar, sehingga perlu dipertimbangkan saat membaca laba periode berjalan.
</p>

<h3 class="font-semibold mt-4 mb-2">6. Transaksi COA yang Belum Memiliki Akun</h3>
<?php if (empty($q['tanpa_kategori'])): ?>
  <p class="text-sm text-green-700 mb-4">&#10003; Semua transaksi COA sudah memiliki akun.</p>
<?php else: ?>
  <p class="text-xs text-gray-500 mb-2">
    <?= (int) $q['tanpa_kategori_n'] ?> transaksi (total <?= lk_rp_penuh($q['tanpa_kategori_total']) ?>) tidak memiliki nomor akun sehingga <b>tidak dihitung</b> di laporan. Umumnya kwitansi berstatus Pending atau input tanpa pemilihan akun.<?= (int) $q['tanpa_kategori_n'] > count($q['tanpa_kategori']) ? ' Daftar di bawah menampilkan ' . count($q['tanpa_kategori']) . ' terbaru.' : '' ?>
  </p>
  <div class="overflow-x-auto mb-4">
    <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
      <thead class="bg-gray-100 dark:bg-neutral-700">
        <tr><th class="px-3 py-2 text-left">Tanggal</th><th class="px-3 py-2 text-left">Transaksi</th><th class="px-3 py-2 text-right">Nominal</th></tr>
      </thead>
      <tbody>
        <?php foreach ($q['tanpa_kategori'] as $r): ?>
          <tr class="border-b border-gray-200 dark:border-neutral-600">
            <td class="px-3 py-2 whitespace-nowrap"><?= lk_tgl($r->accounting_tanggal) ?></td>
            <td class="px-3 py-2"><?= lk_h($r->accounting_nama_transaksi) ?></td>
            <td class="px-3 py-2 text-right whitespace-nowrap"><?= lk_rp($r->accounting_nominal) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php if (!empty($q['akun_tak_dikenal']) || !empty($q['akun_diabaikan'])): ?>
  <h3 class="font-semibold mt-4 mb-2">7. Akun yang Tidak Dikenali Laporan</h3>
  <ul class="list-disc pl-6 text-sm mb-4">
    <?php foreach ($q['akun_tak_dikenal'] as $kode): ?>
      <li>Akun <b><?= lk_h($kode) ?></b> dipakai transaksi tetapi tidak ada di daftar COA.</li>
    <?php endforeach; ?>
    <?php foreach ($q['akun_diabaikan'] as $kode => $jml): ?>
      <li>Akun <b><?= lk_h($kode) ?></b> (<?= lk_rp_penuh($jml) ?>) tidak memiliki perlakuan di laporan dan tidak dihitung.</li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<h3 class="font-semibold mt-4 mb-2">8. Penyesuaian Otomatis &amp; Pengakuan Pendapatan</h3>
<ul class="list-disc pl-6 text-sm space-y-1 mb-4">
  <?php if ($q['duplikat_biaya']['n'] > 0): ?>
    <li><?= (int) $q['duplikat_biaya']['n'] ?> baris biaya lama (total <?= lk_rp_penuh($q['duplikat_biaya']['total']) ?>) dihasilkan oleh edit transaksi dan sudah digantikan baris terbaru; baris lama ini <b>diabaikan</b> laporan, tetapi masih menambah saldo di halaman Chart Of Account. Sebaiknya dibersihkan dari database.</li>
  <?php else: ?>
    <li>Tidak ada baris biaya ganda di COA.</li>
  <?php endif; ?>
  <li>Pendapatan periode ini sebesar <?= lk_rp_penuh($q['pendapatan_dimuka']['nilai']) ?> berasal dari invoice <?= (int) $q['pendapatan_dimuka']['n'] ?> event yang acaranya baru berlangsung setelah <?= lk_tgl($d['sampai']) ?>. Karena pendapatan diakui saat invoice, jumlah ini sebenarnya masih berupa kewajiban menyelenggarakan event; pembaca laporan perlu mempertimbangkannya.</li>
</ul>
