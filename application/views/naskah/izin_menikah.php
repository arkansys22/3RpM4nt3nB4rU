<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naskah Izin Menikah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
    .no-print {
        display: none !important; /* Menyembunyikan elemen dengan class 'no-print' */
    }
    @page {
        margin: 0; /* Menghapus margin default browser */
    }
    }
        p.indent {
            text-indent: 40px; /* Indentasi awal paragraf */
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg">
        <h1 class="text-xl font-bold text-center mb-4">Permohonan Izin Menikah (CPW)</h1>
</br>
        <p class="text-lg leading-relaxed">Bismillahirrohmanirrohim,</p>
        <p class="text-lg leading-relaxed">Astagfirullahal’adzim 3x</p>
        <p class="text-lg leading-relaxed">Asyhadualla illa ha illalah, wa asyhadu anna muhammadarrosulullah.</p>
        <p class="text-lg leading-relaxed"><?= $client->f_bride_fathercname; ?> dan <?= $client->f_bride_mothercname; ?> yang <?= $client->f_bride_cname; ?> cintai dan hormati, <?= $client->f_bride_cname; ?> bersyukur dan berterima
        kasih kepada Allah SWT karena telah diberikan limpahan perhatian, kasih
        sayang dan cinta kasih pada <?= $client->f_bride_cname; ?> tiada henti.</p>
        <p class="text-lg leading-relaxed text-justify"><?= $client->f_bride_cname; ?> menghaturkan permohonan maaf yang sedalam-dalamnya atas
        segala kehilafan dan kesalahan <?= $client->f_bride_cname; ?>, baik kata-kata maupun perbuatan
        yang menyakiti <?= $client->f_bride_fathercname; ?> dan <?= $client->f_bride_mothercname; ?>.</p>
        <p class="text-lg leading-relaxed text-justify">Hari ini <?= hari($client->wedding_date) ?>, <?= tgl_indo($client->wedding_date) ?>, <?= $client->f_bride_cname; ?> memohon izin dan memohon
        restu untuk dinikahkan dengan lelaki pilihan <?= $client->f_bride_cname; ?>, untuk menemani
        perjalanan panjang hidup <?= $client->f_bride_cname; ?> kelak.</p>
        <p class="text-lg leading-relaxed text-justify">Seorang laki-laki bernama <?= $client->m_bride_fname; ?>, yang Insha’Allah
        bisa menjadi imam yang bijak dan penuh kasih sayang.</p></br>

        <h1 class="text-xl font-bold text-center mb-4">Permohonan Izin Menikah (<?= $client->f_bride_fathercname; ?>)</h1>
</br>
        <p class="text-lg leading-relaxed text-justify">Putriku <?= $client->f_bride_cname; ?>, penyampaian izin pernikahanmu dan permohonan restumu
        sudah <?= $client->f_bride_fathercname; ?> restui dan <?= $client->f_bride_fathercname; ?> dengar dengan seksama.</p>
        <p class="text-lg leading-relaxed text-justify">Karena Insya Allah sebentar lagi <?= $client->f_bride_fathercname; ?> akan segera menikahkanmu
        dengan calon suamimu yang bernama <?= $client->m_bride_fname; ?>.</p>
        <p class="text-lg leading-relaxed text-justify">Teriring doa, semoga Allah meridhoi hajat pernikahan yang akan <?= $client->f_bride_fathercname; ?>
        langsungkan sebentar lagi. Hingga rumah tanggamu nanti senantiasa
        rukun, damai dan bahagia penuh rahmat dan keberkahan dari Allah SWT.</p>
        <p class="text-lg leading-relaxed">Aamiin aamiin Allahumma aamiin..</p>
    </br>

    <div class="w-2/5 mx-auto border-2 border-black p-4 text-center">
    <p class="text-xl leading-relaxed font-semibold text-justify">
        Saya terima nikah dan
        kawinnya <?= $client->f_bride_fname; ?> binti
        <?= $client->f_bride_fathername; ?> dengan
        mas kawin tersebut dibayar tunai
    </p>
</div>

        <div class="mt-6 flex justify-between no-print">
            <a href="<?= base_url('naskah/izin_menikah/pdf/' . $client->id_session); ?>" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
               Download PDF
            </a>
            <button onclick="window.print()" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Print
            </button>
        </div>
    </div>
</body>
</html>
