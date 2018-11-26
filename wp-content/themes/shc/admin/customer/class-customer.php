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
		        $this->company_name = isset($params['company_name']) ? $params['company_name'] : '';


		    } else {
		        $this->cpage = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		        $this->ppage = isset( $_GET['ppage'] ) ? abs( (int) $_GET['ppage'] ) : 20;
		        $this->name = isset( $_GET['name'] ) ? $_GET['name']  : '';
		        $this->mobile = isset( $_GET['mobile'] ) ? $_GET['mobile']  : '';
		        $this->customer_from = isset( $_GET['customer_from'] ) ? $_GET['customer_from']  : '';
		        $this->customer_to = isset( $_GET['customer_to'] ) ? $_GET['customer_to']  : '';
		        $this->sale_total = isset( $_GET['sale_total'] ) ? $_GET['sale_total']  : '';
		        $this->company_name = isset( $_GET['company_name'] ) ? $_GET['company_name']  : '';
		    }
		}



		function customer_list_pagination( $args ) {
		    global $wpdb;
			$sale_table 	= $wpdb->prefix.'shc_sale';
			$return_table 	= $wpdb->prefix.'shc_return_items';
			$payment_table  = $wpdb->prefix.'shc_payment';
			$customer_table = $wpdb->prefix.'shc_customers';
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
		    	$condition .= " AND customer_name LIKE '%".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND mobile LIKE '%".$this->mobile."%' ";
		    }

		    if($this->customer_from != '' && $this->customer_to != '') { 
		    	$condition .= " AND DATE(created_at) >= DATE('".$this->customer_from."') AND DATE(created_at) <= DATE('".$this->customer_to."')";
		    } else if($this->customer_from != '' || $this->customer_to != '') {
		    	if($this->customer_from != '') {
		    		$condition .= " AND DATE(created_at) >= DATE('".$this->customer_from."') AND DATE(created_at) <= DATE('".$this->customer_from."')";
		    	} else {
		    		$condition .= " AND DATE(created_at) >= DATE('".$this->customer_to."') AND DATE(created_at) <= DATE('".$this->customer_to."')";
		    	}
		    }

		  if($this->sale_total != '') {
				$condition .= " AND new_sale_total1 >= '".$this->sale_total."' ";
			}
		    

			$query = "SELECT * from 
			(
				SELECT cus_full_detail.customer_id,
			cus_full_detail.customer_name,
			cus_full_detail.address,
			cus_full_detail.mobile,
			cus_full_detail.created_at,
			cus_full_detail.modified_at,
			sum(cus_full_detail.new_sale_total) as new_sale_total1,
			sum(cus_full_detail.final_bal) as final_bal
			FROM (
				SELECT full_table.cus_id as customer_id,full_table.name as customer_name,full_table.address,full_table.mobile,full_table.created_at,full_table.modified_at,
			(case when  full_table.sale_id is null then 0.00 else full_table.sale_id end ) as sale_id,
			(case when  full_table.search_id is null then 0.00 else full_table.search_id end ) as search_id,
			(case when  full_table.year is null then 0.00 else full_table.year end ) as year,
			(case when  full_table.sale_total is null then 0.00 else full_table.sale_total end ) as sale_total,
			(case when  full_table.paid_amount is null then 0.00 else full_table.paid_amount end ) as paid_amount,
			(case when  full_table.key_amount is null then 0.00 else full_table.key_amount end ) as key_amount,
			(case when  full_table.return_amount is null then 0.00 else full_table.return_amount end ) as return_amount,
			(case when  full_table.invoice_bill_bal is null then 0.00 else full_table.invoice_bill_bal end ) as invoice_bill_bal,
			(case when  full_table.return_bill_bal is null then 0.00 else full_table.return_bill_bal end ) as return_bill_bal,
			(case when  full_table.new_sale_total is null then 0.00 else full_table.new_sale_total end ) as new_sale_total,
			(case when  full_table.final_bal is null then 0.00 else full_table.final_bal end ) as final_bal
			from  
			( 
			    select * from (
			    SELECT id as cus_id,name,mobile,address,created_at,modified_at FROM ${customer_table}  WHERE active = 1
			) as customer
			left join 
			(
				SELECT tab.*,(tab.invoice_bill_bal - tab.return_bill_bal) as final_bal  from (
			    select final_tab.*, 
			(final_tab.sale_total- final_tab.paid_amount) as invoice_bill_bal,
			(final_tab.return_amount- final_tab.key_amount) as return_bill_bal,
			(final_tab.sale_total - final_tab.return_amount ) as new_sale_total
			from ( 
			    select bill_table.*,
			(case when return_tab.key_amount is null then 0.00 else return_tab.key_amount end) as key_amount,
			(case when return_tab.return_amount is null then 0.00 else return_tab.return_amount end) as return_amount
			from 
			(
			    SELECT sale.inv_id as sale_id,
			    sale.customer_id,
			    sale.search_id,
			    sale.year,sale.sale_total,
			    (case when payment.payment_amount is null then 0.00 else payment.payment_amount-sale.pay_to_bal  end) as paid_amount 
			    from 
			(
			    SELECT id as inv_id,customer_id,
			    `inv_id` as search_id,
			    `financial_year` as year,
			    `sub_total` as sale_total,`pay_to_bal` FROM ${sale_table} WHERE`active`=1 
			)  as sale
			left join 
			( 
			    select 	sale_id,sum(amount) as payment_amount from ${payment_table} WHERE active = 1 and 	payment_type!= 'credit' GROUP by sale_id
			)  as payment
			on sale.inv_id = payment.sale_id
			) as bill_table 
			left join  
			(
			    SELECT inv_id,key_amount,total_amount as return_amount from {$return_table} WHERE active = 1
			) 
			as return_tab 
			on bill_table.sale_id = return_tab.inv_id )
			as final_tab  
			) as tab 
			)
			as full_sale_tab  
			on full_sale_tab.customer_id = customer.cus_id 
			) as full_table )
			as cus_full_detail GROUP by cus_full_detail.customer_id )
			AS ff  WHERE ff.customer_id != 0 ${condition}";
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
		    	$condition .= " AND sc.name LIKE '%".$this->name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND sc.mobile LIKE '%".$this->mobile."%' ";
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

		    $sale_table 	= $wpdb->prefix.'shc_ws_sale';
			$return_table 	= $wpdb->prefix.'shc_ws_return_items';
			$payment_table  = $wpdb->prefix.'shc_ws_payment';
			$customer_table = $wpdb->prefix.'shc_wholesale_customer';
		    $customPagHTML      = "";		    

			$page_arg = [];
			$page_arg['ppage'] = $args['items_per_page'];
	        $page_arg['name'] = $this->name;
	        $page_arg['company_name'] = $this->company_name;
	        $page_arg['mobile'] = $this->mobile;
	    	$page_arg['customer_from'] = $this->customer_from;
	    	$page_arg['customer_to'] = $this->customer_to;
	    	$page_arg['sale_total'] = $this->sale_total;
		    $page_arg['cpage'] = '%#%';

		    $condition = '';
		    if($this->name != '') {
		    	$condition .= " AND ff.customer_name LIKE '%".$this->name."%' ";
		    }
		    if($this->company_name != '') {
		    	$condition .= " AND ff.company_name LIKE '%".$this->company_name."%' ";
		    }
		    if($this->mobile != '') {
		    	$condition .= " AND ff.mobile LIKE '%".$this->mobile."%' ";
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
				$condition .= " AND ff.sub_total >= '".$this->sale_total."' ";
			}
		    


$query = "SELECT * from 
(
	SELECT cus_full_detail.customer_id,
	cus_full_detail.company_name,
cus_full_detail.customer_name,
cus_full_detail.address,
cus_full_detail.mobile,
cus_full_detail.gst_number,
cus_full_detail.created_at,
cus_full_detail.modified_at,
sum(cus_full_detail.new_sale_total) as new_sale_total1,
sum(cus_full_detail.final_bal) as final_bal
FROM (
	SELECT full_table.cus_id as customer_id,full_table.name as customer_name,full_table.company_name as company_name,full_table.address,full_table.gst_number,full_table.mobile,full_table.created_at,full_table.modified_at,
(case when  full_table.sale_id is null then 0.00 else full_table.sale_id end ) as sale_id,
(case when  full_table.search_id is null then 0.00 else full_table.search_id end ) as search_id,
(case when  full_table.year is null then 0.00 else full_table.year end ) as year,
(case when  full_table.sale_total is null then 0.00 else full_table.sale_total end ) as sale_total,
(case when  full_table.paid_amount is null then 0.00 else full_table.paid_amount end ) as paid_amount,
(case when  full_table.key_amount is null then 0.00 else full_table.key_amount end ) as key_amount,
(case when  full_table.return_amount is null then 0.00 else full_table.return_amount end ) as return_amount,
(case when  full_table.invoice_bill_bal is null then 0.00 else full_table.invoice_bill_bal end ) as invoice_bill_bal,
(case when  full_table.return_bill_bal is null then 0.00 else full_table.return_bill_bal end ) as return_bill_bal,
(case when  full_table.new_sale_total is null then 0.00 else full_table.new_sale_total end ) as new_sale_total,
(case when  full_table.final_bal is null then 0.00 else full_table.final_bal end ) as final_bal
from  
( 
    select * from (
    SELECT id as cus_id,customer_name as name,company_name,mobile,address,gst_number,created_at,modified_at FROM ${customer_table}  WHERE active = 1
) as customer
left join 
(
	SELECT tab.*,(tab.invoice_bill_bal - tab.return_bill_bal) as final_bal  from (
    select final_tab.*, 
(final_tab.sale_total- final_tab.paid_amount) as invoice_bill_bal,
(final_tab.return_amount- final_tab.key_amount) as return_bill_bal,
(final_tab.sale_total - final_tab.return_amount ) as new_sale_total
from ( 
    select bill_table.*,
(case when return_tab.key_amount is null then 0.00 else return_tab.key_amount end) as key_amount,
(case when return_tab.return_amount is null then 0.00 else return_tab.return_amount end) as return_amount
from 
(
    SELECT sale.inv_id as sale_id,
    sale.customer_id,
    sale.search_id,
    sale.year,sale.sale_total,
    (case when payment.payment_amount is null then 0.00 else payment.payment_amount-sale.pay_to_bal  end) as paid_amount 
    from 
(
    SELECT id as inv_id,customer_id,
    `inv_id` as search_id,
    `financial_year` as year,
    `sub_total` as sale_total,`pay_to_bal` FROM ${sale_table} WHERE`active`=1 
)  as sale
left join 
( 
    select 	sale_id,sum(amount) as payment_amount from ${payment_table} WHERE active = 1 and 	payment_type!= 'credit' GROUP by sale_id
)  as payment
on sale.inv_id = payment.sale_id
) as bill_table 
left join  
(
    SELECT inv_id,key_amount,total_amount as return_amount from {$return_table} WHERE active = 1
) 
as return_tab 
on bill_table.sale_id = return_tab.inv_id )
as final_tab  
) as tab 
)
as full_sale_tab  
on full_sale_tab.customer_id = customer.cus_id 
) as full_table )
as cus_full_detail GROUP by cus_full_detail.customer_id )
AS ff  WHERE ff.customer_id != 0 ${condition}";

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