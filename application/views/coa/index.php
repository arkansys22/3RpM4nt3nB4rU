<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Chart Of Account</title>
    <link rel="icon" href="<?= base_url('assets/backend/mb.png') ?>" type="image/x-icon">
    <link href="<?= base_url('assets/backend/style.css') ?>" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .dataTables_filter { display: none !important; }
        #coaTable tr { transition: all 0.2s ease; }
        .bg-hover-parent { background-color: #fee2e2 !important; } /* Tailwind red-100 */
    </style>
</head>
<body
    x-data="{ loaded: true, darkMode: true }"
    x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')) || false; $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
>
    <div x-show="loaded" x-init="setTimeout(() => loaded = false, 500)" class="fixed inset-0 z-999999 flex items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <?php $this->load->view('backend/sidebar')?>

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <?php $this->load->view('backend/header')?>

            <main class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
                    <h1 class="text-2xl font-bold mb-4">Daftar Chart Of Account</h1>
                    
                    <div class="flex flex-wrap justify-between gap-4 mb-4">
                        <div class="flex gap-2">
                            <a href="<?= site_url('coa/create') ?>" class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                                </svg>
                            </a>
                            <button onclick="expandAll()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Expand All</button>
                            <button onclick="collapseAll()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Collapse All</button>
                            <button onclick="exportTableToExcel()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Export Excel</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <input type="text" id="searchInput" onkeyup="searchTable()" class="border p-2 w-full mb-3 rounded" placeholder="Cari akun atau nomor...">
                        
                        <table id="dataTableTwo" class="min-w-full text-sm border border-gray-300">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-3 py-2 text-left border">Account No</th>
                                    <th class="px-3 py-2 text-left border">Name</th>
                                    <th class="px-3 py-2 text-left border">Type</th>
                                    <th class="px-3 py-2 text-right border">Balance</th>
                                </tr>
                            </thead>
                            <tbody id="coaTable">
                                <?php 
                                $all_ids = array_column($p, 'nomer_kategori');
                                foreach ($p as $c) : 
                                    $level = substr_count($c->nomer_kategori, '.');
                                    $id = $c->nomer_kategori;
                                    
                                    // Cek apakah punya child
                                    $has_child = false;
                                    foreach($all_ids as $val) {
                                        if (strpos($val, $id . '.') === 0) { $has_child = true; break; }
                                    }

                                    $parent = (strpos($id, '.') !== false) ? substr($id, 0, strrpos($id, '.')) : '';
                                ?>
                                <tr data-parent="<?= $parent ?>" data-id="<?= $id ?>" class="coa-row border-b">
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <div style="padding-left: <?= $level * 20 ?>px" class="flex items-center gap-2">
                                            <?php if ($has_child): ?>
                                                <button onclick="toggleRow('<?= $id ?>', this)" class="toggle-btn text-blue-600 font-bold w-4 text-xs">▶</button>
                                            <?php else: ?>
                                                <span class="w-4"></span>
                                            <?php endif; ?>
                                            <?= $id ?>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2"><a href="#" class="text-blue-600 hover:underline"><?= $c->nama_kategori ?></a></td>
                                    <td class="px-3 py-2 text-gray-500"><?= $c->detail_kategori ?></td>
                                    <td class="px-3 py-2 text-right font-mono font-semibold balance-cell" data-value="<?= $c->balance ?? 0 ?>">
                                        <?= number_format($c->balance ?? 0, 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script defer src="<?= base_url('assets/backend/bundle.js') ?>"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Sembunyikan semua anak saat pertama kali load
            document.querySelectorAll("#coaTable tr").forEach(row => {
                if (row.getAttribute("data-parent")) row.style.display = "none";
            });
            
            calculateTotals();
            applyZebra();
            setupHoverEffect();
        });

        // Logika Toggle Parent-Child
        function toggleRow(id, btn) {
            const isOpening = btn.innerHTML === "▶";
            btn.innerHTML = isOpening ? "▼" : "▶";

            const rows = document.querySelectorAll("#coaTable tr");
            rows.forEach(row => {
                if (row.getAttribute("data-parent") === id) {
                    if (isOpening) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                        recursiveHide(row.getAttribute("data-id"));
                    }
                }
            });
            applyZebra();
        }

        function recursiveHide(parentId) {
            document.querySelectorAll(`#coaTable tr[data-parent='${parentId}']`).forEach(row => {
                row.style.display = "none";
                const btn = row.querySelector(".toggle-btn");
                if (btn) btn.innerHTML = "▶";
                recursiveHide(row.getAttribute("data-id"));
            });
        }

        // Kalkulasi Otomatis Saldo Induk (Bawah ke Atas)
        function calculateTotals() {
            const rows = Array.from(document.querySelectorAll("#coaTable tr")).reverse();
            rows.forEach(row => {
                const id = row.getAttribute("data-id");
                const children = document.querySelectorAll(`#coaTable tr[data-parent='${id}']`);
                
                if (children.length > 0) {
                    let total = 0;
                    children.forEach(child => {
                        total += parseFloat(child.querySelector(".balance-cell").getAttribute("data-value")) || 0;
                    });
                    const cell = row.querySelector(".balance-cell");
                    cell.setAttribute("data-value", total);
                    cell.innerHTML = formatRupiah(total);
                }
            });
        }

        // Fitur Search
        function searchTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("#coaTable tr");

            if (!input) {
                collapseAll();
                return;
            }

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const id = row.getAttribute("data-id");

                if (text.includes(input)) {
                    row.style.display = "";
                    // Tampilkan semua parent di atasnya agar tidak tersembunyi
                    let parentId = row.getAttribute("data-parent");
                    while (parentId) {
                        const pRow = document.querySelector(`#coaTable tr[data-id='${parentId}']`);
                        if (pRow) {
                            pRow.style.display = "";
                            const btn = pRow.querySelector(".toggle-btn");
                            if (btn) btn.innerHTML = "▼";
                            parentId = pRow.getAttribute("data-parent");
                        } else break;
                    }
                } else {
                    row.style.display = "none";
                }
            });
            applyZebra();
        }

        // Helpers
        function applyZebra() {
            const visibleRows = Array.from(document.querySelectorAll("#coaTable tr")).filter(r => r.style.display !== "none");
            visibleRows.forEach((row, i) => {
                row.style.backgroundColor = (i % 2 === 0) ? "#ffffff" : "#f8fafc";
            });
        }

        function formatRupiah(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function expandAll() {
            document.querySelectorAll("#coaTable tr").forEach(row => row.style.display = "");
            document.querySelectorAll(".toggle-btn").forEach(btn => btn.innerHTML = "▼");
            applyZebra();
        }

        function collapseAll() {
            document.querySelectorAll("#coaTable tr").forEach(row => {
                row.style.display = row.getAttribute("data-parent") ? "none" : "";
            });
            document.querySelectorAll(".toggle-btn").forEach(btn => btn.innerHTML = "▶");
            applyZebra();
        }

        function setupHoverEffect() {
            document.querySelectorAll("#coaTable tr").forEach(row => {
                row.addEventListener("mouseenter", () => {
                    let pId = row.getAttribute("data-parent");
                    while (pId) {
                        const pRow = document.querySelector(`#coaTable tr[data-id='${pId}']`);
                        if (pRow) {
                            pRow.classList.add("bg-hover-parent");
                            pId = pRow.getAttribute("data-parent");
                        } else break;
                    }
                });
                row.addEventListener("mouseleave", () => {
                    document.querySelectorAll(".bg-hover-parent").forEach(el => el.classList.remove("bg-hover-parent"));
                });
            });
        }

        function exportTableToExcel() {
            window.location.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(document.getElementById("dataTableTwo").outerHTML);
        }
    </script>
</body>
</html>