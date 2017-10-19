<?php
	class userRoles {
 
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
			global $src_capabilities;

			$editable_roles = get_editable_roles();
			unset($editable_roles['administrator']);
			unset($editable_roles['editor']);
			unset($editable_roles['author']);
			unset($editable_roles['contributor']);
			unset($editable_roles['subscriber']);
			unset($editable_roles['customer']);
			unset($editable_roles['employee']);





			$capabilities_order = array();
			foreach ($src_capabilities as $cap_key => $cap_value) {

				if(is_array($cap_value)) {
					foreach ($cap_value['data'] as $sub_key => $sub_value) {
						$capabilities_order[$sub_key] = $sub_value;
					}
				} else {
					$capabilities_order[$cap_key] = $cap_value;
				}
				
			}

			$data['capabilities_order'] = $capabilities_order;
			$data['result'] = $editable_roles;
		    return $data;
		}
	}


?>