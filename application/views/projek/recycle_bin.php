<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Bin</title>
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'recycle_bin', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
                    <h1 class="text-2xl font-bold mb-4">Recycle Bin</h1>

                    <!-- Tombol Kembali -->
                    <a href="<?= site_url('projek') ?>" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Kembali</a>

                    <table class="mt-4 w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">Nama Projek</th>
                                <th class="border px-4 py-2">Author</th>
                                <th class="border px-4 py-2">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projek as $p): ?>
                                <tr class="text-center">
                                    <td class="border px-4 py-2"><?= $p->id ?></td>
                                    <td class="border px-4 py-2"><?= $p->nama_projek ?></td>
                                    <td class="border px-4 py-2"><?= $p->author ?></td>
                                    <td class="border px-4 py-2">
                                        <a href="<?= site_url('projek/restore/'.$p->id) ?>" class="bg-green-500 text-white px-2 py-1 rounded">Restore</a>
                                        <a href="<?= site_url('projek/permanently_delete/'.$p->id) ?>" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Hapus secara permanen?')">Delete Permanen</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
