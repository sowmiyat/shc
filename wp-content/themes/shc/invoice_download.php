<?php
/**
 * Template Name: Invoice Download
 *
 * This is the template that displays full width page without sidebar
 *
 * 
 */

$invoice_id = isset($_GET['id']) ? $_GET['id'] : 0;
$year = isset($_GET['year']) ? $_GET['year'] : 0;
$url = site_url( 'download-page/?id=' ).$invoice_id.'&year='.$year;
$content =   file_get_contents($url,0,null,null);
invoiceDownload($content, 'invoice.pdf');





?>