<?php
// Ekspor Excel (.xls berisi tabel HTML). Variabel: $d (data laporan), seperti halaman utama.
$partial = [
    'backend/laporan/partial_lk_ringkasan',
    'backend/laporan/partial_lk_laba_rugi',
    'backend/laporan/partial_lk_neraca',
    'backend/laporan/partial_lk_arus_kas',
    'backend/laporan/partial_lk_ekuitas',
    'backend/laporan/partial_lk_catatan',
];
lk_mode_excel(true); // angka sebagai angka, bukan teks berformat
?>
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
  table { border-collapse: collapse; margin-bottom: 14px; }
  td, th { border: 1px solid #999; padding: 3px 6px; vertical-align: top; }
  th { background: #2563eb; color: #fff; }
  .text-right { text-align: right; }
  .text-left { text-align: left; }
  .text-red-600 { color: #dc2626; }
  .text-gray-400, .text-gray-500 { color: #6b7280; }
  .text-green-600, .text-green-700 { color: #15803d; }
  .text-amber-600, .text-amber-700 { color: #b45309; }
  .font-bold, .font-semibold { font-weight: bold; }
  .bg-blue-50 { background: #eff6ff; }
  .bg-gray-50 { background: #f9fafb; }
  .bg-gray-100 { background: #f3f4f6; }
  .pl-4 { padding-left: 16px; }
  .pl-6 { padding-left: 28px; }
  .pl-8 { padding-left: 42px; }
  h2 { font-size: 14pt; margin: 16px 0 2px 0; }
  h3 { font-size: 12pt; margin: 12px 0 2px 0; }
  p { margin: 2px 0; }
</style>
</head>
<body>
  <h2><?= lk_h(nama_website()) ?> &mdash; LAPORAN KEUANGAN</h2>
  <p>Periode <?= lk_tgl($d['dari']) ?> s/d <?= lk_tgl($d['sampai']) ?> &middot; Dibuat <?= lk_h($d['dibuat']) ?> &middot; disusun dari data Chart Of Account &middot; dalam Rupiah &middot; <b>Belum diaudit</b> (laporan manajemen)</p>
  <?php foreach ($partial as $p): ?>
    <br>
    <?php $this->load->view($p, ['d' => $d, 'excel' => true]); ?>
  <?php endforeach; ?>
</body>
</html>
