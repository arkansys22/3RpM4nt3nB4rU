<?php date_default_timezone_set('Asia/Jakarta'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Partner</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'partner', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
                        <h2 class="text-2xl font-bold mb-4">Tambah Partner</h2>
                        <form action="<?= base_url('partner/store') ?>" method="post" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded">

                            <label class="block mb-2">Nama Partner</label>
                            <input type="text" name="partner_name" class="w-full px-4 py-2 border rounded mb-4" required>

                            <label class="block mb-2">Jenis</label>
                            <select name="type" class="w-full px-4 py-2 border rounded mb-4">
                                <option value="">-</option>
                                <option value="Venue">Venue</option>
                                <option value="MC Akad">MC Akad</option>
                                <option value="MC Pemberkatan">MC Pemberkatan</option>
                                <option value="MC Resepsi">MC Resepsi</option>
                                <option value="MC Adat Sunda">MC Adat Sunda</option>
                                <option value="MC Adat Jawa">MC Adat Jawa</option>
                                <option value="Wedding Organizer">Wedding Organizer</option>
                                <option value="MUA">MUA</option>
                                <option value="Attire">Attire</option>
                                <option value="Perlengkapan Catering">Perlengkapan Catering</option>
                                <option value="Penyewaan Peralatan">Penyewaan Peralatan</option>
                                <option value="Catering">Catering</option>
                                <option value="Dokumentasi">Dokumentasi</option>
                                <option value="Dekorasi">Dekorasi</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Pendukung Pernikahan Lainnya">Pendukung Pernikahan Lainnya</option>

                            </select>

                            <label class="block mb-2">Social Media</label>
                            <input type="text" name="social_media" class="w-full px-4 py-2 border rounded mb-4">

                            <label class="block mb-2">Nama Kontak</label>
                            <input type="text" name="contact_name" class="w-full px-4 py-2 border rounded mb-4" required>

                            <label class="block mb-2">No HP</label>
                            <input type="text" name="phone" class="w-full px-4 py-2 border rounded mb-4" required>

                            <label class="block mb-2">Logo</label>
                            <input type="file" name="logo" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                            <div class="flex flex-col sm:flex-row justify-end">
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                                <a href="<?= base_url('partner') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
