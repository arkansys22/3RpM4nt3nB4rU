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

    public function createPDF_P($html, $filename = '', $download = true) {
        // Setup Dompdf Options
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);

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
