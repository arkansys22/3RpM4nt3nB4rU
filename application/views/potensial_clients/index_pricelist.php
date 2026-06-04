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
              <div class="mt-6 px-6">
                <p class="text-sm font-semibold mb-3 text-center text-gray-600 dark:text-gray-300">Filter Kategori</p>
                <div class="flex flex-wrap justify-center gap-2">
                  <button onclick="filterKategori(this)"
                          data-kategori="semua"
                          class="btn-filter bg-blue-500 text-white border-blue-500 px-3 py-1.5 rounded-full text-sm font-medium border">
                      Semua
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="MC"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      MC
                  </button>

                  <button onclick="filterKategori(this)"
                          data-kategori="WP dan WO"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      WP dan WO
                  </button>

                  <button onclick="filterKategori(this)"
                          data-kategori="Dokumentasi"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      Dokumentasi
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Dekorasi"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      Dekorasi
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Catering"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      Catering
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Entertainment"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      Entertainment
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Makeup & Busana"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      Makeup & Busana
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Venue Pernikahan"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      Venue Pernikahan
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Paket Pernikahan"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                      Paket Pernikahan
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Paket Wedding Organizer"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                       Paket Wedding Organizer
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Paket Wedding Gedung"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                       Paket Wedding Gedung
                  </button>
                  <button onclick="filterKategori(this)"
                          data-kategori="Paket Wedding Rumah"
                          class="btn-filter px-3 py-1.5 rounded-full text-sm font-medium border">
                       Paket Wedding Rumah
                  </button>
                </div>
              </div>
              <!-- ===== End Tombol Filter Kategori ===== -->

              <br><br>
              <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="data-table-common data-table-two max-w-full overflow-x-auto">
                  <table class="table w-full table-auto" id="dataTableTwo">
                    <thead>
                      <tr>
                        <th><div class="flex items-center justify-between gap-1.5"><p>No</p></div></th>
                        <th><div class="flex items-center justify-between gap-1.5"><p>Judul</p></div></th>
                        <th><div class="flex items-center justify-between gap-1.5"><p>Harga Asli</p></div></th>
                        <th><div class="flex items-center justify-between gap-1.5"><p>Harga Promo</p></div></th>
                        <th><div class="flex items-center justify-between gap-1.5"><p>Kategori</p></div></th>
                        <th><div class="flex items-center justify-between gap-1.5"><p>Terakhir Diupdate</p></div></th>
                        <th><div class="flex items-center justify-between gap-1.5"><p>Aksi</p></div></th>
                      </tr>
                    </thead>
                    <tbody id="tableBody">
                      <?php $no = 1; foreach ($potensial_clients_pl as $p): ?>
                        <tr data-kategori="<?= trim($p->data_pricelist_type) ?>">
                          <td><?= $no++ ?></td>
                          <td><?= $p->data_pricelist_judul ?></td>
                          <td><s><?= "Rp " . number_format($p->data_pricelist_harga, 0, ',', '.'); ?></s></td>
                          <td><?= "Rp " . number_format($p->data_pricelist_hargapromo, 0, ',', '.'); ?></td>
                          <td><?= trim($p->data_pricelist_type) ?></td>
                          <td><?= tgl_indo($p->data_pricelist_lastupdate) ?> <p><small><?= time_ago($p->data_pricelist_lastupdate) ?></small></p></td>
                          <td>
                            <div class="flex flex-col items-start gap-2 w-max">
                              <a href="<?= site_url('potensial-clients-pricelist/lihat/'. $p->data_pricelist_idsession) ?>" class="inline-flex justify-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 text-center w-full">Lihat</a>
                              <a href="<?= site_url('potensial-clients-pricelist/delete/'.$p->data_pricelist_idsession) ?>" class="inline-flex justify-center bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 min-w-full text-center" onclick="return confirm('Yakin ingin menghapus potensial clients <?= $p->data_pricelist_judul ?>?')">Hapus</a>
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
        </div>
      </main>
    </div>
  </div>

  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>

<script>
  let table;
  let activeKategori = 'semua';

  $(document).ready(function () {

    table = $('#dataTableTwo').DataTable({
      pageLength: 10
    });

    // Custom search filter berdasarkan data-kategori di <tr>
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
      if (activeKategori === 'semua') return true;

      const row = table.row(dataIndex).node();
      const rowKategori = $(row).attr('data-kategori') ? $(row).attr('data-kategori').trim() : '';

      return rowKategori === activeKategori;
    });

    updateTotal();

    table.on('draw', function () {
      updateTotal();
    });

  });

  function updateTotal() {
    const count = table ? table.rows({ search: 'applied' }).count() : 0;
    const totalMobile  = document.getElementById('totalCount');
    const totalDesktop = document.getElementById('totalCountDesktop');
    if (totalMobile)  totalMobile.textContent  = count;
    if (totalDesktop) totalDesktop.textContent = count;
  }

  function filterKategori(button) {
    activeKategori = button.dataset.kategori;

    // Reset semua tombol
    document.querySelectorAll('.btn-filter').forEach(btn => {
      btn.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
      btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
    });

    // Aktifkan tombol yang dipilih
    button.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
    button.classList.add('bg-blue-500', 'text-white', 'border-blue-500');

    // Redraw tabel dengan filter baru
    table.draw();
  }
</script>
</body>
</html>