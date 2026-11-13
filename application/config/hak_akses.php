<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Katalog halaman yang bisa dipilih di menu "Hak Akses" (pengaturan halaman per level user).
|
| Untuk tiap level (Administrator, Staff Accounting, Staff Admin, Staff, Staff Sales, dan level baru buatan Developer)
| admin memilih halaman mana saja yang tampil di sidebar dan boleh dibuka. SEMUA halaman di katalog ditampilkan di form
| dan bisa dicentang untuk level mana pun, termasuk halaman di luar hak dasar level itu. Level yang belum diatur memakai
| tampilan bawaan (halaman sesuai hak dasar level, seperti sebelum fitur ini ada); level yang sudah diatur hanya
| memiliki halaman yang dicentang.
|
| Halaman di luar hak dasar level dibuka dengan "level efektif" ('grant'): selama request ke halaman itu, level yang
| dibaca controller diganti sementara dengan level 'grant' (lihat libraries/Session/MY_Session.php dan
| hooks/Hak_akses_guard.php), sehingga pengecekan level di controller lolos dan datanya sama seperti yang dilihat level
| tersebut. Pilih 'grant' level yang paling terbatas tetapi masih punya akses penuh ke halaman itu (jangan Developer).
|
| Tiap menu:
|   label        : nama di form & sidebar
|   group        : pengelompokan di form
|   url          : halaman tujuan awal (redirect kalau user membuka halaman yang tidak diizinkan)
|   levels       : (opsional) level akun yang memang bisa membuka halaman ini lewat hak dasar (tanpa kunci = semua level);
|                  dipakai menentukan centang awal di form dan kapan 'grant' diperlukan
|   grant        : (wajib kalau 'levels' ada) level efektif untuk level di luar 'levels'
|   hanya_bawaan : (opsional) true = tidak bisa diberikan lewat Hak Akses ke level di luar 'levels'
|   rules        : 'controller::method' (huruf kecil, boleh wildcard * ala fnmatch) yang diizinkan
|   exclude      : (opsional) pola yang dikecualikan dari rules menu ini
| Controller yang tidak tercantum di sini (dan bukan hak_akses_selalu) diblokir untuk level yang sudah diatur.
*/

$config['hak_akses_menu'] = [
    'dashboard' => ['label' => 'Dashboard', 'group' => 'Umum', 'url' => 'panel', 'rules' => [
        'aspanel::index', 'aspanel::home', 'aspanel::get_*', 'aspanel::sales_*', 'aspanel::*_lebih_lengkap*',
    ]],
    'absensi' => ['label' => 'Absensi Saya', 'group' => 'Umum', 'url' => 'absensi', 'levels' => ['1','2','3','4','9'], 'grant' => '4', 'rules' => [
        'aspanel::absensi', 'aspanel::absensi_masuk', 'aspanel::absensi_keluar', 'aspanel::absensi_izin',
    ]],
    'gaji_saya' => ['label' => 'Gaji Saya', 'group' => 'Umum', 'url' => 'gaji-saya', 'levels' => ['1','2','3','4','9'], 'grant' => '4', 'rules' => ['aspanel::gaji_saya']],
    'absensi_rekap' => ['label' => 'Rekap & Pengaturan Absensi', 'group' => 'Umum', 'url' => 'absensi-rekap', 'levels' => ['1','2'], 'grant' => '2', 'rules' => [
        'aspanel::absensi_rekap*', 'aspanel::absensi_pengaturan*',
    ]],

    'potensial_clients' => ['label' => 'Potensial Clients', 'group' => 'Penjualan', 'url' => 'potensial-clients', 'levels' => ['1','2','4','9'], 'grant' => '4',
        'rules' => ['crud_potensial_clients::*'],
        'exclude' => ['crud_potensial_clients::*_pricelist', 'crud_potensial_clients::upload_gambar', 'crud_potensial_clients::permanent_delete_gambar'],
    ],
    'daftar_harga' => ['label' => 'Daftar Harga', 'group' => 'Penjualan', 'url' => 'potensial-clients-pricelist', 'levels' => ['1','2','4','9'], 'grant' => '4', 'rules' => [
        'crud_potensial_clients::*_pricelist', 'crud_potensial_clients::upload_gambar', 'crud_potensial_clients::permanent_delete_gambar',
    ]],
    'project' => ['label' => 'Project (termasuk invoice, kwitansi, agenda, vendor, client)', 'group' => 'Operasional', 'url' => 'project', 'levels' => ['1','2','4','9'], 'grant' => '4', 'rules' => [
        'crud_project::*', 'crud_payment::*', 'crud_agenda::*', 'crud_vendor::*', 'crud_vendor_checklist::*', 'crud_crewprojects::*',
        'crud_clients::*', 'crud_client_tamu::*', 'crud_client_susunan_acara::*', 'crud_client_peralatan_event::*',
    ]],
    'sales_target' => ['label' => 'Setting Target Sales', 'group' => 'Penjualan', 'url' => 'sales-setting-target', 'levels' => ['1','2','3'], 'grant' => '3', 'rules' => ['crud_sales_marketing::*']],
    'stock' => ['label' => 'Stock', 'group' => 'Operasional', 'url' => 'supplies', 'levels' => ['1','2','4'], 'grant' => '4', 'rules' => ['crud_supplies::*']],
    'crews' => ['label' => 'Crew', 'group' => 'Operasional', 'url' => 'crews', 'levels' => ['1','2','4'], 'grant' => '4', 'rules' => ['crud_crews::*', 'crud_crewrole::*']],
    'partner' => ['label' => 'Partner', 'group' => 'Operasional', 'url' => 'partner', 'levels' => ['1','2','4'], 'grant' => '4', 'rules' => ['crud_partner::*']],
    'susunan_acara' => ['label' => 'Susunan Acara', 'group' => 'Operasional', 'url' => 'susunan-acara', 'levels' => ['1','2','4'], 'grant' => '4', 'rules' => ['crud_susunan_acara::*']],
    'peralatan_event' => ['label' => 'Template Peralatan Event', 'group' => 'Operasional', 'url' => 'peralatan-event', 'levels' => ['1','2','4'], 'grant' => '4', 'rules' => ['crud_peralatan_event::*']],

    'finance_project' => ['label' => 'Finance - Project', 'group' => 'Fin & Acc', 'url' => 'finance-project', 'levels' => ['1','2','3'], 'grant' => '3', 'rules' => ['crud_finance_project::*']],
    'finance_operational' => ['label' => 'Finance - Operational', 'group' => 'Fin & Acc', 'url' => 'finance-operational', 'levels' => ['1','2','3'], 'grant' => '3', 'rules' => ['crud_finance_operational::*']],
    'hutang' => ['label' => 'Hutang', 'group' => 'Fin & Acc', 'url' => 'hutang', 'levels' => ['1','2','3'], 'grant' => '3', 'rules' => ['crud_hutang::*']],
    'modal' => ['label' => 'Modal', 'group' => 'Fin & Acc', 'url' => 'modal', 'levels' => ['1','2','3'], 'grant' => '3', 'rules' => ['crud_modal::*']],
    'coa' => ['label' => 'Chart Of Account', 'group' => 'Fin & Acc', 'url' => 'coa', 'levels' => ['1','2','3'], 'grant' => '3', 'rules' => ['crud_coa::*']],
    'rekap_gaji' => ['label' => 'Setting Salary / Rekap Gaji', 'group' => 'Fin & Acc', 'url' => 'rekap-gaji', 'levels' => ['1','2','3'], 'grant' => '3', 'rules' => ['crud_kategori_gaji::*']],

    'user' => ['label' => 'User', 'group' => 'Pengaturan', 'url' => 'user', 'levels' => ['1','2','4','7'], 'grant' => '4', 'rules' => ['crud_user::*']],
    'pengaturan_website' => ['label' => 'Pengaturan Website', 'group' => 'Pengaturan', 'url' => 'pengaturan-website', 'levels' => ['1','2'], 'grant' => '2', 'rules' => ['crud_pengaturan_website::*']],
    // Mengatur hak akses hanya boleh Developer & Administrator asli (dicek di Crud_hak_akses), jadi tidak bisa diberikan ke level lain
    'hak_akses' => ['label' => 'Hak Akses', 'group' => 'Pengaturan', 'url' => 'hak-akses', 'levels' => ['1','2'], 'hanya_bawaan' => true, 'rules' => ['crud_hak_akses::*']],
];

// Selalu boleh dibuka walau levelnya diatur (login/logout, halaman publik klien, halaman error).
$config['hak_akses_selalu'] = [
    'welcome::*', 'errorcontroller::*', 'user::*', 'client::*',
    'aspanel::logout',
    'data_pengantin::*', 'izin_menikah::*', 'jubir_cpp::*', 'jubir_cpw::*', 'terima_kasih::*', 'terima_kasih2::*',
];

// Level yang bisa diatur halamannya. Developer (1) tidak pernah dibatasi; Client/Partner/Affiliate punya tampilan sendiri.
$config['hak_akses_level_boleh'] = ['2', '3', '4', '7', '9'];
