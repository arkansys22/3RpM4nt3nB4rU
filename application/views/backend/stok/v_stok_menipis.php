<?php
// Barang yang stoknya menipis atau habis. Variabel: $butuh (daftar barang, sudah terurut: habis dulu)
$n = function ($x) { return number_format((float) $x, 0, ',', '.'); };
$this->load->view('backend/stok/layout_atas', ['judul' => 'Stok Menipis & Habis', 'nav' => 'menipis']);
?>
<p class="mb-4 text-sm text-gray-600 dark:text-gray-300">Barang yang stoknya sama dengan atau di bawah stok minimumnya. Atur stok minimum tiap barang lewat tombol Ubah.</p>
<div class="overflow-x-auto rounded-lg shadow-md">
  <table class="min-w-full text-sm text-left whitespace-nowrap bg-white dark:bg-neutral-800">
    <thead class="uppercase tracking-wider text-xs border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
      <tr><th class="px-3 py-3">Barang</th><th class="px-3 py-3">Kategori</th><th class="px-3 py-3 text-right">Stok</th><th class="px-3 py-3 text-right">Minimum</th><th class="px-3 py-3 text-right">Kekurangan</th><th class="px-3 py-3">Status</th><th class="px-3 py-3">Aksi</th></tr>
    </thead>
    <tbody>
      <?php if (empty($butuh)): ?><tr><td colspan="7" class="px-4 py-8 text-center text-green-700">&#10003; Semua barang stoknya aman.</td></tr><?php endif; ?>
      <?php foreach ($butuh as $b): $habis = $b->status_stok === 'habis'; ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600">
          <td class="px-3 py-2 font-semibold"><a href="<?= site_url('supplies/lihat/' . $b->id_session) ?>" class="text-blue-600 hover:underline"><?= lk_h(trim($b->product_name)) ?></a></td>
          <td class="px-3 py-2"><?= $b->kategori ? lk_h($b->kategori) : '<span class="text-amber-600">belum ada</span>' ?></td>
          <td class="px-3 py-2 text-right font-bold <?= $habis ? 'text-red-600' : 'text-yellow-700' ?>"><?= $n($b->stok) ?> <?= lk_h($b->satuan) ?></td>
          <td class="px-3 py-2 text-right text-gray-500"><?= $n($b->stok_minimum) ?></td>
          <td class="px-3 py-2 text-right"><?= $n(max(0, $b->stok_minimum - $b->stok)) ?></td>
          <td class="px-3 py-2"><span class="px-2 py-1 rounded text-xs font-semibold <?= $habis ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800' ?>"><?= $habis ? 'Habis' : 'Menipis' ?></span></td>
          <td class="px-3 py-2"><a href="<?= site_url('supplies/masuk?b=' . rawurlencode($b->id_session)) ?>" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">+ Catat Barang Masuk</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php $this->load->view('backend/stok/layout_bawah'); ?>
