<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'Notfound';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = "User/login";
$route['panel'] = "aspanel/home";
$route['logout'] = "aspanel/logout";




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



$route['project'] = 'crud_project/index'; // Menampilkan daftar project
$route['project/create'] = 'crud_project/create'; // Menampilkan form tambah project
$route['project/store'] = 'crud_project/store'; // Menyimpan data project baru
$route['project/edit/(:any)'] = 'crud_project/edit/$1'; // Menampilkan form edit project
$route['project/update/(:any)'] = 'crud_project/update/$1'; // Mengupdate project
$route['project/delete/(:any)'] = 'crud_project/delete/$1'; // Menghapus project
$route['project/recycle_bin'] = 'crud_project/recycle_bin'; // Halaman recycle bin
$route['project/restore/(:any)'] = 'crud_project/restore/$1'; // Restore clients
$route['project/permanent_delete/(:any)'] = 'crud_project/permanent_delete/$1'; // Hapus permanen clients
$route['project/add_crews_to_project'] = 'Crud_project/add_crews_to_project'; // Tambah crews ke project
$route['project/remove_crews_from_project/(:num)/(:num)'] = 'Crud_project/remove_crews_from_project/$1/$2'; // Hapus crews dari project


$route['clients'] = 'crud_clients/index'; // Menampilkan daftar clients
// $route['clients/create'] = 'crud_clients/create'; // Halaman tambah clients
$route['clients/store'] = 'crud_clients/store'; // Menyimpan data clients baru
$route['clients/edit/(:any)'] = 'crud_clients/edit/$1'; // Halaman edit clients berdasarkan id_session
$route['clients/update/(:any)'] = 'crud_clients/update/$1'; // Proses update data clients
$route['clients/delete/(:any)'] = 'crud_clients/delete/$1'; // Hapus clients berdasarkan id_session
$route['clients/recycle_bin'] = 'crud_clients/recycle_bin'; // Halaman recycle bin
$route['clients/restore/(:any)'] = 'crud_clients/restore/$1'; // Restore clients
$route['clients/permanent_delete/(:any)'] = 'crud_clients/permanent_delete/$1'; // Hapus permanen clients

$route['crews'] = 'Crud_crews/index'; // Menampilkan daftar crews yang masih aktif
$route['crews/create'] = 'Crud_crews/create'; // Menampilkan halaman tambah crews
$route['crews/store'] = 'Crud_crews/store'; // Menyimpan data crews yang baru dibuat
$route['crews/edit/(:any)'] = 'Crud_crews/edit/$1'; // Menampilkan halaman edit crews berdasarkan id_session
$route['crews/update/(:any)'] = 'Crud_crews/update/$1'; // Mengupdate data crews berdasarkan id_session
$route['crews/recycle_bin'] = 'Crud_crews/recycle_bin'; // Menampilkan daftar crews yang telah dihapus (soft delete)
$route['crews/soft_delete/(:any)'] = 'Crud_crews/soft_delete/$1'; // Menghapus data crews (soft delete, ubah status jadi 'delete')
$route['crews/restore/(:any)'] = 'Crud_crews/restore/$1'; // Mengembalikan data crews dari Recycle Bin ke daftar aktif
$route['crews/delete_permanent/(:any)'] = 'Crud_crews/delete_permanent/$1'; // Menghapus permanen data crews dari Recycle Bin

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

$route['petacrawl\.xml'] = "petacrawl";
