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
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong! Please Try Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	unset($params['action']);
	unset($params['form_submit_prevent']);

	$customer_table = $wpdb->prefix. 'shc_customers';
	$wpdb->insert($customer_table, $params);
	$customer_id = $wpdb->insert_id;
	$wpdb->update($customer_table, array('created_by' => $current_nice_name ), array('id' => $customer_id));

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

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;



	$data['success'] 	= 0;
	$data['msg'] = 'Invalid Customer Please Check Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$customer_table = $wpdb->prefix. 'shc_customers';
	$params = array();
	parse_str($_POST['data'], $params);
	$customer_id 		= $params['customer_id'];
	$name 				= $params['name'];
	$mobile 			= $params['mobile'];
	$secondary_mobile 	= $params['secondary_mobile'];
	$landline 			= $params['landline'];
	$address 			= $params['address'];


	unset($params['action']);
	unset($params['customer_id']);
	unset($params['form_submit_prevent']);

	$query = "SELECT * from ${customer_table} WHERE name = '${name}' and mobile ='${mobile}' and secondary_mobile ='${secondary_mobile}' and address ='${address}' and  id = ${customer_id} and active='1'";
	if($wpdb->get_row($query)){
		$data['success'] = 0;
		$data['msg'] 	= 'No Updates!';
		$data['redirect'] = network_admin_url( 'admin.php?page=new_customer&id='.$customer_id );
		
	} else {
		if(get_customer($customer_id)) {

			$wpdb->update($customer_table, $params, array('id' => $customer_id));
			$wpdb->update($customer_table, array('modified_by' => $current_nice_name ), array('id' => $customer_id));
			$data['success'] = 1;
			$data['msg'] = 'Customer Detail Updated!';
			$data['redirect'] = network_admin_url( 'admin.php?page=customer_list' );
		}
	}
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_customer', 'update_customer' );
add_action( 'wp_ajax_nopriv_update_customer', 'update_customer' );

function create_wholesale_customer() {
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong! Please Try Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	unset($params['action']);
	unset($params['form_submit_prevent']);

	$customer_table = $wpdb->prefix. 'shc_wholesale_customer';
	$wpdb->insert($customer_table, $params);
	$customer_id = $wpdb->insert_id;
	$wpdb->update($customer_table, array('created_by' => $current_nice_name ), array('id' => $customer_id));

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

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;


	$data['success'] 	= 0;
	$data['msg'] = 'Invalid Customer Please Check Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$customer_table = $wpdb->prefix. 'shc_wholesale_customer';
	$customer_id = $params['customer_id'];
	$customer_name 		= $params['customer_name'];
	$company_name 		= $params['company_name'];
	$mobile 			= $params['mobile'];
	$secondary_mobile 	= $params['secondary_mobile'];
	$landline 			= $params['landline'];
	$address 			= $params['address'];
	$gst_number 		= $params['gst_number'];
	unset($params['action']);
	unset($params['customer_id']);
	unset($params['form_submit_prevent']);

	$query = "SELECT * from ${customer_table} WHERE customer_name = '${customer_name}' and company_name ='${company_name}' and mobile ='${mobile}' and secondary_mobile ='${secondary_mobile}' and address ='${address}' and gst_number = '${gst_number}' and  id = ${customer_id} and active='1'";
	if($wpdb->get_row($query)){
		$data['success'] = 0;
		$data['msg'] 	= 'No Updates!';
		$data['redirect'] = network_admin_url( 'admin.php?page=new_wholesale_customer&id='.$customer_id );
		
	} else {
		if(get_wholesale_customer($customer_id)) {
			
			$wpdb->update($customer_table, $params, array('id' => $customer_id));
			$wpdb->update($customer_table, array('modified_by' => $current_nice_name ), array('id' => $customer_id));
			$data['success'] = 1;
			$data['msg'] = 'Customer Detail Updated!';
			$data['redirect'] = network_admin_url( 'admin.php?page=wholesale_customer' );
		}
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
	$mobile 				= $_POST['mobile'];
	$customer_id    		= $_POST['customer_id'];
    $retail_customer 		=  $wpdb->prefix.'shc_customers';   
    $query = "SELECT mobile FROM ${retail_customer} WHERE mobile ='$mobile' and id != '$customer_id' and active=1";
    $result 	=  $wpdb->get_row( $query );
    if($result) {
    	$data = 1;
    } else {
    	$data = 0;
    }
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_check_unique_mobile', 'check_unique_mobile' );
add_action( 'wp_ajax_nopriv_check_unique_mobile', 'check_unique_mobile' );
function balance_paid(){
	global $wpdb;
	$customer_id 		= $_POST['id'];
	$amt 				= $_POST['amt'];
    $cd_table 			=  $wpdb->prefix.'shc_cd_notes';   
    if($_POST['id'] !=''){
		$insert_data = array(
		'customer_id' => $customer_id,
		'master_key' => 'return_biling',
		'key_value' => 'debit',
		'key_amount' => $amt,
		);
		$wpdb->insert($cd_table,$insert_data);
		$data['success'] = 1;
		$data['msg'] = 'successfully paid!!!';
		$data['redirect'] = network_admin_url( 'admin.php?page=customer_list' );
		
	}
	echo json_encode($data);
	die();	
}
add_action( 'wp_ajax_balance_paid', 'balance_paid' );
add_action( 'wp_ajax_nopriv_balance_paid', 'balance_paid' );

function ws_balance_paid(){
	global $wpdb;
	$customer_id 		= $_POST['id'];
	$amt 				= $_POST['amt'];
    $cd_table 			=  $wpdb->prefix.'shc_ws_cd_notes';   
    if($_POST['id'] !=''){
		$insert_data = array(
		'customer_id' => $customer_id,
		'master_key' => 'return_biling',
		'key_value' => 'debit',
		'key_amount' => $amt,
		);
		$wpdb->insert($cd_table,$insert_data);
		$data['success'] = 1;
		$data['msg'] = 'successfully paid!!!';
		$data['redirect'] = network_admin_url( 'admin.php?page=wholesale_customer' );
		
	}
	echo json_encode($data);
	die();	
}
add_action( 'wp_ajax_ws_balance_paid', 'ws_balance_paid' );
add_action( 'wp_ajax_nopriv_ws_balance_paid', 'ws_balance_paid' );
function check_unique_mobile_wholesale() {
	global $wpdb;
	$mobile 		= $_POST['mobile'];
	$customer_id    = $_POST['customer_id'];
    $wholesale_customer 		=  $wpdb->prefix.'shc_wholesale_customer';
    // if($customer_id == 0){
    // 	$query = "SELECT mobile FROM ${wholesale_customer} WHERE mobile ='$mobile' and active=1";
    // } else {
    //	$query = "SELECT mobile FROM ${wholesale_customer} WHERE mobile ='$mobile' and id != '$customer_id' and active=1";
    // }
    	if($mobile !== '') {
    		$query = "SELECT mobile FROM ${wholesale_customer} WHERE mobile ='$mobile' and id != '$customer_id' and active=1";
    	}

    
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