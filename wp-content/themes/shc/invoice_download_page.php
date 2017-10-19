<?php
/**
 * Template Name: SRC Download Page
 *
 * @package WordPress
 * @subpackage SHC
 */

    $bill_data = false;
    $invoice_id = '';
    if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoice($_GET['id'], 1) ) {
            $update = true;
            $year = $_GET['year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getBillData($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $invoice_id['inv_id'] = $bill_fdata->inv_id;
            $home_delivery = getHomedelivery($bill_fdata->home_delivery_id);
    }
?>
<!DOCTYPE html>
<html>
<head>
  <link rel='stylesheet' id='bootstrap-min-css'  href='http://ajnainfotech.com/demo/shc/wp-content/themes/shc/admin/inc/css/bootstrap.min.css' type='text/css' media='all' />

<meta charset="utf-8">
<style>
body {font-family: arial, Arial, Helvetica, sans-serif; font-size: 12px;margin-left: 20px;margin-right: 30px;}
/*body {
        height: 250mm;//297
        width: 100mm;
    }*/
dt { float: left; clear: left; text-align: right; font-weight: bold; margin-right: 10px; } 
dd {  padding: 0 0 0.5em 0; }
 .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
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

        <?php
            if($bill_data) {
        ?>
<!-- <div class="col-xs-12 invoice-header">
    <h4 style="margin-left: -15px;">
        Invoice. #<?php echo $bill_fdata->inv_id; ?>
        <small class="pull-right">Date: <?php echo date("d/m/Y"); ?></small>
    </h4>
</div> -->
<table cellspacing='3' cellpadding='3' WIDTH='100%' >
<tr>
<td valign='top' WIDTH='50%'><strong>Saravana Health Store</strong>
<br/>7/12,Mg Road,Thiruvanmiyur,
<br/>Chennai,Tamilnadu,
<br/>Pincode-600041.
<br/>Cell:9841141648
</td>

<td valign='top' WIDTH='50%'>
<table>
  <tr><td>Inv No</td><td>: <?php echo $bill_fdata->id; ?></td></tr>
  <tr><td>Name</td><td>: <?php echo $bill_fdata->customer_name; ?></td></tr>
  <tr><td>Date</td><td>: <?php echo date("d/m/Y"); ?></td></tr>
  <tr><td>Mobile</td><td>: <?php echo $bill_fdata->mobile; ?></td></tr>
  <tr><td>Addr</td><td>: <?php echo $bill_fdata->address; ?></td></tr>
</table>
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


<table  cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-bordered">
<tr>
<th></th>
<th valign='top'>SNO</th>
<th valign='top'>PRD</th>
<th valign='top'>HSN</th>
<th valign='top'>QTY</th>
<th valign='top'>MRP</th>
<th valign='top'>Disounted Price</th>
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
<td valign='top' align='left'><?php echo $d_value->sub_total; ?></td>
</tr>

<?php
      $i++;
        }
      } 
    ?>  
</table>
<table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-bordered">
    <tr><td></td>
        <td></td></tr>
<tr>
  <td valign='top' align='right'><b>NET AMOUNT:</b></td>
    <td valign='top' align='left' style="width:62px;"><span class="amount"><?php echo '<b>'.$bill_fdata->sub_total.'</b>'; ?></span></td>
</tr>
<tr>
  <td valign='top' align='right'>Discount:</td>
    <td valign='top' align='left'><span class="amount"><?php if($bill_fdata->discount_type == 'cash') {
                        echo $bill_fdata->discount; }
                        else {
                          echo $bill_fdata->discount + 0;
                          echo '%';
                        }
                        ?></span></td>
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

          <?php
            }
          ?>
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
                        $tax_amount_zero    = $tax_amount_zero + $d_value->amt;
                        $cgst_amount_zero   = $cgst_amount_zero + $d_value->cgst_value;
                    }

                    else if($d_value->cgst == '2.50'){
                        $tax_amount_five     = $tax_amount_five  + $d_value->amt;
                        $cgst_amount_five    = $cgst_amount_five  + $d_value->cgst_value;
                       
                    }
                    else if($d_value->cgst == '6.00'){
                        $tax_amount_twelve    = $tax_amount_twelve + $d_value->amt;
                        $cgst_amount_twelve   = $cgst_amount_twelve + $d_value->cgst_value;
                    }
                    else if($d_value->cgst == '9.00'){
                        $tax_amount_eighteen    = $tax_amount_eighteen + $d_value->amt;
                        $cgst_amount_eighteen   = $cgst_amount_eighteen + $d_value->cgst_value;
                    }
                    else if($d_value->cgst == '14.00'){
                        $tax_amount_twentyeight    = $tax_amount_twentyeight + $d_value->amt;
                        $cgst_amount_twentyeight   = $cgst_amount_twentyeight + $d_value->cgst_value;
                    }
                    else {

                        return false;
                    }

                }

                $tax_amount = $tax_amount_zero + $tax_amount_five + $tax_amount_twentyeight + $tax_amount_eighteen + $tax_amount_twelve;
                $cgst_amount = $cgst_amount_zero + $cgst_amount_five + $cgst_amount_twelve + $cgst_amount_eighteen + $cgst_amount_twentyeight;
            }
        $number =$cgst_amount;
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
      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-bordered">
        <tr>
            <th></th>
          <th valign='top'>GST</th>
          <th valign='top'>PRD VALUE  </th>  
          <th valign='top'>GST AMT  </th>   
          <th valign='top'>CGST AMT </th>   
          <th valign='top'>SGST AMT </th>   
        </tr> 

         <?php  if($tax_amount_zero != '0.00'){ ?>
            <tr class="zero">
                <td class="amt_zero">0 % </td>
                <td class="cgst_zero"><?php echo $tax_amount_zero; ?> </td>
                <td class="cgst_val_zero"><?php echo $cgst_amount_zero + $cgst_amount_zero; ?></td>
                <td class="sgst_zero"><?php echo $cgst_amount_zero; ?></td>
                <td class="sgst_val_zero"><?php echo $cgst_amount_zero; ?></td>
            </tr>
            <?php } if($tax_amount_five != '0.00'){ ?>
            <tr class="five">
                <td class="amt_five">2.5 % </td>
                <td class="cgst_five"><?php echo $tax_amount_five; ?> </td>
                <td class="cgst_val_five"><?php echo $cgst_amount_five + $cgst_amount_five; ?></td>
                <td class="sgst_five"><?php echo $cgst_amount_five; ?></td>
                <td class="sgst_val_five"><?php echo $cgst_amount_five; ?></td>
            </tr>
            <?php } if($tax_amount_twelve != '0.00'){ ?>
            <tr class="twelve">
                <td class="amt_twelve">6 %</td>
                <td class="cgst_twelve"><?php echo $tax_amount_twelve; ?> </td>
                <td class="cgst_val_twelve"><?php echo $cgst_amount_twelve + $cgst_amount_twelve; ?></td>
                <td class="sgst_twelve"><?php echo $cgst_amount_twelve; ?></td>
                <td class="sgst_val_twelve"><?php echo $cgst_amount_twelve; ?></td>
            </tr>
            <?php } if($tax_amount_eighteen != '0.00'){ ?>
            <tr class="eighteen">
                <td class="amt_eighteen">9 %</td>
                <td class="cgst_eighteen"><?php echo $tax_amount_eighteen; ?> </td>
                <td class="cgst_val_eighteen"><?php echo $cgst_amount_eighteen + $cgst_amount_eighteen; ?></td>
                <td class="sgst_eighteen"><?php echo $cgst_amount_eighteen; ?></td>
                <td class="sgst_val_eighteen"><?php echo $cgst_amount_eighteen; ?></td>
            </tr>
            <?php } if($tax_amount_twentyeight != '0.00'){ ?>
            <tr class="twentyeight">
                <td class="amt_twentyeight">14 %</td>
                <td class="cgst_twentyeight"><?php echo $tax_amount_twentyeight; ?> </td>
                <td class="cgst_val_twentyeight"><?php echo $cgst_amount_twentyeight + $cgst_amount_twentyeight; ?></td>
                <td class="sgst_twentyeight"><?php echo $cgst_amount_twentyeight; ?></td>
                <td class="sgst_val_twentyeight"><?php echo $cgst_amount_twentyeight; ?></td>
            </tr> 
            <?php } ?>
            <tr><td>Total</td><td><?php echo $tax_amount; ?></td><td><?php echo $cgst_amount + $cgst_amount; ?></td><td><?php echo $cgst_amount; ?></td><td><?php echo $cgst_amount; ?></td></tr>               
      </table>


             


</body>
</html> 