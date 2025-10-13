<?php
$id_session = $this->input->get('id_session', true);
$vendor_id = $this->input->get('vendor_id', true);
$vendor = $this->db->get_where('vendor', ['id_session' => $id_session, 'vendor_id' => $vendor_id])->row();

if (!$vendor) {
    echo "<p class='text-center text-danger'>Vendor tidak ditemukan.</p>";
    return;
}

// Pilihan info berdasarkan type vendor
$info = '';
switch ($vendor->type) {
    case 'Venue': $info = $vendor->detail; break;
    case 'MC Akad': $info = $vendor->detail; break;
    case 'MC Pemberkatan': $info = $vendor->detail; break;
    case 'MC Resepsi': $info = $vendor->detail; break;
    case 'MC Adat Sunda': $info = $vendor->detail; break;
    case 'MC Adat Jawa': $info = $vendor->detail; break;
    case 'Wedding Organizer': $info = $vendor->detail; break;
    case 'MUA': $info = $vendor->detail; break;
    case 'Attire': $info = $vendor->detail; break;
    case 'Perlengkapan Catering': $info = $vendor->detail; break;
    case 'Catering': $info = $vendor->detail; break;
    case 'Dokumentasi': $info = $vendor->detail; break;
    case 'Dekorasi': $info = $vendor->detail; break;
    case 'Entertainment': $info = $vendor->detail; break;
    case 'Pendukung Pernikahan Lainnya': $info = $vendor->detail; break;
    case 'Penyewaan Peralatan': $info = $vendor->detail; break;
    
}

// Periksa apakah foto tersedia
$photo2 = !empty($vendor->photo2) ? base_url("uploads/{$vendor->photo2}") : base_url("uploads/default.jpg");
$photo3 = !empty($vendor->photo3) ? base_url("uploads/{$vendor->photo3}") : base_url("uploads/default.jpg");
$photo4 = !empty($vendor->photo4) ? base_url("uploads/{$vendor->photo4}") : base_url("uploads/default.jpg");
$photo5 = !empty($vendor->photo5) ? base_url("uploads/{$vendor->photo5}") : base_url("uploads/default.jpg");
?>

<div class="container ajax-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="header-area">
                <h3 class="project-title"><?= htmlspecialchars($vendor->type) ?></h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="owl-carousel owl-theme single-slideshow" data-autoplay="true" data-loop="true" data-nav="true" data-items="1">
                <?php if (!empty($vendor->photo2)): ?>
                    <div class="item"> <img class="img-fluid" alt="" src="<?= base_url("uploads/{$vendor->photo2}") ?>"> </div>
                <?php endif; ?>
                <?php if (!empty($vendor->photo3)): ?>
                    <div class="item"> <img class="img-fluid" alt="" src="<?= base_url("uploads/{$vendor->photo3}") ?>"> </div>
                <?php endif; ?>
                <?php if (!empty($vendor->photo4)): ?>
                    <div class="item"> <img class="img-fluid" alt="" src="<?= base_url("uploads/{$vendor->photo4}") ?>"> </div>
                <?php endif; ?>
                <?php if (!empty($vendor->photo5)): ?>
                    <div class="item"> <img class="img-fluid" alt="" src="<?= base_url("uploads/{$vendor->photo5}") ?>"> </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="project-details-info">
                <h5 class=""><?= htmlspecialchars($vendor->vendor) ?></h5>

                <h5 class="mt-5">Detail</h5>
                <?php if (strpos($info, "\n") !== false): ?>
                    <ul>
                        <?php foreach (explode("\n", $info) as $line): ?>
                            <li><?= htmlspecialchars($line) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><?= htmlspecialchars($info) ?></p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
