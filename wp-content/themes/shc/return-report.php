<?php
/**
 * Template Name: Return Report Print
 *
 * @package WordPress
 * @subpackage SHC
 */


 if(isset($_GET['bill_form']) && $_GET['bill_form'] != '') { 


            global $wpdb;
            $return_table = $wpdb->prefix.'shc_return_items_details';
            $ws_return_table = $wpdb->prefix.'shc_ws_return_items_details';
            $lot_table = $wpdb->prefix.'shc_lots';

            $condition = '';  


            $bill_from = $_GET['bill_form'];
            $bill_to   = $_GET['bill_to'];
            if($_GET['slap'] != '') {
                $condition .= " WHERE cgst = ".$_GET['slap'];
            }


            $query = "SELECT * from 
(select 
 full_return_tab.lot_id,
 sum(full_return_tab.return_cgst) as cgst_value,
 sum(full_return_tab.return_amt) as amt,sum(full_return_tab.return_unit) as return_unit,
 sum(full_return_tab.return_total) as subtotal from 
 (SELECT return_details.cgst,return_details.lot_id, 
  sum(return_details.cgst_value) as return_cgst, 
  sum(return_details.sgst_value) as return_sgst, 
  sum(return_details.sub_total) as return_total , 
  sum(return_details.return_unit) as return_unit, 
  sum(return_details.amt) as return_amt 
  FROM  ${return_table} as return_details 
  WHERE return_details.active = 1 AND DATE(return_details.modified_at) >= date('$bill_from') AND DATE(return_details.modified_at) <= date('$bill_to') group by return_details.lot_id
union all
SELECT 
  ws_return_details.cgst,
  ws_return_details.lot_id, 
  sum(ws_return_details.cgst_value) as return_cgst, 
  sum(ws_return_details.sgst_value) as return_sgst, 
  sum(ws_return_details.sub_total) as return_total , 
  sum(ws_return_details.return_unit) as return_unit, 
  sum(ws_return_details.amt) as return_amt 
  FROM  ${ws_return_table} as ws_return_details 
  WHERE ws_return_details.active = 1 AND DATE(ws_return_details.modified_at) >= date('$bill_from') AND DATE(ws_return_details.modified_at) <= date('$bill_to') group by ws_return_details.lot_id
 ) as full_return_tab group by full_return_tab.lot_id) as r_table 
left join 
(select id,cgst,sgst,product_name,brand_name from ${lot_table} WHERE active=1) as lot_tab on lot_tab.id =r_table.lot_id  ${condition}";


           

 $results = $wpdb->get_results( $query );

 } ?>
<!DOCTYPE html>
<html>
       <head>
      <link rel='stylesheet' id='bootstrap-min-css'  href="'<?php echo get_template_directory_uri(); ?>'/admin/inc/css/bootstrap.min.css'" type='text/css' media='all' />

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
           Return Report
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

    <!-- <table cellspacing='3' cellpadding='3' WIDTH='100%'>
    <tr>
    <td valign='top' WIDTH='50%'><b>Order number</b>: <?php echo $bill_fdata->order_id; ?></td>
    <td valign='top' WIDTH='50%'><b>Order date:</b> <?php echo $bill_fdata->created_at; ?></td>
    <td valign='top' WIDTH='33%'></td>
    </tr>
    </table> -->

    <br/>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style=" border-collapse: collapse;border: 1px solid black;">
    <tr>
        <th style="border: 1px solid black;" >SNO</th>
        <th style="border: 1px solid black;" >Product Name</th>
        <th style="border: 1px solid black;" >Brand Name</td>
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
                <td style="border: 1px solid black;"><?php echo round($b_value->return_unit); ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->amt; ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->cgst; ?> </td>
                <td style="border: 1px solid black;"><?php echo $b_value->cgst; ?> </td>
                <td style="border: 1px solid black;"><?php echo $b_value->cgst_value; ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->cgst_value; ?></td>
                <td style="border: 1px solid black;"><?php echo $b_value->subtotal; ?></td>                            
            </tr>
    <?php
            $i++;
            }
        ?>  
    </table>


    </body>
</html> 