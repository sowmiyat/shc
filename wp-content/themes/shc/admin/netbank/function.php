<?php

function load_netbank_scripts() {
	wp_enqueue_script( 'netbank-script', get_template_directory_uri() . '/admin/netbank/inc/netbank.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_netbank_scripts' );


function update_netbank() {

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;


	$data['success'] 	= 0;
	$data['msg'] = 'Invalid Data Please Check Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$netbank_table 		= $wpdb->prefix. 'shc_netbank';
	$shop_name 			= $params['nb_shop'];
	$bank 				= $params['nb_bank'];
	$account 			= $params['nb_account'];
	$ifsc 				= $params['nb_ifsc'];
	$account_type 		= $params['nb_account_type'];
	$branch         	= $params['nb_branch'];        
	$netbank_id         = $params['netbank_id'];        
	unset($params['action']);
	unset($params['form_submit_prevent']);

	$query = "SELECT * from ${netbank_table} WHERE shop_name ='${shop_name}' and bank ='${bank}' and account ='${account}' and ifsc ='${ifsc}' and account_type = '${account_type}' and branch = '${branch}' and id ='${netbank_id}' and active='1'";
	if($wpdb->get_row($query)){
		$data['success'] = 0;
		$data['msg'] 	= 'No Updates!';
		$data['redirect'] = network_admin_url( 'admin.php?page=add_netbank&id='.$customer_id );

	} else {

			$wpdb->update($netbank_table, array('active' => '0'), array('id' => $netbank_id));

			$insert_data = array(
				'shop_name' 	=> $params['nb_shop'],
				'bank'  		=> $params['nb_bank'],
				'account' 		=> $params['nb_account'],
				'ifsc' 			=> $params['nb_ifsc'],
				'account_type' 	=> $params['nb_account_type'],
				'branch' 		=> $params['nb_branch'],
				);

			
			$wpdb->insert($netbank_table, $insert_data);

			$data['success'] = 1;
			$data['msg'] = 'Bank Details Updated!';
			$data['redirect'] = network_admin_url( 'admin.php?page=add_netbank' );
	}
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_netbank', 'update_netbank' );
add_action( 'wp_ajax_nopriv_update_netbank', 'update_netbank' );


function get_netbank1(){
	global $wpdb;
	$netbank_table = $wpdb->prefix. 'shc_netbank';
	$query = "SELECT * FROM ${netbank_table} WHERE active = 1";
	return $wpdb->get_row($query);
}