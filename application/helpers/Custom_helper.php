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
          function terbilang($angka) {
              $angka = (int) $angka;
      
              // Satuan angka
              $huruf = array(
                  '', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan',
                  'Sepuluh', 'Sebelas', 'Dua Belas', 'Tiga Belas', 'Empat Belas', 'Lima Belas', 'Enam Belas',
                  'Tujuh Belas', 'Delapan Belas', 'Sembilan Belas', 'Dua Puluh', 'Tiga Puluh', 'Empat Puluh',
                  'Lima Puluh', 'Enam Puluh', 'Tujuh Puluh', 'Delapan Puluh', 'Sembilan Puluh', 'Seratus'
              );
      
              // Satuan besar seperti ribu, juta, miliar
              $unit = array(
                  '', 'Ribu', 'Juta', 'Miliar', 'Triliun'
              );
      
              // Angka 0
              if ($angka == 0) {
                  return 'Nol Rupiah';
              }
      
              $result = '';
              $unitIndex = 0;
      
              // Pecah angka menjadi bagian 3 digit
              while ($angka > 0) {
                  $bagian = $angka % 1000;
                  if ($bagian > 0) {
                      $result = terbilang_hubungan($bagian, $huruf) . ' ' . $unit[$unitIndex] . ' ' . $result;
                  }
                  $angka = floor($angka / 1000);
                  $unitIndex++;
              }
      
              // Menghapus spasi ekstra dan menambahkan "Rupiah" di akhir dengan benar
              return trim($result) . ' Rupiah'; // Tambahkan spasi sebelum 'Rupiah'
          }
      
          // Fungsi bantu untuk menangani 3 digit
          function terbilang_hubungan($angka, $huruf) {
              $result = '';
      
              // Proses ratusan
              if ($angka >= 100) {
                  // Memastikan angka 100 hingga 199 menjadi 'Seratus'
                  if ($angka >= 100 && $angka < 200) {
                      $result .= 'Seratus ';
                  } else {
                      $result .= isset($huruf[(int)($angka / 100)]) ? $huruf[(int)($angka / 100)] . ' Ratus ' : '';
                  }
                  $angka = $angka % 100;
              }
      
              // Proses puluhan
              if ($angka >= 20) {
                  $result .= isset($huruf[(int)($angka / 10) * 10]) ? $huruf[(int)($angka / 10) * 10] . ' ' : '';
                  $angka = $angka % 10;
              }
      
              // Proses satuan
              if ($angka > 0) {
                  $result .= isset($huruf[$angka]) ? $huruf[$angka] . ' ' : '';
              }
      
              return trim($result);
          }
      }
      