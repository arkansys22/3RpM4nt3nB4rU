<?php
function cek_session_akses_developer($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '1'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_administrator($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '2'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_staff_accounting($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '3'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_staff_admin($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '4'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_staff_sales($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '9'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_affiliate_super($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '10'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_affiliate_promax($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '11'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_affiliate_vip($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '12'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_client($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '5'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_guest($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '6'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_staff($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '7'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_partner($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT 1 FROM user WHERE id_session = ?", array($id))->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '8'){
    redirect(base_url().'panel');
  }
}

function hari_ini($w){
    $seminggu = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
    $hari_ini = $seminggu[$w];
    return $hari_ini;
}


function hari($date){

    $daftar_hari = array(
     'Sunday' => 'Minggu',
     'Monday' => 'Senin',
     'Tuesday' => 'Selasa',
     'Wednesday' => 'Rabu',
     'Thursday' => 'Kamis',
     'Friday' => 'Jumat',
     'Saturday' => 'Sabtu'
    );
    $namahari = $daftar_hari[date('l', strtotime($date))];

    return $namahari;
}

function tgl_indo($tgl){
        $tanggal = substr($tgl,8,2);
        $bulan = getBulan(substr($tgl,5,2));
        $tahun = substr($tgl,0,4);
        $waktu = substr($tgl,11,8);
        return $tanggal.' '.$bulan.' '.$tahun.' '.$waktu;
}



function time_ago($datetime)
{
    $time = strtotime($datetime);
    $now  = time();
    $diff = $now - $time;

    if ($diff < 60) {
        return $diff . ' detik lalu';
    }

    $minutes = floor($diff / 60);
    if ($minutes < 60) {
        return $minutes . ' menit lalu';
    }

    $hours = floor($minutes / 60);
    if ($hours < 24) {
        return $hours . ' jam lalu';
    }

    $days = floor($hours / 24);
    if ($days < 30) {
        return $days . ' hari lalu';
    }

    $months = floor($days / 30);
    if ($months < 12) {
        return $months . ' bulan lalu';
    }

    $years = floor($months / 12);
    return $years . ' tahun lalu';
}

function getBulan($bln){
            switch ($bln){
                case 1:
                    return "Jan";
                    break;
                case 2:
                    return "Feb";
                    break;
                case 3:
                    return "Mar";
                    break;
                case 4:
                    return "Apr";
                    break;
                case 5:
                    return "Mei";
                    break;
                case 6:
                    return "Jun";
                    break;
                case 7:
                    return "Jul";
                    break;
                case 8:
                    return "Agustus";
                    break;
                case 9:
                    return "September";
                    break;
                case 10:
                    return "Oktober";
                    break;
                case 11:
                    return "November";
                    break;
                case 12:
                    return "Desember";
                    break;
            }
        }

function format_nominal_salary($nominal, $satuan)
{
    if ($satuan === 'Persentase') {
        $angka = rtrim(rtrim(number_format((float) $nominal, 2, ',', '.'), '0'), ',');
        return $angka . '%';
    }

    return 'Rp ' . number_format((float) $nominal, 0, ',', '.') . ' / ' . strtolower($satuan);
}

if (!function_exists('terbilang')) {
    function terbilang($angka, $isFinal = true) {
        $angka = abs($angka);
        $huruf = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";

        if ($angka < 12) {
            $temp = $huruf[$angka];
        } else if ($angka < 20) {
            $temp = $huruf[$angka - 10] . " belas";
        } else if ($angka < 100) {
            $temp = $huruf[floor($angka / 10)] . " puluh " . terbilang($angka % 10, false);
        } else if ($angka < 200) {
            $temp = "seratus " . terbilang($angka - 100, false);
        } else if ($angka < 1000) {
            $temp = $huruf[floor($angka / 100)] . " ratus " . terbilang($angka % 100, false);
        } else if ($angka < 2000) {
            $temp = "seribu " . terbilang($angka - 1000, false);
        } else if ($angka < 1000000) {
            $temp = terbilang(floor($angka / 1000), false) . " ribu " . terbilang($angka % 1000, false);
        } else if ($angka < 1000000000) {
            $temp = terbilang(floor($angka / 1000000), false) . " juta " . terbilang($angka % 1000000, false);
        } else if ($angka < 1000000000000) {
            $temp = terbilang(floor($angka / 1000000000), false) . " milyar " . terbilang(fmod($angka, 1000000000), false);
        } else if ($angka < 1000000000000000) {
            $temp = terbilang(floor($angka / 1000000000000), false) . " triliun " . terbilang(fmod($angka, 1000000000000), false);
        }

        // Append "rupiah" only for the final number
        if ($isFinal) {
            $temp .= " rupiah";
        }

        return ucfirst(strtolower(trim($temp)));
    }

    function get_location_from_ip($ip) {
      $urls = [
          "http://ip-api.com/json/{$ip}",
          "https://ipwhois.app/json/{$ip}",
          "http://ipinfo.io/{$ip}/json"
      ];

      $mh = curl_multi_init();
      $curl_handles = [];

      foreach ($urls as $url) {
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_TIMEOUT, 2);
          $curl_handles[] = $ch;
          curl_multi_add_handle($mh, $ch);
      }

      $running = null;
      do {
          curl_multi_exec($mh, $running);
          usleep(100);
      } while ($running > 0);

      $results = [];
      foreach ($curl_handles as $ch) {
          $results[] = json_decode(curl_multi_getcontent($ch), true);
          curl_multi_remove_handle($mh, $ch);
      }

      curl_multi_close($mh);

      $city = '';
      $country = '';

      foreach ($results as $response) {
          if (!empty($response['city']) && !empty($response['country'])) {
              $city = $response['city'];
              $country = $response['country'];
              break;
          }
      }

      return trim("{$city}, {$country}", ', ');
  }

/**
 * Daftar variabel yang bisa disisipkan ke "Nama Kegiatan" di Susunan Acara per klien
 * (clients/susunan-acara/...), format tokennya {{token}}. Dipakai untuk render tombol
 * pilihan variabel di form tambah/edit kegiatan -- lihat juga susunan_acara_variabel_values()
 * dan render_susunan_acara_text().
 */
function susunan_acara_variabel_list()
{
    return [
        // Data Mempelai
        ['group' => 'Data Mempelai', 'token' => 'cpw_nama',            'label' => 'Nama Lengkap CPW'],
        ['group' => 'Data Mempelai', 'token' => 'cpw_panggilan',       'label' => 'Nama Panggilan CPW'],
        ['group' => 'Data Mempelai', 'token' => 'cpw_bapak',           'label' => 'Nama Bapak CPW'],
        ['group' => 'Data Mempelai', 'token' => 'cpw_bapak_panggilan', 'label' => 'Panggilan Bapak CPW'],
        ['group' => 'Data Mempelai', 'token' => 'cpw_ibu',             'label' => 'Nama Ibu CPW'],
        ['group' => 'Data Mempelai', 'token' => 'cpw_ibu_panggilan',   'label' => 'Panggilan Ibu CPW'],
        ['group' => 'Data Mempelai', 'token' => 'cpp_nama',            'label' => 'Nama Lengkap CPP'],
        ['group' => 'Data Mempelai', 'token' => 'cpp_panggilan',       'label' => 'Nama Panggilan CPP'],
        ['group' => 'Data Mempelai', 'token' => 'cpp_bapak',           'label' => 'Nama Bapak CPP'],
        ['group' => 'Data Mempelai', 'token' => 'cpp_bapak_panggilan', 'label' => 'Panggilan Bapak CPP'],
        ['group' => 'Data Mempelai', 'token' => 'cpp_ibu',             'label' => 'Nama Ibu CPP'],
        ['group' => 'Data Mempelai', 'token' => 'cpp_ibu_panggilan',   'label' => 'Panggilan Ibu CPP'],

        // Acara
        ['group' => 'Acara', 'token' => 'nama_klien',         'label' => 'Nama Klien'],
        ['group' => 'Acara', 'token' => 'tanggal_pernikahan', 'label' => 'Tanggal Pernikahan'],
        ['group' => 'Acara', 'token' => 'lokasi_acara',       'label' => 'Lokasi Acara'],
        ['group' => 'Acara', 'token' => 'mahar',              'label' => 'Mahar'],
        ['group' => 'Acara', 'token' => 'seserahan',          'label' => 'Simbolis Seserahan'],
        ['group' => 'Acara', 'token' => 'izin_ke',            'label' => 'Permohonan Izin Dilakukan ke'],

        // Petugas & Koordinator (umum, dipakai di akad maupun resepsi/pemberkatan)
        ['group' => 'Petugas & Koordinator', 'token' => 'koordinator_wanita', 'label' => 'Koordinator Keluarga Wanita'],
        ['group' => 'Petugas & Koordinator', 'token' => 'koordinator_pria',   'label' => 'Koordinator Keluarga Pria'],
        ['group' => 'Petugas & Koordinator', 'token' => 'jubir_wanita',       'label' => 'Jubir Keluarga Wanita'],
        ['group' => 'Petugas & Koordinator', 'token' => 'jubir_pria',         'label' => 'Jubir Keluarga Pria'],
        ['group' => 'Petugas & Koordinator', 'token' => 'penghulu',           'label' => 'Penghulu'],
        ['group' => 'Petugas & Koordinator', 'token' => 'wali',               'label' => 'Wali'],
        ['group' => 'Petugas & Koordinator', 'token' => 'saksi_wanita',       'label' => 'Saksi Pengantin Wanita'],
        ['group' => 'Petugas & Koordinator', 'token' => 'saksi_pria',         'label' => 'Saksi Pengantin Pria'],
        ['group' => 'Petugas & Koordinator', 'token' => 'qori',               'label' => 'Qori/Qoriah'],
        ['group' => 'Petugas & Koordinator', 'token' => 'nasihat_pernikahan', 'label' => 'Nasihat Pernikahan'],
        ['group' => 'Petugas & Koordinator', 'token' => 'pengapit',           'label' => 'Pengapit Pengantin Wanita'],
        ['group' => 'Petugas & Koordinator', 'token' => 'pembawa_melati',     'label' => 'Pembawa Kalung Melati'],
        ['group' => 'Petugas & Koordinator', 'token' => 'pembawa_mahar',      'label' => 'Pembawa Mas Kawin/Mahar'],
        ['group' => 'Petugas & Koordinator', 'token' => 'pembawa_cincin',     'label' => 'Pembawa Cincin Kawin'],

        // Petugas & Koordinator (khusus Pemberkatan/non-Muslim)
        ['group' => 'Petugas & Koordinator (Pemberkatan)', 'token' => 'pendeta',              'label' => 'Pendeta'],
        ['group' => 'Petugas & Koordinator (Pemberkatan)', 'token' => 'gereja',                'label' => 'Gereja'],
        ['group' => 'Petugas & Koordinator (Pemberkatan)', 'token' => 'pemimpin_doa',          'label' => 'Pemimpin Doa'],
        ['group' => 'Petugas & Koordinator (Pemberkatan)', 'token' => 'sambutan_pernikahan',   'label' => 'Sambutan Pernikahan'],
    ];
}

/**
 * Nilai aktual tiap token untuk SATU klien. Untuk bapak/ibu, kalau kolom pengganti
 * (freplacementname/mreplacementname, dst) terisi -- artinya orang tuanya sudah tiada dan
 * diwakilkan orang lain -- dipakai nama pengganti itu, bukan nama aslinya. Ini nyamain
 * logika yang sudah ada di clients/edit.php (toggleReplacementFields).
 */
function susunan_acara_variabel_values($clients)
{
    $cpwBapakNama      = !empty($clients->f_bride_freplacementname) ? $clients->f_bride_freplacementname : ($clients->f_bride_fathername ?? '');
    $cpwBapakPanggilan = !empty($clients->f_bride_freplacementcname) ? $clients->f_bride_freplacementcname : ($clients->f_bride_fathercname ?? '');
    $cpwIbuNama        = !empty($clients->f_bride_mreplacementname) ? $clients->f_bride_mreplacementname : ($clients->f_bride_mothername ?? '');
    $cpwIbuPanggilan   = !empty($clients->f_bride_mreplacementcname) ? $clients->f_bride_mreplacementcname : ($clients->f_bride_mothercname ?? '');

    $cppBapakNama      = !empty($clients->m_bride_freplacementname) ? $clients->m_bride_freplacementname : ($clients->m_bride_fathername ?? '');
    $cppBapakPanggilan = !empty($clients->m_bride_freplacementcname) ? $clients->m_bride_freplacementcname : ($clients->m_bride_fathercname ?? '');
    $cppIbuNama        = !empty($clients->m_bride_mreplacementname) ? $clients->m_bride_mreplacementname : ($clients->m_bride_mothername ?? '');
    $cppIbuPanggilan   = !empty($clients->m_bride_mreplacementcname) ? $clients->m_bride_mreplacementcname : ($clients->m_bride_mothercname ?? '');

    return [
        'cpw_nama'            => $clients->f_bride_fname ?? '',
        'cpw_panggilan'       => $clients->f_bride_cname ?? '',
        'cpw_bapak'           => $cpwBapakNama,
        'cpw_bapak_panggilan' => $cpwBapakPanggilan,
        'cpw_ibu'             => $cpwIbuNama,
        'cpw_ibu_panggilan'   => $cpwIbuPanggilan,
        'cpp_nama'            => $clients->m_bride_fname ?? '',
        'cpp_panggilan'       => $clients->m_bride_cname ?? '',
        'cpp_bapak'           => $cppBapakNama,
        'cpp_bapak_panggilan' => $cppBapakPanggilan,
        'cpp_ibu'             => $cppIbuNama,
        'cpp_ibu_panggilan'   => $cppIbuPanggilan,
        'nama_klien'          => $clients->client_name ?? '',
        'tanggal_pernikahan'  => !empty($clients->wedding_date) ? trim(hari($clients->wedding_date) . ', ' . tgl_indo($clients->wedding_date)) : '',
        'lokasi_acara'        => $clients->location ?? '',
        'mahar'               => $clients->mahr ?? '',
        'seserahan'           => $clients->handover ?? '',
        'izin_ke'             => $clients->pmizin ?? '',

        'koordinator_wanita'  => $clients->female_coor ?? '',
        'koordinator_pria'    => $clients->male_coor ?? '',
        'jubir_wanita'        => $clients->f_spokesman ?? '',
        'jubir_pria'          => $clients->m_spokesman ?? '',
        'penghulu'            => $clients->wedding_officiant ?? '',
        'wali'                => $clients->guardian ?? '',
        'saksi_wanita'        => $clients->f_witness ?? '',
        'saksi_pria'          => $clients->m_witness ?? '',
        'qori'                => $clients->qori ?? '',
        'nasihat_pernikahan'  => $clients->advice_doa ?? '',
        'pengapit'            => $clients->clamp ?? '',
        'pembawa_melati'      => $clients->jasmine_carrier ?? '',
        'pembawa_mahar'       => $clients->mahr_carrier ?? '',
        'pembawa_cincin'      => $clients->ring_carrier ?? '',

        'pendeta'             => $clients->pastor ?? '',
        'gereja'              => $clients->church ?? '',
        'pemimpin_doa'        => $clients->prayer ?? '',
        'sambutan_pernikahan' => $clients->wedding_speech ?? '',
    ];
}

/**
 * Ganti semua token {{token}} di $text jadi nilai aktualnya untuk $clients. Dipakai HANYA
 * saat menampilkan (daftar kegiatan & mode presentasi) -- nama_kegiatan yang tersimpan di
 * DB tetap berupa template mentah (dengan token-nya), supaya tetap bisa diedit ulang.
 */
function render_susunan_acara_text($text, $clients)
{
    $values = susunan_acara_variabel_values($clients);

    $search = [];
    $replace = [];
    foreach ($values as $token => $value) {
        $search[] = '{{' . $token . '}}';
        $replace[] = $value;
    }

    return str_replace($search, $replace, $text);
}

}
