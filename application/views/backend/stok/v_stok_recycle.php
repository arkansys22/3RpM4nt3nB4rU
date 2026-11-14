<?php
// Recycle bin barang. Variabel: $daftar (barang berstatus deleted)
$csrf_nama = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$n = function ($x) { return number_format((float) $x, 0, ',', '.'); };
$this->load->view('backend/stok/layout_atas', ['judul' => 'Recycle Bin', 'nav' => 'recycle']);
?>
<p class="mb-4 text-sm text-gray-600 dark:text-gray-300">Barang yang dihapus tetap menyimpan riwayat stoknya. Pulihkan untuk memakainya lagi, atau hapus permanen (tidak bisa dibatalkan).</p>
<div class="overflow-x-auto rounded-lg shadow-md">
  <table class="min-w-full text-sm text-left whitespace-nowrap bg-white dark:bg-neutral-800">
    <thead class="uppercase tracking-wider text-xs border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
      <tr><th class="px-3 py-3">Barang</th><th class="px-3 py-3">Kategori</th><th class="px-3 py-3 text-right">Masuk</th><th class="px-3 py-3 text-right">Keluar</th><th class="px-3 py-3 text-right">Stok</th><th class="px-3 py-3">Aksi</th></tr>
    </thead>
    <tbody>
      <?php if (empty($daftar)): ?><tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Recycle Bin kosong.</td></tr><?php endif; ?>
      <?php foreach ($daftar as $b): ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600">
          <td class="px-3 py-2 font-semibold"><a href="<?= site_url('supplies/lihat/' . $b->id_session) ?>" class="text-blue-600 hover:underline"><?= lk_h(trim($b->product_name)) ?></a></td>
          <td class="px-3 py-2"><?= $b->kategori ? lk_h($b->kategori) : '-' ?></td>
          <td class="px-3 py-2 text-right text-green-700"><?= $n($b->total_masuk) ?></td>
          <td class="px-3 py-2 text-right text-red-600"><?= $n($b->total_keluar) ?></td>
          <td class="px-3 py-2 text-right font-bold"><?= $n($b->stok) ?> <?= lk_h($b->satuan) ?></td>
          <td class="px-3 py-2">
            <div class="flex gap-1">
              <form method="post" action="<?= site_url('supplies/restore/' . $b->id_session) ?>">
                <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Pulihkan</button>
              </form>
              <form method="post" action="<?= site_url('supplies/permanent_delete/' . $b->id_session) ?>" onsubmit="return confirm('Hapus PERMANEN barang ini beserta seluruh riwayat stoknya? Tidak bisa dibatalkan.')">
                <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Hapus Permanen</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php $this->load->view('backend/stok/layout_bawah'); ?>
