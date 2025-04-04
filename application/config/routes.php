<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'ErrorController';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = "User/login";
$route['panel'] = "aspanel/home";
$route['logout'] = "aspanel/logout";

$route['client/login'] = "Client/login";
$route['client/logout'] = "Client/logout";

$route['user'] = 'crud_user/index'; // Menampilkan daftar user
$route['user/lihat/(:any)'] = 'crud_user/lihat/$1'; // Menampilkan detail user
$route['user/create'] = 'crud_user/create'; // Menampilkan form tambah user
$route['user/store'] = 'crud_user/store'; // Menyimpan data user baru
$route['user/edit/(:any)'] = 'crud_user/edit/$1'; // Menampilkan form edit user
$route['user/update/(:any)'] = 'crud_user/update/$1'; // Mengupdate user
$route['user/delete/(:any)'] = 'crud_user/delete/$1'; // Menghapus user
$route['user/recycle_bin'] = 'crud_user/recycle_bin'; // Halaman recycle bin
$route['user/restore/(:any)'] = 'crud_user/restore/$1'; // Restore user
$route['user/permanent_delete/(:any)'] = 'crud_user/permanent_delete/$1'; // Hapus permanen user


$route['finance-operational'] = 'crud_finance_operational/index'; // Menampilkan daftar user
$route['finance-operational/lihat/(:any)'] = 'crud_finance_operational/lihat/$1'; // Menampilkan detail user
$route['finance-operational/create'] = 'crud_finance_operational/create'; // Menampilkan form tambah user
$route['finance-operational/store'] = 'crud_finance_operational/store'; // Menyimpan data user baru
$route['finance-operational/edit/(:any)'] = 'crud_finance_operational/edit/$1'; // Menampilkan form edit user
$route['finance-operational/update/(:any)'] = 'crud_finance_operational/update/$1'; // Mengupdate user
$route['finance-operational/permanent_delete/(:any)'] = 'crud_finance_operational/permanent_delete/$1'; // Hapus permanen user


$route['project'] = 'crud_project/index'; // Menampilkan daftar project
$route['project/create'] = 'crud_project/create'; // Menampilkan form tambah project
$route['project/store'] = 'crud_project/store'; // Menyimpan data project baru
$route['project/edit/(:any)'] = 'crud_project/edit/$1'; // Menampilkan form edit project
$route['project/update/(:any)'] = 'crud_project/update/$1'; // Mengupdate project
$route['project/delete/(:any)'] = 'crud_project/delete/$1'; // Menghapus project
$route['project/recycle_bin'] = 'crud_project/recycle_bin'; // Halaman recycle bin
$route['project/restore/(:any)'] = 'crud_project/restore/$1'; // Restore project
$route['project/permanent_delete/(:any)'] = 'crud_project/permanent_delete/$1'; // Hapus permanen project
$route['project/lihat/(:any)'] = 'crud_project/lihat/$1'; // Menampilkan lihat project



$route['finance-project'] = 'crud_finance_project/index'; // Menampilkan daftar project
$route['finance-project/create'] = 'crud_finance_project/create'; // Menampilkan form tambah project
$route['finance-project/store'] = 'crud_finance_project/store'; // Menyimpan data project baru
$route['finance-project/edit/(:any)'] = 'crud_finance_project/edit/$1'; // Menampilkan form edit project
$route['finance-project/edit2/(:any)'] = 'crud_finance_project/edit2/$1'; // Menampilkan form edit project
$route['finance-project/update2/(:any)'] = 'crud_finance_project/update2/$1'; // Mengupdate project
$route['finance-project/delete/(:any)'] = 'crud_finance_project/delete/$1'; // Menghapus project
$route['finance-project/recycle_bin'] = 'crud_finance_project/recycle_bin'; // Halaman recycle bin
$route['finance-project/restore/(:any)'] = 'crud_finance_project/restore/$1'; // Restore project
$route['finance-project/permanent_delete/(:any)'] = 'crud_finance_project/permanent_delete/$1'; // Hapus permanen project
$route['finance-project/lihat/(:any)'] = 'crud_finance_project/lihat/$1'; // Menampilkan lihat project



$route['payment/create/(:num)/(:num)'] = 'Crud_payment/create/$1/$2';  // Routing untuk menambah pembayaran (Add Payment)
$route['payment/store/(:num)/(:num)'] = 'Crud_payment/store/$1/$2';  // Routing untuk menambah pembayaran (Add Payment)
$route['payment/edit/(:num)/(:num)'] = 'Crud_payment/edit/$1/$2';      // Routing untuk mengedit pembayaran (Edit Payment)
$route['payment/update/(:num)/(:num)'] = 'Crud_payment/update/$1/$2';    // Routing untuk menyimpan perubahan (Update Payment)
$route['payment/delete/(:num)/(:num)'] = 'Crud_payment/delete/$1/$2';    // Routing untuk menghapus pembayaran (Delete Payment)
$route['payment/view_invoice/(:any)/(:num)'] = 'Crud_payment/view_invoice/$1/$2';
$route['payment/view_kwitansi/(:any)/(:num)'] = 'Crud_payment/view_kwitansi/$1/$2';

$route['payment/createinv/(:any)'] = 'crud_payment/create/$1'; // Route for creating an invoice
$route['payment/createkwt/(:any)/(:any)'] = 'crud_payment/create2/$1/$2'; // Route for creating a kwitansi
$route['payment/edit/(:any)/(:any)'] = 'crud_payment/edit/$1/$2';
$route['payment/edit2/(:any)/(:any)'] = 'crud_payment/edit2/$1/$2';
$route['payment/delete/(:any)/(:any)'] = 'crud_payment/delete/$1/$2';
$route['payment/delete2/(:any)/(:any)'] = 'crud_payment/delete2/$1/$2';
$route['payment/view_invoice/(:any)/(:any)'] = 'crud_payment/view_invoice/$1/$2';
$route['payment/view_kwitansi/(:any)/(:any)'] = 'crud_payment/view_kwitansi/$1/$2';
$route['payment/store'] = 'crud_payment/store'; // Route for storing invoice
$route['payment/store2'] = 'crud_payment/store2'; // Route for storing kwitansi
$route['payment/update/(:any)/(:any)'] = 'crud_payment/update/$1/$2';
$route['payment/update2/(:any)/(:any)'] = 'crud_payment/update2/$1/$2';


$route['clients'] = 'crud_clients/index'; // Menampilkan daftar clients
// $route['clients/create'] = 'crud_clients/create'; // Halaman tambah clients
$route['clients/store'] = 'crud_clients/store'; // Menyimpan data clients baru
$route['clients/edit/(:any)'] = 'crud_clients/edit/$1'; // Halaman edit clients berdasarkan id_session
$route['clients/update/(:any)'] = 'crud_clients/update/$1'; // Proses update data clients
$route['clients/delete/(:any)'] = 'crud_clients/delete/$1'; // Hapus clients berdasarkan id_session
$route['clients/recycle_bin'] = 'crud_clients/recycle_bin'; // Halaman recycle bin
$route['clients/restore/(:any)'] = 'crud_clients/restore/$1'; // Restore clients
$route['clients/permanent_delete/(:any)'] = 'crud_clients/permanent_delete/$1'; // Hapus permanen clients
$route['clients/lihat/(:any)'] = 'crud_clients/lihat/$1'; // Menampilkan lihat clients

$route['clients/c_edit/(:any)'] = 'crud_clients/c_edit/$1'; // Halaman edit clients berdasarkan id_session
$route['clients/c_update/(:any)'] = 'crud_clients/c_update/$1'; // Proses update data clients
$route['clients/c_lihat/(:any)'] = 'crud_clients/c_lihat/$1'; // Menampilkan lihat clients
$route['clients/c_concept'] = 'crud_clients/c_concept';

$route['crews'] = 'Crud_crews/index'; // Menampilkan daftar crews yang masih aktif
$route['crews/create'] = 'Crud_crews/create'; // Menampilkan halaman tambah crews
$route['crews/store'] = 'Crud_crews/store'; // Menyimpan data crews yang baru dibuat
$route['crews/edit/(:any)'] = 'Crud_crews/edit/$1'; // Menampilkan halaman edit crews berdasarkan id_session
$route['crews/update/(:any)'] = 'Crud_crews/update/$1'; // Mengupdate data crews berdasarkan id_session
$route['crews/recycle_bin'] = 'Crud_crews/recycle_bin'; // Menampilkan daftar crews yang telah dihapus (soft delete)
$route['crews/soft_delete/(:any)'] = 'Crud_crews/soft_delete/$1'; // Menghapus data crews (soft delete, ubah status jadi 'delete')
$route['crews/restore/(:any)'] = 'Crud_crews/restore/$1'; // Mengembalikan data crews dari Recycle Bin ke daftar aktif
$route['crews/delete_permanent/(:any)'] = 'Crud_crews/delete_permanent/$1'; // Menghapus permanen data crews dari Recycle Bin
$route['crews/lihat/(:any)'] = 'Crud_crews/lihat/$1'; // Menampilkan lihat Crew

$route['potensial-clients'] = 'crud_potensial_clients/index'; // Menampilkan daftar Potensial Clients
$route['potensial-clients-hot'] = 'crud_potensial_clients/index_hot'; // Menampilkan daftar Potensial Clients Hot
$route['potensial-clients-konsul'] = 'crud_potensial_clients/index_konsul'; // Menampilkan daftar Potensial Clients konsul
$route['potensial-clients-bayar'] = 'crud_potensial_clients/index_deal'; // Menampilkan daftar Potensial Clients bayar
$route['potensial-clients-batal'] = 'crud_potensial_clients/index_batal'; // Menampilkan daftar Potensial Clients batal
$route['potensial-clients-ghosting'] = 'crud_potensial_clients/index_ghosting'; // Menampilkan daftar Potensial Clients Ghosting
$route['potensial-clients/create'] = 'crud_potensial_clients/create'; // Menampilkan form tambah Potensial Clients
$route['potensial-clients/store'] = 'crud_potensial_clients/store'; // Menyimpan data Potensial Clients baru
$route['potensial-clients/lihat/(:any)'] = 'crud_potensial_clients/lihat/$1'; // Menampilkan lihat Potensial Clients
$route['potensial-clients/edit/(:any)'] = 'crud_potensial_clients/edit/$1'; // Menampilkan form edit Potensial Clients
$route['potensial-clients/update/(:any)'] = 'crud_potensial_clients/update/$1'; // Mengupdate Potensial Clients
$route['potensial-clients/delete/(:any)'] = 'crud_potensial_clients/delete/$1'; // Menghapus Potensial Clients
$route['potensial-clients/recycle_bin'] = 'crud_potensial_clients/recycle_bin'; // Halaman recycle bin
$route['potensial-clients/restore/(:any)'] = 'crud_potensial_clients/restore/$1'; // Restore Potensial Clients
$route['potensial-clients/permanent_delete/(:any)'] = 'crud_potensial_clients/permanent_delete/$1'; // Hapus permanen Potensial Clients

$route['naskah/jubir_cpp/(:any)'] = 'Jubir_cpp/view/$1';
$route['naskah/jubir_cpp/pdf/(:any)'] = 'Jubir_cpp/generate_pdf/$1';
$route['naskah/jubir_cpw/(:any)'] = 'Jubir_cpw/view/$1';
$route['naskah/jubir_cpw/pdf/(:any)'] = 'Jubir_cpw/generate_pdf/$1';
$route['naskah/izin_menikah/(:any)'] = 'Izin_menikah/view/$1';
$route['naskah/izin_menikah/pdf/(:any)'] = 'Izin_menikah/generate_pdf/$1';
$route['naskah/terima_kasih/(:any)'] = 'Terima_kasih/view/$1';
$route['naskah/terima_kasih/pdf/(:any)'] = 'Terima_kasih/generate_pdf/$1';
$route['naskah/data_pengantin/(:any)'] = 'Data_pengantin/view/$1';
$route['naskah/data_pengantin/pdf/(:any)'] = 'Data_pengantin/generate_pdf/$1';
$route['naskah/list_vendor/(:any)'] = 'crud_vendor/view/$1';
$route['naskah/list_vendor/pdf/(:any)'] = 'crud_vendor/generate_pdf/$1';

$route['vendor/create/(:any)'] = 'crud_vendor/create/$1'; // Create vendor form based on session_id
$route['vendor/store/(:any)'] = 'crud_vendor/store/$1'; // Store vendor data based on session_id
$route['vendor/edit/(:num)/(:num)'] = 'crud_vendor/edit/$1/$2';
$route['vendor/update/(:num)/(:num)'] = 'crud_vendor/update/$1/$2';
$route['vendor/delete/(:num)/(:num)'] = 'crud_vendor/delete/$1/$2';

$route['agenda'] = 'crud_agenda/index'; // Menampilkan daftar crews yang masih aktif
$route['agenda/create/(:any)'] = 'crud_agenda/create/$1'; // Create vendor form based on session_id
$route['agenda/store'] = 'crud_agenda/store'; // Store vendor data based on session_id
$route['agenda/edit/(:any)'] = 'crud_agenda/edit/$1'; // Edit vendor data based on session_id
$route['agenda/update/(:any)'] = 'crud_agenda/update/$1'; // Update vendor data based on session_id

$route['supplies'] = 'crud_supplies/index'; // Menampilkan daftar supplies
$route['supplies/create'] = 'crud_supplies/create'; // Menampilkan form tambah supplies
$route['supplies/store'] = 'crud_supplies/store'; // Menyimpan data supplies baru
$route['supplies/store2'] = 'crud_supplies/store2'; // Menyimpan data supplies baru
$route['supplies/store3'] = 'crud_supplies/store3'; // Menyimpan data supplies baru
$route['supplies/editin/(:any)'] = 'crud_supplies/edit/$1'; // Menampilkan form edit supplies
$route['supplies/editout/(:any)'] = 'crud_supplies/edit/$1'; // Menampilkan form edit supplies
$route['supplies/update/(:any)'] = 'crud_supplies/update/$1'; // Mengupdate supplies
$route['supplies/delete/(:any)'] = 'crud_supplies/delete/$1'; // Menghapus supplies
$route['supplies/recycle_bin'] = 'crud_supplies/recycle_bin'; // Halaman recycle bin
$route['supplies/restore/(:any)'] = 'crud_supplies/restore/$1'; // Restore supplies
$route['supplies/permanent_delete/(:any)'] = 'crud_supplies/permanent_delete/$1'; // Hapus permanen supplies
$route['supplies/lihat/(:any)'] = 'crud_supplies/lihat/$1'; // Menampilkan lihat supplies

$route['crewproject/createlist/(:any)'] = 'Crud_crewprojects/createlist/$1'; // Route for creating a crew list for a project
$route['crewproject/storelist'] = 'Crud_crewprojects/storelist'; // Route for storing a crew list for a project
$route['crewproject/delete/(:any)'] = 'Crud_crewprojects/delete/$1'; // Route for deleting a single crew
$route['crewproject/editlist/(:any)/(:any)'] = 'Crud_crewprojects/editlist/$1/$2'; // Route for editing a crew using project_id and crew_id
$route['crewproject/updatelist/(:any)/(:any)'] = 'Crud_crewprojects/updatelist/$1/$2'; // Route for updating a crew using id_session and crew_id
$route['crewproject/updatelist'] = 'Crud_crewprojects/updatelist';

$route['petacrawl\.xml'] = "petacrawl";
