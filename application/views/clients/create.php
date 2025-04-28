<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Client</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'clients', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h2 class="text-2xl font-bold mb-4">Tambah Client</h2>
              <form action="<?= site_url('clients/store') ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <!-- Data Utama -->
              <h3 class="text-lg font-bold mt-6 mb-2">Data Klien</h3>

              <label class="block mb-2">Nama Klien</label>
              <input type="text" name="client_name" placeholder="Nama Klien" class="w-full px-4 py-2 border rounded mb-4" required>

              <label class="block mb-2">Email</label>
              <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded mb-4" required>

              <label class="block mb-2">No HP</label>
              <input type="text" name="phone" placeholder="No HP" class="w-full px-4 py-2 border rounded mb-4" required>

              <!-- Data Mempelai Wanita -->
              <h3 class="text-lg font-bold mt-6 mb-2">Data Mempelai Wanita</h3>
              <label class="block mb-2">Nama Lengkap</label>
              <input type="text" name="f_bride_fname" placeholder="Nama Lengkap" class="w-full px-4 py-2 border rounded mb-4" required>
              
              <label class="block mb-2">Nama Panggilan</label>
              <input type="text" name="f_bride_cname" placeholder="Nama Panggilan" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Anak Keberapa (anak ke 1/2/3/...)</label>
              <input type="number" name="f_bride_nchild" placeholder="1/2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Berapa Bersaudara (2/3/... bersaudara)</label>
              <input type="number" name="f_bride_hsibling" placeholder="2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Nama Lengkap Ayah</label>
              <input type="text" name="f_bride_fathername" placeholder="Nama Lengkap Ayah" class="w-full px-4 py-2 border rounded mb-4" required>
              
              <label class="block mb-2">Nama Panggilan Ayah</label>
              <input type="text" name="f_bride_fathercname" placeholder="Nama Panggilan Ayah" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Nama Lengkap Ibu</label>
              <input type="text" name="f_bride_mothername" placeholder="Nama Lengkap Ibu" class="w-full px-4 py-2 border rounded mb-4" required>

              <label class="block mb-2">Nama Panggilan Ibu</label>
              <input type="text" name="f_bride_mothercname" placeholder="Nama Panggilan Ibu" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Nama Saudara Kandung</label>
              <textarea name="f_bride_sibling" placeholder="1. A&#10;2. B&#10;3. ..." class="w-full px-4 py-2 border rounded mb-4"></textarea>

              <!-- Data Mempelai Pria -->
              <h3 class="text-lg font-bold mt-6 mb-2">Data Mempelai Pria</h3>
              <label class="block mb-2">Nama Lengkap</label>
              <input type="text" name="m_bride_fname" placeholder="Nama Lengkap" class="w-full px-4 py-2 border rounded mb-4" required>
              
              <label class="block mb-2">Nama Panggilan</label>
              <input type="text" name="m_bride_cname" placeholder="Nama Panggilan" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Anak Keberapa (anak ke 1/2/3/...)</label>
              <input type="number" name="m_bride_nchild" placeholder="1/2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Berapa Bersaudara (2/3/... bersaudara)</label>
              <input type="number" name="m_bride_hsibling" placeholder="2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Nama Lengkap Ayah</label>
              <input type="text" name="m_bride_fathername" placeholder="Nama Lengkap Ayah" class="w-full px-4 py-2 border rounded mb-4" required>
              
              <label class="block mb-2">Nama Panggilan Ayah</label>
              <input type="text" name="m_bride_fathercname" placeholder="Nama Panggilan Ayah" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Nama Lengkap Ibu</label>
              <input type="text" name="m_bride_mothername" placeholder="Nama Lengkap Ibu" class="w-full px-4 py-2 border rounded mb-4" required>
              
              <label class="block mb-2">Nama Panggilan Ibu</label>
              <input type="text" name="m_bride_mothercname" placeholder="Nama Panggilan Ibu" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Nama Saudara Kandung</label>
              <textarea name="m_bride_sibling" placeholder="1. A&#10;2. B&#10;3. ..." class="w-full px-4 py-2 border rounded mb-4"></textarea>

              <!-- Detail Pernikahan -->
              <h3 class="text-lg font-bold mt-6 mb-2">Detail Pernikahan</h3>
              <label class="block mb-2">Tanggal Pernikahan</label>
              <input type="date" name="wedding_date" placeholder="Tanggal Pernikahan" class="w-full px-4 py-2 border rounded mb-4" required>

              <label class="block mb-2">Lokasi</label>
              <input type="text" name="location" placeholder="Lokasi" class="w-full px-4 py-2 border rounded mb-4" required>

              <label class="block mb-2">Mahar</label>
              <input type="text" name="mahr" placeholder="Mahar" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Simbolis</label>
              <input type="text" name="handover" placeholder="Simbolis" class="w-full px-4 py-2 border rounded mb-4">

              <!-- Petugas dan Koordinator Akad Nikah -->
              <h3 class="text-lg font-bold mt-6 mb-2">Petugas dan Koordinator</h3>
              <label class="block mb-2">Koordinator Keluarga Wanita</label>
              <input type="text" name="female_coor" placeholder="Koordinator Keluarga Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Koordinator Keluarga Pria</label>
              <input type="text" name="male_coor" placeholder="Koordinator Keluarga Pria" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Jubir Keluarga Wanita</label>
              <input type="text" name="f_spokesman" placeholder="Jubir Keluarga Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Jubir Keluarga Pria</label>
              <input type="text" name="m_spokesman" placeholder="Jubir Keluarga Pria" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Penghulu</label>
              <input type="text" name="wedding_officiant" placeholder="Penghulu" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Wali</label>
              <input type="text" name="guardian" placeholder="Wali" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Saksi Calon Pengantin Wanita</label>
              <input type="text" name="f_witness" placeholder="Saksi Calon Pengantin Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Saksi Calon Pengantin Pria</label>
              <input type="text" name="m_witness" placeholder="Saksi Calon Pengantin Pria" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Qoriah/Saritilawah</label>
              <input type="text" name="qori" placeholder="Qoriah/Saritilawah" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Nasihat Pernikahan</label>
              <input type="text" name="advice_doa" placeholder="Nasihat Pernikahan" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pengapit Calon Pengantin Wanita dari Keluarga</label>
              <input type="text" name="clamp" placeholder="Pengapit Calon Pengantin Wanita dari Keluarga" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pembawa Nampan Kalung Bunga Melati</label>
              <input type="text" name="jasmine_carrier" placeholder="Pembawa Nampan Kalung Bunga Melati" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pembawa Mas Kawin/Mahar</label>
              <input type="text" name="mahr_carrier" placeholder="Pembawa Mas Kawin/Mahar" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pembawa Cincin Kawin</label>
              <input type="text" name="ring_carrier" placeholder="Pembawa Cincin Kawi" class="w-full px-4 py-2 border rounded mb-4">

              <!-- Petugas dan Koordinator Resepsi -->
              <h3 class="text-lg font-bold mt-6 mb-2">Petugas dan Koordinator Resepsi</h3>
              <label class="block mb-2">Pendeta</label>
              <input type="text" name="pastor" placeholder="Pendeta" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Gereja</label>
              <input type="text" name="church" placeholder="Gereja" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pemimpin Doa</label>
              <input type="text" name="prayer" placeholder="Pemimpin Doa" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Sambutan Pernikahan</label>
              <input type="text" name="wedding_speech" placeholder="Sambutan Pernikahan" class="w-full px-4 py-2 border rounded mb-4">

              <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                <a href="<?= site_url('clients') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
</body>
</html>
