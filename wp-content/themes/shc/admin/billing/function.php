
<?php

date_default_timezone_set('Asia/Kolkata');


require get_template_directory() . '/admin/billing/class-billing.php';

function load_billing_scripts() {
	wp_enqueue_script( 'billing-script', get_template_directory_uri() . '/admin/billing/inc/js/billing.js', array('jquery'), false, false );
	wp_enqueue_script( 'billing-script_retailer', get_template_directory_uri() . '/admin/billing/inc/js/retail_billing.js', array('jquery'), false, false );
	wp_enqueue_script( 'billing-script_caret', get_template_directory_uri() . '/admin/inc/js/jquery.caret.js', array('jquery'), false, false );
	wp_localize_script( 'billing-script', 'bill_update', array( 'updateurl' => menu_page_url('new_billing',0)));
	wp_localize_script( 'billing-script', 'bill_updatews', array( 'updateurlws' => menu_page_url('ws_new_billing',0)));
	wp_localize_script( 'billing-script', 'bill_return_viewws', array( 'returnviewws' => menu_page_url('ws_return_items_view',0)));
	wp_localize_script( 'billing-script_retailer', 'bill_return_view', array( 'returnview' => menu_page_url('return_items_view',0)));
	wp_localize_script( 'billing-script', 'bill_invoice', array( 'invoiceurl' => menu_page_url('invoice',0)));
	wp_localize_script( 'billing-script', 'bill_invoicews', array( 'invoiceurlws' => menu_page_url('ws_invoice',0)));
	wp_localize_script( 'billing-script', 'bill_return_list', array( 'return_items' => menu_page_url('return_items_list',0)));
	wp_localize_script( 'billing-script', 'bill_return', array( 'return_page' => menu_page_url('return_items',0)));
	wp_localize_script( 'billing-script', 'ws_bill_return', array( 'ws_return_page' => menu_page_url('ws_return_items',0)));
	wp_localize_script( 'billing-script', 'bill_return_listws', array( 'return_itemsws' => menu_page_url('ws_return_items_list',0)));
}
add_action( 'admin_enqueue_scripts', 'load_billing_scripts' );

function getBillData($inv_id = 0, $year = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		=  	$wpdb->prefix.'shc_customers';
	$sale_table 			=  	$wpdb->prefix.'shc_sale';
	$sale_detail_table 		= 	$wpdb->prefix.'shc_sale_detail';
	$lots_table 			= 	$wpdb->prefix.'shc_lots';


	$bill_query = "SELECT s.*,
( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
( CASE WHEN c.name IS NULL THEN '' ELSE c.name END ) as customer_name,
( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1 AND dt.item_status = 'open'";
	
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;
}


function getCancelBillData($inv_id = 0, $year = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		=  	$wpdb->prefix.'shc_customers';
	$sale_table 			=  	$wpdb->prefix.'shc_sale';
	$sale_detail_table 		= 	$wpdb->prefix.'shc_sale_detail';
	$lots_table 			= 	$wpdb->prefix.'shc_lots';


	$bill_query = "SELECT s.*,
( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
( CASE WHEN c.name IS NULL THEN '' ELSE c.name END ) as customer_name,
( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.cancel = 1 ";
	
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
( CASE WHEN c.gst_number IS NULL THEN '' ELSE c.gst_number END ) as gst_number,
( CASE WHEN c.company_name IS NULL THEN '' ELSE c.company_name END ) as company_name,
( CASE WHEN c.customer_name IS NULL THEN '' ELSE c.customer_name END ) as customer_name,
( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mosbile,
( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1 AND dt.item_status = 'open'";
	
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;
}


function getCancelBillDataws($inv_id = 0, $year = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		=  $wpdb->prefix.'shc_wholesale_customer';
	$sale_table 			=  $wpdb->prefix.'shc_ws_sale';
	$sale_detail_table 		= $wpdb->prefix.'shc_ws_sale_detail';
	$lots_table 			= $wpdb->prefix.'shc_lots';


	$bill_query = "SELECT s.*,
( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
( CASE WHEN c.gst_number IS NULL THEN '' ELSE c.gst_number END ) as gst_number,
( CASE WHEN c.company_name IS NULL THEN '' ELSE c.company_name END ) as company_name,
( CASE WHEN c.customer_name IS NULL THEN '' ELSE c.customer_name END ) as customer_name,
( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.cancel = 1 AND dt.item_status = 'open'";
	
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

	
	
    $query  = "SELECT * FROM ${table} WHERE  order_id= 0 AND locked = 0 AND financial_year =$financial_year";

    if( $inv_result = $wpdb->get_row($query) ) {
    	$data['invoice_id'] = $inv_result->id;
    	$data['inv_id'] = $inv_result->inv_id;
    	$data['year']   = $inv_result->financial_year;

    } else {

    	$query_table = "SELECT inv_id,financial_year FROM ${table} order by `id` DESC LIMIT 1";
    	$query_year = $wpdb->get_row($query_table);
    	$final_yr_table = $query_year->financial_year;
    	$inv_id = $query_year->inv_id;
    	if($financial_year == $final_yr_table) {
    		$data['inv_id'] = $inv_id + 1;
    	} else {
    		$data['inv_id'] = '1';
    	}

    	$wpdb->insert($table, array('active'=>1,'financial_year' =>$financial_year,'inv_id'=>$data['inv_id'] ));
    	$data['invoice_id'] = $wpdb->insert_id;
    	$data['year'] = $financial_year;

    	
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

    $query  = "SELECT * FROM ${table} WHERE  order_id = 0 AND locked = 0 AND financial_year =$financial_year";

    if( $inv_result = $wpdb->get_row($query) ) {
    	$data['invoice_id'] = $inv_result->id;
    	$data['inv_id'] = $inv_result->inv_id;
		$data['year']   = $inv_result->financial_year;
    } else {

    	$query_table = "SELECT inv_id,financial_year FROM ${table} order by `id` DESC LIMIT 1";
    	$query_year = $wpdb->get_row($query_table);
    	$final_yr_table = $query_year->financial_year;
    	$inv_id = $query_year->inv_id;
    	if($financial_year == $final_yr_table) {
    		$data['inv_id'] = $inv_id + 1;
    	} else {
    		$data['inv_id'] = '1';
    	}

    	$wpdb->insert($table, array('active'=>1,'financial_year' =>$financial_year,'inv_id'=>$data['inv_id'] ));
    	$data['invoice_id'] = $wpdb->insert_id;
    	$data['year'] = $financial_year;

    	
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

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;
	$customer_id = 0;
	$data['success'] = 0;
	global $wpdb;
	$sale_table 			=  $wpdb->prefix.'shc_sale';
	$sale_detail_table 		=  $wpdb->prefix.'shc_sale_detail';
	$customer_table 		=  $wpdb->prefix.'shc_customers';
	$payment_table 			=  $wpdb->prefix.'shc_payment';
	$payment_table_display 	=  $wpdb->prefix.'shc_payment_display';
	$params = array();
	parse_str($_POST['data'], $params);

	if( isset($params['invoice_id']) && $params['invoice_id'] != 0 && isValidInvoice($params['inv_id'],$params['year'] ) ) {
		$invoice_id = $params['invoice_id'];
		$wpdb->update($sale_table, array('locked' => 1), array('id' => $invoice_id));		
	} else {
		$wpdb->insert($sale_table, array('locked' => 1));		
		$invoice_id = $wpdb->insert_id;
	}
	$today 		= date("dmY");
	$year 		= date("Y");
	$rand 		= strtoupper(substr(uniqid(sha1(time())),0,10));
	$order_id 	= $today . $rand;
	$data['invoice_id'] = $invoice_id;





	if($params['old_customer_id'] != '0')
	{
		$customer_id = $params['old_customer_id'];
		$customer_update = array(
		'name' 						=> $params['name'], 
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address']
		);
		$wpdb->update($customer_table, $customer_update,array('id' => $customer_id));
	}
	else {
		if(  $params['mobile']!='' ){ 
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
	}	
	if($params['delivery_need'] == 'yes'){
		$delivery_need = '1';
	} else {
		$delivery_need = '0';
	}

	if(isset($params['cur_bal_check_box'])){
			$pay_to = '1';
			$pay_to_bal = $params['current_bal'];
	} else{
		$pay_to = '0';
		$pay_to_bal = '0';
	}
	if(isset($params['cod_check'])){
		$cod_check = '1';
		$cod_amount = $params['cod_amount'];
	} else {
		$cod_check = '0';
		$cod_amount = '0';
	}
	if(isset($params['payment_completed'])){
		$payment_completed = 1;
	} else {
		$payment_completed = 0;
	}
	$sale_update = array(
		'customer_id' 				=> $customer_id, 
		'order_id' 					=> $order_id,
		'is_delivery'               => $delivery_need,
		'home_delivery_name' 		=> $params['delivery_name'],
		'home_delivery_mobile' 		=> $params['delivery_phone'],
		'home_delivery_address' 	=> $params['delivery_address'],
		'gst_type' 					=> $params['gst_type'],
		'before_total' 				=> $params['f_total'],
		'prev_bal' 					=> $params['balance_amount_val'],
		'sub_total' 				=> $params['fsub_total'], 
		'paid_amount' 				=> $params['paid_amount'],
		'tot_due_amt' 				=> $params['return_amt'],
		'pay_to_bal' 				=> $pay_to_bal,
		'pay_to_check' 				=> $pay_to,
		'cod_check' 				=> $cod_check,
		'cod_amount' 				=> $cod_amount,
		'payment_completed' 		=> $payment_completed,
		'created_by'				=> $current_nice_name,
	);
	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));
	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['inv_id'] = $data1->inv_id;
	$data['id'] = $data1->id;
	$data['year'] = $data1->financial_year;
	foreach ($params['payment_detail'] as $value) {
		if(isset($value['payment_type'])){			
			$payment_data = 	array(
				'reference_id' 		=> $value['reference_id'],
				'reference_screen' 	=> $value['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $value['payment_type'],
				'amount'			=> $value['payment_amount'],
				'pay_to' 			=> $pay_to_bal,
							);
			$wpdb->insert($payment_table, $payment_data);
       }
	}
	foreach ($params['payment_cash'] as $view) {
		if($view == 'credit_content'){
			$payment_data = 	array(
				'reference_id' 		=> $value['reference_id'],
				'reference_screen' 	=> $value['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $params['pay_cheque'],
				'amount'			=> $params['pay_amount_cheque'],
				'pay_to' 			=> $pay_to_bal,
							);
			$wpdb->insert($payment_table, $payment_data);

		}		
	}


	foreach ($params['customer_detail'] as $s_value) {
		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {
			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'wholesale_price' 		=> $s_value['wholesale_price'],
				'stock' 				=> $s_value['stock'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'igst' 					=> $s_value['igst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'igst_value' 			=> $s_value['igst_value'],
				'cess_value' 			=> $s_value['cess_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'discount_type' 		=> $s_value['discount_type'],
				'sub_total' 			=> $s_value['subtotal'],
				'total' 				=> $s_value['total'],
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
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;
	$data['success'] = 0;
	global $wpdb;
	$sale_table 		= $wpdb->prefix.'shc_ws_sale';
	$sale_detail_table 	= $wpdb->prefix.'shc_ws_sale_detail';
	$customer_table 	= $wpdb->prefix.'shc_wholesale_customer';
	$payment_table 		= $wpdb->prefix. 'shc_ws_payment';
	$params = array();



	parse_str($_POST['data'], $params);

	if( isset($params['invoice_id']) && $params['invoice_id'] != 0 && isValidInvoicews( $params['inv_id'],$params['year'] ) ) {
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

	$inv_txt = 'Inv '.$invoice_id;
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

		if(  $params['company'] != '') {
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
			else {
				$customer_id = '0';
			}
		
	}

	if(isset($params['cur_bal_check_box'])){
			$pay_to = '1';
			$pay_to_bal = $params['ws_current_bal'];
	} else{
		$pay_to = '0';
		$pay_to_bal = '0';
	}
	if(isset($params['cod_check'])){
		$cod_check = '1';
		$cod_amount = $params['cod_amount'];
	} else {
		$cod_check = '0';
		$cod_amount = '0';
	}


	$sale_update = array(
		'customer_id' 				=> $customer_id, 
		'order_id' 					=> $order_id,
		'home_delivery_name' 		=> $params['ws_delivery_name'],
		'home_delivery_mobile' 		=> $params['ws_delivery_phone'],
		'home_delivery_address' 	=> $params['ws_delivery_address'],
		'before_total'              => $params['ws_total'],
		'prev_bal' 					=> $params['ws_balance_amount_val'],
		'sub_total' 				=> $params['ws_fsub_total'],
		'discount' 					=> $params['ws_discount'],
		'paid_amount' 				=> $params['ws_paid_amount'],
		'tot_due_amt' 				=> $params['ws_return_amt'],
		'pay_to_bal' 				=> $pay_to_bal,
		'pay_to_check' 				=> $pay_to,
		'cod_check' 				=> $cod_check,
		'cod_amount' 				=> $cod_amount,
		'created_by'				=> $current_nice_name,
	);

	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));


	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['id'] = $invoice_id;
	$data['inv_id'] = $data1->inv_id;
	$data['year'] = $data1->financial_year;
	$payment = $params['payment_type'];
	foreach ($params['payment_detail'] as $value) {
		if(isset($value['payment_type'])){			
			$payment_data = 	array(
				'reference_id' 		=> $value['reference_id'],
				'reference_screen' 	=> $value['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $value['payment_type'],
				'amount'			=> $value['payment_amount'],
				'pay_to' 			=> $pay_to_bal,
							);
			$wpdb->insert($payment_table, $payment_data);
       }
	}
	foreach ($params['payment_cash'] as $view) {
		if($view == 'credit_content'){
			$payment_data = 	array(
				'reference_id' 		=> $value['reference_id'],
				'reference_screen' 	=> $value['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $params['pay_cheque'],
				'amount'			=> $params['pay_amount_cheque'],
				'pay_to' 			=> $pay_to_bal,
							);
			$wpdb->insert($payment_table, $payment_data);
		}		
	}
	foreach ($params['customer_detail'] as $s_value) {
		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {
			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'stock' 				=> $s_value['stock'],
				'wholesale_price' 		=> $s_value['wholesale_price'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'igst' 					=> $s_value['igst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'cess_value' 			=> $s_value['cess_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'discount_type' 		=> $s_value['discount_type'],
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
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] = 0;
	global $wpdb;
	$sale_table 			=  $wpdb->prefix.'shc_sale';
	$sale_detail_table 		=  $wpdb->prefix.'shc_sale_detail';
	$payment_table 			=  $wpdb->prefix.'shc_payment';
	$payment_table_display 	=  $wpdb->prefix.'shc_payment_display';
	$customer_table 		=  $wpdb->prefix.'shc_customers';
	$params = array();
	parse_str($_POST['data'], $params);
	$invoice_id = $params['invoice_id'];

	if($params['old_customer_id'] != '0')
	{
		$customer_id = $params['old_customer_id'];
		$customer_update = array(
		'name' 						=> $params['name'], 
		'mobile' 					=> $params['mobile'],
		'secondary_mobile' 			=> $params['secondary_mobile'],
		'landline' 					=> $params['landline'],
		'address' 					=> $params['address']
		);
		$wpdb->update($customer_table, $customer_update,array('id' => $customer_id));

	}
	else {
		if( $params['mobile']!='' ) { 
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
		else {
				$customer_id = 0;
			}
	}
	if($params['delivery_need'] == 'yes'){
		$delivery_need = '1';
	} else {
		$delivery_need = '0';
	}

	if(isset($params['cur_bal_check_box'])){
			$pay_to = '1';
			$pay_to_bal = $params['current_bal'];
	} else{
		$pay_to = '0';
		$pay_to_bal = '0';
	}
	if(isset($params['cod_check'])){
		$cod_check = '1';
		$cod_amount = $params['cod_amount'];
	} else {
		$cod_check = '0';
		$cod_amount = '0';
	}
	if(isset($params['payment_completed'])){
		$payment_completed = 1;
	} else {
		$payment_completed = 0;
	}
	$sale_update = array(
		'customer_id' 				=> $customer_id, 
		'is_delivery'               => $delivery_need,
		'home_delivery_name' 		=> $params['delivery_name'],
		'home_delivery_mobile' 		=> $params['delivery_phone'],
		'home_delivery_address' 	=> $params['delivery_address'],
		'gst_type' 					=> $params['gst_type'],
		'before_total' 				=> $params['f_total'],
		'sub_total' 				=> $params['fsub_total'], 
		'prev_bal' 					=> $params['balance_amount_val'],
		'paid_amount' 				=> $params['paid_amount'],
		'tot_due_amt' 				=> $params['return_amt'],
		'pay_to_bal' 				=> $pay_to_bal,
		'pay_to_check' 				=> $pay_to,
		'cod_check' 				=> $cod_check,
		'cod_amount' 				=> $cod_amount,
		'payment_completed'			=> $payment_completed,
		'modified_by'	 			=> $current_nice_name,
	);

	
	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));
	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['inv_id'] = $data1->inv_id;
	$data['year'] = $data1->financial_year;
	$data['id'] = $invoice_id;

	$wpdb->update($payment_table, array('active' => 0), array('sale_id' => $data['id']));

	//$wpdb->update($payment_table_display, array('active' => 0), array('sale_id' => $data['id']));	

	foreach ($params['payment_detail'] as $value) {
		if(isset($value['payment_type'])){
			$payment_data = 	array(
				'reference_id' 		=> $value['reference_id'],
				'reference_screen' 	=> $value['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $value['payment_type'],
				'amount'			=> $value['payment_amount'],
				'pay_to' 			=> $pay_to_bal,
				);
			$wpdb->insert($payment_table, $payment_data);
       }
	}
	if(isset($params['pay_amount_cheque'])) {
			$payment_data = 	array(
				'reference_id' 		=> $params['reference_id'],
				'reference_screen' 	=> $params['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $params['pay_cheque'],
				'amount'			=> $params['pay_amount_cheque'],
				'pay_to' 			=> $pay_to_bal,
				);
			$wpdb->insert($payment_table, $payment_data);

	}
	//Update active 0 to sale detail table
	$wpdb->update($sale_detail_table, array('active' => 0), array('sale_id' => $invoice_id));
	foreach ($params['customer_detail'] as $s_value) {
		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {
			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'stock' 				=> $s_value['stock'],
				'wholesale_price' 		=> $s_value['wholesale_price'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'igst' 					=> $s_value['igst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'igst_value' 			=> $s_value['igst_value'],
				'cess_value' 			=> $s_value['cess_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'discount_type' 		=> $s_value['discount_type'],
				'sub_total' 			=> $s_value['subtotal'],
				'total' 				=> $s_value['total'],
			);
			$wpdb->insert($sale_detail_table, $sale_detail_data);
		} 
	}	

	$data['success'] = 1;
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_order', 'update_order' );
add_action( 'wp_ajax_nopriv_update_order', 'update_order' );




function ws_update_order() {
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] = 0;
	global $wpdb;
	$sale_table 		=  $wpdb->prefix.'shc_ws_sale';
	$sale_detail_table 	=  $wpdb->prefix.'shc_ws_sale_detail';
	$payment_table 		=  $wpdb->prefix.'shc_ws_payment';
	$customer_table 	= $wpdb->prefix.'shc_wholesale_customer';
	$params = array();

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
		if(  $params['company'] != '') {
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
	else {
			$customer_id = 0;
		}
	}


	if(isset($params['cur_bal_check_box'])){
			$pay_to = '1';
			$pay_to_bal = $params['ws_current_bal'];
	} else{
		$pay_to = '0';
		$pay_to_bal = '0';
	}
	if(isset($params['cod_check'])){
		$cod_check = '1';
		$cod_amount = $params['cod_amount'];
	} else {
		$cod_check = '0';
		$cod_amount = '0';
	}
	$sale_update = array(
		'customer_id' 				=> $customer_id, 
		'home_delivery_name' 		=> $params['ws_delivery_name'],
		'home_delivery_mobile' 		=> $params['ws_delivery_phone'],
		'home_delivery_address' 	=> $params['ws_delivery_address'],
		'before_total'              => $params['ws_total'],
		'sub_total' 				=> $params['ws_fsub_total'], 
		'prev_bal' 					=> $params['ws_balance_amount_val'],
		'discount' 					=> $params['ws_discount'],
		'paid_amount' 				=> $params['ws_paid_amount'],
		'tot_due_amt' 				=> $params['ws_return_amt'],
		'pay_to_bal' 				=> $pay_to_bal,
		'pay_to_check' 				=> $pay_to,
		'cod_check' 				=> $cod_check,
		'cod_amount' 				=> $cod_amount,
		'modified_by'				=> $current_nice_name,
	);


	$wpdb->update($sale_table, $sale_update, array('id' => $invoice_id));

	
	$query = "SELECT * from ${sale_table} where id = ${invoice_id}";
	$data1 = $wpdb->get_row($query);
	$data['inv_id'] = $data1->inv_id;
	$data['year'] = $data1->financial_year;
	$data['id'] = $invoice_id;
//$wpdb->update($payment_table, array('active' => 0), array('sale_id' => $data['id']));
	$wpdb->update($payment_table, array('active' => 0), array('sale_id' => $data['id']));

	$payment = $params['payment_type'];
		foreach ($params['payment_detail'] as $value) {
		if(isset($value['payment_type'])){
			$payment_data = 	array(
				'reference_id' 		=> $value['reference_id'],
				'reference_screen' 	=> $value['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $value['payment_type'],
				'amount'			=> $value['payment_amount'],
				'pay_to' 			=> $pay_to_bal,
				);
			$wpdb->insert($payment_table, $payment_data);
       }
	}
	foreach ($params['payment_cash'] as $view) {
		if($view == 'credit_content'){
			$payment_data = 	array(
				'reference_id' 		=> $value['reference_id'],
				'reference_screen' 	=> $value['reference_screen'],
				'sale_id'  			=> $data['id'],
				'search_id'			=> $data['inv_id'],
				'year'				=> $data['year'],
				'payment_details'	=> '',
				'payment_date'		=> date('Y-m-d'),
				'customer_id'		=> $customer_id,
				'payment_type'		=> $params['pay_cheque'],
				'amount'			=> $params['pay_amount_cheque'],
				'pay_to' 			=> $pay_to_bal,
				);
			$wpdb->insert($payment_table, $payment_data);
		}
	}


	//Update active 0 to sale detail table
	$wpdb->update($sale_detail_table, array('active' => 0), array('sale_id' => $invoice_id));
	foreach ($params['customer_detail'] as $s_value) {

		if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '') {

			$sale_detail_data = array(
				'sale_id' 				=> $invoice_id,
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['unit'],
				'stock' 				=> $s_value['stock'],
				'wholesale_price' 		=> $s_value['wholesale_price'],
				'amt'					=> $s_value['amt'],
				'cgst' 					=> $s_value['cgst'],
				'sgst' 					=> $s_value['sgst'],
				'cgst_value' 			=> $s_value['cgst_value'],
				'sgst_value' 			=> $s_value['sgst_value'],
				'unit_price' 			=> $s_value['price'],
				'discount' 				=> $s_value['discount'],
				'discount_type' 		=> $s_value['discount_type'],
				'sub_total' 			=> $s_value['subtotal'],
			);

			$wpdb->insert($sale_detail_table, $sale_detail_data);

		} 
		
	}	

	$data['success'] = 1;

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_ws_update_order', 'ws_update_order' );
add_action( 'wp_ajax_nopriv_ws_update_order', 'ws_update_order' );



function isValidInvoice($invoice_id = 0,$year=0, $lock_check = 0){
	global $wpdb;
	$table =  $wpdb->prefix.'shc_sale';

	if($lock_check) {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND financial_year=".$year." AND locked=1";
	} else {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND financial_year=".$year." AND locked=0";
	}
    
    if($inv_result = $wpdb->get_row($query) ) {
    	return true;
    }
    return false;
}




function isValidInvoicews($invoice_id = 0,$year=0, $lock_check = 0){
	global $wpdb;
	$table =  $wpdb->prefix.'shc_ws_sale';

	if($lock_check) {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND financial_year=".$year." AND locked=1";
	} else {
    	$query  = "SELECT * FROM ${table} WHERE active = 1 AND inv_id=".$invoice_id." AND financial_year=".$year." AND locked=0";
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
    $query              = "SELECT * FROM ${table} WHERE active = 1 AND ( company_name LIKE '%${search}%' OR customer_name LIKE '%${search}%' OR mobile LIKE '${search}%')";
	
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



function cancel_billing_filter(){

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/cancel-billing-list.php' );
	die();
}
add_action( 'wp_ajax_cancel_billing_filter', 'cancel_billing_filter' );
add_action( 'wp_ajax_nopriv_cancel_billing_filter', 'cancel_billing_filter' );


function return_cancel_billing_filter(){

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/return-cancel-billing-list.php' );
	die();
}
add_action( 'wp_ajax_return_cancel_billing_filter', 'return_cancel_billing_filter' );
add_action( 'wp_ajax_nopriv_return_cancel_billing_filter', 'return_cancel_billing_filter' );

function ws_cancel_billing_filter() {

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/ws-cancel-billing-list.php' );
	die();
}
add_action( 'wp_ajax_ws_cancel_billing_filter', 'ws_cancel_billing_filter' );
add_action( 'wp_ajax_nopriv_ws_cancel_billing_filter', 'ws_cancel_billing_filter' );

function ws_cancel_return_billing_filter() {

	$billing = new Billing();
	include( get_template_directory().'/admin/billing/ajax_loading/ws-return-cancel-billing-list.php' );
	die();
}
add_action( 'wp_ajax_ws_cancel_return_billing_filter', 'ws_cancel_return_billing_filter' );
add_action( 'wp_ajax_nopriv_ws_cancel_return_billing_filter', 'ws_cancel_return_billing_filter' );


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


//Get Return Data
function getBillDataReturnData($inv_id = 0, $year = 0,$return_id = 0) {

	$currentdate_time = date('Y-m-d H:i:s');


	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 			=  	$wpdb->prefix.'shc_customers';
	$sale_table 				=  	$wpdb->prefix.'shc_sale';
	$sale_detail_table 			= 	$wpdb->prefix.'shc_sale_detail';
	$lots_table 				= 	$wpdb->prefix.'shc_lots';
	$return_table 				= 	$wpdb->prefix.'shc_return_items';
	$return_detail_table 		= 	$wpdb->prefix.'shc_return_items_details';



$bill_query = "SELECT s.*,
( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
( CASE WHEN c.name IS NULL THEN '' ELSE c.name END ) as customer_name,
( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$data['invoice_id'] = $data['bill_data']->id;
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query 		= "SELECT sale_tab.*,(case when (sale_tab.sale_unit - return_tab.return_unit) is null then sale_tab.sale_unit else (sale_tab.sale_unit - return_tab.return_unit) end ) as balance_unit from (SELECT dt.lot_id,dt.sale_unit,dt.sale_id,dt.discount,dt.delivery_count, l.lot_no, l.brand_name, l.product_name,l.hsn,l.gst_percentage,l.cess_percentage FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1  and dt.sale_update < '${currentdate_time}' ) as sale_tab left join (SELECT lot_id,sale_id,sum(return_unit) as return_unit FROM ${return_detail_table} WHERE sale_id = ${invoice_id} AND active = 1 and sale_update < '${currentdate_time}' GROUP by lot_id ) as return_tab on sale_tab.lot_id = return_tab.lot_id";
	
	$data['ordered_data'] 		= $wpdb->get_results($ordered_item_query);
	$return_item_quantity 		= "SELECT dt.sale_id,dt.lot_id,sum(dt.sale_unit) as sale_unit,sum(dt.return_unit) as return_unit,dt.mrp,sum(dt.cgst_value) as cgst_value,sum(dt.sub_total) as sub_total,sum(dt.amt) as amt, l.lot_no, l.brand_name, l.product_name,l.hsn,dt.created_at FROM ${return_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id =${invoice_id} AND dt.active = 1 and dt.sale_update < '${currentdate_time}' GROUP by dt.lot_id";
	$data['return_data'] 		= $wpdb->get_results($return_item_quantity);

	if( $return_id != 0 ) { 
		$return_ordered_itmes_query 	= "SELECT sale_table.*,
(case when return_table.return_unit IS null then 0 else return_table.return_unit end) as return_unit,
(case when return_table.cgst_value IS null then 0 else return_table.cgst_value end) as cgst_value,
(case when return_table.igst_value IS null then 0 else return_table.igst_value end) as igst_value,
(case when return_table.cess_value IS null then 0 else return_table.cess_value end) as cess_value,
(case when return_table.amt IS null then 0 else return_table.amt end) as amt,
(case when return_table.total_amount IS null then 0 else return_table.total_amount end) as total,
(case when return_table.sub_total IS null then 0 else return_table.sub_total end) as sub_total,
(case when return_table.bal_qty IS null then sale_table.balance_unit else return_table.bal_qty end) as new_bal_qty
 from (SELECT sale_tab.*,(case when (sale_tab.sale_unit - return_tab.return_unit) is null then sale_tab.sale_unit else (sale_tab.sale_unit - return_tab.return_unit) end ) as balance_unit from (SELECT dt.lot_id,dt.sale_unit,dt.sale_id,dt.delivery_count,dt.discount, l.lot_no, l.brand_name, l.product_name,l.hsn,l.gst_percentage,l.cess_percentage FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1  and dt.sale_update < '${currentdate_time}' ) as sale_tab left join (SELECT lot_id,sale_id,sum(return_unit) as return_unit FROM ${return_detail_table} WHERE sale_id = ${invoice_id} AND active = 1 and sale_update < '${currentdate_time}' GROUP by lot_id ) as return_tab on sale_tab.lot_id = return_tab.lot_id)  as sale_table left join (select rdetail_table.lot_id,rdetail_table.cgst_value,rdetail_table.igst_value,rdetail_table.cess_value,rdetail_table.cd_id,rdetail_table.cgst,rdetail_table.sub_total,rdetail_table.sale_update,rdetail_table.total_amount,rdetail_table.amt,rdetail_table.return_unit,rdetail_table.bal_qty from ( SELECT rt.`total_amount`,rt.cd_id as cd_id,ritems.* FROM ${return_table} as rt left join ${return_detail_table} as ritems on rt.id = ritems.return_id where rt.`id` = ${return_id} and rt.active = 1 and ritems.active = 1 and ritems.sale_update < '${currentdate_time}' ) as rdetail_table) as  return_table on sale_table.lot_id = return_table.lot_id";
		$data['return_ordered_data'] 	= $wpdb->get_results($return_ordered_itmes_query);

	}

	return $data;


}

function getBillDataReturnDataWs($inv_id = 0, $year = 0,$return_id = 0) {

	$currentdate_time = date('Y-m-d H:i:s');


	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 			=  	$wpdb->prefix.'shc_wholesale_customer';
	$sale_table 				=  	$wpdb->prefix.'shc_ws_sale';
	$sale_detail_table 			= 	$wpdb->prefix.'shc_ws_sale_detail';
	$lots_table 				= 	$wpdb->prefix.'shc_lots';
	$return_table 				= 	$wpdb->prefix.'shc_ws_return_items';
	$return_detail_table 		= 	$wpdb->prefix.'shc_ws_return_items_details';



	$bill_query = "SELECT s.*,
( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
( CASE WHEN c.customer_name IS NULL THEN '' ELSE c.customer_name END ) as customer_name,
( CASE WHEN c.company_name IS NULL THEN '' ELSE c.company_name END ) as company_name,
( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.inv_id = ${inv_id} and s.financial_year = ${year} AND s.locked = 1";

	$data['bill_data'] = $wpdb->get_row($bill_query);
	$data['invoice_id'] = $data['bill_data']->id;
	$invoice_id = $data['bill_data']->id;

	$ordered_item_query 		= "SELECT sale_tab.*,(case when (sale_tab.sale_unit - return_tab.return_unit) is null then sale_tab.sale_unit else (sale_tab.sale_unit - return_tab.return_unit) end ) as balance_unit from (SELECT dt.lot_id,dt.sale_unit,dt.sale_id,dt.delivery_count,dt.discount, l.lot_no, l.brand_name, l.product_name,l.hsn,l.cgst,l.sgst FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1  and dt.sale_update < '${currentdate_time}' ) as sale_tab left join (SELECT lot_id,sale_id,sum(return_unit) as return_unit FROM ${return_detail_table} WHERE sale_id = ${invoice_id} AND active = 1 and sale_update < '${currentdate_time}' GROUP by lot_id ) as return_tab on sale_tab.lot_id = return_tab.lot_id";
	$data['ordered_data'] 		= $wpdb->get_results($ordered_item_query);
	$return_item_quantity 		= "SELECT dt.sale_id,dt.lot_id,sum(dt.sale_unit) as sale_unit,sum(dt.return_unit) as return_unit,dt.mrp,sum(dt.cgst_value) as cgst_value,sum(dt.sub_total) as sub_total,sum(dt.amt) as amt, l.lot_no, l.brand_name, l.product_name,l.hsn,dt.created_at FROM ${return_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id =${invoice_id} AND dt.active = 1 and dt.sale_update < '${currentdate_time}' GROUP by dt.lot_id";
	$data['return_data'] 		= $wpdb->get_results($return_item_quantity);

	if( $return_id != 0 ) { 
		$return_ordered_itmes_query 	= "SELECT sale_table.*,
(case when return_table.return_unit IS null then 0 else return_table.return_unit end) as return_unit,
(case when return_table.cgst_value IS null then 0 else return_table.cgst_value end) as cgst_value,
(case when return_table.amt IS null then 0 else return_table.amt end) as amt,
(case when return_table.total_amount IS null then 0 else return_table.total_amount end) as total,
(case when return_table.sub_total IS null then 0 else return_table.sub_total end) as sub_total,
(case when return_table.bal_qty IS null then sale_table.balance_unit else return_table.bal_qty end) as new_bal_qty
 from (SELECT sale_tab.*,(case when (sale_tab.sale_unit - return_tab.return_unit) is null then sale_tab.sale_unit else (sale_tab.sale_unit - return_tab.return_unit) end ) as balance_unit from (SELECT dt.lot_id,dt.sale_unit,dt.sale_id,dt.delivery_count,dt.discount, l.lot_no, l.brand_name, l.product_name,l.hsn,l.cgst,l.sgst FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.sale_id = ${invoice_id} AND dt.active = 1  and dt.sale_update < '${currentdate_time}' ) as sale_tab left join (SELECT lot_id,sale_id,sum(return_unit) as return_unit FROM ${return_detail_table} WHERE sale_id = ${invoice_id} AND active = 1 and sale_update < '${currentdate_time}' GROUP by lot_id ) as return_tab on sale_tab.lot_id = return_tab.lot_id)  as sale_table left join (select rdetail_table.lot_id,rdetail_table.cgst_value,rdetail_table.cd_id,rdetail_table.cgst,rdetail_table.sub_total,rdetail_table.sale_update,rdetail_table.total_amount,rdetail_table.amt,rdetail_table.return_unit,rdetail_table.bal_qty from ( SELECT rt.`total_amount`,rt.cd_id as cd_id,ritems.* FROM ${return_table} as rt left join ${return_detail_table} as ritems on rt.id = ritems.return_id where rt.`id` = ${return_id} and rt.active = 1 and ritems.active = 1 and ritems.sale_update < '${currentdate_time}' ) as rdetail_table) as  return_table on sale_table.lot_id = return_table.lot_id";
		$data['return_ordered_data'] 	= $wpdb->get_results($return_ordered_itmes_query);
	
	}
	return $data;


}

function getBillDataReturn($invoice_id = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		= $wpdb->prefix.'shc_customers';
	$sale_table 			= $wpdb->prefix.'shc_return_items';
	$sale_detail_table 		= $wpdb->prefix.'shc_return_items_details';
	$lots_table 			= $wpdb->prefix.'shc_lots';
	$sale_tab 				= $wpdb->prefix.'shc_sale';

	$bill_query = "SELECT s.*,
	( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
	( CASE WHEN c.name IS NULL THEN '' ELSE c.name END ) as customer_name,
	( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
	( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
	( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
	( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
	FROM (select rt.*,sale.gst_type from {$sale_table} as rt left join {$sale_tab} as sale on sale.id = rt.inv_id and sale.active= 1) as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE  s.id = ${invoice_id} AND s.active = 1";
	$data['bill_data'] = $wpdb->get_row($bill_query);

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.return_id = ${invoice_id} AND dt.active = 1 ";
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);

	return $data;
}

function getCancelBillDataReturn($invoice_id = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		= $wpdb->prefix.'shc_customers';
	$sale_table 			= $wpdb->prefix.'shc_return_items';
	$sale_detail_table 		= $wpdb->prefix.'shc_return_items_details';
	$lots_table 			= $wpdb->prefix.'shc_lots';

	$bill_query = "SELECT s.*,
	( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
	( CASE WHEN c.name IS NULL THEN '' ELSE c.name END ) as customer_name,
	( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
	( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
	( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
	( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
	FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE  s.id = ${invoice_id} AND s.cancel = 1";
	$data['bill_data'] = $wpdb->get_row($bill_query);

	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.return_id = ${invoice_id} AND dt.cancel = 1 ";
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
	( CASE WHEN c.customer_name IS NULL THEN '' ELSE c.customer_name END ) as customer_name,
	( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
	( CASE WHEN c.company_name IS NULL THEN '' ELSE c.company_name END ) as company_name,
	( CASE WHEN c.gst_number IS NULL THEN '' ELSE c.gst_number END ) as gst_number,
	( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
	( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
	( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
	FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE  s.id = ${invoice_id} AND s.active = 1";
	$data['bill_data'] = $wpdb->get_row($bill_query);
	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.return_id = ${invoice_id} AND dt.active = 1 ";
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;

}

function getCancelBillDataReturnws($invoice_id = 0) {

	$data['success'] = 0;
	$data['msg'] = 'Something Went Wrong!';

	global $wpdb;
	$customer_table 		= $wpdb->prefix.'shc_wholesale_customer';
	$sale_table 			= $wpdb->prefix.'shc_ws_return_items';
	$sale_detail_table 		= $wpdb->prefix.'shc_ws_return_items_details';
	$lots_table 			= $wpdb->prefix.'shc_lots';

	$bill_query = "SELECT s.*,
	( CASE WHEN c.id IS NULL THEN 0 ELSE c.id END ) as customer_id,
	( CASE WHEN c.customer_name IS NULL THEN '' ELSE c.customer_name END ) as customer_name,
	( CASE WHEN c.mobile IS NULL THEN '' ELSE c.mobile END ) as mobile,
	( CASE WHEN c.secondary_mobile IS NULL THEN '' ELSE c.secondary_mobile END ) as secondary_mobile,
	( CASE WHEN c.landline IS NULL THEN '' ELSE c.landline END ) as landline,
	( CASE WHEN c.address IS NULL THEN '' ELSE c.address END ) as address
	FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE  s.id = ${invoice_id} AND s.cancel = 1";
	$data['bill_data'] = $wpdb->get_row($bill_query);
	$ordered_item_query = "SELECT dt.*, l.lot_no, l.brand_name, l.product_name,l.hsn FROM ${sale_detail_table} as dt JOIN ${lots_table} as l ON dt.lot_id = l.id WHERE dt.return_id = ${invoice_id} AND dt.cancel = 1 ";
	$data['ordered_data'] = $wpdb->get_results($ordered_item_query);
	
	return $data;

}

function customer_balance() {

	global $wpdb;
	$customer_id 	= $_POST['id'];
	$sale_table 	= $wpdb->prefix.'shc_sale';
	$return_table 	= $wpdb->prefix.'shc_return_items';
	$payment_table  = $wpdb->prefix.'shc_payment';
	$customer_table = $wpdb->prefix.'shc_customers';

	$query = "SELECT cus_full_detail.customer_id,
cus_full_detail.customer_name,
cus_full_detail.address,
cus_full_detail.mobile,
sum(cus_full_detail.new_sale_total) as new_sale_total,
sum(cus_full_detail.final_bal) as final_bal
FROM (
	SELECT full_table.cus_id as customer_id,full_table.name as customer_name,full_table.address,full_table.mobile,
(case when  full_table.sale_id is null then 0.00 else full_table.sale_id end ) as sale_id,
(case when  full_table.search_id is null then 0.00 else full_table.search_id end ) as search_id,
(case when  full_table.year is null then 0.00 else full_table.year end ) as year,
(case when  full_table.sale_total is null then 0.00 else full_table.sale_total end ) as sale_total,
(case when  full_table.paid_amount is null then 0.00 else full_table.paid_amount end ) as paid_amount,
(case when  full_table.key_amount is null then 0.00 else full_table.key_amount end ) as key_amount,
(case when  full_table.return_amount is null then 0.00 else full_table.return_amount end ) as return_amount,
(case when  full_table.invoice_bill_bal is null then 0.00 else full_table.invoice_bill_bal end ) as invoice_bill_bal,
(case when  full_table.return_bill_bal is null then 0.00 else full_table.return_bill_bal end ) as return_bill_bal,
(case when  full_table.new_sale_total is null then 0.00 else full_table.new_sale_total end ) as new_sale_total,
(case when  full_table.final_bal is null then 0.00 else full_table.final_bal end ) as final_bal
from  
( 
    select * from (
    SELECT id as cus_id,name,mobile,address FROM ${customer_table}  WHERE active = 1
) as customer
left join 
(
	SELECT tab.*,(tab.invoice_bill_bal - tab.return_bill_bal) as final_bal  from (
    select final_tab.*, 
(final_tab.sale_total- final_tab.paid_amount) as invoice_bill_bal,
(final_tab.return_amount- final_tab.key_amount) as return_bill_bal,
(final_tab.sale_total - final_tab.return_amount ) as new_sale_total
from ( 
    select bill_table.*,
(case when return_tab.key_amount is null then 0.00 else return_tab.key_amount end) as key_amount,
(case when return_tab.return_amount is null then 0.00 else return_tab.return_amount end) as return_amount
from 
(
    SELECT sale.inv_id as sale_id,
    sale.customer_id,
    sale.search_id,
    sale.year,sale.sale_total,
    (case when payment.payment_amount is null then 0.00 else payment.payment_amount-sale.pay_to_bal end) as paid_amount 
    from 
(
    SELECT id as inv_id,customer_id,
    `inv_id` as search_id,
    `financial_year` as year,
    `sub_total` as sale_total,`pay_to_bal` FROM ${sale_table} WHERE`active`=1 
)  as sale
left join 
( 
    select 	sale_id,sum(amount) as payment_amount from ${payment_table} WHERE active = 1 and 	payment_type!= 'credit' GROUP by sale_id
 )  as payment
on sale.inv_id = payment.sale_id
) as bill_table 
left join 
(
    SELECT inv_id,key_amount,total_amount as return_amount from {$return_table} WHERE active = 1
) 
as return_tab 
on bill_table.sale_id = return_tab.inv_id )
as final_tab  
) as tab 
)
as full_sale_tab  
on full_sale_tab.customer_id = customer.cus_id 
) as full_table
order by full_table.sale_id ASC )
as cus_full_detail where cus_full_detail.customer_id = ${customer_id}  GROUP by cus_full_detail.customer_id ";

    $data = $wpdb->get_row($query);
// var_dump($query);
// die();
  
	echo json_encode($data);
	die();

}
add_action( 'wp_ajax_customer_balance', 'customer_balance' );
add_action( 'wp_ajax_nopriv_customer_balance', 'customer_balance' );

function ws_customer_balance() {

	global $wpdb;
	$customer_id 	= $_POST['id'];
	$sale_table 	= $wpdb->prefix.'shc_ws_sale';
	$return_table 	= $wpdb->prefix.'shc_ws_return_items';
	$payment_table  = $wpdb->prefix.'shc_ws_payment';
	$customer_table = $wpdb->prefix.'shc_wholesale_customer';

    $query = "SELECT cus_full_detail.customer_id,
cus_full_detail.customer_name,
cus_full_detail.address,
cus_full_detail.mobile,
sum(cus_full_detail.new_sale_total) as new_sale_total,
sum(cus_full_detail.final_bal) as final_bal
FROM (
	SELECT full_table.cus_id as customer_id,full_table.name as customer_name,full_table.address,full_table.mobile,
(case when  full_table.sale_id is null then 0.00 else full_table.sale_id end ) as sale_id,
(case when  full_table.search_id is null then 0.00 else full_table.search_id end ) as search_id,
(case when  full_table.year is null then 0.00 else full_table.year end ) as year,
(case when  full_table.sale_total is null then 0.00 else full_table.sale_total end ) as sale_total,
(case when  full_table.paid_amount is null then 0.00 else full_table.paid_amount end ) as paid_amount,
(case when  full_table.key_amount is null then 0.00 else full_table.key_amount end ) as key_amount,
(case when  full_table.return_amount is null then 0.00 else full_table.return_amount end ) as return_amount,
(case when  full_table.invoice_bill_bal is null then 0.00 else full_table.invoice_bill_bal end ) as invoice_bill_bal,
(case when  full_table.return_bill_bal is null then 0.00 else full_table.return_bill_bal end ) as return_bill_bal,
(case when  full_table.new_sale_total is null then 0.00 else full_table.new_sale_total end ) as new_sale_total,
(case when  full_table.final_bal is null then 0.00 else full_table.final_bal end ) as final_bal
from  
( 
    select * from (
    SELECT id as cus_id,customer_name as name,mobile,address FROM ${customer_table}  WHERE active = 1
) as customer
left join 
(
	SELECT tab.*,(tab.invoice_bill_bal - tab.return_bill_bal) as final_bal  from (
    select final_tab.*, 
(final_tab.sale_total- final_tab.paid_amount) as invoice_bill_bal,
(final_tab.return_amount- final_tab.key_amount) as return_bill_bal,
(final_tab.sale_total - final_tab.return_amount ) as new_sale_total
from ( 
    select bill_table.*,
(case when return_tab.key_amount is null then 0.00 else return_tab.key_amount end) as key_amount,
(case when return_tab.return_amount is null then 0.00 else return_tab.return_amount end) as return_amount
from 
(
    SELECT sale.inv_id as sale_id,
    sale.customer_id,
    sale.search_id,
    sale.year,sale.sale_total,
    (case when payment.payment_amount is null then 0.00 else payment.payment_amount-sale.pay_to_bal end) as paid_amount 
    from 
(
    SELECT id as inv_id,customer_id,
    `inv_id` as search_id,
    `financial_year` as year,
    `sub_total` as sale_total,`pay_to_bal` FROM ${sale_table} WHERE`active`=1 
)  as sale
left join 
( 
    select 	sale_id,sum(amount) as payment_amount from ${payment_table} WHERE active = 1 and 	payment_type!= 'credit' GROUP by sale_id
 )  as payment
on sale.inv_id = payment.sale_id
) as bill_table 
left join 
(
    SELECT inv_id,key_amount,total_amount as return_amount from {$return_table} WHERE active = 1
) 
as return_tab 
on bill_table.sale_id = return_tab.inv_id )
as final_tab  
) as tab 
)
as full_sale_tab  
on full_sale_tab.customer_id = customer.cus_id 
) as full_table
order by full_table.sale_id ASC )
as cus_full_detail where cus_full_detail.customer_id = ${customer_id}  GROUP by cus_full_detail.customer_id";
  $data = $wpdb->get_row($query);

	echo json_encode($data);
	die();

}
add_action( 'wp_ajax_ws_customer_balance', 'ws_customer_balance' );
add_action( 'wp_ajax_nopriv_ws_customer_balance', 'ws_customer_balance' );




function ws_slap() {

	$data['success'] = 0;
	global $wpdb;
	$id = $_POST['id'];
	
	$stock_table =  $wpdb->prefix.'shc_stock';
    $lots_table =  $wpdb->prefix.'shc_lots';
    $stock_details = $wpdb->prefix.'shc_ws_sale_detail';
    $sale =$wpdb->prefix.'shc_ws_sale';
	
$query = "SELECT * from ( SELECT lot_table.*,(case when final_sale_tab.tot_sale is null then 0 else final_sale_tab.tot_sale end ) as tot_sale, (case when final_sale_tab.tot_sale is null then lot_table.stock_in else lot_table.stock_in - final_sale_tab.tot_sale end ) as balance_stock from (select lot_table.*,(case when stock_table.stock_in is null then 0 else stock_table.stock_in end )as stock_in from (select id,cgst,sgst,selling_price,`brand_name`,`product_name`,`hsn` from wp_shc_lots WHERE active = 1) as lot_table left join (select (case when sum(stock_count) is null then 0 else sum(stock_count) end )as stock_in,lot_number from wp_shc_stock WHERE active=1 GROUP by lot_number ) as stock_table on lot_table.id = stock_table.lot_number ) as lot_table left join (select (sum(total_sale_unit)) as tot_sale,lot_id from ( select (case when return_table.return_unit is null then sale_table.sale_unit else (sale_table.sale_unit - return_table.return_unit) end) as total_sale_unit,sale_table.lot_id from (SELECT (case when sum(sale.sale_unit ) is null then 0 ELSE sum(sale.sale_unit ) end) as sale_unit ,(case when (sale.lot_id) is null then 0 else (sale.lot_id) end) as lot_id FROM wp_shc_sale_detail as sale WHERE sale.active =1 group by lot_id) as sale_table left join (SELECT (case when sum(return_tab.return_unit ) is null then 0 ELSE sum(return_tab.return_unit ) end) as return_unit ,(case when (return_tab.lot_id) is null then 0 else (return_tab.lot_id) end) as lot_id FROM wp_shc_return_items_details as return_tab WHERE return_tab.active =1 group by return_tab.lot_id) as return_table on sale_table.lot_id = return_table.lot_id UNION all select (case when ws_return_table.return_unit is null then ws_sale_table.sale_unit else (ws_sale_table.sale_unit - ws_return_table.return_unit) end ) as total_sale_unit,ws_sale_table.lot_id from (SELECT (case when sum(ws_sale.sale_unit ) is null then 0 ELSE sum(ws_sale.sale_unit ) end) as sale_unit ,(case when (ws_sale.lot_id) is null then 0 else (ws_sale.lot_id) end) as lot_id FROM wp_shc_ws_sale_detail as ws_sale WHERE ws_sale.active =1 group by lot_id) as ws_sale_table left join (SELECT (case when sum(ws_return_tab.return_unit ) is null then 0 ELSE sum(ws_return_tab.return_unit ) end) as return_unit ,(case when (ws_return_tab.lot_id) is null then 0 else (ws_return_tab.lot_id) end) as lot_id FROM wp_shc_ws_return_items_details as ws_return_tab WHERE ws_return_tab.active =1 group by ws_return_tab.lot_id) as ws_return_table on ws_sale_table.lot_id = ws_return_table.lot_id) as invoice_table GROUP by lot_id) final_sale_tab on final_sale_tab.lot_id = lot_table.id ) as final_tab WHERE final_tab.id = ${id}"; 
if($data= $wpdb->get_row( $query, ARRAY_A ) ) {
			$data['success'] = 1;	
			$balance = $data['balance_stock'];
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




//Invoice Number Generate Function


function getFinancialYear( $current_date = '' ) {
	$month = date('m', strtotime($current_date));
	$year  = date('Y', strtotime($current_date));

    if( $month >= 4 ) {
    	$financial_year = $year;
    } else {
		$financial_year = ( $year - 1 );
    }
    return $financial_year;
}



function ws_create_return() {
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] = 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_ws_return_items';
	$sale_detail_table =  $wpdb->prefix.'shc_ws_return_items_details';
	$cd_table =  $wpdb->prefix.'shc_ws_cd_notes';
	$params = array();
	parse_str($_POST['data'], $params);

	$goods_query = "SELECT return_id from ${sale_table} WHERE active = 1 order by id desc LIMIT 0,1";
	$goods_return = $wpdb->get_row($goods_query);
	$good_return_exp = explode(' ',$goods_return->return_id);
	$good_return_id = $good_return_exp[1]+1;

	$sale_update = array(
		'customer_id' 			=> $params['customer_id'], 
		'inv_id' 				=> $params['invoice_id'], 
		'total_amount' 			=> $params['rtn_fsub_total'],
		'search_inv_id' 		=> $_POST['search_inv_id'],
		'financial_year'        => $_POST['year'],	
		'created_by'			=> $current_nice_name,	
	);

	$wpdb->insert($sale_table, $sale_update);
	// var_dump($wpdb->last_query);
	// die();
	$return_id  = $wpdb->insert_id;
	$data['id'] = $return_id;

	$amount = (isset($params['retail_check_box']))? $params['rtn_fsub_total'] : 0;
	$wpdb->update($sale_table, array('key_amount' 	=>$amount),array( 'id' => $return_id));
	$id_update = array(
		'return_id' 			=>  'GR '.$good_return_id,
	);

	$wpdb->update($sale_table, $id_update,array( 'id' => $return_id));

	$data['invoice_id'] = $params['inv_id'];
	$data['year'] = $_POST['year'];
	$data['search_inv_id'] = $_POST['search_inv_id'];

	foreach ($params['customer_detail'] as $s_value) {


		 if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '' && $s_value['return_qty_ret'] > 0) {

			$sale_detail_data = array(
				'return_id'				=> $return_id, 
				'sale_id' 				=> $params['inv_id'],
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['sale_qty'],
				'bal_qty' 			    => $s_value['return_bal'],
				'return_unit' 			=> $s_value['return_qty_ret'],
				'mrp' 					=> $s_value['return_mrp'],
				'amt' 					=> $s_value['return_amt'],
				'cgst' 					=> $s_value['return_cgst'],
				'sgst' 					=> $s_value['return_sgst'],
				'cgst_value' 			=> $s_value['return_cgst_value'],
				'sgst_value' 			=> $s_value['return_sgst_value'],
				'sub_total' 			=> $s_value['return_sub_total'],
				'return_reason' 		=> $s_value['return_reason'],
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
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;
	$data['success'] = 0;
	global $wpdb;
	$sale_table 		=  $wpdb->prefix.'shc_return_items';
	$sale_detail_table 	=  $wpdb->prefix.'shc_return_items_details';
	$cd_table 			=  $wpdb->prefix.'shc_cd_notes';
	$payment_return 	=  $wpdb->prefix.'shc_payment_return';
	$params = array();
	parse_str($_POST['data'], $params);	
	$goods_query = "SELECT return_id from ${sale_table} WHERE active = 1 order by id desc LIMIT 0,1";
	$goods_return = $wpdb->get_row($goods_query);
	$good_return_exp = explode(' ',$goods_return->return_id);
	$good_return_id = $good_return_exp[1]+1;
	$sale_update = array(
		'customer_id' 			=> $params['customer_id'], 
		'inv_id' 				=> $params['inv_id'], 
		'total_amount' 			=> $params['rtn_fsub_total'], 
		'search_inv_id' 		=> $_POST['search_inv_id'],
		'financial_year'        => $_POST['year'],
		'created_by'			=> $current_nice_name,
	);
	$wpdb->insert($sale_table, $sale_update);
	$return_id  = $wpdb->insert_id;
	$data['id'] = $wpdb->insert_id;
	$amount = (isset($params['retail_check_box']))? $params['rtn_fsub_total'] : 0;
	$wpdb->update($sale_table, array('key_amount' 	=>$amount),array( 'id' => $return_id));
	$id_update = array(
		'return_id' 			=>  'GR '.$good_return_id,
	);

	$wpdb->update($sale_table, $id_update,array( 'id' => $return_id));
	$data['invoice_id'] = $params['inv_id'];
	$data['year'] = $_POST['year'];
	$data['search_inv_id'] = $_POST['search_inv_id'];
	foreach ($params['customer_detail'] as $s_value) {
		 if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '' && $s_value['return_qty_ret'] > 0) {
			$sale_detail_data = array(
				'return_id'				=> $return_id, 
				'sale_id' 				=> $params['inv_id'],
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['sale_qty'],
				'bal_qty' 			    => $s_value['return_bal'],
				'return_unit' 			=> $s_value['return_qty_ret'],
				'mrp' 					=> $s_value['return_mrp'],
				'amt' 					=> $s_value['return_amt'],
				'cgst' 					=> $s_value['return_cgst'],
				'sgst' 					=> $s_value['return_sgst'],
				'igst' 					=> $s_value['return_igst'],
				'cgst_value' 			=> $s_value['return_cgst_value'],
				'sgst_value' 			=> $s_value['return_sgst_value'],
				'igst_value' 			=> $s_value['return_igst_value'],
				'cess_value' 			=> $s_value['return_cess_value'],
				'sub_total' 			=> $s_value['return_sub_total'],
				'return_reason' 		=> $s_value['return_reason'],
			);
			$wpdb->insert( $sale_detail_table, $sale_detail_data);
			$data['success'] = 1;			
		 }
	}

	foreach ($params['return_amount'] as $r_value) {
		
			$return_detail_data = array(
				'sale_id'				=> $params['inv_id'], 
				'return_id' 			=> $return_id,
				'search_id' 			=> $_POST['search_inv_id'],
				'year' 					=> $_POST['year'],
				'amount' 			    => $r_value['ret_amount'],
				'payment_type' 			=> $r_value['payment_type'],
			);
			$wpdb->insert( $payment_return, $return_detail_data);
			$data['success'] = 1;
	}
	echo json_encode($data);
	die();
	
}
add_action( 'wp_ajax_create_return', 'create_return' );
add_action( 'wp_ajax_nopriv_create_return', 'create_return' );


function update_return() {

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] = 0;
	global $wpdb;
	$sale_table 		=  $wpdb->prefix.'shc_return_items';
	$sale_detail_table 	=  $wpdb->prefix.'shc_return_items_details';
	$cd_table 			=  $wpdb->prefix.'shc_cd_notes';
	$payment_return 	=  $wpdb->prefix.'shc_payment_return';
	$params = array();

	parse_str($_POST['data'], $params);
	


	$sale_update = array(
		'customer_id' 			=> $params['customer_id'], 
		'inv_id' 				=> $params['inv_id'], 
		'total_amount' 			=> $params['rtn_fsub_total'], 
		'modified_by'			=> $current_nice_name,
	);

	$wpdb->update($sale_table, $sale_update ,array('id' =>$params['return_id']));

	$data['id'] = $params['return_id'];
//key_amount
	$amount = (isset($params['retail_check_box']))? $params['rtn_fsub_total'] : 0;
	$wpdb->update($sale_table, array('key_amount' 	=>$amount),array( 'id' => $params['return_id']));
	$wpdb->update($sale_detail_table, array('active' => 0), array('return_id' => $params['return_id']));

	foreach ($params['customer_detail'] as $s_value) {


		 if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '' && $s_value['return_qty_ret'] > 0) {

			$sale_detail_data = array(
				'return_id'				=> $params['return_id'], 
				'sale_id' 				=> $params['inv_id'],
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['sale_qty'],
				'bal_qty' 			    => $s_value['return_bal'],
				'return_unit' 			=> $s_value['return_qty_ret'],
				'mrp' 					=> $s_value['return_mrp'],
				'amt' 					=> $s_value['return_amt'],
				'cgst' 					=> $s_value['return_cgst'],
				'sgst' 					=> $s_value['return_sgst'],
				'igst' 					=> $s_value['return_igst'],
				'cgst_value' 			=> $s_value['return_cgst_value'],
				'sgst_value' 			=> $s_value['return_sgst_value'],
				'igst_value' 			=> $s_value['return_igst_value'],
				'cess_value' 			=> $s_value['return_cess_value'],
				'sub_total' 			=> $s_value['return_sub_total'],
				'return_reason' 		=> $s_value['return_reason'],
			);
			$wpdb->insert( $sale_detail_table, $sale_detail_data);
			$data['success'] = 1;

			
		 }
	}	
	$wpdb->update($payment_return, array('active' => 0), array('return_id' => $params['return_id']));
	foreach ($params['return_amount'] as $r_value) {
		
			$return_detail_data = array(
				'sale_id'				=> $params['inv_id'], 
				'return_id' 			=> $params['return_id'],
				'search_id' 			=> $_POST['search_inv_id'],
				'year' 					=> $_POST['year'],
				'amount' 			    => $r_value['ret_amount'],
				'payment_type' 			=> $r_value['payment_type'],
			);
			$wpdb->insert( $payment_return, $return_detail_data);
			$data['success'] = 1;
	}

	echo json_encode($data);
	die();
	
}
add_action( 'wp_ajax_update_return', 'update_return' );
add_action( 'wp_ajax_nopriv_update_return', 'update_return' );

function ws_update_return() {
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;
	$data['success'] 	= 0;
	global $wpdb;
	$sale_table =  $wpdb->prefix.'shc_ws_return_items';
	$sale_detail_table =  $wpdb->prefix.'shc_ws_return_items_details';
	$cd_table =  $wpdb->prefix.'shc_ws_cd_notes';
	$params = array();

	parse_str($_POST['data'], $params);
	$amount = (isset($params['retail_check_box']))? $params['rtn_fsub_total'] : 0;
	$wpdb->update($sale_table, array('key_amount' 	=>$amount),array( 'id' => $return_id));

	$sale_update = array(
		'customer_id' 			=> $params['customer_id'], 
		'inv_id' 				=> $params['invoice_id'],
		'total_amount' 			=> $params['rtn_fsub_total'], 
		'modified_by'			=> $current_nice_name,
	);

	$wpdb->update($sale_table, $sale_update ,array('id' =>$params['return_id']));


	$data['id'] = $params['return_id'];

	$wpdb->update($sale_detail_table, array('active' => 0), array('return_id' => $params['return_id']));

	foreach ($params['customer_detail'] as $s_value) {


		 if(isset($s_value['id']) && $s_value['id'] != 0 && $s_value['id'] != '' && $s_value['return_qty_ret'] > 0) {

			$sale_detail_data = array(
				'return_id'				=> $params['return_id'], 
				'sale_id' 				=> $params['inv_id'],
				'lot_id' 				=> $s_value['id'],
				'sale_unit' 			=> $s_value['sale_qty'],
				'bal_qty' 			    => $s_value['return_bal'],
				'return_unit' 			=> $s_value['return_qty_ret'],
				'mrp' 					=> $s_value['return_mrp'],
				'amt' 					=> $s_value['return_amt'],
				'cgst' 					=> $s_value['return_cgst'],
				'sgst' 					=> $s_value['return_sgst'],
				'cgst_value' 			=> $s_value['return_cgst_value'],
				'sgst_value' 			=> $s_value['return_sgst_value'],
				'sub_total' 			=> $s_value['return_sub_total'],
				'return_reason' 		=> $s_value['return_reason'],
			);
			$wpdb->insert( $sale_detail_table, $sale_detail_data);
			$data['success'] = 1;

			
		 }
	}	

	echo json_encode($data);
	die();
	
}
add_action( 'wp_ajax_ws_update_return', 'ws_update_return' );
add_action( 'wp_ajax_nopriv_ws_update_return', 'ws_update_return' );





function product_delivery() {

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$id = $_POST['id'];
	$delivery = $_POST['delivery'];
	$delivery_count = $_POST['delivery_count'];

	$lot_detail_table = $wpdb->prefix. 'shc_sale_detail';
	$currentdate_time = date('Y-m-d H:i:s');



	$delivery_data = array(
		'is_delivery' 		=> $delivery,
		'delivery_date' 	=> $currentdate_time,
		'delivery_count'  	=> $delivery_count,
		);


	$wpdb->update($lot_detail_table,$delivery_data,array('id' => $id));
var_dump($wpdb->last_query);
	//$data['success'] = 1;
	echo json_encode($data);
	die();

}
add_action( 'wp_ajax_product_delivery', 'product_delivery');
add_action( 'wp_ajax_nopriv_product_delivery', 'product_delivery');


function ws_product_delivery() {

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$id = $_POST['id'];
	$delivery = $_POST['delivery'];
	$delivery_count = $_POST['delivery_count'];

	$lot_detail_table = $wpdb->prefix. 'shc_ws_sale_detail';
	$currentdate_time = date('Y-m-d H:i:s');



	$delivery_data = array(
		'is_delivery' 		=> $delivery,
		'delivery_date' 	=> $currentdate_time,
		'delivery_count'  	=> $delivery_count,
		);


	$wpdb->update($lot_detail_table,$delivery_data,array('id' => $id));
	var_dump($wpdb->last_query);
	//$data['success'] = 1;
	echo json_encode($data);
	die();

}
add_action( 'wp_ajax_ws_product_delivery', 'ws_product_delivery');
add_action( 'wp_ajax_nopriv_ws_product_delivery', 'ws_product_delivery');

function gst_group($id = 0) {
	global $wpdb;
	$sale_table_detail = $wpdb->prefix. 'shc_ws_sale_detail';
	$sale_table       = $wpdb->prefix. 'shc_ws_sale';

	$query = "SELECT sale_details.cgst,
	 sum(sale_details.cgst_value) as sale_cgst, 
	 sum(sale_details.sgst_value) sale_sgst, 
	 sum(sale_details.sub_total) as sale_total, 
	 sum(sale_details.sale_unit) as sale_unit,
	sum(sale_details.amt) as sale_amt FROM ${sale_table} as sale 
	left join ${sale_table_detail} as sale_details on sale.`id`= sale_details.sale_id WHERE sale.active = 1 and sale_details.active = 1 and sale.id = ${id} group by sale_details.cgst";
	$data['gst_data'] = $wpdb->get_results($query);
	return $data;

}
function gst_group_retail($id = 0) {
	global $wpdb;
	$sale_table_detail = $wpdb->prefix. 'shc_sale_detail';
	$sale_table       = $wpdb->prefix. 'shc_sale';

	$query = "SELECT sale_details.cgst,
	 sum(sale_details.cgst_value) as sale_cgst, 
	 sum(sale_details.sgst_value) sale_sgst, 
	 sum(sale_details.sub_total) as sale_total, 
	 sum(sale_details.sale_unit) as sale_unit,
	sum(sale_details.amt) as sale_amt FROM ${sale_table} as sale 
	left join ${sale_table_detail} as sale_details on sale.`id`= sale_details.sale_id WHERE sale.active = 1 and sale_details.active = 1 and sale.id = ${id} group by sale_details.cgst";
	$data['gst_data'] = $wpdb->get_results($query);
	return $data;

}

function gst_group_cancel_retail($id = 0) {
	global $wpdb;
	$sale_table_detail = $wpdb->prefix. 'shc_sale_detail';
	$sale_table       = $wpdb->prefix. 'shc_sale';

	$query = "SELECT sale_details.cgst,
	 sum(sale_details.cgst_value) as sale_cgst, 
	 sum(sale_details.sgst_value) sale_sgst, 
	 sum(sale_details.sub_total) as sale_total, 
	 sum(sale_details.sale_unit) as sale_unit,
	sum(sale_details.amt) as sale_amt FROM ${sale_table} as sale 
	left join ${sale_table_detail} as sale_details on sale.`id`= sale_details.sale_id WHERE sale.cancel = 1 and sale_details.cancel = 1 and sale.id = ${id} group by sale_details.cgst";
	$data['gst_data'] = $wpdb->get_results($query);
	return $data;

}

function gst_group_cancel($id = 0) {
	global $wpdb;
	$sale_table_detail = $wpdb->prefix. 'shc_ws_sale_detail';
	$sale_table       = $wpdb->prefix. 'shc_ws_sale';

	$query = "SELECT sale_details.cgst,
	 sum(sale_details.cgst_value) as sale_cgst, 
	 sum(sale_details.sgst_value) sale_sgst, 
	 sum(sale_details.sub_total) as sale_total, 
	 sum(sale_details.sale_unit) as sale_unit,
	sum(sale_details.amt) as sale_amt FROM ${sale_table} as sale 
	left join ${sale_table_detail} as sale_details on sale.`id`= sale_details.sale_id WHERE sale.cancel = 1 and sale_details.cancel = 1 and sale.id = ${id} group by sale_details.cgst";
	$data['gst_data'] = $wpdb->get_results($query);
	return $data;

}
function getReturnCheckBox($return_id = 0){
	global $wpdb;
	$return_table = $wpdb->prefix.'shc_return_items';
	$query = "SELECT key_amount from {$return_table} WHERE id = {$return_id} and active = 1";
	$data['returnCheckBox'] = $wpdb->get_row($query);
	return $data;

}
function getReturnCheckBoxWs($return_id = 0){
	global $wpdb;
	$cd_table = $wpdb->prefix.'shc_ws_cd_notes';
	$query = "SELECT * from {$cd_table} WHERE return_id = {$return_id} and active = 1";
	$data['returnCheckBox'] = $wpdb->get_row($query);
	return $data;

}
function get_paymenttype($id = 0,$year = 0){
	global $wpdb;
	$payment_table = $wpdb->prefix.'shc_payment';
	$query = "SELECT * from {$payment_table} WHERE search_id = {$id} and year = {$year} and active = 1";
	return $data['paymentType'] = $wpdb->get_results($query);

}
function getDueDate($reference_id =0,$reference_screen='',$customer_id =0){
	global $wpdb;
	$sale_table 		= $wpdb->prefix.'shc_sale';
	$return_table 		= $wpdb->prefix.'shc_return_items';
	$payment_table  	= $wpdb->prefix.'shc_payment';
	$customer_table  	= $wpdb->prefix.'shc_customers';

	$query = "SELECT full_table.cus_id as customer_id,full_table.name as customer_name,full_table.address,full_table.mobile,
(case when  full_table.sale_id is null then 0.00 else full_table.sale_id end ) as sale_id,
(case when  full_table.search_id is null then 0.00 else full_table.search_id end ) as search_id,
(case when  full_table.year is null then 0.00 else full_table.year end ) as year,
(case when  full_table.sale_total is null then 0.00 else full_table.sale_total end ) as sale_total,
(case when  full_table.paid_amount is null then 0.00 else full_table.paid_amount end ) as paid_amount,
(case when  full_table.key_amount is null then 0.00 else full_table.key_amount end ) as key_amount,
(case when  full_table.return_amount is null then 0.00 else full_table.return_amount end ) as return_amount,
(case when  full_table.invoice_bill_bal is null then 0.00 else full_table.invoice_bill_bal end ) as invoice_bill_bal,
(case when  full_table.return_bill_bal is null then 0.00 else full_table.return_bill_bal end ) as return_bill_bal,
(case when  full_table.new_sale_total is null then 0.00 else full_table.new_sale_total end ) as new_sale_total,
(case when  full_table.final_bal is null then 0.00 else full_table.final_bal end ) as final_bal
from  
( 
    select * from (
    SELECT id as cus_id,name,mobile,address FROM ${customer_table}  WHERE active = 1
) as customer
left join 
(
	SELECT tab.*,(tab.invoice_bill_bal - tab.return_bill_bal) as final_bal  from (
    select final_tab.*, 
(final_tab.sale_total- final_tab.paid_amount) as invoice_bill_bal,
(final_tab.return_amount- final_tab.key_amount) as return_bill_bal,
(final_tab.sale_total - final_tab.return_amount ) as new_sale_total
from ( 
    select bill_table.*,
(case when return_tab.key_amount is null then 0.00 else return_tab.key_amount end) as key_amount,
(case when return_tab.return_amount is null then 0.00 else return_tab.return_amount end) as return_amount
from 
(
    SELECT sale.inv_id as sale_id,
    sale.customer_id,
    sale.search_id,
    sale.year,sale.sale_total,
    (case when payment.payment_amount is null then 0.00 else payment.payment_amount end) as paid_amount 
    from 
(
    SELECT id as inv_id,customer_id,
    `inv_id` as search_id,
    `financial_year` as year,
    `sub_total` as sale_total,`pay_to_bal` FROM ${sale_table} WHERE`active`=1 
)  as sale
left join 
( 
    select 	sale_id,sum(amount) as payment_amount from ${payment_table} WHERE active = 1 and payment_type!= 'credit' and reference_id != ${reference_id} and reference_screen ='${reference_screen}'  GROUP by sale_id
 )  as payment
on sale.inv_id = payment.sale_id
) as bill_table 
left join 
(
    SELECT inv_id,key_amount,total_amount as return_amount from {$return_table} WHERE active =  1 
) 
as return_tab 
on bill_table.sale_id = return_tab.inv_id )
as final_tab  
) as tab 
)
as full_sale_tab  
on full_sale_tab.customer_id = customer.cus_id 
) as full_table
where full_table.customer_id = ${customer_id} order by full_table.sale_id ASC";
return $wpdb->get_results($query);
//var_dump($query);
}

function getWsDueDate($reference_id =0,$reference_screen='',$customer_id =0){
	global $wpdb;
	$sale_table 		= $wpdb->prefix.'shc_ws_sale';
	$return_table 		= $wpdb->prefix.'shc_ws_return_items';
	$payment_table  	= $wpdb->prefix.'shc_ws_payment';
	$customer_table  	= $wpdb->prefix.'shc_ws_customers';

	$query = "SELECT full_table.cus_id as customer_id,full_table.name as customer_name,full_table.address,full_table.mobile,
	(case when  full_table.sale_id is null then 0.00 else full_table.sale_id end ) as sale_id,
	(case when  full_table.search_id is null then 0.00 else full_table.search_id end ) as search_id,
	(case when  full_table.year is null then 0.00 else full_table.year end ) as year,
	(case when  full_table.sale_total is null then 0.00 else full_table.sale_total end ) as sale_total,
	(case when  full_table.paid_amount is null then 0.00 else full_table.paid_amount end ) as paid_amount,
	(case when  full_table.key_amount is null then 0.00 else full_table.key_amount end ) as key_amount,
	(case when  full_table.return_amount is null then 0.00 else full_table.return_amount end ) as return_amount,
	(case when  full_table.invoice_bill_bal is null then 0.00 else full_table.invoice_bill_bal end ) as invoice_bill_bal,
	(case when  full_table.return_bill_bal is null then 0.00 else full_table.return_bill_bal end ) as return_bill_bal,
	(case when  full_table.new_sale_total is null then 0.00 else full_table.new_sale_total end ) as new_sale_total,
	(case when  full_table.final_bal is null then 0.00 else full_table.final_bal end ) as final_bal
	from  
	( 
	    select * from (
	    SELECT id as cus_id,name,mobile,address FROM ${customer_table}  WHERE active = 1
	) as customer
	left join 
	(
		SELECT tab.*,(tab.invoice_bill_bal - tab.return_bill_bal) as final_bal  from (
	    select final_tab.*, 
	(final_tab.sale_total- final_tab.paid_amount) as invoice_bill_bal,
	(final_tab.return_amount- final_tab.key_amount) as return_bill_bal,
	(final_tab.sale_total - final_tab.return_amount ) as new_sale_total
	from ( 
	    select bill_table.*,
	(case when return_tab.key_amount is null then 0.00 else return_tab.key_amount end) as key_amount,
	(case when return_tab.return_amount is null then 0.00 else return_tab.return_amount end) as return_amount
	from 
	(
	    SELECT sale.inv_id as sale_id,
	    sale.customer_id,
	    sale.search_id,
	    sale.year,sale.sale_total,
	    (case when payment.payment_amount is null then 0.00 else payment.payment_amount end) as paid_amount 
	    from 
	(
	    SELECT id as inv_id,customer_id,
	    `inv_id` as search_id,
	    `financial_year` as year,
	    `sub_total` as sale_total,`pay_to_bal` FROM ${sale_table} WHERE`active`=1 
	)  as sale
	left join 
	( 
	    select 	sale_id,sum(amount) as payment_amount from ${payment_table} WHERE active = 1 and payment_type!= 'credit' and reference_id != ${reference_id} and reference_screen ='${reference_screen}'  GROUP by sale_id
	 )  as payment
	on sale.inv_id = payment.sale_id
	) as bill_table 
	left join 
	(
	    SELECT inv_id,key_amount,total_amount as return_amount from {$return_table} WHERE active =  1 
	) 
	as return_tab 
	on bill_table.sale_id = return_tab.inv_id )
	as final_tab  
	) as tab 
	)
	as full_sale_tab  
	on full_sale_tab.customer_id = customer.cus_id 
	) as full_table
	where full_table.customer_id = ${customer_id} order by full_table.sale_id ASC";
	return $wpdb->get_results($query);
//var_dump($query);
}
function paymenttypeGroupByType($id = 0,$year = 0){
	global $wpdb;
	$payment_table = $wpdb->prefix.'shc_payment';
	$query = "SELECT payment_type,(case when payment_type = 'cash' then (sum(amount)-pay_to) else sum(amount) end)as amount from {$payment_table} WHERE search_id = {$id} and year = {$year} and active = 1 and payment_type !='credit' GROUP BY payment_type";
	$data['WithOutCredit'] = $wpdb->get_results($query);
	$query = "SELECT payment_type,sum(amount) as amount from {$payment_table} WHERE search_id = {$id} and year = {$year} and active = 1 and payment_type ='credit' GROUP BY payment_type";
	$data['WithCredit'] = $wpdb->get_results($query);
	return $data;

}
function get_wspaymenttype($id = 0,$year = 0){
	global $wpdb;
	$payment_table = $wpdb->prefix.'shc_ws_payment';
	$query = "SELECT * from {$payment_table} WHERE search_id = {$id} and year = {$year} and active = 1";
	return $data['paymentType'] = $wpdb->get_results($query);

}
function ws_paymenttypeGroupByType($id = 0,$year = 0){
	global $wpdb;
	$payment_table = $wpdb->prefix.'shc_ws_payment';
	$query = "SELECT payment_type,(case when payment_type = 'cash' then (sum(amount)-pay_to) else sum(amount) end)as amount from {$payment_table} WHERE search_id = {$id} and year = {$year} and active = 1 and payment_type !='credit' GROUP BY payment_type";
	$data['WithOutCredit'] = $wpdb->get_results($query);
	$query = "SELECT payment_type,sum(amount) as amount from {$payment_table} WHERE search_id = {$id} and year = {$year} and active = 1 and payment_type ='credit' GROUP BY payment_type";
	$data['WithCredit'] = $wpdb->get_results($query);
	return $data;

}

function DuePaid($customer_id = 0){

	global $wpdb;
	if($_POST['type'] == 'retail'){
		$sale_table 		= $wpdb->prefix.'shc_sale';
		$return_table 		= $wpdb->prefix.'shc_return_items';
		$payment_table  	= $wpdb->prefix.'shc_payment';
		$customer_table  	= $wpdb->prefix.'shc_customers';
		//$cd_notes 		= $wpdb->prefix.'shc_cd_notes';	
	} else{
		$sale_table 		= $wpdb->prefix.'shc_ws_sale';
		$return_table 		= $wpdb->prefix.'shc_ws_return_items';
		$payment_table  	= $wpdb->prefix.'shc_ws_payment';
		$customer_table  	= $wpdb->prefix.'shc_wholesale_customer';
	}
	
	$customer_id 		= ($_POST['id']) ? $_POST['id'] : $customer_id;
	$reference_id 		= ($_POST['reference_id'] != '') ? $_POST['reference_id'] : 0;
	$reference_screen 	= ($_POST['reference_screen'] != '') ? $_POST['reference_screen'] : '';
	// if($reference_id == 0){
	// 	$condition1 = "";
	// } else {
	// 	$condition1 = " AND reference_id  != ${reference_id}";
	// }
	// if($reference_screen == ''){
	// 	$condition = "";
	// } else {
	// 	$condition = "and reference_screen != '${reference_screen}'";
	// }
// 	$query = "SELECT full_table.cus_id as customer_id,full_table.name as customer_name,full_table.address,full_table.mobile,
// (case when  full_table.sale_id is null then 0.00 else full_table.sale_id end ) as sale_id,
// (case when  full_table.search_id is null then 0.00 else full_table.search_id end ) as search_id,
// (case when  full_table.year is null then 0.00 else full_table.year end ) as year,
// (case when  full_table.sale_total is null then 0.00 else full_table.sale_total end ) as sale_total,
// (case when  full_table.paid_amount is null then 0.00 else full_table.paid_amount end ) as paid_amount,
// (case when  full_table.key_amount is null then 0.00 else full_table.key_amount end ) as key_amount,
// (case when  full_table.return_amount is null then 0.00 else full_table.return_amount end ) as return_amount,
// (case when  full_table.invoice_bill_bal is null then 0.00 else full_table.invoice_bill_bal end ) as invoice_bill_bal,
// (case when  full_table.return_bill_bal is null then 0.00 else full_table.return_bill_bal end ) as return_bill_bal,
// (case when  full_table.new_sale_total is null then 0.00 else full_table.new_sale_total end ) as new_sale_total,
// (case when  full_table.final_bal is null then 0.00 else full_table.final_bal end ) as final_bal
// from  
// ( 
//     SELECT * from (
//     SELECT id as cus_id,name,mobile,address FROM ${customer_table}  WHERE active = 1
// ) as customer
// left join 
// (
// 	SELECT tab.*,(tab.invoice_bill_bal - tab.return_bill_bal) as final_bal  from (
//     select final_tab.*, 
// (final_tab.sale_total- final_tab.paid_amount) as invoice_bill_bal,
// (final_tab.return_amount- final_tab.key_amount) as return_bill_bal,
// (final_tab.sale_total - final_tab.return_amount ) as new_sale_total
// from ( 
//     select bill_table.*,
// (case when return_tab.key_amount is null then 0.00 else return_tab.key_amount end) as key_amount,
// (case when return_tab.return_amount is null then 0.00 else return_tab.return_amount end) as return_amount
// from 
// (
//     SELECT sale.inv_id as sale_id,
//     sale.customer_id,
//     sale.search_id,
//     sale.year,sale.sale_total,
//     (case when payment.payment_amount is null then 0.00 else payment.payment_amount-sale.pay_to_bal end) as paid_amount 
//     from 
// (
//     SELECT id as inv_id,customer_id,
//     `inv_id` as search_id,
//     `financial_year` as year,
//     `sub_total` as sale_total,`pay_to_bal` FROM ${sale_table} WHERE`active`=1 
// )  as sale
// left join 
// ( 
//     select 	sale_id,sum(amount) as payment_amount from ${payment_table} WHERE active = 1 and payment_type!= 'credit' ${condition1} ${condition}  GROUP by sale_id
//  )  as payment
// on sale.inv_id = payment.sale_id
// ) as bill_table 
// left join 
// (
//     SELECT inv_id,key_amount,total_amount as return_amount from {$return_table} WHERE active =  1 
// ) 
// as return_tab 
// on bill_table.sale_id = return_tab.inv_id )
// as final_tab  
// ) as tab 
// )
// as full_sale_tab  
// on full_sale_tab.customer_id = customer.cus_id 
// ) as full_table
// where full_table.customer_id = ${customer_id} order by full_table.sale_id ASC "  ;
	$query = "SELECT sale.*,
(case when ret.return_sale is null then 0 ELSE ret.return_sale end ) as return_sale,
(case when  (sale.sale_total - ret.return_sale) is null then sale.sale_total else (sale.sale_total - ret.return_sale) end ) as final_sale_total,
(case when ret.return_amount is null then 0 ELSE ret.return_amount end ) as return_amount,
(case when payment.paid_amount is null then 0 ELSE payment.paid_amount end ) as paid_amount,
(
	sale.pay_to_bal
    +
    (case when ret.return_amount is null then 0 ELSE ret.return_amount end )
    +
    (case when  (sale.sale_total - ret.return_sale) is null then sale.sale_total else (sale.sale_total - ret.return_sale) end )
    -
    (case when payment.paid_amount is null then 0 ELSE payment.paid_amount end )
) as current_due

from 
(
    SELECT s.id as sale_id, s.customer_id, (s.sub_total) as sale_total,s.pay_to_bal, s.created_at as sale_date,s.inv_id,s.financial_year FROM ${sale_table} as s WHERE s.customer_id = ${customer_id} and s.active = 1 
) as sale
left join 
(
    SELECT r.inv_id as sale_id,sum(r.total_amount) as return_sale,sum(r.key_amount) as return_amount FROM ${return_table} as r WHERE r.active = 1 and r.customer_id =${customer_id} GROUP by r.inv_id
) as ret
on sale.sale_id = ret.sale_id 

left join 

(
    SELECT p.sale_id as sale_id,sum(p.amount) as paid_amount FROM ${payment_table} as p WHERE p.customer_id = ${customer_id} and p.active = 1 and p.payment_type != 'credit' GROUP by p.sale_id
) as payment
on sale.sale_id = payment.sale_id 

order by sale.sale_id asc";

$data['due_data'] = $wpdb->get_results($query);
echo json_encode($data);
die();

}
add_action( 'wp_ajax_DuePaid', 'DuePaid');
add_action( 'wp_ajax_nopriv_DuePaid', 'DuePaid');


function DuePaidUpdate(){

	global $wpdb;
	if($_POST['type'] == 'retail'){
		$sale_table 		= $wpdb->prefix.'shc_sale';
		$return_table 		= $wpdb->prefix.'shc_return_items';
		$payment_table  	= $wpdb->prefix.'shc_payment';
		$customer_table  	= $wpdb->prefix.'shc_customers';
		//$cd_notes 		= $wpdb->prefix.'shc_cd_notes';	
	} else{
		$sale_table 		= $wpdb->prefix.'shc_ws_sale';
		$return_table 		= $wpdb->prefix.'shc_ws_return_items';
		$payment_table  	= $wpdb->prefix.'shc_ws_payment';
		$customer_table  	= $wpdb->prefix.'shc_wholesale_customer';
	}
	
	$customer_id 		= ($_POST['id']) ? $_POST['id'] : $customer_id;
	$reference_id 		= ($_POST['reference_id'] != '') ? $_POST['reference_id'] : 0;
	$reference_screen 	= ($_POST['reference_screen'] != '') ? $_POST['reference_screen'] : '';

	$query = "SELECT sale.*,
(case when ret.return_sale is null then 0 ELSE ret.return_sale end ) as return_sale,
(case when  (sale.sale_total - ret.return_sale) is null then sale.sale_total else (sale.sale_total - ret.return_sale) end ) as final_sale_total,
(case when ret.return_amount is null then 0 ELSE ret.return_amount end ) as return_amount,
(case when payment.paid_amount is null then 0 ELSE payment.paid_amount end ) as paid_amount,
(case when payment.due_paid is null then 0 ELSE payment.due_paid end ) as credit_paid,
(case when payment.bill_paid is null then 0 ELSE payment.bill_paid end ) as bill_paid,
(
	
    (case when ret.return_amount is null then 0 ELSE ret.return_amount end )
    +
    (case when  (sale.sale_total - ret.return_sale) is null then sale.sale_total else (sale.sale_total - ret.return_sale) end )
    -
    (case when payment.paid_amount is null then 0 ELSE payment.paid_amount end )
) as due_bal,
(
    (case when ret.return_amount is null then 0 ELSE ret.return_amount end )
    +
    (case when  (sale.sale_total - ret.return_sale) is null then sale.sale_total else (sale.sale_total - ret.return_sale) end )
    -
    (case when payment.paid_amount is null then 0 ELSE payment.paid_amount end )
    + 
    (case when payment.due_paid is null then 0 ELSE payment.due_paid end ) 
) as current_due,

(case when payment.reference_id is null then 0 ELSE payment.reference_id end ) as reference_id,
(case when payment.reference_screen is null then '' ELSE payment.reference_screen end ) as reference_screen
from 
 (
     SELECT p.sale_id as sale_id,sum(p.amount) as paid_amount,p.reference_id,p.reference_screen,
     sum(case when  (reference_screen= 'due_screen' and reference_id = ${reference_id})  then amount else 0 end ) as due_paid,
     sum(case when (reference_screen= 'due_screen' or reference_screen= 'billing_screen')   then amount else 0 end ) as bill_paid FROM ${payment_table} as p WHERE p.customer_id = ${customer_id} and p.active = 1 and p.payment_type != 'credit'  group by sale_id  ) as payment
left join 
( SELECT s.id as sale_id, s.customer_id, (s.sub_total) as sale_total,s.pay_to_bal, s.created_at as sale_date,s.inv_id,s.financial_year FROM ${sale_table} as s WHERE s.customer_id = ${customer_id} and s.active = 1 
) as sale 
on sale.sale_id = payment.sale_id
left join 
(
    SELECT r.inv_id as sale_id,sum(r.total_amount) as return_sale,sum(r.key_amount) as return_amount FROM ${return_table} as r WHERE r.active = 1 and r.customer_id =${customer_id} GROUP by r.inv_id
) as ret
on payment.sale_id = ret.sale_id WHERE
(
	(case when ret.return_amount is null then 0 ELSE ret.return_amount end )
    +
    (case when  (sale.sale_total - ret.return_sale) is null then sale.sale_total else (sale.sale_total - ret.return_sale) end )
    -
    (case when payment.paid_amount is null then 0 ELSE payment.paid_amount end )
    + 
    (case when payment.due_paid is null then 0 ELSE payment.due_paid end ) 
) > 0

order by sale.sale_id asc ";
//var_dump($query);
$data['due_data'] = $wpdb->get_results($query);
echo json_encode($data);
die();

}
add_action( 'wp_ajax_DuePaidUpdate', 'DuePaidUpdate');
add_action( 'wp_ajax_nopriv_DuePaidUpdate', 'DuePaidUpdate');

function ReturnReason($lot_id = 0,$return_id = 0){
	global $wpdb;
	$return_table 	= $wpdb->prefix.'shc_return_items_details';
	$query 			= "SELECT return_reason from ${return_table} WHERE lot_id = ${lot_id} and return_id = ${return_id} and active = 1";
	$data 			= $wpdb->get_row($query);
	
	return $data;
}
function ReturnReasonWs($lot_id = 0,$return_id = 0){
	global $wpdb;
	$return_table 	= $wpdb->prefix.'shc_ws_return_items_details';
	$query 			= "SELECT return_reason from ${return_table} WHERE lot_id = ${lot_id} and return_id = ${return_id} and active = 1";
	$data 			= $wpdb->get_row($query);
	return $data;
}


function isWholesaleRate($sale_id = 0){
	global $wpdb;
	$sale_details_table 	= $wpdb->prefix.'shc_sale_detail';
	$query 			= "SELECT 
sum( case when dis_amt = ws_amt  then 1 else 0 end) as isWholesale_rate 
from 
( 
    SELECT `discount` as dis_amt,`wholesale_price` as ws_amt,sale_id FROM {$sale_details_table} WHERE `sale_id` = $sale_id and `active`=1 
) 
    as sale_details GROUP by sale_details.sale_id";

	$data 			= $wpdb->get_row($query);
	return $data;
}


function getReturnAmountInPaymentMode($inv_id = 0) {
    global $wpdb;

    $credit_payment 		= $wpdb->prefix.'shc_payment';
    $return_payment         = $wpdb->prefix.'shc_payment_return';
     $sale_table 			= $wpdb->prefix.'shc_sale';
    $query 					= "SELECT (case when return_payment.amount is null then paid_table.bill_amount else (paid_table.bill_amount - return_payment.amount ) end) as balance_paid,paid_table.sale_id,paid_table.payment_type  from (
    SELECT paid.sale_id,paid.payment_type,(case when payment_type='cash' then paid.bill_amount - bill.pay_to_bal ELSE paid.bill_amount end) as bill_amount 
    FROM (
    	SELECT sum(`amount`) as bill_amount,payment_type,`sale_id` FROM ${credit_payment} WHERE `active`=1 and `sale_id` = {$inv_id} 
    GROUP by `payment_type` 
    ) as paid
    left join 
    (SELECT id,pay_to_bal,pay_to_check FROM ${sale_table} WHERE `active`=1 and `id` = {$inv_id} ) as bill
    on bill.id = paid.sale_id
) as paid_table
left join
(
    SELECT sum(amount) as amount,payment_type FROM ${return_payment} WHERE active  = 1 and sale_id = {$inv_id} GROUP by payment_type
) as return_payment 
on paid_table.payment_type = return_payment.payment_type ORDER BY FIELD(paid_table.payment_type, 'credit') DESC";

    $data['main_tab'] 		= $wpdb->get_results($query);

    return $data;
}

function getReturnAmountInPaymentModeUpdate($inv_id = 0,$return_id = 0) {
    global $wpdb;
    $credit_payment 		= $wpdb->prefix.'shc_payment';
    $return_payment         = $wpdb->prefix.'shc_payment_return';
    $sale_table 			= $wpdb->prefix.'shc_sale';
    $query 					= "SELECT (case when return_payment.amount is null then paid_table.bill_amount else (paid_table.bill_amount - return_payment.amount ) end) as balance_paid,paid_table.sale_id,paid_table.payment_type  from (
    SELECT paid.sale_id,paid.payment_type,(case when payment_type='cash' then paid.bill_amount - bill.pay_to_bal ELSE paid.bill_amount end) as bill_amount 
    FROM (
    	SELECT sum(`amount`) as bill_amount,payment_type,`sale_id` FROM ${credit_payment} WHERE `active`=1 and `sale_id` = {$inv_id} 
    GROUP by `payment_type` 
    ) as paid
    left join 
    (SELECT id,pay_to_bal,pay_to_check FROM ${sale_table} WHERE `active`=1 and `id` = {$inv_id} ) as bill
    on bill.id = paid.sale_id
) as paid_table
left join
(
    SELECT sum(amount) as amount,payment_type FROM ${return_payment} WHERE active  = 1  and return_id !={$return_id}  and sale_id = {$inv_id} GROUP by payment_type
) as return_payment 
on paid_table.payment_type = return_payment.payment_type ORDER BY FIELD(paid_table.payment_type, 'credit') DESC";

    $data['main_tab'] 		= $wpdb->get_results($query);

    return $data;

}

function getDueAmountInReturnData($inv_id = 0){
	global $wpdb;
	$credit_payment 		= $wpdb->prefix.'shc_payment_return';
	$query 	= "SELECT sum(amount) as amount,sale_id FROM ${credit_payment} WHERE `active`=1 and `sale_id`={$inv_id}  GROUP by sale_id";
	$data 	= $wpdb->get_row($query);
	return $data;

}

function getDueAmountInReturnDataIndividual(){
	global $wpdb;
	$credit_payment 		= $wpdb->prefix.'shc_payment_return';
	$query 	= "SELECT sum(amount) as amount,payment_type FROM ${credit_payment} WHERE `active`=1 GROUP by payment_type";
	$data 	= $wpdb->get_results($query);
	return $data;

}


?>

