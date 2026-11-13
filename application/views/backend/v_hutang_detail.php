<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Hutang</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'dashboard', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
  <!-- ===== Preloader Start ===== -->
  <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent">
    </div>
  </div>
  <!-- ===== Preloader End ===== -->
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <?php $this->load->view('backend/sidebar')?>

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <?php $this->load->view('backend/header')?>

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">
            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">

              <?php
                $rp = function ($n) { return 'Rp ' . number_format((float) $n, 0, ',', '.'); };
                $tgl = function ($d) { return $d ? date('d-m-Y', strtotime($d)) : '-'; };
                $badge = ['Lunas' => 'bg-green-100 text-green-700', 'Terlambat' => 'bg-red-100 text-red-700', 'Belum Lunas' => 'bg-yellow-100 text-yellow-700'][$hutang->status];
                $csrf_nama = $this->security->get_csrf_token_name();
                $csrf_hash = $this->security->get_csrf_hash();
                $input = 'w-full rounded-md border border-stroke dark:border-strokedark px-3 py-2 dark:bg-boxdark dark:text-white';
                $old = $this->session->flashdata('old'); // isian edit yang gagal divalidasi
              ?>

              <div class="flex justify-between items-center mb-4 gap-3">
                <h1 class="text-2xl font-bold">
                  <?= htmlspecialchars($hutang->nama_hutang) ?>
                  <span class="ml-2 px-2 py-1 rounded text-xs font-semibold align-middle <?= $badge ?>"><?= $hutang->status ?></span>
                </h1>
                <a href="<?= site_url('hutang') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none" title="Kembali">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                  </svg>
                </a>
              </div>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= htmlspecialchars($this->session->flashdata('error')) ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= htmlspecialchars($this->session->flashdata('success')) ?></div>
              <?php endif; ?>

              <!-- Ringkasan -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Pokok Hutang</p>
                  <p class="text-xl font-bold"><?= $rp($hutang->nominal_pokok) ?></p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Sudah Dibayar</p>
                  <p class="text-xl font-bold text-green-600"><?= $rp($hutang->total_dibayar) ?></p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Sisa Hutang</p>
                  <p class="text-xl font-bold text-red-600"><?= $rp($hutang->sisa) ?></p>
                </div>
              </div>

              <div class="mb-6 rounded-lg bg-gray-50 dark:bg-neutral-700 p-4 text-sm text-gray-700 dark:text-gray-200 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-1">
                <div><b>Kreditur:</b> <?= htmlspecialchars($hutang->kreditur) ?></div>
                <div><b>Jenis:</b> <?= htmlspecialchars($hutang->jenis) ?> &middot; Jangka <?= htmlspecialchars($hutang->jangka) ?></div>
                <div><b>Tanggal Hutang:</b> <?= $tgl($hutang->tanggal_hutang) ?></div>
                <div><b>Jatuh Tempo:</b> <?= $tgl($hutang->tanggal_jatuh_tempo) ?></div>
                <div class="md:col-span-2">
                  <b>Akun COA:</b>
                  <a href="<?= site_url('coa/detail/' . $hutang->kategori) ?>" class="text-blue-600 hover:underline"><?= htmlspecialchars($hutang->kategori . ($nama_akun ? ' - ' . $nama_akun : '')) ?></a>
                  <span class="text-xs text-gray-500 dark:text-gray-400">(hutang menambah saldo akun, pembayaran menguranginya)</span>
                </div>
                <?php if (trim((string) $hutang->keterangan) !== ''): ?>
                  <div class="md:col-span-2"><b>Keterangan:</b> <?= nl2br(htmlspecialchars($hutang->keterangan)) ?></div>
                <?php endif; ?>
              </div>

              <!-- Bayar -->
              <?php if ($hutang->sisa > 0): ?>
                <div class="mb-6 p-5 rounded-lg bg-gray-50 dark:bg-neutral-700">
                  <h2 class="font-semibold text-gray-800 dark:text-white mb-3">Catat Pembayaran</h2>
                  <form method="post" action="<?= site_url('hutang/bayar/' . $hutang->id_session) ?>">
                    <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div>
                        <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Nominal Bayar <span class="text-xs">(maks. <?= $rp($hutang->sisa) ?>)</span></label>
                        <input type="text" inputmode="numeric" name="nominal_bayar" required placeholder="Contoh: 500.000" class="<?= $input ?> js-rupiah">
                      </div>
                      <div>
                        <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" required value="<?= date('Y-m-d') ?>" class="<?= $input ?>">
                      </div>
                      <div>
                        <label class="mb-2 block text-sm text-gray-700 dark:text-gray-200">Keterangan <span class="text-xs">(opsional)</span></label>
                        <input type="text" name="keterangan" maxlength="255" placeholder="Contoh: Cicilan ke-1" class="<?= $input ?>">
                      </div>
                    </div>
                    <button type="submit" class="mt-4 bg-green-500 text-white px-5 py-2.5 rounded-md hover:bg-green-600 font-semibold">Simpan Pembayaran</button>
                  </form>
                </div>
              <?php endif; ?>

              <!-- Riwayat pembayaran -->
              <h2 class="font-semibold text-gray-800 dark:text-white mb-3">Riwayat Pembayaran</h2>
              <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md mb-6">
                <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                  <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                    <tr>
                      <th class="px-4 py-3 text-gray-700 dark:text-gray-300">Tanggal</th>
                      <th class="px-4 py-3 text-gray-700 dark:text-gray-300 text-right">Nominal</th>
                      <th class="px-4 py-3 text-gray-700 dark:text-gray-300">Keterangan</th>
                      <th class="px-4 py-3 text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($pembayaran)): ?>
                      <tr><td colspan="4" class="px-4 py-5 text-center text-gray-700 dark:text-gray-400">Belum ada pembayaran.</td></tr>
                    <?php else: foreach ($pembayaran as $p): ?>
                      <tr class="border-b border-gray-200 dark:border-neutral-600">
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300"><?= $tgl($p->tanggal_bayar) ?></td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-200 text-right font-semibold"><?= $rp($p->nominal_bayar) ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400"><?= htmlspecialchars((string) $p->keterangan) ?: '-' ?></td>
                        <td class="px-4 py-3">
                          <form method="post" action="<?= site_url('hutang/bayar_hapus/' . $p->id_session) ?>" onsubmit="return confirm('Hapus pembayaran ini? Sisa hutang dan saldo COA akan dikembalikan.')">
                            <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600">Hapus</button>
                          </form>
                        </td>
                      </tr>
                    <?php endforeach; endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- Edit & hapus -->
              <details <?= $old ? 'open' : '' ?> class="mb-4 rounded-lg bg-gray-50 dark:bg-neutral-700 p-5">
                <summary class="cursor-pointer font-semibold text-gray-800 dark:text-white">Edit Data Hutang</summary>
                <form method="post" action="<?= site_url('hutang/update/' . $hutang->id_session) ?>" class="mt-4">
                  <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                  <?php $this->load->view('backend/partial_hutang_form', ['h' => $old ? (object) array_merge((array) $hutang, $old) : $hutang, 'jenis_list' => $jenis_list]); ?>
                  <button type="submit" class="mt-4 bg-blue-500 text-white px-5 py-2.5 rounded-md hover:bg-blue-600 font-semibold">Simpan Perubahan</button>
                </form>
              </details>

              <form method="post" action="<?= site_url('hutang/delete/' . $hutang->id_session) ?>" onsubmit="return confirm('Hapus hutang &quot;<?= htmlspecialchars(addslashes($hutang->nama_hutang), ENT_QUOTES) ?>&quot; beserta SEMUA pembayarannya? Data juga dihapus dari akun COA dan tidak bisa dikembalikan.')">
                <input type="hidden" name="<?= $csrf_nama ?>" value="<?= $csrf_hash ?>">
                <button type="submit" class="bg-red-500 text-white px-5 py-2.5 rounded-md hover:bg-red-600 font-semibold">Hapus Hutang</button>
              </form>

            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <?php $this->load->view('backend/partial_hutang_script'); ?>
</body>
</html>
