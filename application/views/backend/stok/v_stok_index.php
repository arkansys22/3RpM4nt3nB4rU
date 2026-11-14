<?php
$csrf_nama = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$input = 'rounded-md border border-stroke dark:border-strokedark px-3 py-2 text-sm dark:bg-boxdark dark:text-white';
$qs_sekarang = http_build_query(array_filter(['q' => $q, 'kategori' => $kat, 'status' => $st, 'urut' => $urut !== 'nama' ? $urut : '', 'dari' => $dari_in, 'sampai' => $sampai_in], function ($v) { return $v !== '' && $v !== null; }));
$badge = ['aman' => ['Aman', 'bg-green-100 text-green-700'], 'menipis' => ['Menipis', 'bg-yellow-100 text-yellow-800'], 'habis' => ['Habis', 'bg-red-100 text-red-700']];
$n = function ($x) { return number_format((float) $x, 0, ',', '.'); };
$tombol = '<a href="' . site_url('supplies/create') . '" class="bg-green-600 text-white px-4 py-2.5 rounded-md hover:bg-green-700 font-semibold">+ Barang Baru</a>'
    . '<a href="' . site_url('supplies/export') . '?' . lk_h(http_build_query(['dari' => $dari, 'sampai' => $sampai])) . '" class="bg-blue-600 text-white px-4 py-2.5 rounded-md hover:bg-blue-700 font-semibold">Unduh Excel</a>';
$this->load->view('backend/stok/layout_atas', ['judul' => 'Daftar Stok', 'nav' => 'daftar', 'tombol' => $tombol]);
?>

<!-- Ringkasan -->
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-5">
  <?php
  $kartu = [
      ['Jenis Barang', $n($ringkas['jenis']), ''],
      ['Total Unit di Stok', $n($ringkas['unit']), ''],
      ['Total Barang Masuk', $n($ringkas['masuk']), 'text-green-600'],
      ['Total Barang Keluar', $n($ringkas['keluar']), 'text-red-600'],
      ['Stok Menipis', $n($ringkas['menipis']), $ringkas['menipis'] > 0 ? 'text-yellow-600' : ''],
      ['Stok Habis', $n($ringkas['habis']), $ringkas['habis'] > 0 ? 'text-red-600' : ''],
  ];
  foreach ($kartu as $k): ?>
    <div class="rounded-lg border border-stroke dark:border-strokedark p-3">
      <p class="text-xs text-gray-500 dark:text-gray-400"><?= lk_h($k[0]) ?></p>
      <p class="text-xl font-bold <?= $k[2] ?>"><?= $k[1] ?></p>
    </div>
  <?php endforeach; ?>
</div>
<?php if ($ringkas['tanpa_kategori'] > 0): ?>
  <p class="mb-3 text-sm text-amber-700">&#9888; <?= (int) $ringkas['tanpa_kategori'] ?> barang belum punya kategori. Pilih barangnya di tabel lalu gunakan "Ubah kategori terpilih".</p>
<?php endif; ?>

<!-- Filter -->
<form method="get" class="mb-4 p-4 rounded-lg bg-gray-50 dark:bg-neutral-700 flex flex-wrap items-end gap-3">
  <div>
    <label class="block text-xs text-gray-500 mb-1">Cari nama barang</label>
    <input type="text" name="q" value="<?= lk_h($q) ?>" placeholder="mis. lakban" class="<?= $input ?>">
  </div>
  <div>
    <label class="block text-xs text-gray-500 mb-1">Kategori</label>
    <select name="kategori" class="<?= $input ?>">
      <option value="">Semua kategori</option>
      <option value="none" <?= $kat === 'none' ? 'selected' : '' ?>>Belum dikategorikan</option>
      <?php foreach ($kategori_list as $k): ?>
        <option value="<?= (int) $k->id ?>" <?= $kat === (string) $k->id ? 'selected' : '' ?>><?= lk_h($k->nama) ?> (<?= (int) $k->jumlah_barang ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label class="block text-xs text-gray-500 mb-1">Status stok</label>
    <select name="status" class="<?= $input ?>">
      <option value="">Semua</option>
      <?php foreach ($badge as $kunci => $b): ?>
        <option value="<?= $kunci ?>" <?= $st === $kunci ? 'selected' : '' ?>><?= $b[0] ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label class="block text-xs text-gray-500 mb-1">Urutkan</label>
    <select name="urut" class="<?= $input ?>">
      <?php foreach (['nama' => 'Nama A-Z', 'kategori' => 'Kategori', 'stok' => 'Stok terbanyak', 'masuk' => 'Paling banyak masuk', 'keluar' => 'Paling banyak keluar'] as $kunci => $lbl): ?>
        <option value="<?= $kunci ?>" <?= $urut === $kunci ? 'selected' : '' ?>><?= $lbl ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label class="block text-xs text-gray-500 mb-1">Masuk/keluar pada periode (opsional)</label>
    <div class="flex items-center gap-1">
      <input type="date" name="dari" value="<?= lk_h($dari_in) ?>" class="<?= $input ?>">
      <span class="text-gray-400">s/d</span>
      <input type="date" name="sampai" value="<?= lk_h($sampai_in) ?>" class="<?= $input ?>">
    </div>
  </div>
  <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold text-sm">Terapkan</button>
  <a href="<?= site_url('supplies') ?>" class="px-3 py-2 text-sm text-gray-600 dark:text-gray-300 hover:underline">Reset</a>
</form>

<!-- Ubah kategori massal -->
<form id="form-bulk" method="post" action="<?= site_url('supplies/kategorikan') ?>" class="mb-3 flex flex-wrap items-center gap-2 text-sm" onsubmit="var k = this.kategori_id.value; if (k === '') { alert('Pilih kategori tujuan dulu.'); return false; } if (k === 'none') { return confirm('Kosongkan kategori semua barang yang dipilih?'); } return true;">
  <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
  <input type="hidden" name="balik" value="<?= lk_h($qs_sekarang) ?>">
  <span class="text-gray-600 dark:text-gray-300">Barang terpilih:</span>
  <select name="kategori_id" class="<?= $input ?>">
    <option value="">-- pilih kategori tujuan --</option>
    <option value="none">(Kosongkan kategori)</option>
    <?php foreach ($kategori_list as $k): ?><option value="<?= (int) $k->id ?>"><?= lk_h($k->nama) ?></option><?php endforeach; ?>
  </select>
  <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-800 text-sm">Ubah kategori terpilih</button>
  <span class="text-gray-500"><?= count($daftar) ?> barang ditampilkan</span>
</form>

<div class="overflow-x-auto rounded-lg shadow-md mb-6">
  <table class="min-w-full text-sm text-left whitespace-nowrap bg-white dark:bg-neutral-800">
    <thead class="uppercase tracking-wider text-xs border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
      <tr>
        <th class="px-3 py-3"><input type="checkbox" onclick="document.querySelectorAll('.cek-barang').forEach(c => c.checked = this.checked)"></th>
        <th class="px-3 py-3">Barang</th>
        <th class="px-3 py-3">Kategori</th>
        <th class="px-3 py-3 text-right">Masuk</th>
        <th class="px-3 py-3 text-right">Keluar</th>
        <?php if ($pakai_periode): ?>
          <th class="px-3 py-3 text-right text-blue-700" title="<?= lk_h($dari_in !== '' ? lk_tgl($dari_in) : 'awal') ?> s/d <?= lk_h($sampai_in !== '' ? lk_tgl($sampai_in) : 'sekarang') ?>">Masuk (periode)</th>
          <th class="px-3 py-3 text-right text-blue-700">Keluar (periode)</th>
        <?php endif; ?>
        <th class="px-3 py-3 text-right">Stok</th>
        <th class="px-3 py-3 text-right">Min.</th>
        <th class="px-3 py-3">Status</th>
        <th class="px-3 py-3">Terakhir</th>
        <th class="px-3 py-3">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($daftar)): ?>
        <tr><td colspan="<?= $pakai_periode ? 12 : 10 ?>" class="px-4 py-8 text-center text-gray-500">Tidak ada barang yang cocok dengan filter.</td></tr>
      <?php endif; ?>
      <?php foreach ($daftar as $b): $bd = $badge[$b->status_stok]; ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600 hover:bg-gray-50 dark:hover:bg-neutral-700">
          <td class="px-3 py-2"><input type="checkbox" class="cek-barang" name="barang[]" form="form-bulk" value="<?= lk_h($b->id_session) ?>"></td>
          <td class="px-3 py-2">
            <a href="<?= site_url('supplies/lihat/' . $b->id_session) ?>" class="font-semibold text-blue-600 hover:underline"><?= lk_h(trim($b->product_name)) ?></a>
            <?php if ((int) $b->total_penyesuaian !== 0): ?><span class="text-xs text-gray-400" title="Koreksi stok (stock opname)"> koreksi <?= $b->total_penyesuaian > 0 ? '+' : '' ?><?= $n($b->total_penyesuaian) ?></span><?php endif; ?>
          </td>
          <td class="px-3 py-2 text-gray-700 dark:text-gray-300"><?= $b->kategori ? lk_h($b->kategori) : '<span class="text-amber-600">belum ada</span>' ?></td>
          <td class="px-3 py-2 text-right text-green-700"><?= $n($b->total_masuk) ?></td>
          <td class="px-3 py-2 text-right text-red-600"><?= $n($b->total_keluar) ?></td>
          <?php if ($pakai_periode): ?>
            <td class="px-3 py-2 text-right text-blue-700"><?= $n($b->masuk_periode) ?></td>
            <td class="px-3 py-2 text-right text-blue-700"><?= $n($b->keluar_periode) ?></td>
          <?php endif; ?>
          <td class="px-3 py-2 text-right font-bold"><?= $n($b->stok) ?> <span class="font-normal text-xs text-gray-500"><?= lk_h($b->satuan) ?></span></td>
          <td class="px-3 py-2 text-right text-gray-500"><?= $n($b->stok_minimum) ?></td>
          <td class="px-3 py-2"><span class="px-2 py-1 rounded text-xs font-semibold <?= $bd[1] ?>"><?= $bd[0] ?></span></td>
          <td class="px-3 py-2 text-gray-500"><?= $b->terakhir ? lk_tgl($b->terakhir) : '-' ?></td>
          <td class="px-3 py-2">
            <div class="flex gap-1">
              <a href="<?= site_url('supplies/masuk?b=' . rawurlencode($b->id_session)) ?>" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700" title="Catat barang masuk">+ Masuk</a>
              <a href="<?= site_url('supplies/keluar?b=' . rawurlencode($b->id_session)) ?>" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700" title="Catat barang keluar">&minus; Keluar</a>
              <a href="<?= site_url('supplies/ubah/' . $b->id_session) ?>" class="bg-gray-500 text-white px-2 py-1 rounded hover:bg-gray-600">Ubah</a>
              <form method="post" action="<?= site_url('supplies/delete/' . $b->id_session) ?>" onsubmit="return confirm('Pindahkan barang ini ke Recycle Bin? Riwayat stoknya tetap tersimpan.')">
                <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                <button type="submit" class="bg-gray-300 text-gray-800 px-2 py-1 rounded hover:bg-gray-400">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<h2 class="font-semibold mb-2">Aktivitas Terbaru <span class="text-xs font-normal text-gray-500">(30 hari terakhir)</span></h2>
<div class="overflow-x-auto rounded-lg shadow-md">
  <table class="min-w-full text-sm text-left whitespace-nowrap bg-white dark:bg-neutral-800">
    <thead class="text-xs uppercase bg-gray-100 dark:bg-neutral-700">
      <tr><th class="px-3 py-2">Tanggal</th><th class="px-3 py-2">Barang</th><th class="px-3 py-2">Jenis</th><th class="px-3 py-2 text-right">Jumlah</th><th class="px-3 py-2">Keterangan</th><th class="px-3 py-2">Oleh</th></tr>
    </thead>
    <tbody>
      <?php if (empty($terbaru)): ?><tr><td colspan="6" class="px-3 py-4 text-center text-gray-500">Belum ada pergerakan dalam 30 hari terakhir.</td></tr><?php endif; ?>
      <?php foreach ($terbaru as $t):
        $masuk = (int) $t->goods_in > 0; $dibatal = $t->status === 'Dibatalkan'; $koreksi = strpos((string) $t->status, 'Penyesuaian') === 0; ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600 <?= $dibatal ? 'opacity-50 line-through' : '' ?>">
          <td class="px-3 py-2"><?= lk_tgl($t->tanggal) ?></td>
          <td class="px-3 py-2"><a href="<?= site_url('supplies/lihat/' . $t->id_session) ?>" class="text-blue-600 hover:underline"><?= lk_h(trim($t->product_name)) ?></a></td>
          <td class="px-3 py-2"><?= $koreksi ? 'Koreksi' : ($masuk ? 'Masuk' : 'Keluar') ?></td>
          <td class="px-3 py-2 text-right font-semibold <?= $masuk ? 'text-green-700' : 'text-red-600' ?>"><?= $masuk ? '+' : '&minus;' ?><?= $n(abs($masuk ? $t->goods_in : $t->goods_out)) ?> <?= lk_h($t->satuan) ?></td>
          <td class="px-3 py-2 text-gray-600 dark:text-gray-300"><?= lk_h(mb_strimwidth((string) $t->detail, 0, 50, '...')) ?></td>
          <td class="px-3 py-2 text-gray-500"><?= lk_h($nama_user[$t->created_by] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php $this->load->view('backend/stok/layout_bawah'); ?>
