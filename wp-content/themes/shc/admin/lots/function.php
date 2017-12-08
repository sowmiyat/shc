<?php
require get_template_directory() . '/admin/lots/class-lots.php';

	

function load_lot_scripts() {
	wp_enqueue_script( 'lot-script', get_template_directory_uri() . '/admin/lots/inc/js/lot.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_lot_scripts' );


function get_lot($lot_id = 0) {
    global $wpdb;
    $lot_table =  $wpdb->prefix.'shc_lots';
    $query = "SELECT * FROM ${lot_table} WHERE id = ${lot_id}";
    return $wpdb->get_row($query);
}


/*Ajax Functions*/
function create_lot(){

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong Please Try Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	unset($params['action']);
	unset($params['form_submit_prevent']);
	$lot_table = $wpdb->prefix. 'shc_lots';
	$wpdb->insert($lot_table, $params);
	$create_id 			= $wpdb->insert_id;
	$lot_add_update 	=  array('lot_no' =>$create_id,'created_by'=>$current_nice_name);
	$wpdb->update($lot_table, $lot_add_update, array('id' => $create_id));

	if($wpdb->insert_id) {
		$data['success'] = 1;
		$data['msg'] 	= 'Lot Created!';
		
		$data['redirect'] = network_admin_url( 'admin.php?page=list_lots' );
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_create_lot', 'create_lot' );
add_action( 'wp_ajax_nopriv_create_lot', 'create_lot' );



function update_lot(){
	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;

	$data['success'] 	= 0;
	$data['msg'] 	= 'Product Not Exist Please Try Again!';
	$data['redirect'] 	= 0;
	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$lot_id = $params['lot_id'];

	unset($params['action']);
	unset($params['lot_id']);
	unset($params['form_submit_prevent']);
	$lot_table = $wpdb->prefix. 'shc_lots';
	if($lot_id != '' && get_lot($lot_id)) {
		$wpdb->update($lot_table, $params, array('id' => $lot_id));
		$wpdb->update($lot_table, array('modified_by' => $current_nice_name ), array('id' => $lot_id));
		$data['success'] = 1;
		$data['msg'] 	= 'Lot Updated!';
		$data['redirect'] = network_admin_url( 'admin.php?page=list_lots' );
	}

	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_lot', 'update_lot' );
add_action( 'wp_ajax_nopriv_update_lot', 'update_lot' );



function lot_filter() {
	$lots = new Lots();
	include( get_template_directory().'/admin/lots/ajax_loading/lot-list.php' );
	die();	
}
add_action( 'wp_ajax_lot_filter', 'lot_filter' );
add_action( 'wp_ajax_nopriv_lot_filter', 'lot_filter' );


function check_unique_product() {

	global $wpdb;
	$productname 	= $_POST['productname'];
	$brandname 		= $_POST['brandname'];

    $lot_table 		=  $wpdb->prefix.'shc_lots';
    $query 			=  "SELECT * FROM ${lot_table} WHERE brand_name='$brandname' and product_name ='$productname' and active=1";
    $result 		=  $wpdb->get_row( $query );

    if($result) {
    	$data = 1;
    } else {
    	$data = 0;
    }
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_check_unique_product', 'check_unique_product' );
add_action( 'wp_ajax_nopriv_check_unique_product', 'check_unique_product' );

?>