<?php
/**
 * Template Name: Accountant Download
 *
 * @package WordPress
 * @subpackage SHC
 */



$bill_form = isset($_GET['bill_form']) ? $_GET['bill_form'] : '';
$bill_to = isset($_GET['bill_to']) ? $_GET['bill_to'] : '';

$url = site_url( 'acc-print/?bill_form=' ).$bill_form.'&bill_to='.$bill_to;

$content =   file_get_contents($url,0,null,null);
invoiceDownload($content, 'accreport.pdf');

 