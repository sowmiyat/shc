<?php
require get_template_directory() . '/admin/stocks/class-stocks.php';

function load_stock_scripts() {
	wp_enqueue_script( 'stock-script', get_template_directory_uri() . '/admin/stocks/inc/js/stock.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_stock_scripts' );


function get_stock($stock_id = 0) {	
	global $wpdb;
	$stock_table = $wpdb->prefix.'shc_stock';
	$lot_table = $wpdb->prefix. 'shc_lots';

	$query = "SELECT s.*, l.lot_no, l.brand_name, l.product_name, l.selling_price FROM {$stock_table} as s JOIN ${lot_table} as l ON s.lot_number = l.id WHERE s.id = ${stock_id} AND s.active = 1";
	return $wpdb->get_row( $query );
}


/*Ajax Functions*/
function search_lot() {

	$data['success'] = 0;
	global $wpdb;
	$params = array();
	$lot_table = $wpdb->prefix. 'shc_lots';

	$search_term = $_POST['search_key'];

	$query = "SELECT * FROM {$lot_table} WHERE product_name like '%${search_term}%' AND active = 1";

	if( $data['result'] = $wpdb->get_results( $query, ARRAY_A ) ) {
		$data['success'] = 1;
	}
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_search_lot', 'search_lot' );
add_action( 'wp_ajax_nopriv_search_lot', 'search_lot' );



function add_stock(){

	/* $data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong! Please Try Again!';
	$data['redirect'] 	= 0; */

	global $wpdb;
	$stock_table = $wpdb->prefix. 'shc_stock';
	$lot_table = $wpdb->prefix. 'shc_lots';
	$params = array();
	parse_str($_POST['data'], $params);
	unset($params['action']);
	unset($params['pro_number']);

	$lot_id = $params['lot_number'];
	$stock_count = $params['stock_count'];

	if($lot_id != '') {

		$wpdb->insert($stock_table, $params);
		/* if($wpdb->insert_id) {
			$data['success'] = 1;
			$data['msg'] 	= 'Stock Added!';
			
		} */
		$data['redirect'] = network_admin_url( 'admin.php?page=list_stocks' );
		
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_add_stock', 'add_stock' );
add_action( 'wp_ajax_nopriv_add_stock', 'add_stock' );


function update_stock(){

	$data['success'] 	= 0;
	$data['msg'] = 'Invalid Stock Please check again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$stock_table = $wpdb->prefix. 'shc_stock';
	$params = array();
	parse_str($_POST['data'], $params);
	$stock_id = $params['stock_id'];

	unset($params['action']);
	unset($params['stock_id']);
	unset($params['pro_number']);

	$lot_id = $params['lot_number'];
	$stock_count = $params['stock_count'];

	if($lot_id != '' && get_stock($stock_id)) {
		$wpdb->update($stock_table, $params, array('id' => $stock_id));
		$data['success'] = 1;
		$data['msg'] = 'Stock Updated Successfully!';
		$data['redirect'] = network_admin_url( 'admin.php?page=list_stocks' );
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_stock', 'update_stock' );
add_action( 'wp_ajax_nopriv_update_stock', 'update_stock' );


function stock_filter() {
	$stocks = new Stocks();
	include( get_template_directory().'/admin/stocks/ajax_loading/stock-list.php' );
	die();	
}
add_action( 'wp_ajax_stock_filter', 'stock_filter' );
add_action( 'wp_ajax_nopriv_stock_filter', 'stock_filter' );

function stock_filter_total() {
	$stocks = new Stocks();
	include( get_template_directory().'/admin/stocks/ajax_loading/stock-list-total.php' );
	die();	
}
add_action( 'wp_ajax_stock_filter_total', 'stock_filter_total' );
add_action( 'wp_ajax_nopriv_stock_filter_total', 'stock_filter_total' );

function productCheck(){
	global $wpdb;
	$product_name 					= $_POST['productname'];
    $lot_table 						=  $wpdb->prefix.'shc_lots';
    $query = "SELECT product_name FROM ${lot_table} WHERE product_name ='$product_name' and active=1";

    $result 			=  $wpdb->get_row( $query );
    if($result) {
    	$data = 0;
    } else {
    	$data = 1;
    }
	echo json_encode($data);
	die();
}

add_action( 'wp_ajax_productCheck', 'productCheck' );
add_action( 'wp_ajax_nopriv_productCheck', 'productCheck' );


?>