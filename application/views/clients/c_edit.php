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
    <title>Edit Client <?= $clients->client_name ?></title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&family=Open+Sans:ital,wght@0,400;0,600;1,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'clients', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
    style="font-family: 'Josefin Sans', serif; color: #000;"
>
  <!-- ===== Preloader Start ===== -->
<div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid" style="border-color: #ed126b; border-top-color: transparent;">
    </div>
</div>
  <!-- ===== Preloader End ===== -->
      <!-- ===== Main Content Start ===== -->
      <main>
    <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9" style="font-family: 'Josefin Sans', serif; color: #000;">
        <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5" style="font-family: 'Josefin Sans', serif; color: #000;">
            <div class="flex justify-center mb-4">
                <h2 class="text-5xl font-extrabold" style="font-family: 'Josefin Sans', serif; color: #000;">
                    <span style="color: #000;">EDIT</span> <span style="color: #ed126b;">CLIENT</span>
                </h2>
            </div>
            <form action="<?= site_url('clients/c_update/' . $clients->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded" style="font-family: 'Josefin Sans', serif; color: #000;">

              <!-- Data Mempelai Wanita -->
              <h3 class="text-xl font-bold mt-6 mb-2">DATA PENGANTIN WANITA</h3>
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <label class="block mb-2" style="font-family: 'Josefin Sans', serif; color: #000;">Nama Lengkap</label>
                    <input type="text" name="f_bride_fname" value="<?= $clients->f_bride_fname ?>" placeholder="Nama Lengkap" class="w-full px-4 py-2 border rounded" required style="font-family: 'Josefin Sans', serif; color: #000;">
                </div>
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">Nama Panggilan</label>
                    <input type="text" name="f_bride_cname" value="<?= $clients->f_bride_cname ?>" placeholder="Nama Panggilan" class="w-full px-4 py-2 border rounded" style="color: #000;">
                </div>
            </div>
              
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">Anak Ke</label>
                    <select name="f_bride_nchild" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        <option value="">Pilih Anak Ke</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= $clients->f_bride_nchild == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">dari</label>
                    <select name="f_bride_hsibling" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        <option value="">Pilih Jumlah Saudara</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= $clients->f_bride_hsibling == $i ? 'selected' : '' ?>>
                                <?= $i ?> Bersaudara
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

              <label class="block mb-2" style="color: #000;">Ayah Mempelai Wanita</label>
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
            <div class="flex flex-col gap-4 mb-4">

                <div id="fayah-nama-ayah" class="<?= empty($clients->f_bride_freplacementname) ? '' : 'hidden' ?>">
                    
                </div>

                <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Ayah Kandung</label>
                            <input type="text" name="f_bride_fathername" value="<?= $clients->f_bride_fathername ?>" placeholder="Nama Lengkap Ayah Kandung" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                <!-- Nama Lengkap Pengganti Ayah & Nama Panggilan Pengganti Ayah -->
                <div id="fayah" class="<?= !empty($clients->f_bride_freplacementname) ? '' : 'hidden' ?>">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Pengganti Ayah</label>
                            <input type="text" name="f_bride_freplacementname" value="<?= $clients->f_bride_freplacementname ?>" placeholder="Nama Lengkap Pengganti Ayah" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Pengganti Ayah</label>
                            <input type="text" name="f_bride_freplacementcname" value="<?= $clients->f_bride_freplacementcname ?>" placeholder="Bapak/Papa/Ayah/Abi/Kaka" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>

                <!-- Nama Lengkap Ibu & Nama Panggilan Ibu -->
                <div id="fayah-original" class="<?= empty($clients->f_bride_freplacementname) ? '' : 'hidden' ?>">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Ayah Kandung</label>
                            <input type="text" name="f_bride_fathercname" value="<?= $clients->f_bride_fathercname ?>" placeholder="Bapak/Papa/Ayah/Abi/Kaka" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>
            </div>

              <label class="block mb-2" style="color: #000;">Ibu Mempelai Wanita</label>
              <div class="flex gap-4 mb-4">
                  <label>
                      <input type="radio" name="fibu_status" value="Masih Ada" id="masihAdaFibu" onclick="toggleReplacementFieldsibu('fibu', false)"
                      <?= empty($clients->f_bride_mreplacementname) ? 'checked' : '' ?>> Masih Ada
                  </label>
                  <label>
                      <input type="radio" name="fibu_status" value="Tidak Ada" id="tidakAdaFibu" onclick="toggleReplacementFieldsibu('fibu', true)"
                      <?= !empty($clients->f_bride_mreplacementname) ? 'checked' : '' ?>> Tidak Ada
                  </label>
              </div>

            <div class="flex flex-col gap-4 mb-4">

                <div id="fibu-nama-ibu" class="<?= empty($clients->f_bride_mreplacementname) ? '' : 'hidden' ?>">
                    
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Ibu Kandung</label>
                            <input type="text" name="f_bride_mothername" value="<?= $clients->f_bride_mothername ?>" placeholder="Nama Lengkap Ibu Kandung" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                <!-- Nama Lengkap Pengganti Ibu & Nama Panggilan Pengganti Ibu -->
                <div id="fibu" class="<?= !empty($clients->f_bride_mreplacementname) ? '' : 'hidden' ?>">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Pengganti Ibu</label>
                            <input type="text" name="f_bride_mreplacementname" value="<?= $clients->f_bride_mreplacementname ?>" placeholder="Nama Lengkap Pengganti Ibu" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Pengganti Ibu</label>
                            <input type="text" name="f_bride_mreplacementcname" value="<?= $clients->f_bride_mreplacementcname ?>" placeholder="Ibu/Mamah/Bunda/Umi/Kaka" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>

                <!-- Nama Lengkap Ibu & Nama Panggilan Ibu -->
                <div id="fibu-original" class="<?= empty($clients->f_bride_mreplacementname) ? '' : 'hidden' ?>">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Ibu Kandung</label>
                            <input type="text" name="f_bride_mothercname" value="<?= $clients->f_bride_mothercname ?>" placeholder="Ibu/Mamah/Bunda/Umi" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>
            </div>

              <label class="block mb-2" style="color: #000;">Nama Saudara Kandung</label>
              <textarea name="f_bride_sibling" placeholder="1. Contoh A&#10;2. Contoh B&#10;3. ..." class="w-full px-4 py-2 border rounded mb-4" style="color: #000;"><?= $clients->f_bride_sibling ?></textarea>

              <!-- Data Mempelai Pria -->
              <h3 class="text-xl font-bold mt-6 mb-2" style="color: #000;">DATA PENGANTIN PRIA</h3>
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">Nama Lengkap</label>
                    <input type="text" name="m_bride_fname" value="<?= $clients->m_bride_fname ?>" placeholder="Nama Lengkap" class="w-full px-4 py-2 border rounded" required style="color: #000;">
                </div>
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">Nama Panggilan</label>
                    <input type="text" name="m_bride_cname" value="<?= $clients->m_bride_cname ?>" placeholder="Nama Panggilan" class="w-full px-4 py-2 border rounded" style="color: #000;">
                </div>
            </div>
              
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">Anak Ke</label>
                    <select name="m_bride_nchild" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        <option value="">Pilih Anak Ke</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= $clients->m_bride_nchild == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">dari</label>
                    <select name="m_bride_hsibling" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        <option value="">Pilih Jumlah Saudara</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>" <?= $clients->m_bride_hsibling == $i ? 'selected' : '' ?>>
                                <?= $i ?> Bersaudara
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
              
              <label class="block mb-2" style="color: #000;">Ayah Mempelai Pria</label>
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

            <div class="flex flex-col gap-4 mb-4">


                <div id="mayah-nama-ayah" class="<?= empty($clients->m_bride_freplacementname) ? '' : 'hidden' ?>">
                    
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Ayah Kandung</label>
                            <input type="text" name="m_bride_fathername" value="<?= $clients->m_bride_fathername ?>" placeholder="Nama Lengkap Ayah Kandung" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                <!-- Nama Lengkap Pengganti Ayah & Nama Panggilan Pengganti Ayah -->
                <div id="mayah" class="<?= !empty($clients->m_bride_freplacementname) ? '' : 'hidden' ?>">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Pengganti Ayah</label>
                            <input type="text" name="m_bride_freplacementname" value="<?= $clients->m_bride_freplacementname ?>" placeholder="Nama Lengkap Pengganti Ayah" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Pengganti Ayah</label>
                            <input type="text" name="m_bride_freplacementcname" value="<?= $clients->f_bride_freplacementcname ?>" placeholder="Bapak/Papa/Ayah/Abi/Kaka" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>
                <!-- Nama Lengkap Ibu & Nama Panggilan Ibu -->
                <div id="mayah-original" class="<?= empty($clients->m_bride_freplacementname) ? '' : 'hidden' ?>">
                    
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Ayah Kandung</label>
                            <input type="text" name="m_bride_fathercname" value="<?= $clients->m_bride_fathercname ?>" placeholder="Bapak/Papa/Ayah/Abi/Kaka" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>
            
            </div>

              <label class="block mb-2" style="color: #000;">Ibu Mempelai Pria</label>
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

            <div class="flex flex-col gap-4 mb-4">
                <!-- Nama Lengkap Ibu & Nama Panggilan Ibu -->
                <div id="mibu-nama-ibu" class="<?= empty($clients->m_bride_mreplacementname) ? '' : 'hidden' ?>">
                </div>
                <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Ibu Kandung</label>
                            <input type="text" name="m_bride_mothername" value="<?= $clients->m_bride_mothername ?>" placeholder="Nama Lengkap Ibu Kandung" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                </div>

                <!-- Nama Lengkap Pengganti Ibu & Nama Panggilan Pengganti Ibu -->
                <div id="mibu" class="<?= !empty($clients->m_bride_mreplacementname) ? '' : 'hidden' ?>">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Lengkap Pengganti Ibu</label>
                            <input type="text" name="m_bride_mreplacementname" value="<?= $clients->m_bride_mreplacementname ?>" placeholder="Nama Lengkap Pengganti Ibu" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Pengganti Ibu</label>
                            <input type="text" name="m_bride_mreplacementcname" value="<?= $clients->m_bride_mreplacementcname ?>" placeholder="Ibu/Mamah/Bunda/Umi/Kaka" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>

                <!-- Nama Lengkap Ibu & Nama Panggilan Ibu -->
                <div id="mibu-original" class="<?= empty($clients->m_bride_mreplacementname) ? '' : 'hidden' ?>">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="w-full md:w-1/2">
                            <label class="block mb-2" style="color: #000;">Nama Panggilan Ibu Kandung</label>
                            <input type="text" name="m_bride_mothercname" value="<?= $clients->m_bride_mothercname ?>" placeholder="Ibu/Mamah/Bunda/Umi" class="w-full px-4 py-2 border rounded" style="color: #000;">
                        </div>
                    </div>
                </div>
            </div>

              <label class="block mb-2" style="color: #000;">Nama Saudara Kandung</label>
              <textarea name="m_bride_sibling" placeholder="1. Contoh A&#10;2. Contoh B&#10;3. ..." class="w-full px-4 py-2 border rounded mb-4" style="color: #000;"><?= $clients->m_bride_sibling ?></textarea>

              <!-- Detail Pernikahan -->
              <h3 class="text-xl font-bold mt-6 mb-2" style="color: #000;">DETAIL PERNIKAHAN</h3>
            <div class="flex flex-col md:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">Tanggal Pernikahan</label>
                    <input type="date" name="wedding_date" value="<?= $clients->wedding_date ?>" placeholder="Tanggal Pernikahan" class="w-full px-4 py-2 border rounded" readonly style="color: #000;">
                </div>
                <div class="flex-1">
                    <label class="block mb-2" style="color: #000;">Lokasi Acara</label>
                    <input type="text" name="location" value="<?= $clients->location ?>" placeholder="Lokasi Acara" class="w-full px-4 py-2 border rounded" readonly style="color: #000;">
                </div>
            </div>

            <?php if ($islam) : ?>
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Mahar</label>
                        <input type="text" name="mahr" value="<?= $clients->mahr ?>" placeholder="Mahar" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Simbolis Seserahan</label>
                        <select name="handover" class="w-full px-4 py-2 border rounded" style="color: #000;">
                            <option value="">Pilih Simbolis Seserahan</option>
                            <option value="Seperangkat Alat Solat" <?= $clients->handover == 'Seperangkat Alat Solat' ? 'selected' : '' ?>>Seperangkat Alat Solat</option>
                            <option value="Make Up" <?= $clients->handover == 'Make Up' ? 'selected' : '' ?>>Make Up</option>
                            <option value="Lainnya" <?= $clients->handover == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Koordinator Keluarga Wanita</label>
                        <input type="text" name="female_coor" value="<?= $clients->female_coor ?>" placeholder="Koordinator Keluarga Wanita" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Koordinator Keluarga Pria</label>
                        <input type="text" name="male_coor" value="<?= $clients->male_coor ?>" placeholder="Koordinator Keluarga Pria" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Jubir Keluarga Wanita</label>
                        <input type="text" name="f_spokesman" value="<?= $clients->f_spokesman ?>" placeholder="Jubir Keluarga Wanita" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Jubir Keluarga Pria</label>
                        <input type="text" name="m_spokesman" value="<?= $clients->m_spokesman ?>" placeholder="Jubir Keluarga Pria" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Penghulu</label>
                        <input type="text" name="wedding_officiant" value="<?= $clients->wedding_officiant ?>" placeholder="Bapak A S.Ag (KUA Bogor)" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Wali</label>
                        <input type="text" name="guardian" value="<?= $clients->guardian ?>" placeholder="Wali" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Saksi Calon Pengantin Wanita</label>
                        <input type="text" name="f_witness" value="<?= $clients->f_witness ?>" placeholder="Saksi Calon Pengantin Wanita" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Saksi Calon Pengantin Pria</label>
                        <input type="text" name="m_witness" value="<?= $clients->m_witness ?>" placeholder="Saksi Calon Pengantin Pria" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Qoriah/Saritilawah</label>
                        <input type="text" name="qori" value="<?= $clients->qori ?>" placeholder="Qoriah/Saritilawah" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Nasihat Pernikahan</label>
                        <input type="text" name="advice_doa" value="<?= $clients->advice_doa ?>" placeholder="Nasihat Pernikahan" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Pengapit Calon Pengantin Wanita dari Keluarga Wanita</label>
                        <input type="text" name="clamp" value="<?= $clients->clamp ?>" placeholder="Pengapit Calon Pengantin Wanita dari Keluarga Wanita" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Pembawa Nampan Kalung Bunga Melati dari Keluarga Wanita</label>
                        <input type="text" name="jasmine_carrier" value="<?= $clients->jasmine_carrier ?>" placeholder="Pembawa Nampan Kalung Bunga Melati dari Keluarga Wanita" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Pembawa Mas Kawin/Mahar dari Keluarga Pria</label>
                        <input type="text" name="mahr_carrier" value="<?= $clients->mahr_carrier ?>" placeholder="Pembawa Mas Kawin/Mahar dari Keluarga Pria" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Pembawa Cincin Kawin dari Keluarga Pria</label>
                        <input type="text" name="ring_carrier" value="<?= $clients->ring_carrier ?>" placeholder="Pembawa Cincin Kawin dari Keluarga Pria" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>
            <?php else : ?>
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Koordinator Keluarga Wanita</label>
                        <input type="text" name="female_coor" value="<?= $clients->female_coor ?>" placeholder="Koordinator Keluarga Wanita" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Koordinator Keluarga Pria</label>
                        <input type="text" name="male_coor" value="<?= $clients->male_coor ?>" placeholder="Koordinator Keluarga Pria" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Pendeta</label>
                        <input type="text" name="pastor" value="<?= $clients->pastor ?>" placeholder="Pendeta" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Gereja</label>
                        <input type="text" name="church" value="<?= $clients->church ?>" placeholder="Gereja" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Pemimpin Doa</label>
                        <input type="text" name="prayer" value="<?= $clients->prayer ?>" placeholder="Pemimpin Doa" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                    <div class="flex-1">
                        <label class="block mb-2" style="color: #000;">Sambutan Pernikahan</label>
                        <input type="text" name="wedding_speech" value="<?= $clients->wedding_speech ?>" placeholder="Sambutan Pernikahan" class="w-full px-4 py-2 border rounded" style="color: #000;">
                    </div>
                </div>
            <?php endif; ?>

            <div class="flex flex-col sm:flex-row justify-end items-center gap-2">
                <button type="submit" class="flex items-center justify-center gap-2 bg-[#ed126b] text-white px-4 py-2 rounded w-full hover:bg-pink-700 sm:w-32 text-center relative overflow-hidden group border-2 border-[#ed126b]" style="font-family: 'Josefin Sans', serif;">
                    <span class="absolute left-0 top-0 h-full w-0 group-hover:w-full bg-white transition-all duration-500 z-0"></span>
                    <span class="flex items-center gap-2 relative z-10 transition-colors duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Update
                    </span>
                </button>
            </div>
            <style>
                button[type="submit"].group {
                    position: relative;
                    overflow: hidden;
                }
                button[type="submit"].group span.absolute.left-0 {
                    transition: width 0.5s;
                    z-index: 1;
                }
                button[type="submit"].group:hover span.absolute.left-0 {
                    width: 100% !important;
                }
                button[type="submit"].group span.flex.z-10 {
                    position: relative;
                    z-index: 10;
                }
                button[type="submit"].group:hover span.flex.z-10 {
                    color: #ed126b !important;
                }
                button[type="submit"].group {
                    border: 2px solid #ed126b !important;
                }
                button[type="submit"].group:hover {
                    border: 2px solid #ed126b !important;
                }
            </style>
              </form>
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
</body>
</html>
