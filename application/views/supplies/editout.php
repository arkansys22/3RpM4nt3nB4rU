<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Keluar</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'supplies', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Stock Keluar</h1>
              <form action="<?= base_url('supplies/store3') ?>" method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded">
    <input type="hidden" name="id_session" value="<?= $supplies->id_session ?>">

    <label class="block mb-2">Nama Produk</label>
    <input type="text" name="product_name" value="<?= $supplies->product_name ?>" 
    class="w-full px-4 py-2 border rounded mb-4 bg-gray-100 cursor-not-allowed" readonly>

    <label class="block mb-2">Barang Keluar</label>
    <input type="text" name="goods_out" class="w-full px-4 py-2 border rounded mb-4" required>

    <label class="block mb-2">Detail</label>
    <input type="text" name="detail" class="w-full px-4 py-2 border rounded mb-4" required>

    <!-- Input hidden untuk menyimpan value lama -->
    <input type="hidden" name="old_goods_out" value="<?= $supplies->goods_out ?>">
    <input type="hidden" name="old_detail" value="<?= $supplies->detail ?>">

    <div class="flex flex-col sm:flex-row justify-end">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Ambil Stock</button>
        <a href="<?= site_url('supplies') ?>" 
          class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center flex items-center justify-center">
          Batal
        </a>
    </div>
</form>
            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js">
  </script>
</body>
</html>
