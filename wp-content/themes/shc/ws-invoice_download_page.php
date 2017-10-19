<?php
/**
 * Template Name: SRC Wholesale Invoice Download
 *
 * @package WordPress
 * @subpackage SHC
 */
    $number = 0;
    $bill_data = false;
    $invoice_id = '';
    if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoicews($_GET['id'], 1) ) {
          $update = true;
            $year = $_GET['year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getBillDataws($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $invoice_id['inv_id'] = $bill_fdata->inv_id;
            $home_delivery = getHomedelivery($bill_fdata->home_delivery_id);

    }




    $number =$bill_fdata->sub_total;
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
  <link rel='stylesheet' id='bootstrap-min-css'  href='http://ajnainfotech.com/demo/shc/wp-content/themes/shc/admin/inc/css/bootstrap.min.css' type='text/css' media='all' />

<meta charset="utf-8">
<style>
body {font-family: arial, Arial, Helvetica, sans-serif; font-size: 12px;margin-left: 20px;margin-right: 30px;border:1px solid #73879c;}
body {
     
        
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
  <div class="title"><div style="margin-left: 40%;margin-bottom: 10px;margin-top: 10px;"><b>INVOICE BILL</b></div></div>

  <div class="body_style">
      <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
          <td valign='top' WIDTH='50%'><strong>Saravana Health Store</strong>
            <br/>7/12,Mg Road,Thiruvanmiyur,
            <br/>Chennai,Tamilnadu,
            <br/>Pincode-600041.
            <br/>Cell:9841141648.
            <br/>GST No - 33BMDPA4840E1ZP
          </td>
          <td valign='top' WIDTH='50%'>
              <table>
                <tr>
                  <td>Invoice Number</td>
                  <td>:<?php echo $bill_fdata->id; ?></td>
                </tr>
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
              <tr><td><b>Buyers , </b></td><td><b></b></td></tr>
              <tr><td style="width: 100px;">Name</td><td>: <?php echo $bill_fdata->customer_name; ?></td></tr>
              <tr><td>Company</td><td>: <?php echo $bill_fdata->company_name; ?></td></tr>
              <tr><td>Mobile</td><td>: <?php echo $bill_fdata->mobile; ?></td></tr>
              <tr><td>Address</td><td>: <?php echo $bill_fdata->address; ?></td></tr>
              <tr><td>GST Number</td><td>: <?php echo $bill_fdata->gst_number; ?></td></tr>
            </table>
          </td>
          <td valign='top' width="50%">
            <table>
              <tr><td><b>Delivery Address , </b></td><td><b></b></td></tr>
              <?php if(isset($home_delivery)){  ?>
              <tr><td>Name</td><td>: <?php echo $home_delivery->delivery_name.','; ?></td></tr>
              <tr><td>Mobile</td><td>: <?php echo $home_delivery->delivery_phonenumber.','; ?></td></tr>
              <tr><td>Address</td><td>: <?php echo $home_delivery->delivery_address; ?></td></tr>
             <?php } else { ?>
              <tr><td >Name</td><td>: <?php echo $bill_fdata->customer_name; ?></td></tr>
              <tr><td>Company</td><td>: <?php echo $bill_fdata->company_name; ?></td></tr>
              <tr><td>Mobile</td><td>: <?php echo $bill_fdata->mobile; ?></td></tr>
              <tr><td>Address</td><td>: <?php echo $bill_fdata->address; ?></td></tr>
              <?php } ?>
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


      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" >
        <tr>
          <th></th>
          <th valign='top'>SNO</th>
          <th valign='top'>PRODUCTS</th>
          <th valign='top'>HSN</th>
          <th valign='top'>QTY</th>
          <th valign='top'>MRP</th>
          <th valign='top'>DISCOUNTED VALUE</th>
          <th valign='top'>AMOUNT</th>
          <th valign='top'>CGST </th>
          <th valign='top'>CGST VALUE</th>
          <th valign='top'>SGST</th>
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
          <td valign='top' align='left'><?php echo $d_value->unit_price; ?></td>
          <td valign='top' align='left'><?php echo $d_value->discount; ?></td>
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
        <td valign='top' align='right'>Discount:</td>
          <td valign='top' align='left'>
            <span class="amount">
              <?php 
                  echo $bill_fdata->discount + 0;
                  echo '%';
                ?>
            </span>
          </td>
      </tr>
      <tr>
        <td valign='top' align='right'>Subtotal:</td>
          <td valign='top' align='left' style="width:62px;"><span class="amount"><?php echo $bill_fdata->sub_total; ?></span></td>
      </tr>
      <tr>
        <td valign='top' align='right'>Paid Amount:</td>
          <td valign='top' align='left'><span class="amount"><?php echo $bill_fdata->paid_amount; ?></span></td>
      </tr>
      <tr>
        <td valign='top' align='right'>Balance:</td>
          <td valign='top' align='left'><span class="amount"><?php echo $bill_fdata->return_amt; ?></span></td>
      </tr>
      </table>
      Amount Chargable ( In Words)<br/>
      <?php echo ucwords($result) . "Rupees & ". ucwords($points). " Paises  Only "; ?>

        <?php
            if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                    $tax_amount_zero            = '0.00';
                    $tax_amount_five            = '0.00';
                    $tax_amount_twelve          = '0.00';
                    $tax_amount_eighteen        = '0.00';
                    $tax_amount_twentyeight     = '0.00';
                    $cgst_amount_zero           = '0.00';
                    $cgst_amount_five           = '0.00';
                    $cgst_amount_twelve         = '0.00';
                    $cgst_amount_eighteen       = '0.00';
                    $cgst_amount_twentyeight    = '0.00';
                foreach ($bill_ldata as $d_value) {   

                    if($d_value->cgst == '0.00'){
                        $tax_amount_zero    = $tax_amount_zero + $d_value->sub_total;
                        $cgst_amount_zero   = $cgst_amount_zero + $d_value->cgst_value;
                    }

                    else if($d_value->cgst == '2.50'){
                        $tax_amount_five     = $tax_amount_five  + $d_value->sub_total;
                        $cgst_amount_five    = $cgst_amount_five  + $d_value->cgst_value;
                       
                    }
                    else if($d_value->cgst == '6.00'){
                        $tax_amount_twelve    = $tax_amount_twelve + $d_value->sub_total;
                        $cgst_amount_twelve   = $cgst_amount_twelve + $d_value->cgst_value;
                    }
                    else if($d_value->cgst == '9.00'){
                        $tax_amount_eighteen    = $tax_amount_eighteen + $d_value->sub_total;
                        $cgst_amount_eighteen   = $cgst_amount_eighteen + $d_value->cgst_value;
                    }
                    else if($d_value->cgst == '14.00'){
                        $tax_amount_twentyeight    = $tax_amount_twentyeight + $d_value->sub_total;
                        $cgst_amount_twentyeight   = $cgst_amount_twentyeight + $d_value->cgst_value;
                    }
                    else {

                        return false;
                    }

                }

                $tax_amount = $tax_amount_zero + $tax_amount_five + $tax_amount_twentyeight + $tax_amount_eighteen + $tax_amount_twelve;
                $cgst_amount = $cgst_amount_zero + $cgst_amount_five + $cgst_amount_twelve + $cgst_amount_eighteen + $cgst_amount_twentyeight;
            }
        $number =(2 * $cgst_amount);
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
    $result_cgst = implode('', $str);
    $points_cgst = ($point) ?
    "" . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';

        ?>
      <table cellspacing='3' cellpadding='3' class="table table-bordered" style="width:50%">
        <tr>
          <th></th>
          <th valign='top' rowspan="2" >TAXABLE VALUE</th>
          <th valign='top' colspan="2">CENTRAL SALES TAX  </th>  
          <th valign='top' colspan="2">STATE SALES TAX  </th>   
        </tr>
        <tr>
           
          <th valign='top'>CGST</th>
          <th valign='top'>CGST VALUE</th>
          <th valign='top'>SGST</th>
          <th valign='top'>SGST VALUE</th>
        </tr>                   
        <?php  if($tax_amount_zero != '0.00'){ ?>
            <tr class="zero">
                <td class="amt_zero">Rs. <?php  echo $tax_amount_zero; ?></td>
                <td class="cgst_zero">0.00 % </td>
                <td class="cgst_val_zero"><?php echo $cgst_amount_zero; ?></td>
                <td class="sgst_zero">0.00 %</td>
                <td class="sgst_val_zero"><?php echo $cgst_amount_zero; ?></td>
            </tr>
            <?php } if($tax_amount_five != '0.00'){ ?>
            <tr class="five">
                <td class="amt_five">Rs.<?php  echo $tax_amount_five; ?></td>
                <td class="cgst_five">2.50 % </td>
                <td class="cgst_val_five"><?php echo $cgst_amount_five; ?></td>
                <td class="sgst_five">2.50 %</td>
                <td class="sgst_val_five"><?php echo $cgst_amount_five; ?></td>
            </tr>
            <?php } if($tax_amount_twelve != '0.00'){ ?>
            <tr class="twelve">
                <td class="amt_twelve">Rs.<?php  echo $tax_amount_twelve; ?></td>
                <td class="cgst_twelve">6.00 % </td>
                <td class="cgst_val_twelve"><?php echo $cgst_amount_twelve; ?></td>
                <td class="sgst_twelve">6.00 %</td>
                <td class="sgst_val_twelve"><?php echo $cgst_amount_twelve; ?></td>
            </tr>
            <?php } if($tax_amount_eighteen != '0.00'){ ?>
            <tr class="eighteen">
                <td class="amt_eighteen">Rs.<?php  echo $tax_amount_eighteen; ?></td>
                <td class="cgst_eighteen">9.00 % </td>
                <td class="cgst_val_eighteen"><?php echo $cgst_amount_eighteen; ?></td>
                <td class="sgst_eighteen">9.00 %</td>
                <td class="sgst_val_eighteen"><?php echo $cgst_amount_eighteen; ?></td>
            </tr>
            <?php } if($tax_amount_twentyeight != '0.00'){ ?>
            <tr class="twentyeight">
                <td class="amt_twentyeight">Rs.<?php  echo $tax_amount_twentyeight; ?></td>
                <td class="cgst_twentyeight">14.00 % </td>
                <td class="cgst_val_twentyeight"><?php echo $cgst_amount_twentyeight; ?></td>
                <td class="sgst_twentyeight">14.00 %</td>
                <td class="sgst_val_twentyeight"><?php echo $cgst_amount_twentyeight; ?></td>
            </tr> 
            <?php } ?>
            <tr><td></td><td></td><td></td><td>Total Tax</td><td><?php echo $cgst_amount + $cgst_amount; ?></td></tr> 
                
      </table>

        Tax Amount (in words)<br/>
        <?php echo ucwords($result_cgst) . "Rupees & ". ucwords($points_cgst). " Paises  Only "; ?><br/><br/>


                <?php
                  }
                ?>


        <div>
            Declaration,
            <div>
              We declare that  this  invoice  shows  the  actual price of the goods described and that all particulars are <BR/>
              true and correct

            </div>
        </div>
    </div>

    

</div>
</body>
</html> 