<?php
// Isian form hutang (dipakai form tambah di v_hutang.php dan form edit di v_hutang_detail.php).
// $h = objek hutang saat edit, null saat tambah. $jenis_list = daftar jenis.
$v = function ($f, $default = '') use ($h) { return $h ? (string) $h->$f : $default; };
$input = 'w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
$jangka_sekarang = $v('jangka', 'Pendek');
?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Nama Hutang</label>
    <input type="text" name="nama_hutang" required maxlength="255" value="<?= htmlspecialchars($v('nama_hutang')) ?>" placeholder="Contoh: Pinjaman modal dekorasi" class="<?= $input ?>">
  </div>
  <div>
    <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Pemberi Pinjaman / Kreditur</label>
    <input type="text" name="kreditur" required maxlength="255" value="<?= htmlspecialchars($v('kreditur')) ?>" placeholder="Contoh: Bank BCA, ShopeePayLater, Pak Budi" class="<?= $input ?>">
  </div>
  <div>
    <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Jenis</label>
    <select name="jenis" required class="<?= $input ?>">
      <?php foreach ($jenis_list as $j): ?>
        <option value="<?= htmlspecialchars($j) ?>" <?= $v('jenis', 'Pinjaman') === $j ? 'selected' : '' ?>><?= htmlspecialchars($j) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div>
    <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Jangka Waktu</label>
    <select name="jangka" required class="<?= $input ?> js-hutang-jangka">
      <option value="Pendek" <?= $jangka_sekarang === 'Pendek' ? 'selected' : '' ?>>Jangka Pendek (lunas &le; 1 tahun)</option>
      <option value="Panjang" <?= $jangka_sekarang === 'Panjang' ? 'selected' : '' ?>>Jangka Panjang (lebih dari 1 tahun)</option>
    </select>
    <p class="mt-1 text-xs text-blue-600 dark:text-blue-400 js-hutang-akun-hint">
      Dicatat di COA: <b>2000 &ndash; Utang</b>
    </p>
  </div>
  <div>
    <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Nominal Hutang (Pokok)</label>
    <input type="text" inputmode="numeric" name="nominal_pokok" required value="<?= htmlspecialchars(($h && ctype_digit((string) $h->nominal_pokok)) ? number_format($h->nominal_pokok, 0, ',', '.') : ($h ? (string) $h->nominal_pokok : '')) ?>" placeholder="Contoh: 5.000.000" class="<?= $input ?> js-rupiah">
  </div>
  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Tanggal Hutang</label>
      <input type="date" name="tanggal_hutang" required value="<?= htmlspecialchars($v('tanggal_hutang', date('Y-m-d'))) ?>" class="<?= $input ?>">
    </div>
    <div>
      <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Jatuh Tempo <span class="text-xs">(opsional)</span></label>
      <input type="date" name="tanggal_jatuh_tempo" value="<?= htmlspecialchars($v('tanggal_jatuh_tempo')) ?>" class="<?= $input ?>">
    </div>
  </div>
  <div class="md:col-span-2">
    <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Keterangan <span class="text-xs">(opsional)</span></label>
    <textarea name="keterangan" rows="2" maxlength="2000" class="<?= $input ?>"><?= htmlspecialchars($v('keterangan')) ?></textarea>
  </div>
</div>
