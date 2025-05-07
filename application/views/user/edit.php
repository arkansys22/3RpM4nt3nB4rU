<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const levelSelect = document.querySelector('select[name="level"]');
        const clientIdField = document.querySelector('select[name="clientid"]').closest('div');
        const crewIdField = document.querySelector('select[name="crewid"]').closest('div');
        const partnerIdField = document.querySelector('select[name="partnerid"]').closest('div'); // Ensure Partner ID field is handled
        const clientIdSelect = document.querySelector('select[name="clientid"]');
        const crewIdSelect = document.querySelector('select[name="crewid"]');
        const partnerIdSelect = document.querySelector('select[name="partnerid"]'); // Ensure Partner ID select is handled

        function toggleFields() {
          if (levelSelect.value === '5') { // Assuming '5' is the value for client level
            clientIdField.style.display = 'block';
            crewIdField.style.display = 'none';
            partnerIdField.style.display = 'none'; // Hide Partner ID
            crewIdSelect.value = '-';
            partnerIdSelect.value = '-';
          } else if (levelSelect.value === '8') { // Assuming '8' is the value for partner level
            clientIdField.style.display = 'none';
            crewIdField.style.display = 'none';
            partnerIdField.style.display = 'block'; // Show Partner ID
            clientIdSelect.value = '-';
            crewIdSelect.value = '-';
          } else {
            clientIdField.style.display = 'none';
            crewIdField.style.display = 'block';
            partnerIdField.style.display = 'none'; // Hide Partner ID
            clientIdSelect.value = '-';
            partnerIdSelect.value = '-';
          }
        }

        levelSelect.addEventListener('change', toggleFields);
        toggleFields(); // Initial call to set the correct visibility on page load
      });
    </script>
</head>
<body
    x-data="{ page: 'projects', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode'));
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark text-bodydark bg-boxdark-2': darkMode === true}"
  >
    <!-- ===== Preloader Start ===== -->
  <div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})" class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent">
    </div>
  </div>
  <!-- ===== Preloader End ===== -->
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <?php $this->load->view('backend/sidebar')?>

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <?php $this->load->view('backend/header')?>

      <!-- ===== Main Content Start ===== -->
      <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
          <div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-9">
            <div class="col-span-12 rounded-sm border border-stroke bg-white px-5 pb-5 pt-7.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5">
              <h1 class="text-2xl font-bold mb-4">Edit Pengguna</h1>
              <form action="<?= site_url('user/update/'.$pc->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Username</label>
                <input type="text" name="username" value="<?= $pc->username ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Nama</label>
                <input type="text" name="nama" value="<?= $pc->nama ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Email</label>
                <input type="email" name="email" value="<?= $pc->email ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Password</label>
                <input type="password" name="password" placeholder="Ganti password isi disini, jika tidak abaikan" class="w-full px-4 py-2 border rounded mb-4w-full px-4 py-2 border rounded mb-4">
                
                <label class="block mb-2">Level</label>
                <select name="level" class="w-full px-4 py-2 border rounded mb-4" required> 
                      <?php foreach ($level as $p) {
                            if ($pc->level == $p['user_level_id']){
                              echo"<option selected='selected' value='$p[user_level_id]'>$p[user_level_nama]</option> ";
                            }else{
                              echo"<option value='$p[user_level_id]'>$p[user_level_nama]</option>";
                        }
                      } ?>                    
                </select>

                <div>
                  <label class="block mb-2">Crews ID</label>
                  <select name="crewid" class="w-full px-4 py-2 border rounded mb-4" required> 
                        <option value="-">-</option>
                        <?php foreach ($crews as $p) {
                              if ($pc->crews_idsession == $p['id_session']){
                                echo"<option selected='selected' value='$p[id_session]'>$p[crew_name]</option> ";
                              }else{
                                echo"<option value='$p[id_session]'>$p[crew_name]</option>";
                          }
                        } ?>
                  </select>
                </div>

                <div>
                  <label class="block mb-2">Client ID</label>
                  <select name="clientid" class="w-full px-4 py-2 border rounded mb-4" required> 
                        <option value="-">-</option>
                        <?php foreach ($clients as $p) {
                              if ($pc->client_idsession == $p['id_session']){
                                echo"<option selected='selected' value='$p[id_session]'>$p[client_name]</option> ";
                              }else{
                                echo"<option value='$p[id_session]'>$p[client_name]</option>";
                          }
                        } ?>                    
                  </select>
                </div>

                <div>
                  <label class="block mb-2">Partner ID</label>
                  <select name="partnerid" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="-">-</option>
                    <?php foreach ($partner as $p) { // Assuming $partner is provided in the controller
                      if ($pc->partner_idsession == $p['id_session']) {
                        echo "<option selected='selected' value='$p[id_session]'>$p[partner_name]</option>";
                      } else {
                        echo "<option value='$p[id_session]'>$p[partner_name]</option>";
                      }
                    } ?>
                  </select>
                </div>

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('user/lihat/'.$pc->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
  </div>
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js"></script>
</body>
</html>
