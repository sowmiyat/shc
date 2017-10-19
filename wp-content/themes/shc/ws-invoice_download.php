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

$url = site_url( 'ws-invoice-download/?id=' ).$invoice_id.'&download&year='.$year;
$content =   file_get_contents($url,0,null,null);

invoiceDownload($content, 'wsinvoice.pdf');





?>