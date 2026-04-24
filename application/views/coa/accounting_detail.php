<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Accounting</title>
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
              <h1 class="text-2xl font-bold mb-4">
                  Detail Account: <?= $account->nama_kategori ?>
              </h1>

              <div class="bg-white p-4 rounded shadow mb-4">
                  <b>No Account:</b> <?= $account->nomer_kategori ?><br>
                  <b>Total:</b> Rp <?= number_format($total ?? 0,0,',','.') ?>
              </div>

              <table class="min-w-full text-sm border">
                  <thead class="bg-blue-600 text-white">
                      <tr>
                          <th class="px-3 py-2 border">Tanggal</th>
                          <th class="px-3 py-2 border">Transaksi</th>
                          <th class="px-3 py-2 border text-right">Nominal</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php foreach($transaksi as $t): ?>
                      <tr>
                          <td class="px-3 py-2 border">
                              <?= date('d-m-Y', strtotime($t->accounting_tanggal)) ?>
                          </td>
                          <td class="px-3 py-2 border">
                              <div class="flex flex-col">
                                  <span><?= $t->accounting_nama_transaksi ?></span>

                                  <?php if (!empty($t->project_name)) : ?>
                                      <span class="text-xs text-gray-500">
                                          <?= $t->project_name?>
                                      </span>
                                  <?php endif; ?>
                              </div>
                          </td>
                          <td class="px-3 py-2 border text-right">
                              Rp <?= number_format($t->accounting_nominal,0,',','.') ?>
                          </td>
                      </tr>
                      <?php endforeach; ?>
                  </tbody>
              </table>

              <a href="<?= site_url('coa') ?>" 
                 class="mt-4 inline-block bg-gray-500 text-white px-4 py-2 rounded">
                 Kembali
              </a>
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
  // calculateTotals();
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
  let table = document.getElementById("coaCustomTable").outerHTML;
  let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(table);

  let link = document.createElement("a");
  link.href = url;
  link.download = "chart_of_account.xls";
  link.click();
}
</script>

<script>
  function showDetail(el) {
      const id = el.dataset.id;
      const value = el.dataset.value;

      console.log("Klik balance:", id);

      // 🔥 OPTION 1: redirect ke halaman detail
      window.location.href = "<?= site_url('accounting/detail/') ?>" + id;

      // =============================
      // 🔥 OPTION 2 (AJAX - tanpa reload)
      // =============================
      /*
      fetch("<?= site_url('accounting/get_detail/') ?>" + id)
          .then(res => res.json())
          .then(res => {
              console.log(res);

              alert("Total data: " + res.total);
              // nanti bisa tampilkan ke modal popup
          })
          .catch(err => {
              console.log(err);
              alert("Gagal ambil data");
          });
      */
  }
</script>
<script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
