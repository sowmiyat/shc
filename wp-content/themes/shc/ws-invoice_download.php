<?php
/**
 * Template Name: Wholesale Invoice Download
 *
 * This is the template that displays full width page without sidebar
 *
 * 
 */

$invoice_id = isset($_GET['id']) ? $_GET['id'] : 0;
$year = isset($_GET['year']) ? $_GET['year'] : 0;
$url = site_url( 'ws-invoice/?id=' ).$invoice_id.'&year='.$year;

$content =   file_get_contents($url);


$mpdf = new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;






?>