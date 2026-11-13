<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hutang</title>
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

              <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Hutang</h1>
                <button type="button" id="btn-toggle-form-hutang" onclick="toggleFormHutang()" class="bg-green-500 text-white px-5 py-2.5 rounded-md hover:bg-green-600 font-semibold <?= $old ? 'hidden' : '' ?>">
                  + Tambah Hutang
                </button>
              </div>

              <?php if ($this->session->flashdata('error')): ?>
                <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm"><?= htmlspecialchars($this->session->flashdata('error')) ?></div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('success')): ?>
                <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm"><?= htmlspecialchars($this->session->flashdata('success')) ?></div>
              <?php endif; ?>

              <?php
                $rp = function ($n) { return 'Rp ' . number_format((float) $n, 0, ',', '.'); };
              ?>

              <!-- Ringkasan -->
              <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Total Sisa Hutang</p>
                  <p class="text-xl font-bold text-red-600"><?= $rp($ringkas['sisa_total']) ?></p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= $ringkas['aktif'] ?> hutang belum lunas &middot; sudah dibayar <?= $rp($ringkas['dibayar_total']) ?></p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Jangka Pendek <span class="text-xs">(COA 2000 Utang)</span></p>
                  <p class="text-xl font-bold"><?= $rp($ringkas['sisa_pendek']) ?></p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Jangka Panjang <span class="text-xs">(COA 2300 Hutang Jangka Panjang)</span></p>
                  <p class="text-xl font-bold"><?= $rp($ringkas['sisa_panjang']) ?></p>
                </div>
                <div class="rounded-lg border border-stroke dark:border-strokedark p-4">
                  <p class="text-sm text-gray-500 dark:text-gray-400">Perlu Perhatian</p>
                  <p class="text-xl font-bold"><span class="text-red-600"><?= $ringkas['terlambat'] ?></span> terlambat</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= $ringkas['segera'] ?> jatuh tempo dalam 7 hari</p>
                </div>
              </div>

              <!-- Form tambah -->
              <div id="form-hutang-wrapper" class="<?= $old ? '' : 'hidden' ?> mb-6 p-5 rounded-lg bg-gray-50 dark:bg-neutral-700">
                <h2 class="font-semibold text-gray-800 dark:text-white mb-1">Tambah Hutang Baru</h2>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                  Untuk pinjaman/utang umum perusahaan. Utang ke vendor per event tetap dicatat di Finance &rarr; Project (Utang).
                </p>
                <form method="post" action="<?= site_url('hutang/store') ?>">
                  <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                  <?php $this->load->view('backend/partial_hutang_form', ['h' => $old ? (object) $old : null, 'jenis_list' => $jenis_list]); ?>
                  <div class="flex gap-3 mt-4">
                    <button type="submit" class="bg-green-500 text-white px-5 py-2.5 rounded-md hover:bg-green-600 font-semibold">Simpan</button>
                    <button type="button" onclick="toggleFormHutang()" class="bg-gray-400 text-white px-5 py-2.5 rounded-md hover:bg-gray-500 font-semibold">Batal</button>
                  </div>
                </form>
              </div>

              <!-- Tabel -->
              <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                  <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                    <tr>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Hutang</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Jenis</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Akun COA</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300 text-right">Pokok</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300 text-right">Dibayar</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300 text-right">Sisa</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Jatuh Tempo</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Status</th>
                      <th class="px-4 py-4 text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($daftar)): ?>
                      <tr class="border-b border-gray-200 dark:border-neutral-600">
                        <td colspan="9" class="px-4 py-6 text-gray-700 dark:text-gray-400 text-center">Belum ada data hutang.</td>
                      </tr>
                    <?php else: foreach ($daftar as $h): ?>
                      <?php
                        $badge = ['Lunas' => 'bg-green-100 text-green-700', 'Terlambat' => 'bg-red-100 text-red-700', 'Belum Lunas' => 'bg-yellow-100 text-yellow-700'][$h->status];
                      ?>
                      <tr class="border-b border-gray-200 dark:border-neutral-600">
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-200">
                          <a href="<?= site_url('hutang/lihat/' . $h->id_session) ?>" class="font-semibold text-blue-600 hover:underline"><?= htmlspecialchars($h->nama_hutang) ?></a>
                          <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($h->kreditur) ?></div>
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400"><?= htmlspecialchars($h->jenis) ?><div class="text-xs"><?= htmlspecialchars($h->jangka) ?></div></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400"><?= htmlspecialchars($h->kategori) ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400 text-right"><?= $rp($h->nominal_pokok) ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400 text-right"><?= $rp($h->total_dibayar) ?></td>
                        <td class="px-4 py-3 text-gray-900 dark:text-gray-200 text-right font-semibold"><?= $rp($h->sisa) ?></td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-400"><?= $h->tanggal_jatuh_tempo ? date('d-m-Y', strtotime($h->tanggal_jatuh_tempo)) : '-' ?></td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded text-xs font-semibold <?= $badge ?>"><?= $h->status ?></span></td>
                        <td class="px-4 py-3">
                          <a href="<?= site_url('hutang/lihat/' . $h->id_session) ?>" class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600">Detail</a>
                        </td>
                      </tr>
                    <?php endforeach; endif; ?>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    function toggleFormHutang() {
      document.getElementById('form-hutang-wrapper').classList.toggle('hidden');
      document.getElementById('btn-toggle-form-hutang').classList.toggle('hidden');
    }
  </script>
  <?php $this->load->view('backend/partial_hutang_script'); ?>
</body>
</html>
