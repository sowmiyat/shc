<?php
	class Stocks {
 
		function __construct() {

		    if( isset($_POST['action']) ) {
		    	$params = array();
				parse_str($_POST['data'], $params);

		        $this->cpage = 1;
		        $this->ppage = isset($params['ppage']) ? $params['ppage'] : 20;
		        $this->brand_name = isset($params['brand_name']) ? $params['brand_name'] : '';
		        $this->product_name = isset($params['product_name']) ? $params['product_name'] : '';
		        $this->stock_from = isset($params['stock_from']) ? $params['stock_from'] : '';
		        $this->stock_to = isset($params['stock_to']) ? $params['stock_to'] : '';


		    } else {
		        $this->cpage = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage = isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->brand_name = isset( $_GET['brand_name'] ) ? $_GET['brand_name']  : '';
		        $this->product_name = isset( $_GET['product_name'] ) ? $_GET['product_name']  : '';
		        $this->stock_from = isset( $_GET['stock_from'] ) ? $_GET['stock_from']  : '';
		        $this->stock_to = isset( $_GET['stock_to'] ) ? $_GET['stock_to']  : '';
		    }
		}



		function stock_list_pagination_total( $args ) {
		    global $wpdb;
		    $stock_table =  $wpdb->prefix.'shc_stock';
		    $lots_table =  $wpdb->prefix.'shc_lots';
		    $stock_details = $wpdb->prefix.'shc_ws_sale_detail';
		    $sale =$wpdb->prefix.'shc_ws_sale';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['lot_no'] = $this->lot_no;
	        $page_arg['brand_name'] = $this->brand_name;
	    	$page_arg['product_name'] = $this->product_name;
	    	$page_arg['stock_from'] = $this->stock_from;
	    	$page_arg['stock_to'] = $this->stock_to;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->brand_name != '') {
		    	$condition .= " AND brand_name LIKE '".$this->brand_name."%' ";
		    }
		    if($this->product_name != '') {
		    	$condition .= " AND product_name LIKE '".$this->product_name."%' ";
		    }

		    if($this->stock_from != '' && $this->stock_to != '') {
		    	$condition .= " AND DATE(final_table.created_at) >= DATE('".$this->stock_from."') AND DATE(final_table.created_at) <= DATE('".$this->stock_to."')";
		    } else if($this->stock_from != '' || $this->stock_to != '') {
		    	if($this->stock_from != '') {
		    		$condition .= " AND DATE(final_table.created_at) >= DATE('".$this->stock_from."') AND DATE(final_table.created_at) <= DATE('".$this->stock_from."')";
		    	} else {
		    		$condition .= " AND DATE(final_table.created_at) >= DATE('".$this->stock_to."') AND DATE(final_table.created_at) <= DATE('".$this->stock_to."')";
		    	}
		    }

		 //    $query 				= "SELECT * from
			// (SELECT l.*, s.id as stock_id, s.stock_count, s.selling_total, s.created_at as stock_created FROM {$stock_table} as s JOIN {$lots_table} as l ON s.lot_number = l.id WHERE s.active = 1) as stock_table
			// LEFT JOIN 
			// (SELECT  t1.lot_number, 
			// ( CASE 
			// WHEN t1.stock_qty IS NULL
			// THEN 0
			// ELSE t1.stock_qty
			// END ) as stock_qty,
			// ( CASE 
			// WHEN t2.sale_qty IS NULL
			// THEN 0
			// ELSE t2.sale_qty
			// END ) as sale_qty,

			// (
			//     ( CASE 
			//     WHEN t1.stock_qty IS NULL
			//     THEN 0
			//     ELSE t1.stock_qty
			//     END )
			//     - 
			//     ( CASE 
			//     WHEN t2.sale_qty IS NULL
			//     THEN 0
			//     ELSE t2.sale_qty
			//     END )

			// ) as bal_stock 

			// FROM 
			// (
			// SELECT st.lot_number, SUM(st.stock_count) as stock_qty FROM {$stock_table} as st WHERE st.active = 1 GROUP BY st.lot_number
			// ) as t1
			// LEFT JOIN
			// (
			// SELECT sd.lot_id, SUM(sd.sale_unit) as sale_qty FROM {$stock_details} as sd JOIN {$sale} as s ON sd.sale_id = s.id WHERE sd.active = 1 AND s.active = 1 and sd.item_status='open' GROUP BY sd.lot_id
			// ) as t2
			// ON t1.lot_number    = t2.lot_id) as balance_table
			// ON stock_table.id   = balance_table.lot_number where stock_table.active = 1 GROUP by `id` ${condition}";

			$query = "SELECT final_table.*,( final_table.stock_in - final_table.bal_qty) as final_stock from ( SELECT bal.*,stock.lot_number,stock.stock_in,stock.created_at from (SELECT tab.lot_id,sum(tab.bal_qty) as bal_qty,tab.cgst,tab.sgst,tab.selling_price,tab.brand_name,tab.product_name,tab.sale_update from (SELECT sale.lot_id,sale.cgst,sale.sgst,sale.selling_price,sale.sale_update,sale.brand_name,sale.product_name, (sale.sale_unit - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty from (SELECT s.*, l.brand_name, l.product_name,l.hsn,l.selling_price FROM wp_shc_sale_detail as s left join wp_shc_lots as l on l.id = s.lot_id WHERE s.active = 1 ) as sale left join (SELECT sum(sr.return_unit ) as return_unit ,sr.lot_id FROM wp_shc_return_items_details as sr WHERE sr.active = 1 ) as rtn on rtn.lot_id = sale.lot_id union all SELECT sale.lot_id,sale.cgst,sale.sgst,sale.selling_price,sale.sale_update,sale.brand_name,sale.product_name, (sale.sale_unit - (case when rtn.return_unit is null then 0 else rtn.return_unit END)) as bal_qty from (SELECT s.*, l.brand_name, l.product_name,l.hsn,l.selling_price FROM wp_shc_ws_sale_detail as s left join wp_shc_lots as l on l.id = s.lot_id WHERE s.active = 1 ) as sale left join (SELECT sum(sr.return_unit ) as return_unit ,sr.lot_id FROM wp_shc_ws_return_items_details as sr WHERE sr.active = 1 ) as rtn on rtn.lot_id = sale.lot_id ) as tab group by tab.lot_id) as bal left join (select lot_number,sum(stock_count) as stock_in,created_at from wp_shc_stock GROUP by lot_number ) as stock on bal.lot_id = stock.lot_number) as final_table where final_table.stock_in > 0 ${condition}";

		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    
		    $data['total']      = $wpdb->get_var( $total_query );

		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']    = $wpdb->get_results( $query . "ORDER BY final_table.stock_in ASC  LIMIT ${offset}, ${args['items_per_page']}" );
		    $totalPage         = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1) {
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=total_stock_list')),
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


		function stock_list_pagination( $args ) {
		    global $wpdb;
		    $stock_table =  $wpdb->prefix.'shc_stock';
		    $lots_table =  $wpdb->prefix.'shc_lots';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['brand_name'] = $this->brand_name;
	    	$page_arg['product_name'] = $this->product_name;
	    	$page_arg['stock_from'] = $this->stock_from;
	    	$page_arg['stock_to'] = $this->stock_to;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->brand_name != '') {
		    	$condition .= " AND l.brand_name LIKE '".$this->brand_name."%' ";
		    }
		    if($this->product_name != '') {
		    	$condition .= " AND l.product_name LIKE '".$this->product_name."%' ";
		    }

		    if($this->stock_from != '' && $this->stock_to != '') {
		    	$condition .= " AND DATE(s.created_at) >= DATE('".$this->stock_from."') AND DATE(s.created_at) <= DATE('".$this->stock_to."')";
		    } else if($this->stock_from != '' || $this->stock_to != '') {
		    	if($this->stock_from != '') {
		    		$condition .= " AND DATE(s.created_at) >= DATE('".$this->stock_from."') AND DATE(s.created_at) <= DATE('".$this->stock_from."')";
		    	} else {
		    		$condition .= " AND DATE(s.created_at) >= DATE('".$this->stock_to."') AND DATE(s.created_at) <= DATE('".$this->stock_to."')";
		    	}
		    }

		    $query 				= "SELECT l.*, s.id as stock_id, s.stock_count, s.selling_total, s.created_at as stock_created FROM ${stock_table} as s JOIN ${lots_table} as l ON s.lot_number = l.id WHERE s.active = 1 ${condition}";

		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    $data['total']      = $wpdb->get_var( $total_query );
		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']     = $wpdb->get_results( $query . "ORDER BY brand_name ASC,stock_count ASC LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage          = ceil($data['total']  / $args['items_per_page']);

		    if($totalPage > 1) {
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=list_stocks')),
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