<?php
	class adminUsers {
 
		function __construct() {

		    if( isset($_POST['action']) ) {
		    	$params = array();
				parse_str($_POST['data'], $params);

		        $this->cpage = 1;
		        $this->ppage = isset($params['ppage']) ? $params['ppage'] : 20;
		        $this->name = isset($params['name']) ? $params['name'] : '';
		        $this->mobile = isset($params['mobile']) ? $params['mobile'] : '';
		        $this->customer_from = isset($params['customer_from']) ? $params['customer_from'] : '';
		        $this->customer_to = isset($params['customer_to']) ? $params['customer_to'] : '';


		    } else {
		        $this->cpage = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage = isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->name = isset( $_GET['name'] ) ? $_GET['name']  : '';
		        $this->mobile = isset( $_GET['mobile'] ) ? $_GET['mobile']  : '';
		        $this->customer_from = isset( $_GET['customer_from'] ) ? $_GET['customer_from']  : '';
		        $this->customer_to = isset( $_GET['customer_to'] ) ? $_GET['customer_to']  : '';
		    }




		}



		function user_list_pagination( $args ) {

		    $editable_roles = get_editable_roles();
			unset($editable_roles['administrator']);
			unset($editable_roles['editor']);
			unset($editable_roles['author']);
			unset($editable_roles['contributor']);
			unset($editable_roles['subscriber']);
			unset($editable_roles['customer']);
			unset($editable_roles['employee']);

			$args = array(
				'role__in' => array_keys($editable_roles),
				'role__not_in' => array('administrator', 'editor', 'author', 'contributor', 'subscriber', 'customer', 'employee'),
				'orderby' => 'registered',
				'order' => 'DESC'
			);
			$data['result'] = get_users($args);
			$data['editable_roles'] = $editable_roles;
			return $data;
		}
	}


?>