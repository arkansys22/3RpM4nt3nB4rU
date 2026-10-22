<?php
require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf {
    public function createPDF_L($html, $filename = '', $download = true) {
        // Setup Dompdf Options
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);

        // Inisialisasi Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF
        if ($download) {
            $dompdf->stream($filename . ".pdf", ["Attachment" => 1]);
        } else {
            return $dompdf->output();
        }
    }

    // $subset_font: hanya sematkan huruf yang terpakai ke PDF. Wajib untuk font besar
    // (mis. Noto Sans SC untuk huruf Mandarin) -- tanpa ini seluruh file font ikut tertanam.
    public function createPDF_P($html, $filename = '', $download = true, $subset_font = false) {
        // Setup Dompdf Options
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);
        $options->setIsFontSubsettingEnabled($subset_font);

        // Inisialisasi Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();

        // Output PDF
        if ($download) {
            $dompdf->stream($filename . ".pdf", ["Attachment" => 1]);
        } else {
            return $dompdf->output();
        }
    }
}
