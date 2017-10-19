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
	    8
	);
	
	add_submenu_page('list_stocks', 'Stock Added List', 'Stock Added List', $src_capabilities['stocks']['permission']['stock_list'], 'list_stocks', 'list_stocks' );
	add_submenu_page('list_stocks', 'Stock Total List', 'Stock Total List', $src_capabilities['stocks']['permission']['stock_list'], 'total_stock_list', 'total_stock_list' );
	add_submenu_page('list_stocks', 'Add Stock', 'Add Stock', $src_capabilities['stocks']['permission']['add_stock'], 'add_stocks', 'add_stocks' );


	add_menu_page(
	    __( 'Retail Billing', 'shc'),
	    'Retail Billing',
	    $src_capabilities['billing']['permission']['billing_list'],
	    'billing_list',
	    'billing_list',
	    'dashicons-cart',
	    9
	);
	add_submenu_page('billing_list', 'Billing List', 'Billing List', $src_capabilities['billing']['permission']['billing_list'], 'billing_list', 'billing_list' );
	add_submenu_page('billing_list', 'New Billing', 'New Billing', $src_capabilities['billing']['permission']['add_billing'], 'new_billing', 'new_billing' );
	add_submenu_page('billing_list', 'Invoice', 'Invoice', $src_capabilities['billing']['permission']['add_billing'], 'invoice', 'invoice' );
	add_submenu_page('billing_list', 'Return Items', 'Return Items', $src_capabilities['billing']['permission']['add_billing'], 'return_items', 'return_items' );
	add_submenu_page('billing_list', 'Return Items List', 'Return Items List', $src_capabilities['billing']['permission']['add_billing'], 'return_items_list', 'return_items_list' );
	add_submenu_page('billing_list', '', '', $src_capabilities['billing']['permission']['add_billing'], 'return_items_view', 'return_items_view' );
	add_menu_page(
	    __( 'WS Billing', 'shc'),
	    'WS Billing',
	    $src_capabilities['billing']['permission']['billing_list'],
	    'ws_billing_list',
	    'ws_billing_list',
	    'dashicons-cart',
	    8
	);
	add_submenu_page('ws_billing_list', 'Billing List', 'Billing List', $src_capabilities['billing']['permission']['billing_list'], 'ws_billing_list', 'ws_billing_list' );
	add_submenu_page('ws_billing_list', 'New Billing', 'New Billing', $src_capabilities['billing']['permission']['add_billing'], 'ws_new_billing', 'ws_new_billing' );
	add_submenu_page('ws_billing_list', 'Invoice', 'Invoice', $src_capabilities['billing']['permission']['add_billing'], 'ws_invoice', 'ws_invoice' );
	add_submenu_page('ws_billing_list', 'Return Items', 'Return Items', $src_capabilities['billing']['permission']['add_billing'], 'ws_return_items', 'ws_return_items' );
	add_submenu_page('ws_billing_list', 'Return Items List', 'Return Items List', $src_capabilities['billing']['permission']['add_billing'], 'ws_return_items_list', 'ws_return_items_list' );
	add_submenu_page('ws_billing_list', '', '', $src_capabilities['billing']['permission']['add_billing'], 'ws_return_items_view', 'ws_return_items_view' );


	add_menu_page(
	    __( 'Retail Customers', 'shc'),
	    'Retail Customers',
	    $src_capabilities['customers']['permission']['customer_list'],
	    'customer_list',
	    'customer_list',
	    'dashicons-id',
	    8
	);
	add_submenu_page('customer_list', 'Customer List', 'Customer List', $src_capabilities['customers']['permission']['customer_list'], 'customer_list', 'customer_list' );
	add_submenu_page('customer_list', 'New Customer', 'New Customer', $src_capabilities['customers']['permission']['add_customer'], 'new_customer', 'new_customer' );
	

	add_menu_page(
	    __( 'WS Customers', 'shc'),
	    'WS Customers',
	    $src_capabilities['customers']['permission']['customer_list'],
	    'wholesale_customer',
	    'wholesale_customer',
	    'dashicons-id',
	    8
	);
	add_submenu_page('wholesale_customer', 'Customer List', 'Customer List', $src_capabilities['customers']['permission']['customer_list'], 'wholesale_customer', 'wholesale_customer' );
	add_submenu_page('wholesale_customer', 'New Customer', 'New Customer', $src_capabilities['customers']['permission']['add_customer'], 'new_wholesale_customer', 'new_wholesale_customer' );
	
	add_menu_page(
	    __( 'Admin Users', 'src'),
	    'Admins',
	    $src_capabilities['admin_user']['permission']['add_admin'],
	    'admin_users',
	    'add_admin',
	    'dashicons-businessman',
	    9
	);
	add_submenu_page('admin_users', 'New Admin User', 'New Admin User', $src_capabilities['admin_user']['permission']['add_admin'], 'add_admin', 'add_admin' );
	add_submenu_page('admin_users', 'Admin Users List', 'Admin Users List', $src_capabilities['admin_user']['permission']['admin_list'], 'list_admin_users', 'list_admin_users' );

	add_menu_page(
	    __( 'Admin Roles', 'src'),
	    'Roles',
	    $src_capabilities['roles']['permission']['add_roles'],
	    'user_roles',
	    'add_role',
	    'dashicons-awards',
	    9
	);
	add_submenu_page('user_roles', 'New Role', 'New Role', $src_capabilities['roles']['permission']['add_roles'], 'add_admin_role', 'add_admin_role' );
	add_submenu_page('user_roles', 'Role List', 'Role List', $src_capabilities['roles']['permission']['role_list'], 'list_roles', 'list_roles' );


	add_menu_page(
	    __( 'Stock Report', 'src'),
	    'Report',
	     $src_capabilities['roles']['permission']['add_roles'],
	    'list_report',
	    'list_report',
	    'dashicons-awards',
	    9
	);
	add_submenu_page('list_report', 'Stock Reports', 'Stock Reports',  $src_capabilities['roles']['permission']['add_roles'], 'list_report', 'list_report' );
	add_submenu_page('list_report', 'Accountant Report', 'Accountant Report',  $src_capabilities['roles']['permission']['add_roles'], 'list_report_account', 'list_report_account' );



}


function list_lots() {
	require 'lots/listing/lot-list.php';
}
function add_lot() {
    require 'lots/add-lot.php';
}
function list_stocks() {
	require 'stocks/listing/stock-list.php';
}
function total_stock_list() {
	require 'stocks/listing/stock-list-total.php';
}
function add_stocks() {
	require 'stocks/add-stock.php';
}

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
function ws_return_items_list(){
	require 'billing/ws_return_invoice_list.php';
}
function ws_return_items_view(){
	require 'billing/ws_return_invoice_view.php';
}

function return_items_view(){
	require 'billing/return_invoice_view.php';
}


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



function add_admin() {
    require 'users/add-admin.php';
}
function list_admin_users() {
    require 'users/listing/user-list.php';
}

function add_admin_role() {
    require 'roles/add-role.php';
}
function list_roles() {
    require 'roles/listing/role-list.php';
}

function list_report() {
    require 'report/listing/stock-list.php';
}

function list_report_account() {
	 require 'report/listing/stock-list-accountant.php';
}

?>