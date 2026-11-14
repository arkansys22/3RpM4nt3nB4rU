<?php
// Ekspor Excel (.xls berisi tabel HTML): daftar stok + riwayat pergerakan pada periode
$jenis = function ($t) {
    if ($t->status === 'Dibatalkan') return 'Dibatalkan';
    if (strpos((string) $t->status, 'Penyesuaian') === 0) return 'Koreksi';
    return (int) $t->goods_in > 0 ? 'Barang Masuk' : 'Barang Keluar';
};
lk_mode_excel(true); // teks yang diawali = + - @ diberi tanda petik (cegah formula injection)
?>
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
  table { border-collapse: collapse; margin-bottom: 14px; }
  td, th { border: 1px solid #999; padding: 3px 6px; vertical-align: top; }
  th { background: #2563eb; color: #fff; }
  .r { text-align: right; }
  h2 { font-size: 14pt; margin: 6px 0 2px 0; } p { margin: 2px 0; }
</style>
</head>
<body>
  <h2><?= lk_h(nama_website()) ?> &mdash; STOCK BARANG</h2>
  <p>Periode pergerakan <?= lk_tgl($dari) ?> s/d <?= lk_tgl($sampai) ?> &middot; Dibuat <?= lk_h($dibuat) ?></p>

  <h2>Daftar Stok Saat Ini</h2>
  <table border="1">
    <thead><tr>
      <th>Barang</th><th>Kategori</th><th>Satuan</th><th>Total Masuk</th><th>Total Keluar</th><th>Koreksi</th><th>Stok</th><th>Stok Minimum</th><th>Status</th>
      <th>Masuk (periode)</th><th>Keluar (periode)</th><th>Terakhir Bergerak</th>
    </tr></thead>
    <tbody>
      <?php foreach ($daftar as $b): ?>
        <tr>
          <td><?= lk_h(trim($b->product_name)) ?></td><td><?= lk_h($b->kategori ?: 'Belum dikategorikan') ?></td><td><?= lk_h($b->satuan) ?></td>
          <td class="r"><?= (int) $b->total_masuk ?></td><td class="r"><?= (int) $b->total_keluar ?></td><td class="r"><?= (int) $b->total_penyesuaian ?></td>
          <td class="r"><b><?= (int) $b->stok ?></b></td><td class="r"><?= (int) $b->stok_minimum ?></td><td><?= lk_h(ucfirst($b->status_stok)) ?></td>
          <td class="r"><?= (int) $b->masuk_periode ?></td><td class="r"><?= (int) $b->keluar_periode ?></td><td><?= $b->terakhir ? lk_tgl($b->terakhir) : '' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2>Riwayat Pergerakan (<?= lk_tgl($dari) ?> s/d <?= lk_tgl($sampai) ?>)</h2>
  <table border="1">
    <thead><tr><th>Tanggal</th><th>Barang</th><th>Kategori</th><th>Jenis</th><th>Masuk</th><th>Keluar</th><th>Koreksi (+/-)</th><th>Saldo Setelahnya</th><th>Event / Project</th><th>Keterangan</th><th>Dicatat Oleh</th></tr></thead>
    <tbody>
      <?php if (empty($riwayat)): ?><tr><td colspan="11">Tidak ada pergerakan pada periode ini.</td></tr><?php endif; ?>
      <?php foreach ($riwayat as $t): $masuk = (int) $t->goods_in > 0; $batal = $t->status === 'Dibatalkan'; $koreksi = strpos((string) $t->status, 'Penyesuaian') === 0; $biasa = !$batal && !$koreksi; ?>
        <tr>
          <td><?= lk_h($t->tanggal) ?></td><td><?= lk_h(trim($t->product_name)) ?></td><td><?= lk_h($t->kategori ?: '') ?></td><td><?= $jenis($t) ?><?= $batal ? ' (' . ($masuk ? 'masuk ' . (int) $t->goods_in : 'keluar ' . abs((int) $t->goods_out)) . ')' : '' ?></td>
          <td class="r"><?= $biasa && $masuk ? (int) $t->goods_in : '' ?></td><td class="r"><?= $biasa && !$masuk ? abs((int) $t->goods_out) : '' ?></td><td class="r"><?= $koreksi ? (int) $t->goods_in + (int) $t->goods_out : '' ?></td>
          <td class="r"><?= $t->status === 'Dibatalkan' ? '' : (int) $t->amount ?></td>
          <td><?= lk_h($nama_project[$t->project_id_session] ?? '') ?></td><td><?= lk_h($t->detail) ?></td><td><?= lk_h($nama_user[$t->created_by] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
