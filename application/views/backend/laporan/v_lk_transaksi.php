<?php
// Potongan HTML: daftar transaksi COA asal sebuah angka. Variabel: $trx, $kum, $dari, $sampai, $maks
$tampil = array_slice($trx, 0, $maks);
$total = array_sum(array_map(function ($t) { return $t->nominal; }, $trx));
$sumber_kelas = [
    'Invoice' => 'bg-blue-100 text-blue-800', 'Kwitansi' => 'bg-green-100 text-green-800', 'Finance Project' => 'bg-amber-100 text-amber-800',
    'Finance Operational' => 'bg-orange-100 text-orange-800', 'Menu Modal' => 'bg-purple-100 text-purple-800', 'Menu Hutang' => 'bg-red-100 text-red-800',
    'Cicilan Hutang' => 'bg-red-100 text-red-800', 'Utang vendor (Finance Project)' => 'bg-gray-100 text-gray-700',
];
?>
<div data-lk-fragmen="1" class="text-sm">
  <p class="mb-2 text-xs text-gray-500">
    <?= (int) count($trx) ?> transaksi<?= $kum ? ' (dari awal pencatatan sampai ' . lk_tgl($sampai) . ')' : ' (' . lk_tgl($dari) . ' s/d ' . lk_tgl($sampai) . ')' ?>
    &middot; total <b><?= lk_rp_penuh($total) ?></b>
    <?php if (count($trx) > $maks): ?>&middot; <span class="text-amber-700">menampilkan <?= (int) $maks ?> terbaru</span><?php endif; ?>
  </p>
  <div class="overflow-x-auto rounded border border-gray-200">
    <table class="min-w-full text-xs">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="px-2 py-2 text-left">Tanggal</th>
          <th class="px-2 py-2 text-left">Sumber</th>
          <th class="px-2 py-2 text-left">Transaksi</th>
          <th class="px-2 py-2 text-left">Project / Klien</th>
          <th class="px-2 py-2 text-left">Akun</th>
          <th class="px-2 py-2 text-right">Nominal</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tampil)): ?>
          <tr><td colspan="6" class="px-2 py-4 text-center text-gray-500">Tidak ada transaksi.</td></tr>
        <?php endif; ?>
        <?php foreach ($tampil as $t): ?>
          <tr class="border-t border-gray-200 align-top">
            <td class="px-2 py-1.5 whitespace-nowrap"><?= lk_tgl($t->tanggal) ?></td>
            <td class="px-2 py-1.5 whitespace-nowrap"><span class="px-1.5 py-0.5 rounded <?= $sumber_kelas[$t->sumber] ?? 'bg-gray-100 text-gray-700' ?>"><?= lk_h($t->sumber) ?></span></td>
            <td class="px-2 py-1.5"><?= lk_h($t->nama) ?><?= $t->no_dokumen ? '<div class="text-gray-400">' . lk_h($t->no_dokumen) . ($t->status_bayar ? ' &middot; ' . lk_h($t->status_bayar) : '') . '</div>' : '' ?></td>
            <td class="px-2 py-1.5"><?= $t->project ? lk_h($t->project) . ($t->klien ? '<div class="text-gray-400">' . lk_h($t->klien) . '</div>' : '') : '<span class="text-gray-400">-</span>' ?></td>
            <td class="px-2 py-1.5 whitespace-nowrap"><span class="text-gray-400"><?= lk_h($t->kode) ?></span> <?= lk_h($t->nama_akun) ?></td>
            <td class="px-2 py-1.5 text-right whitespace-nowrap font-semibold <?= $t->nominal < 0 ? 'text-red-600' : '' ?>"><?= lk_rp($t->nominal) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
