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
      .dataTables_filter {
          display: none !important;
        }

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
                <input type="text" id="searchInput" 
       onkeyup="searchTable()" 
       class="border p-2 w-full mb-3" 
       placeholder="Cari...">
                <table id="dataTableTwo" class="min-w-full text-sm border border-gray-300">

                  <!-- HEADER -->
                  <thead class="bg-blue-600 text-white">
                    <tr>
                      <th class="px-3 py-2 text-left border">Account No</th>
                      <th class="px-3 py-2 text-left border">Name</th>
                      <th class="px-3 py-2 text-left border">Type</th>
                      <th class="px-3 py-2 text-right border">Balance</th>
                    </tr>
                  </thead>

                  <!-- BODY -->
                  <tbody id="coaTable">
                    <?php 
                    // Buat array bantuan untuk cek apakah sebuah ID adalah parent
                    $all_ids = array_column($p, 'nomer_kategori');
                    
                    foreach ($p as $c) : 
                        $level = substr_count($c->nomer_kategori, '.');
                        $has_child = false;
                        foreach($all_ids as $id) {
                            if (strpos($id, $c->nomer_kategori . '.') === 0) {
                                $has_child = true;
                                break;
                            }
                        }

                        $parent = '';
                        if (strpos($c->nomer_kategori, '.') !== false) {
                            $parent = substr($c->nomer_kategori, 0, strrpos($c->nomer_kategori, '.'));
                        }
                    ?>
                    <tr data-parent="<?= $parent ?>" data-id="<?= $c->nomer_kategori ?>" class="coa-row transition">
                        <td class="px-3 py-2 border whitespace-nowrap">
                            <div style="padding-left: <?= $level * 20 ?>px" class="flex items-center gap-2">
                                <?php if ($has_child): ?>
                                    <button onclick="toggleRow('<?= $c->nomer_kategori ?>', this)" class="toggle-btn text-blue-600 w-4" data-open="false">▶</button>
                                <?php else: ?>
                                    <span class="w-4"></span> <?php endif; ?>
                                <?= $c->nomer_kategori ?>
                            </div>
                        </td>
                        <td class="px-3 py-2 border">
                            <a href="#" class="hover:underline text-blue-700"><?= $c->nama_kategori ?></a>
                        </td>
                        <td class="px-3 py-2 border"><?= $c->detail_kategori ?></td>
                        <td class="px-3 py-2 border text-right font-semibold balance-cell" data-value="<?= $c->balance ?? 0 ?>">
                            <?= number_format($c->balance ?? 0, 0, ',', '.') ?>
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
  
  <script>
document.addEventListener("DOMContentLoaded", function () {
  initTree();
  calculateTotals();
  applyZebra();
});

/* =========================
   INIT TREE
========================= */
function initTree() {
  document.querySelectorAll("#coaTable tr").forEach(row => {
    if (row.dataset.parent) {
      row.style.display = "none";
    }
  });
}

/* =========================
   TOGGLE TREE (FIX STABIL)
========================= */
function toggleRow(id, btn) {
  const isOpen = btn.dataset.open === "true";

  btn.dataset.open = !isOpen;
  btn.innerHTML = isOpen ? "▶" : "▼";

  document.querySelectorAll("#coaTable tr").forEach(row => {
    if (row.dataset.parent === id) {
      if (isOpen) {
        hideRecursive(row);
      } else {
        row.style.display = "";
      }
    }
  });

  applyZebra();
}

function hideRecursive(row) {
  row.style.display = "none";

  const id = row.dataset.id;

  const btn = row.querySelector(".toggle-btn");
  if (btn) {
    btn.dataset.open = "false";
    btn.innerHTML = "▶";
  }

  document.querySelectorAll("#coaTable tr").forEach(child => {
    if (child.dataset.parent === id) {
      hideRecursive(child);
    }
  });
}

/* =========================
   EXPAND / COLLAPSE GLOBAL
========================= */
function expandAll() {
  document.querySelectorAll("#coaTable tr").forEach(row => {
    row.style.display = "";
  });

  document.querySelectorAll(".toggle-btn").forEach(btn => {
    btn.dataset.open = "true";
    btn.innerHTML = "▼";
  });

  applyZebra();
}

function collapseAll() {
  document.querySelectorAll("#coaTable tr").forEach(row => {
    if (row.dataset.parent) {
      row.style.display = "none";
    }
  });

  document.querySelectorAll(".toggle-btn").forEach(btn => {
    btn.dataset.open = "false";
    btn.innerHTML = "▶";
  });

  applyZebra();
}

/* =========================
   SEARCH (SUPER STABIL)
========================= */
function searchTable() {
  const keyword = document.getElementById("searchInput").value.toLowerCase();

  const rows = document.querySelectorAll("#coaTable tr");

  rows.forEach(row => {
    const text = row.innerText.toLowerCase();
    const id = row.dataset.id;

    if (text.includes(keyword)) {
      row.style.display = "";

      // tampilkan semua parent
      let parts = id.split(".");
      while (parts.length > 1) {
        parts.pop();
        const parentId = parts.join(".");
        const parentRow = document.querySelector(`[data-id="${parentId}"]`);
        if (parentRow) parentRow.style.display = "";
      }

    } else {
      row.style.display = "none";
    }
  });

  applyZebra();
}

/* =========================
   ZEBRA STRIPE
========================= */
function applyZebra() {
  const visibleRows = Array.from(document.querySelectorAll("#coaTable tr"))
    .filter(r => r.style.display !== "none");

  visibleRows.forEach((row, i) => {
    row.style.backgroundColor = (i % 2 === 0) ? "#ffffff" : "#f2f7ff";
  });
}

/* =========================
   TOTAL (AKURAT TREE)
========================= */
function calculateTotals() {
  const rows = Array.from(document.querySelectorAll("#coaTable tr")).reverse();

  rows.forEach(row => {
    const id = row.dataset.id;
    const children = document.querySelectorAll(`[data-parent="${id}"]`);

    if (children.length > 0) {
      let total = 0;

      children.forEach(child => {
        total += parseFloat(child.querySelector(".balance-cell").dataset.value) || 0;
      });

      const cell = row.querySelector(".balance-cell");
      cell.dataset.value = total;
      cell.innerHTML = formatRupiah(total);
    }
  });
}

/* =========================
   FORMAT RUPIAH
========================= */
function formatRupiah(angka) {
  return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/* =========================
   EXPORT EXCEL
========================= */
function exportTableToExcel() {
  let table = document.getElementById("dataTableTwo").outerHTML;
  let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(table);

  let link = document.createElement("a");
  link.href = url;
  link.download = "chart_of_account.xls";
  link.click();
}
</script>
</body>
</html>
