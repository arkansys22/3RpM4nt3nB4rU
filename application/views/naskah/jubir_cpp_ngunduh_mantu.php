<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naskah Jubir CPP - Ngunduh Mantu</title>
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
        <h1 class="text-xl font-bold text-center mb-4">Sambutan Ngunduh Mantu dari Pihak Pengantin Pria</h1>
</br></br>
        <p class="text-lg">Bissmillahirrahmaanirrahim.</p>
        <p class="text-lg">Assalamualaikum warahmatullahi wabarakatuh.</p>
        <p class="indent text-lg text-justify">Puji syukur kehadirat Allah SWT, pada hari yang berbahagia ini kita dapat
        berkumpul merayakan pernikahan putra-putri kami tercinta <strong><?= $client->m_bride_fname; ?></strong> dan
        <strong><?= $client->f_bride_fname; ?></strong> yang telah dilangsungkan akad nikahnya pada hari
        <strong><?= format_tanggal_acara($client->wedding_date); ?> di <?= $client->location; ?></strong>. Kami atas nama
        keluarga besar <strong>Bapak <?= $client->m_bride_fathername; ?></strong> dan
        <strong>Ibu <?= $client->m_bride_mothername; ?></strong> mengucapkan selamat datang dan terima kasih yang
        sebesar-besarnya kepada seluruh keluarga besar <strong>Bapak <?= $client->f_bride_fathername; ?></strong> dan
        <strong>Ibu <?= $client->f_bride_mothername; ?></strong> beserta rombongan atas kedatangan dan restunya.</p>
        <p class="indent text-lg text-justify">Semoga pernikahan ini menjadi awal dari kehidupan baru yang penuh berkah,
        cinta, dan kebahagiaan hingga akhir hayat mereka berdua. Semoga Allah SWT menjadikan rumah tangga mereka
        keluarga yang sakinah, mawaddah, warahmah, keluarga yang diidam-idamkan oleh kedua mempelai, dijauhkan dari
        segala cobaan, dan mereka mampu menghadapi segala cobaan juga tantangan.</p>
        <p class="indent text-lg text-justify">Sebagai penutup, izinkan kami membacakan sedikit pantun untuk mengiringi
        kebahagiaan kita hari ini.</p>
        <p class="text-lg" style="font-style: italic;">
            Mentari pagi bersinar cerah<br>
            Burung berkicau sambut mentari<br>
            Dua hati kini telah berserah<br>
            Semoga langgeng hingga akhir nanti
        </p>
        <p class="text-lg">Burung Irian burung cindrawasih, cukup sekian dan terima kasih,</p>
        <p class="text-lg">Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>

        <div class="mt-6 flex justify-between no-print">
            <a href="<?= base_url('naskah/jubir_cpp/pdf/' . $client->id_session); ?>"
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
