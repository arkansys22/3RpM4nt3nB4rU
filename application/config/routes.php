<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'Notfound';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = "User/login";
$route['panel'] = "aspanel/home";

$route['projects'] = 'crud_projects/index'; // Menampilkan daftar projects
$route['projects/create'] = 'crud_projects/create'; // Menampilkan form tambah projects
$route['projects/store'] = 'crud_projects/store'; // Menyimpan data projects baru
$route['projects/edit/(:any)'] = 'crud_projects/edit/$1'; // Menampilkan form edit projects
$route['projects/update/(:any)'] = 'crud_projects/update/$1'; // Mengupdate projects
$route['projects/delete/(:any)'] = 'crud_projects/delete/$1'; // Menghapus projects
$route['projects/recycle_bin'] = 'crud_projects/recycle_bin'; // Halaman recycle bin
$route['projects/restore/(:any)'] = 'crud_projects/restore/$1'; // Restore clients
$route['projects/permanent_delete/(:any)'] = 'crud_projects/permanent_delete/$1'; // Hapus permanen clients

$route['clients'] = 'crud_clients/index'; // Menampilkan daftar clients
// $route['clients/create'] = 'crud_clients/create'; // Halaman tambah clients
$route['clients/store'] = 'crud_clients/store'; // Menyimpan data clients baru
$route['clients/edit/(:any)'] = 'crud_clients/edit/$1'; // Halaman edit clients berdasarkan id_session
$route['clients/update/(:any)'] = 'crud_clients/update/$1'; // Proses update data clients
$route['clients/delete/(:any)'] = 'crud_clients/delete/$1'; // Hapus clients berdasarkan id_session
$route['clients/recycle_bin'] = 'crud_clients/recycle_bin'; // Halaman recycle bin
$route['clients/restore/(:any)'] = 'crud_clients/restore/$1'; // Restore clients
$route['clients/permanent_delete/(:any)'] = 'crud_clients/permanent_delete/$1'; // Hapus permanen clients

$route['crew'] = 'Crud_crew/index'; // Menampilkan daftar crew yang masih aktif
$route['crew/create'] = 'Crud_crew/create'; // Menampilkan halaman tambah crew
$route['crew/store'] = 'Crud_crew/store'; // Menyimpan data crew yang baru dibuat
$route['crew/edit/(:any)'] = 'Crud_crew/edit/$1'; // Menampilkan halaman edit crew berdasarkan id_session
$route['crew/update/(:any)'] = 'Crud_crew/update/$1'; // Mengupdate data crew berdasarkan id_session
$route['crew/recycle_bin'] = 'Crud_crew/recycle_bin'; // Menampilkan daftar crew yang telah dihapus (soft delete)
$route['crew/soft_delete/(:any)'] = 'Crud_crew/soft_delete/$1'; // Menghapus data crew (soft delete, ubah status jadi 'delete')
$route['crew/restore/(:any)'] = 'Crud_crew/restore/$1'; // Mengembalikan data crew dari Recycle Bin ke daftar aktif
$route['crew/delete_permanent/(:any)'] = 'Crud_crew/delete_permanent/$1'; // Menghapus permanen data crew dari Recycle Bin

$route['petacrawl\.xml'] = "petacrawl";
