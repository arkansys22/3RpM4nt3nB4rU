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
    <style>

      #coaTable tr {
        transition: background-color 0.2s ease;
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

                <div class="flex flex-wrap gap-2 mb-4">

                  <button onclick="expandAll()" 
                    class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
                    Expand All
                  </button>

                  <button onclick="collapseAll()" 
                    class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600">
                    Collapse All
                  </button>

                  <button onclick="exportTableToExcel()" 
                    class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                    Export Excel
                  </button>

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
                  <tbody id="coaTable">
                    <?php foreach ($p as $c) : 

                      // hitung level berdasarkan jumlah titik
                      $level = substr_count($c->nomer_kategori, '.');

                    ?>
                    <tr 
                      data-parent="<?= $c->parent ?? '' ?>" 
                      data-id="<?= $c->nomer_kategori ?>"
                      class="coa-row transition"
                    >
                      <!-- ACCOUNT -->
                      <td class="px-3 py-2 border whitespace-nowrap">
                        <div style="padding-left: <?= $level * 20 ?>px" class="flex items-center gap-2">

                          <!-- BUTTON EXPAND -->
                          <button onclick="toggleRow('<?= $c->nomer_kategori ?>', this)" 
                                  class="toggle-btn text-blue-600 w-4">
                            ▶
                          </button>

                          <?= $c->nomer_kategori ?>
                        </div>
                      </td>

                      <!-- NAME -->
                      <td class="px-3 py-2 border">
                        <?= $c->nama_kategori ?>
                      </td>

                      <!-- TYPE -->
                      <td class="px-3 py-2 border">
                        <?= $c->detail_kategori ?>
                      </td>

                      <!-- BALANCE -->
                      <td class="px-3 py-2 border text-right font-semibold">
                        <?= number_format($c->balance ?? 0, 0, ',', '.') ?>
                      </td>

                      <!-- AKSI -->
                      <td class="px-3 py-2 border">
                        <div class="flex flex-col gap-1">
                          <a href="<?= site_url('coa/lihat/'.$c->id) ?>" 
                             class="bg-yellow-500 text-white px-2 py-1 rounded text-center">
                             Lihat
                          </a>
                          <a href="<?= site_url('coa/delete_permanent/'.$c->nomer_kategori) ?>" 
                             onclick="return confirm('Yakin ingin menghapus?')" 
                             class="bg-red-500 text-white px-2 py-1 rounded text-center">
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
    function applyZebra() {
      let rows = document.querySelectorAll("#coaTable tr");
      let visibleIndex = 0;

      rows.forEach(row => {
        if (row.style.display !== "none") {

          // reset dulu
          row.style.backgroundColor = "";

          if (visibleIndex % 2 === 0) {
            row.style.backgroundColor = "#ffffff"; // putih
          } else {
            row.style.backgroundColor = "#f2f7ff"; // biru muda
          }

          visibleIndex++;
        }
      });
    }
  </script>
  <script>
    document.querySelectorAll("#coaTable tr").forEach(row => {

      row.addEventListener("mouseenter", function () {
        let parent = row.getAttribute("data-parent");

        while (parent) {
          let parentRow = document.querySelector(`[data-id='${parent}']`);
          if (parentRow) {
            parentRow.classList.add("bg-red-100");
            parent = parentRow.getAttribute("data-parent");
          } else break;
        }
      });

      row.addEventListener("mouseleave", function () {
        document.querySelectorAll("#coaTable tr").forEach(r => {
          r.classList.remove("bg-red-100");
        });
      });

    });
  </script>
  <script>
    function exportTableToExcel() {
      let table = document.getElementById("dataTableTwo").outerHTML;
      let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(table);

      let link = document.createElement("a");
      link.href = url;
      link.download = "chart_of_account.xls";
      link.click();
    }
  </script>
  <script>
    function expandAll() {
      
      document.querySelectorAll("#coaTable tr").forEach(row => {
        row.style.display = "";

      });

      document.querySelectorAll(".toggle-btn").forEach(btn => {
        btn.innerHTML = "▼";
      });
      applyZebra();
    }

    function collapseAll() {
      
      document.querySelectorAll("#coaTable tr").forEach(row => {
        if (row.getAttribute("data-parent")) {
          row.style.display = "none";
        }
      });

      document.querySelectorAll(".toggle-btn").forEach(btn => {
        btn.innerHTML = "▶";
      });
      applyZebra();
    }
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let rows = document.querySelectorAll(".coa-row");

      rows.forEach(row => {
        if (row.getAttribute("data-parent")) {
          row.style.display = "none";
        }
      });
      applyZebra();
    });
  </script>
  <script>
    function toggleRow(id, btn) {
      let rows = document.querySelectorAll(`#coaTable tr`);
      let isOpen = btn.innerHTML === "▼";
      

      btn.innerHTML = isOpen ? "▶" : "▼";

      rows.forEach(row => {
        if (row.getAttribute("data-parent") === id) {
          if (isOpen) {
            row.style.display = "none";
            hideChildren(row.getAttribute("data-id"));

            let childBtn = row.querySelector(".toggle-btn");
            if (childBtn) childBtn.innerHTML = "▶";
          } else {
            row.style.display = "";
          }
        }
      });
      applyZebra();
    }

    function hideChildren(parentId) {
      let rows = document.querySelectorAll(`#coaTable tr`);

      rows.forEach(row => {
        if (row.getAttribute("data-parent") === parentId) {
          row.style.display = "none";
          hideChildren(row.getAttribute("data-id"));

          let btn = row.querySelector(".toggle-btn");
          if (btn) btn.innerHTML = "▶";
        }
      });
    }
  </script>
  <script>
    function searchTable() {
      
      let input = document.getElementById("searchInput").value.toLowerCase();
      let rows = document.querySelectorAll("#coaTable tr");

      rows.forEach(row => {
        let text = row.innerText.toLowerCase();

        if (text.includes(input)) {
          row.style.display = "";

          let parent = row.getAttribute("data-parent");
          while (parent) {
            let parentRow = document.querySelector(`[data-id='${parent}']`);
            if (parentRow) {
              parentRow.style.display = "";
              parent = parentRow.getAttribute("data-parent");
            } else break;
          }

        } else {
          row.style.display = "none";
        }
      });
      applyZebra();
    }
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      
      calculateTotals();
    });

    function calculateTotals() {
      let rows = document.querySelectorAll("#coaTable tr");

      rows.forEach(row => {
        let id = row.getAttribute("data-id");

        let total = sumChildren(id);

        if (total > 0) {
          let balanceCell = row.querySelector("td:nth-child(4)");
          if (balanceCell) {
            balanceCell.innerHTML = formatRupiah(total);
          }
        }
      });
    }

    function sumChildren(parentId) {
      let rows = document.querySelectorAll("#coaTable tr");
      let total = 0;

      rows.forEach(row => {
        if (row.getAttribute("data-parent") === parentId) {
          let balanceText = row.querySelector("td:nth-child(4)").innerText.replace(/\./g, '');
          let balance = parseInt(balanceText) || 0;

          total += balance;
          total += sumChildren(row.getAttribute("data-id"));
        }
      });

      return total;
    }

    function formatRupiah(angka) {
      return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  </script>
</body>
</html>
