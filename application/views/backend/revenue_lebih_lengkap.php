<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lebih Lengkap - Revenue</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'revenue', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
                <h1 class="text-2xl font-bold">Detail Gross & Net Revenue</h1>
                <!-- Tombol Kembali -->
                <a href="<?= site_url('panel') ?>" class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">Kembali</a>
                </div>
              <?php foreach ($revenue_per_year as $year => $months): ?>
                <h2 class="text-xl font-semibold mb-3 flex justify-between">
                  <span class="text-gray-900 dark:text-gray-200"><?= $year ?></span>
                  <span class="text-gray-500 dark:text-gray-400">Total Revenue: Rp <?= number_format(array_sum(array_column($months, 'total_bill')), 0, ',', '.') ?></span>
                  <span class="text-gray-500 dark:text-gray-400">Uang Masuk: Rp <?= number_format(array_sum(array_column($months, 'total_paid')), 0, ',', '.') ?></span>
                </h2>
                <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                  <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                    <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                      <tr>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Bulan</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Total Revenue</th>
                        <th scope="col" class="px-6 py-4 text-gray-700 dark:text-gray-300">Uang Masuk</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($month = 1; $month <= 12; $month++): ?>
                        <tr class="border-b border-gray-200 dark:border-neutral-600">
                          <th scope="row" class="px-6 py-4 text-gray-900 dark:text-gray-200">
                            <a href="<?= base_url("revenue/lebih_lengkap/detail/$year/$month") ?>" class="text-blue-500 hover:underline">
                              <?= date('F', mktime(0, 0, 0, $month, 1)) ?>
                            </a>
                          </th>
                          <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                            Rp <?= isset($months[$month]) ? number_format($months[$month]['total_bill'], 0, ',', '.') : '0' ?>
                          </td>
                          <td class="px-6 py-4 text-gray-700 dark:text-gray-400">
                            Rp <?= isset($months[$month]) ? number_format($months[$month]['total_paid'], 0, ',', '.') : '0' ?>
                          </td>
                        </tr>
                      <?php endfor; ?>
                    </tbody>
                  </table>
                </div>
                <br>
              <?php endforeach; ?>
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
