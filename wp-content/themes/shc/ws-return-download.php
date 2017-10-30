<?php
/**
 * Template Name: Ws Goods Return Download 
 *
 * This is the template that displays full width page without sidebar
 *
 * 
 */

$return_id = isset($_GET['id']) ? $_GET['id'] : 0;
$url = site_url( 'ws-goods-return-download-page/?id=' ).$return_id;
$content =   file_get_contents($url,0,null,null);


$mpdf = new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;




?>