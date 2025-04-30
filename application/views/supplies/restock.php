<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restock Supplies</title>
  <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
  <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
  x-data="{ page: 'restock', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
            <h1 class="text-2xl font-bold mb-4">Restock Supplies</h1>
              <a href="<?= site_url('supplies') ?>" class="flex items-center gap-2 bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                  </svg>
                </a>
            </div>
              <div class="overflow-x-auto bg-white dark:bg-neutral-700 rounded-lg shadow-md">
                <table class="min-w-full text-left text-sm whitespace-nowrap bg-white dark:bg-neutral-800">
                  <thead class="uppercase tracking-wider border-b-2 border-gray-200 dark:border-neutral-600 bg-gray-100 dark:bg-neutral-700">
                    <tr>
                      <th class="px-6 py-4 text-gray-700 dark:text-gray-300">Nama Stock</th>
                      <th class="px-6 py-4 text-gray-700 dark:text-gray-300">Jenis Barang</th>
                      <th class="px-6 py-4 text-gray-700 dark:text-gray-300">Jumlah Stock</th>
                      <th class="px-6 py-4 text-gray-700 dark:text-gray-300">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($low_stocks as $stock): ?>
                      <tr class="border-b border-gray-200 dark:border-neutral-600">
                        <td class="px-6 py-4 text-gray-900 dark:text-gray-200"><?= $stock->product_name ?></td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-400"><?= $stock->type ?></td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-400"><?= $stock->amount ?></td>
                        <td class="px-6 py-4">
                          <?php if ($stock->amount == 0): ?>
                            <span class="text-red-600 font-bold">Stock Habis, Restock Hari Ini!!!</span>
                          <?php elseif ($stock->amount < 10): ?>
                            <span class="text-red-500 font-bold">Segera Restock</span>
                          <?php else: ?>
                            <span class="text-orange-500">Stock Menipis</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
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
