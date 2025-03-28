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
              <form action="<?= site_url('clients/update/'.$clients->id_session) ?>" method="post" class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Client : </strong><?= $clients->client_name ?></label>        
                <label class="block mb-2 text-black dark:text-white"><strong>Agama : </strong><?= $project->religion ?></label>        
                <label class="block mb-2 text-black dark:text-white"><strong>Email : </strong><?= $clients->email ?></label>        
                <label class="block mb-2 text-black dark:text-white"><strong>No HP : </strong><?= $clients->phone ?></label>

                <!-- Data Mempelai Wanita -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white"><strong>Data Mempelai Wanita</strong></h3>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap : </strong><?= $clients->f_bride_fname ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan : </strong><?= $clients->f_bride_cname ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Anak Keberapa : </strong><?= $clients->f_bride_nchild ?> dari <?= $clients->f_bride_hsibling ?> Bersaudara</label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Ayah : </strong><?= $clients->f_bride_fathername ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Ayah : </strong><?= $clients->f_bride_fathercname ?></label>
                <?php if (!empty($clients->f_bride_freplacementname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Pengganti : </strong><?= $clients->f_bride_freplacementname ?></label>
                <?php endif; ?>
                <?php if (!empty($clients->f_bride_freplacementcname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Pengganti : </strong><?= $clients->f_bride_freplacementcname ?></label>
                <?php endif; ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Ibu : </strong><?= $clients->f_bride_mothername ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Ibu : </strong><?= $clients->f_bride_mothercname ?></label>
                <?php if (!empty($clients->f_bride_mreplacementname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Pengganti : </strong><?= $clients->f_bride_mreplacementname ?></label>
                <?php endif; ?>
                <?php if (!empty($clients->f_bride_mreplacementcname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Pengganti : </strong><?= $clients->f_bride_mreplacementcname ?></label>
                <?php endif; ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Saudara Kandung : </strong><?= nl2br($clients->f_bride_sibling) ?></label>

                <!-- Data Mempelai Pria -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white"><strong>Data Mempelai Pria</strong></h3>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap : </strong><?= $clients->m_bride_fname ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan : </strong><?= $clients->m_bride_cname ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Anak Keberapa : </strong><?= $clients->m_bride_nchild ?> dari <?= $clients->m_bride_hsibling ?> Bersaudara</label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Ayah : </strong><?= $clients->m_bride_fathername ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Ayah : </strong><?= $clients->m_bride_fathercname ?></label>
                <?php if (!empty($clients->m_bride_freplacementname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Pengganti : </strong><?= $clients->m_bride_freplacementname ?></label>
                <?php endif; ?>
                <?php if (!empty($clients->m_bride_freplacementcname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Pengganti : </strong><?= $clients->m_bride_freplacementcname ?></label>
                <?php endif; ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Ibu : </strong><?= $clients->m_bride_mothername ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Ibu : </strong><?= $clients->m_bride_mothercname ?></label>
                <?php if (!empty($clients->m_bride_mreplacementname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Lengkap Pengganti : </strong><?= $clients->m_bride_mreplacementname ?></label>
                <?php endif; ?>
                <?php if (!empty($clients->m_bride_mreplacementcname)) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Panggilan Pengganti : </strong><?= $clients->m_bride_mreplacementcname ?></label>
                <?php endif; ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Nama Saudara Kandung : </strong><?= nl2br($clients->m_bride_sibling) ?></label>

                <!-- Detail Pernikahan -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white"><strong>Detail Pernikahan</strong></h3>
                <label class="block mb-2 text-black dark:text-white"><strong>Tanggal Pernikahan : </strong><?= hari($clients->wedding_date) ?>, <?= tgl_indo($clients->wedding_date) ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Lokasi : </strong><?= $clients->location ?></label>

                <?php if ($islam) : ?>
                <label class="block mb-2 text-black dark:text-white"><strong>Mahar : </strong><?= $clients->mahr ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Simbolis Seserahan : </strong><?= $clients->handover ?></label>

                <!-- Petugas dan Koordinator Akad Nikah -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white"><strong>Petugas dan Koordinator</strong></h3>
                <label class="block mb-2 text-black dark:text-white"><strong>Koordinator Keluarga Wanita : </strong><?= $clients->female_coor ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Koordinator Keluarga Pria : </strong><?= $clients->male_coor ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Jubir Keluarga Wanita : </strong><?= $clients->f_spokesman ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Jubir Keluarga Pria : </strong><?= $clients->m_spokesman ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Penghulu : </strong><?= $clients->wedding_officiant ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Wali : </strong><?= $clients->guardian ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Saksi Calon Pengantin Wanita : </strong><?= $clients->f_witness ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Saksi Calon Pengantin Pria : </strong><?= $clients->m_witness ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Qoriah/Saritilawah : </strong><?= $clients->qori ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Nasihat Pernikahan : </strong><?= $clients->advice_doa ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Pengapit Calon Pengantin Wanita dari Keluarga : </strong><?= $clients->clamp ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Pembawa Nampan Kalung Bunga Melati : </strong><?= $clients->jasmine_carrier ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Pembawa Mas Kawin/Mahar : </strong><?= $clients->mahr_carrier ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Pembawa Cincin Kawin : </strong><?= $clients->ring_carrier ?></label>

                <?php else : ?>
                <!-- Petugas dan Koordinator Resepsi -->
                <h3 class="text-lg font-bold mt-6 mb-2 text-black dark:text-white"><strong>Petugas dan Koordinator Resepsi</strong></h3>
                <label class="block mb-2 text-black dark:text-white"><strong>Koordinator Keluarga Wanita : </strong><?= $clients->female_coor ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Koordinator Keluarga Pria : </strong><?= $clients->male_coor ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Pendeta : </strong><?= $clients->pastor ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Gereja : </strong><?= $clients->church ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Pemimpin Doa : </strong><?= $clients->prayer ?></label>
                <label class="block mb-2 text-black dark:text-white"><strong>Sambutan Pernikahan : </strong><?= $clients->wedding_speech ?></label>
                <?php endif; ?>
                <div class="flex flex-wrap gap-2 mt-4">
                  <a href="<?= site_url('clients/edit/'. $clients->id_session) ?>" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
                  <a href="javascript:history.back()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
                  <a href="<?= site_url('naskah/jubir_cpp/'. $clients->id_session) ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Jubir CPP</a>
                  <a href="<?= site_url('naskah/jubir_cpw/'. $clients->id_session) ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Jubir CPW</a>
                  <a href="<?= site_url('naskah/izin_menikah/'. $clients->id_session) ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Izin Menikah</a>
                  <a href="<?= site_url('naskah/terima_kasih/'. $clients->id_session) ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ucapan Terimakasih</a>
                  <a href="<?= site_url('naskah/data_pengantin/'. $clients->id_session) ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Data Pengantin</a>
                  <a href="<?= site_url('naskah/list_vendor/'. $clients->id_session) ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">List Vendor</a>
                  <a href="<?= $clients->wedding_ceremony ?>"  target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Susunan Akad</a>
                  <a href="<?= $clients->reception_afterward ?>"  target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Susunan Resepsi</a>
                  <a href="<?= $clients->list_photo ?>"  target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">List Foto</a>
                  <a href="<?= site_url('clients/c_lihat/'. $clients->id_session) ?>" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Lihat Profile</a>
                    <a href="https://wa.me/<?= $clients->phone?>?text=Halo Kami dari Mantenbaru Organizer!%0A%0AKami%20ingin%20membagikan%20data%20profil%20pengantin%20yang%20sudah%20kami%20buat.%20Silakan%20klik%20link%20di%20bawah%20ini%20untuk%20melihat%20dan%20mengedit%20data%20sesuai%20kebutuhan.%20Anda%20bisa%20memperbarui%20informasi%20yang%20diperlukan%20agar%20data%20profil%20pengantin%20bisa%20sesuai%20dengan%20keinginan.%0A%0A<?= site_url('clients/c_lihat/'. $clients->id_session) ?>%0A%0AJika%20anda%20membutuhkan%20username%20dan%20password%20untuk%20mengedit%20data%20sesuai%20kebutuhan,%20jangan%20ragu%20untuk%20menghubungi%20kami.%20Terima%20kasih!" target="_blank" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Share Profile</a>
                </div>
              </form>

              <!-- ====== Table Three Start -->
              <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default  dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1" >
                <div class="max-w-full overflow-x-auto">
                  <table class="w-full table-auto">
                    <thead>
                      <tr class="bg-gray-2 text-left dark:bg-meta-4">
                        <th
                          class="min-w-[220px] px-4 py-4 font-medium text-black dark:text-white xl:pl-11"
                        >
                          Author
                        </th>
                        <th
                          class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                          Status
                        </th>
                        <th
                          class="min-w-[120px] px-4 py-4 font-medium text-black dark:text-white"
                        >
                          Time
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          Device
                        </th>
                        <th class="px-4 py-4 font-medium text-black dark:text-white">
                          IP
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1; foreach ($logactivity as $p): ?>
                      <tr>
                        <?php $company= $this->Crud_m->view_where('user', array('id_session'=> $p->log_activity_user_id))->row(); ?>
                        <?php $level= $this->Crud_m->view_where('user_level', array('user_level_id'=> $company->level))->row(); ?>
                        <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                          <h5 class="font-medium text-black dark:text-white"><?= $company->username ?></h5>
                          <p class="text-sm"><?= $level->user_level_nama ?></p>
                        </td>                        
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                          <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                            <?= $p->log_activity_status ?>
                          </p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                        <p class="text-black dark:text-white"><?= hari($p->log_activity_waktu) ?>, <?= tgl_indo($p->log_activity_waktu)?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p class="text-black dark:text-white"><?= $p->log_activity_platform ?></p>
                        </td>
                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                         <p class="text-black dark:text-white"><?= $p->log_activity_ip ?></p>
                        </td>
                      </tr>
                      <?php endforeach; ?>                            
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- ====== Table Three End -->
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
