<?php
/**
 * Template Name: Accountant Download
 *
 * @package WordPress
 * @subpackage SHC
 */



$bill_form = isset($_GET['bill_form']) ? $_GET['bill_form'] : '';
$bill_to = isset($_GET['bill_to']) ? $_GET['bill_to'] : '';
$slap = isset($_GET['slap']) ? $_GET['slap'] : '';

$url = site_url( 'acc-print/?bill_form=' ).$bill_form.'&bill_to='.$bill_to.'&slap='.$slap;

$content =   file_get_contents($url,0,null,null);


$mpdf = new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
 

 