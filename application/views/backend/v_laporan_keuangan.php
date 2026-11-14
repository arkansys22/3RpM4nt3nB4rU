<?php
// Halaman Laporan Keuangan. Data dari Crud_laporan_keuangan::index()
$qs = function ($tambah = []) use ($dari, $sampai, $banding, $tab) {
    return http_build_query(array_merge(['dari' => $dari, 'sampai' => $sampai, 'banding' => $banding, 'tab' => $tab], $tambah));
};
$tabs = [
    'ringkasan' => 'Ringkasan',
    'laba-rugi' => 'Laba Rugi',
    'neraca' => 'Neraca',
    'arus-kas' => 'Arus Kas',
    'ekuitas' => 'Perubahan Ekuitas',
    'catatan' => 'Catatan & Kualitas Data',
    'semua' => 'Laporan Lengkap (cetak)',
];
$partial = [
    'ringkasan' => 'backend/laporan/partial_lk_ringkasan',
    'laba-rugi' => 'backend/laporan/partial_lk_laba_rugi',
    'neraca' => 'backend/laporan/partial_lk_neraca',
    'arus-kas' => 'backend/laporan/partial_lk_arus_kas',
    'ekuitas' => 'backend/laporan/partial_lk_ekuitas',
    'catatan' => 'backend/laporan/partial_lk_catatan',
];
$tampil = $tab === 'semua' ? array_keys($partial) : [$tab];
$input = 'rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .print-only { display: none; }
      @media print {
        @page { margin: 12mm; }
        aside, header, .no-print { display: none !important; }
        .print-only { display: block !important; }
        body, main, .bg-white { background: #fff !important; color: #000 !important; }
        .overflow-y-auto, .overflow-x-hidden, .h-screen { overflow: visible !important; height: auto !important; }
        thead, tr.bg-gray-100, tr.bg-gray-50, tr.bg-blue-50, .bg-blue-600, .bg-gray-100, .bg-blue-50, .bg-amber-100 { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        tr, canvas, .rounded-lg { page-break-inside: avoid; break-inside: avoid; }
        .overflow-x-auto { overflow: visible !important; }
        .lk-halaman { page-break-before: always; break-before: page; }
        .lk-halaman:first-of-type { page-break-before: auto; break-before: auto; }
        table { font-size: 11px !important; }
      }
    </style>
</head>
<body
    x-data="{ page: 'dashboard', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
  <!-- ===== Preloader Start ===== -->
  <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black no-print">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent">
    </div>
  </div>
  <!-- ===== Preloader End ===== -->
  <div class="flex h-screen overflow-hidden">
    <?php $this->load->view('backend/sidebar')?>

    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <?php $this->load->view('backend/header')?>

      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">
            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <!-- Kop laporan (hanya saat dicetak) -->
              <div class="print-only mb-4 text-center">
                <p class="text-lg font-bold"><?= lk_h(nama_website()) ?></p>
                <p class="text-xl font-bold">LAPORAN KEUANGAN</p>
                <p>Periode <?= lk_tgl($dari) ?> s/d <?= lk_tgl($sampai) ?></p>
                <p class="text-xs">Dibuat <?= lk_h($dibuat) ?> &middot; disusun dari data Chart Of Account &middot; dalam Rupiah</p>
                <p class="text-xs"><b>Belum diaudit</b> &mdash; laporan manajemen; angka mengikuti kelengkapan input COA (lihat Catatan &amp; Kualitas Data).</p>
              </div>

              <div class="flex flex-wrap justify-between items-center gap-3 mb-4 no-print">
                <div>
                  <h1 class="text-2xl font-bold">Laporan Keuangan</h1>
                  <p class="text-sm text-gray-500">Disusun otomatis dari data Chart Of Account yang sudah diinput.</p>
                </div>
                <div class="flex gap-2">
                  <a href="<?= site_url('laporan-keuangan/export') . '?' . $qs() ?>" class="bg-green-600 text-white px-4 py-2.5 rounded-md hover:bg-green-700 font-semibold">Unduh Excel</a>
                  <button type="button" onclick="window.print()" class="bg-blue-600 text-white px-4 py-2.5 rounded-md hover:bg-blue-700 font-semibold">Cetak / Simpan PDF</button>
                </div>
              </div>

              <!-- Filter periode -->
              <div class="mb-4 p-4 rounded-lg bg-gray-50 dark:bg-neutral-700 no-print">
                <div class="flex flex-wrap gap-2 mb-3">
                  <?php foreach ($preset as $nama => $rentang): $aktif = ($rentang[0] === $dari && $rentang[1] === $sampai); ?>
                    <a href="?<?= lk_h($qs(['dari' => $rentang[0], 'sampai' => $rentang[1]])) ?>"
                       class="px-3 py-1.5 rounded-md text-sm <?= $aktif ? 'bg-blue-600 text-white' : 'bg-white dark:bg-boxdark border border-stroke dark:border-strokedark hover:bg-gray-100' ?>"><?= lk_h($nama) ?></a>
                  <?php endforeach; ?>
                </div>
                <form method="get" class="flex flex-wrap items-end gap-3">
                  <input type="hidden" name="tab" value="<?= lk_h($tab) ?>">
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Dari tanggal</label>
                    <input type="date" name="dari" value="<?= lk_h($dari) ?>" required class="<?= $input ?>">
                  </div>
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Sampai tanggal</label>
                    <input type="date" name="sampai" value="<?= lk_h($sampai) ?>" required class="<?= $input ?>">
                  </div>
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Pembanding</label>
                    <select name="banding" class="<?= $input ?>">
                      <option value="sebelumnya" <?= $banding === 'sebelumnya' ? 'selected' : '' ?>>Periode sebelumnya (sama panjang)</option>
                      <option value="tahun_lalu" <?= $banding === 'tahun_lalu' ? 'selected' : '' ?>>Periode yang sama tahun lalu</option>
                      <option value="none" <?= $banding === 'none' ? 'selected' : '' ?>>Tanpa pembanding</option>
                    </select>
                  </div>
                  <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 font-semibold">Terapkan</button>
                </form>
              </div>

              <!-- Tab -->
              <div class="flex flex-wrap gap-1 border-b border-stroke dark:border-strokedark mb-5 no-print">
                <?php foreach ($tabs as $kunci => $nama): ?>
                  <a href="?<?= lk_h($qs(['tab' => $kunci])) ?>"
                     class="px-4 py-2 text-sm font-medium rounded-t-md <?= $tab === $kunci ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-700' ?>"><?= lk_h($nama) ?></a>
                <?php endforeach; ?>
              </div>

              <!-- Isi -->
              <?php foreach ($tampil as $kunci): ?>
                <section class="lk-halaman <?= $tab === 'semua' ? 'mb-10' : '' ?>">
                  <?php $this->load->view($partial[$kunci], ['d' => $d, 'excel' => false]); ?>
                </section>
              <?php endforeach; ?>

              <p class="text-xs text-gray-400 mt-6 no-print">Dibuat <?= lk_h($dibuat) ?>. Angka bersifat manajemen (belum diaudit) dan mengikuti kelengkapan input COA.</p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Jendela rincian asal angka (diisi lewat fetch dari laporan-keuangan/detail & /transaksi) -->
  <dialog id="lk-dialog" class="no-print rounded-lg p-0 w-[95vw] max-w-5xl bg-white text-gray-800 backdrop:bg-black/50">
    <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-5 py-3 bg-white">
      <span class="text-sm font-semibold text-gray-600">Rincian asal angka</span>
      <button type="button" id="lk-dialog-tutup" class="text-gray-500 hover:text-gray-800 text-xl leading-none" aria-label="Tutup">&times;</button>
    </div>
    <div id="lk-dialog-isi" class="p-5 overflow-y-auto" style="max-height:78vh"></div>
  </dialog>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <?php if (in_array('ringkasan', $tampil, true)): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
      (function () {
        if (typeof Chart === 'undefined') { return; }
        var tren = <?= json_encode($tren, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        var bulanNama = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        var label = Object.keys(tren).map(function (ym) { return bulanNama[parseInt(ym.substr(5, 2), 10) - 1] + ' ' + ym.substr(2, 2); });
        function ambil(k) { return Object.keys(tren).map(function (ym) { return Math.round(tren[ym][k]); }); }
        var juta = function (v) { return (v / 1000000).toLocaleString('id-ID') + ' jt'; };
        var opsi = {
          responsive: true, maintainAspectRatio: false,
          plugins: { legend: { position: 'bottom' }, tooltip: { callbacks: { label: function (c) { return c.dataset.label + ': Rp ' + c.parsed.y.toLocaleString('id-ID'); } } } },
          scales: { y: { ticks: { callback: juta } } }
        };
        new Chart(document.getElementById('lk-chart-laba'), {
          type: 'bar',
          data: { labels: label, datasets: [
            { label: 'Pendapatan', data: ambil('pendapatan'), backgroundColor: '#3b82f6' },
            { label: 'Total Beban', data: ambil('beban'), backgroundColor: '#f59e0b' },
            { label: 'Laba Bersih', data: ambil('laba'), type: 'line', borderColor: '#16a34a', backgroundColor: '#16a34a', tension: 0 }
          ] },
          options: opsi
        });
        new Chart(document.getElementById('lk-chart-kas'), {
          type: 'bar',
          data: { labels: label, datasets: [
            { label: 'Uang Masuk', data: ambil('kas_masuk'), backgroundColor: '#16a34a' },
            { label: 'Uang Keluar', data: ambil('kas_keluar'), backgroundColor: '#dc2626' }
          ] },
          options: opsi
        });
      })();
    </script>
  <?php endif; ?>
  <script>
    // ===== Lihat detail: asal angka di kartu Ringkasan =====
    (function () {
      var dlg = document.getElementById('lk-dialog');
      var isi = document.getElementById('lk-dialog-isi');
      if (!dlg || !isi) { return; }
      var URL_DETAIL = <?= json_encode(site_url('laporan-keuangan/detail') . '/', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      var URL_TRX = <?= json_encode(site_url('laporan-keuangan/transaksi'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      var QS = <?= json_encode(http_build_query(['dari' => $dari, 'sampai' => $sampai, 'banding' => $banding]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

      // ambil potongan HTML dari server; hasilnya harus berpenanda data-lk-fragmen (kalau tidak, mis. sesi habis -> halaman login)
      function ambil(url, tujuan, pesanGagal) {
        return fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(function (r) { return r.text().then(function (t) { return { ok: r.ok, teks: t }; }); })
          .then(function (h) {
            if (h.ok && h.teks.indexOf('data-lk-fragmen') !== -1) { tujuan.innerHTML = h.teks; return true; }
            tujuan.innerHTML = '<p class="text-sm text-red-600">' + pesanGagal + ' Klik lagi untuk mencoba ulang.</p>';
            return false;
          })
          .catch(function () { tujuan.innerHTML = '<p class="text-sm text-red-600">' + pesanGagal + ' Klik lagi untuk mencoba ulang.</p>'; return false; });
      }

      document.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('[data-lk-detail]') : null;
        if (b) {
          isi.innerHTML = '<p class="text-sm text-gray-500">Memuat rincian...</p>';
          if (!dlg.open) { dlg.showModal(); }
          ambil(URL_DETAIL + encodeURIComponent(b.getAttribute('data-lk-detail')) + '?' + QS, isi, 'Gagal memuat rincian. Muat ulang halaman (sesi mungkin berakhir).');
          return;
        }
        var t = e.target.closest ? e.target.closest('[data-lk-trx]') : null;
        if (t) {
          var baris = t.closest('tr').nextElementSibling;
          if (!baris || !baris.classList.contains('lk-trx-baris')) { return; }
          var kotak = baris.querySelector('.lk-trx-isi');
          if (!baris.classList.contains('hidden')) { baris.classList.add('hidden'); return; } // klik lagi = tutup
          baris.classList.remove('hidden');
          if (!kotak.getAttribute('data-dimuat')) {
            ambil(URL_TRX + '?' + t.getAttribute('data-lk-trx'), kotak, 'Gagal memuat transaksi.').then(function (berhasil) { if (berhasil) { kotak.setAttribute('data-dimuat', '1'); } });
          }
        }
      });
      document.getElementById('lk-dialog-tutup').addEventListener('click', function () { dlg.close(); });
      dlg.addEventListener('click', function (e) { if (e.target === dlg) { dlg.close(); } }); // klik latar gelap = tutup
    })();

    // Cetak selalu terang (tanpa mode gelap) lalu kembalikan
    var _lkGelap = false;
    window.addEventListener('beforeprint', function () { _lkGelap = document.body.classList.contains('dark'); document.body.classList.remove('dark'); });
    window.addEventListener('afterprint', function () { if (_lkGelap) { document.body.classList.add('dark'); } });
  </script>
</body>
</html>
