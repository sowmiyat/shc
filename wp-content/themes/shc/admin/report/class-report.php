<?php
	class report {
 
		function __construct() {

		    if( isset($_POST['action'])) {
		    	$params = array();
				parse_str($_POST['data'], $params);

		        $this->cpage  = 1;
		        $this->ppage  = isset($params['ppage']) ? $params['ppage'] : 5;
		        $this->bill_from = isset($params['bill_from']) ? $params['bill_from'] : date('Y-m-01');
		        $this->bill_to = isset($params['bill_to']) ? $params['bill_to'] : date('Y-m-t');
		        $this->slap    = isset($params['slap']) ? $params['slap'] :'' ;

		    }  
		    else {
		        $this->cpage 		= isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage 		= isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 5;
		        $this->bill_from 	= isset( $_GET['bill_from'] ) ? $_GET['bill_from']  : date('Y-m-d');
		        $this->bill_to 		= isset( $_GET['bill_to'] ) ? $_GET['bill_to']  : date('Y-m-d');
		        $this->slap   = isset( $_GET['slap'] ) ? $_GET['slap']  : '';
		    }
		}



		function stock_report_pagination( $args ) {
		    global $wpdb;
		    $sale = $wpdb->prefix.'shc_sale';
		    $sale_details =  $wpdb->prefix.'shc_sale_detail';
		    $return_table = $wpdb->prefix.'shc_return_items_details';
		    $ws_sale = $wpdb->prefix.'shc_ws_sale';
		    $ws_sale_details = $wpdb->prefix.'shc_ws_sale_detail';
		    $ws_return_table = $wpdb->prefix.'shc_ws_return_items_details';
		    $lot_table = $wpdb->prefix.'shc_lots';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	    	$page_arg['product_name'] = $this->product_name;
	    	$page_arg['bill_from'] = $this->bill_from;
	    	$page_arg['bill_to'] = $this->bill_to;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';  


		    $bill_from = $this->bill_from;
		    $bill_to   = $this->bill_to;
   			if($this->slap != '') {
		    	$condition .= " WHERE report.gst = ".$this->slap;
		    }
		    

		    // $query 				= "SELECT tab.lot_id,sum(tab.bal_qty) as bal_qty,sum(tab.cgst_value) as cgst_value,sum(tab.sgst_value) as sgst_value,tab.brand_name,tab.product_name,tab.hsn,sum(tab.sub_total) as sub_total,tab.sgst,tab.cgst,tab.sale_update from 
						// 			(SELECT sale.lot_id,sale.brand_name,sale.product_name,sale.hsn,sale.quantity,rtn.return_unit,sale.cgst,sale.sgst,sale.sale_update,
						// 			(sale.quantity - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty,
						// 			(sale.cgst_value - (case when rtn.return_cgst_val is null then 0 else rtn.return_cgst_val END)) as cgst_value,
						// 			(sale.sgst_value - (case when rtn.return_sgst_val is null then 0 else rtn.return_sgst_val END)) as sgst_value,
						// 			(sale.sub_total - (case when rtn.return_sub_tot is null then 0 else rtn.return_sub_tot END)) as sub_total
						// 			from ( SELECT s.lot_id,sum(s.cgst_value) as cgst_value,sum(s.sgst_value) as sgst_value,sum(s.sub_total) as sub_total,sum(s.sale_unit) as quantity,l.brand_name,l.product_name,l.hsn,l.cgst,l.sgst,s.sale_update FROM ${ws_sale_details} as s left join ${lot_table} as l on l.id = s.lot_id WHERE s.active = 1 GROUP by s.lot_id ) as sale left join (SELECT sr.lot_id,sum(sr.return_unit ) as return_unit,sum(sr.cgst_value) as return_cgst_val,sum(sr.sgst_value) as return_sgst_val,sum(sr.sub_total) as return_sub_tot FROM ${ws_return_table} as sr WHERE sr.active = 1 GROUP by sr.lot_id ) as rtn on rtn.lot_id = sale.lot_id 
						// 			UNION all
						// 			SELECT sale.lot_id,sale.brand_name,sale.product_name,sale.hsn,sale.quantity,rtn.return_unit,sale.cgst,sale.sgst,sale.sale_update,
						// 			(sale.quantity - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty,
						// 			(sale.cgst_value - (case when rtn.return_cgst_val is null then 0 else rtn.return_cgst_val END)) as cgst_value,
						// 			(sale.sgst_value - (case when rtn.return_sgst_val is null then 0 else rtn.return_sgst_val END)) as sgst_value,
						// 			(sale.sub_total - (case when rtn.return_sub_tot is null then 0 else rtn.return_sub_tot END)) as sub_total
						// 			from ( SELECT s.lot_id,sum(s.cgst_value) as cgst_value,sum(s.sgst_value) as sgst_value,sum(s.sub_total) as sub_total,sum(s.sale_unit) as quantity,l.brand_name,l.product_name,l.hsn,l.cgst,l.sgst,s.sale_update FROM ${sale_details} as s left join ${lot_table} as l on l.id = s.lot_id WHERE s.active = 1 GROUP by s.lot_id ) as sale left join (SELECT sr.lot_id,sum(sr.return_unit ) as return_unit,sum(sr.cgst_value) as return_cgst_val,sum(sr.sgst_value) as return_sgst_val,sum(sr.sub_total) as return_sub_tot FROM ${return_table} as sr WHERE sr.active = 1 GROUP by sr.lot_id ) as rtn on rtn.lot_id = sale.lot_id ) as tab where tab.lot_id > 0 ${condition} group by tab.lot_id ";
			$query = "SELECT  report.*,lot.brand_name,lot.product_name,lot.hsn from (SELECT 
					(sum(final_ws_sale.bal_cgst)) as cgst_value,
				    (sum(final_ws_sale.bal_total)) as total,
				    (sum(final_ws_sale.bal_unit)) as total_unit,
				    (sum(final_ws_sale.bal_amt)) as amt,
					final_ws_sale.gst as gst,
                    final_ws_sale.lot_id
				      
					from (SELECT 
		(case when return_table.return_cgst is null then sale_table.sale_cgst else sale_table.sale_cgst - return_table.return_cgst end ) as bal_cgst, 
		(case when return_table.return_total is null then sale_table.sale_total else sale_table.sale_total - return_table.return_total end ) as bal_total,
		(case when return_table.return_unit is null then sale_table.sale_unit else  sale_table.sale_unit - return_table.return_unit end) as bal_unit,
		(case when return_table.return_amt is null then sale_table.sale_amt else sale_table.sale_amt - return_table.return_amt end ) as bal_amt,
							sale_table.cgst as gst,
                          	sale_table.lot_id
							FROM 
							(
                                SELECT sale_details.cgst,sale_details.lot_id,
                                sum(sale_details.cgst_value) as sale_cgst, 
                                sum(sale_details.sgst_value) sale_sgst, 
                                sum(sale_details.sub_total) as sale_total, 
                                sum(sale_details.sale_unit) as sale_unit,
                                sum(sale_details.amt) as sale_amt FROM ${sale} as sale left join ${sale_details} as sale_details on sale.`id`= sale_details.sale_id WHERE sale.active = 1 and sale_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by sale_details.lot_id
                            ) as sale_table 
							left join
							(
                                SELECT return_details.cgst,return_details.lot_id,
                                sum(return_details.cgst_value) as return_cgst, 
                                sum(return_details.sgst_value) as return_sgst, 
                                sum(return_details.sub_total) as return_total ,
                                sum(return_details.return_unit) as return_unit,
                                sum(return_details.amt) as return_amt FROM ${sale} as sale left join ${return_table} as return_details on sale.`id`= return_details.sale_id WHERE sale.active = 1 and return_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by return_details.lot_id
                            ) as return_table 
							on sale_table.lot_id = return_table.lot_id
                            
                            
                            union ALL
                            SELECT
		(case when ws_return_table.return_cgst is null then ws_sale_table.sale_cgst else ws_sale_table.sale_cgst - ws_return_table.return_cgst end ) as bal_cgst, 
		(case when ws_return_table.return_total is null then ws_sale_table.sale_total else ws_sale_table.sale_total - ws_return_table.return_total end ) as bal_total,
		(case when ws_return_table.return_unit is null then ws_sale_table.sale_unit else  ws_sale_table.sale_unit - ws_return_table.return_unit end) as bal_unit,
		(case when ws_return_table.return_amt is null then ws_sale_table.sale_amt else ws_sale_table.sale_amt - ws_return_table.return_amt end ) as bal_amt,
							ws_sale_table.cgst as gst,
                          	ws_sale_table.lot_id
							FROM 
							(
                                SELECT ws_sale_details.cgst,ws_sale_details.lot_id,
                                sum(ws_sale_details.cgst_value) as sale_cgst, 
                                sum(ws_sale_details.sgst_value) sale_sgst, 
                                sum(ws_sale_details.sub_total) as sale_total, 
                                sum(ws_sale_details.sale_unit) as sale_unit,
                                sum(ws_sale_details.amt) as sale_amt FROM ${ws_sale} as sale left join ${ws_sale_details} as ws_sale_details on sale.`id`= ws_sale_details.sale_id WHERE sale.active = 1 and ws_sale_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by ws_sale_details.lot_id
                            ) as ws_sale_table 
							left join
							(
                                SELECT ws_return_details.cgst,ws_return_details.lot_id,
                                sum(ws_return_details.cgst_value) as return_cgst, 
                                sum(ws_return_details.sgst_value) as return_sgst, 
                                sum(ws_return_details.sub_total) as return_total ,
                                sum(ws_return_details.return_unit) as return_unit,
                                sum(ws_return_details.amt) as return_amt FROM ${ws_sale} as sale left join ${ws_return_table} as  ws_return_details on sale.`id`= ws_return_details.sale_id WHERE sale.active = 1 and ws_return_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by ws_return_details.lot_id
                            ) as ws_return_table 
							on ws_sale_table.lot_id = ws_return_table.lot_id ) as final_ws_sale  group by  final_ws_sale.lot_id ) as report 
 left join ${lot_table} as lot on report.lot_id=lot.id ${condition}";


		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";

	        $status_query       = "SELECT SUM(cgst_value) as total_cgst,sum(total_unit) as sold_qty,sum(total) as sub_tot FROM (${query}) AS combined_table";
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
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=list_report')),
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


			function stock_report_pagination_accountant( $args ) {
		    global $wpdb;
		    $sale = $wpdb->prefix.'shc_sale';
		    $sale_details =  $wpdb->prefix.'shc_sale_detail';
		    $return_table = $wpdb->prefix.'shc_return_items_details';
		    $ws_sale = $wpdb->prefix.'shc_ws_sale';
		    $ws_sale_details = $wpdb->prefix.'shc_ws_sale_detail';
		    $ws_return_table = $wpdb->prefix.'shc_ws_return_items_details';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	    	$page_arg['bill_from'] = $this->bill_from;
	    	$page_arg['bill_to'] = $this->bill_to;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';  
		    
		    if($this->bill_from != '' && $this->bill_to != '') {
		    	$condition .= " AND DATE(sale.modified_at) >= DATE('".$this->bill_from."') AND DATE(sale.modified_at) <= DATE('".$this->bill_to."')";
		    } else if($this->bill_from != '' || $this->bill_to != '') {
		    	if($this->bill_from != '') {
		    		$condition .= " AND DATE(sale.modified_at) >= DATE('".$this->bill_from."') AND DATE(sale.modified_at) <= DATE('".$this->bill_from."')";
		    	} else {
		    		$condition .= " AND DATE(sale.modified_at) >= DATE('".$this->bill_to."') AND DATE(sale.modified_at) <= DATE('".$this->bill_to."')";
		    	}
		    }
		    $query 				= "SELECT 
					(sum(final_sale.bal_cgst) + sum(final_ws_sale.bal_cgst)) as cgst_value,
				    (sum(final_sale.bal_total) + sum(final_ws_sale.bal_total)) as total,
				    (sum(final_sale.bal_unit) + sum(final_ws_sale.bal_unit)) as total_unit,
				    (sum(final_sale.bal_amt) + sum(final_ws_sale.bal_amt)) as amt,
					final_ws_sale.gst as gst
				      
					from (SELECT 
							(case when return_table.return_cgst is null then sale_table.sale_cgst else sale_table.sale_cgst - return_table.return_cgst end ) as bal_cgst, 
							(case when return_table.return_total is null then sale_table.sale_total else sale_table.sale_total - return_table.return_total end ) as bal_total,
							(case when return_table.return_unit is null then sale_table.sale_unit else  sale_table.sale_unit - return_table.return_unit end) as bal_unit,
							(case when return_table.return_amt is null then sale_table.sale_amt else sale_table.sale_amt - return_table.return_amt end ) as bal_amt,
							sale_table.cgst as gst
							FROM 
							(
							SELECT sale_details.cgst,
							    sum(sale_details.cgst_value) as sale_cgst, 
							    sum(sale_details.sgst_value) sale_sgst, 
							    sum(sale_details.sub_total) as sale_total, 
							    sum(sale_details.sale_unit) as sale_unit,
							    sum(sale_details.amt) as sale_amt FROM ${sale} as sale left join ${sale_details} as sale_details on sale.`id`= sale_details.sale_id WHERE sale.active = 1 and sale_details.active = 1 ${condition} group by sale_details.cgst
							) as sale_table 
							left join
							(
							 SELECT return_details.cgst,
							    sum(return_details.cgst_value) as return_cgst, 
							    sum(return_details.sgst_value) as return_sgst, 
							    sum(return_details.sub_total) as return_total ,
							    sum(return_details.return_unit) as return_unit,
							    sum(return_details.amt) as return_amt FROM ${sale} as sale left join ${return_table} as return_details on sale.`id`= return_details.sale_id WHERE sale.active = 1 and return_details.active = 1 ${condition} group by return_details.cgst
							) as return_table 
							on sale_table.cgst = return_table.cgst) as final_sale 
				left JOIN
				            (
				                	SELECT 
		(case when ws_return_table.return_cgst is null then ws_sale_table.sale_cgst else ws_sale_table.sale_cgst - ws_return_table.return_cgst end ) as bal_cgst, 
		(case when ws_return_table.return_total is null then ws_sale_table.sale_total else ws_sale_table.sale_total - ws_return_table.return_total end ) as bal_total,
		(case when ws_return_table.return_unit is null then ws_sale_table.sale_unit else  ws_sale_table.sale_unit - ws_return_table.return_unit end) as bal_unit,
		(case when ws_return_table.return_amt is null then ws_sale_table.sale_amt else ws_sale_table.sale_amt - ws_return_table.return_amt end ) as bal_amt,
							ws_sale_table.cgst as gst
							FROM 
							(
							SELECT ws_sale_details.cgst,
							    sum(ws_sale_details.cgst_value) as sale_cgst, 
							    sum(ws_sale_details.sgst_value) sale_sgst, 
							    sum(ws_sale_details.sub_total) as sale_total, 
							    sum(ws_sale_details.sale_unit) as sale_unit,
							    sum(ws_sale_details.amt) as sale_amt FROM ${ws_sale} as sale left join ${ws_sale_details} as ws_sale_details on sale.`id`= ws_sale_details.sale_id WHERE sale.active = 1 and ws_sale_details.active = 1 ${condition} group by ws_sale_details.cgst
							) as ws_sale_table 
							left join
							(
							 SELECT ws_return_details.cgst,
							    sum(ws_return_details.cgst_value) as return_cgst, 
							    sum(ws_return_details.sgst_value) as return_sgst, 
							    sum(ws_return_details.sub_total) as return_total ,
							    sum(ws_return_details.return_unit) as return_unit,
							    sum(ws_return_details.amt) as return_amt FROM ${ws_sale} as sale left join ${ws_return_table} as ws_return_details on sale.`id`= ws_return_details.sale_id WHERE sale.active = 1 and ws_return_details.active = 1 ${condition} group by ws_return_details.cgst
							) as ws_return_table 
							on ws_sale_table.cgst = ws_return_table.cgst
				            ) as final_ws_sale 
				            on final_sale.gst = final_ws_sale.gst  group by final_ws_sale.gst";


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
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=list_report_account')),
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


//SELECT sale.created_at, sum(sale_details.cgst_value) as cgst_val, sum(sale_details.sgst_value) as sgst_val, sum(sale_details.sale_unit) as sale_unit, sum(sale_details.sub_total) as total FROM `wp_shc_sale` as sale left join wp_shc_sale_detail as sale_details on sale.`id`= sale_details.sale_id WHERE sale.active = 1 and sale_details.active = 1 and sale_details.cgst='9.00' and sale.created_at between '2017-09-09' and '2017-09-31' GROUP by DATE(sale.created_at)
//SELECT return_sale.created_at, sum(return_sale_details.cgst_value) as return_cgst, sum(return_sale_details.sgst_value) as return_sgst, sum(return_sale_details.return_unit) as return_unit, sum(return_sale_details.sub_total) as return_total FROM `wp_shc_return_items` as return_sale left join wp_shc_return_items_details as return_sale_details on return_sale.`id`= return_sale_details.sale_id WHERE return_sale.active = 1 and return_sale_details.active = 1 and return_sale_details.cgst='9.00' and return_sale.created_at between '2017-09-09' and '2017-09-31' GROUP by DATE(return_sale.created_at)
?>
