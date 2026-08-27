<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Perhitungan salary aktual satu user di satu bulan — dipakai bersama oleh
// Crud_kategori_gaji (Setting Salary, admin Fin & Acc) dan Aspanel (Rekap
// Gaji Saya, self-service tiap staff), supaya rumusnya konsisten di kedua
// tempat.
class Gaji_model extends CI_Model {

    // Kategori operational_kategori untuk "Biaya Gaji, Lembur & THR" -- ini
    // yang dipakai finance-operational/create buat menampilkan pilihan Staff
    // (lihat Crud_finance_operational), jadi transaksi di kategori ini sudah
    // terkait ke satu user lewat operational_acc.staff_id_session.
    const KATEGORI_OPERASIONAL_GAJI = '6201.01';

    // Hitung nominal aktual satu kategori untuk satu user di satu bulan.
    // - Bulanan: nominalnya sendiri, flat.
    // - Harian: nominal x jumlah hari Hadir di absensi bulan itu.
    // - Project: BUKAN nominal_gaji x jumlah project lagi, tapi dijumlah dari
    //   fee crew NYATA yang dicatat di Finance Project (project_acc, form
    //   "Tambah Crew") buat user ini di bulan itu -- supaya angkanya sesuai
    //   yang benar-benar tercatat, bukan estimasi tarif flat.
    // - Persentase: persen x total pencapaian sales (closing) bulan itu,
    //   pakai aturan "achieved" yang sama dengan Aspanel::getEstimasiRevenue().
    // Catatan: ini cuma buat kategori yang di-assign lewat Setting Salary
    // (user_kategori_gaji). Transaksi gaji nyata dari Finance Operational
    // (kategori 6201.01) dipakai TERPISAH, lihat get_detail_gaji_operational().
    public function hitung_detail_gaji($user_id_session, $kategori, $periode)
    {
        $nominal = (float) $kategori->nominal_gaji;
        $detail = [
            'nama_kategori' => $kategori->nama_kategori,
            'satuan_gaji' => $kategori->satuan_gaji,
            'nominal_gaji' => $nominal,
            'keterangan' => '',
            'jumlah' => 0,
        ];

        if ($kategori->satuan_gaji === 'Bulanan') {
            $detail['jumlah'] = $nominal;
            $detail['keterangan'] = 'Gaji bulanan tetap';
            return $detail;
        }

        if ($kategori->satuan_gaji === 'Harian') {
            $hari_hadir = (int) $this->db->where('user_id_session', $user_id_session)
                ->where('status', 'Hadir')
                ->where("DATE_FORMAT(tanggal, '%Y-%m') =", $periode)
                ->count_all_results('user_absensi');
            $detail['jumlah'] = $hari_hadir * $nominal;
            $detail['keterangan'] = $hari_hadir . ' hari hadir x Rp ' . number_format($nominal, 0, ',', '.');
            return $detail;
        }

        if ($kategori->satuan_gaji === 'Project') {
            $fee_crew = $this->hitung_fee_crew($user_id_session, $periode);
            $detail['nama_kategori'] = 'Crew WO';
            $detail['jumlah'] = $fee_crew->total;
            $detail['keterangan'] = $fee_crew->jumlah_project > 0
                ? 'Total fee crew dari ' . $fee_crew->jumlah_project . ' project bulan ini'
                : 'Belum ada fee crew tercatat di Finance Project bulan ini';
            return $detail;
        }

        if ($kategori->satuan_gaji === 'Persentase') {
            $pencapaian = $this->hitung_pencapaian_sales($user_id_session, $periode);
            $detail['jumlah'] = $pencapaian * ($nominal / 100);
            $detail['keterangan'] = number_format($nominal, 2, ',', '.') . '% x Rp ' . number_format($pencapaian, 0, ',', '.') . ' pencapaian';
            return $detail;
        }

        return $detail;
    }

    // Transaksi gaji NYATA yang sudah dicatat di Finance Operational
    // (operational_acc, kategori 6201.01) buat satu user di satu bulan --
    // independen dari kategori_gaji/Setting Salary, jadi tetap muncul di
    // laporan meski user itu belum punya kategori salary apa pun di-assign.
    // Satu baris per transaksi (bisa lebih dari satu dalam sebulan, mis.
    // gaji pokok + lembur dicatat terpisah), sudah dalam format yang sama
    // dengan hitung_detail_gaji() supaya bisa langsung digabung ke
    // $detail_gaji di controller.
    public function get_detail_gaji_operational($user_id_session, $periode)
    {
        $this->db->select('nama_transaksi, tanggal_transaksi, nominal_transaksi');
        $this->db->from('operational_acc');
        $this->db->where('kategori', self::KATEGORI_OPERASIONAL_GAJI);
        $this->db->where('staff_id_session', $user_id_session);
        $this->db->where("DATE_FORMAT(tanggal_transaksi, '%Y-%m') =", $periode);
        $this->db->order_by('tanggal_transaksi', 'ASC');
        $rows = $this->db->get()->result();

        $detail_list = [];
        foreach ($rows as $row) {
            $nominal = (float) $row->nominal_transaksi;
            $detail_list[] = [
                'nama_kategori' => $row->nama_transaksi,
                'satuan_gaji' => 'Bulanan',
                'nominal_gaji' => $nominal,
                'keterangan' => 'Tercatat ' . date('d M Y', strtotime($row->tanggal_transaksi)),
                'jumlah' => $nominal,
            ];
        }

        return $detail_list;
    }

    // Total fee crew NYATA yang sudah dicatat di Finance Project (project_acc,
    // dari form "Tambah Crew" -- lihat Crud_finance_project::store2()) buat
    // satu user di satu bulan. Transaksi crew disimpan dengan project_acc.detail
    // = crews.id_session pemilihnya (BUKAN teks bebas), jadi di-JOIN ke
    // user.crews_idsession buat tahu itu punya siapa.
    //
    // PENTING: banyak user yang belum punya data crew nyata punya
    // crews_idsession berisi placeholder '-' atau string kosong (bukan NULL).
    // Transaksi non-crew (detail-nya teks bebas) yang kebetulan juga '-'/kosong
    // bisa ke-JOIN ke SEMUA user placeholder itu sekaligus kalau tidak
    // di-exclude eksplisit di sini -- makanya ada guard NOT IN ('-', '').
    private function hitung_fee_crew($user_id_session, $periode)
    {
        $this->db->select('COALESCE(SUM(pa.nominal_transaksi),0) AS total, COUNT(DISTINCT pa.project_id_session) AS jumlah_project');
        $this->db->from('project_acc pa');
        $this->db->join('user u', 'u.crews_idsession = pa.detail', 'inner');
        $this->db->where('u.id_session', $user_id_session);
        $this->db->where("pa.detail NOT IN ('-','')", NULL, FALSE);
        $this->db->where("DATE_FORMAT(pa.tanggal_transaksi, '%Y-%m') =", $periode);

        $row = $this->db->get()->row();
        $row->total = (float) $row->total;
        $row->jumlah_project = (int) $row->jumlah_project;

        return $row;
    }

    // Total pencapaian sales (closing) satu user di satu bulan — project
    // dihitung "achieved" kalau Pembayaran Kesatu sudah Paid (tanggal
    // berapa pun) dan Pembayaran Kedua Paid DI BULAN itu. Aturan sama
    // persis dengan Aspanel::getEstimasiRevenue() supaya konsisten dengan
    // angka pencapaian yang sudah dipakai di dashboard & sales-ranking.
    private function hitung_pencapaian_sales($user_id_session, $periode)
    {
        $periode_escaped = $this->db->escape($periode);
        $this->db->select('COALESCE(SUM(project.value),0) AS total');
        $this->db->from('project');
        $this->db->where('project.closing_user_idsession', $user_id_session);
        $this->db->where("
            EXISTS(
                SELECT 1 FROM payment p1
                WHERE p1.id_session = project.id_session
                AND p1.metodep LIKE 'Pembayaran Kesatu%'
                AND p1.status = 'Paid'
            )
        ", NULL, FALSE);
        $this->db->where("
            EXISTS(
                SELECT 1 FROM payment p2
                WHERE p2.id_session = project.id_session
                AND p2.metodep LIKE 'Pembayaran Kedua%'
                AND p2.status = 'Paid'
                AND DATE_FORMAT(p2.date, '%Y-%m') = $periode_escaped
            )
        ", NULL, FALSE);

        return (float) $this->db->get()->row()->total;
    }

    // Semua kategori salary yang di-assign ke satu user, sudah di-JOIN ke
    // kategori_gaji.
    public function get_kategori_user($user_id_session)
    {
        $sql = "SELECT kategori_gaji.id, kategori_gaji.nama_kategori,
                       kategori_gaji.satuan_gaji, kategori_gaji.nominal_gaji
                FROM user_kategori_gaji ukg
                JOIN kategori_gaji ON kategori_gaji.id = ukg.kategori_gaji_id
                WHERE ukg.user_id_session = ?
                ORDER BY kategori_gaji.nama_kategori ASC";

        return $this->db->query($sql, [$user_id_session])->result();
    }
}
