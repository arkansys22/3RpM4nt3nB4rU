<?php
// Riwayat & ringkasan satu barang. Variabel: $barang, $riwayat, $dari, $sampai, $nama_user, $nama_project
$csrf_nama = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$input = 'rounded-md border border-stroke dark:border-strokedark px-3 py-2 text-sm dark:bg-boxdark dark:text-white';
$n = function ($x) { return number_format((float) $x, 0, ',', '.'); };
$badge = ['aman' => ['Aman', 'bg-green-100 text-green-700'], 'menipis' => ['Menipis', 'bg-yellow-100 text-yellow-800'], 'habis' => ['Habis', 'bg-red-100 text-red-700']][$barang->status_stok];
$aktif = $barang->status === 'created';
$tombol = $aktif
    ? '<a href="' . site_url('supplies/masuk?b=' . rawurlencode($barang->id_session)) . '" class="bg-green-600 text-white px-4 py-2.5 rounded-md hover:bg-green-700 font-semibold">+ Masuk</a>'
        . '<a href="' . site_url('supplies/keluar?b=' . rawurlencode($barang->id_session)) . '" class="bg-red-600 text-white px-4 py-2.5 rounded-md hover:bg-red-700 font-semibold">&minus; Keluar</a>'
        . '<a href="' . site_url('supplies/opname?b=' . rawurlencode($barang->id_session)) . '" class="bg-blue-600 text-white px-4 py-2.5 rounded-md hover:bg-blue-700 font-semibold">Koreksi</a>'
        . '<a href="' . site_url('supplies/ubah/' . $barang->id_session) . '" class="bg-gray-500 text-white px-4 py-2.5 rounded-md hover:bg-gray-600 font-semibold">Ubah</a>'
    : '';
$this->load->view('backend/stok/layout_atas', ['judul' => trim($barang->product_name), 'nav' => 'daftar', 'tombol' => $tombol]);
?>

<?php if (!$aktif): ?>
  <p class="mb-4 p-3 rounded-md bg-gray-100 text-gray-700 text-sm">Barang ini ada di Recycle Bin. Pulihkan dulu untuk mencatat pergerakan baru.</p>
<?php endif; ?>

<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
  <div class="rounded-lg border border-stroke dark:border-strokedark p-3"><p class="text-xs text-gray-500">Stok Sekarang</p><p class="text-xl font-bold"><?= $n($barang->stok) ?> <span class="text-sm font-normal"><?= lk_h($barang->satuan) ?></span></p><span class="px-2 py-0.5 rounded text-xs font-semibold <?= $badge[1] ?>"><?= $badge[0] ?></span></div>
  <div class="rounded-lg border border-stroke dark:border-strokedark p-3"><p class="text-xs text-gray-500">Total Masuk</p><p class="text-xl font-bold text-green-600"><?= $n($barang->total_masuk) ?></p></div>
  <div class="rounded-lg border border-stroke dark:border-strokedark p-3"><p class="text-xs text-gray-500">Total Keluar</p><p class="text-xl font-bold text-red-600"><?= $n($barang->total_keluar) ?></p></div>
  <div class="rounded-lg border border-stroke dark:border-strokedark p-3"><p class="text-xs text-gray-500">Koreksi (opname)</p><p class="text-xl font-bold"><?= $barang->total_penyesuaian > 0 ? '+' : '' ?><?= $n($barang->total_penyesuaian) ?></p></div>
  <div class="rounded-lg border border-stroke dark:border-strokedark p-3"><p class="text-xs text-gray-500">Kategori &middot; Stok Minimum</p><p class="text-sm font-semibold"><?= $barang->kategori ? lk_h($barang->kategori) : '<span class="text-amber-600">belum ada kategori</span>' ?></p><p class="text-sm text-gray-500">Minimum <?= $n($barang->stok_minimum) ?> <?= lk_h($barang->satuan) ?></p></div>
</div>
<p class="mb-4 text-xs text-gray-500">Stok = total masuk &minus; total keluar &plusmn; koreksi. Total masuk/keluar dihitung dari seluruh riwayat (tidak terpengaruh filter tanggal di bawah).</p>

<form method="get" class="mb-3 flex flex-wrap items-end gap-3">
  <div><label class="block text-xs text-gray-500 mb-1">Dari tanggal</label><input type="date" name="dari" value="<?= lk_h($dari) ?>" class="<?= $input ?>"></div>
  <div><label class="block text-xs text-gray-500 mb-1">Sampai tanggal</label><input type="date" name="sampai" value="<?= lk_h($sampai) ?>" class="<?= $input ?>"></div>
  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-semibold">Terapkan</button>
  <a href="<?= site_url('supplies/lihat/' . $barang->id_session) ?>" class="px-2 py-2 text-sm text-gray-600 hover:underline">Reset</a>
</form>

<?php if (!empty($total_periode)): ?>
  <p class="mb-2 text-sm text-gray-700 dark:text-gray-300">Pada periode yang dipilih: masuk <b class="text-green-700"><?= $n($total_periode['masuk']) ?></b>, keluar <b class="text-red-600"><?= $n($total_periode['keluar']) ?></b><?= $total_periode['koreksi'] != 0 ? ', koreksi <b>' . ($total_periode['koreksi'] > 0 ? '+' : '') . $n($total_periode['koreksi']) . '</b>' : '' ?> <?= lk_h($barang->satuan) ?>.</p>
<?php endif; ?>
<div class="overflow-x-auto rounded-lg shadow-md mb-4">
  <table class="min-w-full text-sm text-left whitespace-nowrap bg-white dark:bg-neutral-800">
    <thead class="uppercase tracking-wider text-xs border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
      <tr>
        <th class="px-3 py-3">Tanggal</th>
        <th class="px-3 py-3">Jenis</th>
        <th class="px-3 py-3 text-right">Masuk</th>
        <th class="px-3 py-3 text-right">Keluar</th>
        <th class="px-3 py-3 text-right">Saldo</th>
        <th class="px-3 py-3">Tujuan / Keterangan</th>
        <th class="px-3 py-3">Dicatat oleh</th>
        <?php if ($aktif): ?><th class="px-3 py-3">Aksi</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($riwayat)): ?><tr><td colspan="<?= $aktif ? 8 : 7 ?>" class="px-4 py-8 text-center text-gray-500">Belum ada pergerakan<?= ($dari || $sampai) ? ' pada periode ini' : '' ?>.</td></tr><?php endif; ?>
      <?php foreach ($riwayat as $r):
        $batal = $r->status === 'Dibatalkan'; $koreksi = strpos((string) $r->status, 'Penyesuaian') === 0; $masuk = (int) $r->goods_in > 0;
        $jenis = $batal ? 'Dibatalkan' : ($koreksi ? 'Koreksi' : ($masuk ? 'Barang Masuk' : 'Barang Keluar')); ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600 <?= $batal ? 'opacity-50' : '' ?>">
          <td class="px-3 py-2"><?= lk_tgl($r->tanggal) ?></td>
          <td class="px-3 py-2"><?= $jenis ?><?= $batal ? ' (' . ($masuk ? 'masuk' : 'keluar') . ')' : '' ?></td>
          <td class="px-3 py-2 text-right text-green-700 <?= $batal ? 'line-through' : '' ?>"><?= $masuk ? $n($r->goods_in) : '' ?></td>
          <td class="px-3 py-2 text-right text-red-600 <?= $batal ? 'line-through' : '' ?>"><?= !$masuk ? $n(abs($r->goods_out)) : '' ?></td>
          <td class="px-3 py-2 text-right font-semibold"><?= $batal ? '' : $n($r->amount) ?></td>
          <td class="px-3 py-2">
            <?php if (!empty($r->project_id_session) && isset($nama_project[$r->project_id_session])): ?><span class="text-blue-600">&#9873; <?= lk_h($nama_project[$r->project_id_session]) ?></span><br><?php endif; ?>
            <span class="text-gray-600 dark:text-gray-300"><?= lk_h($r->detail) ?></span>
          </td>
          <td class="px-3 py-2 text-gray-500"><?= lk_h($nama_user[$r->created_by] ?? '-') ?><br><span class="text-xs">dicatat <?= lk_h(date('d-m-Y H:i', strtotime($r->created_at))) ?></span></td>
          <?php if ($aktif): ?>
            <td class="px-3 py-2">
              <?php if (!$batal): ?>
                <div class="flex gap-1">
                  <?php if (!$koreksi): ?><a href="<?= site_url('supplies/gerakan/' . $r->id_sessionstock) ?>" class="bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600">Ubah</a><?php endif; ?>
                  <form method="post" action="<?= site_url('supplies/gerakan_batal/' . $r->id_sessionstock) ?>" onsubmit="return confirm('Batalkan pergerakan ini? Saldo dihitung ulang. Ditolak kalau saldo jadi minus.')">
                    <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Batalkan</button>
                  </form>
                </div>
              <?php endif; ?>
            </td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<a href="<?= site_url('supplies') ?>" class="inline-block bg-gray-400 text-white px-5 py-2 rounded-md hover:bg-gray-500 font-semibold">&larr; Kembali ke daftar</a>
<?php $this->load->view('backend/stok/layout_bawah'); ?>
