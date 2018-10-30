<?php
require get_template_directory() . '/admin/creditdebit/class-creditdebit.php';

function load_credit_scripts() {
	wp_enqueue_script( 'credit-script', get_template_directory_uri() . '/admin/creditdebit/inc/creditdebit.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_credit_scripts' );

/*Ajax Functions*/
function create_creditdebit(){

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] 	= 0;
	$data['msg'] 		= 'Something Went Wrong Please Try Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$credit_table 			=  $wpdb->prefix.'shc_creditdebit';
	$payment_table_display 	=  $wpdb->prefix.'shc_creditdebit_details';

	$params = array();
	parse_str($_POST['data'], $params);
	if($params['customer_type'] == 'retail'){
		$payment_table 			=  $wpdb->prefix.'shc_payment';	
	} else {
		$payment_table 			=  $wpdb->prefix.'shc_ws_payment';
	}
	$credit_data = array(
		'date' 			=> $params['creditdebit_date'],
		'customer_id' 	=> $params['creditdebit_cus_id'],
		'customer_name' => $params['creditdebit_customer'],
		'customer_type' => $params['customer_type'],
		'description' 	=> $params['description'],
		'due_amount' 	=> $params['total_due'],
		'to_pay_amt' 	=> $params['to_pay_amt'],
		);

	$wpdb->insert($credit_table, $credit_data);
	$create_id 			= $wpdb->insert_id;
	$lot_add_update 	=  array('created_by'=>$current_nice_name);
	$wpdb->update($credit_table, $lot_add_update, array('id' => $create_id));


//Insert Payment Type in credit Debit table

	foreach ($params['payment_detail'] as $value) {
		if(isset($value['payment_type'])){			
			$payment_data = 	array(
				'cd_id' 			=> $create_id,
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $params['creditdebit_cus_id'],
				'payment_type'		=> $value['payment_type'],
				'amount'			=> $value['payment_amount'],
							);
			$wpdb->insert($payment_table_display, $payment_data);
       }
	}


//Insert Payment Type
	foreach ($params['duepayAmount'] as $key => $value) {
			
		if($params['duepayAmount']!= 0){
			
				$insert_detail = array(
				'reference_id' 		=> $create_id,
				'reference_screen' 	=> 'due_screen',
				//'uniquename'        => $params['duepayUniquename'][$key]['due'],
				'sale_id' 			=> $params['dueId'][$key]['due'],
				'search_id' 		=> $params['dueInvid'][$key]['due'],
				'year' 				=> $params['dueYear'][$key]['due'],
				'customer_id' 		=> $params['creditdebit_cus_id'],
				'amount' 			=> $params['duepayAmount'][$key]['due'],
				'due_amount' 		=> $params['dueDueAmount'][$key]['due'],
				'payment_type' 		=> $params['duePaytype'][$key]['due'],
				'payment_date'      => date('Y-m-d'),
				);
				
		}
		$wpdb->insert($payment_table, $insert_detail);
	}

	if($wpdb->insert_id) {
		$data['success'] = 1;
		$data['msg'] 	= 'Notes Created!';
		
		$data['redirect'] = network_admin_url( 'admin.php?page=credit_debit' );
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_create_creditdebit', 'create_creditdebit' );
add_action( 'wp_ajax_nopriv_create_creditdebit', 'create_creditdebit' );



function update_creditdebit(){
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] 	= 0;
	$data['msg'] 	= 'Product Not Exist Please Try Again!';
	$data['redirect'] 	= 0;
	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);

	$creditdebit_id 		= $params['creditdebit_id'];
	$credit_table 			= $wpdb->prefix.'shc_creditdebit';
	$payment_table_display 	= $wpdb->prefix.'shc_creditdebit_details';

	
	if($params['customer_type'] == 'retail'){
		$payment_table 			=  $wpdb->prefix.'shc_payment';	
	} else {
		$payment_table 			=  $wpdb->prefix.'shc_ws_payment';
	}
	$query = "SELECT * from ${credit_table} WHERE id = ${creditdebit_id} and date ='${creditdebit_date}' and customer_type = ${customer_type} and customer_id = ${customer_id} and  description = '${description}' and active='1'";
	
	if($creditdebit_id != '') {
		$credit_data = array(
			'date' 			=> $params['creditdebit_date'],
			'customer_name' => $params['creditdebit_customer'],
			'customer_id' 	=> $params['creditdebit_cus_id'],
			'customer_type' => $params['customer_type'],
			'due_amount' 	=> $params['total_due'],
			'description' 	=> $params['description'],
			'to_pay_amt' 	=> $params['to_pay_amt'],
			'modified_by' 	=> $current_nice_name
		);

		$wpdb->update($credit_table, $credit_data, array('id' => $creditdebit_id));
		$wpdb->update($payment_table_display, array('active' => 0), array('cd_id' => $creditdebit_id));	

		foreach ($params['payment_detail'] as $value) {
			if(isset($value['payment_type'])){
				$payment_data = 	array(
					'cd_id'  			=> $creditdebit_id,
					'payment_details'	=> '',
					'payment_date'		=> date('Y-m-d'),
					'customer_id'		=> $params['creditdebit_cus_id'],
					'payment_type'		=> $value['payment_type'],
					'amount'			=> $value['payment_amount'],
					);
				$wpdb->insert($payment_table_display, $payment_data);
	       }
		}

		$wpdb->update($payment_table, array('active' => 0), array('reference_id' => $creditdebit_id,'reference_screen' => 'due_screen'));	
		foreach ($params['duepayAmount'] as $key => $value) {

			if($params['duepayAmount']!= 0 && $params['duepayAmount']!= '0.00'){
				$insert_detail = array(
				'reference_id' 		=> $creditdebit_id,
				'reference_screen' 	=> 'due_screen',
				//'uniquename'        => $params['duepayUniquename'][$key]['due'],
				'sale_id' 			=> $params['dueId'][$key]['due'],
				'search_id' 		=> $params['dueInvid'][$key]['due'],
				'year' 				=> $params['dueYear'][$key]['due'],
				'customer_id' 		=> $params['creditdebit_cus_id'],
				'amount' 			=> $params['duepayAmount'][$key]['due'],
				'due_amount' 		=> $params['dueDueAmount'][$key]['due'],
				'payment_type' 		=> $params['duePaytype'][$key]['due'],
				'payment_date'      => date('Y-m-d'),
				);					
			}
			$wpdb->insert($payment_table, $insert_detail);
		}

		$data['success'] = 1;
		$data['msg'] 	= 'Notes Updated!';
		$data['redirect'] = network_admin_url( 'admin.php?page=credit_debit' );	
	}
	

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_creditdebit', 'update_creditdebit' );
add_action( 'wp_ajax_nopriv_update_creditdebit', 'update_creditdebit' );


function get_creditdebit($credit_id = 0) {
    global $wpdb;
    $credit_tab 			= $wpdb->prefix.'shc_creditdebit';
    $credit_tab_details 	= $wpdb->prefix.'shc_creditdebit_details';
    $credit_payment 		= $wpdb->prefix.'shc_payment';
    $query 					= "SELECT * FROM ${credit_tab} WHERE id = ${credit_id} and active = 1";
    $data['main_tab'] 		= $wpdb->get_row($query);
    $query1 				= "SELECT * FROM ${credit_tab_details} WHERE cd_id = ${credit_id} and active = 1";
    $data['sub_tab'] 		= $wpdb->get_results($query1);
    $query2 				= "SELECT * FROM ${credit_payment} WHERE reference_id = ${credit_id} and reference_screen='due_screen' and active = 1";
    $data['payment_tab'] 	= $wpdb->get_results($query2);
    return $data;
}
function get_Wscreditdebit($credit_id = 0) {
    global $wpdb;
    $credit_tab 			= $wpdb->prefix.'shc_creditdebit';
    $credit_tab_details 	= $wpdb->prefix.'shc_creditdebit_details';
    $credit_payment 		= $wpdb->prefix.'shc_ws_payment';
    $query 					= "SELECT * FROM ${credit_tab} WHERE id = ${credit_id} and active = 1";
    $data['main_tab'] 		= $wpdb->get_row($query);
    $query1 				= "SELECT * FROM ${credit_tab_details} WHERE cd_id = ${credit_id} and active = 1";
    $data['sub_tab'] 		= $wpdb->get_results($query1);
    $query2 				= "SELECT * FROM ${credit_payment} WHERE reference_id = ${credit_id} and reference_screen='due_screen' and active = 1";
    $data['payment_tab'] 	= $wpdb->get_results($query2);
    return $data;
}


function creditdebit_filter() {
	$creditdebit = new creditdebit();
	include( get_template_directory().'/admin/creditdebit/ajax_loading/creditdebit_list.php' );
	die();	
}
add_action( 'wp_ajax_creditdebit_filter', 'creditdebit_filter' );
add_action( 'wp_ajax_nopriv_creditdebit_filter', 'creditdebit_filter' );



function get_creditdebit_cus() { 

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$search = $_POST['search_key'];
	$type = $_POST['customer_type'];
	if($type == 'ws'){
		$table =  $wpdb->prefix.'shc_wholesale_customer';
	    $customPagHTML      = "";
	    $query              = "SELECT * FROM ${table} WHERE active = 1 AND ( company_name LIKE '%${search}%' OR customer_name LIKE '%${search}%' OR mobile LIKE '${search}%')";	
	}
	else{
		$table =  $wpdb->prefix.'shc_customers';
	    $customPagHTML      = "";
	    $query              = "SELECT * FROM ${table} WHERE active = 1 AND (  name LIKE '%${search}%' OR mobile LIKE '${search}%')";	
	}
	$data['result'] = $wpdb->get_results($query);
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_get_creditdebit_cus', 'get_creditdebit_cus' );
add_action( 'wp_ajax_nopriv_get_creditdebit_cus', 'get_creditdebit_cus' );

