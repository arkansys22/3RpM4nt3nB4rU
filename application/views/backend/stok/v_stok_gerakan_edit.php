<?php
// Ubah satu pergerakan (masuk/keluar). Variabel: $g (baris supplies_stock + product_name, satuan), $project_list, $project_sekarang, $old
$csrf_nama = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$input = 'w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
$masuk = (int) $g->goods_in > 0;
$jumlah = $masuk ? (int) $g->goods_in : abs((int) $g->goods_out);
$this->load->view('backend/stok/layout_atas', ['judul' => 'Ubah Pergerakan', 'nav' => 'daftar']);
?>
<p class="mb-4 text-sm text-gray-600 dark:text-gray-300"><b><?= lk_h(trim($g->product_name)) ?></b> &middot; <?= $masuk ? 'Barang Masuk' : 'Barang Keluar' ?>. Setelah disimpan, saldo seluruh riwayat barang ini dihitung ulang. Perubahan yang membuat saldo minus akan ditolak.</p>
<form method="post" action="<?= site_url('supplies/gerakan_update/' . $g->id_sessionstock) ?>" class="max-w-2xl">
  <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
      <label class="mb-2 block text-sm font-medium">Jumlah <span class="text-gray-500 font-normal">(<?= lk_h($g->satuan) ?>)</span></label>
      <input type="text" inputmode="numeric" name="jumlah" required value="<?= $jumlah ?>" class="<?= $input ?>">
    </div>
    <div>
      <label class="mb-2 block text-sm font-medium">Tanggal</label>
      <input type="date" name="tanggal" required max="<?= date('Y-m-d') ?>" value="<?= lk_h($g->tanggal) ?>" class="<?= $input ?>">
    </div>
  </div>
  <?php if (!$masuk): ?>
    <div class="mb-4">
      <label class="mb-2 block text-sm font-medium">Dipakai untuk event / project</label>
      <select name="project_id_session" class="<?= $input ?>">
        <option value="">-- tidak terkait event --</option>
        <?php $ada = false; foreach ($project_list as $p): $pilih = $g->project_id_session === $p->id_session; $ada = $ada || $pilih; ?>
          <option value="<?= lk_h($p->id_session) ?>" <?= $pilih ? 'selected' : '' ?>><?= lk_h($p->project_name) ?> (<?= lk_tgl($p->event_date) ?>)</option>
        <?php endforeach; ?>
        <?php if (!$ada && !empty($g->project_id_session) && $project_sekarang): ?>
          <option value="<?= lk_h($g->project_id_session) ?>" selected><?= lk_h($project_sekarang) ?></option>
        <?php endif; ?>
      </select>
    </div>
  <?php endif; ?>
  <div class="mb-6">
    <label class="mb-2 block text-sm font-medium">Keterangan</label>
    <textarea name="detail" rows="2" maxlength="500" class="<?= $input ?>"><?= lk_h($g->detail) ?></textarea>
  </div>
  <div class="flex gap-3">
    <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-md hover:bg-green-700 font-semibold">Simpan Perubahan</button>
    <a href="<?= site_url('supplies/lihat/' . $g->id_session) ?>" class="bg-gray-400 text-white px-6 py-2.5 rounded-md hover:bg-gray-500 font-semibold">Batal</a>
  </div>
</form>
<?php $this->load->view('backend/stok/layout_bawah'); ?>
