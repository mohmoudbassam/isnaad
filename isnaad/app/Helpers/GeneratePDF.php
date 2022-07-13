<?php
namespace App\Helpers;
use Dompdf\Dompdf;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Pages;
use Mpdf\Mpdf;

trait GeneratePDF
{
    public function CreatePDF($file,$track,$filename){
        $pdffilename = 'ups-'.$track.'.pdf';
        $mpdf=new Mpdf(['mode' => 'utf-8','orientation' => 'P', 'format' => 'A6']);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML("
        <img src='$file'>");
        $mpdf->Output(getcwd().'/ups_labels/'.$pdffilename,\Mpdf\Output\Destination::FILE);
        $file1 =url('/ups_labels'. "/".$pdffilename);
//        unlink("ups_labels/".$filename);
        return $file1;
    }
}
