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
(select id,cgst,sgst,product_name,brand_name from ${lot_table} WHERE active=1) as lot_tab on lot_tab.id =r_table.lot_id where lot_tab.id>1 ${condition}";


           

 $results = $wpdb->get_results( $query );

 } ?>
<!DOCTYPE html>
<html>
    <head>
      <link rel='stylesheet' id='bootstrap-min-css'  href='http://ajnainfotech.com/demo/shc/wp-content/themes/shc/admin/inc/css/bootstrap.min.css' type='text/css' media='all' />

    <meta charset="utf-8">
    <style>
        body {  font-family: arial, Arial, Helvetica, sans-serif; font-size: 12px;margin-left: 20px;margin-right: 30px; }
/*        body {
                height: 297mm;
                width: 210mm;
            }*/
        dt {    float: left; clear: left; text-align: right; font-weight: bold; margin-right: 10px; } 
        dd {    padding: 0 0 0.5em 0; }
         .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
             padding: 3px; 
            } 
    </style> 
    <style type="text/css" media="print">
    @page 
        {
            size: auto;   /* auto is the initial value */ 

        /* this affects the margin in the printer settings */ 
            margin: 10mm 10mm 0mm 0mm;      
         
        }
            
          
    </style>   
    </head>

    <body>

    <div class="col-xs-12 invoice-header">
        <h4 style="margin-left: -15px;">
           Return Report
            <small class="pull-right">Date: <?php echo date("d/m/Y"); ?></small>
        </h4>
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

    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
    <tr>
        <th>SNO</th>
		<th>Product Name</th>
		<th>Brand Name</td>
        <th>Number of Goods Sold</th>
        <th>CGST</th>
        <th>SGST</th>
        <th>CGST Amount</th>
        <th>SGST Amonut</th>
        <th>Amount</th>
        <th>Cost Of Goods Sold(COGS)</th>
    </tr>

    <?php 
    $i = 1;
    foreach ($results as $b_value) {
    ?>
            <tr>
                <td class=""><?php echo $i; ?></td>
				<td class=""><?php echo $b_value->product_name; ?></td>
				<td class=""><?php echo $b_value->brand_name; ?></td>
                <td class=""><?php echo round($b_value->return_unit); ?></td>
				<td class=""><?php echo $b_value->amt; ?></td>
                <td class=""><?php echo $b_value->cgst; ?> </td>
                <td class=""><?php echo $b_value->cgst; ?> </td>
                <td class=""><?php echo $b_value->cgst_value; ?></td>
                <td class=""><?php echo $b_value->cgst_value; ?></td>
                <td class=""><?php echo $b_value->subtotal; ?></td>                            
            </tr>
    <?php
            $i++;
            }
        ?>  
    </table>


    </body>
</html> 