<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda</title>
    <link rel="icon" href="<?= favicon_url() ?>" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'agenda', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h1 class="text-2xl font-bold mb-4">Edit Agenda untuk <?= $agenda->client_name ?></h1>
              <form action="<?= site_url('agenda/update/'.$agenda->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="id_session" value="<?= $agenda->id_session ?>">

                <label class="block mb-2">Brainstorming</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Tanggal</span>
                    <input type="date" id="brainstorming" name="brainstorming" value="<?= $agenda->brainstorming ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Waktu</span>
                    <input type="time" id="brainstorming_waktu" name="brainstorming_waktu" value="<?= !empty($agenda->brainstorming_waktu) ? substr($agenda->brainstorming_waktu, 0, 5) : '' ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4 -mt-2">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Nama Lokasi</span>
                    <input type="text" id="brainstorming_lokasi" name="brainstorming_lokasi" maxlength="255" placeholder="Contoh: Kafe Rasa Nusantara" value="<?= htmlspecialchars($agenda->brainstorming_lokasi ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">URL Lokasi</span>
                    <input type="text" id="brainstorming_lokasi_url" name="brainstorming_lokasi_url" maxlength="500" placeholder="Link Google Maps" value="<?= htmlspecialchars($agenda->brainstorming_lokasi_url ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <label class="block mb-2">Fiting</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Tanggal</span>
                    <input type="date" id="fiting" name="fiting" value="<?= $agenda->fiting ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Waktu</span>
                    <input type="time" id="fiting_waktu" name="fiting_waktu" value="<?= !empty($agenda->fiting_waktu) ? substr($agenda->fiting_waktu, 0, 5) : '' ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4 -mt-2">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Nama Lokasi</span>
                    <input type="text" id="fiting_lokasi" name="fiting_lokasi" maxlength="255" placeholder="Contoh: Kafe Rasa Nusantara" value="<?= htmlspecialchars($agenda->fiting_lokasi ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">URL Lokasi</span>
                    <input type="text" id="fiting_lokasi_url" name="fiting_lokasi_url" maxlength="500" placeholder="Link Google Maps" value="<?= htmlspecialchars($agenda->fiting_lokasi_url ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <label class="block mb-2">Test Food</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Tanggal</span>
                    <input type="date" id="testfood" name="testfood" value="<?= $agenda->testfood ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Waktu</span>
                    <input type="time" id="testfood_waktu" name="testfood_waktu" value="<?= !empty($agenda->testfood_waktu) ? substr($agenda->testfood_waktu, 0, 5) : '' ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4 -mt-2">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Nama Lokasi</span>
                    <input type="text" id="testfood_lokasi" name="testfood_lokasi" maxlength="255" placeholder="Contoh: Kafe Rasa Nusantara" value="<?= htmlspecialchars($agenda->testfood_lokasi ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">URL Lokasi</span>
                    <input type="text" id="testfood_lokasi_url" name="testfood_lokasi_url" maxlength="500" placeholder="Link Google Maps" value="<?= htmlspecialchars($agenda->testfood_lokasi_url ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <label class="block mb-2">Final Fiting</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Tanggal</span>
                    <input type="date" id="final_fiting" name="final_fiting" value="<?= $agenda->final_fiting ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Waktu</span>
                    <input type="time" id="final_fiting_waktu" name="final_fiting_waktu" value="<?= !empty($agenda->final_fiting_waktu) ? substr($agenda->final_fiting_waktu, 0, 5) : '' ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4 -mt-2">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Nama Lokasi</span>
                    <input type="text" id="final_fiting_lokasi" name="final_fiting_lokasi" maxlength="255" placeholder="Contoh: Kafe Rasa Nusantara" value="<?= htmlspecialchars($agenda->final_fiting_lokasi ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">URL Lokasi</span>
                    <input type="text" id="final_fiting_lokasi_url" name="final_fiting_lokasi_url" maxlength="500" placeholder="Link Google Maps" value="<?= htmlspecialchars($agenda->final_fiting_lokasi_url ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <label class="block mb-2">Technical Meeting</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Tanggal</span>
                    <input type="date" id="technical_meeting" name="technical_meeting" value="<?= $agenda->technical_meeting ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Waktu</span>
                    <input type="time" id="technical_meeting_waktu" name="technical_meeting_waktu" value="<?= !empty($agenda->technical_meeting_waktu) ? substr($agenda->technical_meeting_waktu, 0, 5) : '' ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4 -mt-2">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Nama Lokasi</span>
                    <input type="text" id="technical_meeting_lokasi" name="technical_meeting_lokasi" maxlength="255" placeholder="Contoh: Kafe Rasa Nusantara" value="<?= htmlspecialchars($agenda->technical_meeting_lokasi ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">URL Lokasi</span>
                    <input type="text" id="technical_meeting_lokasi_url" name="technical_meeting_lokasi_url" maxlength="500" placeholder="Link Google Maps" value="<?= htmlspecialchars($agenda->technical_meeting_lokasi_url ?? '') ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <label class="block mb-2">Final Revision</label>
                <input type="date" id="final_revision" name="final_revision" value="<?= $agenda->final_revision ?>" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Loading Decoration</label>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4">
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Tanggal</span>
                    <input type="date" id="loading_decoration" name="loading_decoration" value="<?= $agenda->loading_decoration ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                  <div>
                    <span class="block mb-1 text-xs text-gray-500">Waktu</span>
                    <input type="time" id="loading_decoration_waktu" name="loading_decoration_waktu" value="<?= !empty($agenda->loading_decoration_waktu) ? substr($agenda->loading_decoration_waktu, 0, 5) : '' ?>" class="w-full px-4 py-2 border rounded">
                  </div>
                </div>

                <label class="block mb-2">Wedding Day</label>
                <input type="date" id="wedding_day" name="wedding_day" value="<?= $agenda->wedding_day ?>" class="w-full px-4 py-2 border rounded mb-4">

                <label class="block mb-2">Honeymoon</label>
                <input type="date" id="honeymoon" name="honeymoon" value="<?= $agenda->honeymoon ?>" class="w-full px-4 py-2 border rounded mb-4">

                <div class="flex flex-col sm:flex-row justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
                <a href="<?= site_url('project/lihat/'.$agenda->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
</body>
</html>
