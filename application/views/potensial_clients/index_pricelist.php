<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Harga</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'projects', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
  <!-- ===== Preloader Start ===== -->
  <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
  </div>
  <!-- ===== Preloader End ===== -->

  <div class="flex h-screen overflow-hidden">
    <?php $this->load->view('backend/sidebar')?>

    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <?php $this->load->view('backend/header')?>

      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">

            <!-- ===== Stats Card ===== -->
            <div class="data-stats-slider-outer relative col-span-12 rounded-sm border border-stroke bg-white py-10 shadow-default dark:border-strokedark dark:bg-boxdark">
              <div class="block lg:hidden">
                <div class="dataStatsSlider swiper !-mx-px">
                  <div class="swiper-wrapper">
                    <div class="swiper-slide border-r border-stroke px-10 last:border-r-0 dark:border-strokedark">
                      <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                          <h4 class="text-xl font-bold">Daftar Harga</h4>
                        </div>
                      </div>
                      <div class="mt-5.5 flex flex-col gap-1.5">
                        <div class="flex items-center justify-between gap-1">
                          <p class="text-sm font-medium">Total</p>
                          <p class="font-medium" id="totalCount"><?= count($potensial_clients_pricelist); ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="hidden lg:block">
                <div class="flex flex-col items-center justify-center">
                  <div class="text-center">
                    <h4 class="text-xl font-bold mb-2">Daftar Harga</h4>
                    <p class="text-lg font-medium">Total</p>
                    <p class="text-2xl font-bold" id="totalCountDesktop"><?= count($potensial_clients_pricelist); ?></p>
                  </div>
                </div>
              </div>

              

            </div>

            <!-- ===== Tabel ===== -->
            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
              <h1 class="text-2xl font-bold mb-4">Daftar Harga</h1>

              <div class="col-span-12 flex flex-wrap items-center justify-center gap-3 md:justify-between">
                <div class="relative">
                  <a href="<?= site_url('potensial-clients-pricelist/create') ?>">
                    <button class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"></path>
                      </svg>
                    </button>
                  </a>
                </div>
                <div class="relative z-20">
                  <a href="<?= site_url('potensial-clients-pricelist/recycle_bin') ?>">
                    <button class="bg-red-500 text-white p-3 rounded-md hover:bg-red-700 focus:outline-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-2 14H7L5 7M12 4v-2m4 2h-8m5 2l1-1m3 1l-1-1m0 0h6l-1 2m-7-5h2m6 5H5"></path>
                      </svg>
                    </button>
                  </a>
                </div>
              </div>


              <!-- ===== Tombol Filter Kategori ===== -->
              <div class="mt-6 px-2">
                <p class="text-xs font-semibold mb-3 uppercase tracking-widest text-center text-gray-400 dark:text-gray-500">Filter Kategori</p>
                <div class="flex flex-wrap justify-center gap-2">
                  <button onclick="filterKategori(this)" data-kategori="semua"
                    class="btn-filter active-filter px-3 py-1.5 rounded-full text-xs font-semibold border transition-all duration-200 bg-blue-500 text-white border-blue-500 shadow-sm">
                    Semua
                  </button>
                  <?php
                  $kategoris = ['MC','WP dan WO','Dokumentasi','Dekorasi','Catering','Entertainment','Makeup & Busana','Venue Pernikahan','Paket Pernikahan','Paket Wedding Organizer','Paket Wedding Gedung','Paket Wedding Rumah'];
                  foreach ($kategoris as $k): ?>
                  <button onclick="filterKategori(this)" data-kategori="<?= $k ?>"
                    class="btn-filter px-3 py-1.5 rounded-full text-xs font-semibold border transition-all duration-200 bg-white dark:bg-boxdark text-gray-600 dark:text-gray-300 border-gray-200 dark:border-strokedark hover:bg-blue-500 hover:text-white hover:border-blue-500">
                    <?= $k ?>
                  </button>
                  <?php endforeach; ?>
                </div>
              </div>
              <!-- ===== End Tombol Filter Kategori ===== -->

              <br>

              <!-- Search & Info Bar -->
              <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-2 mb-3 px-1">
                <div class="relative w-full sm:w-64">
                  <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                  </svg>
                  <input type="text" id="searchInput" placeholder="Cari judul atau kategori..."
                    oninput="handleSearch(this.value)"
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 dark:border-strokedark rounded-lg bg-white dark:bg-boxdark text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"/>
                </div>
                <p id="infoText" class="text-xs text-gray-400 whitespace-nowrap"></p>
              </div>

              <!-- Tabel -->
              <div class="rounded-xl border border-gray-100 dark:border-strokedark overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                  <table class="w-full text-sm" id="mainTable">
                    <thead>
                      <tr class="bg-gray-50 dark:bg-meta-4 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Judul</th>
                        <th class="px-4 py-3 text-left font-semibold">Harga Asli</th>
                        <th class="px-4 py-3 text-left font-semibold">Harga Promo</th>
                        <th class="px-4 py-3 text-left font-semibold">Kategori</th>
                        <th class="px-4 py-3 text-left font-semibold">Diupdate</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-gray-100 dark:divide-strokedark">
                      <?php $no = 1; foreach ($potensial_clients_pl as $p): ?>
                        <tr data-kategori="<?= htmlspecialchars(trim($p->data_pricelist_type)) ?>"
                            data-judul="<?= htmlspecialchars(strtolower($p->data_pricelist_judul)) ?>"
                            class="hover:bg-blue-50 dark:hover:bg-meta-4 transition-colors duration-150 group">
                          <td class="px-4 py-3 text-gray-400 text-xs font-mono row-no"><?= $no++ ?></td>
                          <td class="px-4 py-3">
                            <span class="font-medium text-gray-800 dark:text-white"><?= $p->data_pricelist_judul ?></span>
                          </td>
                          <td class="px-4 py-3">
                            <span class="text-gray-400 line-through text-xs"><?= "Rp " . number_format($p->data_pricelist_harga, 0, ',', '.') ?></span>
                          </td>
                          <td class="px-4 py-3">
                            <span class="font-semibold text-green-600 dark:text-green-400"><?= "Rp " . number_format($p->data_pricelist_hargapromo, 0, ',', '.') ?></span>
                          </td>
                          <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                              <?= htmlspecialchars(trim($p->data_pricelist_type)) ?>
                            </span>
                          </td>
                          <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                            <?= tgl_indo($p->data_pricelist_lastupdate) ?>
                            <br><span class="text-gray-400"><?= time_ago($p->data_pricelist_lastupdate) ?></span>
                          </td>
                          <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                              <a href="<?= site_url('potensial-clients-pricelist/lihat/'. $p->data_pricelist_idsession) ?>"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-400 hover:bg-amber-500 text-white text-xs font-semibold transition-all shadow-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat
                              </a>
                              <a href="<?= site_url('potensial-clients-pricelist/delete/'.$p->data_pricelist_idsession) ?>"
                                onclick="return confirm('Yakin ingin menghapus <?= htmlspecialchars($p->data_pricelist_judul) ?>?')"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-xs font-semibold transition-all shadow-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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

              <!-- Empty State -->
              <div id="emptyState" class="hidden text-center py-16">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-400 font-medium">Tidak ada data untuk kategori ini</p>
              </div>

              <!-- Pagination -->
              <div id="customPagination" class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4 px-1"></div>


            </div>

          </div>
        </div>
      </main>
    </div>
  </div>
  <script src="<?php echo base_url()?>assets/backend/bundle.js"></script>
  <script>
    let activeKategori = 'semua';
    let searchQuery    = '';
    let currentPage    = 1;
    const perPage      = 10;
    let allRows        = [];

    window.addEventListener('DOMContentLoaded', function () {
      if (typeof $ !== 'undefined' && $.fn.DataTable && $.fn.DataTable.isDataTable('#mainTable')) {
        $('#mainTable').DataTable().destroy();
      }
      allRows = Array.from(document.querySelectorAll('#tableBody tr'));
      renderTable();
    });

    function getFilteredRows() {
      return allRows.filter(row => {
        const kat   = row.getAttribute('data-kategori') || '';
        const judul = row.getAttribute('data-judul') || '';
        const matchKat    = activeKategori === 'semua' || kat.trim() === activeKategori;
        const matchSearch = judul.includes(searchQuery.toLowerCase()) || kat.toLowerCase().includes(searchQuery.toLowerCase());
        return matchKat && matchSearch;
      });
    }

    function renderTable() {
      const filtered   = getFilteredRows();
      const totalData  = filtered.length;
      const totalPages = Math.ceil(totalData / perPage);

      if (currentPage > totalPages) currentPage = 1;

      const start    = (currentPage - 1) * perPage;
      const end      = start + perPage;
      const pageRows = filtered.slice(start, end);

      // Sembunyikan semua
      allRows.forEach(row => row.style.display = 'none');

      // Tampilkan halaman aktif
      pageRows.forEach((row, i) => {
        row.style.display = '';
        row.querySelector('.row-no').textContent = start + i + 1;
      });

      // Empty state
      const emptyState = document.getElementById('emptyState');
      emptyState.classList.toggle('hidden', totalData > 0);

      // Update total
      ['totalCount','totalCountDesktop'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = totalData;
      });

      // Info text
      const s = totalData > 0 ? start + 1 : 0;
      const e = Math.min(end, totalData);
      const infoEl = document.getElementById('infoText');
      if (infoEl) infoEl.textContent = `Menampilkan ${s}–${e} dari ${totalData} data`;

      renderPagination(totalData, totalPages);
    }

    function renderPagination(totalData, totalPages) {
      const el = document.getElementById('customPagination');
      if (!el) return;

      if (totalPages <= 1) { el.innerHTML = ''; return; }

      // Tombol halaman dengan ellipsis
      let pages = [];
      for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
          pages.push(i);
        } else if (pages[pages.length - 1] !== '...') {
          pages.push('...');
        }
      }

      const btnBase = 'px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all duration-150';
      const btnActive = 'bg-blue-500 text-white border-blue-500 shadow-sm';
      const btnInactive = 'bg-white dark:bg-boxdark text-gray-600 dark:text-gray-300 border-gray-200 dark:border-strokedark hover:bg-blue-50 hover:border-blue-300';
      const btnDisabled = 'opacity-40 cursor-not-allowed bg-white dark:bg-boxdark text-gray-400 border-gray-200 dark:border-strokedark';

      let html = `<div class="flex flex-wrap gap-1.5 items-center">`;

      html += `<button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}
                class="${btnBase} ${currentPage === 1 ? btnDisabled : btnInactive}">
                ← Prev
               </button>`;

      pages.forEach(p => {
        if (p === '...') {
          html += `<span class="px-2 text-gray-400 text-xs">…</span>`;
        } else {
          html += `<button onclick="goToPage(${p})" class="${btnBase} ${p === currentPage ? btnActive : btnInactive}">${p}</button>`;
        }
      });

      html += `<button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}
                class="${btnBase} ${currentPage === totalPages ? btnDisabled : btnInactive}">
                Next →
               </button>`;

      html += `</div>`;
      el.innerHTML = html;
    }

    function goToPage(page) {
      const totalPages = Math.ceil(getFilteredRows().length / perPage);
      if (page < 1 || page > totalPages) return;
      currentPage = page;
      renderTable();
    }

    function handleSearch(value) {
      searchQuery = value.trim();
      currentPage = 1;
      renderTable();
    }

    function filterKategori(button) {
      activeKategori = button.dataset.kategori;
      currentPage    = 1;

      document.querySelectorAll('.btn-filter').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white', 'border-blue-500', 'shadow-sm');
        btn.classList.add('bg-white', 'dark:bg-boxdark', 'text-gray-600', 'dark:text-gray-300', 'border-gray-200', 'dark:border-strokedark');
      });

      button.classList.remove('bg-white', 'dark:bg-boxdark', 'text-gray-600', 'dark:text-gray-300', 'border-gray-200', 'dark:border-strokedark');
      button.classList.add('bg-blue-500', 'text-white', 'border-blue-500', 'shadow-sm');

      renderTable();
    }
  </script>
</body>
</html>