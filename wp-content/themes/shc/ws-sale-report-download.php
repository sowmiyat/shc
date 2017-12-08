<?php
/**
 * Template Name: WholeSale Report Download
 *
 * @package WordPress
 * @subpackage SHC
 */


$inv_id = isset($_GET['inv_id']) ? $_GET['inv_id'] : '';
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';
$bill_from = isset($_GET['bill_from']) ? $_GET['bill_from'] : '';
$bill_to = isset($_GET['bill_to']) ? $_GET['bill_to'] : '';


$url = site_url( 'ws-sale-report-print/?bill_from=' ).$bill_from.'&bill_to='.$bill_to.'&name='.$name.'&inv_id='.$inv_id.'&order_id='.$order_id.'&mobile='.$mobile;

$content =   file_get_contents($url,0,null,null);


$mpdf = new mPDF(); 
$mpdf->SetDisplayMode('fullpage');
//$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;
 

 