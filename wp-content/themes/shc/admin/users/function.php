<?php
require get_template_directory() . '/admin/users/class-users.php';

function load_users_scripts() {
	wp_enqueue_script( 'users-script', get_template_directory_uri() . '/admin/users/inc/js/users.js', array('jquery'), false, false );
}
add_action( 'admin_enqueue_scripts', 'load_users_scripts' );


function create_admin_user() {
	$data['success'] 	= 0;
	$data['msg'] 	= 'User Name Already Used! OR Email Already Used! Please Check and Create again!';
	$data['redirect'] 	= 0;

	$params = array();
	parse_str($_POST['data'], $params);
	$user_name = $params['user_name'];
	$password = $params['password'];
	$mobile = $params['mobile'];
	$email = $params['email'];
	$user_role = $params['role'];

	$user_id = username_exists( $user_name );

	if ( !$user_id && email_exists($email) == false ) {
		$user_id = wp_create_user( $user_name, $password, $email ); 
		wp_update_user(array('ID' => $user_id,'role' => $user_role));
		update_user_meta($user_id, 'mobile', $mobile);
		$data['success'] = 1;
		$data['msg'] 	= 'User Account Created!';
		$data['redirect'] = network_admin_url( 'admin.php?page=list_admin_users' );
	}
	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_create_admin_user', 'create_admin_user' );
add_action( 'wp_ajax_nopriv_create_admin_user', 'create_admin_user' );

function update_admin_user() {
	$data['success'] 	= 0;
	$data['msg'] 	= 'Something Went Wrong Please Try Again!';
	$data['redirect'] 	= 0;


	$params = array();
	parse_str($_POST['data'], $params);
	$user = false;

	$current_role = array();
	if(isset($params['user_id']) && ( get_user_by('id', $params['user_id'])->user_email == $params['email'] || email_exists($params['email']) == false )  && $user = get_userdata($params['user_id']) ) {
		$user_id = $params['user_id'];
		$current_role = implode(', ', $user->roles);
	} else {
		$data['msg'] = 'User Not Exist! OR Email Already Used! Please Check and Update again';
	}


	if( $user ) {

		$user_name = $params['user_name'];
		$password = $params['password'];
		$mobile = $params['mobile'];
		$email = $params['email'];
		$user_role = $params['role'];

		$u = new WP_User( $user_id );
		$u->remove_role( $current_role );

		$update_data = array('ID' => $user_id, 'user_pass' => $password, 'role' => $user_role, 'user_email' => $email);
		$success = wp_update_user($update_data);
		update_user_meta($user_id, 'mobile', $mobile);
		$data['success'] = 1;
		$data['msg'] 	= 'User Detail Updated!';
	}


	echo json_encode($data);
	die();
}
add_action( 'wp_ajax_update_admin_user', 'update_admin_user' );
add_action( 'wp_ajax_nopriv_update_admin_user', 'update_admin_user' );
?>