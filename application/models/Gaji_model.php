<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Perhitungan salary aktual satu user di satu bulan — dipakai bersama oleh
// Crud_kategori_gaji (Setting Salary, admin Fin & Acc) dan Aspanel (Rekap
// Gaji Saya, self-service tiap staff), supaya rumusnya konsisten di kedua
// tempat.
class Gaji_model extends CI_Model {

    // Hitung nominal aktual satu kategori untuk satu user di satu bulan.
    // - Bulanan: nominalnya sendiri, flat.
    // - Harian: nominal x jumlah hari Hadir di absensi bulan itu.
    // - Project: nominal x jumlah project yang event_date-nya di bulan itu
    //   (dari jadwal crew_projects, sama seperti dashboard staff).
    // - Persentase: persen x total pencapaian sales (closing) bulan itu,
    //   pakai aturan "achieved" yang sama dengan Aspanel::getEstimasiRevenue().
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
            $sql = "SELECT COUNT(DISTINCT project.id_session) AS total
                    FROM crew_projects
                    JOIN project ON project.id_session = crew_projects.project_id
                    JOIN user ON user.crews_idsession = crew_projects.crew_id
                    WHERE user.id_session = ?
                    AND DATE_FORMAT(project.event_date, '%Y-%m') = ?";
            $jumlah_project = (int) $this->db->query($sql, [$user_id_session, $periode])->row()->total;
            $detail['jumlah'] = $jumlah_project * $nominal;
            $detail['keterangan'] = $jumlah_project . ' project x Rp ' . number_format($nominal, 0, ',', '.');
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
