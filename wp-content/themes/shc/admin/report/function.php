<?php

require get_template_directory() . '/admin/report/class-report.php';

function load_report_scripts() {
	wp_enqueue_script( 'billing-scriptss', get_template_directory_uri() . '/admin/report/inc/js/stock_report.js', array('jquery'), false, false );
	
}
add_action( 'admin_enqueue_scripts', 'load_report_scripts' );
function stock_report() {
	$report = new report();
	include( get_template_directory().'/admin/report/ajax_loading/stock-list.php' );
	die();	
}
add_action( 'wp_ajax_stock_report', 'stock_report' );
add_action( 'wp_ajax_nopriv_stock_report', 'stock_report' );


function stock_report_acc() {
	$report = new report();
	include( get_template_directory().'/admin/report/ajax_loading/stock-list-accountant.php' );
	die();	
}
add_action( 'wp_ajax_stock_report_acc', 'stock_report_acc' );
add_action( 'wp_ajax_nopriv_stock_report_acc', 'stock_report_acc' );



?>

