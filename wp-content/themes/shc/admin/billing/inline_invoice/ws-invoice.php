<?php
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

<style>

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


  <div class="print_padding" id="invoice_print">
  
<link rel='stylesheet' id='bootstrap-min-css'  href='http://ajnainfotech.com/demo/shc/wp-content/themes/shc/admin/inc/css/bootstrap.min.css' type='text/css' media='all' />
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
              <tr><td>Name</td><td>: <?php echo $bill_fdata->home_delivery_name; ?></td></tr>
              <tr><td>Mobile</td><td>: <?php echo $bill_fdata->home_delivery_mobile; ?></td></tr>
              <tr><td>Address</td><td>: <?php echo $bill_fdata->home_delivery_address; ?></td></tr>
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
          <td valign='top' align='left'><span class="amount"><?php if($bill_fdata->discount_type == 'cash') {
                              echo $bill_fdata->discount; }
                              else {
                                echo $bill_fdata->discount + 0;
                                echo '%';
                              }
                              ?></span></td>
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
        $number = (2 * $cgst_amount);
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
            <tr><td></td><td></td><td></td><td>Total Tax</td><td><?php echo $cgst_amount + $cgst_amount;  ?></td></tr> 
                
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

    <div>
        <div class="footer_left" >
            <b style="margin-left:10px;">Customer Seal & Signature</b>
        </div>
        <div class="footer_right">
            <b style="margin-left:10px;">For Saravana Health Store</b>
        <div class="footer_last">
            <b style="margin-left:10px;">Authorised Signatory</b>
        </div>
        </div>
    </div>













































































































































<div id="print_bill">

  <style type="text/css">

    @page {
        size: 'A4';
        margin: 0px;
        padding: 0;
    }
    @media print {
      *{
        display: none;
      }


      .body {
        font-family: "Lucida Sans Unicode", "Lucida Grande", "sans-serif";
      }
      .inner-container {
        padding-left: 100px;
        padding-right: 60px;
        width: 794px;
      }
      .left-float {
        float: left;
      }
      .top-left {
        width: 160px;
      }
      .top-center {
        width: 284px;
      }
      .top-right {
        width: 190px;
      }
      .left-logo img, .right-logo img {
        width: 100%;
      }
      .comp-detail {
        padding-left: 5px;
      }

      .comp-detail-in .detail-left {
        width: 55px;
      }

      .customer-detail-left {
        width: 400px;
      }
      .company-detail-left {
        width: 444px;
      }
      .company-detail-left .company-name h3 {
          font-family: serif;
          font-weight: bold;
          font-size: 24px;
          margin-bottom: 3px;
      }
      .company-detail-left .company-address {
          font-size: 13px;
          font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
      }

      .customer-detail-right {
        width: 234px;
      }

      .text-center {
        text-align: center;
      }
      .text-rigth {
        text-align: right;
      }
        .table td, .table th {
          background-color: transparent !important;
        }
        .bill-detail {
          height: 650px;
        }

      .footer {
        position: fixed;
              bottom: 0px;
              left: 0px;
      }
      .footer .foot {
          background-color: #67a3b7 !important;
          -webkit-print-color-adjust: exact;
      }

      .table>tbody>tr>td {
        padding: 0 3px;
        height: 20px;
      }
      .table-bordered>tbody>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
      }

      .billing-title {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
          text-decoration: underline;
      }
      h3 {
        margin-top: 0px;
      }


    }
      .body {
        font-family: "Lucida Sans Unicode", "Lucida Grande", "sans-serif";
      }
      .inner-container {
        padding-left: 100px;
        padding-right: 60px;
        width: 794px;
      }
      .left-float {
        float: left;
      }
      .top-left {
        width: 160px;
      }
      .top-center {
        width: 284px;
      }
      .top-right {
        width: 190px;
      }
      .left-logo img, .right-logo img {
        width: 100%;
      }
      .comp-detail {
        padding-left: 5px;
      }

      .comp-detail-in .detail-left {
        width: 55px;
      }

      .customer-detail-left {
        width: 400px;
      }
      .company-detail-left {
        width: 444px;
      }
      .company-detail-left .company-name h3 {
          font-family: serif;
          font-weight: bold;
          font-size: 24px;
          margin-bottom: 3px;
      }
      .company-detail-left .company-address {
          font-size: 13px;
          font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
      }

      .customer-detail-right {
        width: 234px;
      }

      .text-center {
        text-align: center;
      }
      .text-rigth {
        text-align: right;
      }
        .table td, .table th {
          background-color: transparent !important;
        }
        .bill-detail {
          height: 650px;
        }

      .footer {
        position: fixed;
              bottom: 0px;
              left: 0px;
      }
      .footer .foot {
          background-color: #67a3b7 !important;
          -webkit-print-color-adjust: exact;
      }

      .table>tbody>tr>td {
        padding: 0 3px;
        height: 20px;
      }
      .table-bordered>tbody>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
      }

      .billing-title {
        text-align: center;
        font-weight: bold;
        font-size: 14px;
          text-decoration: underline;
      }
      h3 {
        margin-top: 0px;
      }



  </style>



<table> 
  <thead>
    <tr>
      <td>
        <div class="customer-detail inner-container" style="margin-top: 20px;">
          <div class="left-float company-detail-left">
            <div class="company-name">
              <h3>SHC</h3>
            </div>
            <div class="company-address">
              WEREWRWEr
            </div>
            <div class="company-address">
              TEL: 547645764576
            </div>
            <div class="company-address">
              Mobile: 897685678657
            </div>

            <div class="company-address">
              GST NO : DFD3453DEFDF
            </div>
          </div>
          <div class="left-float top-right">
            <div class="right-logo">
              <img src="<?php echo get_template_directory_uri(); ?>/admin/inc/images/invoice/right-logo-1.jpg">
            </div>
          </div>
          <div class="clear"></div>
        </div>
        <div class="customer-detail inner-container" style="margin-top: 2px;margin-bottom:2px;">
          <div class="billing-title">
            HIRE BILL
          </div>
        </div>
      </td>
    </tr>
  </thead>
  <tbody>


<?php
$bill_data = array('fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg','fgfdg' );
  $pages = false;
  $per_page = 16;
  $pieces = false;
  $tota_row = 0;

  if($bill_data) {
    $pages = ceil(count($bill_data)/$per_page);
    $pieces = array_chunk($bill_data, $per_page);
    $tota_row = count($bill_data);
    $reminder = ($tota_row % $per_page);
  }



  $page_total[-1] = 0;
  for ($i = 0; $i < $pages; $i++) { 
    $tot_tmp = 0;
    foreach ($pieces[$i] as $key => $h_value) {
      $tot_tmp = $tot_tmp + $h_value->hiring_amt;
    }
    $page_total[$i] = $page_total[$i-1] + $tot_tmp;
  }


      for ($i = 0; $i < $pages; $i++) { 
        $page_start = ( $i * $per_page ) + 1;
        $current_page = ($i + 1);
    ?>
      <tr>
        <td>
          <div class="inner-container" style="margin-top: 0px;">
            <div class="bill-detail">
              <table class="table table-bordered" style="margin-bottom: 2px;">
                <thead>
                  <tr>
                    <th colspan="4">
                      <div style="min-height: 100px;padding:5px;">
                        <div style="line-height:10px;">
                          To: 
                        </div>
                        <div style="margin-left:30px;line-height:10px;">
                          dfgfdg
                        </div>
                        <div style="margin-left:30px;margin-top:5px;">
                          fgdfg
                        </div>
                      </div>
                    </th>
                    <th colspan="4">
                      <div style="min-height: 100px;padding:5px;">
                        <div>
                          <div style="line-height: 20px;height: 25px;">
                            <div style="float:left;width: 60px">BILL NO</div>
                            <div style="float:left;">
                              : fdgdf
                            </div>
                            <div class="clear"></div>
                          </div>
                          <div style="line-height: 20px;height: 25px;">
                            <div style="float:left;width: 60px">DATE</div>
                            <div style="float:left;">
                              : hggfh
                            </div>
                            <div class="clear"></div>
                          </div>
                          <div style="line-height: 20px;height: 25px;">
                            <div style="float:left;width: 60px">SITE</div>
                            <div style="float:left;">
                              : tguyyr
                            </div>
                            <div class="clear"></div>
                          </div>
                          <div class="clear"></div>
                        </div>
                      </div>
                    </th>
                  </tr>
                  <tr>
                    <th style="width:35px;padding:0" class="center-th" rowspan="2">
                      <div class="text-center">S.No</div>
                    </th>
                    <th class="center-th" style="" rowspan="2">
                      <div class="text-center">Description</div>
                    </th>
                    <th class="center-th" style="width:35px;padding:0;" rowspan="2">
                      <div class="text-center">Qty</div>
                    </th>
                    <th class="center-th" style="padding: 0;" colspan="3">
                      <div class="text-center">Peroid</div>
                    </th>
                    <th class="center-th" style="padding: 0;">
                      <div class="text-center">Rate/Day</div>
                    </th>
                    <th class="center-th" style="padding: 0;width: 80px;">
                      <div class="text-center">Amount</div>
                    </th>
                  </tr>
                  <tr>
                    <th style="padding: 0;width: 70px;"><div class="text-center">From</div></th>
                    <th style="padding: 0;width: 70px;"><div class="text-center">To</div></th>
                    <th style="padding: 0;width: 35px;"><div class="text-center">No of Days</div></th>
                    <th style="padding: 0;width: 65px;"><div class="text-right">Rs Ps</div></th>
                    <th style="padding: 0;width: 35px;"><div class="text-right">Rs Ps</div></th>
                  </tr>
                </thead>


                <?php
                if($current_page > 1) {
                ?>
                  <tr>
                    <td></td>
                    <td>
                      <div class="text-center">BF / TOTAL</div>
                    </td>
                    <td><div class="text-center">-</div></td>
                    <td><div class="text-center">-</div></td>
                    <td><div class="text-center">-</div></td>
                    <td><div class="text-center">-</div></td>
                    <td><div class="text-right">-</div></td>
                    <td>
                      <div class="text-right">
                        jhgjjhgj
                      </div>
                    </td>
                  </tr>
                <?php
                }
                foreach ($pieces[$i] as $key => $value) {
                ?>
                  <tr>
                    <td>
                      <div class="text-center">
                        <?php echo $page_start ?>
                      </div>
                    </td>
                    <td>
                      <?php echo $value?>
                    </td>
                    <td>
                      <div class="text-center">
                        45
                      </div>
                    </td>
                    <td>
                      <div class="text-center" style="text-align: right;">
                        ghgf
                      </div>
                    </td>
                    <td>
                      <div class="text-center">
                        657
                      </div>
                    </td>
                    <td>
                      <div class="text-center">
                        gfj
                      </div>
                    </td>
                    <td>
                      <div class="text-rigth">
                        sada
                      </div>
                    </td>
                    <td>
                      <div class="text-rigth">
                        fdgf
                      </div>
                    </td>
                  </tr>


                <?php
                  $page_start++;
                }
                  if($pages == $current_page) {
                ?>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colspan="7"><div class="text-center">Total (Hire Charges)</div></td>
                      <td>
                        <div class="text-rigth">
                          100
                        </div>
                      </td>
                    </tr>
                    
                    <table>
                      <tr>
                        <td>Amount Chargable (in words)</td>
                      </tr>
                      <tr>
                        <td><b>INR fghfghfg</b></td>
                      </tr>
                    </table>


                          <table class="table table-bordered" style="margin-top:10px;margin-bottom: 5px;">
                            <thead>
                              <tr>
                                <th class="center-th" style="" rowspan="2">
                                  <div class="text-center">HSN</div>
                                </th>
                                <th class="center-th" style="width:90px;padding:0;" rowspan="2">
                                  <div class="text-center">Taxable Value</div>
                                </th>
                                <th class="center-th" style="padding: 0;" colspan="2">
                                  <div class="text-center">CGST</div>
                                </th>
                                <th class="center-th" style="padding: 0;" colspan="2">
                                  <div class="text-center">SGST</div>
                                </th>
                              </tr>
                              <tr>
                                    <th style="padding: 0;width: 70px;"><div class="text-center">Rate</div></th>
                                    <th style="padding: 0;width: 70px;"><div class="text-center">Amount</div></th>
                                    <th style="padding: 0;width: 70px;"><div class="text-center">Rate</div></th>
                                    <th style="padding: 0;width: 70px;"><div class="text-center">Amount</div></th>
                  
                              </tr>
                            </thead>
                            <tbody>
                                  <tr>
                                    <td>
                                      <div class="text-center">
                                        dfsdf
                                      </div>
                                    </td>
                                    <td>
                                      <div class="text-right">
                                        567567
                                      </div>
                                    </td>
                                        <td>
                                          <div class="text-right">
                                            9%
                                          </div>
                                        </td>
                                        <td>
                                          <div class="text-right">
                                            454
                                          </div>
                                        </td>
                                        <td>
                                          <div class="text-right">
                                            9%
                                          </div>
                                        </td>
                                        <td>
                                          <div class="text-right">
                                            45
                                          </div>
                                        </td>
                                  </tr>
                            </tbody>
                          </table>
                        <table>
                          <tr>
                            <td>Tax Amount (in words)</td>
                          </tr>
                          <tr>
                            <td><b>INR hgjhj</b></td>
                          </tr>
                        </table>
                <?php
                  } else {
                ?>
                    <tr>
                      <td colspan="7">
                        <div class="text-center">CF / TOTAL</div>
                      </td>
                      <td>
                        <div class="text-right">
                          45435
                        </div>
                      </td>
                    </tr>
                <?php
                  }

                ?>
                
              </table>
            </div>
          </div>
        </td>
      </tr>
    <?php
      }
    ?>
  </tbody>
</table>

<div class="footer" style="margin-bottom:20px;">





    <div class="inner-container" style="margin-top: 5px;">

      <div class="left-float" style="width: 480px;float: left;padding-right: 10px;">
        <div style="float: left;padding-right: 10px;font-size: 10px;margin-top: 30px;">
          Immediate interest @ 24% p.a. will be charged if not paid within 3 days from the date of the bill
          <ul style="margin-bottom: 2px;">
            <li>All materials should be returned thoroughly cleaned and oiled</li>
            <li>White waste oil should be used for oiling all materials</li>
            <li>For all materials minimum hire charges will be for 30 days</li>
          </ul>
        </div>
        <div class="clear"></div>
      </div>
      <div class="left-float" style="width: 154px;margin-top:15px;">
        <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 16px;">
          For SHC
        </div>
        <div style="margin-top: 30px;">Manager / Accountant</div>
      </div>
      <div class="clear"></div>
    </div>



    <div class="inner-container foot" style="width: 810px;line-height: 20px;font-size: 14px;color: #fff !important;">
      <div class="left-float" style="width:325px;font-size: 14px;color: #fff !important;text-align: center;">Email : infojbcaccesss@gmail.com</div>
      <div class="left-float" style="width:325px;font-size: 14px;color: #fff !important;text-align: center;">Website : www.jcbascdfdsgdfg.in</div>
      <div class="clear"></div>
    </div>
</div>






</div>

