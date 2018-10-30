<?php
/**
 * Template Name: Invoice Download
 *
 * This is the template that displays full width page without sidebar
 *
 * 
 */

$invoice_id = isset($_GET['id']) ? $_GET['id'] : 0;
$cur_year = isset($_GET['cur_year']) ? $_GET['cur_year'] : 0;
$url = site_url( 'download-page/?id=' ).$invoice_id.'&cur_year='.$cur_year;
$content =   file_get_contents($url,0,null,null);


$mpdf = new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;




?>