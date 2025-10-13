<?php
// Ambil agama dari tabel project berdasarkan id_session klien
$project = $this->db->get_where('project', ['id_session' => $clients->id_session])->row();
$religion = $project->religion ?? ''; // Pastikan tidak error jika religion kosong

$islam = strtolower($religion) === 'islam'; // Cek apakah agama Islam

// Ambil data vendor dari database berdasarkan id_session klien
$vendors = $this->db->get_where('vendor', ['id_session' => $clients->id_session])->result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if($project->religion === 'Islam') { ?>
	<title>Mantenbaru <?= $clients->f_bride_cname ?> dan <?= $clients->m_bride_cname ?></title>
	<?php } elseif ($project->religion === 'Kristen') { ?>
	<title>Mantenbaru <?= $clients->m_bride_cname ?> dan <?= $clients->f_bride_cname ?></title>
	<?php } ?>
	<!-- favicon -->
	<link rel="shortcut icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
	<style>.box {
            width: 40%; /* Setengah lebar halaman */
            margin: 5px auto; /* Tengah secara horizontal */
            border: 2px solid black; /* Border hitam */
            padding: 15px; /* Jarak dalam */
            text-align: justify; /* Teks rata kanan kiri */
            font-weight: bold; /* Teks tebal */
            font-size: 20px; /* Ukuran teks lebih besar */
            line-height: 1.8; /* Jarak antar baris */
        }</style>
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
	<!-- Menu toggle Icon Start -->
	<div class="toggle-icon">
    <div id="nav-icon3">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div>
	</div>
	<!-- Menu toggle Icon End -->
	<!-- Main Website wrapper start -->
	<div id="main">
		<!--Main-Menu Area Start-->
		<div class="mainmenu-area">
			<nav class="my-navbar">
				<ul class="navbar-links">
					<li class="mynav-item active">
						<a class="mynav-link active" href="#home">Home</a>
					</li>
					<li class="mynav-item">
						<a class="mynav-link" href="#about">About</a>
					</li>
					<li class="mynav-item">
						<a class="mynav-link" href="#resume">Wedding Texts</a>
					</li>
					<li class="mynav-item portfolio">
						<a class="mynav-link portfolio" href="#concept">Concepts</a>
					</li>
					<li class="mynav-item">
						<a class="mynav-link" href="#blog">Agenda</a>
					</li>
					<li class="mynav-item">
						<a class="mynav-link" href="#contact">Rate Us</a>
					</li>
				</ul>
			</nav>
		</div>
		<!--Main-Menu Area Start-->

		<!--Hero Area Start-->
		<section class="home section-bg active" id="home" >
			<div class="h-100vh d-flex align-items-center">
				<div class="container">
					<div class="row">
						<div class="col-lg-6">
							<div class="main-profile-image">
								<img src="<?php echo base_url()?>assets/frontend/assets/images/logo.jpg" alt="">
							</div>
						</div>
						<div class="col-lg-6  align-self-center">
							<div class="hero-box text-left">
								<span class="greeting">Our Special Wedding Day</span>
								

								<?php $project = $this->Crud_m->view_where('project', array('id_session' => $clients->id_session))->row(); ?>							

								<?php if($project->religion === 'Islam') {?>

								<h2 class="name">
                				<?= $clients->f_bride_cname ?><span> & </span><?= $clients->m_bride_cname ?>
								</h2>

								<h4 class="header_title"><?= hari($clients->wedding_date) ?>, <?= tgl_indo($clients->wedding_date) ?> | <?= $clients->location ?></h4>
								<a id="g-p-f-h" class="pagelink mybtn mybtn-bg" href="#concept"><span><i
											class="far fa-calendar-check"></i>Our Wedding Concepts</span></a>


								<?php }elseif ($project->religion === 'Kristen'){ ?>

								<h2 class="name">
                				<?= $clients->m_bride_cname ?><span> & </span><?= $clients->f_bride_cname ?>
								</h2>

								<h4 class="header_title"><?= hari($clients->wedding_date) ?>, <?= tgl_indo($clients->wedding_date) ?> | <?= $clients->location ?></h4>
								<a id="g-p-f-h" class="pagelink mybtn mybtn-bg" href="#concept"><span><i
											class="far fa-calendar-check"></i>Our Wedding Concepts</span></a>

								<?php } ?>




							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Hero Area End-->

		<!-- About Area Start -->
		<section id="about" class="about-area section-padding section-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-heading">
							<h2 class="s-h-title">
								About <span>Us</span>
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
					<div class="d-flex justify-content-between align-items-center flex-wrap">
						<h3 class="mb-0">Data Pengantin Wanita</h3>
						<a href="<?= site_url('clients/c_edit/'. $clients->id_session) ?>" class="mybtn mybtn-bg mt-2 mt-md-0"> <span><i class="fas fa-user"></i>Edit Data</span> </a>
					</div>
						<div class="about-box">
							<div class="row">
								<div class="col-lg-12 d-flex align-self-center">
									<div class="about-content">
										<ul class="info-list">
											<li>
												<span class="title">Nama Lengkap : </span>
												<span class="value"><?= $clients->f_bride_fname ?></span>
											</li>
											<li>
												<span class="title">Nama Panggilan : </span>
												<span class="value"><?= $clients->f_bride_cname ?></span>
											</li>
											<?php if (!empty($clients->f_bride_fathername)) : ?>
											<li>
												<span class="title">Nama Ayah : </span>
												<span class="value"><?= $clients->f_bride_fathername ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->f_bride_fathercname)) : ?>
											<li>
												<span class="title">Nama Panggilan Ayah : </span>
												<span class="value"><?= $clients->f_bride_fathercname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->f_bride_freplacementname)) : ?>
											<li>
												<span class="title">Nama Pengganti Ayah : </span>
												<span class="value"><?= $clients->f_bride_freplacementname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->f_bride_freplacementcname)) : ?>
											<li>
												<span class="title">Nama Panggilan Pengganti Ayah : </span>
												<span class="value"><?= $clients->f_bride_freplacementcname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->f_bride_mothername)) : ?>
											<li>
												<span class="title">Nama Ibu : </span>
												<span class="value"><?= $clients->f_bride_mothername ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->f_bride_mothercname)) : ?>
											<li>
												<span class="title">Nama Panggilan Ibu : </span>
												<span class="value"><?= $clients->f_bride_mothercname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->f_bride_mreplacementname)) : ?>
											<li>
												<span class="title">Nama Pengganti Ibu : </span>
												<span class="value"><?= $clients->f_bride_mreplacementname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->f_bride_mreplacementcname)) : ?>
											<li>
												<span class="title">Nama Panggilan Pengganti Ibu : </span>
												<span class="value"><?= $clients->f_bride_mreplacementcname ?></span>
											</li>
											<?php endif; ?>
											<li>
												<span class="title">Pengantin Anak Ke : </span>
												<span class="value"><?= $clients->f_bride_nchild ?> dari <?= $clients->f_bride_hsibling ?> Bersaudara</span>
											</li>
											<li>
												<span class="title">Nama Saudara Kandung : </span>
												<span class="value"><?= nl2br($clients->f_bride_sibling) ?></span>
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
												<span class="value"><?= $clients->m_bride_fname ?></span>
											</li>
											<li>
												<span class="title">Nama Panggilan : </span>
												<span class="value"><?= $clients->m_bride_cname ?></span>
											</li>
											<?php if (!empty($clients->m_bride_fathername)) : ?>
											<li>
												<span class="title">Nama Ayah : </span>
												<span class="value"><?= $clients->m_bride_fathername ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->m_bride_fathercname)) : ?>
											<li>
												<span class="title">Nama Panggilan Ayah : </span>
												<span class="value"><?= $clients->m_bride_fathercname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->m_bride_freplacementname)) : ?>
											<li>
												<span class="title">Nama Pengganti Ayah : </span>
												<span class="value"><?= $clients->m_bride_freplacementname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->m_bride_freplacementcname)) : ?>
											<li>
												<span class="title">Nama Panggilan Pengganti Ayah : </span>
												<span class="value"><?= $clients->m_bride_freplacementcname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->m_bride_mothername)) : ?>
											<li>
												<span class="title">Nama Ibu : </span>
												<span class="value"><?= $clients->m_bride_mothername ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->m_bride_mothercname)) : ?>
											<li>
												<span class="title">Nama Panggilan Ibu : </span>
												<span class="value"><?= $clients->m_bride_mothercname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->m_bride_mreplacementname)) : ?>
											<li>
												<span class="title">Nama Pengganti Ibu : </span>
												<span class="value"><?= $clients->m_bride_mreplacementname ?></span>
											</li>
											<?php endif; ?>
											<?php if (!empty($clients->m_bride_mreplacementcname)) : ?>
											<li>
												<span class="title">Nama Panggilan Pengganti Ibu : </span>
												<span class="value"><?= $clients->m_bride_mreplacementcname ?></span>
											</li>
											<?php endif; ?>
											<li>
												<span class="title">Pengantin Anak Ke : </span>
												<span class="value"><?= $clients->m_bride_nchild ?> dari <?= $clients->m_bride_hsibling ?> Bersaudara</span>
											</li>
											<li>
												<span class="title">Nama Saudara Kandung : </span>
												<span class="value"><?= nl2br($clients->m_bride_sibling) ?></span>
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
												<span class="value"><?= hari($clients->wedding_date) ?>, <?= tgl_indo($clients->wedding_date) ?></span>
											</li>
											<li>
												<span class="title">Lokasi Acara : </span>
												<span class="value"><?= $clients->location ?></span>
											</li>
											<?php if ($islam) : ?>
											<li>
												<span class="title">Mahar : </span>
												<span class="value"><?= $clients->mahr ?></span>
											</li>
											<li>
												<span class="title">Simbolis Seserahan : </span>
												<span class="value"><?= $clients->handover ?> </span>
											</li>
											<li>
												<span class="title">Jubir Kel. Pria  : </span>
												<span class="value"><?= $clients->m_spokesman ?></span>
											</li>
											<li>
												<span class="title">Jubir Kel. Wanita : </span>
												<span class="value"><?= $clients->f_spokesman ?></span>
											</li>
											<li>
												<span class="title">Nama Penghulu : </span>
												<span class="value"><?= $clients->wedding_officiant ?></span>
											</li>
											<li>
												<span class="title">Wali Nikah : </span>
												<span class="value"><?= $clients->guardian ?></span>
											</li>
											<li>
												<span class="title">Saksi Dari Pria : </span>
												<span class="value"><?= $clients->m_witness ?></span>
											</li>
											<li>
												<span class="title">Saksi Dari Wanita : </span>
												<span class="value"><?= $clients->f_witness ?></span>
											</li>
											<li>
												<span class="title">Qori/Saritilawah : </span>
												<span class="value"><?= $clients->qori ?></span>
											</li>
											<li>
												<span class="title">Nasihat Pernikahan : </span>
												<span class="value"><?= $clients->advice_doa ?></span>
											</li>
											<li>
												<span class="title">Pengapit Pengantin Wanita : </span>
												<span class="value"><?= $clients->clamp ?></span>
											</li>
											<li>
												<span class="title">Pembawa Kalung Melati dari Kel. Wanita : </span>
												<span class="value"><?= $clients->jasmine_carrier ?></span>
											</li>
											<li>
												<span class="title">Pembawa Mahar/Mas Kawin dari Kel. Pria : </span>
												<span class="value"><?= $clients->mahr_carrier ?></span>
											</li>
											<li>
												<span class="title">Pembawa Cincin dari Kel. Pria : </span>
												<span class="value"><?= $clients->ring_carrier ?></span>
											</li>
											<li>
												<span class="title">Koor. Kel. Pria : </span>
												<span class="value"><?= $clients->male_coor ?></span>
											</li>
											<li>
												<span class="title">Koor. Kel. Wanita : </span>
												<span class="value"><?= $clients->female_coor ?></span>
											</li>
											<?php else : ?>
											<li>
												<span class="title">Koor. Kel. Pria : </span>
												<span class="value"><?= $clients->male_coor ?></span>
											</li>
											<li>
												<span class="title">Koor. Kel. Wanita : </span>
												<span class="value"><?= $clients->female_coor ?></span>
											</li>
                                            <li>
                                                <span class="title">Pendeta : </span>
                                                <span class="value"><?= $clients->pastor ?></span>
                                            </li>
                                            <li>
                                                <span class="title">Gereja : </span>
                                                <span class="value"><?= $clients->church ?></span>
                                            </li>
                                            <li>
                                                <span class="title">Pemimpin Doa : </span>
                                                <span class="value"><?= $clients->prayer ?></span>
                                            </li>
                                            <li>
												<span class="title">Sambutan Pernikahan : </span>
                                                <span class="value"><?= $clients->wedding_speech ?></span>
                                            </li>
                                            <?php endif; ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</section>
		<!-- About Area End -->

		<!-- Resume Area Start -->
		<section id="resume" class="about-area section-padding section-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-heading">
							<h2 class="s-h-title">
								Wedding <span>Texts</span>
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="about-box">
							<div class="row">
								<div class="col-lg-12 d-flex align-self-center">
									<div class="about-content">


								<?php if($project->religion === 'Islam') {?>
										<?php if (!empty($clients->wedding_ceremony)) : ?>
											<a href="<?= $clients->wedding_ceremony ?>" target="_blank" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>Susunan Acara Akad</span> 
											</a>
										<?php endif; ?>
										<?php if (!empty($clients->reception_afterward)) : ?>
											<a href="<?= $clients->reception_afterward ?>" target="_blank" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>Susunan Acara Resepsi</span> 
											</a>
										<?php endif; ?>
										<?php if (!empty($clients->id_session)) : ?>
											<a href="<?= site_url('naskah/data_pengantin/pdf/'. $clients->id_session) ?>" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>Susunan Panitia</span> 
											</a>
										<?php endif; ?>
										<?php if (!empty($clients->list_photo)) : ?>
											<a href="<?= $clients->list_photo ?>" target="_blank" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>List Tamu/Foto</span> 
											</a>
										<?php endif; ?>
								


								<?php }elseif ($project->religion === 'Kristen'){ ?>
										<?php if (!empty($clients->wedding_ceremony)) : ?>
											<a href="<?= $clients->wedding_ceremony ?>" target="_blank" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>Susunan Acara Pemberkatan</span> 
											</a>
										<?php endif; ?>
										<?php if (!empty($clients->reception_afterward)) : ?>
											<a href="<?= $clients->reception_afterward ?>" target="_blank" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>Susunan Acara Resepsi</span> 
											</a>
										<?php endif; ?>
										<?php if (!empty($clients->id_session)) : ?>
											<a href="<?= site_url('naskah/data_pengantin/pdf/'. $clients->id_session) ?>" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>Susunan Panitia</span> 
											</a>
										<?php endif; ?>
										<?php if (!empty($clients->list_photo)) : ?>
											<a href="<?= $clients->list_photo ?>" target="_blank" class="mybtn mybtn-bg"> 
												<span><i class="fas fa-download"></i>List Tamu/Foto</span> 
											</a>
										<?php endif; ?>
								

								<?php } ?>




										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ($islam) : ?>
				<div class="row">
					<div class="col-lg-12">
					<h3>Naskah Jubir Pengantin Pria</h3>
						<div class="about-box">
							<div class="row">
								<div class="col-lg-12 d-flex align-self-center">
									<div class="about-content">
                  <h4 class="text-xl font-bold text-center mb-4">Penyerahan Calon Pengantin Pria (Jubir CPP)</h4>
</br></br>
        <p class="text-lg">Bismillahirrohmanirrohim,</p>
        <p class="text-lg">Assalamu’alaikum wr.wb</p>
        <p class="indent text-lg text-justify">Alhamdulillah alhamdulillahi robbil ‘aalamiin, was-sholaatu wassalaamu ‘alaa asyrofil
        anbiyaa-i wal mursaliin, sayyidina muhammadin, wa’ala alihi wa’ashabihi aj’ma’iin, Amma ba’du.</p>
        <p class="indent text-lg text-justify">Shalawat serta salam marilah kita haturkan kepada junjungan Nabi Besar Muhammad
        SAW. , beserta keluarganya, dan para sahabat, dan kepada kita semua yang hingga saat ini
        masih istiqomah dalam mengamalkan risalahnya. Mudah-mudahan kita semua mendapatkan
        syafa’atnya di yaumil akhir kelak. Aamiin....</p>
        <p class="indent text-lg text-justify">Yang kami hormati para ‘Alim ulama yang dimuliakan Allah, para sesepuh, tokoh agama,
        tokoh masyarakat, dan para tamu undangan yang kami hormati wabil khusus <strong>keluarga besar
        bapak <?= $clients->f_bride_fathername; ?></strong> dan <strong>ibu <?= $clients->f_bride_mothername; ?></strong>.</p>
        <p class="indent text-lg text-justify">Ijinkan kami berdiri dihadapan bapak/ibu serta hadirin untuk memberikan sedikit sambutan
        mewakili keluarga dari <strong><?= $clients->m_bride_fname; ?></strong>.</p>
        <p class="indent text-lg text-justify">Pertama - tama kami sekeluarga menyampaikan salam hormat kepada keluarga besar
        bapak & Ibu dengan iringan doa semoga selalu dalam lindungan dan ridho Allah SWT.</p>
        <p class="indent text-lg text-justify">Kedua, saya selaku wakil dari keluarga bermaksud mengantarkan dan menyerahkan
        <strong><?= $clients->m_bride_fname; ?></strong>. Sesuai rencana yang disepakati bersama yang akan dinikahkan
        dengan <strong><?= $clients->f_bride_fname; ?></strong>.</p>
        <p class="indent text-lg text-justify">Menyertai keperluan proses ini kami juga menyiapkan mas kawin sesuai permintaan,
        sebagai salah satu syarat utama sebuah pernikahan.</p>
        <p class="indent text-lg text-justify">Jika berkenan menerima, kami juga membawa sedikit souvenir sebagai tanda cinta dan
        pengikat tali kekeluargaan.</p>
        <p class="indent text-lg text-justify">Namun yang utama dari barang bawaan ini adalah niat ikhlas kami, jadi kami mohon tidak
        menilai mengenai harganya.</p>
        <p class="indent text-lg text-justify">Selanjutnya, apabila sudah tiba waktunya yang dijadwalkan, kami memohon kiranya yang
        mewakili <strong>keluarga bapak <?= $clients->f_bride_fathername; ?></strong> berkenan untuk segera menikahkan mereka
        berdua.</p>
        <p class="indent text-lg text-justify">Kami sekeluarga besar senantiasa mengiringi dengan doa dan restu, semoga proses ini
        dapat berjalan lancar tanpa ada halangan suatu apapun serta dalam berkah dan ridho Allah SWT
        aamiin ya Robbal aalamiin.</p>
        <p class="indent text-lg text-justify">Saya selaku wakil keluarga yang bertindak dalam penyerahan calon pengantin pria,
        apabila ada tutur kata ataupun tingkah laku saya dan juga segenap rombongan yang kurang
        berkenan, saya memohon maaf yang sebesar besarnya dan berharap semoga penyerahan ini
        kiranya dapat diterima dengan penuh keikhlasan, demikian dan terima kasih.</p>
        <p class="text-lg">Billahi taufik wal hidayah wassalamu-alaikum Wr.Wb.</p>
										<a href="<?= site_url('naskah/jubir_cpp/pdf/'. $clients->id_session) ?>" class="mybtn mybtn-bg"> <span><i class="fas fa-download"></i>Download </span> </a>								
										
									</div>
									
								</div>
							</div>
						</div>
						</div>
					</div>
				<div class="row">
					<div class="col-lg-12">
					<h3>Naskah Jubir Pengantin Wanita</h3>
						<div class="about-box">
							<div class="row">
								<div class="col-lg-12 d-flex align-self-center">
									<div class="about-content">
                  <h4 class="text-xl font-bold text-center mb-4">Penerimaan Calon Pengantin Pria (Jubir CPW)</h4>
</br></br>
        <p class="text-lg">Bismillahirrohmanirrohim,</p>
        <p class="text-lg">Assalamu’alaikum wr.wb</p>
        <p class="indent text-lg text-justify">Yang kami hormati para sesepuh, tokoh agama, tokoh masyarakat, wabil khusus
        yang terhormat bapak dan ibu serta tamu undangan sekalian yang kami muliakan.</p>
        <p class="indent text-lg text-justify">Pertama-tama marilah kita panjatkan puji syukur kehadirat Allah swt. Bahwa pada
        kesempatan yang berbahagia ini, kita semua masih diberikan rahmat, hidayah, serta
        nikmat sehat, sehingga kita semua dapat bertemu dan berkumpul dalam rangka
        menghadiri acara akad nikah <strong><?= $clients->f_bride_fname; ?></strong> dan <strong><?= $clients->m_bride_fname; ?></strong>.</p>
        <p class="indent text-lg text-justify">Saya mewakili keluarga menerima kehadiran keluarga besar calon mempelai pria
        dan menyatakan dengan ikhlas, kami dapat menerima.</p>
        <p class="indent text-lg text-justify">Pada hari ini untuk dinikahkan dengan putri bapak <strong><?= $clients->f_bride_fathername; ?>.</strong> dan ibu <strong><?= $clients->f_bride_mothername; ?>.</strong>
        yang bernama <strong><?= $clients->f_bride_fname; ?></strong>, semoga semua rencana kita akan mendapat petunjuk dan
        bimbingan dari Allah SWT. Amiin...</p>
        <p class="indent text-lg text-justify">Dan berbagai hantaran yang telah disampaikan, kami menerima dengan senang
        hati dengan ucapan Alhamdulilah. Semoga mendapatkan balasan yang berlimpah dari
        Allah SWT. Aamiin....</p>
        <p class="indent text-lg text-justify">Itulah yang dapat kami sampaikan, kami akhiri, Billahi taufik wal hidayah</p>
        <p class="text-lg">Wassalamu’alaikum. Wr.Wb</p>

										<a href="<?= site_url('naskah/jubir_cpw/pdf/'. $clients->id_session) ?>" class="mybtn mybtn-bg"> <span><i class="fas fa-download"></i>Download </span> </a>								
										
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
						<div class="col-lg-12">
						<h3>Izin Nikah</h3>
							<div class="about-box">
								<div class="row">
									<div class="col-lg-12 d-flex align-self-center">
										<div class="about-content">
                    <h4 class="text-xl font-bold text-center mb-4">Permohonan Izin Menikah (CPW)</h4>
</br>
        <p class="text-lg leading-relaxed">Bismillahirrohmanirrohim,</p>
        <p class="text-lg leading-relaxed">Astagfirullahal’adzim 3x</p>
        <p class="text-lg leading-relaxed">Asyhadualla illa ha illalah, wa asyhadu anna muhammadarrosulullah.</p>
        <p class="text-lg leading-relaxed"><?php if (!empty($clients->f_bride_freplacementcname)) {echo $clients->f_bride_freplacementcname;} else {echo $clients->f_bride_fathercname;}?>
        dan <?php if (!empty($clients->f_bride_mreplacementcname)) {echo $clients->f_bride_mreplacementcname;} else {echo $clients->f_bride_mothercname;}?> yang <?= $clients->f_bride_cname; ?> cintai dan hormati, <?= $clients->f_bride_cname; ?> bersyukur dan berterima
        kasih kepada Allah SWT karena telah diberikan limpahan perhatian, kasih
        sayang dan cinta kasih pada <?= $clients->f_bride_cname; ?> tiada henti.</p>
        <p class="text-lg leading-relaxed text-justify"><?= $clients->f_bride_cname; ?> menghaturkan permohonan maaf yang sedalam-dalamnya atas
        segala kehilafan dan kesalahan <?= $clients->f_bride_cname; ?>, baik kata-kata maupun perbuatan
        yang menyakiti <?php if (!empty($clients->f_bride_freplacementcname)) {echo $clients->f_bride_freplacementcname;} else {echo $clients->f_bride_fathercname;}?>
        dan <?php if (!empty($clients->f_bride_mreplacementcname)) {echo $clients->f_bride_mreplacementcname;} else {echo $clients->f_bride_mothercname;}?>.</p>
        <p class="text-lg leading-relaxed text-justify">Hari ini <?= hari($clients->wedding_date) ?>, <?= tgl_indo($clients->wedding_date) ?>, <?= $clients->f_bride_cname; ?> memohon izin dan memohon
        restu untuk dinikahkan dengan lelaki pilihan <?= $clients->f_bride_cname; ?>, untuk menemani
        perjalanan panjang hidup <?= $clients->f_bride_cname; ?> kelak.</p>
        <p class="text-lg leading-relaxed text-justify">Seorang laki-laki bernama <?= $clients->m_bride_fname; ?>, yang Insha’Allah
        bisa menjadi imam yang bijak dan penuh kasih sayang.</p></br>

        <h4 class="text-xl font-bold text-center mb-4">Permohonan Izin Menikah (<?php if (!empty($clients->f_bride_freplacementcname)) {echo $clients->f_bride_freplacementcname;} else {echo $clients->f_bride_fathercname;}?>)</h4>
</br>
        <p class="text-lg leading-relaxed text-justify">Putriku <?= $clients->f_bride_cname; ?>, penyampaian izin pernikahanmu dan permohonan restumu
        sudah <?php if (!empty($clients->f_bride_freplacementcname)) {echo $clients->f_bride_freplacementcname;} else {echo $clients->f_bride_fathercname;}?> restui dan <?php if (!empty($clients->f_bride_freplacementcname)) {echo $clients->f_bride_freplacementcname;} else {echo $clients->f_bride_fathercname;}?> dengar dengan seksama.</p>
        <p class="text-lg leading-relaxed text-justify">Karena Insya Allah sebentar lagi <?php if (!empty($clients->f_bride_freplacementcname)) {echo $clients->f_bride_freplacementcname;} else {echo $clients->f_bride_fathercname;}?> akan segera menikahkanmu
        dengan calon suamimu yang bernama <?= $clients->m_bride_fname; ?>.</p>
        <p class="text-lg leading-relaxed text-justify">Teriring doa, semoga Allah meridhoi hajat pernikahan yang akan <?php if (!empty($clients->f_bride_freplacementcname)) {echo $clients->f_bride_freplacementcname;} else {echo $clients->f_bride_fathercname;}?>
        langsungkan sebentar lagi. Hingga rumah tanggamu nanti senantiasa
        rukun, damai dan bahagia penuh rahmat dan keberkahan dari Allah SWT.</p>
        <p class="text-lg leading-relaxed">Aamiin aamiin Allahumma aamiin..</p>
		<?php if (empty($clients->f_bride_freplacementname)): ?>
		<div class="box">
        Saya terima nikah dan
        kawinnya <?= $clients->f_bride_fname; ?> binti
        <?= $clients->f_bride_fathername; ?> dengan
        mas kawin tersebut dibayar
        tunai
    </div>
	<?php endif; ?>
											<a href="<?= site_url('naskah/izin_menikah/pdf/'. $clients->id_session) ?>" class="mybtn mybtn-bg"> <span><i class="fas fa-download"></i>Download </span> </a>								
											
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				<div class="row">
					<div class="col-lg-12">
					<h3>Ucapan Terima Kasih Pengantin Ke Tamu Resepsi</h3>
						<div class="about-box">
							<div class="row">
								<div class="col-lg-12 d-flex align-self-center">
									<div class="about-content">
                  <h4 class="text-xl font-bold text-center mb-4">Kata Sambutan Terima Kasih Oleh Pengantin Pria Di Resepsi</h4>
</br></br>
        <p class="text-lg">Assalamualaikum warahmatullahi wabarakatuh.</p>
        <p class="indent text-lg text-justify">Rasa syukur yang tak terhingga saya panjatkan kehadirat Allah SWT atas
        segala rahmat dan karunia-Nya, sehingga pada hari yang berbahagia ini
        kita dapat berkumpul bersama dalam acara pernikahan saya dan <?= $clients->f_bride_fname; ?>.</p>
        <p class="indent text-lg text-justify">Kehadiran Bapak, Ibu, saudara-saudara sekalian merupakan kehormatan
        bagi kami. Doa dan restu yang Bapak, Ibu, dan saudara-saudara sekalian
        berikan akan menjadi semangat bagi kami dalam membangun bahtera
        rumah tangga.</p>
        <p class="indent text-lg text-justify">Terima kasih juga kepada keluarga besar, sahabat, dan rekan kerja yang
        telah banyak membantu dalam mempersiapkan acara ini. Tanpa dukungan
        kalian semua, acara ini tidak akan berjalan dengan lancar.</p>
        <p class="indent text-lg text-justify">Semoga Allah SWT senantiasa melimpahkan rahmat dan karunia-Nya
        kepada kita semua. Amin.</p>
        <p class="text-lg">Terima kasih.</p>
										<a href="<?= site_url('naskah/terima_kasih/pdf/'. $clients->id_session) ?>" class="mybtn mybtn-bg"> <span><i class="fas fa-download"></i>Download </span> </a>								
										
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php else : ?>
				<div class="row">
					<div class="col-lg-12">
					<h3>Ucapan Terima Kasih Pengantin Ke Tamu Resepsi</h3>
						<div class="about-box">
							<div class="row">
								<div class="col-lg-12 d-flex align-self-center">
									<div class="about-content">
                  <h4 class="text-xl font-bold text-center mb-4">Kata Sambutan Terima Kasih Oleh Pengantin Pria Di Resepsi</h4>
</br></br>
<p class="indent text-lg text-justify">Dengan penuh rasa syukur, kami mengucapkan terima kasih yang sebesar-besarnya kepada semua saudara dan saudari yang telah hadir dalam perayaan pernikahan kami. Kehadiran dan doa-doa yang tulus dari Anda semua sangat berarti bagi kami. Kami merasa diberkati karena dikelilingi oleh orang-orang yang begitu mengasihi dan mendukung kami.
Kami berdoa agar kasih Tuhan senantiasa menyertai setiap langkah hidup kita, dan semoga kita semua selalu diberkati dengan kedamaian, kebahagiaan, serta keberhasilan dalam segala hal. Terima kasih telah menjadi bagian dari hari yang sangat istimewa ini.</p>
<p>Tuhan memberkati kita semua.</p>
										<a href="<?= site_url('naskah/terima_kasih2/pdf/'. $clients->id_session) ?>" class="mybtn mybtn-bg"> <span><i class="fas fa-download"></i>Download </span> </a>								
										
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</section>
		<!-- Resume Area End -->

		<!-- Portfolio Area Start -->
		<section id="portfolio" class="project-gallery section-padding  section-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-heading">
							<h2 class="s-h-title">
								Wedding <span>Concepts</span>
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="row project-gallery-item">
							<?php 
							$order = ['Venue', 'MC Akad', 'MC Pemberkatan', 'MC Resepsi', 'MC Adat Jawa', 'MC Adat Sunda', 'Wedding Organizer', 'MUA', 'Attire', 'Perlengkapan Catering', 'Catering', 'Dokumentasi', 'Dekorasi', 'Entertainment', 'Penyewaan Peralatan', 'Pendukung Pernikahan Lainnya'];
							usort($vendors, function($a, $b) use ($order) {
								$pos_a = array_search($a->type, $order);
								$pos_b = array_search($b->type, $order);
								return $pos_a - $pos_b;
							});
							foreach ($vendors as $vendor) : ?>
							<div class="mix col-md-6 col-lg-6 gallery-item cat-1 cat-3">
								<a href="<?php echo site_url('clients/c_concept?id_session=' . $vendor->id_session . '&vendor_id=' . $vendor->vendor_id); ?>" class="gallery-item-content pp">
									<div class="item-thumbnail">
									<?php if(empty($vendor->photo1)){?>
										<img src="<?php echo base_url()?>assets/frontend/blank.png?>" alt="" style="width: 100%; height: 300px; object-fit: cover;">
									<?php } else { 
										$partner_path = FCPATH . 'uploads/partner/' . $vendor->photo1;
										if (file_exists($partner_path)) { ?>
											<img src="<?php echo base_url()?>uploads/partner/<?= $vendor->photo1 ?>" alt="" style="width: 100%; height: 300px; object-fit: cover;">
										<?php } else { ?>
											<img src="<?php echo base_url()?>uploads/<?= $vendor->photo1 ?>" alt="" style="width: 100%; height: 300px; object-fit: cover;">
										<?php } 
									} ?>

										<div class="content-overlay">
											<div class="content">
												<h4 class="project-title">
													<?= $vendor->type ?>
												</h4>
												<span class="project-category"><?= $vendor->vendor ?></span>
											</div>
										</div>
									</div>
								</a>
							</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Portfolio Area End -->

		<!-- Blog List  Area Start -->
		<section class="blogs blog-page sidebar section-padding  section-bg" id="blog">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-heading">
							<h2 class="s-h-title">
								Wedding <span>Agenda</span>
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="row">
							<div class="col-lg-4 col-md-6">
								<div class="blog-box">
									<div class="blog-images">
										<img src="<?php echo base_url()?>assets/frontend/assets/images/agenda1.jpg" class="img-fluid" alt="">
									</div>
									<div class="blog-details">
										<ul class="post-meta-one">
											<li>
												<p><i class="fa fa-clock-o"></i><?= !empty($agenda->brainstorming) ? hari($agenda->brainstorming) . ', ' . tgl_indo($agenda->brainstorming) : 'Tanggal belum ditentukan' ?></p>
											</li>
										</ul>

										<div class="blog-title">
											Brainstorming Concept
										</div>
										<p class="text">
											Penyusunan konsep pernikahan mulai dari souvenir, undangan pernikahan, susunan acara, dekorasi dan lain lain.
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="blog-box">
									<div class="blog-images">
										<img src="<?php echo base_url()?>assets/frontend/assets/images/agenda2.jpg" class="img-fluid" alt="">
									</div>
									<div class="blog-details">
										<ul class="post-meta-one">
											<li>
												<p><i class="fa fa-clock-o"></i><?= !empty($agenda->technical_meeting) ? hari($agenda->technical_meeting) . ', ' . tgl_indo($agenda->technical_meeting) : 'Tanggal belum ditentukan' ?></p>
											</li>
										</ul>

										<div class="blog-title">
											Technical Meeting
										</div>
										<p class="text">
											Pertemuan dengan para vendor pernikahan sebagai tujuan untuk memastikan berjalan sesuai yang direncanakan.
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="blog-box">
									<div class="blog-images">
										<img src="<?php echo base_url()?>assets/frontend/assets/images/agenda3.jpg" class="img-fluid" alt="">
									</div>
									<div class="blog-details">
										<ul class="post-meta-one">
											<li>
												<p><i class="fa fa-clock-o"></i><?= !empty($agenda->final_revision) ? hari($agenda->final_revision) . ', ' . tgl_indo($agenda->final_revision) : 'Tanggal belum ditentukan' ?></p>
											</li>
										</ul>

										<div class="blog-title">
											Final Revisi
										</div>
										<p class="text">
											Batas akhir finalisasi konsep pernikahan untuk memastikan segala keperluan pernikahan telah sesuai.
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="blog-box">
									<div class="blog-images">
										<img src="<?php echo base_url()?>assets/frontend/assets/images/agenda4.jpg" class="img-fluid" alt="">
									</div>
									<div class="blog-details">
										<ul class="post-meta-one">
											<li>
												<p><i class="fa fa-clock-o"></i><?= !empty($agenda->loading_decoration) ? hari($agenda->loading_decoration) . ', ' . tgl_indo($agenda->loading_decoration) : 'Tanggal belum ditentukan' ?></p>
											</li>
										</ul>

										<div class="blog-title">
											Loading & Installation 
										</div>
										<p class="text">
											Pemasangan keperluan pernikahan seperti dekorasi, sound, alat catering dan lainnya sesuai dengan tata letak.  
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="blog-box">
									<div class="blog-images">
										<img src="<?php echo base_url()?>assets/frontend/assets/images/agenda5.jpg" class="img-fluid" alt="">
									</div>
									<div class="blog-details">
										<ul class="post-meta-one">
											<li>
												<p><i class="fa fa-clock-o"></i><?= !empty($agenda->wedding_day) ? hari($agenda->wedding_day) . ', ' . tgl_indo($agenda->wedding_day) : 'Tanggal belum ditentukan' ?></p>
											</li>
										</ul>

										<div class="blog-title">
											Wedding Day
										</div>
										<p class="text">
											Hari istimewa pernikahan kamu dan pasangan dapat berjalan sesuai dengan wedding dream yang telah dirancang.
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="blog-box">
									<div class="blog-images">
										<img src="<?php echo base_url()?>assets/frontend/assets/images/agenda6.jpg" class="img-fluid" alt="">
									</div>
									<div class="blog-details">
										<ul class="post-meta-one">
											<li>
												<p><i class="fa fa-clock-o"></i><?= !empty($agenda->honeymoon) ? hari($agenda->honeymoon) . ', ' . tgl_indo($agenda->honeymoon) : 'Tanggal belum ditentukan' ?></p>
											</li>
										</ul>

										<div class="blog-title">
											Honeymoon
										</div>
										<p class="text">
											Yeaay! Waktunya tiba hari liburan pertama kali kamu dan pasangan yang telah sah menjadi suami istri.
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Blog List  Area End -->

		<!-- Contact Us Area Start -->
		<section class="contact contact-info-area section-padding  section-bg" id="contact">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-heading">
							<h2 class="s-h-title">
								Rate <span>Us</span>
							</h2>
							Ulasan Mereka Menjadi Saksi Kinerja Mantenbaru Dalam Memberikan Yang Terbaik Di Setiap Pernikahan
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="single-review">
							<div class="reviewr">
								<div class="img">
									<img src="<?php echo base_url()?>assets/frontend/assets/images/rev1.jpg" alt="">
								</div>
								<div class="content">
									<h4 class="name">
										Keupek Ridho
									</h4>
									<p>
										The Wedding Of Nurul & Ridho
									</p>
								</div>
							</div>
							<div class="stars">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
							</div>
							<div class="content">
								<p>
									Terimakasih Mantenbaru sudah melancarkan acara pernikahan kami, timnya keren, ramah, pelayanannya mantap pengantin kaya raja dan ratu dilayani.
									Bisa kerjasama juga sama WO syari. Dah mantep manten baru ini keren
								</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="single-review">
							<div class="reviewr">
								<div class="img">
									<img src="<?php echo base_url()?>assets/frontend/assets/images/samson.jpg" alt="">
								</div>
								<div class="content">
									<h4 class="name">
										Samson Sinaga
									</h4>
									<p>
										The Wedding Of Samson & Dina
									</p>
								</div>
							</div>
							<div class="stars">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
							</div>
							<div class="content">
								<p>
									Seneng acara bisa berjalan dengan lancar dan tertata rapi, itu semua berkat mantenbaru yang sangat kooperatif dan bisa mewujudkan apa yang kami minta, apalagi ketika acara begitu supportif dan sigap ketika acara berlangsung. Terima kasih yang sebesar-besarnya mantenbaru.....😁🙏 …
								</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="single-review">
							<div class="reviewr">
								<div class="img">
									<img src="<?php echo base_url()?>assets/frontend/assets/images/arsyad.jpg" alt="">
								</div>
								<div class="content">
									<h4 class="name">
										Arsyad Husyaen
									</h4>
									<p>
										The Wedding Of Imas & Arsyad
									</p>
								</div>
							</div>
							<div class="stars">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
							</div>
							<div class="content">
								<p>
									Di support sama tim Mantenbaru Wedding Organizer sangat membantu, keperluan kita sangat di permudah, dan sangat profesional sampe kga keliatan mereka udah makan atau belum. Terimaksih support nya
								</p>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="single-review">
							<div class="reviewr">
								<div class="img">
									<img src="<?php echo base_url()?>assets/frontend/assets/images/dini.jpg" alt="">
								</div>
								<div class="content">
									<h4 class="name">
										Anisa Fitria Aldini
									</h4>
									<p>
										The Wedding Of Dini & Dhimas
									</p>
								</div>
							</div>
							<div class="stars">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
							</div>
							<div class="content">
								<p>
									Terima kasih untuk team manten baru! Di bantu a-z nya banget! Kayak tuan putri sehari🤭 sukses selalu kedepannya untuk mantenbaru! …
								</p>
							</div>							
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="single-review">
								<div class="reviewr">
									<div class="img">
										<img src="<?php echo base_url()?>assets/frontend/assets/images/yasmine.jpg" alt="">
									</div>
									<div class="content">
										<h4 class="name">
											Yasmine Alfredo
										</h4>
										<p>
											The Wedding Of Yasmine & Andre
										</p>
									</div>
								</div>
								<div class="stars">
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
								</div>
								<div class="content">
									<p>
										Alhamdulillah gak salah pilih vendor, acaraku jadi terorganized dan teamnya sangat bisa berkomunikasi dengan baik. Paham sama keinginan manten dan perhatian sama every single details yang ternyata di momen acara itu aku butuhkan, aku happy banget. Aku dan suamiku, mau terima kasih banyak untuk seluruh team MantenBaru yang aku gak bisa sebutkan satu persatu, semoga makin sukses untuk MantenBaru!
									</p>
								</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="single-review">
							<div class="reviewr">
								<div class="img">
									<img src="<?php echo base_url()?>assets/frontend/assets/images/ghazy2.jpg" alt="">
								</div>
								<div class="content">
									<h4 class="name">
										Ghazi Fadhil Muhammad
									</h4>
									<p>
										The Wedding Of Gita & Ghazy
									</p>
								</div>
							</div>
							<div class="stars">
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
								<i class="fas fa-star"></i>
							</div>
							<div class="content">
								<p>
									WO underrated banget. Komunikasi baik, fast respon, staff2nya juga supportif, asik2 tapi tetep profesional. Biaya terjangkau tapi hasilnya memuaskan.
								</p>
							</div>
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="section-heading">
							<h3 class="">
								<span>Ulasan Kamu Sangat Berarti Untuk Perkembangan Mantenbaru</span>
							</h3>
							<a href="https://g.page/r/CfXM0XtrsWnfEAE/review" target="_blank" class="mybtn mybtn-bg"> <span><i class="fas fa-hand-holding-heart"> Berikan Ulasan Disini</i></span> </a> 
						</div>
					</div>
				</div>

				<!--/.row-->
			</div>
		</section>
		<!-- Contact Us Area End -->
	</div>
	<!-- Main Website wrapper End -->

	<!-- Blog Modal Start-->
	<div class="modal fade" id="blogmodal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<div class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</div>
					<div class="blog-details">
						<div class="blog-content">
							<div class="feature-image">
								<img src="<?php echo base_url()?>assets/frontend/assets/images/b1.jpg" class="img-fluid" alt="">
							</div>
							<div class="content">
								<h3 class="title">
									By an outlived insisted procured improved am. Paid hill fine ten now love even leaf.
								</h3>
								<ul class="post-meta">
									<li>
										<a href="#">
											<i class="fas fa-user-tie"></i>
											<span>Alex Jole</span>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="far fa-calendar-alt"></i>
											<span>
												February 21, 2019
											</span>
										</a>
									</li>

								</ul>
								<p>
									Now for manners use has company believe parlors. Least nor party who wrote while did. Excuse formed as
									is agreed admire so on result parish. Put use set uncommonly announcing and travelling. Allowance
									sweetness direction to as necessary. Principle oh explained excellent do my suspected conveying in.
								</p>
								<p>
									Least nor party who wrote while did. Excuse formed as is agreed admire so on result parish. Put use
									set
									uncommonly announcing and travelling.
								</p>
							
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- Blog Modal End-->

	<!-- Main jquery and all jquery plugin hear -->
  <script src="<?php echo base_url()?>assets/frontend/assets/js/jquery.js"></script>
  <script src="<?php echo base_url()?>assets/frontend/assets/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url()?>assets/frontend/assets/js/magnific-popup.js"></script>
  <script src="<?php echo base_url()?>assets/frontend/assets/js/circel.js"></script>
  <script src="<?php echo base_url()?>assets/frontend/assets/js/typed.min.js"></script>
  <script src="<?php echo base_url()?>assets/frontend/assets/js/mixitup.min.js"></script>
  <script src="<?php echo base_url()?>assets/frontend/assets/js/owl.carousel.min.js"></script>
  <script src="<?php echo base_url()?>assets/frontend/assets/js/main.js"></script>

</body>

</html>