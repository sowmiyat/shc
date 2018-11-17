<?php
add_action('admin_menu', 'admin_menu_register');
function admin_menu_register(){


global $src_capabilities;


	add_menu_page(
	    __( 'Products', 'shc'),
	    'Products',
	    $src_capabilities['lots']['permission']['lot_list'],
	    'list_lots',
	    'list_lots',
	    'dashicons-images-alt',
	    2
	);
	add_submenu_page('list_lots', 'Products List', 'Product List', $src_capabilities['lots']['permission']['lot_list'], 'list_lots', 'list_lots' );
	add_submenu_page('list_lots', 'Add Product', 'Add Product', $src_capabilities['lots']['permission']['add_lot'], 'add_lot', 'add_lot' );



	add_menu_page(
	    __( 'Stock', 'shc'),
	    'Stock',
	    $src_capabilities['stocks']['permission']['stock_list'],
	    'list_stocks',
	    'list_stocks',
	    'dashicons-list-view',
	    3
	);
	
	add_submenu_page('list_stocks', 'Stock Added List', 'Stock Added List', $src_capabilities['stocks']['permission']['stock_list'], 'list_stocks', 'list_stocks' );
	add_submenu_page('list_stocks', 'Stock Total List', 'Stock Total List', $src_capabilities['stocks']['permission']['total_stock_list'], 'total_stock_list', 'total_stock_list' );
	add_submenu_page('list_stocks', 'Add Stock', 'Add Stock', $src_capabilities['stocks']['permission']['add_stock'], 'add_stocks', 'add_stocks' );


	add_menu_page(
	    __( 'Retail Billing', 'shc'),
	    'Retail Billing',
	    $src_capabilities['billing']['permission']['billing_list'],
	    'billing_list',
	    'billing_list',
	    'dashicons-cart',
	    7
	);
	add_submenu_page('billing_list', 'Billing List', 'Billing List', $src_capabilities['billing']['permission']['billing_list'], 'billing_list', 'billing_list' );
	add_submenu_page('billing_list', 'New Billing', 'New Billing', $src_capabilities['billing']['permission']['add_billing'], 'new_billing', 'new_billing' );
	add_submenu_page('billing_list', '', '', $src_capabilities['billing']['permission']['billing_list'], 'invoice', 'invoice' );
	add_submenu_page('billing_list', '', '', $src_capabilities['billing']['permission']['cancel_invoice'], 'cancel_invoice_view', 'cancel_invoice_view' );
	add_submenu_page('billing_list', 'Return Items', 'Return Items', $src_capabilities['billing']['permission']['add_return'], 'return_items', 'return_items' );
	add_submenu_page('billing_list', 'Return Items List', 'Return Items List', $src_capabilities['billing']['permission']['return_list'], 'return_items_list', 'return_items_list' );
	add_submenu_page('billing_list', '', '', $src_capabilities['billing']['permission']['return_list'], 'return_items_view', 'return_items_view' );
		add_submenu_page('billing_list', 'Delivery List', 'Delivery List', $src_capabilities['billing']['permission']['cancel_invoice'], 'delivery_list', 'delivery_list' );
	add_submenu_page('billing_list', 'Cancel Invoice list', 'Cancel Invoice list', $src_capabilities['billing']['permission']['cancel_invoice'], 'cancel_invoice', 'cancel_invoice' );
	// add_submenu_page('billing_list', 'Cancel Return Items', 'Cancel Return Items', $src_capabilities['billing']['permission']['cancel_return_items'], 'cancel_return_items', 'cancel_return_items' );
	// add_submenu_page('billing_list', '', '', $src_capabilities['billing']['permission']['cancel_return_items'], 'cancel_return_items_view', 'cancel_return_items_view' );

	add_menu_page(
	    __( 'Wholesale Billing', 'shc'),
	    'Wholesale Billing',
	    $src_capabilities['billing']['permission']['ws_billing_list'],
	    'ws_billing_list',
	    'ws_billing_list',
	    'dashicons-cart',
	    5
	);
	add_submenu_page('ws_billing_list', 'Billing List', 'Billing List', $src_capabilities['billing']['permission']['ws_billing_list'], 'ws_billing_list', 'ws_billing_list' );
	add_submenu_page('ws_billing_list', 'New Billing', 'New Billing', $src_capabilities['billing']['permission']['ws_add_billing'], 'ws_new_billing', 'ws_new_billing' );
	add_submenu_page('ws_billing_list', '', '', $src_capabilities['billing']['permission']['ws_billing_list'], 'ws_invoice', 'ws_invoice' );
	add_submenu_page('ws_billing_list', '', '', $src_capabilities['billing']['permission']['ws_cancel_invoice'], 'ws_cancel_invoice_view', 'ws_cancel_invoice_view' );
	add_submenu_page('ws_billing_list', 'Return Items', 'Return Items', $src_capabilities['billing']['permission']['ws_add_return'], 'ws_return_items', 'ws_return_items' );
	add_submenu_page('ws_billing_list', 'Return Items List', 'Return Items List', $src_capabilities['billing']['permission']['ws_return_list'], 'ws_return_items_list', 'ws_return_items_list' );
	add_submenu_page('ws_billing_list', '', '', $src_capabilities['billing']['permission']['ws_return_list'], 'ws_return_items_view', 'ws_return_items_view' );
	add_submenu_page('ws_billing_list', 'Cancel Invoice list', 'Cancel Invoice list', $src_capabilities['billing']['permission']['ws_cancel_invoice'], 'ws_cancel_invoice', 'ws_cancel_invoice' );
	// add_submenu_page('ws_billing_list', 'Cancel Return Items', 'Cancel Return Items', $src_capabilities['billing']['permission']['ws_cancel_return_items'], 'ws_cancel_return_items', 'ws_cancel_return_items' );
	// add_submenu_page('ws_billing_list', '', '', $src_capabilities['billing']['permission']['ws_cancel_return_items'], 'ws_cancel_return_items_view', 'ws_cancel_return_items_view' );


	add_menu_page(
	    __( 'Retail Customers', 'shc'),
	    'Retail Customers',
	    $src_capabilities['customers']['permission']['customer_list'],
	    'customer_list',
	    'customer_list',
	    'dashicons-id',
	    6
	);
	add_submenu_page('customer_list', 'Customer List', 'Customer List', $src_capabilities['customers']['permission']['customer_list'], 'customer_list', 'customer_list' );
	add_submenu_page('customer_list', 'New Customer', 'New Customer', $src_capabilities['customers']['permission']['add_customer'], 'new_customer', 'new_customer' );
	

	add_menu_page(
	    __( 'Wholesale Customers', 'shc'),
	    'Wholesale Customers',
	    $src_capabilities['customers']['permission']['ws_customer_list'],
	    'wholesale_customer',
	    'wholesale_customer',
	    'dashicons-id',
	    4
	);
	add_submenu_page('wholesale_customer', 'Customer List', 'Customer List', $src_capabilities['customers']['permission']['ws_customer_list'], 'wholesale_customer', 'wholesale_customer' );
	add_submenu_page('wholesale_customer', 'New Customer', 'New Customer', $src_capabilities['customers']['permission']['add_ws_customer'], 'new_wholesale_customer', 'new_wholesale_customer' );
	
	add_menu_page(
	    __( 'Admin Users', 'src'),
	    'Admins',
	    $src_capabilities['admin_user']['permission']['add_admin'],
	    'add_admin',
	    'add_admin',
	    'dashicons-businessman',
	    12
	);
	add_submenu_page('add_admin', 'New Admin User', 'New Admin User', $src_capabilities['admin_user']['permission']['add_admin'], 'add_admin', 'add_admin' );
	add_submenu_page('add_admin', 'Admin Users List', 'Admin Users List', $src_capabilities['admin_user']['permission']['admin_list'], 'list_admin_users', 'list_admin_users' );

	add_menu_page(
	    __( 'Admin Roles', 'src'),
	    'Roles',
	    $src_capabilities['roles']['permission']['add_roles'],
	    'add_admin_role',
	    'add_admin_role',
	    'dashicons-awards',
	    13
	);
	add_submenu_page('add_admin_role', 'New Role', 'New Role', $src_capabilities['roles']['permission']['add_roles'], 'add_admin_role', 'add_admin_role' );
	add_submenu_page('add_admin_role', 'Role List', 'Role List', $src_capabilities['roles']['permission']['role_list'], 'list_roles', 'list_roles' );


	add_menu_page(
	    __( 'Stock Report', 'src'),
	    'Report',
	     $src_capabilities['report']['permission']['stock_report'],
	    'list_report',
	    'list_report',
	    'dashicons-awards',
	    11
	);
	add_submenu_page('list_report', 'Stock Report', 'Stock Report',  $src_capabilities['report']['permission']['stock_report'], 'list_report', 'list_report' );
	add_submenu_page('list_report', 'Goods Return Report', 'Goods Return Report',  $src_capabilities['report']['permission']['return_report'], 'list_return', 'list_return' );
	add_submenu_page('list_report', 'Accountant Report', 'Accountant Report',  $src_capabilities['report']['permission']['acc_report'], 'list_report_account', 'list_report_account' );

	add_menu_page(
	    __( 'Profile', 'src'),
	    'Profile',
	    $src_capabilities['admin_user']['permission']['add_admin'],
	    'add_profile',
	    'add_profile',
	    'dashicons-businessman',
	    9
	);
	add_submenu_page('add_profile', 'Add Profile', 'Add Profile', $src_capabilities['admin_user']['permission']['add_admin'], 'add_profile', 'add_profile' );
	add_menu_page(
	    __( 'Bank Details', 'src'),
	    'Bank Details',
	    $src_capabilities['admin_user']['permission']['add_admin'],
	    'add_netbank',
	    'add_netbank',
	    'dashicons-businessman',
	    10
	);
	add_submenu_page('add_netbank', 'Add Bank Details', 'Add Bank Details', $src_capabilities['admin_user']['permission']['add_admin'], 'add_netbank', 'add_netbank' );
	add_menu_page(
	    __( 'Bill Due', 'shc'),
	    'Bill Due',
	    $src_capabilities['customers']['permission']['ws_customer_list'],
	    'credit_debit',
	    'credit_debit',
	    'dashicons-id',
	    8
	);
	add_submenu_page('credit_debit', 'Paid Bill Due List', 'Paid Bill Due List', $src_capabilities['customers']['permission']['ws_customer_list'], 'credit_debit', 'credit_debit' );
	add_submenu_page('credit_debit', 'Pay Bill Due', 'Pay Bill Due', $src_capabilities['customers']['permission']['add_ws_customer'], 'add_credit_debit', 'add_credit_debit' );	

}

//<----  Lot------>
function list_lots() {
	require 'lots/listing/lot-list.php';
}
function add_lot() {
    require 'lots/add-lot.php';
}

//<-  Stock------>
function list_stocks() {
	require 'stocks/listing/stock-list.php';
}
function total_stock_list() {
	require 'stocks/listing/stock-list-total.php';
}
function add_stocks() {
	require 'stocks/add-stock.php';
}


//<------- Retail Biling----->
function billing_list() {
    require 'billing/listing/billing-list.php';
}
function new_billing() {
	require 'billing/add-billing.php';
}

function invoice() {
	require 'billing/invoice.php';
}

function return_items(){
	require 'billing/return_invoice.php';
}

function return_items_list(){
	require 'billing/return_invoice_list.php';
}
function return_items_view() {
	require 'billing/return_invoice_view.php';
}
function delivery_list() {
	require 'billing/listing/delivery_list.php';
}
function cancel_invoice() {
	require 'billing/listing/cancel-billing-list.php';
}

function cancel_invoice_view() {
	require 'billing/cancel_view.php';
}

function cancel_return_items(){
	require 'billing/listing/return-cancel-billing-list.php';
}

function cancel_return_items_view(){
	require 'billing/cancel_return_invoice_view.php';
}
//<------- Wholesale Biling----->



function ws_billing_list() {
    require 'billing/listing/ws-billing-list.php';
}
function ws_new_billing() {
	require 'billing/add-ws-billing.php';
}

function ws_invoice() {
	require 'billing/ws-invoice.php';
}

function ws_return_items() {
	require 'billing/ws_return_invoice.php';
}
function ws_return_items_list() {
	require 'billing/ws_return_invoice_list.php';
}
function ws_return_items_view() {
	require 'billing/ws_return_invoice_view.php';
}
function ws_cancel_invoice(){
	require 'billing/listing/ws-cancel-billing-list.php';
}

function ws_cancel_invoice_view(){
	require 'billing/ws_cancel_view.php';
}

function ws_cancel_return_items(){
	require 'billing/listing/ws-return-cancel-billing-list.php';	
}

function ws_cancel_return_items_view(){
	require 'billing/ws_cancel_return_invoice_view.php';
}

//<---- Customer ------->

function customer_list() {
	require 'customer/listing/customer-list.php';
}
function new_customer() {
	require 'customer/add-customer.php';
}

function wholesale_customer() {
	require 'customer/listing/wholesale-customer-list.php';
}
function new_wholesale_customer() {
	require 'customer/add-wholesale-customer.php';
}

//<------- Admin ---->

function add_admin() {
    require 'users/add-admin.php';
}
function list_admin_users() {
    require 'users/listing/user-list.php';
}


//<----- Role------>
function add_admin_role() {
    require 'roles/add-role.php';
}
function list_roles() {
    require 'roles/listing/role-list.php';
}


//<------ Report-------->
function list_report() {
    require 'report/listing/stock-list.php';
}

function list_return() {
    require 'report/listing/return-list.php';
}

function list_report_account() {
	 require 'report/listing/stock-list-accountant.php';
}

//<------ Profile ----->
function add_profile(){
	 require 'profile/add_profile.php';
}

//<------ Profile ----->
function add_netbank(){
	 require 'netbank/add_netbank.php';
}

//<---- credit debit --->
function credit_debit() {

    require 'creditdebit/listing/creditdebit_list.php';

}

function add_credit_debit() {
     require 'creditdebit/add_creditdebit.php';
}


// SELECT * FROM
// 	(
// 		SELECT 
// 		s.id,
// 	    s.customer_id,
// 	    s.inv_id,
// 	    s.financial_year,

// 	    ( CASE WHEN (s.sub_total) IS NULL THEN 0.00 ELSE s.sub_total END ) as sale_total,
// 	    ( CASE WHEN (payment.total_paid) IS NULL THEN 0.00 ELSE payment.total_paid END ) as total_paid,
// 	    ( CASE WHEN (ret.return_total) IS NULL THEN 0.00 ELSE ret.return_total END ) as return_total,
// 		( CASE WHEN (s.pay_to_bal) IS NULL THEN 0.00 ELSE SUM(s.pay_to_bal) END ) as sale_to_pay,
// 	    ( CASE WHEN (ret.return_to_pay) IS NULL THEN 0.00 ELSE ret.return_to_pay END ) as return_to_pay,
// 		( ( CASE WHEN s.sub_total IS NULL THEN 0.00 ELSE s.sub_total END ) - ( CASE WHEN ret.return_total IS NULL THEN 0.00 ELSE ret.return_total END ) ) as actual_sale,
// 		( ( CASE WHEN payment.total_paid IS NULL THEN 0.00 ELSE payment.total_paid END ) - ( ( CASE WHEN s.pay_to_bal IS NULL THEN 0.00 ELSE s.pay_to_bal END ) + ( CASE WHEN ret.return_to_pay IS NULL THEN 0.00 ELSE ret.return_to_pay END )) ) as actual_paid,
// 	    (
// 	    	( ( CASE WHEN s.sub_total IS NULL THEN 0.00 ELSE s.sub_total END ) - ( CASE WHEN ret.return_total IS NULL THEN 0.00 ELSE ret.return_total END ) )
// 	        -
// 	        ( ( CASE WHEN payment.total_paid IS NULL THEN 0.00 ELSE payment.total_paid END ) - ( ( CASE WHEN s.pay_to_bal IS NULL THEN 0.00 ELSE s.pay_to_bal END ) + ( CASE WHEN ret.return_to_pay IS NULL THEN 0.00 ELSE ret.return_to_pay END )) )
// 	    ) as customer_pending,

// 	    ( CASE WHEN (screen.current_screen_paid) IS NULL THEN 0.00 ELSE SUM(screen.current_screen_paid) END ) as current_screen_paid
	    
// 	    FROM
// 		wp_shc_sale as s 
	    
// 		LEFT JOIN 
// 		( 
// 			SELECT  
// 		  	( CASE WHEN (p.amount) IS NULL THEN 0.00 ELSE SUM(p.amount) END ) as total_paid,
// 		  	p.sale_id as payment_sale_id
// 		  	FROM wp_shc_payment as p WHERE p.payment_type != 'credit' AND p.active = 1 AND p.customer_id = 8 GROUP BY p.sale_id
// 		) as payment
// 		ON s.id = payment.payment_sale_id

// 		LEFT JOIN 
// 		( 
// 			SELECT  
// 		  	( CASE WHEN (scr.amount) IS NULL THEN 0.00 ELSE SUM(scr.amount) END ) as current_screen_paid,
// 		  	scr.sale_id as screen_sale_id
// 		  	FROM wp_shc_payment as scr WHERE scr.payment_type != 'credit' AND scr.reference_screen = 'billing_screen'  AND reference_id = 90 AND scr.active = 1 AND scr.customer_id = 1 GROUP BY scr.sale_id
// 		) as screen
// 		ON s.id = screen.screen_sale_id
// 		LEFT JOIN 
// 		(
// 		  	SELECT 
// 		  	( CASE WHEN SUM(r.total_amount) IS NULL THEN 0.00 ELSE SUM(r.total_amount) END ) as return_total, 
// 		  	( CASE WHEN SUM(r.key_amount) IS NULL THEN 0.00 ELSE SUM(r.key_amount) END ) as return_to_pay,
// 		  	r.inv_id as return_sale_id
// 		  	FROM wp_shc_return_items as r WHERE r.active = 1 AND r.customer_id = 8 GROUP BY r.inv_id
// 		) as ret
// 		ON s.id = ret.return_sale_id WHERE s.customer_id = 8 GROUP BY s.id
// 	) as full_table WHERE 1 = 1 AND full_table.customer_pending > 0

?>