<?php date_default_timezone_set('Asia/Jakarta'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Project</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'project', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Tambah Project</h1>
              <form action="<?= site_url('project/store') ?>" method="post" class="bg-white p-6 shadow-md rounded">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

                <label class="block mb-2">Pilih Dari Klien Potensial</label>
                <select id="potensialSelect" name="potensial_clients" class="w-full px-4 py-2 border rounded mb-4" required> 
                    <option value="">-- Pilih Klien Potensial --</option>
                  <?php if (!empty($potensial_clients)): ?>
                      <?php foreach ($potensial_clients as $p): ?>
                          <option 
                              value="<?= isset($p['id_session']) ? $p['id_session'] : ''; ?>"
                              data-name="<?= isset($p['pc_name']) ? $p['pc_name'] : ''; ?>"
                              data-date="<?= isset($p['event_date']) ? $p['event_date'] : ''; ?>"
                              data-lokasi="<?= isset($p['location']) ? $p['location'] : ''; ?>"
                              <?= (isset($project) && isset($project->potensial_clients_id_session) && $project->potensial_clients_id_session == $p['id_session']) ? 'selected' : ''; ?> >
                              
                              <?= isset($p['pc_name']) ? $p['pc_name'] : 'Tanpa Nama'; ?>
                          </option>
                      <?php endforeach; ?>
                  <?php endif; ?>
                </select>

                <label class="block mb-2">Nama Project</label>
                <input type="text" name="project_name" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Closingan</label>
                <select name="closing_user_idsession" class="w-full px-4 py-2 border rounded mb-4" required> 
                  <option value="">-- Pilih Closingan --</option>
                      <?php foreach ($user as $p) {
                            if ($project->closing_user_idsession == $p['id_session']){
                              echo"<option selected='selected' value='$p[id_session]'>$p[nama]</option> ";
                            }else{
                              echo"<option value='$p[id_session]'>$p[nama]</option>";
                        }
                      } ?>                    
                </select>



                <label class="block mb-2">Nama Client</label>
                <input type="text" id="client_name" name="client_name" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal Pernikahan</label>
                <input type="text" id="event_date" name="event_date" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Value</label>
                <input type="text" id="value_project" class="w-full px-4 py-2 border rounded mb-4" name="value" required>


                <label class="block mb-2">Detail</label>
                <textarea name="detail" rows="20" cols="100%" class="w-full px-4 py-2 border rounded mb-4" required></textarea>

                <label class="block mb-2">Agama</label>
                <select name="religion" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="">Pilih Agama</option>
                    <option value="Islam" <?= set_select('religion', 'Islam') ?>>Islam</option>
                    <option value="Kristen" <?= set_select('religion', 'Kristen') ?>>Kristen</option>
                    <option value="Katolik" <?= set_select('religion', 'Katolik') ?>>Katolik</option>
                    <option value="Hindu" <?= set_select('religion', 'Hindu') ?>>Hindu</option>
                    <option value="Buddha" <?= set_select('religion', 'Buddha') ?>>Buddha</option>
                    <option value="Lainnya" <?= set_select('religion', 'Lainnya') ?>>Lainnya</option>
                  </select>
                
                <label class="block mb-2">Lokasi</label>
                <input type="text" id="lokasi" name="location" class="w-full px-4 py-2 border rounded mb-4" required>

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                <a href="<?= site_url('project') ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
  <script>
    function formatNumber(input) {
      let value = input.value.replace(/\D/g, ''); // Hanya angka
      value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambah titik setiap 3 digit
      input.value = value;
  }
  </script>

  <script>
    document.getElementById('potensialSelect').addEventListener('change', function() {

        let selectedValue = this.value;

        if (!selectedValue) {
            document.getElementById('value_project').value = '';
            return;
        }

        // AJAX ambil total
        fetch("<?= site_url('Crud_project/get_total_penawaran') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id_session=" + selectedValue + "&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>"
        })
        .then(response => response.json())
        .then(data => {
            let total = data.total ? data.total : 0;
            total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('value_project').value = total;
        })
        .catch(err => console.log('ERROR AJAX:', err));

        // ambil data dari option
        let selectedOption = this.options[this.selectedIndex];

        let name = selectedOption.getAttribute('data-name');
        let date = selectedOption.getAttribute('data-date');
        let location = selectedOption.getAttribute('data-lokasi');

    
        document.getElementById('client_name').value = name || '';
        document.getElementById('event_date').value = date || '';
        document.getElementById('lokasi').value = location || '';
    });
  </script>
  <script>
    window.addEventListener('load', function() {
        document.getElementById('potensialSelect').dispatchEvent(new Event('change'));
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
    flatpickr("#event_date", {
        dateFormat: "Y-m-d"
    });
  </script>
</body>
</html>
