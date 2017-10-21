<?php

require get_template_directory() . '/admin/billing/class-billing.php';

function load_billing_scripts() {
	wp_enqueue_script( 'billing-script', get_template_directory_uri() . '/admin/billing/inc/js/billing.js', array('jquery'), false, false );
	wp_enqueue_script( 'billing-script_retailer', get_template_directory_uri() . '/admin/billing/inc/js/retail_billing.js', array('jquery'), false, false );
	wp_localize_script( 'billing-script', 'bill_update', array( 'updateurl' => menu_page_url('new_billing',0)));
	wp_localize_script( 'billing-script', 'bill_updatews', array( 'updateurlws' => menu_page_url('ws_new_billing',0)));
	wp_localize_script( 'billing-script', 'bill_return_viewws', array( 'returnviewws' => menu_page_url('ws_return_items_view',0)));
	wp_localize_script( 'billing-script_retailer', 'bill_return_view', array( 'returnview' => menu_page_url('return_items_view',0)));
	wp_localize_script( 'billing-script', 'bill_invoice', array( 'invoiceurl' => menu_page_url('invoice',0)));
	wp_localize_script( 'billing-script', 'bill_invoicews', array( 'invoiceurlws' => menu_page_url('ws_invoice',0)));
	wp_localize_script( 'billing-script', 'bill_return_list', array( 'return_items' => menu_page_url('return_items_list',0)));
	wp_localize_script( 'billing-script', 'bill_return_listws', array( 'return_itemsws' => menu_page_url('ws_return_items_list',0)));
}
add_action( 'admin_enqueue_scripts', 'load_billing_scripts' );

function getBillData($inv_id = 0, $year = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		=  $wpdb->prefix.'shc_customers';
	$sale_table 			=  $wpdb->prefix.'shc_sale';
	$sale_detail_table 		= $wpdb->prefix.'shc_sale_detail';
	$lots_table 			= $wpdb->prefix.'shc_lots';


	$bill_query = "SELECT s.*,
( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as customer_name,
( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
( CASE WHEN c.secondary_mobile IS NULL THEN 'Nil' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN 'Nil' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1 AND dt.item_status = 'open'";
	
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;
}


function getBillDataws($inv_id = 0, $year = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		=  $wpdb->prefix.'shc_wholesale_customer';
	$sale_table 			=  $wpdb->prefix.'shc_ws_sale';
	$sale_detail_table 		= $wpdb->prefix.'shc_ws_sale_detail';
	$lots_table 			= $wpdb->prefix.'shc_lots';


	$bill_query = "SELECT s.*,
( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
( CASE WHEN c.gst_number IS NULL THEN 0 ELSE c.gst_number END ) as gst_number,
( CASE WHEN c.company_name IS NULL THEN 0 ELSE c.company_name END ) as company_name,
( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as customer_name,
( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
( CASE WHEN c.secondary_mobile IS NULL THEN 'Nil' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN 'Nil' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1 AND dt.item_status = 'open'";
	
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;
}

function generateInvoice() {
	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';
	global $wpdb;
	$table =  $wpdb->prefix.'shc_sale';

	$date = date("Y/m/d");
	$year = date("Y");
	$financial_year = getFinancialYear($date);

	
	
    $query  = "SELECT * FROM ${table} WHERE active = 1 AND order_id = 0 AND locked = 0 ";
    
    if( $inv_result = $wpdb->get_row($query) ) {
    	$data['invoice_id'] = $inv_result->id;
    	$data['inv_id'] = $inv_result->inv_id;

    } else {

    	$query_table = "SELECT inv_id,financial_year FROM ${table} WHERE active = 1 order by `id` DESC LIMIT 1";
    	$query_year = $wpdb->get_row($query_table);
    	$final_yr_table = $query_year->financial_year;
    	$inv_id = $query_year->inv_id;
    	if($financial_year == $final_yr_table) {
    		$data['inv_id'] = $inv_id + 1;
    	} else {
    		$data['inv_id'] = '1';
    	}

    	$wpdb->insert($table, array('active'=>1,'financial_year' =>$year,'inv_id'=>$data['inv_id'] ));
    	$data['invoice_id'] = $wpdb->insert_id;

    	
    }


    return $data;
}


function generateInvoicews() {
	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';
	global $wpdb;
	$table =  $wpdb->prefix.'shc_ws_sale';

	$date = date("Y/m/d");
	$year = date("Y");
	$financial_year = getFinancialYear($date);
	
	
    $query  = "SELECT * FROM ${table} WHERE active = 1 AND order_id = 0 AND locked = 0 ";
    
    if( $inv_result = $wpdb->get_row($query) ) {
    	$data['invoice_id'] = $inv_result->id;
    	$data['inv_id'] = $inv_result->inv_id;

    } else {

    	$query_table = "SELECT inv_id,financial_year FROM ${table} WHERE active = 1 order by `id` DESC LIMIT 1";
    	$query_year = $wpdb->get_row($query_table);
    	$final_yr_table = $query_year->financial_year;
    	$inv_id = $query_year->inv_id;
    	if($financial_year == $final_yr_table) {
    		$data['inv_id'] = $inv_id + 1;
    	} else {
    		$data['inv_id'] = '1';
    	}

    	$wpdb->insert($table, array('active'=>1,'financial_year' =>$year,'inv_id'=>$data['inv_id'] ));
    	$data['invoice_id'] = $wpdb->insert_id;

    	
    }


    return $data;
}



/*Ajax Functions*/
function get_lot_data() {
	
	$data['success'] = 0;
	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$id = $_POST['id'];

	$lot_table = $wpdb->prefix. 'shc_lots';
	$id = $_POST['id'];
	$search_term = $_POST['search_key'];
	$query = "SELECT * FROM {$lot_table} WHERE  product_name like '%${search_term}%' AND active = 1";
	
	if( $data['result'] = $wpdb->get_results( $query, ARRAY_A ) ) {
		$data['success'] = 1;
	}
	echo json_encode($data);
	die();

}
add_action( 'wp_ajax_get_lot_data', 'get_lot_data');
add_action( 'wp_ajax_nopriv_get_lot_data', 'get_lot_data');


function create_order() {
	$data['success'] = 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_sale';
	$sale_detail_table =  $wpdb->prefix.'shc_sale_detail';
	$customer_table = $wpdb->prefix. 'shc_customers';
	$params = array();

	parse_str($_POST['data'], $params);


	if( isset($params['invoice_id']) && $params['invoice_id'] != 0 && isValidInvoice( $params['invoice_id'] ) ) {
		$invoice_id = $params['invoice_id'];
		$wpdb->update($sale_table, array('locked' => 1), array('id' => $invoice_id));
		
	} else {
		$wpdb->insert($sale_table, array('locked' => 1));		
		$invoice_id = $wpdb->insert_id;
	}

	$today = date("dmY");
	$year = date("Y");
	$rand = strtoupper(substr(uniqid(sha1(time())),0,4));
	$order_id = $today . $rand;


	$data['invoice_id'] = $invoice_id;

	if($params['old_customer_id'] != '0')
	{
		$customer_id = $params['old_customer_id'];
		$customer_update = array(
		'name' 			=> $params['name'], 
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address']
		);
		$wpdb->update($customer_table, $customer_update,array('id' => $customer_id));

	}
	else {
		$customer_update = array(
		'name' 						=> $params['name'], 
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address']
		);
			
		$wpdb->insert($customer_table, $customer_update);

		$customer_id = $wpdb->insert_id;
	}

	if($params['ws_payment_pay_type'] == 'cash'){
		$payment_details = '';
		$payment_date = '';
	}
	else if($params['ws_payment_pay_type'] == 'card'){
		$payment_details = $params['card_number'];
		$payment_date = '';
	}
	else if($params['ws_payment_pay_type'] == 'cheque'){
		$payment_details = $params['cheque_number'];
		$payment_date = $params['cheque_date'];
	}

	else if($params['ws_payment_pay_type'] == 'credit'){
		$payment_details = '';
		$payment_date = '';
	}
	else {
		$payment_details = $params['internet_banking_details'];
		$payment_date = '';
	}

	$sale_update = array(
		'customer_id' 			=>  $customer_id, 
		'order_id' 				=> $order_id,
		'home_delivery_name' 		=> $params['delivery_name'],
		'home_delivery_mobile' 		=> $params['delivery_phone'],
		'home_delivery_address' 	=> $params['delivery_address'],
		'sub_total' 			=> $params['fsub_total'], 
		'discount' 				=> $params['retail_main_discount'],
		'discount_type'			=> $params['discount_per'],
		'paid_amount' 			=> $params['paid_amount'],
		'return_amt' 			=> $params['return_amt'],
		'payment_type'         	=> $params['ws_payment_pay_type'],
		'payment_details'       => $payment_details,
		'payment_date'       	=> $payment_date,
	);
	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));
	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['inv_id'] = $data1->inv_id;


	

	foreach ($params['customer_detail'] as $s_value) {
		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {

			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'sub_total' 			=> $s_value['subtotal'],
			);
			$wpdb->insert($sale_detail_table, $sale_detail_data);
			$data['success'] = 1;
			
		}
	}	

	echo json_encode($data);
	die();	
	
}
add_action( 'wp_ajax_create_order', 'create_order' );
add_action( 'wp_ajax_nopriv_create_order', 'create_order' );

function ws_create_order() {
	$data['success'] = 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_ws_sale';
	$sale_detail_table =  $wpdb->prefix.'shc_ws_sale_detail';
	$customer_table = $wpdb->prefix. 'shc_wholesale_customer';
	$params = array();

	parse_str($_POST['data'], $params);

	if( isset($params['invoice_id']) && $params['invoice_id'] != 0 && isValidInvoicews( $params['invoice_id'] ) ) {
		$invoice_id = $params['invoice_id'];
		$wpdb->update($sale_table, array('locked' => 1), array('id' => $invoice_id));
		
	} else {
		$wpdb->insert($sale_table, array('locked' => 1));		
		$invoice_id = $wpdb->insert_id;
	}

	$today = date("dmY");
	$year = date("Y");
	$rand = strtoupper(substr(uniqid(sha1(time())),0,4));
	$order_id = $today . $rand;

	$inv_txt = 'INV'.$invoice_id;
	$data['invoice_id'] = $invoice_id;

	if($params['ws_old_customer_id'] != '0')
	{
		$customer_id = $params['ws_old_customer_id'];
		$customer_update = array(
		'customer_name' 			=> $params['name'], 
		'company_name' 				=> $params['company'],
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address'], 
		'gst_number' 				=> $params['gst']
		);
		$wpdb->update($customer_table, $customer_update,array('id' => $customer_id));

	}
	else {
		$customer_update = array(
		'customer_name' 			=> $params['name'], 
		'company_name' 				=> $params['company'],
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address'], 
		'gst_number' 				=> $params['gst']
		);
			
		$wpdb->insert($customer_table, $customer_update);


		$customer_id = $wpdb->insert_id;
	}
	if($params['ws_payment_pay_type'] == 'cash'){ 
		$payment_details = '';
		$payment_date = '';
	}
	else if($params['ws_payment_pay_type'] == 'card'){
		$payment_details = $params['card_number'];
		$payment_date = '';
	}
	else if($params['ws_payment_pay_type'] == 'cheque'){
		$payment_details = $params['cheque_number'];
		$payment_date = $params['cheque_date'];
	}
	else if($params['ws_payment_pay_type'] == 'credit'){
		$payment_details = '';
		$payment_date = '';
	}
	else {
		$payment_details = $params['internet_banking_details'];
		$payment_date = '';
	}

	$sale_update = array(
		'customer_id' 				=> $customer_id, 
		'order_id' 					=> $order_id,
		'home_delivery_name' 		=> $params['ws_delivery_name'],
		'home_delivery_mobile' 		=> $params['ws_delivery_phone'],
		'home_delivery_address' 	=> $params['ws_delivery_address'],
		'sub_total' 				=> $params['ws_fsub_total'], 
		'discount' 					=> $params['ws_discount'],
		'paid_amount' 				=> $params['ws_paid_amount'],
		'return_amt' 				=> $params['ws_return_amt'],
		'payment_type'         		=> $params['ws_payment_pay_type'],
		'payment_details'       	=> $payment_details,
		'payment_date'       		=> $payment_date,
	);
	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));
	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['inv_id'] = $data1->inv_id;
	foreach ($params['customer_detail'] as $s_value) {
		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {

			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'sub_total' 			=> $s_value['subtotal'],
			);
			$wpdb->insert($sale_detail_table, $sale_detail_data);
			$data['success'] = 1;
			
		}
	}	

	echo json_encode($data);
	die();
	
}
add_action( 'wp_ajax_ws_create_order', 'ws_create_order' );
add_action( 'wp_ajax_nopriv_ws_create_order', 'ws_create_order' );


function update_order() {
	$data['success'] = 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_sale';
	$sale_detail_table =  $wpdb->prefix.'shc_sale_detail';
	$params = array();
	$data['year'] = date('Y');
	parse_str($_POST['data'], $params);
	$invoice_id = $params['invoice_id'];
	
	if($params['old_customer_id'] != '0')
	{
		$customer_id = $params['old_customer_id'];
		$customer_update = array(
		'name' 			=> $params['name'], 
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address']
		);
		$wpdb->update($customer_table, $customer_update,array('id' => $customer_id));

	}
	else {
		$customer_update = array(
		'name' 						=> $params['name'], 
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address']
		);
			
		$wpdb->insert($customer_table, $customer_update);

		$customer_id = $wpdb->insert_id;
	}


	$sale_update = array(
		'customer_id' 				=>  $customer_id, 
		'home_delivery_name' 		=> $params['delivery_name'],
		'home_delivery_mobile' 		=> $params['delivery_phone'],
		'home_delivery_address' 	=> $params['delivery_address'],
		'sub_total' 				=> $params['fsub_total'], 
		'discount' 					=> $params['discount'],
		'discount_type'				=> $params['discount_per'],
		'paid_amount' 				=> $params['paid_amount'],
		'return_amt' 				=> $params['return_amt'],
	);

	
	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));


	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['inv_id'] = $data1->inv_id;

	$data['invoice_id'] = $invoice_id;

	//Update active 0 to sale detail table
	$wpdb->update($sale_detail_table, array('active' => 0), array('sale_id' => $invoice_id));
	foreach ($params['customer_detail'] as $s_value) {
		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {


			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'sub_total' 			=> $s_value['subtotal'],
			);

			$wpdb->insert($sale_detail_table, $sale_detail_data);
		} else {
			if($s_value['id'] != '') {

				$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'sub_total' 			=> $s_value['subtotal'],
				'active' 				=> 1
				);

				$wpdb->update( $sale_detail_table, $sale_detail_data, array('id' => $s_value['sale_detail_id']) );
			}
		}
	}	

	$data['success'] = 1;

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_order', 'update_order' );
add_action( 'wp_ajax_nopriv_update_order', 'update_order' );




function ws_update_order() {
	$data['success'] = 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_ws_sale';
	$sale_detail_table =  $wpdb->prefix.'shc_ws_sale_detail';
	$params = array();
	$data['year'] = date('Y');
	parse_str($_POST['data'], $params);
	$invoice_id = $params['invoice_id'];

	if($params['ws_old_customer_id'] != '0')
	{
		$customer_id = $params['ws_old_customer_id'];
		$customer_update = array(
		'customer_name' 			=> $params['name'], 
		'company_name' 				=> $params['company'],
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address'], 
		'gst_number' 				=> $params['gst']
		);
		$wpdb->update($customer_table, $customer_update,array('id' => $customer_id));

	}
	else {
		$customer_update = array(
		'customer_name' 			=> $params['name'], 
		'company_name' 				=> $params['company'],
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address'], 
		'gst_number' 				=> $params['gst']
		);
			
		$wpdb->insert($customer_table, $customer_update);

		$customer_id = $wpdb->insert_id;
	}


	$sale_update = array(
		'customer_id' 				=>  $customer_id, 
		'home_delivery_name' 		=> $params['ws_delivery_name'],
		'home_delivery_mobile' 		=> $params['ws_delivery_phone'],
		'home_delivery_address' 	=> $params['ws_delivery_address'],
		'sub_total' 				=> $params['ws_fsub_total'], 
		'discount' 					=> $params['ws_discount'],
		'paid_amount' 				=> $params['ws_paid_amount'],
		'return_amt' 				=> $params['ws_return_amt'],
	);


	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));
	
	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['inv_id'] = $data1->inv_id;

	$data['invoice_id'] = $invoice_id;

	//Update active 0 to sale detail table
	$wpdb->update($sale_detail_table, array('active' => 0), array('sale_id' => $invoice_id));
	foreach ($params['customer_detail'] as $s_value) {
		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {


			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'sub_total' 			=> $s_value['subtotal'],
			);

			$wpdb->insert($sale_detail_table, $sale_detail_data);

		} else {
			if($s_value['id'] != '') {

				$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'sub_total' 			=> $s_value['subtotal'],
				'active' 				=> 1
				);

				$wpdb->update( $sale_detail_table, $sale_detail_data, array('id' => $s_value['sale_detail_id']) );
			}
		}
	}	

	$data['success'] = 1;

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_ws_update_order', 'ws_update_order' );
add_action( 'wp_ajax_nopriv_ws_update_order', 'ws_update_order' );



function isValidInvoice($invoice_id = 0, $lock_check = 0){
	global $wpdb;
	$table =  $wpdb->prefix.'shc_sale';

	if($lock_check) {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND locked=1";
	} else {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND locked=0";
	}
    
    if( $inv_result = $wpdb->get_row($query) ) {
    	return true;
    }
    return false;
}
function isValidInvoiceReturn($invoice_id = 0){

	global $wpdb;
	$table =  $wpdb->prefix.'shc_return_items';
    $query  = "SELECT * FROM ${table} WHERE active = 1 AND id=".$invoice_id." ";
    
    if( $inv_result = $wpdb->get_row($query) ) {
    	return true;
    }
    return false;

}

function isValidInvoiceReturnws($invoice_id = 0){

	global $wpdb;
	$table =  $wpdb->prefix.'shc_ws_return_items';
    $query  = "SELECT * FROM ${table} WHERE active = 1 AND 	id=".$invoice_id." ";
    
    if( $inv_result = $wpdb->get_row($query) ) {
    	return true;
    }
    return false;

}

function isValidInvoicews($invoice_id = 0, $lock_check = 0){
	global $wpdb;
	$table =  $wpdb->prefix.'shc_ws_sale';

	if($lock_check) {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND locked=1";
	} else {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND locked=0";
	}
    
    if( $inv_result = $wpdb->get_row($query) ) {
    	return true;
    }
    return false;
}


function get_customer_name() {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$search = $_POST['search_key'];
	$table =  $wpdb->prefix.'shc_customers';
    $customPagHTML      = "";
    $query              = "SELECT * FROM ${table} WHERE active = 1 AND ( name LIKE '%${search}%' OR mobile LIKE '${search}%')";
	
	$data['result'] = $wpdb->get_results($query);
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_get_customer_name', 'get_customer_name' );
add_action( 'wp_ajax_nopriv_get_customer_name', 'get_customer_name' );

function get_ws_customer_mobile() { 

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$search = $_POST['search_key'];
	$table =  $wpdb->prefix.'shc_wholesale_customer';
    $customPagHTML      = "";
    $query              = "SELECT * FROM ${table} WHERE active = 1 AND ( customer_name LIKE '%${search}%' OR mobile LIKE '${search}%')";
	
	$data['result'] = $wpdb->get_results($query);
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_get_ws_customer_mobile', 'get_ws_customer_mobile' );
add_action( 'wp_ajax_nopriv_get_ws_customer_mobile', 'get_ws_customer_mobile' );



function billing_filter(){

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/billing-list.php' );
	die();
}
add_action( 'wp_ajax_billing_filter', 'billing_filter' );
add_action( 'wp_ajax_nopriv_billing_filter', 'billing_filter' );

function ws_billing_filter(){

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/ws-billing-list.php' );
	die();
}
add_action( 'wp_ajax_ws_billing_filter', 'ws_billing_filter' );
add_action( 'wp_ajax_nopriv_ws_billing_filter', 'ws_billing_filter' );



function return_billing_filter(){

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/return-list.php' );
	die();
}
add_action( 'wp_ajax_return_billing_filter', 'return_billing_filter' );
add_action( 'wp_ajax_nopriv_return_billing_filter', 'return_billing_filter' );


function ws_return_billing_filter(){

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/ws-return-list.php' );
	die();
}
add_action( 'wp_ajax_ws_return_billing_filter', 'ws_return_billing_filter' );
add_action( 'wp_ajax_nopriv_ws_return_billing_filter', 'ws_return_billing_filter' );





function getBillDataReturn($invoice_id = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		= $wpdb->prefix.'shc_customers';
	$sale_table 			= $wpdb->prefix.'shc_return_items';
	$sale_detail_table 		= $wpdb->prefix.'shc_return_items_details';
	$lots_table 			= $wpdb->prefix.'shc_lots';

	$bill_query = "SELECT s.*,
	( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
	( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as customer_name,
	( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
	( CASE WHEN c.secondary_mobile IS NULL THEN 'Nil' ELSE c.secondary_mobile END ) as secondary_mobile,
	( CASE WHEN c.landline IS NULL THEN 'Nil' ELSE c.landline END ) as landline,
	( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
	FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE  s.id = ${invoice_id} AND s.active = 1";
	$data['bill_data'] = $wpdb->get_row($bill_query);

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.return_id = ${invoice_id} AND dt.active = 1 ";
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;
}
function getBillDataReturnws($invoice_id = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		= $wpdb->prefix.'shc_wholesale_customer';
	$sale_table 			= $wpdb->prefix.'shc_ws_return_items';
	$sale_detail_table 		= $wpdb->prefix.'shc_ws_return_items_details';
	$lots_table 			= $wpdb->prefix.'shc_lots';

	$bill_query = "SELECT s.*,
	( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
	( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as customer_name,
	( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
	( CASE WHEN c.secondary_mobile IS NULL THEN 'Nil' ELSE c.secondary_mobile END ) as secondary_mobile,
	( CASE WHEN c.landline IS NULL THEN 'Nil' ELSE c.landline END ) as landline,
	( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
	FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE  s.id = ${invoice_id} AND s.active = 1";
	$data['bill_data'] = $wpdb->get_row($bill_query);
	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.return_id = ${invoice_id} AND dt.active = 1 ";
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;

}


function check_unique_mobile_bill() {

	global $wpdb;
	$mobile 		= $_POST['mobile'];
    $lot_table 		=  $wpdb->prefix.'shc_customers';
    $query 			=  $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ${lot_table} WHERE mobile ='$mobile' and active=1") );

	echo json_encode($query);
	die();

}
add_action( 'wp_ajax_check_unique_mobile_bill', 'check_unique_mobile_bill' );
add_action( 'wp_ajax_nopriv_check_unique_mobile_bill', 'check_unique_mobile_bill' );

function customer_balance() {

	global $wpdb;
	$id 			= $_POST['id'];
    $lot_table 		=  $wpdb->prefix.'shc_sale';
    $query 			=  "SELECT 
						(case when 
						(sum(final_total)-sum(paid_amount))  is NULL Then  '0.00' else (sum(final_total)-sum(paid_amount)) end ) as balance

 						FROM ${lot_table} WHERE customer_id = $id and active=1";
    $data = $wpdb->get_row($query);
var_dump($query);
die();
	echo json_encode($data->balance);
	die();

}
add_action( 'wp_ajax_customer_balance', 'customer_balance' );
add_action( 'wp_ajax_nopriv_customer_balance', 'customer_balance' );

function ws_customer_balance() {

	global $wpdb;
	$id 			= $_POST['id'];
    $lot_table 		=  $wpdb->prefix.'shc_ws_sale';
    $query 			=  "SELECT 
						(case
						when 
						(sum(final_total)-sum(paid_amount))  is NULL Then  '0.00' else (sum(final_total)-sum(paid_amount)) end ) as balance

			 			FROM wp_shc_ws_sale WHERE customer_id = $id and active=1";
    
    $data = $wpdb->get_row($query);
   
	echo json_encode($data->balance);
	die();

}
add_action( 'wp_ajax_ws_customer_balance', 'ws_customer_balance' );
add_action( 'wp_ajax_nopriv_ws_customer_balance', 'ws_customer_balance' );


function check_unique_mobile_wholesale_bill() {

	global $wpdb;
	$mobile 		= $_POST['mobile'];
    $lot_table 		=  $wpdb->prefix.'shc_wholesale_customer';
    $query 			=  $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM ${lot_table} WHERE mobile ='$mobile' and active=1") );

	echo json_encode($query);
	die();




}
add_action( 'wp_ajax_check_unique_mobile_wholesale_bill', 'check_unique_mobile_wholesale_bill' );
add_action( 'wp_ajax_nopriv_check_unique_mobile_wholesale_bill', 'check_unique_mobile_wholesale_bill' );



function ws_slap() {

	$data['success'] = 0;
	global $wpdb;
	$id = $_POST['id'];
	
	$stock_table =  $wpdb->prefix.'shc_stock';
    $lots_table =  $wpdb->prefix.'shc_lots';
    $stock_details = $wpdb->prefix.'shc_ws_sale_detail';
    $sale =$wpdb->prefix.'shc_ws_sale';
	

	$query = "SELECT final_table.*,( final_table.stock_in - final_table.bal_qty) as final_stock from ( SELECT bal.*,stock.lot_number,stock.stock_in,stock.created_at from (SELECT tab.lot_id,sum(tab.bal_qty) as bal_qty,tab.cgst,tab.sgst,tab.selling_price,tab.brand_name,tab.product_name,tab.sale_update from (SELECT sale.lot_id,sale.cgst,sale.sgst,sale.selling_price,sale.sale_update,sale.brand_name,sale.product_name, (sale.sale_unit - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty from (SELECT s.*, l.brand_name, l.product_name,l.hsn,l.selling_price FROM wp_shc_sale_detail as s left join wp_shc_lots as l on l.id = s.lot_id WHERE s.active = 1 ) as sale left join (SELECT sum(sr.return_unit ) as return_unit ,sr.lot_id FROM wp_shc_return_items_details as sr WHERE sr.active = 1 ) as rtn on rtn.lot_id = sale.lot_id union all SELECT sale.lot_id,sale.cgst,sale.sgst,sale.selling_price,sale.sale_update,sale.brand_name,sale.product_name, (sale.sale_unit - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty from (SELECT s.*, l.brand_name, l.product_name,l.hsn,l.selling_price FROM wp_shc_ws_sale_detail as s left join wp_shc_lots as l on l.id = s.lot_id WHERE s.active = 1 ) as sale left join (SELECT sum(sr.return_unit ) as return_unit ,sr.lot_id FROM wp_shc_ws_return_items_details as sr WHERE sr.active = 1 ) as rtn on rtn.lot_id = sale.lot_id ) as tab group by tab.lot_id) as bal left join (select lot_number,sum(stock_count) as stock_in,created_at from wp_shc_stock GROUP by lot_number ) as stock on bal.lot_id = stock.lot_number) as final_table where final_table.lot_id = ${id}  ";

		if($data= $wpdb->get_row( $query, ARRAY_A ) ) {
			$data['success'] = 1;	
			$balance = $data['final_stock'];
		}


	echo json_encode($balance);
	die();
}
add_action( 'wp_ajax_ws_slap', 'ws_slap' );
add_action( 'wp_ajax_nopriv_ws_slap', 'ws_slap' );

//billing

function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function getToken()
{	
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < 50; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}

function get_return_lot_data() {
	$data['success'] = 0;
	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$id = $_POST['id'];

	$sale_table = $wpdb->prefix. 'shc_ws_sale_detail';
	$lot_table = $wpdb->prefix. 'shc_lots';
	$return_table = $wpdb->prefix. 'shc_ws_return_items_details';
	$id = $_POST['inv_id'];
	$search_term = $_POST['search_key'];
	//$query = "SELECT * FROM {$sale_table} as s left join {$lot_table} as l on l.id = s.lot_id WHERE s.sale_id = '$id' and l.product_name like '%${search_term}%' and s.active = 1";
	$query ="SELECT sale.*, (sale.sale_unit - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty from (SELECT s.*, l.brand_name, l.product_name,l.hsn,l.selling_price FROM {$sale_table} as s left join {$lot_table} as l on l.id = s.lot_id WHERE s.sale_id = '$id' and l.product_name like '%$search_term%' and s.active = 1 ) as sale left join (SELECT sum(sr.return_unit ) as return_unit ,sr.lot_id FROM {$return_table} as sr WHERE sr.sale_id = '$id' and sr.active = 1 group by sr.lot_id) as rtn on rtn.lot_id = sale.lot_id";

	if( $data['items'] = $wpdb->get_results( $query, ARRAY_A ) ) {
		$data['success'] = 1;
		
	}
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_get_return_lot_data', 'get_return_lot_data' );
add_action( 'wp_ajax_nopriv_get_return_lot_data', 'get_return_lot_data' );




function get_return_lot_data_retail() {
	$data['success'] = 0;
	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$id = $_POST['id'];

	$sale_table = $wpdb->prefix. 'shc_sale_detail';
	$lot_table = $wpdb->prefix. 'shc_lots';
	$return_table = $wpdb->prefix. 'shc_return_items_details';
	$id = $_POST['inv_id'];
	$search_term = $_POST['search_key'];
	//$query = "SELECT * FROM {$sale_table} as s left join {$lot_table} as l on l.id = s.lot_id WHERE s.sale_id = '$id' and l.product_name like '%${search_term}%' and s.active = 1";
	$query ="SELECT sale.*, (sale.sale_unit - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty from (SELECT s.*, l.brand_name, l.product_name,l.hsn,l.selling_price FROM {$sale_table} as s left join {$lot_table} as l on l.id = s.lot_id WHERE s.sale_id = '$id' and l.product_name like '%$search_term%' and s.active = 1 ) as sale left join (SELECT sum(sr.return_unit ) as return_unit ,sr.lot_id FROM {$return_table} as sr WHERE sr.sale_id = '$id' and sr.active = 1 group by sr.lot_id) as rtn on rtn.lot_id = sale.lot_id";

	if( $data['items'] = $wpdb->get_results( $query, ARRAY_A ) ) {
		$data['success'] = 1;
		
	}
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_get_return_lot_data_retail', 'get_return_lot_data_retail' );
add_action( 'wp_ajax_nopriv_get_return_lot_data_retail', 'get_return_lot_data_retail' );


//Invoice Number Generate Function


function getFinancialYear( $current_date = '' ) {
	$month = date('m', strtotime($current_date));
	$year = date('Y', strtotime($current_date));

    if( $month >= 4 ) {
    	$financial_year = $year;
    } else {
		$financial_year = ( $year - 1 );
    }
    return $financial_year;
}



function ws_create_return() {
	$data['success'] = 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_ws_return_items';
	$sale_detail_table =  $wpdb->prefix.'shc_ws_return_items_details';
	$params = array();

	parse_str($_POST['data'], $params);


	$sale_update = array(
		'customer_id' 			=> $params['customer_id'], 
		'inv_id' 				=> $params['inv_id'], 
		'total_amount' 			=> $params['ws_rtn_fsub_total'], 
	);

	$wpdb->insert($sale_table, $sale_update);
	$data['id'] = $wpdb->insert_id;
	$return_id = $wpdb->insert_id;

	$id_update = array(
		'return_id' 			=>  'GR'.$return_id,	
	);

	$wpdb->update($sale_table, $id_update,array( 'id' => $return_id));
	$data['invoice_id'] = $params['inv_id'];

	foreach ($params['rtn_customer_table'] as $s_value) {

		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {

			$sale_detail_data = array(
				'return_id'				=> $return_id, 
				'sale_id' 				=> $params['inv_id'],
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'return_unit' 			=> $s_value['rtn_qty'],
				'mrp' 					=> $s_value['mrp'],
				'amt' 					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'sub_total' 			=> $s_value['subtotal'],
			);
			$wpdb->insert( $sale_detail_table, $sale_detail_data);
			$data['success'] = 1;
			
		}
	}	
	echo json_encode($data);
	die();
	
}
add_action( 'wp_ajax_ws_create_return', 'ws_create_return' );
add_action( 'wp_ajax_nopriv_ws_create_return', 'ws_create_return' );

function create_return() {
	$data['success'] = 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_return_items';
	$sale_detail_table =  $wpdb->prefix.'shc_return_items_details';
	$params = array();

	parse_str($_POST['data'], $params);


	$sale_update = array(
		'customer_id' 			=> $params['customer_id'], 
		'inv_id' 				=> $params['inv_id'], 
		'total_amount' 			=> $params['ws_rtn_fsub_total'], 
	);

	$wpdb->insert($sale_table, $sale_update);

	$return_id = $wpdb->insert_id;
	$data['id'] = $wpdb->insert_id;

	$id_update = array(
		'return_id' 			=>  'GR'.$return_id,	
	);

	$wpdb->update($sale_table, $id_update,array( 'id' => $return_id));


	$data['invoice_id'] = $params['inv_id'];

	foreach ($params['rtn_customer_table'] as $s_value) {


		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {

			$sale_detail_data = array(
				'return_id'				=> $return_id, 
				'sale_id' 				=> $params['inv_id'],
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'return_unit' 			=> $s_value['rtn_qty'],
				'mrp' 					=> $s_value['mrp'],
				'amt' 					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'sub_total' 			=> $s_value['subtotal'],
			);
			$wpdb->insert( $sale_detail_table, $sale_detail_data);
			$data['success'] = 1;

			
		}
	}	

	echo json_encode($data);
	die();
	
}
add_action( 'wp_ajax_create_return', 'create_return' );
add_action( 'wp_ajax_nopriv_create_return', 'create_return' );


?>


