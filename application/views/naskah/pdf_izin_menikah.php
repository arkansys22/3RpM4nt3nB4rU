<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Permohonan Izin Menikah</title>
    <style>
        p {
    font-size: 18px; /* Ukuran teks lebih besar dari standar (16px) */
    line-height: 1.5; /* Agar lebih nyaman dibaca */
    }
        .text-justify {
            text-align: justify;
        }
        .indent {
            text-indent: 2em; /* Memberikan indentasi pada paragraf */
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Permohonan Izin Menikah (CPW)</h2></br>
        <p class="text-justify indent">Bismillahirrohmanirrohim,</p>
        <p class="text-justify indent">Astagfirullahal’adzim 3x</p>
        <p class="text-justify indent">Asyhadualla illa ha illalah, wa asyhadu anna muhammadarrosulullah.</p>
        <p class="text-justify indent"><?= $client->f_bride_fathercname; ?> dan <?= $client->f_bride_mothercname; ?> yang <?= $client->f_bride_cname; ?> cintai dan hormati, <?= $client->f_bride_cname; ?> bersyukur dan berterima
        kasih kepada Allah SWT karena telah diberikan limpahan perhatian, kasih
        sayang dan cinta kasih pada <?= $client->f_bride_cname; ?> tiada henti.</p>
        <p class="text-justify indent"><?= $client->f_bride_cname; ?> menghaturkan permohonan maaf yang sedalam-dalamnya atas
        segala kehilafan dan kesalahan <?= $client->f_bride_cname; ?>, baik kata-kata maupun perbuatan
        yang menyakiti <?= $client->f_bride_fathercname; ?> dan <?= $client->f_bride_mothercname; ?>.</p>
        <p class="text-justify indent">Hari ini <?= hari($client->wedding_date) ?>, <?= tgl_indo($client->wedding_date) ?>, <?= $client->f_bride_cname; ?> memohon izin dan memohon
        restu untuk dinikahkan dengan lelaki pilihan <?= $client->f_bride_cname; ?>, untuk menemani
        perjalanan panjang hidup <?= $client->f_bride_cname; ?> kelak.</p>
        <p class="text-justify indent">Seorang laki-laki bernama <?= $client->m_bride_fname; ?>, yang Insha’Allah
        bisa menjadi imam yang bijak dan penuh kasih sayang.</p><br>

    <h2 style="text-align: center;">Permohonan Izin Menikah (<?= $client->f_bride_fathercname; ?>)</h2></br>
        <p class="text-justify indent">Putriku <?= $client->f_bride_cname; ?>, penyampaian izin pernikahanmu dan permohonan restumu
        sudah <?= $client->f_bride_fathercname; ?> restui dan <?= $client->f_bride_fathercname; ?> dengar dengan seksama.</p>
        <p class="text-justify indent">Karena Insya Allah sebentar lagi <?= $client->f_bride_fathercname; ?> akan segera menikahkanmu
        dengan calon suamimu yang bernama <?= $client->m_bride_fname; ?>.</p>
        <p class="text-justify indent">Teriring doa, semoga Allah meridhoi hajat pernikahan yang akan <?= $client->f_bride_fathercname; ?>
        langsungkan sebentar lagi. Hingga rumah tanggamu nanti senantiasa
        rukun, damai dan bahagia penuh rahmat dan keberkahan dari Allah SWT.</p>
        <p class="text-justify indent">Aamiin aamiin Allahumma aamiin..</p>
</body>
</html>
