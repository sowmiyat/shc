<?php


function load_profile_scripts() {
	wp_enqueue_script( 'profile-script', get_template_directory_uri() . '/admin/profile/inc/profile.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_profile_scripts' );


function update_profile() {

	$current_user 		= wp_get_current_user();
	$current_nice_name 	= $current_user->user_nicename;


	$data['success'] 	= 0;
	$data['msg'] = 'Invalid Data Please Check Again!';
	$data['redirect'] 	= 0;

	global $wpdb;
	$params = array();
	parse_str($_POST['data'], $params);
	$profile_table = $wpdb->prefix. 'shc_profile';
	$company_name 		= $params['profile_company'];
	$mobile 			= $params['profile_mobile'];
	$address 			= $params['profile_address'];
	$address2 			= $params['profile_address2'];
	$gst_number 		= $params['profile_gst_number'];
	$profile_id         = $params['profile_id'];        
	unset($params['action']);
	unset($params['form_submit_prevent']);

	$query = "SELECT * from ${profile_table} WHERE company_name ='${company_name}' and phone_number ='${mobile}' and address ='${address}' and address2 ='${address2}' and gst_number = '${gst_number}' and id ='${profile_id}' and active='1'";
	if($wpdb->get_row($query)){
		$data['success'] = 0;
		$data['msg'] 	= 'No Updates!';
		$data['redirect'] = network_admin_url( 'admin.php?page=add_profile&id='.$customer_id );

	} else {

			$wpdb->update($profile_table, array('active' => '0'), array('id' => $profile_id));

			$insert_data = array(
				'company_name' => $params['profile_company'],
				'phone_number'  => $params['profile_mobile'],
				'address' => $params['profile_address'],
				'address2' => $params['profile_address2'],
				'gst_number' => $params['profile_gst_number']
				);

			
			$wpdb->insert($profile_table, $insert_data);

			$data['success'] = 1;
			$data['msg'] = 'Profile Detail Updated!';
			$data['redirect'] = network_admin_url( 'admin.php?page=add_profile' );
	}
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_profile', 'update_profile' );
add_action( 'wp_ajax_nopriv_update_profile', 'update_profile' );


function get_profile1(){
	global $wpdb;
	$profile_table = $wpdb->prefix. 'shc_profile';
	$query = "SELECT * FROM ${profile_table} WHERE active = 1";
	return $wpdb->get_row($query);
}