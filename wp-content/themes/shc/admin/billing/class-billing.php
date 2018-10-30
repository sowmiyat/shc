<?php
	class Billing {
 
		function __construct() {

		    if( isset($_POST['action'])) {
		    	$params = array();
				parse_str($_POST['data'], $params);

		        $this->cpage 			= 1;
		        $this->ppage 			= isset($params['ppage']) ? $params['ppage'] : 20;
		        $this->inv_id 			= isset($params['inv_id']) ? $params['inv_id'] : '';
		        $this->order_id 		= isset($params['order_id']) ? $params['order_id'] : '';
		        $this->return_id 		= isset($params['return_id']) ? $params['return_id'] : '';
		        $this->name 			= isset($params['name']) ? $params['name'] : '';
		        $this->customer_name 	= isset($params['customer_name']) ? $params['customer_name'] : '';
		        $this->mobile 			= isset($params['mobile']) ? $params['mobile'] : '';
		        $this->bill_from 		= isset($params['bill_from']) ? $params['bill_from'] : '';
		        $this->bill_to 			= isset($params['bill_to']) ? $params['bill_to'] : '';
		        $this->product_name 	= isset($params['product_name']) ? $params['product_name'] : '';

		    }  
		    else {
		        $this->cpage 		= isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage 		= isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->inv_id 		= isset( $_GET['inv_id'] ) ? $_GET['inv_id']  : '';
		        $this->order_id 	= isset( $_GET['order_id'] ) ? $_GET['order_id']  : '';
		        $this->return_id 	= isset( $_GET['return_id'] ) ? $_GET['return_id']  : '';
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
		    $sale_detail =  $wpdb->prefix.'shc_sale_detail';
		    $payment_table =  $wpdb->prefix.'shc_payment';

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
		    	$condition .= " AND order_id LIKE '%".$this->order_id."%' ";
		    }
		    if($this->name != '') {
		    	$condition .= " AND name LIKE '%".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
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

		    $query 				= "SELECT * FROM ( SELECT * FROM (SELECT s.*,
				( CASE WHEN c.name  IS NULL THEN 'Nil' ELSE c.name  END ) as name,
				( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
				( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
				FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.active = 1 ) as sale left join 
				 (
				 select sale_id,
				(case  when s_count = d_count then 1 else 0 end )as delivered
				from  (SELECT sum(`sale_unit`) as s_count ,sum(`delivery_count`) as d_count,`sale_id`,`active` FROM {$sale_detail} as sale_detail  WHERE active=1  GROUP by sale_id ) ful_tab
				 ) as sale_detail on sale.id = sale_detail.sale_id WHERE sale.active = 1  ${condition}";

		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
			$status_query       = "SELECT SUM(sub_total) as total_amount,sum(cod_amount) as cod FROM (${query}) AS combined_table";

			$data['s_result']   = $wpdb->get_row( $status_query );
			$payment_query= "SELECT SUM(final.amount) as amt,final.pay_type from (select 
(case when type.amount is null then '0.00' else type.amount end ) as amount,type.created_at,
(case when type.pay_type is null then 'null' else type.pay_type end ) as pay_type,type.name,type.mobile
from (
    SELECT payment.amount,payment.pay_type as pay_type,sale.* FROM 
    (
        SELECT s.*, ( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 
    )
    as sale left join (
    SELECT payment_type as pay_type,amount,sale_id from ${payment_table} WHERE active=1  
) as payment 
on payment.sale_id = sale.id where sale.active = 1  ${condition} )
as type WHERE type.active = 1 ) as final GROUP by final.pay_type ";

			
			
//for cash amount
			$cash_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'cash'";
			$ToPay_query = "SELECT sale.*,sum(sale.pay_to_bal) as pay_to FROM ( 
     SELECT s.*, 
     ( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name, 
     ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, 
     ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address 
     FROM ${sale_table} as s 
     LEFT JOIN ${customer_table} as c 
     ON s.customer_id = c.id WHERE s.locked = 1 
 ) as sale WHERE sale.active = 1 ${condition}";
			$data['payto_result'] = $wpdb->get_row( $ToPay_query );
			$data['c_result']   = $wpdb->get_row( $cash_query );

//for credit

			$credit_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'credit'";
			$data['cr_result']   = $wpdb->get_row( $credit_query );
			
//for card

			$card_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'card'";
			$data['card_result']   = $wpdb->get_row( $card_query );
//for cheque

			$cheque_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'cheque'";
			$data['cheque_result']   = $wpdb->get_row( $cheque_query );

//for internet

			$interbanking_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'internet'";
			$data['interbanking_result']   = $wpdb->get_row( $interbanking_query );


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



		function cancel_billing_list_pagination( $args ) {
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
		    	$condition .= " AND order_id LIKE '%".$this->order_id."%' ";
		    }
		    if($this->name != '') {
		    	$condition .= " AND name LIKE '%".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
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
			FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.cancel = 1 ${condition}";

		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
			$status_query       = "SELECT SUM(sub_total) as total_amount FROM (${query}) AS combined_table";

			$data['s_result']   = $wpdb->get_row( $status_query );
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
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=cancel_invoice')),
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
			    $sale_detail = $wpdb->prefix.'shc_ws_sale_detail';
			    $payment_table =  $wpdb->prefix.'shc_ws_payment';
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
			    	$condition .= " AND order_id LIKE '%%".$this->order_id."%' ";
			    }
			    if($this->name != '') {
			    	$condition .= " AND name LIKE '%%".$this->name."%' ";
			    }
			    if($this->mobile != '') {
			    	$condition .= " AND mobile LIKE '%%".$this->mobile."%' ";
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

			    $query 				= "SELECT * FROM ( SELECT * FROM (SELECT s.*,
				( CASE WHEN c.customer_name  IS NULL THEN 'Nil' ELSE c.customer_name  END ) as name,
				( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
				( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
				FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.active = 1 ) as sale left join 
				 (
				 select sale_id,
				(case  when s_count = d_count then 1 else 0 end )as delivered
				from  (SELECT sum(`sale_unit`) as s_count ,sum(`delivery_count`) as d_count,`sale_id`,`active` FROM {$sale_detail} as sale_detail  WHERE active=1  GROUP by sale_id ) ful_tab
				 ) as sale_detail on sale.id = sale_detail.sale_id WHERE sale.active = 1  ${condition}";

			    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

			   	$status_query       = "SELECT SUM(sub_total) as total_amount,sum(cod_amount) as cod FROM (${query}) AS combined_table";

				$data['s_result']   = $wpdb->get_row( $status_query );
			$payment_query= "SELECT SUM(final.amount) as amt,final.pay_type from (select 
	(case when type.amount is null then '0.00' else type.amount end ) as amount,type.created_at,
	(case when type.pay_type is null then '0.00' else type.pay_type end ) as pay_type,type.name,type.mobile
	from (
	    SELECT payment.amount,payment.pay_type as pay_type,sale.* FROM 
	    (
	        SELECT s.*, ( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 
	    )
	    as sale left join (
	    SELECT payment_type as pay_type,amount,sale_id from ${payment_table} WHERE active=1  
	) as payment 
	on payment.sale_id = sale.id where sale.active = 1  ${condition} )
	as type WHERE type.active = 1 ) as final GROUP by final.pay_type ";

				
				
	//for cash amount
				$cash_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'cash'";
				$ToPay_query = "SELECT sale.*,sum(sale.pay_to_bal) as pay_to FROM ( 
				     SELECT s.*, 
				     ( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as name, 
				     ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, 
				     ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address 
				     FROM ${sale_table} as s 
				     LEFT JOIN ${customer_table} as c 
				     ON s.customer_id = c.id WHERE s.locked = 1 
				 ) as sale WHERE sale.active = 1 ${condition}";
				$data['payto_result'] = $wpdb->get_row( $ToPay_query );
				$data['c_result']   = $wpdb->get_row( $cash_query );

	//for credit

				$credit_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'credit'";
				$data['cr_result']   = $wpdb->get_row( $credit_query );
				
	//for card

				$card_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'card'";
				$data['card_result']   = $wpdb->get_row( $card_query );
	//for cheque

				$cheque_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'cheque'";
				$data['cheque_result']   = $wpdb->get_row( $cheque_query );

	//for internet

				$interbanking_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'internet_banking'";
				$data['interbanking_result']   = $wpdb->get_row( $interbanking_query );
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


		function ws_cancel_billing_list_pagination( $args ) {
		    global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_ws_sale';
		    $customer_table =  $wpdb->prefix.'shc_wholesale_customer';
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
		    	$condition .= " AND order_id LIKE '%".$this->order_id."%' ";
		    }
		    if($this->name != '') {
		    	$condition .= " AND name LIKE '%".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
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
			FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.active = 0 ${condition}";
		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

		    $status_query       = "SELECT SUM(sub_total) as total_amount FROM (${query}) AS combined_table";

			$data['s_result']   = $wpdb->get_row( $status_query );

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
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=ws_cancel_invoice')),
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
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['inv_id'] = $this->inv_id;
	    	$page_arg['mobile'] = $this->mobile;
	    	$page_arg['product_name'] = $this->product_name;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->inv_id != '') {
		    	$condition .= " AND search_inv_id = ".$this->inv_id;
		    }  
		    if($this->return_id != '') {
		    	$condition .= " AND return_id LIKE '%".$this->return_id."%' ";
		    }
		     if($this->customer_name != '') {
		    	$condition .= " AND name LIKE '%".$this->customer_name."%' ";
		    }
		     if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
		    }
		    if($this->bill_from != '' && $this->bill_to != '') {
		    	$condition .= " AND DATE(modified_at) >= DATE('".$this->bill_from."') AND DATE(modified_at) <= DATE('".$this->bill_to."')";
		    } else if($this->bill_from != '' || $this->bill_to != '') {
		    	if($this->bill_from != '') {
		    		$condition .= " AND DATE(modified_at) >= DATE('".$this->bill_from."') AND DATE(modified_at) <= DATE('".$this->bill_from."')";
		    	} else {
		    		$condition .= " AND DATE(modified_at) >= DATE('".$this->bill_to."') AND DATE(modified_at) <= DATE('".$this->bill_to."')";
		    	}
		    }
		    $query 				= "SELECT * FROM (SELECT s.*, ( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM wp_shc_return_items as s LEFT JOIN wp_shc_customers as c ON s.customer_id = c.id ) as tot WHERE tot.active = 1 ${condition}";
		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

 			$status_query       = "SELECT SUM(total_amount) as total_amount FROM (${query}) AS combined_table";

			$data['s_result']   = $wpdb->get_row( $status_query );

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


		function cancel_return_list_pagination( $args )
		{


			global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $customer_table =  $wpdb->prefix.'shc_customers';
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
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
		    }
		    $query 				= "SELECT * FROM (SELECT s.*, ( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM wp_shc_return_items as s LEFT JOIN wp_shc_customers as c ON s.customer_id = c.id ) as tot WHERE tot.cancel = 1 ${condition}";
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
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=cancel_return_items_list')),
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
		    // $sale_table =  $wpdb->prefix.'shc_sale';
		    // $customer_table =  $wpdb->prefix.'shc_customers';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['inv_id'] = $this->inv_id;
	    	$page_arg['mobile'] = $this->mobile;
	    	$page_arg['product_name'] = $this->product_name;
		    $page_arg['cpage'] = '%#%';

		     if($this->inv_id != '') {
		    	$condition .= " AND search_inv_id = ".$this->inv_id;
		    }  
		    if($this->return_id != '') {
		    	$condition .= " AND return_id LIKE '%".$this->return_id."%' ";
		    }
		     if($this->customer_name != '') {
		    	$condition .= " AND name LIKE '%".$this->customer_name."%' ";
		    }
		     if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
		    }
		    if($this->bill_from != '' && $this->bill_to != '') {
		    	$condition .= " AND DATE(modified_at) >= DATE('".$this->bill_from."') AND DATE(modified_at) <= DATE('".$this->bill_to."')";
		    } else if($this->bill_from != '' || $this->bill_to != '') {
		    	if($this->bill_from != '') {
		    		$condition .= " AND DATE(modified_at) >= DATE('".$this->bill_from."') AND DATE(modified_at) <= DATE('".$this->bill_from."')";
		    	} else {
		    		$condition .= " AND DATE(modified_at) >= DATE('".$this->bill_to."') AND DATE(modified_at) <= DATE('".$this->bill_to."')";
		    	}
		    }
		    $query 				= "SELECT * FROM (SELECT s.*, ( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,( CASE WHEN c.gst_number IS NULL THEN 'Nil' ELSE c.gst_number END ) as gst_number, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM wp_shc_ws_return_items as s LEFT JOIN wp_shc_wholesale_customer as c ON s.customer_id = c.id ) as tot WHERE tot.active = 1 ${condition}";
		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

		    $status_query       = "SELECT SUM(total_amount) as total_amount FROM (${query}) AS combined_table";
			$data['s_result']   = $wpdb->get_row( $status_query );
			
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


		function ws_return_cancel_list_pagination( $args )
		{


			global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $customer_table =  $wpdb->prefix.'shc_customers';
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
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
		    }
		    $query 				= "SELECT * FROM (SELECT s.*, ( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as name, ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,( CASE WHEN c.gst_number IS NULL THEN 'Nil' ELSE c.gst_number END ) as gst_number, ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address FROM wp_shc_ws_return_items as s LEFT JOIN wp_shc_wholesale_customer as c ON s.customer_id = c.id ) as tot WHERE tot.cancel = 1 ${condition}";
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
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=ws_cancel_return_items')),
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


		function ws_cancel_return_list_pagination( $args )
		{


			global $wpdb;
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $customer_table =  $wpdb->prefix.'shc_customers';
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
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
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
	

// SELECT 
// SUM(final.amount) as amt,final.pay_type,
// sum(final.pay_to) as pay_to,sum(final.current_bal) as final_bal
// from 
// (
//     select 
//     (case when type.amount is null then '0.00' else type.amount end ) as amount,
//     type.created_at, 
//     (case when type.pay_type is null then 'null' else type.pay_type end ) as pay_type,
//     type.name,
//     type.mobile,
//     type.current_bal, 
//     (case when type.pay_to is null then '0.00' else type.pay_to end ) as pay_to
//     from ( 
//         SELECT 
//         payment.amount,
//         payment.pay_type as pay_type ,
//         payment.pay_to as pay_to,sale.* 
//         FROM (
//             SELECT s.*, ( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name, 
//             ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, 
//             ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address 
//             FROM wp_shc_sale as s LEFT JOIN wp_shc_customers as c ON s.customer_id = c.id WHERE s.locked = 1 ) 
//         as sale 
//         left join 
//         ( SELECT payment_type as pay_type,amount,sale_id,pay_to from wp_shc_payment WHERE active=1 ) 
//         as payment 
//         on payment.sale_id = sale.id where sale.active = 1 )
//     as type 
//     WHERE type.active = 1 ) 
// as final GROUP by final.pay_type

?>