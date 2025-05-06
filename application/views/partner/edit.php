<?php date_default_timezone_set('Asia/Jakarta'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Partner <?= $partner->partner_name ?></title>
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
                        <h2 class="text-2xl font-bold mb-4">Edit Partner <?= $partner->partner_name ?></h2>
                    <form action="<?= base_url('partner/update/' . $partner->id_session) ?>" method="post" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded">
                            <label class="block mb-2">Nama Partner</label>
                            <input type="text" name="partner_name" value="<?= $partner->partner_name ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                            <label class="block mb-2">Jenis</label>
                            <select name="type" class="w-full px-4 py-2 border rounded mb-4">
                                <option value="Venue" <?= $partner->type == 'Venue' ? 'selected' : '' ?>>Venue</option>
                                <option value="MC Akad" <?= $partner->type == 'MC Akad' ? 'selected' : '' ?>>MC Akad</option>
                                <option value="MC Pemberkatan" <?= $partner->type == 'MC Pemberkatan' ? 'selected' : '' ?>>MC Pemberkatan</option>
                                <option value="MC Resepsi" <?= $partner->type == 'MC Resepsi' ? 'selected' : '' ?>>MC Resepsi</option>
                                <option value="Wedding Organizer" <?= $partner->type == 'Wedding Organizer' ? 'selected' : '' ?>>WO</option>
                                <option value="MUA" <?= $partner->type == 'MUA' ? 'selected' : '' ?>>MUA</option>
                                <option value="Perlengkapan Catering" <?= $partner->type == 'Perlengkapan Catering' ? 'selected' : '' ?>>Perlengkapan Catering</option>
                                <option value="Catering" <?= $partner->type == 'Catering' ? 'selected' : '' ?>>Catering</option>
                                <option value="Dokumentasi" <?= $partner->type == 'Dokumentasi' ? 'selected' : '' ?>>Dokumentasi</option>
                                <option value="Dekorasi" <?= $partner->type == 'Dekorasi' ? 'selected' : '' ?>>Dekorasi</option>
                                <option value="Entertainment" <?= $partner->type == 'Entertainment' ? 'selected' : '' ?>>Entertainment</option>
                            </select>

                            <label class="block mb-2">Social Media</label>
                            <input type="text" name="social_media" value="<?= $partner->social_media ?>" class="w-full px-4 py-2 border rounded mb-4">

                            <label class="block mb-2">Nama Kontak</label>
                            <input type="text" name="contact_name" value="<?= $partner->contact_name ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                            <label class="block mb-2">No HP</label>
                            <input type="text" name="phone" value="<?= $partner->phone ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                            <label class="block mb-2">Logo</label>
                            <?php if ($partner->logo): ?>
                                <img src="<?= base_url('uploads/partner/' . $partner->logo) ?>" alt="Logo" class="block w-full mb-4">
                            <?php endif; ?>
                            <input type="file" name="logo" class="w-full px-4 py-2 border rounded mb-4">

                            <div class="flex flex-col sm:flex-row justify-end">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
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
