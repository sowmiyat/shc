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
		        $this->comparison = isset($params['comparison']) ? $params['comparison'] : '';
		        $this->count = isset($params['count']) ? $params['count'] : '';


		    } else {
		        $this->cpage = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage = isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->brand_name = isset( $_GET['brand_name'] ) ? $_GET['brand_name']  : '';
		        $this->product_name = isset( $_GET['product_name'] ) ? $_GET['product_name']  : '';
		        $this->stock_from = isset( $_GET['stock_from'] ) ? $_GET['stock_from']  : '';
		        $this->stock_to = isset( $_GET['stock_to'] ) ? $_GET['stock_to']  : '';
		        $this->comparison = isset( $_GET['comparison'] ) ? $_GET['comparison']  : '';
		        $this->count = isset( $_GET['count'] ) ? $_GET['count']  : '';
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
	    	$page_arg['comparison'] = $this->comparison;
	    	$page_arg['count'] = $this->count;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->brand_name != '') {
		    	$condition .= " AND brand_name LIKE '".$this->brand_name."%' ";
		    }
		    if($this->product_name != '') {
		    	$condition .= " AND product_name LIKE '".$this->product_name."%' ";
		    }

		    if($this->comparison != '' && $this->count != ''){
		    	if( $this->comparison == 'less_than') {
		    		$compare = '<';
		    	}
		    	else {
		    		$compare = '>';
		    	}
		    	$condition .= " AND balance_stock ".$compare.$this->count;
		    }



			$query = "SELECT * from ( SELECT lot_table.*,(case when final_sale_tab.tot_sale is null then 0 else final_sale_tab.tot_sale end ) as tot_sale, (case when final_sale_tab.tot_sale is null then lot_table.stock_in else lot_table.stock_in - final_sale_tab.tot_sale end ) as balance_stock from (select lot_table.*,(case when stock_table.stock_in is null then 0 else stock_table.stock_in end )as stock_in from (select id,cgst,sgst,selling_price,`brand_name`,`product_name`,`hsn` from wp_shc_lots WHERE active = 1) as lot_table left join (select (case when sum(stock_count) is null then 0 else sum(stock_count) end )as stock_in,lot_number from wp_shc_stock WHERE active=1 GROUP by lot_number ) as stock_table on lot_table.id = stock_table.lot_number ) as lot_table left join (select (sum(total_sale_unit)) as tot_sale,lot_id from ( select (case when return_table.return_unit is null then sale_table.sale_unit else (sale_table.sale_unit - return_table.return_unit) end) as total_sale_unit,sale_table.lot_id from (SELECT (case when sum(sale.sale_unit ) is null then 0 ELSE sum(sale.sale_unit ) end) as sale_unit ,(case when (sale.lot_id) is null then 0 else (sale.lot_id) end) as lot_id FROM wp_shc_sale_detail as sale WHERE sale.active =1 group by lot_id) as sale_table left join (SELECT (case when sum(return_tab.return_unit ) is null then 0 ELSE sum(return_tab.return_unit ) end) as return_unit ,(case when (return_tab.lot_id) is null then 0 else (return_tab.lot_id) end) as lot_id FROM wp_shc_return_items_details as return_tab WHERE return_tab.active =1) as return_table on sale_table.lot_id = return_table.lot_id UNION all select (case when ws_return_table.return_unit is null then ws_sale_table.sale_unit else (ws_sale_table.sale_unit - ws_return_table.return_unit) end ) as total_sale_unit,ws_sale_table.lot_id from (SELECT (case when sum(ws_sale.sale_unit ) is null then 0 ELSE sum(ws_sale.sale_unit ) end) as sale_unit ,(case when (ws_sale.lot_id) is null then 0 else (ws_sale.lot_id) end) as lot_id FROM wp_shc_ws_sale_detail as ws_sale WHERE ws_sale.active =1 group by lot_id) as ws_sale_table left join (SELECT (case when sum(ws_return_tab.return_unit ) is null then 0 ELSE sum(ws_return_tab.return_unit ) end) as return_unit ,(case when (ws_return_tab.lot_id) is null then 0 else (ws_return_tab.lot_id) end) as lot_id FROM wp_shc_ws_return_items_details as ws_return_tab WHERE ws_return_tab.active =1) as ws_return_table on ws_sale_table.lot_id = ws_return_table.lot_id) as invoice_table GROUP by lot_id) final_sale_tab on final_sale_tab.lot_id = lot_table.id ) as final_tab WHERE final_tab.id > 0 ${condition}";	    
		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    
		    $data['total']      = $wpdb->get_var( $total_query );

		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']    = $wpdb->get_results( $query . " ORDER BY final_tab.product_name ASC  LIMIT ${offset}, ${args['items_per_page']}" );

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