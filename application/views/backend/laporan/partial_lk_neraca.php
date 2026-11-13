<?php
// Neraca (Laporan Posisi Keuangan). Variabel: $d (data laporan), $excel (bool)
$n = $d['neraca_akhir'];
$n0 = $d['neraca_awal'];

$baris = function ($label, $a, $b, $gaya = '') {
    $kelas = $gaya === 'besar' ? 'bg-blue-50 dark:bg-neutral-700 font-bold' : ($gaya === 'total' ? 'font-semibold bg-gray-50 dark:bg-neutral-800' : '');
    $pad = $gaya === '' ? 'pl-6' : '';
    echo '<tr class="border-b border-gray-200 dark:border-neutral-600 ' . $kelas . '">';
    echo '<td class="px-3 py-2 ' . $pad . '">' . lk_h($label) . '</td>';
    echo '<td class="px-3 py-2 text-right whitespace-nowrap ' . ($a < 0 ? 'text-red-600' : '') . '">' . lk_rp($a) . '</td>';
    echo '<td class="px-3 py-2 text-right whitespace-nowrap text-gray-500 ' . ($b < 0 ? 'text-red-600' : '') . '">' . lk_rp($b) . '</td>';
    echo '</tr>';
};
$judul = function ($teks) {
    echo '<tr class="bg-gray-100 dark:bg-neutral-700"><td colspan="3" class="px-3 py-2 font-semibold">' . lk_h($teks) . '</td></tr>';
};
$kepala = function () use ($d) {
    echo '<thead class="bg-blue-600 text-white"><tr><th class="px-3 py-2 text-left">Keterangan</th>'
        . '<th class="px-3 py-2 text-right">Per ' . lk_tgl($d['sampai']) . '</th>'
        . '<th class="px-3 py-2 text-right">Per ' . lk_tgl($d['sebelum_dari']) . '</th></tr></thead>';
};
?>
<div class="mb-2">
  <h2 class="text-xl font-bold">Neraca (Laporan Posisi Keuangan)</h2>
  <p class="text-sm text-gray-500">Per <?= lk_tgl($d['sampai']) ?> &middot; pembanding awal periode (<?= lk_tgl($d['sebelum_dari']) ?>) &middot; dalam Rupiah</p>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-4">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
      <?php $kepala(); ?>
      <tbody>
        <?php
        $judul('ASET');
        foreach ($n['aset'] as $k => $a) {
            if ($k === 'akumulasi' && $a['jumlah'] == 0 && $n0['aset'][$k]['jumlah'] == 0) {
                continue;
            }
            $baris($a['label'], $a['jumlah'], $n0['aset'][$k]['jumlah']);
        }
        if ($n['selisih_aset'] > 0 || $n0['selisih_aset'] > 0) {
            $baris('Selisih rekonsiliasi (lihat Catatan)', $n['selisih_aset'], $n0['selisih_aset']);
        }
        $baris('TOTAL ASET', $n['total_aset'], $n0['total_aset'], 'besar');
        ?>
      </tbody>
    </table>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
      <?php $kepala(); ?>
      <tbody>
        <?php
        $judul('LIABILITAS');
        $kode_liab = array_unique(array_merge(array_keys($n['liabilitas']), array_keys($n0['liabilitas'])));
        sort($kode_liab, SORT_NATURAL);
        if (!$kode_liab && $n['selisih_liab'] == 0 && $n0['selisih_liab'] == 0) {
            echo '<tr class="border-b border-gray-200 dark:border-neutral-600"><td colspan="3" class="px-3 py-2 pl-6 text-gray-400 italic">Tidak ada liabilitas tercatat</td></tr>';
        }
        foreach ($kode_liab as $kode) {
            $a = $n['liabilitas'][$kode] ?? ['label' => $n0['liabilitas'][$kode]['label'], 'jumlah' => 0];
            $baris($a['label'], $a['jumlah'], $n0['liabilitas'][$kode]['jumlah'] ?? 0);
        }
        if ($n['selisih_liab'] > 0 || $n0['selisih_liab'] > 0) {
            $baris('Subtotal liabilitas dari akun COA', $n['subtotal_liab_akun'], $n0['subtotal_liab_akun'], 'total');
            $baris('Selisih rekonsiliasi (uang masuk belum terhubung ke pendapatan COA)', $n['selisih_liab'], $n0['selisih_liab']);
        }
        $baris('TOTAL LIABILITAS', $n['total_liabilitas'], $n0['total_liabilitas'], 'total');
        $judul('EKUITAS');
        foreach ($n['ekuitas'] as $k => $e) {
            $baris($e['label'], $e['jumlah'], $n0['ekuitas'][$k]['jumlah']);
        }
        $baris('TOTAL EKUITAS', $n['total_ekuitas'], $n0['total_ekuitas'], 'total');
        $baris('TOTAL LIABILITAS & EKUITAS', $n['total_pasiva'], $n0['total_pasiva'], 'besar');
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($n['selisih'] == 0): ?>
  <p class="text-sm text-green-700 mb-2">&#10003; Neraca seimbang: Total Aset = Total Liabilitas &amp; Ekuitas.</p>
<?php else: ?>
  <p class="text-sm text-amber-700 mb-2">
    &#9888; Neraca memuat <b>selisih rekonsiliasi Rp <?= number_format(abs($n['selisih']), 0, ',', '.') ?></b>
    (<?= $n['selisih'] > 0 ? 'uang masuk di akun Kas + sisa piutang melebihi pendapatan yang tercatat di COA' : 'pendapatan di COA melebihi uang masuk di akun Kas + sisa piutang' ?>).
    Selisih ini bukan kesalahan hitung, melainkan tanda data COA belum lengkap &mdash; penjelasan &amp; daftar yang perlu dilengkapi ada di tab Catatan.
  </p>
<?php endif; ?>
<p class="text-xs text-gray-500">
  Kas &amp; Bank dihitung dari seluruh arus transaksi COA (uang masuk dari klien, modal, pinjaman, dikurangi biaya, aset, cicilan dan deviden) karena COA tidak mencatat
  pengurangan saldo kas. Cocokkan dengan saldo rekening sebenarnya. Utang ke vendor per event (210102.xx) tidak dimasukkan karena baru berupa komitmen.
  Piutang dihitung dari invoice berakun COA dikurangi kwitansi Paid proyek yang sama sampai tanggal laporan.
</p>
