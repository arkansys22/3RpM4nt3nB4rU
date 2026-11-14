<?php
// Kelola kategori barang. Variabel: $kategori_list, $edit (objek kategori atau null), $old
$csrf_nama = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$input = 'w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
$val = function ($k, $d = '') use ($old, $edit) {
    if (is_array($old) && isset($old[$k])) return $old[$k];
    return $edit && isset($edit->{$k === 'nama' ? 'nama' : 'keterangan'}) ? (string) $edit->{$k === 'nama' ? 'nama' : 'keterangan'} : $d;
};
$this->load->view('backend/stok/layout_atas', ['judul' => 'Kategori Barang', 'nav' => 'kategori']);
?>
<div class="mb-6 p-5 rounded-lg bg-gray-50 dark:bg-neutral-700 max-w-3xl">
  <h2 class="font-semibold mb-3"><?= $edit ? 'Ubah Kategori' : 'Tambah Kategori' ?></h2>
  <form method="post" action="<?= $edit ? site_url('supplies/kategori_update/' . (int) $edit->id) : site_url('supplies/kategori_store') ?>">
    <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
      <div>
        <label class="mb-2 block text-sm font-medium">Nama Kategori</label>
        <input type="text" name="nama" required maxlength="100" value="<?= lk_h($val('nama')) ?>" placeholder="Contoh: ATK & Alat Tulis" class="<?= $input ?>">
      </div>
      <div>
        <label class="mb-2 block text-sm font-medium">Keterangan <span class="text-xs text-gray-500 font-normal">(opsional)</span></label>
        <input type="text" name="keterangan" maxlength="255" value="<?= lk_h($val('keterangan')) ?>" class="<?= $input ?>">
      </div>
    </div>
    <div class="flex gap-3">
      <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-md hover:bg-green-700 font-semibold"><?= $edit ? 'Simpan Perubahan' : 'Tambah' ?></button>
      <?php if ($edit): ?><a href="<?= site_url('supplies/kategori') ?>" class="bg-gray-400 text-white px-5 py-2 rounded-md hover:bg-gray-500 font-semibold">Batal</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="overflow-x-auto rounded-lg shadow-md max-w-4xl">
  <table class="min-w-full text-sm text-left bg-white dark:bg-neutral-800">
    <thead class="uppercase tracking-wider text-xs border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
      <tr><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Keterangan</th><th class="px-4 py-3 text-right">Jumlah Barang</th><th class="px-4 py-3">Aksi</th></tr>
    </thead>
    <tbody>
      <?php if (empty($kategori_list)): ?><tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada kategori.</td></tr><?php endif; ?>
      <?php foreach ($kategori_list as $k): ?>
        <tr class="border-b border-gray-200 dark:border-neutral-600">
          <td class="px-4 py-2 font-semibold"><a href="<?= site_url('supplies?kategori=' . (int) $k->id) ?>" class="text-blue-600 hover:underline"><?= lk_h($k->nama) ?></a></td>
          <td class="px-4 py-2 text-gray-600 dark:text-gray-300"><?= lk_h($k->keterangan) ?></td>
          <td class="px-4 py-2 text-right"><?= (int) $k->jumlah_barang ?></td>
          <td class="px-4 py-2">
            <div class="flex gap-1">
              <a href="<?= site_url('supplies/kategori?ubah=' . (int) $k->id) ?>" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Ubah</a>
              <form method="post" action="<?= site_url('supplies/kategori_delete/' . (int) $k->id) ?>" onsubmit="return confirm('Hapus kategori ini? <?= (int) $k->jumlah_barang ?> barang di dalamnya menjadi belum dikategorikan.')">
                <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php $this->load->view('backend/stok/layout_bawah'); ?>
