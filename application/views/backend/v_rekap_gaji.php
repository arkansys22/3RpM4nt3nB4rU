<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Gaji</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
                <h1 class="text-2xl font-bold">Rekap Gaji</h1>
                <div class="flex items-center gap-2">
                  <a href="<?= site_url('rekap-gaji/kategori') ?>" class="flex items-center gap-2 bg-gray-500 text-white px-4 py-3 rounded-md hover:bg-gray-600 focus:outline-none text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 3.75v3.75m-16.5-3.75v3.75m16.5 3.75c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    Kelola Kategori Gaji
                  </a>
                  <a href="<?= site_url('panel') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                  </a>
                </div>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                  <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 text-sm">
                    <?= $this->session->flashdata('error') ?>
                  </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                  <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 text-sm">
                    <?= $this->session->flashdata('success') ?>
                  </div>
                <?php endif; ?>

                <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">
                  Total gaji seluruh user yang sudah punya kategori: <span class="font-semibold">Rp <?= number_format($total_gaji, 0, ',', '.') ?></span>
                </p>

                <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                  <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                    <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                      <tr>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Nama</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Role</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Kategori Gaji</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Nominal Gaji</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($rekap_gaji)): ?>
                        <tr class="border-b border-gray-200 dark:border-neutral-600">
                          <td colspan="4" class="px-6 py-4 text-gray-700 dark:text-gray-400 text-center">
                            Belum ada user staff yang terdaftar.
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($rekap_gaji as $r): ?>
                          <tr class="border-b border-gray-200 dark:border-neutral-600">
                            <th scope="row" class="px-6 py-4 text-gray-900 dark:text-gray-200">
                              <?= $r->nama ?>
                            </th>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= ucwords(strtolower($r->user_level_nama)) ?>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <form method="post" action="<?= site_url('rekap-gaji/assign/' . $r->id_session) ?>">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                <select name="kategori_gaji_id" onchange="this.form.submit()" class="border border-stroke dark:border-strokedark rounded-md px-3 py-2 dark:bg-boxdark dark:text-white">
                                  <option value="">- Belum ada kategori -</option>
                                  <?php foreach ($daftar_kategori as $k): ?>
                                    <option value="<?= $k->id ?>" <?= (int) $r->kategori_gaji_id === (int) $k->id ? 'selected' : '' ?>>
                                      <?= $k->nama_kategori ?>
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                              </form>
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                              <?= !empty($r->kategori_gaji_id) ? 'Rp ' . number_format($r->nominal_gaji, 0, ',', '.') : '-' ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
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
</body>
</html>
