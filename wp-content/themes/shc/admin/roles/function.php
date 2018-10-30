<?php
require get_template_directory() . '/admin/roles/class-roles.php';

function load_roles_scripts() {
	wp_enqueue_script( 'roles-script', get_template_directory_uri() . '/admin/roles/inc/js/roles.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_roles_scripts' );

function src_global_var() {
    global $src_capabilities;
	$src_capabilities = array( 
  
		'lots' => array(
			'name' => 'Products',
			'data' => array(
				'add_lot' => 'Add Stock',
				'lot_list' => 'Lot List', 
			),
			'permission' => array(
				'add_lot' => (is_super_admin()) ? 'manage_options' : 'add_lot',
				'lot_list' => (is_super_admin()) ? 'manage_options' : 'lot_list', 
			),
		),
		'stocks' => array(
			'name' => 'Stocks',
			'data' => array(
				'add_stock' => 'Add Stock',
				'stock_list' => 'Stock List', 
				'total_stock_list' => 'Total Stock List', 
			),
			'permission' => array(
				'add_stock' => (is_super_admin()) ? 'manage_options' : 'add_stock',
				'stock_list' => (is_super_admin()) ? 'manage_options' : 'stock_list', 
				'total_stock_list' => (is_super_admin()) ? 'manage_options' : 'total_stock_list', 
			),			
		),
		'customers' => array(
			'name' => 'Customers',
			'data' => array(
				'add_customer' => 'Add Customer',
				'customer_list' => 'Customer List',
				'add_ws_customer' => 'Add Wholesale Customer',
				'ws_customer_list' => 'Wholesale Customer List',
			),
			'permission' => array(
				'add_customer' => (is_super_admin()) ? 'manage_options' : 'add_customer',
				'customer_list' => (is_super_admin()) ? 'manage_options' : 'customer_list',
				'add_ws_customer' => (is_super_admin()) ? 'manage_options' : 'add_ws_customer', 
				'ws_customer_list' => (is_super_admin()) ? 'manage_options' : 'ws_customer_list', 
			),			
		),
		'billing' => array(
			'name' => 'Sales & Billing',
			'data' => array(
				'add_billing' 				=> 'Purchase & Sales', 	
				'billing_list' 				=> 'Billing List', 
				'cancel_invoice' 			=> 'Cancel Billing List', 
				'add_return' 				=> 'Return Goods', 
				'return_list' 				=> 'Return List', 
				'ws_add_billing' 			=> 'Wholesale Purchase & Sales', 
				'ws_billing_list' 			=> 'Wholesale Billing List',
				'ws_cancel_invoice' 		=> 'Cancel Wholesale Billing List',
				'ws_add_return' 			=> 'Wholesale Return Goods', 
				'ws_return_list' 			=> 'Wholesale Return List', 
				 
			),
			'permission' => array(
				'add_billing' 				=> (is_super_admin()) ? 'manage_options' : 'add_billing',
				'billing_list' 				=> (is_super_admin()) ? 'manage_options' : 'billing_list', 
				'cancel_invoice' 			=> (is_super_admin()) ? 'manage_options' : 'cancel_invoice', 
				'add_return' 				=> (is_super_admin()) ? 'manage_options' : 'add_return',
				'return_list' 				=> (is_super_admin()) ? 'manage_options' : 'return_list', 
				'ws_add_billing' 			=> (is_super_admin()) ? 'manage_options' : 'ws_add_billing', 
				'ws_billing_list' 			=> (is_super_admin()) ? 'manage_options' : 'ws_billing_list', 
				'ws_cancel_invoice' 		=> (is_super_admin()) ? 'manage_options' : 'ws_cancel_invoice', 
				'ws_add_return' 			=> (is_super_admin()) ? 'manage_options' : 'ws_add_return', 
				'ws_return_list' 			=> (is_super_admin()) ? 'manage_options' : 'ws_return_list', 
				
			),			
		),

		'report' => array(
			'name' => 'Report',
			'data' => array(
				'stock_report' => 'Stock Report', 
				'return_report' => 'Goods Return Report', 
				'acc_report' => 'Accountant Report', 
			),
			'permission' => array(
				'stock_report' => (is_super_admin()) ? 'manage_options' : 'stock_report',
				'return_report' => (is_super_admin()) ? 'manage_options' : 'return_report', 
				'acc_report' => (is_super_admin()) ? 'manage_options' : 'acc_report', 
			),			

		),

		'admin_user' => array(
			'name' => 'Admin Users',
			'data' => array(
				'add_admin' => 'Add New Admin', 
				'admin_list' => 'Admin List',
			),
			'permission' => array(
				'add_admin' => (is_super_admin()) ? 'manage_options' : 'add_admin',
				'admin_list' => (is_super_admin()) ? 'manage_options' : 'admin_list', 
			),			
		),
		'roles' => array(
			'name' => 'Admin Roles',
			'data' => array(
				'add_roles' => 'Add New Role', 
				'role_list' => 'Role List', 
			),
			'permission' => array(
				'add_roles' => (is_super_admin()) ? 'manage_options' : 'add_roles',
				'role_list' => (is_super_admin()) ? 'manage_options' : 'role_list', 
			),			

		)


	);

}
add_action( 'init', 'src_global_var' );


function create_roles() {
	$data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong Please Try Again!';
	$data['redirect'] 	= 0;

	global $src_capabilities;
	$params = array();
	parse_str($_POST['data'], $params);


	$role_name 	=  	$params['role_name'];
	$role_slug 	= 	$params['role_slug'];

	$grant_true		= true;
	$grant_false 	= false;	

	foreach ($params['main_menu'] as $cap_value) {
		if($src_capabilities[$cap_value]) {

			if(is_array($src_capabilities[$cap_value])) {
				foreach ($src_capabilities[$cap_value]['data'] as $cap_key => $c_value) {
					$capabilities[] = $cap_key;
				}
			} else {
				$capabilities[] = $cap_value;
			}
		} else {
			$capabilities[] = $cap_value;
		}
	}
	$new_cap = array_unique($capabilities); 
	$new_cap[] = 'read';


	$new_role = add_role( $role_slug, __( $role_name ) );
	if ( null !== $new_role ) {
		foreach ($new_cap as  $cap_value) {
			$new_role->add_cap( $cap_value, $grant_true );
		}
		$data['success'] = 1;
		$data['msg'] 	= 'Role Created!';
		$data['redirect'] = network_admin_url( 'admin.php?page=add_admin_role' );
	}


	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_create_roles', 'create_roles' );
add_action( 'wp_ajax_nopriv_create_roles', 'create_roles' );


function update_roles() {
	$data['success'] 	= 0;
	$data['msg'] 	= 'Role Not Exist Please Try Again!';
	$data['redirect'] 	= 0;

	global $src_capabilities;
	$params = array();
	parse_str($_POST['data'], $params);

	$current_cap = get_role($params['role_slug']);
	$editable_roles = get_editable_roles();


	$capabilities = array();

	if($params['main_menu']) {
		foreach ($params['main_menu'] as $cap_value) {
			if($src_capabilities[$cap_value]) {
				if(is_array($src_capabilities[$cap_value])) {
					foreach ($src_capabilities[$cap_value]['data'] as $cap_key => $c_value) {
						$capabilities[] = $cap_key;
					}
				} else {
					$capabilities[] = $cap_value;
				}
			} else {
				$capabilities[] = $cap_value;
			}
		}
	}


	$new_cap = array_unique($capabilities);  
	$new_cap[] = 'read';

	$new_fliped1 = array_flip($new_cap);
	$new_fliped2 = $current_cap->capabilities;

	$new_data 		= array_diff_key($new_fliped1, $new_fliped2);
	$delete_data 	= array_diff_key($new_fliped2, $new_fliped1);


	if( count($new_data) > 0) {
		foreach ($new_data as $n_key => $n_value) {
			$current_cap->add_cap( $n_key );
		}
	}
	if( count($delete_data) > 0) {
		foreach ($delete_data as $d_key => $d_value) {
			$current_cap->remove_cap( $d_key );
		}
	}

	$data['success'] = 1;
	$data['msg'] 	= 'Role Updated!';

	echo json_encode($data);
	die();

}
add_action( 'wp_ajax_update_roles', 'update_roles' );
add_action( 'wp_ajax_nopriv_update_roles', 'update_roles' );