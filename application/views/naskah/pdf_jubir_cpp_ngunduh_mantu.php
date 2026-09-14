<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sambutan Ngunduh Mantu - Pihak Pengantin Pria (Jubir CPP)</title>
    <style>
        p {
    font-size: 20px; /* Ukuran teks lebih besar dari standar (16px) */
    line-height: 1; /* Agar lebih nyaman dibaca */
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
    <h2 style="text-align: center;">Sambutan Ngunduh Mantu dari Pihak Pengantin Pria</h2><br>
        <p>Bissmillahirrahmaanirrahim.</p>
        <p>Assalamualaikum warahmatullahi wabarakatuh.</p>
        <p class="text-justify indent">Puji syukur kehadirat Allah SWT, pada hari yang berbahagia ini kita dapat
        berkumpul merayakan pernikahan putra-putri kami tercinta <strong><?= $client->m_bride_fname; ?></strong> dan
        <strong><?= $client->f_bride_fname; ?></strong> yang telah dilangsungkan akad nikahnya pada hari
        <strong><?= format_tanggal_acara($client->wedding_date); ?> di <?= $client->location; ?></strong>. Kami atas nama
        keluarga besar <strong>Bapak <?= $client->m_bride_fathername; ?></strong> dan
        <strong>Ibu <?= $client->m_bride_mothername; ?></strong> mengucapkan selamat datang dan terima kasih yang
        sebesar-besarnya kepada seluruh keluarga besar <strong>Bapak <?= $client->f_bride_fathername; ?></strong> dan
        <strong>Ibu <?= $client->f_bride_mothername; ?></strong> beserta rombongan atas kedatangan dan restunya.</p>
        <p class="text-justify indent">Semoga pernikahan ini menjadi awal dari kehidupan baru yang penuh berkah,
        cinta, dan kebahagiaan hingga akhir hayat mereka berdua. Semoga Allah SWT menjadikan rumah tangga mereka
        keluarga yang sakinah, mawaddah, warahmah, keluarga yang diidam-idamkan oleh kedua mempelai, dijauhkan dari
        segala cobaan, dan mereka mampu menghadapi segala cobaan juga tantangan.</p>
        <p class="text-justify indent">Sebagai penutup, izinkan kami membacakan sedikit pantun untuk mengiringi
        kebahagiaan kita hari ini.</p>
        <p style="font-style: italic;">
            Mentari pagi bersinar cerah<br>
            Burung berkicau sambut mentari<br>
            Dua hati kini telah berserah<br>
            Semoga langgeng hingga akhir nanti
        </p>
        <p>Burung Irian burung cindrawasih, cukup sekian dan terima kasih,</p>
        <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
</body>
</html>
