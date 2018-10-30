<?php
/**
 * Template Name: WholeSale Report Download
 *
 * @package WordPress
 * @subpackage SHC
 */


$inv_id = isset($_GET['inv_id']) ? $_GET['inv_id'] : '';

$cus_name = isset($_GET['cus_name']) ? $_GET['cus_name'] : '';
$mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$bill_from = isset($_GET['bill_from']) ? $_GET['bill_from'] : '';
$bill_to = isset($_GET['bill_to']) ? $_GET['bill_to'] : '';


$url = site_url( 'ws-sale-report-print/?bill_from=' ).$bill_from.'&bill_to='.$bill_to.'&cus_name='.$cus_name.'&inv_id='.$inv_id.'&mobile='.$mobile;

$content =   file_get_contents($url,0,null,null);


$mpdf = new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
 

 