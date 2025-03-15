<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Permohonan Izin Menikah</title>
    <style>
        p {
    font-size: 20px; /* Ukuran teks lebih besar dari standar (16px) */
    line-height: 1; /* Agar lebih nyaman dibaca */
    }
        .text-justify {
            text-align: justify;
        }
        .box {
            width: 40%; /* Setengah lebar halaman */
            margin: 5px auto; /* Tengah secara horizontal */
            border: 2px solid black; /* Border hitam */
            padding: 15px; /* Jarak dalam */
            text-align: justify; /* Teks rata kanan kiri */
            font-weight: bold; /* Teks tebal */
            font-size: 20px; /* Ukuran teks lebih besar */
            line-height: 1.8; /* Jarak antar baris */
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Permohonan Izin Menikah (CPW)</h2></br>
        <p class="text-justify">Bismillahirrohmanirrohim,</p>
        <p class="text-justify">Astagfirullahal’adzim 3x</p>
        <p class="text-justify">Asyhadualla illa ha illalah, wa asyhadu anna muhammadarrosulullah.</p>
        <p class="text-justify"><?php if (!empty($client->f_bride_freplacementcname)) {echo $client->f_bride_freplacementcname;} else {echo $client->f_bride_fathercname;}?>
        dan <?php if (!empty($client->f_bride_mreplacementcname)) {echo $client->f_bride_mreplacementcname;} else {echo $client->f_bride_mothercname;}?> yang <?= $client->f_bride_cname; ?> cintai dan hormati, <?= $client->f_bride_cname; ?> bersyukur dan berterima
        kasih kepada Allah SWT karena telah diberikan limpahan perhatian, kasih
        sayang dan cinta kasih pada <?= $client->f_bride_cname; ?> tiada henti.</p>
        <p class="text-justify"><?= $client->f_bride_cname; ?> menghaturkan permohonan maaf yang sedalam-dalamnya atas
        segala kehilafan dan kesalahan <?= $client->f_bride_cname; ?>, baik kata-kata maupun perbuatan
        yang menyakiti <?php if (!empty($client->f_bride_freplacementcname)) {echo $client->f_bride_freplacementcname;} else {echo $client->f_bride_fathercname;}?>
        dan <?php if (!empty($client->f_bride_mreplacementcname)) {echo $client->f_bride_mreplacementcname;} else {echo $client->f_bride_mothercname;}?>.</p>
        <p class="text-justify">Hari ini <?= hari($client->wedding_date) ?>, <?= tgl_indo($client->wedding_date) ?>, <?= $client->f_bride_cname; ?> memohon izin dan memohon
        restu untuk dinikahkan dengan lelaki pilihan <?= $client->f_bride_cname; ?>, untuk menemani
        perjalanan panjang hidup <?= $client->f_bride_cname; ?> kelak.</p>
        <p class="text-justify">Seorang laki-laki bernama <?= $client->m_bride_fname; ?>, yang Insha’Allah
        bisa menjadi imam yang bijak dan penuh kasih sayang.</p><br>

    <h2 style="text-align: center;">Permohonan Izin Menikah (<?php if (!empty($client->f_bride_freplacementcname)) {echo $client->f_bride_freplacementcname;} else {echo $client->f_bride_fathercname;}?>)</h2></br>
        <p class="text-justify">Putriku <?= $client->f_bride_cname; ?>, penyampaian izin pernikahanmu dan permohonan restumu
        sudah <?php if (!empty($client->f_bride_freplacementcname)) {echo $client->f_bride_freplacementcname;} else {echo $client->f_bride_fathercname;}?> restui dan <?php if (!empty($client->f_bride_freplacementcname)) {echo $client->f_bride_freplacementcname;} else {echo $client->f_bride_fathercname;}?> dengar dengan seksama.</p>
        <p class="text-justify">Karena Insya Allah sebentar lagi <?php if (!empty($client->f_bride_freplacementcname)) {echo $client->f_bride_freplacementcname;} else {echo $client->f_bride_fathercname;}?> akan segera menikahkanmu
        dengan calon suamimu yang bernama <?= $client->m_bride_fname; ?>.</p>
        <p class="text-justify">Teriring doa, semoga Allah meridhoi hajat pernikahan yang akan <?php if (!empty($client->f_bride_freplacementcname)) {echo $client->f_bride_freplacementcname;} else {echo $client->f_bride_fathercname;}?>
        langsungkan sebentar lagi. Hingga rumah tanggamu nanti senantiasa
        rukun, damai dan bahagia penuh rahmat dan keberkahan dari Allah SWT.</p>
        <p class="text-justify">Aamiin aamiin Allahumma aamiin..</p>
        <br>
        <?php if (empty($client->f_bride_freplacementname)): ?>
        <div class="box">
        Saya terima nikah dan
        kawinnya <?= $client->f_bride_fname; ?> binti
        <?= $client->f_bride_fathername; ?> dengan
        mas kawin tersebut dibayar
        tunai
        </div>
        <?php endif; ?>
</body>
</html>
