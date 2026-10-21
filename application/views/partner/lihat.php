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

                        <!-- ====== Project yang memakai partner ini ===== -->
                        <div id="project-pengguna" class="mb-6 rounded-sm border border-stroke bg-white px-5 pb-4 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
                            <?php
                            // Kategori berdasarkan tanggal acara (default: Akan Datang, hari ini termasuk)
                            $hari_ini = date('Y-m-d');
                            $semua_project = $projects;
                            $jml_lewat = 0;
                            foreach ($semua_project as $pr) {
                                if ($pr->event_date < $hari_ini) { $jml_lewat++; }
                            }
                            $tab_kategori = [
                                'akan_datang' => ['Akan Datang', count($semua_project) - $jml_lewat],
                                'sudah_lewat' => ['Sudah Lewat', $jml_lewat],
                                'semua'       => ['Semua', count($semua_project)],
                            ];
                            $kategori = $this->input->get('kategori');
                            if (!isset($tab_kategori[$kategori])) {
                                $kategori = 'akan_datang';
                            }
                            if ($kategori !== 'semua') {
                                $projects = array_values(array_filter($semua_project, function ($pr) use ($kategori, $hari_ini) {
                                    return $kategori === 'sudah_lewat' ? $pr->event_date < $hari_ini : $pr->event_date >= $hari_ini;
                                }));
                            }
                            // Akan Datang: tanggal terdekat dulu; lainnya: terbaru dulu (sudah dari model)
                            if ($kategori === 'akan_datang') {
                                usort($projects, function ($x, $y) { return strcmp($x->event_date, $y->event_date); });
                            }
                            ?>
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-bold">Project yang Menggunakan Partner Ini</h2>
                                <span class="rounded-full bg-primary bg-opacity-10 px-3 py-1 text-sm font-semibold text-primary"><?= count($projects) ?> project</span>
                            </div>
                            <div class="mb-4 flex flex-wrap gap-2">
                                <?php foreach ($tab_kategori as $kunci => $tab): ?>
                                    <a href="<?= site_url('partner/lihat/' . $partner->id_session) . '?kategori=' . $kunci ?>#project-pengguna"
                                       class="inline-flex items-center gap-2 rounded-md border px-4 py-2 text-sm font-medium <?= $kategori === $kunci ? 'border-primary bg-primary text-white' : 'border-stroke hover:bg-whiter dark:border-strokedark dark:hover:bg-meta-4' ?>">
                                        <?= $tab[0] ?>
                                        <span class="rounded-full px-2 py-0.5 text-xs <?= $kategori === $kunci ? 'bg-white bg-opacity-20' : 'bg-gray-200 text-gray-600' ?>"><?= $tab[1] ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php if (empty($projects)): ?>
                                <p class="pb-2 text-sm text-body dark:text-bodydark">Tidak ada project pada kategori ini.</p>
                            <?php else: ?>
                            <div class="max-w-full overflow-x-auto">
                                <table class="w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                            <th class="px-4 py-3 font-medium">No</th>
                                            <th class="min-w-[200px] px-4 py-3 font-medium">Project</th>
                                            <th class="min-w-[150px] px-4 py-3 font-medium">Client</th>
                                            <th class="min-w-[150px] px-4 py-3 font-medium">Tanggal Acara</th>
                                            <th class="min-w-[150px] px-4 py-3 font-medium">Lokasi</th>
                                            <th class="px-4 py-3 font-medium">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($projects as $i => $pr): ?>
                                        <tr>
                                            <td class="border-b border-[#eee] px-4 py-3 dark:border-strokedark"><?= $i + 1 ?></td>
                                            <td class="border-b border-[#eee] px-4 py-3 dark:border-strokedark"><?= htmlspecialchars($pr->project_name) ?></td>
                                            <td class="border-b border-[#eee] px-4 py-3 dark:border-strokedark"><?= htmlspecialchars($pr->client_name) ?></td>
                                            <td class="border-b border-[#eee] px-4 py-3 dark:border-strokedark"><?= tgl_indo($pr->event_date) ?></td>
                                            <td class="border-b border-[#eee] px-4 py-3 dark:border-strokedark"><?= htmlspecialchars($pr->location) ?></td>
                                            <td class="border-b border-[#eee] px-4 py-3 dark:border-strokedark">
                                                <div class="flex flex-wrap gap-2">
                                                <a href="<?= site_url('vendor/lihat/' . $pr->id_session . '/' . $partner->id_session) ?>" class="inline-flex bg-yellow-500 text-white px-2 py-1 rounded-md hover:bg-yellow-600 text-sm">Lihat</a>
                                                <?php if ($kategori === 'akan_datang'): ?>
                                                    <?php
                                                    // Pesan WhatsApp ke nomor partner: satu project per pesan
                                                    $detail_wa = trim(strip_tags((string) $pr->detail));
                                                    if (mb_strlen($detail_wa) > 1200) {
                                                        $detail_wa = mb_substr($detail_wa, 0, 1200) . '...';
                                                    }
                                                    $pesan_wa = "Halo " . $partner->partner_name . ", berikut info project:\n"
                                                        . "Nama Project: " . $pr->project_name . "\n"
                                                        . "Tanggal Acara: " . format_tanggal_acara($pr->event_date) . "\n"
                                                        . "Lokasi: " . ($pr->location !== '' ? $pr->location : '-') . "\n"
                                                        . "Detail: " . ($detail_wa !== '' ? $detail_wa : '-');
                                                    // Nomor: hanya angka, awalan 0 -> 62
                                                    $nomor_wa = preg_replace('/\D/', '', (string) $partner->phone);
                                                    if (strpos($nomor_wa, '0') === 0) {
                                                        $nomor_wa = '62' . substr($nomor_wa, 1);
                                                    }
                                                    $url_wa = 'https://wa.me/' . $nomor_wa . '?text=' . rawurlencode($pesan_wa);
                                                    ?>
                                                    <a href="<?= htmlspecialchars($url_wa) ?>" target="_blank" rel="noopener"
                                                       class="inline-flex items-center gap-1 bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 text-sm">
                                                        Bagikan WhatsApp
                                                    </a>
                                                <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>

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
