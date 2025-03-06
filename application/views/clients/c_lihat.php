<?php
// Ambil agama dari tabel project berdasarkan id_session klien
$project = $this->db->get_where('project', ['id_session' => $clients->id_session])->row();
$religion = $project->religion ?? ''; // Pastikan tidak error jika religion kosong

$islam = strtolower($religion) === 'islam'; // Cek apakah agama Islam
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Client</title>
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
              <h1 class="text-2xl font-bold mb-4">Lihat Client</h1>
              <form action="<?= site_url('clients/c_update/'.$clients->id_session) ?>" method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2 text-black dark:text-white">Nama Client : <?= $clients->client_name ?></label>        
                <label class="block mb-2 text-black dark:text-white">Agama : <?= $project->religion ?></label>        
                <label class="block mb-2 text-black dark:text-white">Email : <?= $clients->email ?></label>        
                <label class="block mb-2 text-black dark:text-white">No HP : <?= $clients->phone ?></label>

                <!-- Data Mempelai Wanita -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white">Data Mempelai Wanita</h3>
                <label class="block mb-2 text-black dark:text-white">Nama Lengkap : <?= $clients->f_bride_fname ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Panggilan : <?= $clients->f_bride_cname ?></label>
                <label class="block mb-2 text-black dark:text-white">Anak Keberapa : <?= $clients->f_bride_nchild ?></label>
                <label class="block mb-2 text-black dark:text-white">Berapa Bersaudara : <?= $clients->f_bride_hsibling ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Lengkap Ayah : <?= $clients->f_bride_fathername ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Panggilan Ayah : <?= $clients->f_bride_fathercname ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Lengkap Ibu : <?= $clients->f_bride_mothername ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Panggilan Ibu : <?= $clients->f_bride_mothercname ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Saudara Kandung : <?= nl2br($clients->f_bride_sibling) ?></label>

                <!-- Data Mempelai Pria -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white">Data Mempelai Pria</h3>
                <label class="block mb-2 text-black dark:text-white">Nama Lengkap : <?= $clients->m_bride_fname ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Panggilan : <?= $clients->m_bride_cname ?></label>
                <label class="block mb-2 text-black dark:text-white">Anak Keberapa : <?= $clients->m_bride_nchild ?></label>
                <label class="block mb-2 text-black dark:text-white">Berapa Bersaudara : <?= $clients->m_bride_hsibling ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Lengkap Ayah : <?= $clients->m_bride_fathername ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Panggilan Ayah : <?= $clients->m_bride_fathercname ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Lengkap Ibu : <?= $clients->m_bride_mothername ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Panggilan Ibu : <?= $clients->m_bride_mothercname ?></label>
                <label class="block mb-2 text-black dark:text-white">Nama Saudara Kandung : <?= nl2br($clients->m_bride_sibling) ?></label>

                <!-- Detail Pernikahan -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white">Detail Pernikahan</h3>
                <label class="block mb-2 text-black dark:text-white">Tanggal Pernikahan : <?= hari($clients->wedding_date) ?>, <?= tgl_indo($clients->wedding_date) ?></label>
                <label class="block mb-2 text-black dark:text-white">Lokasi : <?= $clients->location ?></label>

                <?php if ($islam) : ?>
                <label class="block mb-2 text-black dark:text-white">Mahar : <?= $clients->mahr ?></label>
                <label class="block mb-2 text-black dark:text-white">Simbolis : <?= $clients->handover ?></label>

                <!-- Petugas dan Koordinator Akad Nikah -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white">Petugas dan Koordinator</h3>
                <label class="block mb-2 text-black dark:text-white">Koordinator Keluarga Wanita : <?= $clients->female_coor ?></label>
                <label class="block mb-2 text-black dark:text-white">Koordinator Keluarga Pria : <?= $clients->male_coor ?></label>
                <label class="block mb-2 text-black dark:text-white">Jubir Keluarga Wanita : <?= $clients->f_spokesman ?></label>
                <label class="block mb-2 text-black dark:text-white">Jubir Keluarga Pria : <?= $clients->m_spokesman ?></label>
                <label class="block mb-2 text-black dark:text-white">Penghulu : <?= $clients->wedding_officiant ?></label>
                <label class="block mb-2 text-black dark:text-white">Wali : <?= $clients->guardian ?></label>
                <label class="block mb-2 text-black dark:text-white">Saksi Calon Pengantin Wanita : <?= $clients->f_witness ?></label>
                <label class="block mb-2 text-black dark:text-white">Saksi Calon Pengantin Pria : <?= $clients->m_witness ?></label>
                <label class="block mb-2 text-black dark:text-white">Qoriah/Saritilawah : <?= $clients->qori ?></label>
                <label class="block mb-2 text-black dark:text-white">Nasihat Pernikahan : <?= $clients->advice_doa ?></label>
                <label class="block mb-2 text-black dark:text-white">Pengapit Calon Pengantin Wanita dari Keluarga : <?= $clients->clamp ?></label>
                <label class="block mb-2 text-black dark:text-white">Pembawa Nampan Kalung Bunga Melati : <?= $clients->jasmine_carrier ?></label>
                <label class="block mb-2 text-black dark:text-white">Pembawa Mas Kawin/Mahar : <?= $clients->mahr_carrier ?></label>
                <label class="block mb-2 text-black dark:text-white">Pembawa Cincin Kawin : <?= $clients->ring_carrier ?></label>

                <?php else : ?>
                <!-- Petugas dan Koordinator Resepsi -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white">Petugas dan Koordinator Resepsi</h3>
                <label class="block mb-2 text-black dark:text-white">Pendeta : <?= $clients->pastor ?></label>
                <label class="block mb-2 text-black dark:text-white">Gereja : <?= $clients->church ?></label>
                <label class="block mb-2 text-black dark:text-white">Pemimpin Doa : <?= $clients->prayer ?></label>
                <label class="block mb-2 text-black dark:text-white">Sambutan Pernikahan : <?= $clients->wedding_speech ?></label>
                <?php endif; ?>
                <div class="flex flex-wrap gap-2 mt-4">
                  <a href="<?= site_url('clients/edit/'. $clients->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Edit</a>
                  <a href="javascript:history.back()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
                  <a href="<?= site_url('naskah/jubir_cpp/'. $clients->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Jubir CPP</a>
                  <a href="<?= site_url('naskah/jubir_cpw/'. $clients->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Jubir CPW</a>
                  <a href="<?= site_url('naskah/izin_menikah/'. $clients->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Izin Menikah</a>
                  <a href="<?= site_url('naskah/terima_kasih/'. $clients->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Ucapan Terimakasih</a>
                  <a href="<?= site_url('naskah/data_pengantin/'. $clients->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Data Pengantin</a>
                  <a href="<?= site_url('naskah/list_vendor/'. $clients->id_session) ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">List Vendor</a>
                  <a href="<?= $clients->wedding_ceremony ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Susunan Akad</a>
                  <a href="<?= $clients->reception_afterward ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Susunan Resepsi</a>
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
