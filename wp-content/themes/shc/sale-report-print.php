<?php
/**
 * Template Name: Sale Report Print
 *
 * @package WordPress
 * @subpackage SHC
 */


 


            global $wpdb;
            $sale_table =  $wpdb->prefix.'shc_sale';
            $customer_table =  $wpdb->prefix.'shc_customers';

            $condition = '';  
            
            if($_GET['inv_id'] != '') {
                $condition .= " AND id = ".$_GET['inv_id'];
            }
            if($_GET['order_id'] != '') {
                $condition .= " AND order_id LIKE '".$_GET['order_id']."%' ";
            }
            if($_GET['name'] != '') {
                $condition .= " AND name LIKE '".$_GET['name']."%' ";
            }
            if($_GET['mobile'] != '') {
                $condition .= " AND mobile LIKE '".$_GET['mobile']."%' ";
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


            $query = "SELECT * FROM (SELECT s.*,
            ( CASE WHEN c.name IS NULL THEN 'Nil' ELSE c.name END ) as name,
            ( CASE WHEN c.mobile IS NULL THEN 'Nil' ELSE c.mobile END ) as mobile,
            ( CASE WHEN c.address IS NULL THEN 'Nil' ELSE c.address END ) as address
            FROM ${sale_table} as s LEFT JOIN ${customer_table} as c ON s.customer_id = c.id WHERE s.locked = 1 ) as tot WHERE tot.active = 1 ${condition}";
          $results = $wpdb->get_results( $query );


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
        <th style="border: 1px solid black;">SNO</th>
        <th style="border: 1px solid black;">INV.No</th>
        <th style="border: 1px solid black;">Order ID</th>
        <th style="border: 1px solid black;">Customer Name</th>
        <th style="border: 1px solid black;">Customer Mobile</th>
        <th style="border: 1px solid black;">Purchase Total</th>
        <th style="border: 1px solid black;">Purchase Date</th>
    </tr>

    <?php 
    $i = 1;
    foreach ($results as $b_value) {
    ?>
            <tr>
                <td style="border: 1px solid black;" ><?php echo $i; ?></td>
                <td style="border: 1px solid black;" ><?php echo 'INV '.$b_value->inv_id; ?></td>
                <td style="border: 1px solid black;" ><?php echo $b_value->order_id; ?> </td>
                <td style="border: 1px solid black;" ><?php echo $b_value->name; ?> </td>
                <td style="border: 1px solid black;" ><?php echo $b_value->mobile; ?></td>
                <td style="border: 1px solid black;" ><?php echo $b_value->sub_total; ?></td>
                <td style="border: 1px solid black;" ><?php echo $b_value->created_at; ?></td>                          
            </tr>
    <?php
            $i++;
            }
        ?>  
    </table>


    </body>
</html> 