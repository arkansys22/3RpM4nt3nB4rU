<?php
// Form barang masuk / barang keluar / koreksi stok. Variabel: $mode (masuk|keluar|koreksi), $barang_list, $dipilih, $project_list, $old
$csrf_nama = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$input = 'w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
$cfg = [
    'masuk' => ['Barang Masuk', 'masuk', site_url('supplies/store2'), 'Jumlah Masuk', 'Contoh: pembelian, titipan, pengembalian dari event', 'bg-green-600 hover:bg-green-700', 'Simpan Barang Masuk'],
    'keluar' => ['Barang Keluar', 'keluar', site_url('supplies/store3'), 'Jumlah Keluar', 'Contoh: dipakai untuk event, rusak, hilang', 'bg-red-600 hover:bg-red-700', 'Simpan Barang Keluar'],
    'koreksi' => ['Koreksi Stok (Stock Opname)', 'opname', site_url('supplies/opname_store'), 'Stok Fisik Hasil Hitung', 'Contoh: hasil hitung ulang gudang', 'bg-blue-600 hover:bg-blue-700', 'Simpan Koreksi'],
][$mode];
$val = function ($k, $d = '') use ($old) { return is_array($old) && isset($old[$k]) ? $old[$k] : $d; };
$pilih = $val('id_session', $dipilih);
$tanggal = $val('tanggal', date('Y-m-d'));

// kelompokkan barang per kategori untuk dropdown
$grup = [];
foreach ($barang_list as $b) {
    $grup[$b->kategori ?: 'Belum dikategorikan'][] = $b;
}
ksort($grup);
$this->load->view('backend/stok/layout_atas', ['judul' => $cfg[0], 'nav' => $cfg[1]]);
?>

<form method="post" action="<?= $cfg[2] ?>" class="max-w-2xl">
  <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">

  <?php if ($mode === 'koreksi'): ?>
    <p class="mb-4 text-sm text-gray-600 dark:text-gray-300">Pakai ini kalau hasil hitung fisik berbeda dari stok di sistem. Sistem mencatat selisihnya sebagai koreksi (tidak dihitung sebagai barang masuk/keluar biasa).</p>
  <?php endif; ?>

  <div class="mb-4">
    <label class="mb-2 block text-sm font-medium">Barang</label>
    <select name="id_session" id="pilih-barang" required class="<?= $input ?>">
      <option value="">-- pilih barang --</option>
      <?php foreach ($grup as $nama_kat => $items): ?>
        <optgroup label="<?= lk_h($nama_kat) ?>">
          <?php foreach ($items as $b): ?>
            <option value="<?= lk_h($b->id_session) ?>" data-stok="<?= (int) $b->stok ?>" data-satuan="<?= lk_h($b->satuan) ?>" <?= $pilih === $b->id_session ? 'selected' : '' ?>>
              <?= lk_h(trim($b->product_name)) ?> &mdash; stok <?= (int) $b->stok ?> <?= lk_h($b->satuan) ?>
            </option>
          <?php endforeach; ?>
        </optgroup>
      <?php endforeach; ?>
    </select>
    <p id="info-stok" class="mt-1 text-sm text-blue-600"></p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
      <label class="mb-2 block text-sm font-medium"><?= $cfg[3] ?> <span id="satuan-label" class="text-gray-500 font-normal"></span></label>
      <input type="text" inputmode="numeric" name="jumlah" required value="<?= lk_h($val('jumlah')) ?>" placeholder="<?= $mode === 'koreksi' ? '0 atau lebih' : 'Contoh: 10' ?>" class="<?= $input ?>">
    </div>
    <div>
      <label class="mb-2 block text-sm font-medium">Tanggal</label>
      <input type="date" name="tanggal" required max="<?= date('Y-m-d') ?>" value="<?= lk_h($tanggal) ?>" class="<?= $input ?>">
    </div>
  </div>

  <?php if ($mode === 'keluar'): ?>
    <div class="mb-4">
      <label class="mb-2 block text-sm font-medium">Dipakai untuk event / project <span class="text-xs text-gray-500 font-normal">(opsional)</span></label>
      <select name="project_id_session" class="<?= $input ?>">
        <option value="">-- tidak terkait event --</option>
        <?php foreach ($project_list as $p): ?>
          <option value="<?= lk_h($p->id_session) ?>" <?= $val('project_id_session') === $p->id_session ? 'selected' : '' ?>><?= lk_h($p->project_name) ?> (<?= lk_tgl($p->event_date) ?>)</option>
        <?php endforeach; ?>
      </select>
      <p class="mt-1 text-xs text-gray-500">Menampilkan event 60 hari terakhir sampai 180 hari ke depan.</p>
    </div>
  <?php endif; ?>

  <div class="mb-6">
    <label class="mb-2 block text-sm font-medium">Keterangan <span class="text-xs text-gray-500 font-normal">(opsional)</span></label>
    <textarea name="detail" rows="2" maxlength="500" placeholder="<?= lk_h($cfg[4]) ?>" class="<?= $input ?>"><?= lk_h($val('detail')) ?></textarea>
  </div>

  <div class="flex gap-3">
    <button type="submit" class="<?= $cfg[5] ?> text-white px-6 py-2.5 rounded-md font-semibold"><?= $cfg[6] ?></button>
    <a href="<?= site_url('supplies') ?>" class="bg-gray-400 text-white px-6 py-2.5 rounded-md hover:bg-gray-500 font-semibold">Batal</a>
  </div>
</form>

<?php
$skrip = <<<'JS'
<script>
  (function () {
    var sel = document.getElementById('pilih-barang');
    function perbarui() {
      var o = sel.options[sel.selectedIndex];
      var info = document.getElementById('info-stok'), sat = document.getElementById('satuan-label');
      if (!o || !o.value) { info.textContent = ''; sat.textContent = ''; return; }
      info.textContent = 'Stok sekarang: ' + parseInt(o.getAttribute('data-stok'), 10).toLocaleString('id-ID') + ' ' + o.getAttribute('data-satuan');
      sat.textContent = '(' + o.getAttribute('data-satuan') + ')';
    }
    sel.addEventListener('change', perbarui);
    perbarui();
  })();
</script>
JS;
$this->load->view('backend/stok/layout_bawah', ['skrip' => $skrip]);
?>
