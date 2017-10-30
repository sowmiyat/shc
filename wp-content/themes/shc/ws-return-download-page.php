<?php
/**
 * Template Name: Ws Goods Return Download page
 *
 * @package WordPress
 * @subpackage SHC
 */

    $bill_data = false;
    $invoice_id = '';
     if(isset($_GET['id']) && $_GET['id'] != '' ) {                                               

        if(isValidInvoiceReturnws($_GET['id'])) {


            $update = true;
            $invoice_id = $_GET['id'];
            $bill_data = getBillDataReturnws($invoice_id);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];


        }
    }
?>
<!DOCTYPE html> 
<html>
<head>
  <link rel='stylesheet' id='bootstrap-min-css'  href="<?php echo get_template_directory_uri(); ?>'/admin/inc/css/bootstrap.min.css'" type='text/css' media='all' />

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
  <div class="print_padding">
  

        <?php
            if($bill_data) {
        ?>
  <div class="title"><div style="margin-left: 40%;margin-bottom: 10px;margin-top: 10px;"><b>GOODS RETURN CHALLAN</b></div></div>

  <div class="body_style">
      <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
          <td valign='top' WIDTH='50%'><strong>Saravana Health Store</strong>
          <br/>7/12,Mg Road,Thiruvanmiyur,
          <br/>Chennai,Tamilnadu,
          <br/>Pincode-600041.
          <br/>Cell:9841141648.
          <br/>GST No - 33BMDPA4840E1ZP
          <td valign='top' WIDTH='50%'>
              <table>
                <tr><td>Return Number</td><td>: <?php echo 'GR'.$bill_fdata->return_id; ?></td></tr>
                <tr><td>Date</td><td>: <?php echo date("d/m/Y"); ?></td></tr>
                <tr><td>State</td><td>: TAMILNADU</td></tr>
                <tr><td>State Code</td><td>: 33</td></tr>
              </table>
          </td>
      </tr>
      </table>
      <br/>
      <table  WIDTH='100%'>
        <tr>
          <td valign='top' width="50%">
            <table>
              <tr><td><b>Return to , </b></td><td><b></b></td></tr>
              <tr><td style="width: 100px;">Name</td><td>: <?php echo $bill_fdata->customer_name; ?></td></tr>
              <tr><td>Company</td><td>: <?php echo $bill_fdata->company_name; ?></td></tr>
              <tr><td>Mobile</td><td>: <?php echo $bill_fdata->mobile; ?></td></tr>
              <tr><td>Address</td><td>: <?php echo $bill_fdata->address; ?></td></tr>
              <tr><td>GST Number</td><td>: <?php echo $bill_fdata->gst_number; ?></td></tr>
            </table>
          </td>
          
        </tr>
        

      </table>

      <br />

      <br/>


      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" >
        <tr>
          <th valign='top'>SNO</th>
          <th valign='top'>PRODUCTS</th>
          <th valign='top'>HSN</th>
          <th valign='top'>Return Quantity</th>
          <th valign='top'>MRP(Per Item)</th> 
          <th valign='top'>AMOUNT</th>
          <th valign='top'>CGST (%) </th>
          <th valign='top'>CGST VALUE</th>
          <th valign='top'>SGST (%) </th>
          <th valign='top'>SGST VALUE</th>
          <th valign='top'>SUB TOTAL</th>
        </tr>
      <?php
          if($bill_data && $bill_ldata && count($bill_ldata)>0) {
              $i = 1;
              foreach ($bill_ldata as $d_value) {
      ?>
                            
        <tr>
          <td valign='top' align='center'><?php echo $i; ?></td>
          <td valign='top'><?php echo $d_value->product_name; ?></td>
          <td valign='top'><?php echo $d_value->hsn; ?></td>
          <td valign='top' align='left'><?php echo $d_value->sale_unit; ?></td>
          <td valign='top' align='left'><?php echo $d_value->mrp; ?></td>
          <td valign='top' align='left'><?php echo $d_value->amt; ?></td>
          <td valign='top'><?php echo $d_value->cgst + 0; echo ' %'; ?></td>
          <td valign='top'><?php echo $d_value->cgst_value; ?></td>
          <td valign='top'><?php echo $d_value->sgst + 0; echo ' %';  ?></td>
          <td valign='top'><?php echo $d_value->sgst_value; ?></td>
          <td valign='top' style="padding:3px;" align='left'><?php echo $d_value->sub_total; ?></td>
        </tr>
      <?php
            $i++;
              }
            } 
          ?>  
      </table>
      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
      <tr>
        <td valign='top' align='right'>Total:</td>
          <td valign='top' align='left' style="width:62px;"><span class="amount"><?php echo $bill_fdata->total_amount; ?></span></td>
      </tr>
      
      </table>

      Amount Chargable ( In Words)<br/>
      <?php echo ucwords(convert_number_to_words_full($bill_fdata->total_amount)); ?>
 
       <br/>
        <?php
                  }
                ?>
        
    </div>

    <div>
       <b style="float:right;">Authorised Signatory</b>
    </div>

</div>
</body>
</html> 