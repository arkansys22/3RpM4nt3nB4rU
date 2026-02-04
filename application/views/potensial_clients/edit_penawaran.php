<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penawaran Klien</title>
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
              <h1 class="text-2xl font-bold mb-4">Edit Penawaran Klien</h1>
              <form action="<?= site_url('potensial-clients/update/'.$pc->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">

                <div class="mb-4.5 flex flex-col md:flex-row">
                  <h1 class="text-xl font-bold">Informasi Potensial Klien</h1>
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Nama Klien <p><h1 class="text-lg font-bold"><?= $pc->pc_name ?></h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Nomer WhatsApp <p><h1 class="text-lg font-bold"><?= $pc->pc_nowa ?></h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Lokasi Acara <p><h1 class="text-lg font-bold"><?= $pc->location ?></h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Tanggal Acara<p><h1 class="text-lg font-bold"><?= hari($pc->event_date) ?>, <?= tgl_indo($pc->event_date) ?></h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Wedding Planner & Organizer <p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="flex gap-4 mb-4">
                  <label>
                      <input type="radio" name="fayah_status" value="Tidak" id="masihAdaFayah" onclick="toggleReplacementFields('fayah', false)"
                      <?= empty($clients->f_bride_freplacementname) ? 'checked' : '' ?>> Tidak
                  </label>
                  <label>
                      <input type="radio" name="fayah_status" value="Pakai" id="tidakAdaFayah" onclick="toggleReplacementFields('fayah', true)"
                      <?= !empty($clients->f_bride_freplacementname) ? 'checked' : '' ?>> Pakai
                  </label>
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Dekorasi <p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Catering <p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Dokumentasi 1<p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Dokumentasi 2 <p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  MC <p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Entertainment <p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                <div class="mb-4.5 flex flex-col md:flex-row">
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  MUA & Attire <p><h1 class="text-lg font-bold">Pilihan Produk</h1></p>
                  </label> 
                  </div>
                  <div class="w-full md:w-1/2">
                  <label class="block mb-2">
                  Harga<p><h1 class="text-lg font-bold">Rp 8.888.888</h1></p>
                  </label>
                  </div>                  
                </div>
                

                
                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('potensial-clients/lihat/'.$pc->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
    <script>
    function toggleReplacementFields(type, show) {
        if (type === 'fayah') {
            document.getElementById('fayah-nama-ayah').classList.remove('hidden');
            document.getElementById('fayah-original').classList.toggle('hidden', show);
            document.getElementById('fayah').classList.toggle('hidden', !show);
        } else {
            document.getElementById(type).classList.toggle("hidden", !show);
            document.getElementById(type + '-original').classList.toggle("hidden", show);
        }
    }

    function toggleReplacementFieldsibu(type, show) {
        if (type === 'fibu') {
            document.getElementById('fibu-nama-ibu').classList.remove('hidden');
            document.getElementById('fibu-original').classList.toggle('hidden', show);
            document.getElementById('fibu').classList.toggle('hidden', !show);
        } else {
            document.getElementById(type).classList.toggle("hidden", !show);
            document.getElementById(type + '-original').classList.toggle("hidden", show);
        }
    }
    </script>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
