<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'crews', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h2 class="text-2xl font-bold mb-4">Edit Account <?= $coa->nomer_kategori ?></h2>
              <form action="<?= site_url('coa/update/'.$coa->nomer_kategori) ?>" method="post" class="bg-white p-6 shadow-md rounded">             

                <label class="block mb-2">Account Type</label>
                <select name="account_type" id="account_type" class="w-full px-4 py-2 border rounded mb-4">
                    <option value="Cash/Bank" <?= ($coa->detail_kategori == 'Cash/Bank') ? 'selected' : '' ?>>Cash/Bank</option>
                    <option value="Account Receivable" <?= ($coa->detail_kategori == 'Account Receivable') ? 'selected' : '' ?>>Account Receivable</option>
                    <option value="Inventory" <?= ($coa->detail_kategori == 'Inventory') ? 'selected' : '' ?>>Inventory</option>
                    <option value="Other Current Asset" <?= ($coa->detail_kategori == 'Other Current Asset') ? 'selected' : '' ?>>Other Current Asset</option>
                    <option value="Fixed Asset" <?= ($coa->detail_kategori == 'Fixed Asset') ? 'selected' : '' ?>>Fixed Asset</option>
                    <option value="Accumulated Depreciation" <?= ($coa->detail_kategori == 'Accumulated Depreciation') ? 'selected' : '' ?>>Accumulated Depreciation</option>
                    <option value="Other Asset" <?= ($coa->detail_kategori == 'Other Asset') ? 'selected' : '' ?>>Other Asset</option>
                    <option value="Account Payable" <?= ($coa->detail_kategori == 'Account Payable') ? 'selected' : '' ?>>Account Payable</option>
                    <option value="Other Current Liability" <?= ($coa->detail_kategori == 'Other Current Liability') ? 'selected' : '' ?>>Other Current Liability</option>
                    <option value="Long Term Liability" <?= ($coa->detail_kategori == 'Long Term Liability') ? 'selected' : '' ?>>Long Term Liability</option>
                    <option value="Equity" <?= ($coa->detail_kategori == 'Equity') ? 'selected' : '' ?>>Equity</option>
                    <option value="Revenue" <?= ($coa->detail_kategori == 'Revenue') ? 'selected' : '' ?>>Revenue</option>
                    <option value="Cost Of Goods Sold" <?= ($coa->detail_kategori == 'Cost Of Goods Sold') ? 'selected' : '' ?>>Cost Of Goods Sold</option>
                    <option value="Expense" <?= ($coa->detail_kategori == 'Expense') ? 'selected' : '' ?>>Expense</option>
                    <option value="Other Expense" <?= ($coa->detail_kategori == 'Other Expense') ? 'selected' : '' ?>>Other Expense</option>
                    <option value="Other Income" <?= ($coa->detail_kategori == 'Other Income') ? 'selected' : '' ?>>Other Income</option>
                    <option value="Other Current" <?= ($coa->detail_kategori == 'Other Current') ? 'selected' : '' ?>>Other Current</option>
                </select>

                <label class="block mb-2">Account No.</label>
                <input type="text" name="account_code" value="<?= $coa->nomer_kategori ?>" class="w-full px-4 py-2 border rounded mb-4" >

                <label class="block mb-2">Name</label>
                <input type="text" name="name" value="<?= $coa->nama_kategori ?>" class="w-full px-4 py-2 border rounded mb-4" >                

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('coa') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
