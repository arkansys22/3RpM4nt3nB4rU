<?php
// Ambil agama dari tabel project berdasarkan id_session klien
$project = $this->db->get_where('project', ['id_session' => $clients->id_session])->row();
$religion = $project->religion ?? ''; // Pastikan tidak error jika religion kosong

$islam = strtolower($religion) === 'islam'; // Cek apakah agama Islam
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
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
              <h2 class="text-2xl font-bold mb-4">Edit Client <?= $clients->client_name ?></h2>
              <form action="<?= site_url('clients/update/' . $clients->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
              <h3 class="text-lg font-bold mt-6 mb-2">Data Klien</h3>

              <label class="block mb-2">Nama Klien</label>
              <input type="text" name="client_name" value="<?= $clients->client_name ?>" placeholder="Nama Klien" class="w-full px-4 py-2 border rounded mb-4" required>

              <label class="block mb-2">Email</label>
              <input type="email" name="email" value="<?= $clients->email ?>" placeholder="Email" class="w-full px-4 py-2 border rounded mb-4" required>

              <label class="block mb-2">No HP</label>
              <input type="text" name="phone" value="<?= $clients->phone ?>" placeholder="No HP" class="w-full px-4 py-2 border rounded mb-4" required>

              <!-- Susunan Acara -->
              <h3 class="text-lg font-bold mt-6 mb-2">Susunan Acara</h3>
              <?php if ($islam) : ?>
                <label class="block mb-2">Susunan Acara Akad</label>
                <input type="text" name="wedding_ceremony" value="<?= $clients->wedding_ceremony ?>" placeholder="Link GDrive" class="w-full px-4 py-2 border rounded mb-4">
              <?php else : ?>
                <label class="block mb-2">Susunan Acara Pemberkatan</label>
                <input type="text" name="wedding_ceremony" value="<?= $clients->wedding_ceremony ?>" placeholder="Link GDrive" class="w-full px-4 py-2 border rounded mb-4">
              <?php endif; ?>
              <label class="block mb-2">Susunan Acara Resepsi</label>
              <input type="text" name="reception_afterward" value="<?= $clients->reception_afterward ?>" placeholder="Link GDrive" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">List Foto</label>
              <input type="text" name="list_photo" value="<?= $clients->list_photo ?>" placeholder="Link GDrive" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Jam Stand by</label>
              <input type="time" name="stand_by" value="<?= $clients->stand_by ?>" placeholder="Jam Stand by" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Seragam</label>
              <input type="text" name="uniform" value="<?= $clients->uniform ?>" placeholder="Seragam" class="w-full px-4 py-2 border rounded mb-4">

              <!-- Data Mempelai Wanita -->
              <h3 class="text-lg font-bold mt-6 mb-2">Data Mempelai Wanita</h3>
              <label class="block mb-2">Nama Lengkap</label>
              <input type="text" name="f_bride_fname" value="<?= $clients->f_bride_fname ?>" placeholder="Nama Lengkap" class="w-full px-4 py-2 border rounded mb-4" required>
              
              <label class="block mb-2">Nama Panggilan</label>
              <input type="text" name="f_bride_cname" value="<?= $clients->f_bride_cname ?>" placeholder="Nama Panggilan" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Anak Keberapa (anak ke 1/2/3/...)</label>
              <input type="number" name="f_bride_nchild" value="<?= $clients->f_bride_nchild ?>" placeholder="1/2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Berapa Bersaudara (2/3/... bersaudara)</label>
              <input type="number" name="f_bride_hsibling" value="<?= $clients->f_bride_hsibling ?>" placeholder="2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Ayah Mempelai Wanita</label>
              <div class="flex gap-4 mb-4">
                  <label>
                      <input type="radio" name="fayah_status" value="Masih Ada" id="masihAdaFayah" onclick="toggleReplacementFields('fayah', false)"
                      <?= empty($clients->f_bride_freplacementname) ? 'checked' : '' ?>> Masih Ada
                  </label>
                  <label>
                      <input type="radio" name="fayah_status" value="Tidak Ada" id="tidakAdaFayah" onclick="toggleReplacementFields('fayah', true)"
                      <?= !empty($clients->f_bride_freplacementname) ? 'checked' : '' ?>> Tidak Ada
                  </label>
              </div>

              <div id="fayah-nama-ayah">
                  <label class="block mb-2">Nama Lengkap Ayah</label>
                  <input type="text" name="f_bride_fathername" value="<?= $clients->f_bride_fathername ?>" placeholder="Nama Lengkap Ayah" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <div id="fayah" class="<?= !empty($clients->f_bride_freplacementname) ? '' : 'hidden' ?>">
                  <label class="block mb-2">Nama Lengkap Pengganti Ayah</label>
                  <input type="text" name="f_bride_freplacementname" value="<?= $clients->f_bride_freplacementname ?>" placeholder="Nama Lengkap Pengganti Ayah" class="w-full px-4 py-2 border rounded mb-4">

                  <label class="block mb-2">Nama Panggilan Pengganti Ayah</label>
                  <input type="text" name="f_bride_freplacementcname" value="<?= $clients->f_bride_freplacementcname ?>" placeholder="Bapak/Papa/Ayah/Abi/Kaka" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <div id="fayah-original" class="<?= empty($clients->f_bride_freplacementname) ? '' : 'hidden' ?>">
                  <label class="block mb-2">Nama Panggilan Ayah</label>
                  <input type="text" name="f_bride_fathercname" value="<?= $clients->f_bride_fathercname ?>" placeholder="Bapak/Papa/Ayah/Abi" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <label class="block mb-2">Ibu Mempelai Wanita</label>
              <div class="flex gap-4 mb-4">
                  <label>
                      <input type="radio" name="fibu_status" value="Masih Ada" id="masihAdaFibu" onclick="toggleReplacementFields('fibu', false)"
                      <?= empty($clients->f_bride_mreplacementname) ? 'checked' : '' ?>> Masih Ada
                  </label>
                  <label>
                      <input type="radio" name="fibu_status" value="Tidak Ada" id="tidakAdaFibu" onclick="toggleReplacementFields('fibu', true)"
                      <?= !empty($clients->f_bride_mreplacementname) ? 'checked' : '' ?>> Tidak Ada
                  </label>
              </div>

              <div id="fibu-nama-ibu">
                  <label class="block mb-2">Nama Lengkap Ibu</label>
                  <input type="text" name="f_bride_mothername" value="<?= $clients->f_bride_mothername ?>" placeholder="Nama Lengkap Ibu" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <div id="fibu" class="<?= !empty($clients->f_bride_mreplacementname) ? '' : 'hidden' ?>">
                  <label class="block mb-2">Nama Lengkap Pengganti Ibu</label>
                  <input type="text" name="f_bride_mreplacementname" value="<?= $clients->f_bride_mreplacementname ?>" placeholder="Nama Lengkap Pengganti Ibu" class="w-full px-4 py-2 border rounded mb-4">

                  <label class="block mb-2">Nama Panggilan Pengganti Ibu</label>
                  <input type="text" name="f_bride_mreplacementcname" value="<?= $clients->f_bride_mreplacementcname ?>" placeholder="Ibu/Mamah/Bunda/Umi/Kaka" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <div id="fibu-original" class="<?= empty($clients->f_bride_mreplacementname) ? '' : 'hidden' ?>">
                  <label class="block mb-2">Nama Panggilan Ibu</label>
                  <input type="text" name="f_bride_mothercname" value="<?= $clients->f_bride_mothercname ?>" placeholder="Ibu/Mamah/Bunda/Umi" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <label class="block mb-2">Nama Saudara Kandung</label>
              <textarea name="f_bride_sibling" placeholder="1. Contoh A&#10;2. Contoh B&#10;3. ..." class="w-full px-4 py-2 border rounded mb-4"><?= $clients->f_bride_sibling ?></textarea>

              <!-- Data Mempelai Pria -->
              <h3 class="text-lg font-bold mt-6 mb-2">Data Mempelai Pria</h3>
              <label class="block mb-2">Nama Lengkap</label>
              <input type="text" name="m_bride_fname" value="<?= $clients->m_bride_fname ?>" placeholder="Nama Lengkap" class="w-full px-4 py-2 border rounded mb-4" required>
              
              <label class="block mb-2">Nama Panggilan</label>
              <input type="text" name="m_bride_cname" value="<?= $clients->m_bride_cname ?>" placeholder="Nama Panggilan" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Anak Keberapa (anak ke 1/2/3/...)</label>
              <input type="number" name="m_bride_nchild" value="<?= $clients->m_bride_nchild ?>" placeholder="1/2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Berapa Bersaudara (2/3/... bersaudara)</label>
              <input type="number" name="m_bride_hsibling" value="<?= $clients->m_bride_hsibling ?>" placeholder="2/3/..." class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Ayah Mempelai Pria</label>
              <div class="flex gap-4 mb-4">
                  <label>
                      <input type="radio" name="mayah_status" value="Masih Ada" id="masihAdaMayah" onclick="toggleReplacementFields('mayah', false)"
                      <?= empty($clients->m_bride_freplacementname) ? 'checked' : '' ?>> Masih Ada
                  </label>
                  <label>
                      <input type="radio" name="mayah_status" value="Tidak Ada" id="tidakAdaMayah" onclick="toggleReplacementFields('mayah', true)"
                      <?= !empty($clients->m_bride_freplacementname) ? 'checked' : '' ?>> Tidak Ada
                  </label>
              </div>

              <div id="mayah-nama-ayah" >
                  <label class="block mb-2">Nama Lengkap Ayah</label>
                  <input type="text" name="m_bride_fathername" value="<?= $clients->m_bride_fathername ?>" placeholder="Nama Lengkap Ayah" class="w-full px-4 py-2 border rounded mb-4">
              </div>
              <div id="mayah" class="<?= !empty($clients->m_bride_freplacementname) ? '' : 'hidden' ?>">
              <label class="block mb-2">Nama Lengkap Pengganti Ayah</label>
              <input type="text" name="m_bride_freplacementname" value="<?= $clients->m_bride_freplacementname ?>" placeholder="Nama Lengkap Pengganti Ayah" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Nama Panggilan Pengganti Ayah</label>
              <input type="text" name="m_bride_freplacementcname" value="<?= $clients->m_bride_freplacementcname ?>" placeholder="Bapak/Papa/Ayah/Abi/Kaka" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <div id="mayah-original" class="<?= empty($clients->m_bride_freplacementname) ? '' : 'hidden' ?>">
                  <label class="block mb-2">Nama Panggilan Ayah</label>
                  <input type="text" name="m_bride_fathercname" value="<?= $clients->m_bride_fathercname ?>" placeholder="Bapak/Papa/Ayah/Abi" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <label class="block mb-2">Ibu Mempelai Pria</label>
              <div class="flex gap-4 mb-4">
                  <label>
                      <input type="radio" name="mibu_status" value="Masih Ada" id="masihAdaMibu" onclick="toggleReplacementFields('mibu', false)"
                      <?= empty($clients->m_bride_mreplacementname) ? 'checked' : '' ?>> Masih Ada
                  </label>
                  <label>
                      <input type="radio" name="mibu_status" value="Tidak Ada" id="tidakAdaMibu" onclick="toggleReplacementFields('mibu', true)"
                      <?= !empty($clients->m_bride_mreplacementname) ? 'checked' : '' ?>> Tidak Ada
                  </label>
              </div>



              <div id="mibu-nama-ibu">
                  <label class="block mb-2">Nama Lengkap Ibu</label>
                  <input type="text" name="m_bride_mothername" value="<?= $clients->m_bride_mothername ?>" placeholder="Nama Lengkap Ibu" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <div id="mibu" class="<?= !empty($clients->m_bride_mreplacementname) ? '' : 'hidden' ?>">
                  <label class="block mb-2">Nama Lengkap Pengganti Ibu</label>
                  <input type="text" name="m_bride_mreplacementname" value="<?= $clients->m_bride_mreplacementname ?>" placeholder="Nama Lengkap Pengganti Ibu" class="w-full px-4 py-2 border rounded mb-4">

                  <label class="block mb-2">Nama Panggilan Pengganti Ibu</label>
                  <input type="text" name="m_bride_mreplacementcname" value="<?= $clients->m_bride_mreplacementcname ?>" placeholder="Ibu/Mamah/Bunda/Umi/Kaka" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <div id="mibu-original" class="<?= empty($clients->m_bride_mreplacementname) ? '' : 'hidden' ?>">
                  <label class="block mb-2">Nama Panggilan Ibu</label>
                  <input type="text" name="m_bride_mothercname" value="<?= $clients->m_bride_mothercname ?>" placeholder="Ibu/Mamah/Bunda/Umi" class="w-full px-4 py-2 border rounded mb-4">
              </div>

              <label class="block mb-2">Nama Saudara Kandung</label>
              <textarea name="m_bride_sibling" placeholder="1. Contoh A&#10;2. Contoh B&#10;3. ..." class="w-full px-4 py-2 border rounded mb-4"><?= $clients->m_bride_sibling ?></textarea>

              <!-- Detail Pernikahan -->
              <h3 class="text-lg font-bold mt-6 mb-2">Detail Pernikahan</h3>
              <label class="block mb-2">Tanggal Pernikahan</label>
              <input type="date" name="wedding_date" value="<?= $clients->wedding_date ?>" placeholder="Tanggal Pernikahan" class="w-full px-4 py-2 border rounded mb-4" readonly>

              <label class="block mb-2">Lokasi Acara</label>
              <input type="text" name="location" value="<?= $clients->location ?>" placeholder="Lokasi Acara" class="w-full px-4 py-2 border rounded mb-4" readonly>

              <label class="block mb-2">Maps</label>
              <input type="text" name="maps" value="<?= $clients->maps ?>" placeholder="Maps" class="w-full px-4 py-2 border rounded mb-4">

              <?php if ($islam) : ?>
              <label class="block mb-2">Mahar</label>
              <input type="text" name="mahr" value="<?= $clients->mahr ?>" placeholder="Mahar" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Simbolis Seserahan</label>
              <select type="text" name="handover" value="<?= $clients->handover ?>" class="w-full px-4 py-2 border rounded mb-4">
              <option value="">Pilih Simbolis Seserahan</option>
              <option value="Seperangkat Alat Solat" <?= $clients->handover == 'Seperangkat Alat Solat' ? 'selected' : '' ?>>Seperangkat Alat Solat</option>
              <option value="Make Up" <?= $clients->handover == 'Make Up' ? 'selected' : '' ?>>Make Up</option>
              <option value="Lainnya" <?= $clients->handover == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
              </select>

              <!-- Petugas dan Koordinator Akad Nikah -->
              <h3 class="text-lg font-bold mt-6 mb-2">Petugas dan Koordinator</h3>
              <label class="block mb-2">Koordinator Keluarga Wanita</label>
              <input type="text" name="female_coor" value="<?= $clients->female_coor ?>" placeholder="Koordinator Keluarga Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Koordinator Keluarga Pria</label>
              <input type="text" name="male_coor" value="<?= $clients->male_coor ?>" placeholder="Koordinator Keluarga Pria" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Jubir Keluarga Wanita</label>
              <input type="text" name="f_spokesman" value="<?= $clients->f_spokesman ?>" placeholder="Jubir Keluarga Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Jubir Keluarga Pria</label>
              <input type="text" name="m_spokesman" value="<?= $clients->m_spokesman ?>" placeholder="Jubir Keluarga Pria" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Penghulu</label>
              <input type="text" name="wedding_officiant" value="<?= $clients->wedding_officiant ?>" placeholder="Bapak A S.Ag (KUA Bogor)" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Wali</label>
              <input type="text" name="guardian" value="<?= $clients->guardian ?>" placeholder="Wali" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Saksi Calon Pengantin Wanita</label>
              <input type="text" name="f_witness" value="<?= $clients->f_witness ?>" placeholder="Saksi Calon Pengantin Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Saksi Calon Pengantin Pria</label>
              <input type="text" name="m_witness" value="<?= $clients->m_witness ?>" placeholder="Saksi Calon Pengantin Pria" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Qoriah/Saritilawah</label>
              <input type="text" name="qori" value="<?= $clients->qori ?>" placeholder="Qoriah/Saritilawah" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Nasihat Pernikahan</label>
              <input type="text" name="advice_doa" value="<?= $clients->advice_doa ?>" placeholder="Nasihat Pernikahan" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pengapit Calon Pengantin Wanita dari Keluarga Wanita</label>
              <input type="text" name="clamp" value="<?= $clients->clamp ?>" placeholder="Pengapit Calon Pengantin Wanita dari Keluarga Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pembawa Nampan Kalung Bunga Melati dari Keluarga Wanita</label>
              <input type="text" name="jasmine_carrier" value="<?= $clients->jasmine_carrier ?>" placeholder="Pembawa Nampan Kalung Bunga Melati dari Keluarga Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pembawa Mas Kawin/Mahar dari Keluarga Pria</label>
              <input type="text" name="mahr_carrier" value="<?= $clients->mahr_carrier ?>" placeholder="Pembawa Mas Kawin/Mahar dari Keluarga Pria" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pembawa Cincin Kawin dari Keluarga Pria</label>
              <input type="text" name="ring_carrier" value="<?= $clients->ring_carrier ?>" placeholder="Pembawa Cincin Kawin dari Keluarga Pria" class="w-full px-4 py-2 border rounded mb-4">

              <!-- Petugas dan Koordinator Resepsi -->
              <?php else : ?>
              <h3 class="text-lg font-bold mt-6 mb-2">Petugas dan Koordinator Resepsi</h3>
              <label class="block mb-2">Koordinator Keluarga Wanita</label>
              <input type="text" name="female_coor" value="<?= $clients->female_coor ?>" placeholder="Koordinator Keluarga Wanita" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Koordinator Keluarga Pria</label>
              <input type="text" name="male_coor" value="<?= $clients->male_coor ?>" placeholder="Koordinator Keluarga Pria" class="w-full px-4 py-2 border rounded mb-4">

              <label class="block mb-2">Pendeta</label>
              <input type="text" name="pastor" value="<?= $clients->pastor ?>" placeholder="Pendeta" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Gereja</label>
              <input type="text" name="church" value="<?= $clients->church ?>" placeholder="Gereja" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Pemimpin Doa</label>
              <input type="text" name="prayer" value="<?= $clients->prayer ?>" placeholder="Pemimpin Doa" class="w-full px-4 py-2 border rounded mb-4">
              
              <label class="block mb-2">Sambutan Pernikahan</label>
              <input type="text" name="wedding_speech" value="<?= $clients->wedding_speech ?>" placeholder="Sambutan Pernikahan" class="w-full px-4 py-2 border rounded mb-4">
              <?php endif; ?>

              <div class="flex flex-col sm:flex-row justify-end">
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('clients/lihat/' . $clients->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
</script>
</body>
</html>
