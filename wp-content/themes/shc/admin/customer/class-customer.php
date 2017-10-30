<?php
	class Customer {
 
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
		        $this->sale_total = isset($params['sale_total']) ? $params['sale_total'] : '';


		    } else {
		        $this->cpage = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage = isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->name = isset( $_GET['name'] ) ? $_GET['name']  : '';
		        $this->mobile = isset( $_GET['mobile'] ) ? $_GET['mobile']  : '';
		        $this->customer_from = isset( $_GET['customer_from'] ) ? $_GET['customer_from']  : '';
		        $this->customer_to = isset( $_GET['customer_to'] ) ? $_GET['customer_to']  : '';
		        $this->sale_total = isset( $_GET['sale_total'] ) ? $_GET['sale_total']  : '';
		    }




		}



		function customer_list_pagination( $args ) {
		    global $wpdb;
		    $customer_table =  $wpdb->prefix.'shc_customers';
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $return_table =  $wpdb->prefix.'shc_return_items';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['name'] = $this->name;
	        $page_arg['mobile'] = $this->mobile;
	    	$page_arg['customer_from'] = $this->customer_from;
	    	$page_arg['customer_to'] = $this->customer_to;
	    	$page_arg['sale_total'] = $this->sale_total;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->name != '') {
		    	$condition .= " AND ff.name LIKE '".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND ff.mobile LIKE '".$this->mobile."%' ";
		    }

		    if($this->customer_from != '' && $this->customer_to != '') { 
		    	$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_from."') AND DATE(ff.created_at) <= DATE('".$this->customer_to."')";
		    } else if($this->customer_from != '' || $this->customer_to != '') {
		    	if($this->customer_from != '') {
		    		$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_from."') AND DATE(ff.created_at) <= DATE('".$this->customer_from."')";
		    	} else {
		    		$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_to."') AND DATE(ff.created_at) <= DATE('".$this->customer_to."')";
		    	}
		    }

		  if($this->sale_total != '') {
				$condition .= " AND total_buy >= '".$this->sale_total."' ";
			}
		    

		    $query 				= "SELECT * from ( SELECT sc.*, 
(case when f.total_credit is null then 0.00 else f.total_credit end ) as balance,
    (case when f.total_buy is null then 0.00 else f.total_buy end ) as total_buy,
        (case when f.total_return is null then 0.00 else f.total_return end ) as total_return,
		(case when f.paid is null then 0.00 else f.paid end ) as paid
FROM ${customer_table} as sc LEFT JOIN ( 
    select sale_customer.*,
    (case when  return_customer.total_return is null then sale_customer.total_sale else sale_customer.total_sale-return_customer.total_return end) as total_credit ,
    (case when return_customer.total_return is null then 0 else return_customer.total_return end)  as total_return
    from (
       SELECT c.id as cus_id, (SUM(s.sub_total)-SUM(s.paid_amount)) as total_sale,sum(s.paid_amount) as paid,sum(s.sub_total) as total_buy FROM ${customer_table} as c LEFT JOIN ${sale_table} as s ON c.id = s.customer_id WHERE c.active = 1 AND s.active = 1 GROUP BY c.id
    ) as sale_customer left join 
    (
        select customer_id,sum(total_amount) as total_return from ${return_table}  GROUP by customer_id 
    ) as return_customer
    on sale_customer.cus_id = return_customer.customer_id 
) as f 
ON sc.id = f.cus_id ) as ff WHERE ff.active = 1  ${condition}";


	    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    $data['total']              = $wpdb->get_var( $total_query );
		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage         = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=customer_list')),
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
		    
		    if( $end_count == 0){
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;
		}



		function customer_list_pagination_dashboard( $args ) {
		    global $wpdb;
		  	$customer_table =  $wpdb->prefix.'shc_customers';
		    $sale_table =  $wpdb->prefix.'shc_sale';
		    $return_table =  $wpdb->prefix.'shc_return_items';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['name'] = $this->name;
	        $page_arg['mobile'] = $this->mobile;
	    	$page_arg['customer_from'] = $this->customer_from;
	    	$page_arg['customer_to'] = $this->customer_to;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->name != '') {
		    	$condition .= " AND sc.name LIKE '".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND sc.mobile LIKE '".$this->mobile."%' ";
		    }

		    if($this->customer_from != '' && $this->customer_to != '') { 
		    	$condition .= " AND DATE(sc.created_at) >= DATE('".$this->customer_from."') AND DATE(sc.created_at) <= DATE('".$this->customer_to."')";
		    } else if($this->customer_from != '' || $this->customer_to != '') {
		    	if($this->customer_from != '') {
		    		$condition .= " AND DATE(sc.created_at) >= DATE('".$this->customer_from."') AND DATE(sc.created_at) <= DATE('".$this->customer_from."')";
		    	} else {
		    		$condition .= " AND DATE(sc.created_at) >= DATE('".$this->customer_to."') AND DATE(sc.created_at) <= DATE('".$this->customer_to."')";
		    	}
		    }


		    $query 				= "SELECT * from ( SELECT sc.*, 
(case when f.total_credit is null then 0.00 else f.total_credit end ) as balance,
    (case when f.total_buy is null then 0.00 else f.total_buy end ) as total_buy,
        (case when f.total_return is null then 0.00 else f.total_return end ) as total_return,
		(case when f.paid is null then 0.00 else f.paid end ) as paid
FROM ${customer_table} as sc LEFT JOIN ( 
    select sale_customer.*,
    (case when  return_customer.total_return is null then sale_customer.total_sale else sale_customer.total_sale-return_customer.total_return end) as total_credit ,
    (case when return_customer.total_return is null then 0 else return_customer.total_return end)  as total_return
    from (
       SELECT c.id as cus_id, (SUM(s.sub_total)-SUM(s.paid_amount)) as total_sale,sum(s.paid_amount) as paid,sum(s.sub_total) as total_buy FROM ${customer_table} as c LEFT JOIN ${sale_table} as s ON c.id = s.customer_id WHERE c.active = 1 AND s.active = 1 GROUP BY c.id
    ) as sale_customer left join 
    (
        select customer_id,sum(total_amount) as total_return from ${return_table}  GROUP by customer_id 
    ) as return_customer
    on sale_customer.cus_id = return_customer.customer_id 
) as f 
ON sc.id = f.cus_id ) as ff WHERE ff.active = 1 ";
		   // $query              = "SELECT * FROM ${table} WHERE active = 1 ${condition}";


		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    $total              = $wpdb->get_var( $total_query );
		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']         = $wpdb->get_results( $query . " ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage         = ceil($total / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=customer_list')),
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
		    
		    if( $end_count == 0){
		    	$start_count = 0;
		    }
		    else {
		    	$start_count = $data['start_count'] + 1;
		    }
		    $data['status_txt'] = "<div class='dataTables_info' role='status' aria-live='polite'>Showing ".$start_count." to ".$end_count." of ".$data['total']." entries</div>";
		    return $data;
		}



		function wholesale_customer_list_pagination( $args ) {
		    global $wpdb;
		    $customer_table =  $wpdb->prefix.'shc_wholesale_customer';
		    $sale_table =  $wpdb->prefix.'shc_ws_sale';
		    $return_table =  $wpdb->prefix.'shc_ws_return_items';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['name'] = $this->name;
	        $page_arg['mobile'] = $this->mobile;
	    	$page_arg['customer_from'] = $this->customer_from;
	    	$page_arg['customer_to'] = $this->customer_to;
	    	$page_arg['sale_total'] = $this->sale_total;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->name != '') {
		    	$condition .= " AND ff.customer_name LIKE '".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND ff.mobile LIKE '".$this->mobile."%' ";
		    }

		    if($this->customer_from != '' && $this->customer_to != '') { 
		    	$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_from."') AND DATE(ff.created_at) <= DATE('".$this->customer_to."')";
		    } else if($this->customer_from != '' || $this->customer_to != '') {
		    	if($this->customer_from != '') {
		    		$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_from."') AND DATE(ff.created_at) <= DATE('".$this->customer_from."')";
		    	} else {
		    		$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_to."') AND DATE(ff.created_at) <= DATE('".$this->customer_to."')";
		    	}
		    }

		    if($this->customer_from != '' && $this->customer_to != '') { 
		    	$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_from."') AND DATE(ff.created_at) <= DATE('".$this->customer_to."')";
		    } else if($this->customer_from != '' || $this->customer_to != '') {
		    	if($this->customer_from != '') {
		    		$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_from."') AND DATE(ff.created_at) <= DATE('".$this->customer_from."')";
		    	} else {
		    		$condition .= " AND DATE(ff.created_at) >= DATE('".$this->customer_to."') AND DATE(ff.created_at) <= DATE('".$this->customer_to."')";
		    	}
		    }
			if($this->sale_total != '') {
				$condition .= " AND total_buy >= '".$this->sale_total."' ";
			}
		    

		    $query 				= "SELECT * from ( SELECT sc.*, 
(case when f.total_credit is null then 0.00 else f.total_credit end ) as balance,
    (case when f.total_buy is null then 0.00 else f.total_buy end ) as total_buy,
        (case when f.total_return is null then 0.00 else f.total_return end ) as total_return,
		(case when f.paid is null then 0.00 else f.paid end ) as paid
FROM ${customer_table} as sc LEFT JOIN ( 
    select sale_customer.*,
    (case when  return_customer.total_return is null then sale_customer.total_sale else sale_customer.total_sale-return_customer.total_return end) as total_credit ,
    (case when return_customer.total_return is null then 0 else return_customer.total_return end)  as total_return
    from (
       SELECT c.id as cus_id, (SUM(s.sub_total)-SUM(s.paid_amount)) as total_sale,sum(s.paid_amount) as paid,sum(s.sub_total) as total_buy FROM ${customer_table} as c LEFT JOIN ${sale_table} as s ON c.id = s.customer_id WHERE c.active = 1 AND s.active = 1 GROUP BY c.id
    ) as sale_customer left join 
    (
        select customer_id,sum(total_amount) as total_return from ${return_table}  GROUP by customer_id 
    ) as return_customer
    on sale_customer.cus_id = return_customer.customer_id 
) as f 
ON sc.id = f.cus_id ) as ff WHERE ff.active = 1 ${condition}";
	   // $query              = "SELECT * FROM ${table} WHERE active = 1 ${condition}";
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
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=wholesale_customer')),
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
		     if( $end_count == 0){
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