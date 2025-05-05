<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Partner</title>
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
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="text-2xl font-bold">Lihat Partner</h1>
                            <div
                                x-data="{openDropDown: false}"
                                class="relative inline-block"
                            >
                                <a
                                    href="#"
                                    @click.prevent="openDropDown = !openDropDown"
                                    class="inline-flex items-center gap-2.5 rounded-md bg-primary px-5.5 py-3 font-medium text-white hover:bg-opacity-95"
                                >
                                    Menu
                                    <svg
                                        class="fill-current duration-200 ease-linear"
                                        :class="openDropDown && 'rotate-180'"
                                        width="12"
                                        height="7"
                                        viewBox="0 0 12 7"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M0.564864 0.879232C0.564864 0.808624 0.600168 0.720364 0.653125 0.667408C0.776689 0.543843 0.970861 0.543844 1.09443 0.649756L5.82517 5.09807C5.91343 5.18633 6.07229 5.18633 6.17821 5.09807L10.9089 0.649756C11.0325 0.526192 11.2267 0.543844 11.3502 0.667408C11.4738 0.790972 11.4562 0.985145 11.3326 1.10871L6.60185 5.55702C6.26647 5.85711 5.73691 5.85711 5.41917 5.55702L0.670776 1.10871C0.600168 1.0381 0.564864 0.967492 0.564864 0.879232Z"
                                            fill=""
                                        />
                                    </svg>
                                </a>
                                <div
                                    x-show="openDropDown"
                                    @click.outside="openDropDown = false"
                                    class="absolute left-0 top-full z-40 mt-2 w-full rounded-md border border-stroke bg-white py-3 shadow-card dark:border-strokedark dark:bg-boxdark"
                                >
                                    <ul class="flex flex-col">
                                        <li>
                                            <a
                                                href="<?= site_url('partner/edit/'. $partner->id_session) ?>"
                                                class="flex px-5 py-2 font-medium hover:bg-whiter hover:text-primary dark:hover:bg-meta-4"
                                            >
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                                href="<?= site_url('partner') ?>"
                                                class="flex px-5 py-2 font-medium hover:bg-whiter hover:text-primary dark:hover:bg-meta-4"
                                            >
                                                Kembali
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <form class="bg-white dark:bg-boxdark p-6 shadow-md rounded">
                            <label class="block mb-2"><strong>Nama Partner: </strong><?= $partner->partner_name ?></label>
                            <label class="block mb-2"><strong>Jenis: </strong><?= $partner->type ?></label>
                            <label class="block mb-2"><strong>Social Media: </strong><?= $partner->social_media ?></label>
                            <label class="block mb-2"><strong>Nama Kontak: </strong><?= $partner->contact_name ?></label>
                            <label class="block mb-2"><strong>No HP: </strong><?= $partner->phone ?></label>
                        </form>

                        <!-- ====== Log Activity Table Start ===== -->
                        <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
                            <div class="max-w-full overflow-x-auto">
                                <table class="w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                            <th class="min-w-[220px] px-4 py-4 font-medium xl:pl-11">Author</th>
                                            <th class="min-w-[150px] px-4 py-4 font-medium">Status</th>
                                            <th class="min-w-[120px] px-4 py-4 font-medium">Time</th>
                                            <th class="px-4 py-4 font-medium">Device</th>
                                            <th class="px-4 py-4 font-medium">IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($logactivity as $p): ?>
                                        <tr>
                                            <?php $user = $this->Crud_m->view_where('user', array('id_session' => $p->log_activity_user_id))->row(); ?>
                                            <?php $level = $this->Crud_m->view_where('user_level', array('user_level_id' => $user->level))->row(); ?>
                                            <td class="border-b border-[#eee] px-4 py-5 pl-9 dark:border-strokedark xl:pl-11">
                                                <h5 class="font-medium"><?= $user->username ?></h5>
                                                <p class="text-sm"><?= $level->user_level_nama ?></p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="inline-flex rounded-full bg-success bg-opacity-10 px-3 py-1 text-sm font-medium text-success">
                                                    <?= $p->log_activity_status ?>
                                                </p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p><?= hari($p->log_activity_waktu) ?>, <?= tgl_indo($p->log_activity_waktu) ?></p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p><?= $p->log_activity_platform ?></p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p><?= $p->log_activity_ip ?></p>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- ====== Log Activity Table End ===== -->
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
