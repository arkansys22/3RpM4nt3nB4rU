<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

                <!-- Checkbox -->
                <label>
                    <input type="checkbox" id="show_data"> Tampilkan Data Serupa
                </label>
                <!-- Select tambahan -->
                <div id="additional_input">
                    <label>Pilih Account (Prefix Sama)</label>
                    <select id="account_select" class="w-full px-4 py-2 border rounded mb-4">
                        <option value="">-- Pilih Account --</option>
                    </select>
                </div>
                
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

       

            // mapping account type
            let mapping = {
                'Cash/Bank': '1000',
                'Account Receivable': '1100',
                'Inventory': '1200',
                'Other Current Asset': '1300',
                'Fixed Asset': '1700',
                'Accumulated Depreciation': '1710',
                'Account Payable': '2000',
                'Other Current Liability': '2100',
                'Long Term Liability': '0',
                'Equity': '3000',
                'Revenue': '4000',
                'Cost Of Goods Sold': '5000',
                'Expense': '6000',
                'Other Income': '7100',
                'Other Expense': '7200'
            };

            // saat pilih account type
            $('#account_type').change(function(){
                let type = $(this).val();
                let code = mapping[type] || '';

                $('#account_code').val(code);

                // reset
                $('#account_select').html('<option value="">-- Pilih Account --</option>');
                $('#additional_input').hide();
                $('#show_data').prop('checked', false);
            });


            // saat checkbox dicentang
            $('#show_data').change(function(){

                if($(this).is(':checked')){
                    let prefix = $('#account_code').val();

                    if(prefix === ''){
                        alert('Pilih account type dulu!');
                        $(this).prop('checked', false);
                        return;
                    }

                    let prefix = code.substring(0,2);
                    $('#additional_input').show();

                    // AJAX ambil data dari server
                    $.ajax({
                        url: "<?= base_url('Crud_coa/get_account_by_prefix'); ?>",
                        type: "POST",
                        data: {prefix: prefix},
                        dataType: "json",
                        success: function(res){
                          let option = '<option value="">-- Pilih Account --</option>';

                          $.each(res, function(i, item){
                          option += '<option value="'+item.account_code+'">'+item.account_code+'</option>';
                          });
                          $('#account_select').html(option);
                        }
                    });

                } else {
                    $('#additional_input').hide();
                    $('#account_select').html('<option value="">-- Pilih Account --</option>');
                }

            });

        });

    
  </script>
</body>
</html>
