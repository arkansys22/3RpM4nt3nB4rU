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

    // Kategori "Biaya Komisi" -- di finance-operational/create dipilihkan Penerima Komisi
    // (user aktif), disimpan di operational_acc.staff_id_session juga, dan ikut masuk rekap gaji.
    const KATEGORI_OPERASIONAL_KOMISI = '6100.02';

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
    // (kategori 6201.01 gaji + 6100.02 komisi) dipakai TERPISAH, lihat get_detail_gaji_operational().
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
    // (operational_acc, kategori 6201.01 gaji + 6100.02 komisi) buat satu user di satu bulan --
    // independen dari kategori_gaji/Setting Salary, jadi tetap muncul di
    // laporan meski user itu belum punya kategori salary apa pun di-assign.
    // Satu baris per transaksi (bisa lebih dari satu dalam sebulan, mis.
    // gaji pokok + lembur dicatat terpisah), sudah dalam format yang sama
    // dengan hitung_detail_gaji() supaya bisa langsung digabung ke
    // $detail_gaji di controller.
    public function get_detail_gaji_operational($user_id_session, $periode)
    {
        $this->db->select('kategori, nama_transaksi, tanggal_transaksi, nominal_transaksi');
        $this->db->from('operational_acc');
        $this->db->where_in('kategori', [self::KATEGORI_OPERASIONAL_GAJI, self::KATEGORI_OPERASIONAL_KOMISI]);
        $this->db->where('staff_id_session', $user_id_session);
        $this->db->where("DATE_FORMAT(tanggal_transaksi, '%Y-%m') =", $periode);
        $this->db->order_by('tanggal_transaksi', 'ASC');
        $rows = $this->db->get()->result();

        $detail_list = [];
        foreach ($rows as $row) {
            $nominal = (float) $row->nominal_transaksi;
            $komisi = $row->kategori === self::KATEGORI_OPERASIONAL_KOMISI;
            $detail_list[] = [
                'nama_kategori' => $komisi ? 'Komisi: ' . $row->nama_transaksi : $row->nama_transaksi,
                'satuan_gaji' => $komisi ? 'Komisi' : 'Bulanan',
                'nominal_gaji' => $nominal,
                'keterangan' => 'Tercatat ' . date('d M Y', strtotime($row->tanggal_transaksi)),
                'jumlah' => $nominal,
            ];
        }

        return $detail_list;
    }

    // Fee crew NYATA dari Finance Project (form "Tambah Crew") buat satu user di satu bulan, untuk user yang
    // TIDAK punya kategori salary satuan Project -- supaya fee crew tetap masuk rekap gaji walau belum di-assign.
    // Kalau user punya kategori Project, fee crew sudah dihitung lewat hitung_detail_gaji() (jangan dobel).
    // Format baris sama dengan hitung_detail_gaji(); array kosong kalau tidak ada fee.
    public function get_detail_fee_crew_tambahan($user_id_session, $kategori_list, $periode)
    {
        foreach ($kategori_list as $k) {
            if ($k->satuan_gaji === 'Project') {
                return [];
            }
        }
        $fee = $this->hitung_fee_crew($user_id_session, $periode);
        if ($fee->total <= 0) {
            return [];
        }
        return [[
            'nama_kategori' => 'Crew WO',
            'satuan_gaji' => 'Project',
            'nominal_gaji' => $fee->total,
            'keterangan' => 'Total fee crew dari ' . $fee->jumlah_project . ' project bulan ini',
            'jumlah' => $fee->total,
        ]];
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

    // Ringkasan pendapatan fee crew NYATA (Finance Project, form "Tambah Crew") satu user, untuk kartu di dashboard
    // staff/freelance. Sumber fee-nya sama dengan Crew WO di Gaji Saya (project_acc.detail = user.crews_idsession), tapi
    // dikelompokkan menurut TANGGAL EVENT (project.event_date), bukan tanggal fee dicatat: fee event 28 Nov yang dicatat
    // 29 Okt tetap pendapatan November. (Gaji Saya tetap memakai tanggal catat, karena itu kapan fee-nya dibayarkan.)
    // Fee yang project-nya tidak ditemukan memakai tanggal catatnya.
    // - total_semua  : TOTAL SEMUA PENDAPATAN sampai akhir bulan ini = total_gaji + total_event.
    // - total_gaji / gaji_semua : gaji & komisi NYATA yang dicatat di Finance Operational (operational_acc kategori 6201.01 +
    //                  6100.02, staff_id_session = user ini); gaji_semua = rincian per transaksi (terbaru dulu). Tidak
    //                  tergantung akun terhubung ke data crew atau tidak.
    // - total_event / event_semua : fee semua event sampai akhir bulan ini (termasuk bulan ini); event_semua = rincian per
    //                  event (terbaru dulu), jumlah_event_semua = jumlah barisnya.
    // - bulan_ini / event_bulan_ini : sama, untuk event yang tanggalnya di bulan ini (urut tanggal).
    // - event_mendatang : event yang sudah dijadwalkan untuk crew ini (crew_projects, tanggal event >= hari ini, project
    //                  tidak dihapus), urut tanggal; jumlahnya = jumlah_event_mendatang.
    public function get_ringkasan_pendapatan_crew($user_id_session)
    {
        $r = (object) [
            'ada_crew' => false,
            'total_semua' => 0.0,
            'total_gaji' => 0.0,
            'jumlah_gaji' => 0,
            'gaji_semua' => [],
            'total_event' => 0.0,
            'jumlah_event_semua' => 0,
            'event_semua' => [],
            'bulan_ini' => 0.0,
            'jumlah_event_bulan_ini' => 0,
            'event_bulan_ini' => [],
            'jumlah_event_mendatang' => 0,
            'event_mendatang' => [],
        ];

        $hari_ini = date('Y-m-d');
        $akhir_bulan = date('Y-m-t');

        // Gaji & komisi nyata (sama dengan get_detail_gaji_operational, tapi semua bulan sampai akhir bulan ini)
        $gaji = $this->db->select('kategori, nama_transaksi, tanggal_transaksi, nominal_transaksi')
            ->from('operational_acc')
            ->where_in('kategori', [self::KATEGORI_OPERASIONAL_GAJI, self::KATEGORI_OPERASIONAL_KOMISI])
            ->where('staff_id_session', $user_id_session)
            ->where('tanggal_transaksi <=', $akhir_bulan)
            ->order_by('tanggal_transaksi', 'DESC')
            ->order_by('id', 'DESC')
            ->get()->result();
        foreach ($gaji as $g) {
            $g->nominal = (float) $g->nominal_transaksi;
            $g->jenis = $g->kategori === self::KATEGORI_OPERASIONAL_KOMISI ? 'Komisi' : 'Gaji';
        }
        $r->gaji_semua = $gaji;
        $r->jumlah_gaji = count($gaji);
        $r->total_gaji = array_sum(array_column($gaji, 'nominal'));
        $r->total_semua = $r->total_gaji;

        // crews_idsession kosong / '-' = akun belum terhubung ke data crew (lihat catatan di hitung_fee_crew)
        $u = $this->db->select('crews_idsession')->get_where('user', ['id_session' => $user_id_session])->row();
        $crew_id = $u ? trim((string) $u->crews_idsession) : '';
        if ($crew_id === '' || $crew_id === '-') {
            return $r;
        }
        $r->ada_crew = true;

        $semua = $this->fee_crew_per_event($crew_id, null, $akhir_bulan);
        $r->event_semua = array_reverse($semua);
        $r->jumlah_event_semua = count($semua);
        $r->total_event = array_sum(array_column($semua, 'fee'));
        $r->total_semua = $r->total_gaji + $r->total_event;

        $bulan = $this->fee_crew_per_event($crew_id, date('Y-m-01'), $akhir_bulan);
        $r->event_bulan_ini = $bulan;
        $r->jumlah_event_bulan_ini = count($bulan);
        $r->bulan_ini = array_sum(array_column($bulan, 'fee'));

        // Satu baris per event (crew bisa punya beberapa peran di event yang sama -> digabung)
        $event = $this->db->query(
            "SELECT p.id_session AS project_id, p.event_date, p.client_name, p.location,
                    GROUP_CONCAT(DISTINCT cp.role ORDER BY cp.role SEPARATOR ', ') AS role
             FROM crew_projects cp
             JOIN project p ON p.id_session = cp.project_id
             WHERE cp.crew_id = ? AND p.event_date >= ? AND (p.status IS NULL OR p.status <> 'delete')
             GROUP BY p.id_session, p.event_date, p.client_name, p.location
             ORDER BY p.event_date ASC",
            [$crew_id, $hari_ini]
        )->result();
        $r->event_mendatang = $event;
        $r->jumlah_event_mendatang = count($event);

        return $r;
    }

    // Fee crew NYATA (project_acc.detail = crews_idsession) per event, untuk event yang tanggalnya di antara $dari dan
    // $sampai (null = tanpa batas), urut tanggal event naik. Tanggal event = project.event_date; kalau project-nya tidak
    // ditemukan, tanggal catat fee paling awal (client_name/location null). Fee satu event dijumlahkan (bisa lebih dari
    // satu transaksi); 'role' = peran crew itu di event tersebut (crew_projects), kosong kalau tidak terdaftar.
    private function fee_crew_per_event($crew_id, $dari, $sampai)
    {
        $sql = "SELECT pa.project_id_session AS project_id,
                       COALESCE(p.event_date, MIN(pa.tanggal_transaksi)) AS tgl,
                       p.client_name, p.location,
                       SUM(pa.nominal_transaksi) AS fee,
                       (SELECT GROUP_CONCAT(DISTINCT cp.role ORDER BY cp.role SEPARATOR ', ') FROM crew_projects cp
                         WHERE cp.project_id = pa.project_id_session AND cp.crew_id = ?) AS role
                FROM project_acc pa
                LEFT JOIN project p ON p.id_session = pa.project_id_session
                WHERE pa.detail = ?
                GROUP BY pa.project_id_session, p.event_date, p.client_name, p.location
                HAVING 1 = 1";
        $param = [$crew_id, $crew_id];
        if ($dari !== null) {
            $sql .= ' AND tgl >= ?';
            $param[] = $dari;
        }
        if ($sampai !== null) {
            $sql .= ' AND tgl <= ?';
            $param[] = $sampai;
        }
        $sql .= ' ORDER BY tgl ASC';

        $rows = $this->db->query($sql, $param)->result();
        foreach ($rows as $row) {
            $row->event_date = $row->tgl;
            $row->fee = (float) $row->fee;
        }

        return $rows;
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
