<?php
/**
 * Template Name: Report Download
 *
 * @package WordPress
 * @subpackage SHC
 */



$bill_form = isset($_GET['bill_form']) ? $_GET['bill_form'] : '';
$bill_to = isset($_GET['bill_to']) ? $_GET['bill_to'] : '';
$slap = isset($_GET['slap']) ? $_GET['slap'] : '';

$url = site_url( 'report-print/?bill_form=' ).$bill_form.'&bill_to='.$bill_to.'&slap='.$slap;

$content =   file_get_contents($url,0,null,null);
invoiceDownload($content, 'report.pdf');

 