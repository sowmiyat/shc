<?php
	class Billing {
 
		function __construct() {

		    if( isset($_POST['action'])) {
		    	$params = array();
				parse_str($_POST['data'], $params);

		        $this->cpage = 1;
		        $this->ppage = isset($params['ppage']) ? $params['ppage'] : 20;
		        $this->inv_id = isset($params['inv_id']) ? $params['inv_id'] : '';
		        $this->order_id = isset($params['order_id']) ? $params['order_id'] : '';
		        $this->name = isset($params['name']) ? $params['name'] : '';
		        $this->customer_name = isset($params['customer_name']) ? $params['customer_name'] : '';
		        $this->mobile = isset($params['mobile']) ? $params['mobile'] : '';
		        $this->bill_from = isset($params['bill_from']) ? $params['bill_from'] : '';
		        $this->bill_to = isset($params['bill_to']) ? $params['bill_to'] : '';
		        $this->product_name = isset($params['product_name']) ? $params['product_name'] : '';

		    }  
		    else {
		        $this->cpage 		= isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage 		= isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->inv_id 		= isset( $_GET['inv_id'] ) ? $_GET['inv_id']  : '';
		        $this->order_id 	= isset( $_GET['order_id'] ) ? $_GET['order_id']  : '';
		        $this->name 		= isset( $_GET['name'] ) ? $_GET['name']  : '';
		        $this->mobile 		= isset( $_GET['mobile'] ) ? $_GET['mobile']  : '';
		        $this->bill_from 	= isset( $_GET['bill_from'] ) ? $_GET['bill_from']  : '';
		        $this->bill_to 		= isset( $_GET['bill_to'] ) ? $_GET['bill_to']  : '';
		        $this->product_name = isset( $_GET['product_name'] ) ? $_GET['product_name']  : '';
		    }
		}



		function billing_list_pagination( $args ) {
		    global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $customer_table =  $wpdb->prefix.'shc_customers';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['inv_id'] = $this->inv_id;
	        $page_arg['order_id'] = $this->order_id;
	    	$page_arg['name'] = $this->name;
	    	$page_arg['mobile'] = $this->mobile;
	    	$page_arg['bill_from'] = $this->bill_from;
	    	$page_arg['bill_to'] = $this->bill_to;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->inv_id != '') {
		    	$condition .= " AND id = ".$this->inv_id;
		    }
		    if($this->order_id != '') {
		    	$condition .= " AND order_id LIKE '".$this->order_id."%' ";
		    }
		    if($this->name != '') {
		    	$condition .= " AND name LIKE '".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '".$this->mobile."%' ";
		    }
		    if($this->bill_from != '' && $this->bill_to != '') {
		    	$condition .= " AND DATE(created_at) >= DATE('".$this->bill_from."') AND DATE(created_at) <= DATE('".$this->bill_to."')";
		    } else if($this->bill_from != '' || $this->bill_to != '') {
		    	if($this->bill_from != '') {
		    		$condition .= " AND DATE(created_at) >= DATE('".$this->bill_from."') AND DATE(created_at) <= DATE('".$this->bill_from."')";
		    	} else {
		    		$condition .= " AND DATE(created_at) >= DATE('".$this->bill_to."') AND DATE(created_at) <= DATE('".$this->bill_to."')";
		    	}
		    }

		    $query 				= "SELECT * FROM (SELECT s.*,
			( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name,
			( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
			( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
			FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.active = 1 ${condition}";

		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

		    $data['total']      = $wpdb->get_var( $total_query );
		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage         = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=billing_list')),
		                'format' => '',
		                'type' => 'array',
		                'prev_text' => __('prev'),
		                'next_text' => __('next'),
		                'total' => $totalPage,
		                'current' => $page
		                )
		            );
		        if ( ! empty( $pagination ) ) : 
		            $customPagHTML .= '<ul class="paginate pag3 clearfix"><li class="single">Page '.$page.' of '.$totalPage.'</li>';
		            foreach ($pagination as $key => $page_link ) {
		                if( strpos( $page_link, 'current' ) !== false ) {
		                    $customPagHTML .=  '<li class="current">'.$page_link.'</li>';
		                } else {
		                    $customPagHTML .=  '<li>'.$page_link.'</li>';
		                }
		            }
		            $customPagHTML .=  '</ul>';
		        endif;
		    }

		    $data['pagination'] = $customPagHTML;
		    $end_count = $data['start_count'] + count($data['result']);
		    
		    if( $end_count == 0) {
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;
		}

			function ws_billing_list_pagination( $args ) {
		    global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_ws_sale';
		    $customer_table =  $wpdb->prefix.'shc_wholesale_customer';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['inv_id'] = $this->inv_id;
	        $page_arg['order_id'] = $this->order_id;
	    	$page_arg['customer_name'] = $this->customer_name;
	    	$page_arg['mobile'] = $this->mobile;
	    	$page_arg['bill_from'] = $this->bill_from;
	    	$page_arg['bill_to'] = $this->bill_to;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    
		    if($this->inv_id != '') {
		    	$condition .= " AND id = ".$this->inv_id;
		    }
		    if($this->order_id != '') {
		    	$condition .= " AND order_id LIKE '".$this->order_id."%' ";
		    }
		    if($this->name != '') {
		    	$condition .= " AND name LIKE '".$this->customer_name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '".$this->mobile."%' ";
		    }
		    if($this->bill_from != '' && $this->bill_to != '') {
		    	$condition .= " AND DATE(created_at) >= DATE('".$this->bill_from."') AND DATE(created_at) <= DATE('".$this->bill_to."')";
		    } else if($this->bill_from != '' || $this->bill_to != '') {
		    	if($this->bill_from != '') {
		    		$condition .= " AND DATE(created_at) >= DATE('".$this->bill_from."') AND DATE(created_at) <= DATE('".$this->bill_from."')";
		    	} else {
		    		$condition .= " AND DATE(created_at) >= DATE('".$this->bill_to."') AND DATE(created_at) <= DATE('".$this->bill_to."')";
		    	}
		    }

		    $query 				= "SELECT * FROM (SELECT s.*,
			( CASE WHEN c.customer_name  IS NULL THEN 'Nil' ELSE c.customer_name  END ) as name,
			( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
			( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
			FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.active = 1 ${condition}";
		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

		    $data['total']      = $wpdb->get_var( $total_query );

		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage         = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=ws_billing_list')),
		                'format' => '',
		                'type' => 'array',
		                'prev_text' => __('prev'),
		                'next_text' => __('next'),
		                'total' => $totalPage,
		                'current' => $page
		                )
		            );
		        if ( ! empty( $pagination ) ) : 
		            $customPagHTML .= '<ul class="paginate pag3 clearfix"><li class="single">Page '.$page.' of '.$totalPage.'</li>';
		            foreach ($pagination as $key => $page_link ) {
		                if( strpos( $page_link, 'current' ) !== false ) {
		                    $customPagHTML .=  '<li class="current">'.$page_link.'</li>';
		                } else {
		                    $customPagHTML .=  '<li>'.$page_link.'</li>';
		                }
		            }
		            $customPagHTML .=  '</ul>';
		        endif;
		    }

		    $data['pagination'] = $customPagHTML;
		    $end_count = $data['start_count'] + count($data['result']);

		    if( $end_count == 0) {
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;
		}

		function return_list_pagination( $args )
		{


			global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $customer_table =  $wpdb->prefix.'shc_customers';
		    $
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['inv_id'] = $this->inv_id;
	    	$page_arg['mobile'] = $this->mobile;
	    	$page_arg['product_name'] = $this->product_name;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->inv_id != '') {
		    	$condition .= " AND inv_id = ".$this->inv_id;
		    }  
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '".$this->mobile."%' ";
		    }
		    $query 				= "SELECT * FROM (SELECT s.*, ( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM wp_shc_return_items as s LEFT JOIN wp_shc_customers as c ON s.customer_id = c.id ) as tot WHERE tot.active = 1 ${condition}";
		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    $data['total']      = $wpdb->get_var( $total_query );
		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;
		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );


		    $totalPage         = ceil($data['total'] / $args['items_per_page']);


		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=return_items_list')),
		                'format' => '',
		                'type' => 'array',
		                'prev_text' => __('prev'),
		                'next_text' => __('next'),
		                'total' => $totalPage,
		                'current' => $page
		                )
		            );
		        if ( ! empty( $pagination ) ) : 
		            $customPagHTML .= '<ul class="paginate pag3 clearfix"><li class="single">Page '.$page.' of '.$totalPage.'</li>';
		            foreach ($pagination as $key => $page_link ) {
		                if( strpos( $page_link, 'current' ) !== false ) {
		                    $customPagHTML .=  '<li class="current">'.$page_link.'</li>';
		                } else {
		                    $customPagHTML .=  '<li>'.$page_link.'</li>';
		                }
		            }
		            $customPagHTML .=  '</ul>';
		        endif;
		    }

		    $data['pagination'] = $customPagHTML;
		    $end_count = $data['start_count'] + count($data['result']);
		    
		    if( $end_count == 0) {
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;
		}




		function ws_return_list_pagination( $args )
		{


			global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $customer_table =  $wpdb->prefix.'shc_customers';
		    $
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['inv_id'] = $this->inv_id;
	    	$page_arg['mobile'] = $this->mobile;
	    	$page_arg['product_name'] = $this->product_name;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->inv_id != '') {
		    	$condition .= " AND inv_id = ".$this->inv_id;
		    }  
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '".$this->mobile."%' ";
		    }
		    $query 				= "SELECT * FROM (SELECT s.*, ( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,( CASE WHEN c.gst_number IS NULL THEN 'Nil' ELSE c.gst_number END ) as gst_number, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM wp_shc_ws_return_items as s LEFT JOIN wp_shc_wholesale_customer as c ON s.customer_id = c.id ) as tot WHERE tot.active = 1 ${condition}";
		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    $data['total']      = $wpdb->get_var( $total_query );
		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;
		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );


		    $totalPage         = ceil($data['total'] / $args['items_per_page']);


		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=ws_return_items_list')),
		                'format' => '',
		                'type' => 'array',
		                'prev_text' => __('prev'),
		                'next_text' => __('next'),
		                'total' => $totalPage,
		                'current' => $page
		                )
		            );
		        if ( ! empty( $pagination ) ) : 
		            $customPagHTML .= '<ul class="paginate pag3 clearfix"><li class="single">Page '.$page.' of '.$totalPage.'</li>';
		            foreach ($pagination as $key => $page_link ) {
		                if( strpos( $page_link, 'current' ) !== false ) {
		                    $customPagHTML .=  '<li class="current">'.$page_link.'</li>';
		                } else {
		                    $customPagHTML .=  '<li>'.$page_link.'</li>';
		                }
		            }
		            $customPagHTML .=  '</ul>';
		        endif;
		    }

		    $data['pagination'] = $customPagHTML;
		    $end_count = $data['start_count'] + count($data['result']);
		    
		    if( $end_count == 0) {
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }

		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;
		}


	}
	



?>