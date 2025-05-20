<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naskah Jubir CPW</title>
    <link rel="icon" href="<?php echo base_url()?>assets/backend/mb.png" type="image/x-icon">
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
        <h1 class="text-xl font-bold text-center mb-4">Penerimaan Calon Pengantin Pria (Jubir CPW)</h1>
</br>
        <p class="text-lg">Bismillahirrohmanirrohim,</p>
        <p class="text-lg">Assalamu’alaikum wr.wb</p>
        <p class="indent text-lg text-justify">Yang kami hormati para sesepuh, tokoh agama, tokoh masyarakat, wabil khusus
        yang terhormat bapak dan ibu serta tamu undangan sekalian yang kami muliakan.</p>
        <p class="indent text-lg text-justify">Pertama-tama marilah kita panjatkan puji syukur kehadirat Allah swt. Bahwa pada
        kesempatan yang berbahagia ini, kita semua masih diberikan rahmat, hidayah, serta
        nikmat sehat, sehingga kita semua dapat bertemu dan berkumpul dalam rangka
        menghadiri acara akad nikah <strong><?= $client->f_bride_fname; ?></strong> dan <strong><?= $client->m_bride_fname; ?></strong>.</p>
        <p class="indent text-lg text-justify">Saya mewakili keluarga menerima kehadiran keluarga besar calon mempelai pria
        dan menyatakan dengan ikhlas, kami dapat menerima.</p>
        <p class="indent text-lg text-justify">Pada hari ini untuk dinikahkan dengan putri bapak <strong><?= $client->f_bride_fathername; ?>.</strong> dan ibu <strong><?= $client->f_bride_mothername; ?>.</strong>
        yang bernama <strong><?= $client->f_bride_fname; ?></strong>, semoga semua rencana kita akan mendapat petunjuk dan
        bimbingan dari Allah SWT. Amiin...</p>
        <p class="indent text-lg text-justify">Dan berbagai hantaran yang telah disampaikan, kami menerima dengan senang
        hati dengan ucapan Alhamdulilah. Semoga mendapatkan balasan yang berlimpah dari
        Allah SWT. Aamiin....</p>
        <p class="indent text-lg text-justify">Itulah yang dapat kami sampaikan, kami akhiri, Billahi taufik wal hidayah</p>
        <p class="text-lg">Wassalamu’alaikum. Wr.Wb</p>

        <div class="mt-6 flex justify-between items-center no-print">
            <a href="<?= base_url('naskah/jubir_cpw/pdf/' . $client->id_session); ?>" 
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
