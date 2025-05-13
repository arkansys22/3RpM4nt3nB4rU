<?php
function cek_session_akses_developer($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '1'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_administrator($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '2'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_staff_accounting($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '3'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_staff_admin($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '4'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_client($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '5'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_guest($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '6'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_staff($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
  if ($session == '0' AND $ci->session->userdata('level') != '7'){
    redirect(base_url().'panel');
  }
}

function cek_session_akses_partner($id){
  $ci = & get_instance();
  $session = $ci->db->query("SELECT * FROM user WHERE user.id_session='$id'")->num_rows();
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
                    return "Agu";
                    break;
                case 9:
                    return "Sep";
                    break;
                case 10:
                    return "Okt";
                    break;
                case 11:
                    return "Nov";
                    break;
                case 12:
                    return "Des";
                    break;
            }
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
  
}
