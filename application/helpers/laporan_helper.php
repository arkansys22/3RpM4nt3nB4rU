<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Pemformat angka/tanggal untuk Laporan Keuangan (dipakai view & ekspor Excel)

if (!function_exists('lk_mode_excel')) {
    // true saat membuat ekspor Excel: lk_rp() mengembalikan ANGKA (bukan teks berformat) supaya bisa dihitung ulang di Excel
    function lk_mode_excel($set = null)
    {
        static $mode = false;
        if ($set !== null) {
            $mode = (bool) $set;
        }
        return $mode;
    }
}

if (!function_exists('lk_rp')) {
    // 1234567 -> "1.234.567"; negatif -> "(1.234.567)"; 0 -> "-"
    function lk_rp($n, $nol = '-')
    {
        $n = round((float) $n);
        if (lk_mode_excel()) {
            return (int) $n;
        }
        if ($n == 0) {
            return $nol;
        }
        $teks = number_format(abs($n), 0, ',', '.');
        return $n < 0 ? '(' . $teks . ')' : $teks;
    }
}

if (!function_exists('lk_rp_penuh')) {
    function lk_rp_penuh($n)
    {
        $n = round((float) $n);
        return ($n < 0 ? '-' : '') . 'Rp ' . number_format(abs($n), 0, ',', '.');
    }
}

if (!function_exists('lk_pct')) {
    function lk_pct($n, $desimal = 1)
    {
        return $n === null ? '-' : number_format((float) $n, $desimal, ',', '.') . '%';
    }
}

if (!function_exists('lk_tgl')) {
    function lk_tgl($tgl)
    {
        if (empty($tgl)) {
            return '-';
        }
        $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $t = strtotime($tgl);
        return date('j', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
    }
}

if (!function_exists('lk_bulan')) {
    // "2026-04" -> "Apr 2026"
    function lk_bulan($ym)
    {
        $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return $bulan[(int) substr($ym, 5, 2)] . ' ' . substr($ym, 0, 4);
    }
}

if (!function_exists('lk_h')) {
    function lk_h($s)
    {
        return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('lk_selisih_pct')) {
    // perubahan dalam persen dari pembanding; null bila pembanding 0
    function lk_selisih_pct($sekarang, $banding)
    {
        return abs((float) $banding) < 1 ? null : ((float) $sekarang - (float) $banding) / abs((float) $banding) * 100;
    }
}
