<?php
// Ambil agama dari tabel project berdasarkan id_session klien
$project = $this->db->get_where('project', ['id_session' => $clients->id_session])->row();
$religion = $project->religion ?? ''; // Pastikan tidak error jika religion kosong

$islam = strtolower($religion) === 'islam'; // Cek apakah agama Islam
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/frontend/assets/images/favicon.png" type="image/x-icon">
    <!-- animate css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/frontend/assets/css/animate.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/frontend/assets/css/bootstrap.min.css">
    <!-- plugin -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/frontend/assets/css/plugin.css">
    <!-- owl carousel -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/frontend/assets/css/owl.carousel.min.css">
    <!-- main css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/frontend/assets/css/style.css">
    <!-- responsive css -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/frontend/assets/css/responsive.css">
    <!-- Tight Theme -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/frontend/assets/css/lite.css">
    <style> .hidden { display: none; } </style>
</head>
<body>
    <!-- preloader area start -->
    <div class="preloader" id="preloader">
        <div class="loader loader-1">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
        </div>
    </div>
    <!-- preloader area end -->
    <!-- Main Website wrapper start -->
    <div id="about">
        <!-- About Area Start -->
        <section id="about" class="about-area section-padding section-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-heading">
                            <h2 class="s-h-title">
                                Edit <span>Client</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <form action="<?= site_url('clients/c_update/' . $clients->id_session) ?>" method="post" class="bg-white p-6 shadow-md rounded">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h3 class="mb-0">Data Pengantin Wanita</h3>
                            </div>
                            <div class="about-box">
                                <div class="row">
                                    <div class="col-lg-12 d-flex align-self-center">
                                        <div class="about-content">
                                            <ul class="info-list">
                                                <li>
                                                    <span class="title">Nama Lengkap : </span>
                                                    <span class="value"><input type="text" name="f_bride_fname" value="<?= $clients->f_bride_fname ?>" class="form-control" required></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Panggilan : </span>
                                                    <span class="value"><input type="text" name="f_bride_cname" value="<?= $clients->f_bride_cname ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Ayah : </span>
                                                    <span class="value"><input type="text" name="f_bride_fathername" value="<?= $clients->f_bride_fathername ?>" class="form-control" required></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Panggilan Ayah : </span>
                                                    <span class="value"><input type="text" name="f_bride_fathercname" value="<?= $clients->f_bride_fathercname ?>" class="form-control"></span>
                                                </li>
                                                <li>
        <span class="title">Ayah Mempelai Wanita :</span>
        <li>
        <li class="value">
            <label><input type="radio" name="fayah_status" onclick="toggleReplacementFields('fayah', false)" <?= empty($clients->f_bride_freplacementname) ? 'checked' : '' ?>> Masih Ada</label>
            <label><input type="radio" name="fayah_status" onclick="toggleReplacementFields('fayah', true)" <?= !empty($clients->f_bride_freplacementname) ? 'checked' : '' ?>> Tidak Ada</label>
        </li>
        </li>
    </li>
    <li>
    <li id="fayah" class="<?= !empty($clients->f_bride_freplacementname) ? '' : 'hidden' ?>">
        <span class="title">Nama Lengkap Pengganti Ayah :</span>
        <span class="value"><input type="text" name="f_bride_freplacementname" value="<?= $clients->f_bride_freplacementname ?>" class="form-control"></span>
    </li>
    <li id="fayah_cname" class="<?= !empty($clients->f_bride_freplacementcname) ? '' : 'hidden' ?>">
        <span class="title">Nama Panggilan Pengganti Ayah :</span>
        <span class="value"><input type="text" name="f_bride_freplacementcname" value="<?= $clients->f_bride_freplacementcname ?>" class="form-control"></span>
    </li>
    </li>

                                                <li>
                                                    <span class="title">Nama Ibu : </span>
                                                    <span class="value"><input type="text" name="f_bride_mothername" value="<?= $clients->f_bride_mothername ?>" class="form-control" required></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Panggilan Ibu : </span>
                                                    <span class="value"><input type="text" name="f_bride_mothercname" value="<?= $clients->f_bride_mothercname ?>" class="form-control"></span>
                                                </li>
                                                <li>
        <span class="title">Ibu Mempelai Wanita :</span>
        <li>
        <li class="value">
            <label><input type="radio" name="fibu_status" onclick="toggleReplacementFields('fibu', false)" <?= empty($clients->f_bride_mreplacementname) ? 'checked' : '' ?>> Masih Ada</label>
            <label><input type="radio" name="fibu_status" onclick="toggleReplacementFields('fibu', true)" <?= !empty($clients->f_bride_mreplacementname) ? 'checked' : '' ?>> Tidak Ada</label>
        </li>
        </li>
    </li>
    <li>
    <li id="fibu" class="<?= !empty($clients->f_bride_mreplacementname) ? '' : 'hidden' ?>">
        <span class="title">Nama Lengkap Pengganti Ibu :</span>
        <span class="value"><input type="text" name="f_bride_mreplacementname" value="<?= $clients->f_bride_mreplacementname ?>" class="form-control"></span>
    </li>
    <li id="fibu_cname" class="<?= !empty($clients->f_bride_mreplacementcname) ? '' : 'hidden' ?>">
        <span class="title">Nama Panggilan Pengganti Ibu :</span>
        <span class="value"><input type="text" name="f_bride_mreplacementcname" value="<?= $clients->f_bride_mreplacementcname ?>" class="form-control"></span>
    </li>
    </li>

                                                <li>
                                                    <span class="title">Anak Ke : </span>
                                                    <span class="value"><input type="number" name="f_bride_nchild" value="<?= $clients->f_bride_nchild ?>" class="form-control">
                                                  dari <input type="number" name="f_bride_hsibling" value="<?= $clients->f_bride_hsibling ?>" class="form-control"> Bersaudara</span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Saudara Kandung : </span>
                                                    <span class="value"><textarea name="f_bride_sibling" class="form-control"><?= $clients->f_bride_sibling ?></textarea></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h3>Data Pengantin Pria</h3>
                            <div class="about-box">
                                <div class="row">
                                    <div class="col-lg-12 d-flex align-self-center">
                                        <div class="about-content">
                                            <ul class="info-list">
                                                <li>
                                                    <span class="title">Nama Lengkap : </span>
                                                    <span class="value"><input type="text" name="m_bride_fname" value="<?= $clients->m_bride_fname ?>" class="form-control" required></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Panggilan : </span>
                                                    <span class="value"><input type="text" name="m_bride_cname" value="<?= $clients->m_bride_cname ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Ayah : </span>
                                                    <span class="value"><input type="text" name="m_bride_fathername" value="<?= $clients->m_bride_fathername ?>" class="form-control" required></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Panggilan Ayah : </span>
                                                    <span class="value"><input type="text" name="m_bride_fathercname" value="<?= $clients->m_bride_fathercname ?>" class="form-control"></span>
                                                </li>
                                                <li>
        <span class="title">Ayah Mempelai Pria :</span>
        <li>
        <li class="value">
            <label><input type="radio" name="mayah_status" onclick="toggleReplacementFields('mayah', false)" <?= empty($clients->m_bride_freplacementname) ? 'checked' : '' ?>> Masih Ada</label>
            <label><input type="radio" name="mayah_status" onclick="toggleReplacementFields('mayah', true)" <?= !empty($clients->m_bride_freplacementname) ? 'checked' : '' ?>> Tidak Ada</label>
        </li>
    </li>
    </li>
    <li>
    <li id="mayah" class="<?= !empty($clients->m_bride_freplacementname) ? '' : 'hidden' ?>">
        <span class="title">Nama Lengkap Pengganti Ayah :</span>
        <span class="value"><input type="text" name="m_bride_freplacementname" value="<?= $clients->m_bride_freplacementname ?>" class="form-control"></span>
    </li>
    <li id="mayah_cname" class="<?= !empty($clients->m_bride_freplacementcname) ? '' : 'hidden' ?>">
        <span class="title">Nama Panggilan Pengganti Ayah :</span>
        <span class="value"><input type="text" name="m_bride_freplacementcname" value="<?= $clients->m_bride_freplacementcname ?>" class="form-control"></span>
    </li>
    </li>

                                                <li>
                                                    <span class="title">Nama Ibu : </span>
                                                    <span class="value"><input type="text" name="m_bride_mothername" value="<?= $clients->m_bride_mothername ?>" class="form-control" required></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Panggilan Ibu : </span>
                                                    <span class="value"><input type="text" name="m_bride_mothercname" value="<?= $clients->m_bride_mothercname ?>" class="form-control"></span>
                                                </li>
                                                <li>
        <span class="title">Ibu Mempelai Pria :</span>
        <li>
        <li class="value">
            <label><input type="radio" name="mibu_status" onclick="toggleReplacementFields('mibu', false)" <?= empty($clients->m_bride_mreplacementname) ? 'checked' : '' ?>> Masih Ada</label>
            <label><input type="radio" name="mibu_status" onclick="toggleReplacementFields('mibu', true)" <?= !empty($clients->m_bride_mreplacementname) ? 'checked' : '' ?>> Tidak Ada</label>
        </li>
    </li>
    </li>
    <li>
    <li id="mibu" class="<?= !empty($clients->m_bride_mreplacementname) ? '' : 'hidden' ?>">
        <span class="title">Nama Lengkap Pengganti Ibu :</span>
        <span class="value"><input type="text" name="m_bride_mreplacementname" value="<?= $clients->m_bride_mreplacementname ?>" class="form-control"></span>
    </li>
    <li id="mibu_cname" class="<?= !empty($clients->m_bride_mreplacementcname) ? '' : 'hidden' ?>">
        <span class="title">Nama Panggilan Pengganti Ibu :</span>
        <span class="value"><input type="text" name="m_bride_mreplacementcname" value="<?= $clients->m_bride_mreplacementcname ?>" class="form-control"></span>
    </li>
    </li>

                                                <li>
                                                    <span class="title">Anak Ke : </span>
                                                    <span class="value"><input type="number" name="m_bride_nchild" value="<?= $clients->m_bride_nchild ?>" class="form-control">
                                                  dari <input type="number" name="m_bride_hsibling" value="<?= $clients->m_bride_hsibling ?>" class="form-control"> Bersaudara</span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Saudara Kandung : </span>
                                                    <span class="value"><textarea name="m_bride_sibling" class="form-control"><?= $clients->m_bride_sibling ?></textarea></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <h3>Detail Pernikahan</h3>
                            <div class="about-box">
                                <div class="row">
                                    <div class="col-lg-12 d-flex align-self-center">
                                        <div class="about-content">
                                            <ul class="info-list">
                                                <li>
                                                    <span class="title">Tanggal Pernikahan : </span>
                                                    <span class="value"><input type="date" name="wedding_date" value="<?= $clients->wedding_date ?>" class="form-control" readonly></span>
                                                </li>
                                                <li>
                                                    <span class="title">Lokasi Acara : </span>
                                                    <span class="value"><input type="text" name="location" value="<?= $clients->location ?>" class="form-control" readonly></span>
                                                </li>
                                                <?php if ($islam) : ?>
                                                <li>
                                                    <span class="title">Mahar : </span>
                                                    <span class="value"><input type="text" name="mahr" value="<?= $clients->mahr ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Simbolis Seserahan : </span>
                                                    <span class="value">
                                                        <select name="handover" class="form-control">
                                                            <option value="">Pilih Simbolis Seserahan</option>
                                                            <option value="Seperangkat Alat Solat" <?= $clients->handover == 'Seperangkat Alat Solat' ? 'selected' : '' ?>>Seperangkat Alat Solat</option>
                                                            <option value="Make Up" <?= $clients->handover == 'Make Up' ? 'selected' : '' ?>>Make Up</option>
                                                            <option value="Lainnya" <?= $clients->handover == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                                        </select>
                                                    </span>
                                                </li>
                                                <li>
                                                    <span class="title">Jubir Kel. Pria : </span>
                                                    <span class="value"><input type="text" name="m_spokesman" value="<?= $clients->m_spokesman ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Jubir Kel. Wanita : </span>
                                                    <span class="value"><input type="text" name="f_spokesman" value="<?= $clients->f_spokesman ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nama Penghulu : </span>
                                                    <span class="value"><input type="text" name="wedding_officiant" value="<?= $clients->wedding_officiant ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Wali Nikah : </span>
                                                    <span class="value"><input type="text" name="guardian" value="<?= $clients->guardian ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Saksi Dari Pria : </span>
                                                    <span class="value"><input type="text" name="m_witness" value="<?= $clients->m_witness ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Saksi Dari Wanita : </span>
                                                    <span class="value"><input type="text" name="f_witness" value="<?= $clients->f_witness ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Qori/Saritilawah : </span>
                                                    <span class="value"><input type="text" name="qori" value="<?= $clients->qori ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Nasihat Pernikahan : </span>
                                                    <span class="value"><input type="text" name="advice_doa" value="<?= $clients->advice_doa ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Pengapit Pengantin Wanita : </span>
                                                    <span class="value"><input type="text" name="clamp" value="<?= $clients->clamp ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Pembawa Kalung Melati dari Kel. Wanita : </span>
                                                    <span class="value"><input type="text" name="jasmine_carrier" value="<?= $clients->jasmine_carrier ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Pembawa Mahar/Mas Kawin dari Kel. Pria : </span>
                                                    <span class="value"><input type="text" name="mahr_carrier" value="<?= $clients->mahr_carrier ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Pembawa Cincin dari Kel. Pria : </span>
                                                    <span class="value"><input type="text" name="ring_carrier" value="<?= $clients->ring_carrier ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Koor. Kel. Pria : </span>
                                                    <span class="value"><input type="text" name="male_coor" value="<?= $clients->male_coor ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Koor. Kel. Wanita : </span>
                                                    <span class="value"><input type="text" name="female_coor" value="<?= $clients->female_coor ?>" class="form-control"></span>
                                                </li>
                                                <?php else : ?>
                                                <li>
                                                    <span class="title">Koor. Kel. Pria : </span>
                                                    <span class="value"><input type="text" name="male_coor" value="<?= $clients->male_coor ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Koor. Kel. Wanita : </span>
                                                    <span class="value"><input type="text" name="female_coor" value="<?= $clients->female_coor ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Pendeta : </span>
                                                    <span class="value"><input type="text" name="pastor" value="<?= $clients->pastor ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Gereja : </span>
                                                    <span class="value"><input type="text" name="church" value="<?= $clients->church ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Pemimpin Doa : </span>
                                                    <span class="value"><input type="text" name="prayer" value="<?= $clients->prayer ?>" class="form-control"></span>
                                                </li>
                                                <li>
                                                    <span class="title">Sambutan Pernikahan : </span>
                                                    <span class="value"><input type="text" name="wedding_speech" value="<?= $clients->wedding_speech ?>" class="form-control"></span>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="mybtn mybtn-bg mt-2 mt-md-0"> <span><i class="fas fa-user"></i></span>Update</button>
                    </div>
                </form>
            </div>
        </section>
        <!-- About Area End -->
    </div>
    <!-- Main Website wrapper End -->

    <!-- Main jquery and all jquery plugin hear -->
    <script src="<?php echo base_url()?>assets/frontend/assets/js/jquery.js"></script>
    <script src="<?php echo base_url()?>assets/frontend/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url()?>assets/frontend/assets/js/magnific-popup.js"></script>
    <script src="<?php echo base_url()?>assets/frontend/assets/js/circel.js"></script>
    <script src="<?php echo base_url()?>assets/frontend/assets/js/typed.min.js"></script>
    <script src="<?php echo base_url()?>assets/frontend/assets/js/mixitup.min.js"></script>
    <script src="<?php echo base_url()?>assets/frontend/assets/js/owl.carousel.min.js"></script>
    <script src="<?php echo base_url()?>assets/frontend/assets/js/main.js"></script>
    <script>
function toggleReplacementFields(id, show) {
    document.getElementById(id).classList.toggle('hidden', !show);
    document.getElementById(id + '_cname').classList.toggle('hidden', !show);
}

window.onload = function() {
    const fields = [
        { status: 'fayah_status', replacement: "<?= $clients->f_bride_freplacementname ?? '' ?>", id: 'fayah' },
        { status: 'fibu_status', replacement: "<?= $clients->f_bride_mreplacementname ?? '' ?>", id: 'fibu' },
        { status: 'mayah_status', replacement: "<?= $clients->m_bride_freplacementname ?? '' ?>", id: 'mayah' },
        { status: 'mibu_status', replacement: "<?= $clients->m_bride_mreplacementname ?? '' ?>", id: 'mibu' }
    ];
    
    fields.forEach(field => {
        const hasReplacement = field.replacement.trim() !== '';
        document.querySelector(`input[name="${field.status}"][onclick*='${hasReplacement}']`).checked = true;
        toggleReplacementFields(field.id, hasReplacement);
    });
};
</script>
</body>
</html>
