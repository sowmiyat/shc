<?php
	class creditdebit { 
		function __construct() {

		    if( isset($_POST['action']) ) {
		    	$params = array();
				parse_str($_POST['data'], $params);

		        $this->cpage = 1;
		        $this->ppage = isset($params['ppage']) ? $params['ppage'] : 20;
		        $this->type = isset($params['type']) ? $params['type'] : '';
		        $this->amount = isset($params['amount']) ? $params['amount'] : '';
		        $this->date = isset($params['date']) ? $params['date'] : '';


		    } else {
		        $this->cpage = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage = isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->type = isset( $_GET['type'] ) ? $_GET['type']  : '';
		        $this->amount = isset( $_GET['amount'] ) ? $_GET['amount']  : '';
		        $this->date = isset( $_GET['date'] ) ? $_GET['date']  : '';
		    }
		}
		function creditdebit_list_pagination( $args ) {
		    global $wpdb;
		    $table =  $wpdb->prefix.'shc_creditdebit';
		    $customPagHTML      = "";

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['type'] = $this->type;
	        $page_arg['amount'] = $this->amount;
	    	$page_arg['date'] = $this->date;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    // if($this->type != '-') {
		    // 	$condition .= " AND type LIKE '".$this->type."%' ";
		    // }
		    // if($this->amount != '') {
		    // 	$condition .= " AND amount <= '".$this->amount."' ";
		    // }
		    if($this->date != '') {
		    	$condition .= " AND date LIKE '%".$this->date."%' ";
		    }

		    $query              = "SELECT * FROM ${table} WHERE active = 1 ${condition}";

		    $total_query        = "SELECT COUNT(1) FROM (${query}) AS combined_table";
		    $data['total']              = $wpdb->get_var( $total_query );
		    //$page               = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : abs( (int) $args['page'] );
		    $page               = $this->cpage;
		    $ppage 				= $this->ppage;
		    $offset             = ( $page * $args['items_per_page'] ) - $args['items_per_page'] ;

		    $data['result']     = $wpdb->get_results( $query . "ORDER BY ${args['orderby_field']} ${args['order_by']} LIMIT ${offset}, ${args['items_per_page']}" );

		    $totalPage          = ceil($data['total'] / $args['items_per_page']);

		    if($totalPage > 1){
		        $data['start_count'] = ($ppage * ($page-1));

		        $pagination = paginate_links( array(
		                'base' => add_query_arg( $page_arg , admin_url('admin.php?page=credit_debit')),
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