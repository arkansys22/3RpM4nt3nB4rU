<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// Nge-update `user.last_activity` tiap kali user yang login membuka halaman
// apa pun -- fitur status online (lihat status_online() di customs_helper.php
// & application/hooks/Track_online.php) butuh ini supaya "Online" benar-benar
// berarti "baru saja aktif", bukan cuma "pernah login dan belum eksplisit
// logout".
$hook['post_controller_constructor'][] = array(
    'class'    => 'Track_online',
    'function' => 'touch_last_activity',
    'filename' => 'Track_online.php',
    'filepath' => 'hooks',
);
