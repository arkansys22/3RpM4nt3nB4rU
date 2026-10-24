<?php date_default_timezone_set('Asia/Jakarta'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Partner</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
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
                        <h1 class="text-2xl font-bold mb-4">Daftar Partner</h1>
                        <div class="flex justify-between mb-4">
                            <a href="<?= base_url('partner/create') ?>">
                                <button class="bg-blue-500 text-white p-3 rounded-md hover:bg-blue-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"></path>
                                    </svg>
                                </button>
                            </a>
                            <a href="<?= base_url('partner/recycle_bin') ?>">
                                <button class="bg-red-500 text-white p-3 rounded-md hover:bg-red-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-2 14H7L5 7M12 4v-2m4 2h-8m5 2l1-1m3 1l-1-1m0 0h6l-1 2m-7-5h2m6 5H5"></path>
                                    </svg>
                                </button>
                            </a>
                        </div>

                        <?php if ($this->session->flashdata('Success')): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= $this->session->flashdata('Success') ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('Error')): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= htmlspecialchars($this->session->flashdata('Error')) ?></div>
                        <?php endif; ?>

                        <!-- ====== Kelola Jenis / Kategori Partner ===== -->
                        <details id="kelola-jenis" class="mb-4 rounded-md border border-stroke dark:border-strokedark" <?= ($this->session->flashdata('Success') || $this->session->flashdata('Error')) ? 'open' : '' ?>>
                            <summary class="flex cursor-pointer select-none items-center justify-between px-4 py-3 font-semibold">
                                <span>Kelola Jenis Partner <span class="font-normal text-body dark:text-bodydark">(<?= count($jenis_list) ?> jenis)</span></span>
                                <span class="text-sm font-normal text-primary">Tambah / edit</span>
                            </summary>
                            <div class="px-4 pb-4">
                                <form action="<?= site_url('partner/jenis/store') ?>" method="post" class="mb-4 flex flex-col gap-2 sm:flex-row">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    <input type="text" name="nama" required maxlength="255" placeholder="Nama jenis baru, contoh: Souvenir"
                                           class="w-full flex-1 rounded border px-4 py-2">
                                    <button type="submit" class="rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600">+ Tambah Jenis</button>
                                </form>

                                <div class="divide-y divide-stroke dark:divide-strokedark">
                                    <?php foreach ($jenis_list as $j): ?>
                                        <?php if ($j->id === null) { continue; } ?>
                                        <div class="flex flex-col gap-2 py-2 sm:flex-row sm:items-center">
                                            <form action="<?= site_url('partner/jenis/update/' . $j->id) ?>" method="post" class="flex flex-1 gap-2">
                                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                                <input type="text" name="nama" required maxlength="255" value="<?= htmlspecialchars($j->nama) ?>"
                                                       class="w-full flex-1 rounded border px-3 py-1.5">
                                                <button type="submit" class="rounded bg-blue-500 px-3 py-1.5 text-sm text-white hover:bg-blue-600">Simpan</button>
                                            </form>
                                            <div class="flex items-center gap-2">
                                                <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-medium text-gray-600"><?= (int) $j->jumlah ?> partner</span>
                                                <?php if ((int) $j->jumlah === 0): ?>
                                                    <a href="<?= site_url('partner/jenis/delete/' . $j->id) ?>" onclick="return confirm('Hapus jenis ini?')"
                                                       class="rounded bg-red-500 px-3 py-1.5 text-sm text-white hover:bg-red-600">Hapus</a>
                                                <?php else: ?>
                                                    <span class="px-3 py-1.5 text-xs text-gray-400" title="Masih dipakai partner">Dipakai</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <p class="mt-3 text-xs text-gray-500">Mengubah nama jenis ikut mengubah jenis di semua partner (dan vendor project yang memakai partner itu). Jenis yang masih dipakai tidak bisa dihapus.</p>
                            </div>
                        </details>

                        <!-- ====== Data Table Two Start ===== --><br>
                        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                            <div class="data-table-common data-table-two max-w-full overflow-x-auto">
                                <table class="table w-full table-auto" id="dataTableTwo">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="flex items-center justify-between gap-1.5">
                                                    <p>Nama Partner</p>
                                                    <div class="inline-flex flex-col space-y-[2px]">
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 0L0 5H10L5 0Z" fill="" />
                                                            </svg>
                                                        </span>
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="flex items-center justify-between gap-1.5">
                                                    <p>Jenis</p>
                                                    <div class="inline-flex flex-col space-y-[2px]">
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 0L0 5H10L5 0Z" fill="" />
                                                            </svg>
                                                        </span>
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="flex items-center justify-between gap-1.5">
                                                    <p>Social Media</p>
                                                    <div class="inline-flex flex-col space-y-[2px]">
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 0L0 5H10L5 0Z" fill="" />
                                                            </svg>
                                                        </span>
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="flex items-center justify-between gap-1.5">
                                                    <p>Nama Kontak</p>
                                                    <div class="inline-flex flex-col space-y-[2px]">
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 0L0 5H10L5 0Z" fill="" />
                                                            </svg>
                                                        </span>
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>
                                                <div class="flex items-center justify-between gap-1.5">
                                                    <p>No HP</p>
                                                    <div class="inline-flex flex-col space-y-[2px]">
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 0L0 5H10L5 0Z" fill="" />
                                                            </svg>
                                                        </span>
                                                        <span class="inline-block">
                                                            <svg class="fill-current" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 5L10 0L-4.37114e-07 8.74228e-07L5 5Z" fill="" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>Jumlah Project</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($partner as $partner): ?>
                                            <tr>
                                                <td><?= $partner->partner_name ?></td>
                                                <td><?= $partner->type ?></td>
                                                <td><?= $partner->social_media ?></td>
                                                <td><?= $partner->contact_name ?></td>
                                                <td><a href="https://wa.me/<?= $partner->phone ?>"><?= $partner->phone ?></a></td>
                                                <?php $jml = $jumlah_project[$partner->id_session] ?? 0; ?>
                                                <td>
                                                    <a href="<?= base_url('partner/lihat/' . $partner->id_session) ?>#project-pengguna"
                                                       class="inline-flex rounded-full px-3 py-1 text-sm font-medium <?= $jml > 0 ? 'bg-primary bg-opacity-10 text-primary' : 'bg-gray-200 text-gray-500' ?>">
                                                        <?= $jml ?> project
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="flex flex-col items-start gap-2 w-max">
                                                        <a href="<?= base_url('partner/lihat/' . $partner->id_session) ?>" class="inline-flex justify-center bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 text-center w-full">Lihat</a>
                                                        <a href="<?= base_url('partner/delete/' . $partner->id_session) ?>" class="inline-flex justify-center bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 min-w-full text-center" onclick="return confirm('Delete this partner?')">Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- ====== Data Table Two End ===== -->
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
