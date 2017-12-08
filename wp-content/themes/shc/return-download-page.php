<?php
/**
 * Template Name: Goods Return Download Page
 * @package WordPress
 * @subpackage SHC
 */

    $bill_data = false;
    $invoice_id = '';
     if(isset($_GET['id']) && $_GET['id'] != '' ) {                                               

        if(isValidInvoiceReturn($_GET['id'])) {


            $update = true;
            $invoice_id = $_GET['id'];
            $bill_data = getBillDataReturn($invoice_id);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];


        }
    }

  $number =$bill_fdata->total_amount;
    $no = round($number);
    $point = round($number - $no, 2) * 100;
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
      } else $str[] = null;
    } 
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
    "" . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
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
<!-- <div class="col-xs-12 invoice-header">
    <h4 style="margin-left: -15px;">
        Invoice. #<?php echo $bill_fdata->inv_id; ?>
        <small class="pull-right">Date: <?php echo date("d/m/Y"); ?></small>
    </h4>
</div> -->
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
                <tr><td>Return Number</td><td>: <?php echo $bill_fdata->return_id; ?></td></tr>
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
              <!-- <tr><td>Company</td><td>: <?php echo $bill_fdata->company_name; ?></td></tr> -->
              <tr><td>Mobile</td><td>: <?php echo $bill_fdata->mobile; ?></td></tr>
              <tr><td>Address</td><td>: <?php echo $bill_fdata->address; ?></td></tr>
              <!-- <tr><td>GST Number</td><td>: <?php echo $bill_fdata->gst_number; ?></td></tr> -->
            </table>
          </td>
          
        </tr>
        

      </table>

      <br />

      <!-- <table cellspacing='3' cellpadding='3' WIDTH='100%'>
      <tr>
      <td valign='top' WIDTH='50%'><b>Order number</b>: <?php //echo $bill_fdata->order_id; ?></td>
      <td valign='top' WIDTH='50%'><b>Order date:</b> <?php //echo $bill_fdata->created_at; ?></td>
      <td valign='top' WIDTH='33%'></td>
      </tr>
      </table> -->

      <br/>


      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" border=1>
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
      <?php echo ucwords($result) . "Rupees & ". ucwords($points). " Paises  Only "; ?>
 
       <br/>
        <?php
                  }
                ?>
        
    </div>

    <div>
       <!-- <b style="float:right;">Authorised Signatory</b> -->
    </div>

</div>
</body>
</html> 