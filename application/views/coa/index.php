<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Chart Of Account</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      table {
        border-collapse: collapse;
      }
    </style>
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
              <h1 class="text-2xl font-bold mb-4">Daftar Chart Of Account</h1>
                <div class="flex justify-between mb-4">
                  <div class="flex space-x-2">
                    <a href="<?= site_url('coa/create') ?>">
                      <button class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"></path>
                        </svg>
                      </button>
                    </a>
                  </div>
                </div>                
                <div class="flex justify-between mb-4">
                    <input 
                      type="text" 
                      id="searchInput" 
                      placeholder="Cari data..." 
                      class="w-full border rounded-md p-2"
                      onkeyup="searchTable()"
                    >
                </div>

              <div class="overflow-x-auto">
                <table id="dataTableTwo" class="min-w-full text-sm border border-gray-300">

                  <!-- HEADER -->
                  <thead class="bg-blue-600 text-white">
                    <tr>
                      <th class="px-3 py-2 text-left border">Account No</th>
                      <th class="px-3 py-2 text-left border">Name</th>
                      <th class="px-3 py-2 text-left border">Type</th>
                      <th class="px-3 py-2 text-right border">Balance</th>
                      <th class="px-3 py-2 text-center border">Aksi</th>
                    </tr>
                  </thead>

                  <!-- BODY -->
                  <tbody class="bg-white">
                    <?php foreach ($p as $c) : ?>
                    <tr class="even:bg-gray-100 hover:bg-blue-50 transition">
                      
                      <!-- ACCOUNT -->
                      <td class="px-3 py-2 border whitespace-nowrap">
                        <?= $c->nomer_kategori ?>
                      </td>

                      <!-- NAME -->
                      <td class="px-3 py-2 border">
                        <?= $c->nama_kategori ?>
                      </td>

                      <!-- TYPE -->
                      <td class="px-3 py-2 border">
                        <?= $c->detail_kategori ?>
                      </td>

                      <?= $balance=1000 ?>

                      <!-- BALANCE (RATA KANAN) -->
                      <td class="px-3 py-2 border text-right font-semibold">
                        <?= number_format($balance ?? 0, 0, ',', '.') ?>
                      </td>

                      <!-- AKSI -->
                      <td class="px-3 py-2 border">
                        <div class="flex flex-col gap-1">
                          <a href="<?= site_url('coa/lihat/'.$c->id) ?>" 
                             class="bg-yellow-500 text-white px-2 py-1 rounded text-center hover:bg-yellow-600">
                             Lihat
                          </a>
                          <a href="<?= site_url('coa/delete_permanent/'.$c->nomer_kategori) ?>" 
                             onclick="return confirm('Yakin ingin menghapus?')" 
                             class="bg-red-500 text-white px-2 py-1 rounded text-center hover:bg-red-600">
                             Hapus
                          </a>
                        </div>
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
  <script>
    function searchTable() {
      let input = document.getElementById("searchInput");
      let filter = input.value.toLowerCase();
      let table = document.getElementById("dataTableTwo");
      let tr = table.getElementsByTagName("tr");

      for (let i = 1; i < tr.length; i++) { // mulai dari 1 skip header
        let td = tr[i].getElementsByTagName("td");
        let found = false;

        for (let j = 0; j < td.length; j++) {
          if (td[j]) {
            let txtValue = td[j].textContent || td[j].innerText;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
              found = true;
              break;
            }
          }
        }

        tr[i].style.display = found ? "" : "none";
      }
    }
  </script>
</body>
</html>
