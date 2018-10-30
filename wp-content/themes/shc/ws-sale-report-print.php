<?php
/**
 * Template Name: WholeSale Report Print
 *
 * @package WordPress
 * @subpackage SHC
 */


 


            global $wpdb;
            $sale_table =  $wpdb->prefix.'shc_ws_sale';
            $customer_table =  $wpdb->prefix.'shc_wholesale_customer';
            $sale_detail =  $wpdb->prefix.'shc_ws_sale_detail';
            $payment_table =  $wpdb->prefix.'shc_ws_payment';

            $condition = '';  

            
            if($_GET['inv_id'] != '') {
                $condition .= " AND id = ".$_GET['inv_id'];
            }

            if($_GET['cus_name'] != '') {
                $condition .= " AND name LIKE '%".$_GET['cus_name']."%' ";
            }
            if($_GET['mobile'] != '') {
                $condition .= " AND mobile LIKE '%".$_GET['mobile']."%' ";
            }
            if($_GET['bill_from'] != '' && $_GET['bill_to'] != '') {
                $condition .= " AND DATE(created_at) >= DATE('".$_GET['bill_from']."') AND DATE(created_at) <= DATE('".$_GET['bill_to']."')";
            } else if($_GET['bill_from'] != '' || $_GET['bill_to'] != '') {
                if($_GET['bill_from'] != '') {
                    $condition .= " AND DATE(created_at) >= DATE('".$_GET['bill_from']."') AND DATE(created_at) <= DATE('".$_GET['bill_from']."')";
                } else {
                    $condition .= " AND DATE(created_at) >= DATE('".$_GET['bill_to']."') AND DATE(created_at) <= DATE('".$_GET['bill_to']."')";
                }
            }

            $query = "SELECT * FROM ( SELECT * FROM (SELECT s.*,
                ( CASE WHEN c.customer_name  IS NULL THEN 'Nil' ELSE c.customer_name  END ) as name,
                ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
                ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
                FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.active = 1 ) as sale left join 
                 (
                 select sale_id,
                (case  when s_count = d_count then 1 else 0 end )as delivered
                from  (SELECT sum(`sale_unit`) as s_count ,sum(`delivery_count`) as d_count,`sale_id`,`active` FROM {$sale_detail} as sale_detail  WHERE active=1  GROUP by sale_id ) ful_tab
                 ) as sale_detail on sale.id = sale_detail.sale_id WHERE sale.active = 1  ${condition}";

          $results = $wpdb->get_results( $query );
        $status_query       = "SELECT SUM(sub_total) as total_amount,sum(cod_amount) as cod FROM (${query}) AS combined_table";
            $s_result   = $wpdb->get_row( $status_query );

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

                
                
                $cash_query  = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'cash'";
                $ToPay_query = "SELECT sale.*,sum(sale.pay_to_bal) as pay_to FROM ( 
                     SELECT s.*, 
                     ( CASE WHEN c.customer_name IS NULL THEN 'Nil' ELSE c.customer_name END ) as name, 
                     ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile, 
                     ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address 
                     FROM ${sale_table} as s 
                     LEFT JOIN ${customer_table} as c 
                     ON s.customer_id = c.id WHERE s.locked = 1 
                 ) as sale WHERE sale.active = 1 ${condition}";
                $payto_result = $wpdb->get_row( $ToPay_query );
                $c_result   = $wpdb->get_row( $cash_query );

    //for credit

                $credit_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'credit'";
                $cr_result   = $wpdb->get_row( $credit_query );
                
    //for card

                $card_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'card'";
                $card_result   = $wpdb->get_row( $card_query );
    //for cheque

                $cheque_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'cheque'";
                $cheque_result   = $wpdb->get_row( $cheque_query );

    //for internet

                $interbanking_query       = "SELECT amt,pay_type FROM (${payment_query})  AS combined_table WHERE combined_table.pay_type = 'internet_banking'";
                $interbanking_result   = $wpdb->get_row( $interbanking_query );


 $profile = get_profile1();
 ?>
<!DOCTYPE html>
<html>
       <head>


    <meta charset="utf-8">
        <style>

            body {font-family: arial, Arial, Helvetica, sans-serif; font-size: 12px;margin-left: 20px;margin-right: 30px;border:1px solid #73879c;}
            body {
                    height: 297mm;/*297*/
                    width: 210mm;
                    padding: 20px;
                }
                @media print {
          dt { float: left; clear: left; text-align: right; font-weight: bold; margin-right: 10px; } 
            dd {  padding: 0 0 0.5em 0; }
             .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
                 padding: 3px; 
                } 
                .footer_left,.footer_right{
                  width:50%;
                  float: left;
                  border:1px solid #73879c;
                  height: 100px;
                }
                .title{
                   border:1px solid #73879c;
                }
                .footer_last{
                  margin-top: 60px;
                }
                .body_style{
                    margin-left: 10px;
                }
                .print_padding{
                  padding: 10px;

                }
            }
        </style>   
    </head>


    <body>

    <div class="col-xs-12 invoice-header">
        <h2 style="">
           Sale Report
            <small class="pull-right">Date: <?php echo date("d/m/Y"); ?></small>
        </h2>
    </div>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
        <tr>
            <td valign='top' WIDTH='50%'><strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
                <br/><?php echo $profile ? $profile->address : '';  ?>
                <br/><?php echo $profile ? $profile->address2 : '';  ?>
                <br/>Cell : <?php echo $profile ? $profile->phone_number : '';  ?>
                <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?>
            </td>
        </tr>
    </table>

    <br />

    <!-- <table cellspacing='3' cellpadding='3' WIDTH='100%'>
    <tr>
    <td valign='top' WIDTH='50%'><b>Order number</b>: <?php echo $bill_fdata->order_id; ?></td>
    <td valign='top' WIDTH='50%'><b>Order date:</b> <?php echo $bill_fdata->created_at; ?></td>
    <td valign='top' WIDTH='33%'></td>
    </tr>
    </table> -->
	<table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style=" border-collapse: collapse;border: 1px solid black; width:400px;margin: 0 auto;margin-bottom:20px;">
          <thead>
              <tr class="">
                  <th style="border: 1px solid black;">Cash</th>
                  <th style="border: 1px solid black;">Card</th>
                  <th style="border: 1px solid black;">Cheque</th>
                  <th style="border: 1px solid black;">Internet Banking</th>
                  <th style="border: 1px solid black;">Credit</th>
                  <th style="border: 1px solid black;">COD</th>
                  <th style="border: 1px solid black;">Total Sale</th>
                  <th style="border: 1px solid black;">Total Due</th>
              </tr>
          </thead>
          <tbody>
                <tr><?php  $cash_amount = $c_result->amt- $payto_result->pay_to; ?>
                    <td style="border: 1px solid black;" ><?php echo $cash_amount; ?></td>
                    <td style="border: 1px solid black;" ><?php echo $card_result->amt; ?></td>
                    <td style="border: 1px solid black;" ><?php echo $cheque_result->amt; ?></td>
                    <td style="border: 1px solid black;" ><?php echo $interbanking_result->amt; ?></td>
                    <td style="border: 1px solid black;" ><?php echo $cr_result->amt; ?></td>
                    <td style="border: 1px solid black;" ><?php echo $s_result->cod; ?></td>
                    <td style="border: 1px solid black;" ><?php echo $s_result->total_amount; ?></td>
                    <td style="border: 1px solid black;" ><?php 
                $total_cash_paid = $cash_amount + $card_result->amt + $cheque_result->amt + $interbanking_result->amt; 
                 echo  $s_result->total_amount - $total_cash_paid;
                         ?></td>
                </tr>
          </tbody>
      </table>
    <br/>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style=" border-collapse: collapse;border: 1px solid black;">
     <tr>
        <th style="border: 1px solid black;">SNO</th>
        <th style="border: 1px solid black;">INV.No</th>
        <th style="border: 1px solid black;">Customer Name</th>
        <th style="border: 1px solid black;">Customer Mobile</th>
        <th style="border: 1px solid black;">Purchase Total</th>
        <th style="border: 1px solid black;">Payment Type</th>
        <th style="border: 1px solid black;">Purchase Date</th>
        <th style="border: 1px solid black;">Payment Due</th>
    </tr>


    <?php 
    $i = 1;
    foreach ($results as $b_value) {
    ?>
            <tr>
                <td style="border: 1px solid black;" ><?php echo $i; ?></td>
                <td style="border: 1px solid black;" ><?php echo 'Inv '.$b_value->inv_id; ?></td>
                <td style="border: 1px solid black;" ><?php echo $b_value->name; ?> </td>
                <td style="border: 1px solid black;" ><?php echo $b_value->mobile; ?></td>
                <td style="border: 1px solid black;" ><?php echo $b_value->sub_total; ?></td>
                <td style="border: 1px solid black;" ><?php
                    $payment_type = ws_paymenttypeGroupByType($b_value->inv_id,$b_value->financial_year);
                    $total_paid = 0;
                    $amount = 0;
                    foreach ($payment_type['WithOutCredit'] as $p_value) {
                        if($p_value->payment_type == 'cash'){
                            echo 'Cash : ';
                            echo  $p_value->amount.'</br>';
                        } 
                        if($p_value->payment_type == 'card'){
                            echo 'Card : ';
                          echo $p_value->amount.'</br>';
                        }
                        if($p_value->payment_type == 'cheque'){

                            echo 'Cheque : ';
                            echo  $p_value->amount.'</br>';
                        } 
                        if($p_value->payment_type == 'internet_banking'){
                            echo 'Netbanking : ';
                            echo $p_value->amount.'</br>';
                        }
                        $total_paid = $p_value->amount + $total_paid; 
                                
                    } 
                    foreach ($payment_type['WithCredit'] as $p_value) {
                        if($p_value->payment_type == 'credit'){
                            echo  'Credit : ';
                            echo $b_value->sub_total - $total_paid.'</br>';
                        } 
                    }
                    if($b_value->cod_check == '1'){
                        echo '<b>COD : ';
                        echo  $b_value->sub_total - $total_paid;
                    }

                    ?>
                    
                </td>
                <td style="border: 1px solid black;" ><?php echo $b_value->modified_at; ?></td>                          
                <td style="border: 1px solid black;" ><?php echo ( $b_value->sub_total - $total_paid); ?></td>                          
            </tr>
    <?php
            $i++;
            }
        ?>  
    </table>


    </body>
</html> 