<?php
// Laporan Perubahan Ekuitas. Variabel: $d (data laporan), $excel (bool)
$e = $d['ekuitas'];
$selisih = $e['akhir'] - $e['akhir_neraca'];
?>
<div class="mb-2">
  <h2 class="text-xl font-bold">Laporan Perubahan Ekuitas</h2>
  <p class="text-sm text-gray-500">Periode <?= lk_tgl($d['dari']) ?> s/d <?= lk_tgl($d['sampai']) ?> &middot; dalam Rupiah</p>
</div>
<div class="overflow-x-auto mb-4">
  <table class="min-w-full text-sm border border-gray-200 dark:border-neutral-600" <?= $excel ? 'border="1"' : '' ?>>
    <thead class="bg-blue-600 text-white">
      <tr><th class="px-3 py-2 text-left">Keterangan</th><th class="px-3 py-2 text-right">Jumlah</th></tr>
    </thead>
    <tbody>
      <?php foreach ($e['baris'] as $b): ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600 <?= !empty($b['tebal']) ? 'font-semibold bg-gray-50 dark:bg-neutral-800' : '' ?>">
          <td class="px-3 py-2 <?= empty($b['tebal']) ? 'pl-6' : '' ?>"><?= lk_h($b['label']) ?></td>
          <td class="px-3 py-2 text-right whitespace-nowrap <?= $b['jumlah'] < 0 ? 'text-red-600' : '' ?>"><?= lk_rp($b['jumlah']) ?></td>
        </tr>
      <?php endforeach; ?>
      <tr class="bg-blue-50 dark:bg-neutral-700 font-bold">
        <td class="px-3 py-2">EKUITAS AKHIR PERIODE</td>
        <td class="px-3 py-2 text-right whitespace-nowrap <?= $e['akhir'] < 0 ? 'text-red-600' : '' ?>"><?= lk_rp($e['akhir']) ?></td>
      </tr>
    </tbody>
  </table>
</div>
<?php if (abs($selisih) < 1): ?>
  <p class="text-sm text-green-700">&#10003; Ekuitas akhir sama dengan Total Ekuitas di Neraca.</p>
<?php else: ?>
  <p class="text-sm text-amber-700">&#9888; Ekuitas akhir berbeda dari Neraca sebesar Rp <?= number_format(abs($selisih), 0, ',', '.') ?> &mdash; hubungi pengembang.</p>
<?php endif; ?>
