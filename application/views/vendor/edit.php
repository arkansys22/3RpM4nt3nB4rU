<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
    <link href="<?php echo base_url()?>assets/backend/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
    x-data="{ 
        page: 'vendor', 
        loaded: true, 
        darkMode: true, 
        stickyMenu: false, 
        sidebarToggle: false, 
        scrollTop: false, 
        isPartner: <?= $is_partner ? 'true' : 'false' ?> 
    }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode'));
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
    "
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
              <h1 class="text-2xl font-bold mb-4">Edit Vendor <?= $project->client_name ?></h1>
              <form action="<?= base_url('vendor/update/' . $vendors->id_session . '/' . $vendors->vendor_id) ?>" method="post" enctype="multipart/form-data" class="bg-white p-6 shadow-md rounded">
                <input type="hidden" name="id_session" value="<?= $vendors->id_session ?>">

                <!-- Partner Section -->
                <div x-show="isPartner" id="partner-section">
                    <label class="block mb-2"><strong>Nama Partner</strong></label>
                    <select name="partner_id" id="partner_id" class="w-full px-4 py-2 border rounded mb-4" onchange="fetchPartnerData()">
                        <option value="">Pilih Partner</option>
                        <?php foreach ($partners as $partner): ?>
                            <option value="<?= $partner->id ?>" <?= $vendors->vendor_id == $partner->id_session ? 'selected' : '' ?>><?= $partner->partner_name ?> - <?= $partner->type ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div id="partner-details">
                        <label class="block mb-2"><strong>Detail</strong></label>
                        <textarea name="partner_detail" id="partner_detail" rows="20" cols="100%" class="w-full px-4 py-2 border rounded mb-4"><?= $vendors->detail ?></textarea>
                        <label class="block mb-2"><strong>Photo/Concept 1</strong></label>
                        <?php if ($vendors->photo2): ?>
                          <img src="<?= base_url('uploads/' . $vendors->photo2) ?>" alt="Photo 2" class="mb-2 rounded border block w-full">
                        <?php endif; ?>
                        <input type="file" name="partner_photo2" id="partner_photo1" class="w-full px-4 py-2 border rounded mb-4">
                        <label class="block mb-2"><strong>Photo/Concept 2</strong></label>
                        <?php if ($vendors->photo3): ?>
                          <img src="<?= base_url('uploads/' . $vendors->photo3) ?>" alt="Photo 3" class="mb-2 rounded border block w-full">
                        <?php endif; ?>
                        <input type="file" name="partner_photo3" id="partner_photo2" class="w-full px-4 py-2 border rounded mb-4">
                        <label class="block mb-2"><strong>Photo/Concept 3</strong></label>
                        <?php if ($vendors->photo4): ?>
                          <img src="<?= base_url('uploads/' . $vendors->photo4) ?>" alt="Photo 4" class="mb-2 rounded border block w-full">
                        <?php endif; ?>
                        <input type="file" name="partner_photo4" id="partner_photo3" class="w-full px-4 py-2 border rounded mb-4">
                        <label class="block mb-2"><strong>Photo/Concept 4</strong></label>
                        <?php if ($vendors->photo5): ?>
                          <img src="<?= base_url('uploads/' . $vendors->photo5) ?>" alt="Photo 5" class="mb-2 rounded border block w-full">
                        <?php endif; ?>
                        <input type="file" name="partner_photo5" id="partner_photo4" class="w-full px-4 py-2 border rounded mb-4">
                    </div>
                </div>

                <!-- Non-Partner Section -->
                <div x-show="!isPartner" id="non-partner-section">
                    <label class="block mb-2"><strong>Nama Vendor</strong></label>
                    <input type="text" name="vendor" id="vendor" value="<?= $vendors->vendor ?>" class="w-full px-4 py-2 border rounded mb-4">
                    <label class="block mb-2"><strong>Type:</strong></label>
                    <select name="type" id="type" class="w-full px-4 py-2 border rounded mb-4">
                        <option value="Venue" <?= $vendors->type == 'Venue' ? 'selected' : '' ?>>Venue</option>
                        <option value="MC Akad" <?= $vendors->type == 'MC Akad' ? 'selected' : '' ?>>MC Akad</option>
                        <option value="MC Pemberkatan" <?= $vendors->type == 'MC Pemberkatan' ? 'selected' : '' ?>>MC Pemberkatan</option>
                        <option value="MC Resepsi" <?= $vendors->type == 'MC Resepsi' ? 'selected' : '' ?>>MC Resepsi</option>
                        <option value="MC Adat Sunda" <?= $vendors->type == 'MC Adat Sunda' ? 'selected' : '' ?>>MC Adat Sunda</option>
                        <option value="MC Adat Jawa" <?= $vendors->type == 'MC Adat Jawa' ? 'selected' : '' ?>>MC Adat Jawa</option>
                        <option value="Wedding Organizer" <?= $vendors->type == 'Wedding Organizer' ? 'selected' : '' ?>>WO</option>
                        <option value="MUA" <?= $vendors->type == 'MUA' ? 'selected' : '' ?>>MUA</option>
                        <option value="Perlengkapan Catering" <?= $vendors->type == 'Perlengkapan Catering' ? 'selected' : '' ?>>Perlengkapan Catering</option>
                        <option value="Penyewaan Peralatan" <?= $vendors->type == 'Penyewaan Peralatan' ? 'selected' : '' ?>>Penyewaan Peralatan</option>                        
                        <option value="Catering" <?= $vendors->type == 'Catering' ? 'selected' : '' ?>>Catering</option>
                        <option value="Dokumentasi" <?= $vendors->type == 'Dokumentasi' ? 'selected' : '' ?>>Dokumentasi</option>
                        <option value="Dekorasi" <?= $vendors->type == 'Dekorasi' ? 'selected' : '' ?>>Dekorasi</option>
                        <option value="Entertainment" <?= $vendors->type == 'Entertainment' ? 'selected' : '' ?>>Entertainment</option>
                        <option value="Pendukung Pernikahan Lainnya" <?= $vendors->type == 'Pendukung Pernikahan Lainnya' ? 'selected' : '' ?>>Pendukung Pernikahan Lainnya</option>
                    </select>
                    <label class="block mb-2"><strong>Social Media:</strong></label>
                    <input type="text" name="social_media" id="social_media" value="<?= $vendors->social_media ?>" class="w-full px-4 py-2 border rounded mb-4">
                    <label class="block mb-2"><strong>Contact Name:</strong></label>
                    <input type="text" name="contact_name" id="contact_name" value="<?= $vendors->contact_name ?>" class="w-full px-4 py-2 border rounded mb-4">
                    <label class="block mb-2"><strong>Phone:</strong></label>
                    <input type="text" name="phone" id="phone" value="<?= $vendors->phone ?>" class="w-full px-4 py-2 border rounded mb-4">
                    <label class="block mb-2"><strong>Detail:</strong></label>
                    <textarea name="detail" rows="20" cols="100%" class="w-full px-4 py-2 border rounded mb-4"><?= $vendors->detail ?></textarea>
                    <label class="block mb-2"><strong>Logo/Photo Close up (Wedding Concept)</strong></label>
                    <?php if ($vendors->photo1): ?>
                      <img src="<?= base_url('uploads/' . $vendors->photo1) ?>" alt="Photo 1" class="mb-2 rounded border block w-full">
                    <?php endif; ?>
                    <input type="file" name="photo1" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <label class="block mb-2"><strong>Photo/Concept 1</strong></label>
                    <?php if ($vendors->photo2): ?>
                      <img src="<?= base_url('uploads/' . $vendors->photo2) ?>" alt="Photo 2" class="mb-2 rounded border block w-full">
                    <?php endif; ?>
                    <input type="file" name="photo2" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <label class="block mb-2"><strong>Photo/Concept 2</strong></label>
                    <?php if ($vendors->photo3): ?>
                      <img src="<?= base_url('uploads/' . $vendors->photo3) ?>" alt="Photo 3" class="mb-2 rounded border block w-full">
                    <?php endif; ?>
                    <input type="file" name="photo3" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <label class="block mb-2"><strong>Photo/Concept 3</strong></label>
                    <?php if ($vendors->photo4): ?>
                      <img src="<?= base_url('uploads/' . $vendors->photo4) ?>" alt="Photo 4" class="mb-2 rounded border block w-full">
                    <?php endif; ?>
                    <input type="file" name="photo4" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <label class="block mb-2"><strong>Photo/Concept 4</strong></label>
                    <?php if ($vendors->photo5): ?>
                      <img src="<?= base_url('uploads/' . $vendors->photo5) ?>" alt="Photo 5" class="mb-2 rounded border block w-full">
                    <?php endif; ?>
                    <input type="file" name="photo5" class="w-full px-4 py-2 border rounded mb-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex flex-col sm:flex-row justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 sm:w-24 mb-2 sm:mb-0 text-center">Update</button>
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
    function fetchPartnerData() {
        const partnerId = document.getElementById('partner_id').value;
        if (partnerId) {
            fetch(`<?= base_url('vendor/get_partner_data/') ?>${partnerId}`) // Use partner ID
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
