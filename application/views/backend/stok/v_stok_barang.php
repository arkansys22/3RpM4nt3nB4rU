<?php
// Form barang baru / ubah barang. Variabel: $barang (objek supplies atau null), $kategori_list, $satuan_list, $old
$csrf_nama = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$input = 'w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
$edit = $barang !== null;
$val = function ($k, $d = '') use ($old, $barang) {
    if (is_array($old) && isset($old[$k])) return $old[$k];
    return $barang !== null && isset($barang->$k) ? (string) $barang->$k : $d;
};
$this->load->view('backend/stok/layout_atas', ['judul' => $edit ? 'Ubah Barang' : 'Barang Baru', 'nav' => 'daftar']);
?>
<form method="post" action="<?= $edit ? site_url('supplies/update/' . $barang->id_session) : site_url('supplies/store') ?>" class="max-w-2xl">
  <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">

  <div class="mb-4">
    <label class="mb-2 block text-sm font-medium">Nama Barang</label>
    <input type="text" name="product_name" required maxlength="255" value="<?= lk_h($val('product_name')) ?>" placeholder="Contoh: Lakban bening" class="<?= $input ?>">
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
      <label class="mb-2 block text-sm font-medium">Kategori</label>
      <select name="kategori_id" class="<?= $input ?>">
        <option value="none">Belum dikategorikan</option>
        <?php foreach ($kategori_list as $k): ?>
          <option value="<?= (int) $k->id ?>" <?= $val('kategori_id') === (string) $k->id ? 'selected' : '' ?>><?= lk_h($k->nama) ?></option>
        <?php endforeach; ?>
      </select>
      <p class="mt-1 text-xs text-gray-500">Kategori bisa ditambah di tab <a href="<?= site_url('supplies/kategori') ?>" class="text-blue-600 hover:underline">Kategori</a>.</p>
    </div>
    <div>
      <label class="mb-2 block text-sm font-medium">Satuan</label>
      <input type="text" name="satuan" required maxlength="30" list="daftar-satuan" value="<?= lk_h($val('satuan', 'pcs')) ?>" class="<?= $input ?>">
      <datalist id="daftar-satuan"><?php foreach ($satuan_list as $s): ?><option value="<?= lk_h($s) ?>"><?php endforeach; ?></datalist>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
      <label class="mb-2 block text-sm font-medium">Stok Minimum <span class="text-xs text-gray-500 font-normal">(peringatan "menipis")</span></label>
      <input type="text" inputmode="numeric" name="stok_minimum" required value="<?= lk_h($val('stok_minimum', '10')) ?>" class="<?= $input ?>">
    </div>
    <?php if (!$edit): ?>
      <div>
        <label class="mb-2 block text-sm font-medium">Stok Awal <span class="text-xs text-gray-500 font-normal">(opsional)</span></label>
        <input type="text" inputmode="numeric" name="stok_awal" value="<?= lk_h($val('stok_awal', '0')) ?>" class="<?= $input ?>">
      </div>
    <?php endif; ?>
  </div>

  <?php if (!$edit): ?>
    <div class="mb-6">
      <label class="mb-2 block text-sm font-medium">Tanggal Stok Awal</label>
      <input type="date" name="tanggal" max="<?= date('Y-m-d') ?>" value="<?= lk_h($val('tanggal', date('Y-m-d'))) ?>" class="<?= $input ?> sm:w-60">
    </div>
  <?php else: ?>
    <p class="mb-6 text-sm text-gray-500">Jumlah stok tidak diubah di sini. Gunakan tab Barang Masuk, Barang Keluar, atau Koreksi Stok supaya riwayatnya tercatat.</p>
  <?php endif; ?>

  <div class="flex gap-3">
    <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-md hover:bg-green-700 font-semibold">Simpan</button>
    <a href="<?= $edit ? site_url('supplies/lihat/' . $barang->id_session) : site_url('supplies') ?>" class="bg-gray-400 text-white px-6 py-2.5 rounded-md hover:bg-gray-500 font-semibold">Batal</a>
  </div>
</form>
<?php $this->load->view('backend/stok/layout_bawah'); ?>
