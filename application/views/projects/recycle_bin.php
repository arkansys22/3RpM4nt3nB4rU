<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Bin Projects</title>
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
                        <h1 class="text-2xl font-bold mb-4">Recycle Bin Projects</h1>

                    <!-- Tombol Kembali -->
                    <a href="<?= site_url('projects') ?>" class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">Kembali</a>

                    <table class="mt-4 w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                            <th class="border px-4 py-2">Nama Projects</th>
                            <th class="border px-4 py-2">Tanggal Pernikahan</th>
                            <th class="border px-4 py-2">Value</th>
                            <th class="border px-4 py-2">Detail</th>
                            <th class="border px-4 py-2">Agama</th>
                            <th class="border px-4 py-2">Lokasi</th>
                            <th class="border px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $p): ?>
                                <tr class="text-center">
                                <td class="border px-4 py-2"><?= $p->project_name ?></td>
                                <td class="border px-4 py-2"><?= $p->event_date ?></td>
                                <td class="border px-4 py-2"><?= "Rp " . number_format($p->value, 0, ',', '.') ?></td>
                                <td class="border px-4 py-2"><?= $p->detail ?></td>
                                <td class="border px-4 py-2"><?= $p->religion ?></td>
                                <td class="border px-4 py-2"><?= $p->location ?></td>
                                    <td class="border px-4 py-2">
                                        <a href="<?= site_url('projects/restore/'.$p->id_session) ?>" class="bg-green-500 text-white px-2 py-1 rounded whitespace-nowrap mb-2 block">Restore</a>
                                        <a href="<?= site_url('projects/permanent_delete/'.$p->id_session) ?>" class="bg-red-500 text-white px-2 py-1 rounded whitespace-nowrap block" onclick="return confirm('Hapus secara permanen?')">Delete Permanen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
