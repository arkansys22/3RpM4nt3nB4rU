<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Account</title>
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
              <h2 class="text-2xl font-bold mb-4">Tambah Account</h2>
              <form action="<?= site_url('crews/store') ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Account Type</label>
                <select name="account_type" id="account_type" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="">-- Pilih --</option>  
                    <option value="Cash/Bank">Cash/Bank</option>
                    <option value="Account Receivable">Account Receivable</option>
                    <option value="Inventory">Inventory</option>
                    <option value="Other Current Asset">Other Current Asset</option>
                    <option value="Fixed Asset">Fixed Asset</option>
                    <option value="Accumulated Depreciation">Accumulated Depreciation</option>
                    <option value="Other Asset">Other Asset</option>
                    <option value="Account Payable">Account Payable</option>
                    <option value="Other Current Liability">Other Current Liability</option>
                    <option value="Long Term Liability">Long Term Liability</option>
                    <option value="Equity">Equity</option>
                    <option value="Revenue">Revenue</option>
                    <option value="Cost Of Goods Sold">Cost Of Goods Sold</option>
                    <option value="Expense">Expense</option>
                    <option value="Other Expense">Other Expense</option>
                    <option value="Other Income">Other Income</option>
                </select>
                
                <label class="block mb-2">Account No.</label>
                <input type="text" id="account_code" name="phone" class="w-full px-4 py-2 border rounded mb-4"  placeholder="Akan otomatis terisi">

                <label class="block mb-2">Name</label>
                <input type="text" name="name" class="w-full px-4 py-2 border rounded mb-4" required>



                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                <a href="<?= site_url('crews') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
  <script>
    $(document).ready(function(){

        $('#account_type').change(function(){

            let type = $(this).val();

            if(type === 'Cash/Bank'){
                $('#account_code').val('1000');
            } else if(type === 'Account Receivable'){
                $('#account_code').val('1100');
            } else if(type === 'Inventory'){
                $('#account_code').val('1200');
            } else if(type === 'Other Current Asset'){
                $('#account_code').val('1300');
            } else if(type === 'Fixed Asset'){
                $('#account_code').val('1700');
            } else if(type === 'Accumulated Depreciation'){
                $('#account_code').val('1710');
            } else if(type === 'Account Payable'){
                $('#account_code').val('2000');
            } else if(type === 'Other Current Liability'){
                $('#account_code').val('2100');
            } else if(type === 'Long Term Liability'){
                $('#account_code').val('0');
            } else if(type === 'Equity'){
                $('#account_code').val('3000');
            } else if(type === 'Revenue'){
                $('#account_code').val('4000'); 
            } else if(type === 'Cost Of Goods Sold'){
                $('#account_code').val('5000');
            } else if(type === 'Expense'){
                $('#account_code').val('6000');
            } else if(type === 'Other Income'){
                $('#account_code').val('7100'); 
            } else if(type === 'Other Expense'){
                $('#account_code').val('7200'); 
            } else {
                $('#account_code').val('');
            }

        });

    });
  </script>
</body>
</html>
