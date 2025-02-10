<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'Notfound';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = "User/login";
$route['panel'] = "aspanel/home";

$route['projek'] = 'crud_projek/index'; // Menampilkan daftar projek
$route['projek/create'] = 'crud_projek/create'; // Menampilkan form tambah projek
$route['projek/create/submit'] = 'crud_projek/create'; // Menangani submit form tambah projek
$route['projek/edit/(:any)'] = 'crud_projek/edit/$1'; // Menampilkan form edit projek
$route['projek/update/(:any)'] = 'crud_projek/update/$1'; // Mengupdate projek
$route['projek/delete/(:any)'] = 'crud_projek/delete/$1'; // Menghapus projek
$route['projek/recycle_bin'] = 'crud_projek/recycle_bin'; // Halaman recycle bin
$route['projek/restore/(:any)'] = 'crud_projek/restore/$1'; // Restore klien
$route['projek/permanent_delete/(:any)'] = 'crud_projek/permanent_delete/$1'; // Hapus permanen klien

$route['default_controller'] = 'crud_clients'; // Menjadikan halaman klien sebagai default
$route['clients'] = 'crud_clients/index'; // Menampilkan daftar klien
$route['clients/create'] = 'crud_clients/create'; // Halaman tambah klien
$route['clients/edit/(:any)'] = 'crud_clients/edit/$1'; // Halaman edit klien berdasarkan id_session
$route['clients/update/(:any)'] = 'crud_clients/update/$1'; // Proses update data klien
$route['clients/delete/(:any)'] = 'crud_clients/delete/$1'; // Hapus klien berdasarkan id_session
$route['clients/recycle_bin'] = 'crud_clients/recycle_bin'; // Halaman recycle bin
$route['clients/restore/(:any)'] = 'crud_clients/restore/$1'; // Restore klien
$route['clients/permanent_delete/(:any)'] = 'crud_clients/permanent_delete/$1'; // Hapus permanen klien

$route['petacrawl\.xml'] = "petacrawl";
