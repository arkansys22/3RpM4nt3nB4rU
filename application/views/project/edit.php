<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
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
              <h1 class="text-2xl font-bold mb-4">Edit Project <?= $project->project_name ?></h1>
              <form action="<?= site_url('project/update/'.$project->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                <label class="block mb-2">Nama Project</label>
                <input type="text" name="project_name" value="<?= $project->project_name ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Closingan</label>
                <select name="closing_user_idsession" class="w-full px-4 py-2 border rounded mb-4" required> 
                      <?php foreach ($user as $p) {
                            if ($project->closing_user_idsession == $p['id_session']){
                              echo"<option selected='selected' value='$p[id_session]'>$p[nama]</option> ";
                            }else{
                              echo"<option value='$p[id_session]'>$p[nama]</option>";
                        }
                      } ?>                    
                </select>

                <label class="block mb-2">Nama Client</label>
                <input type="text" name="client_name" value="<?= $project->client_name ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Tanggal Pernikahan</label>
                <input type="date" name="event_date" value="<?= $project->event_date ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <label class="block mb-2">Value</label>
                <input type="text" id="value" value="<?= number_format($project->value ?? 0, 0, ',', '.') ?>" class="w-full px-4 py-2 border rounded mb-4" oninput="formatNumber(this)" name="value" required>

                <label class="block mb-2">Detail</label>
                <textarea name="detail" rows="20" cols="100%" class="w-full px-4 py-2 border rounded mb-4" required><?= $project->detail ?></textarea>


                <?php  if($this->session->level=='1' OR $this->session->level=='2'){ ?>
                <label class="block mb-2">Detail Biaya Vendor</label>
                <textarea name="detail_biaya" rows="20" cols="100%" class="w-full px-4 py-2 border rounded mb-4" required><?= $project->detail_biaya ?></textarea>
                <?php } else{ ?>
                <input type="hidden" name="detail_biaya" value="<?= $project->detail_biaya ?>" class="w-full px-4 py-2 border rounded mb-4" required>
                <?php } ?>
                
                <label class="block mb-2">Agama</label>
                <select name="religion" class="w-full px-4 py-2 border rounded mb-4" required>
                    <option value="Islam" <?= $project->religion == 'Islam' ? 'selected' : '' ?>>Islam</option>
                    <option value="Kristen" <?= $project->religion == 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                    <option value="Katolik" <?= $project->religion == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                    <option value="Hindu" <?= $project->religion == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                    <option value="Buddha" <?= $project->religion == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                    <option value="Lainnya" <?= $project->religion == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>

                <label class="block mb-2">Lokasi</label>
                <input type="text" name="location" value="<?= $project->location ?>" class="w-full px-4 py-2 border rounded mb-4" required>

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('project/lihat/'.$project->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
  <script defer src="<?php echo base_url()?>assets/backend/bundle.js">
  </script>
  <script>
    function formatNumber(input) {
        let value = input.value.replace(/\D/g, ''); // Hanya angka
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambah titik setiap 3 digit
        input.value = value;
    }
  </script>

</body>
</html>
