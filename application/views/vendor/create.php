<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Vendor</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ page: 'vendor', 'loaded': true, 'darkMode': true, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
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
              <h2 class="text-2xl font-bold mb-4">Tambah Vendor <?= $project->client_name ?></h2>
              <form action="<?= base_url('vendor/store') ?>" method="POST" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded">
                <input type="hidden" name="id_session" value="<?= $project->id_session ?>">

                <label class="block mb-2"><strong>Status Vendor</strong></label>
                <div class="mb-4">
                  <label class="inline-flex items-center">
                    <input type="radio" name="vendor_status" value="non_partner" class="form-radio" checked onclick="togglePartner(false)">
                    <span class="ml-2">Non-Partner</span>
                  </label>
                  <label class="inline-flex items-center ml-4">
                    <input type="radio" name="vendor_status" value="partner" class="form-radio" onclick="togglePartner(true)">
                    <span class="ml-2">Partner</span>
                  </label>
                </div>

                <!-- Partner Section -->
                <div id="partner-section" class="hidden">
                  <label class="block mb-2"><strong>Nama Partner</strong></label>
                  <select name="partner_id" id="partner_id" class="w-full px-4 py-2 border rounded mb-4" onchange="togglePartnerDetails()">
                    <option value="">Pilih Partner</option>
                    <?php foreach ($partners as $partner): ?>
                      <option value="<?= $partner->id ?>"><?= $partner->partner_name ?> - <?= $partner->type ?></option>
                    <?php endforeach; ?>
                  </select>

                  <div id="partner-details" class="hidden">
                    <label class="block mb-2"><strong>Detail</strong></label>
                    <textarea name="partner_detail" rows="20" cols="100%" id="partner_detail" class="w-full px-4 py-2 border rounded mb-4"></textarea>
                    <label class="block mb-2"><strong>Photo/Concept 1</strong></label>
                    <input type="file" name="partner_photo2" id="partner_photo1" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <label class="block mb-2"><strong>Photo/Concept 2</strong></label>
                    <input type="file" name="partner_photo3" id="partner_photo2" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <label class="block mb-2"><strong>Photo/Concept 3</strong></label>
                    <input type="file" name="partner_photo4" id="partner_photo3" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <label class="block mb-2"><strong>Photo/Concept 4</strong></label>
                    <input type="file" name="partner_photo5" id="partner_photo4" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                  </div>
                </div>

                <!-- Non-Partner Section -->
                <div id="non-partner-section">
                  <label class="block mb-2"><strong>Nama Vendor</strong></label>
                  <input type="text" name="vendor" id="vendor" class="w-full px-4 py-2 border rounded mb-4">

                  <label class="block mb-2"><strong>Jenis</strong></label>
                  <select name="type" id="type" class="w-full px-4 py-2 border rounded mb-4">
                      <option value="">-</option>
                      <option value="Venue">Venue</option>
                      <option value="MC Akad">MC Akad</option>
                      <option value="MC Pemberkatan">MC Pemberkatan</option>
                      <option value="MC Resepsi">MC Resepsi</option>
                      <option value="Wedding Organizer">Wedding Organizer</option>
                      <option value="MUA">MUA</option>
                      <option value="Perlengkapan Catering">Perlengkapan Catering</option>
                      <option value="Catering">Catering</option>
                      <option value="Dokumentasi">Dokumentasi</option>
                      <option value="Dekorasi">Dekorasi</option>
                      <option value="Entertainment">Entertainment</option>
                  </select>

                  <label class="block mb-2"><strong>Sosial Media</strong></label>
                  <input type="text" name="social_media" id="social_media" class="w-full px-4 py-2 border rounded mb-4">

                  <label class="block mb-2"><strong>Nama Kontak</strong></label>
                  <input type="text" name="contact_name" id="contact_name" class="w-full px-4 py-2 border rounded mb-4">

                  <label class="block mb-2"><strong>No HP</strong></label>
                  <input type="text" name="phone" id="phone" class="w-full px-4 py-2 border rounded mb-4">

                  <label class="block mb-2"><strong>Detail</strong></label>
                  <textarea name="detail" id="detail" class="w-full px-4 py-2 border rounded mb-4"></textarea>

                  <label class="block mb-2"><strong>Logo/Photo Close up</strong></label>
                  <input type="file" name="photo1" id="photo1" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                  <label class="block mb-2"><strong>Photo/Concept 1</strong></label>
                  <input type="file" name="photo2" id="photo2" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                  <label class="block mb-2"><strong>Photo/Concept 2</strong></label>
                  <input type="file" name="photo3" id="photo3" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                  <label class="block mb-2"><strong>Photo/Concept 3</strong></label>
                  <input type="file" name="photo4" id="photo4" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                  <label class="block mb-2"><strong>Photo/Concept 4</strong></label>
                  <input type="file" name="photo5" id="photo5" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex flex-col sm:flex-row justify-end">
                  <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full hover:bg-green-600 sm:w-24 mb-2 sm:mb-0 text-center">Simpan</button>
                  <a href="<?= base_url('project/lihat/' . $project->id_session) ?>" class="sm:ml-2 bg-gray-500 text-white px-4 py-2 rounded w-full hover:bg-gray-600 sm:w-24 text-center">Batal</a>
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
    function togglePartner(isPartner) {
      const partnerSection = document.getElementById('partner-section');
      const nonPartnerSection = document.getElementById('non-partner-section');
      const partnerDetails = document.getElementById('partner-details');

      partnerSection.classList.toggle('hidden', !isPartner);
      nonPartnerSection.classList.toggle('hidden', isPartner);

      // Ensure all fields are enabled before submission
      const allFields = document.querySelectorAll('#partner-section input, #partner-section textarea, #non-partner-section input, #non-partner-section textarea');
      allFields.forEach(field => {
        field.disabled = false; // Enable all fields
      });

      // Hide partner details if no partner is selected
      if (!isPartner) {
        partnerDetails.classList.add('hidden');
      }
    }

    function togglePartnerDetails() {
      const partnerId = document.getElementById('partner_id').value;
      const detailsSection = document.getElementById('partner-details');
      if (partnerId) {
        detailsSection.classList.remove('hidden');
        fetchPartnerData();
      } else {
        detailsSection.classList.add('hidden');
      }
    }

    function fetchPartnerData() {
      const partnerId = document.getElementById('partner_id').value;
      if (partnerId) {
        fetch(`<?= base_url('vendor/get_partner_data/') ?>${partnerId}`)
          .then(response => response.json())
          .then(data => {
            document.getElementById('vendor').value = data.partner_name;
            document.getElementById('type').value = data.type;
            document.getElementById('social_media').value = data.social_media;
            document.getElementById('contact_name').value = data.contact_name;
            document.getElementById('phone').value = data.phone;
          });
      }
    }
  </script>
</body>
</html>
