<?php
/**
 * Template Name: Report Print
 *
 * @package WordPress
 * @subpackage SHC
 */


 if(isset($_GET['bill_form']) && $_GET['bill_form'] != '') { 


            global $wpdb;
            $sale = $wpdb->prefix.'shc_sale';
            $sale_details =  $wpdb->prefix.'shc_sale_detail';
            $return_table = $wpdb->prefix.'shc_return_items_details';
            $ws_sale = $wpdb->prefix.'shc_ws_sale';
            $ws_sale_details = $wpdb->prefix.'shc_ws_sale_detail';
            $ws_return_table = $wpdb->prefix.'shc_ws_return_items_details';
            $lot_table = $wpdb->prefix.'shc_lots';

            $condition = '';  


            $bill_from = $_GET['bill_form'];
            $bill_to   = $_GET['bill_to'];
            if($_GET['slap'] != '') {
                $condition .= " WHERE report.gst = ".$_GET['slap'];
            }


            $query = "SELECT report.*,lot.brand_name,lot.product_name,lot.hsn from (
    SELECT (sum(final_ws_sale.bal_cgst)) as cgst_value, 
    (sum(final_ws_sale.bal_total)) as total, 
    (sum(final_ws_sale.bal_unit)) as total_unit, 
    (sum(final_ws_sale.bal_amt)) as amt, 
    final_ws_sale.gst as gst, 
    final_ws_sale.lot_id from 
    (
        SELECT 
        (case when return_table.return_cgst is null then sale_table.sale_cgst else sale_table.sale_cgst - return_table.return_cgst end ) as bal_cgst, 
        (case when return_table.return_total is null then sale_table.sale_total else sale_table.sale_total - return_table.return_total end ) as bal_total, 
        (case when return_table.return_unit is null then sale_table.sale_unit else sale_table.sale_unit - return_table.return_unit end) as bal_unit, 
        (case when return_table.return_amt is null then sale_table.sale_amt else sale_table.sale_amt - return_table.return_amt end ) as bal_amt, sale_table.cgst as gst, 
        sale_table.lot_id FROM ( 
            SELECT sale_details.cgst,sale_details.lot_id, 
            sum(sale_details.cgst_value) as sale_cgst, 
            sum(sale_details.sgst_value) sale_sgst, 
            sum(sale_details.sub_total) as sale_total, 
            sum(sale_details.sale_unit) as sale_unit, 
            sum(sale_details.amt) as sale_amt 
            FROM ${sale} as sale 
            left join 
            ${sale_details} as sale_details 
            on sale.`id`= sale_details.sale_id 
            WHERE sale.active = 1 and sale_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by sale_details.lot_id ) 
        as sale_table left join ( 
           SELECT ret_tab.* FROM  (
       SELECT return_details.cgst,
       return_details.lot_id, 
       sum(return_details.cgst_value) as return_cgst, 
       sum(return_details.sgst_value) as return_sgst, 
       sum(return_details.sub_total) as return_total , 
       sum(return_details.return_unit) as return_unit, 
       sum(return_details.amt) as return_amt,
       return_details.sale_id from ${return_table} as return_details  
       WHERE return_details.active = 1 AND DATE(return_details.modified_at) >= date('$bill_from') AND DATE(return_details.modified_at) <= date('$bill_to')  
       group by return_details.lot_id) as ret_tab 
    left join ${sale} as sale 
    on sale.id = ret_tab.sale_id ) as return_table on sale_table.lot_id = return_table.lot_id union ALL
SELECT 
(case when ws_return_table.return_cgst is null then ws_sale_table.sale_cgst else ws_sale_table.sale_cgst - ws_return_table.return_cgst end ) as bal_cgst, 
(case when ws_return_table.return_total is null then ws_sale_table.sale_total else ws_sale_table.sale_total - ws_return_table.return_total end ) as bal_total, 
(case when ws_return_table.return_unit is null then ws_sale_table.sale_unit else ws_sale_table.sale_unit - ws_return_table.return_unit end) as bal_unit, 
(case when ws_return_table.return_amt is null then ws_sale_table.sale_amt else ws_sale_table.sale_amt - ws_return_table.return_amt end ) as bal_amt, 
ws_sale_table.cgst as gst, ws_sale_table.lot_id 
FROM ( 
    SELECT ws_sale_details.cgst,ws_sale_details.lot_id, 
    sum(ws_sale_details.cgst_value) as sale_cgst, 
    sum(ws_sale_details.sgst_value) sale_sgst, 
    sum(ws_sale_details.sub_total) as sale_total, 
    sum(ws_sale_details.sale_unit) as sale_unit, 
    sum(ws_sale_details.amt) as sale_amt FROM 
    ${ws_sale} as sale 
    left join 
    ${ws_sale_details} as ws_sale_details
    on  sale.`id`= ws_sale_details.sale_id 
    WHERE sale.active = 1 and ws_sale_details.active = 1 AND DATE(sale.modified_at) >= date('$bill_from') AND DATE(sale.modified_at) <= date('$bill_to') group by ws_sale_details.lot_id
) as ws_sale_table left join ( 
   SELECT ws_ret_tab.* FROM  (
       SELECT ws_return_details.cgst,
       ws_return_details.lot_id, 
       sum(ws_return_details.cgst_value) as return_cgst, 
       sum(ws_return_details.sgst_value) as return_sgst, 
       sum(ws_return_details.sub_total) as return_total , 
       sum(ws_return_details.return_unit) as return_unit, 
       sum(ws_return_details.amt) as return_amt,
       ws_return_details.sale_id from ${ws_return_table} as ws_return_details  
       WHERE ws_return_details.active = 1 AND DATE(ws_return_details.modified_at) >= date('$bill_from') AND DATE(ws_return_details.modified_at) <= date('$bill_to')  
       group by ws_return_details.lot_id) as ws_ret_tab 
    left join ${ws_sale} as sale 
    on sale.id = ws_ret_tab.sale_id
) as ws_return_table
on ws_sale_table.lot_id = ws_return_table.lot_id ) 
as final_ws_sale group by final_ws_sale.lot_id )
as report 
left join ${lot_table} as 
lot on report.lot_id=lot.id ${condition}";


           

                 $results = $wpdb->get_results( $query );

 } ?>
<html>
   <head>

    <meta charset="utf-8">
        <style>

            body {font-family: arial, Arial, Helvetica, sans-serif; font-size: 12px;margin-left: 20px;margin-right: 30px;border:1px solid #73879c;}
            body {
                    height: 297mm;/*297*/
                    width: 210mm;
                    
                }
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
        </style>   
    </head>


    <body>

    <div class="col-xs-12 invoice-header">
        <h2 style="">
           Stock Report
            <small class="pull-right">Date: <?php echo date("d/m/Y"); ?></small>
        </h2>
    </div>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
        <tr>
            <td valign='top' WIDTH='50%'><strong>Saravana Health Store</strong>
                <br/>7/12,Mg Road,Thiruvanmiyur,
                <br/>Chennai,Tamilnadu,
                <br/>Pincode-600041.
                <br/>Cell:9841141648
            </td>
        </tr>
    </table>

    <br />

    <br/>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style=" border-collapse: collapse;border: 1px solid black;">
    <tr>
        <th style="border: 1px solid black;" >SNO</th>
        <th style="border: 1px solid black;" >Brand Name</th>
        <th style="border: 1px solid black;" >Product Name</th>
        <th style="border: 1px solid black;" >Number of Goods Sold</th>
        <th style="border: 1px solid black;" >CGST</th>
        <th style="border: 1px solid black;" >SGST</th>
        <th style="border: 1px solid black;" >CGST Amount</th>
        <th style="border: 1px solid black;" >SGST Amonut</th>
        <th style="border: 1px solid black;" >Amount</th>
        <th style="border: 1px solid black;" >Cost Of Goods Sold(COGS)</th>
    </tr>

    <?php 
    $i = 1;
    foreach ($results as $b_value) {
    ?>
            <tr>
                <td style="border: 1px solid black;"><?php echo $i; ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->product_name; ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->brand_name; ?></td>
                <td style="border: 1px solid black;"><?php echo round($b_value->total_unit); ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->gst; ?> </td>
                <td style="border: 1px solid black;"><?php echo $b_value->gst; ?> </td>
                <td style="border: 1px solid black;"><?php echo $b_value->cgst_value; ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->cgst_value; ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->amt; ?></td> 
                <td style="border: 1px solid black;"><?php echo $b_value->total; ?></td>                               
            </tr>
    <?php
            $i++;
            }
        ?>  
    </table>


    </body>
</html> 