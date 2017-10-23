<?php
require get_template_directory() . '/admin/customer/class-customer.php';

function load_customer_scripts() {
	wp_enqueue_script( 'customer-script', get_template_directory_uri() . '/admin/customer/inc/js/customer.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_customer_scripts' );


function get_customer($customer_id = 0) {
    global $wpdb;
    $customer_table =  $wpdb->prefix.'shc_customers';
    $query = "SELECT * FROM ${customer_table} WHERE id = ${customer_id}";
    return $wpdb->get_row($query);
}

function get_wholesale_customer($customer_id = 0) {
    global $wpdb;
    $customer_table =  $wpdb->prefix.'shc_wholesale_customer';
    $query = "SELECT * FROM ${customer_table} WHERE id = ${customer_id}";
    return $wpdb->get_row($query);
}




function create_customer() {
	$data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong! Please Try Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	unset($params['action']);

	$customer_table = $wpdb->prefix. 'shc_customers';
	$wpdb->insert($customer_table, $params);

	if($wpdb->insert_id) {
		$data['success'] = 1;
		$data['msg'] 	= 'Customer Added!';
		$data['redirect'] = network_admin_url( 'admin.php?page=customer_list' );
		
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_create_customer', 'create_customer' );
add_action( 'wp_ajax_nopriv_create_customer', 'create_customer' );



function update_customer() {
	$data['success'] 	= 0;
	$data['msg'] = 'Invalid Customer Please Check Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$customer_id = $params['customer_id'];

	unset($params['action']);
	unset($params['customer_id']);

	if(get_customer($customer_id)) {
		$customer_table = $wpdb->prefix. 'shc_customers';
		$wpdb->update($customer_table, $params, array('id' => $customer_id));
		$data['success'] = 1;
		$data['msg'] = 'Customer Detail Updated!';
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_customer', 'update_customer' );
add_action( 'wp_ajax_nopriv_update_customer', 'update_customer' );

function create_wholesale_customer() {
	$data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong! Please Try Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	unset($params['action']);

	$customer_table = $wpdb->prefix. 'shc_wholesale_customer';
	$wpdb->insert($customer_table, $params);

	if($wpdb->insert_id) {
		$data['success'] = 1;
		$data['msg'] 	= 'Customer Added!';
		$data['redirect'] = network_admin_url( 'admin.php?page=wholesale_customer' );
		
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_create_wholesale_customer', 'create_wholesale_customer' );
add_action( 'wp_ajax_nopriv_create_wholesale_customer', 'create_wholesale_customer' );

function update_wholesale_customer() {
	$data['success'] 	= 0;
	$data['msg'] = 'Invalid Customer Please Check Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$customer_id = $params['customer_id'];

	unset($params['action']);
	unset($params['customer_id']);

	if(get_customer($customer_id)) {
		$customer_table = $wpdb->prefix. 'shc_wholesale_customer';
		$wpdb->update($customer_table, $params, array('id' => $customer_id));
		$data['success'] = 1;
		$data['msg'] = 'Customer Detail Updated!';
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_wholesale_customer', 'update_wholesale_customer' );
add_action( 'wp_ajax_nopriv_update_wholesale_customer', 'update_wholesale_customer' );



function customer_filter() {
	$customer = new Customer();
	include( get_template_directory().'/admin/customer/ajax_loading/customer-list.php' );
	die();
}
add_action( 'wp_ajax_customer_filter', 'customer_filter' );
add_action( 'wp_ajax_nopriv_customer_filter', 'customer_filter' );

function wholesale_customer_filter() {
	$customer = new Customer();
	include( get_template_directory().'/admin/customer/ajax_loading/wholesale-customer-list.php' );
	die();
}
add_action( 'wp_ajax_wholesale_customer_filter', 'wholesale_customer_filter' );
add_action( 'wp_ajax_nopriv_wholesale_customer_filter', 'wholesale_customer_filter' );

function check_unique_mobile() {

	global $wpdb;
	$mobile 		= $_POST['mobile'];
    $lot_table 		=  $wpdb->prefix.'shc_customers';
    $query 			=  $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ${lot_table} WHERE mobile ='$mobile' and active=1") );

	echo json_encode($query);
	die();




}
add_action( 'wp_ajax_check_unique_mobile', 'check_unique_mobile' );
add_action( 'wp_ajax_nopriv_check_unique_mobile', 'check_unique_mobile' );


function check_unique_mobile_wholesale() {
	global $wpdb;
	$mobile 		= $_POST['mobile'];
    $wholesale_customer 		=  $wpdb->prefix.'shc_wholesale_customer';
    $query = "SELECT mobile FROM ${wholesale_customer} WHERE mobile ='$mobile' and active=1";
    $result 			=  $wpdb->get_row( $query );
    if($result) {
    	$data = 1;
    } else {
    	$data = 0;
    }
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_check_unique_mobile_wholesale', 'check_unique_mobile_wholesale' );
add_action( 'wp_ajax_nopriv_check_unique_mobile_wholesale', 'check_unique_mobile_wholesale' );