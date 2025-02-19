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
$route['clients/create'] = 'crud_clients/create'; // Halaman tambah clients
$route['clients/store'] = 'crud_clients/store'; // Menyimpan data clients baru
$route['clients/edit/(:any)'] = 'crud_clients/edit/$1'; // Halaman edit clients berdasarkan id_session
$route['clients/update/(:any)'] = 'crud_clients/update/$1'; // Proses update data clients
$route['clients/delete/(:any)'] = 'crud_clients/delete/$1'; // Hapus clients berdasarkan id_session
$route['clients/recycle_bin'] = 'crud_clients/recycle_bin'; // Halaman recycle bin
$route['clients/restore/(:any)'] = 'crud_clients/restore/$1'; // Restore clients
$route['clients/permanent_delete/(:any)'] = 'crud_clients/permanent_delete/$1'; // Hapus permanen clients

$route['petacrawl\.xml'] = "petacrawl";
