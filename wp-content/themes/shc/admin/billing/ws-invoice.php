<?php

if(isset($_GET['action']) && $_GET['action'] == 'update'){
  echo "<script language='javascript'>
  jQuery(document).ready(function (argument) {
    jQuery('.ws_delivery_check').trigger('click');
  });
</script>";
}
    $bill_data = false;
    $invoice_id['inv_id'] = '';

    if(isset($_GET['id']) && $_GET['id'] != '' ) {
        if(isValidInvoicews($_GET['id'],$_GET['year'] ,1)){
            $update = true;
            $year = $_GET['year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getBillDataws($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $bill_id = $bill_fdata->id;
            $invoice_id['inv_id'] = $bill_fdata->inv_id;
            $gst_slab = gst_group($bill_id);
            $gst_data = $gst_slab['gst_data'];
        }
        else {
             echo "<script>alert('INVOICE NOT FOUND!!! Try another number');</script>";
        }
    }
    if($bill_data && $bill_ldata && count($bill_ldata)>0) {
      $i = 1;
      $sale_unit = 0;
      $delivery_count = 0;
      foreach ($bill_ldata as $d_value) {
        
           $delivery_count = $delivery_count + $d_value->delivery_count; 
           $sale_unit = $sale_unit + $d_value->sale_unit; 
        }
      }
$profile = get_profile1();
$netbank = get_netbank1();
$payment_type = ws_paymenttypeGroupByType($_GET['id'],$_GET['year']);
$tot_paid_amt = 0;
foreach ($payment_type['WithOutCredit'] as $p_value) {
  $tot_paid_amt = $p_value->amount + $tot_paid_amt;
 }
?>
<?php if(isset($_GET['action'])){ ?>
<script>
  function print_current_page()
  {
  // window.print();
  // var printPage = window.open(document.URL, '_blank');
  // setTimeout(printPage.print(), 5);
  var url      = window.location.href; 
  var printPage = window.open(url+'1', '_blank');
  setTimeout(printPage.print(), 5);
  }
</script>
<?php } else { ?>
<script>
  function print_current_page()
  {
  // window.print();
  var printPage = window.open(document.URL, '_blank');
  setTimeout(printPage.print(), 5);
  
  }
</script>
<?php }  ?>
<div class="">
  <div class="">
    <div class="col-md-12 print-hide">
      <div class="x_panel">
        <div class="x_title">
            <form action="<?php menu_page_url( 'ws_invoice' ); ?>" method="GET">
                  <h2>Invoice 
                      <input type="hidden" name="page" value="ws_invoice">
                      <input type="text" name="id" class="invoice_id" value="<?php echo $invoice_id['inv_id']; ?>" autocomplete="off">
                       Year
                      <select name="year" class="year">
                            <?php   $current_year = date('Y');
                                    $display_year = $current_year - 30;
                                    $added_year = 0;
                                    for($i=0;$i<60;$i++) {
                                        echo $years = $display_year + $added_year;
                                        if($years == $_GET['year'] ){ $selected = 'selected'; } else {
                                           $selected = ''; 
                                        }
                                        echo '<option value="'.$years.'"'.$selected.'>'.$years.'</option>';
                                        $added_year++;
                                    } 
                            ?>
                        </select>
                      <input type="submit" style="height: 38px;margin-left: 20px;" class="btn btn-success">
                  </h2>
                  <!-- ws_print_bill -->
               
            </form>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

        <?php
            if($bill_data) {
        ?>
          <section class="content invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12 invoice-header">
                    <h3>
                        <i class="fa fa-globe"></i> Invoice Number.   <?php echo $bill_fdata->inv_id; ?>
                        <small class="pull-right"><?php echo $bill_fdata->created_at; ?></small>
                    </h3>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
              <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
                    <br/><?php echo $profile ? $profile->address : '';  ?>
                    <br/><?php echo $profile ? $profile->address2 : '';  ?>
                    <br/>Cell : <?php echo $profile ? $profile->phone_number : '';  ?>
                    <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?>
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong><?php echo $bill_fdata->customer_name; ?></strong>
                    <br><?php echo $bill_fdata->address; ?>
                    <br><?php echo $bill_fdata->mobile; ?>
                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                <b>Invoice <?php echo $bill_fdata->inv_id; ?></b>
                <br>
                <br>
                <b>Order ID:</b> <?php echo $bill_fdata->order_id; ?>
                <br>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
              <div class="col-xs-12 table">
                <h2>Billed Items</h2>
                <input type="checkbox" name="ws_check_all" value="ws_check_all" class="ws_check_all" <?php if(isset($_GET['action']) && $_GET['action'] == 'update') {echo 'checked'; } else { if($delivery_count == $sale_unit){ echo 'checked'; } } ?> style="width: 20px;height: 20px;">  Delivery Check all
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width:50px;">S.No</th>
                      <th style="width:50px;">DELIVERY</th>
                      <th style="width:300px;">PRODUCT</th>
                      <th style="width:300px;">HSN CODE</th>
                      <th style="width:80px;">QTY</th>
                      <th style="width:120px;">MRP</th>
                      <th style="width:140px;">DISCOUNT</th>
                       <th style="width:140px;">WHOLESALE AMOUNT</th>
                      <th style="width:140px;">AMOUNT</th>
                      <th style="width:90px;">CGST</th>
                      <th style="width:90px;">CGST AMOUNT</th>
                      <th style="width:90px;">SGST</th>
                      <th style="width:90px;">SGST AMOUNT</th>
                      <th style="width:120px;">SUB TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                            $i = 1;
                            foreach ($bill_ldata as $d_value) {
                               $is_delivery =$d_value->is_delivery;
                                if($is_delivery == 1){
                                  $checked = 'checked';
                                }
                                else {
                                  $checked = '';
                                }
                    ?>
                        <tr>
                          <td>
                            <div class="rowno"><?php echo $i; ?></div>
                          </td>
                           <td class="delivery">
                                <input type="checkbox" class="ws_delivery_check"  <?php echo $checked; ?>  style="width: 20px; height: 20px;"/>
                             
                                <input type="text" value="<?php if($_GET['action'] == 'update'){
                                            echo $d_value->sale_unit;
                                  } else { 
                                    if($d_value->delivery_count > 0) {
                                        echo $d_value->delivery_count;
                                      } 
                                      else 
                                        { echo '0'; 
                                      }
                                    }?>" class="ws_delivery_count" onkeypress="return isNumberKey(event)" style="width: 30px;margin-top: 5px;"/>  
                          </td> <input type="hidden" value="<?php  echo $d_value->id; ?>" class="ws_delivery_id" />
                          <td>
                            <span class="span_product_name"><?php echo $d_value->product_name; ?></span>
                          </td>
                          <td>
                            <span class="span_hsn"><?php echo $d_value->hsn; ?></span>
                          </td>
                          <td>
                            <span class="span_unit_count"><?php echo $d_value->sale_unit; ?><span>
                               <input type="hidden" value="<?php echo $d_value->sale_unit; ?>" name="unit_count" class="ws_unit_count"/> 
                          </td>
                          <td>
                            <span class="span_unit_price"><?php echo $d_value->unit_price; ?><span>
                          </td> 
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->discount; ?><span>
                          </td>
                           <td>
                            <span class="span_whole_price"><?php echo $d_value->wholesale_price; ?><span>
                          </td>
                          <td>
                            <span class="span_sub_total"><?php echo $d_value->amt; ?></span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->cgst; ?></span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->cgst_value; ?></span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->sgst; ?></span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->sgst_value; ?></span>
                          </td>                               
                          <td>
                            <span class="span_sale_tax"><?php echo $d_value->sub_total; ?></span>
                          </td>
                        </tr>
                    <?php
                        $i++;
                        }
                    }

                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
              <!-- accepted payments column -->
              <div class="col-xs-6">
               <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">TAXABLE VALUE</th>
                        <th colspan="2">CENTRAL SALES TAX</th>
                        <th colspan="2">STATE SALES TAX </th> 
                    </tr>
                    <tr>
                        
                        <th>CGST<br/>-RATE</th> 
                        <th>CGST<br/>-AMOUNT</th>
                        <th>SGST<br/>-RATE</th> 
                        <th>SGST<br/>-AMOUNT</th>
                    </tr>
                </thead>

                <tbody>
                    <?php  if(isset($gst_data)) { 
                      $total_tax=0;
                            foreach( $gst_data as $g_data) {

                     ?>
                    <tr class="">
                        <td class="amt_zero">Rs. <?php  echo $g_data->sale_amt; ?></td>
                        <td class="cgst_zero"><?php echo $g_data->cgst; ?> % </td>
                        <td class="cgst_val_zero"><?php echo $g_data->sale_cgst; ?></td>
                        <td class="sgst_zero"><?php echo $g_data->cgst; ?> % </td>
                        <td class="sgst_val_zero"><?php echo $g_data->sale_sgst; ?></td>
                    </tr>
                      <?php $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
                            }
                          } ?>
                    <tr><td></td><td></td><td></td><td>Total Tax</td><td><?php echo $total_tax; ?></td></tr> 
                
            </table>
              </div>
              <div class="col-xs-2">
              </div>

              <!-- /.col -->
              <div class="col-xs-4">
                <p class="lead"></p>
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <!-- <tr>
                        <th style="width:50%">Total :</th>
                        <td><?php echo $bill_fdata->before_total; ?></td>
                      </tr>
                      <tr>

                        <th>Discount:</th>
                        <td><?php 
                          echo $bill_fdata->discount + 0;
                          echo '%';
                        
                        ?></td>
                      </tr> -->
                      <tr>
                        <th style="width:50%">Total Amount:</th>
                        <td><?php echo $bill_fdata->sub_total; ?></td>
                      </tr>
                       <tr>
                        <th>Amount Paid:</th>
                        <td><?php echo $tot_paid_amt + $bill_fdata->pay_to_bal; ?></td>
                      </tr>
                       <tr>
                        <th>Amount Return:</th>
                        <td><?php echo $bill_fdata->pay_to_bal; ?></td>
                      </tr>
                      <tr>
                        <th>Due Amount:</th>
                        <td><?php echo $bill_fdata->sub_total - $tot_paid_amt; ?></td>
                      </tr>
                      <tr>
                        <th>Payment Type:</th><td>


                        <?php $internet_check = '';
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
                            if($p_value->payment_type == 'internet'){
                              $internet_check = $p_value->payment_type; 
                              echo 'Netbanking : ';
                              echo $p_value->amount.'</br>';
                              ?>
                              <table>
                                <tr> <td><b>Banking Details,</b></td><td></td></tr>
                                <tr> <td>Name</td><td> : <?php echo $netbank ? $netbank->shop_name : ''; ?></td></tr>
                                <tr> <td>Bank Name</td><td> : <?php echo $netbank ? $netbank->bank : ''; ?></td></tr>
                                <tr> <td>Account Number</td><td> : <?php echo $netbank ? $netbank->account : ''; ?></td></tr>
                                <tr> <td>IFSC Code</td><td> : <?php echo $netbank ? $netbank->ifsc : ''; ?></td></tr>
                                <tr> <td>Account Type</td><td> : <?php echo $netbank ? $netbank->account_type : ''; ?></td></tr>
                                <tr> <td>Branch</td><td> : <?php echo $netbank ? $netbank->branch : ''; ?></td></tr>
                              </table>
                              <?php 
                             
                            }
                            if($p_value->payment_type == 'credit'){
                              echo  'Credit : ';
                              echo $p_value->amount.'</br>';

                            }
                                
                          }
                        ?>
                        
                      </td>
                    </tr>
                      <tr>
                        <th>COD</th>
                        <td>
                          <?php echo $bill_fdata->cod_amount; ?>
                        </td>
                      </tr>
                      
                       <tr>
                        <th>Home Delivery:<br/></th>
                          <td>
                             <?php 
                                
                                    echo $bill_fdata->home_delivery_name;
                                    echo "<br/>";
                                    echo $bill_fdata->home_delivery_mobile;
                                    echo "<br/>";
                                    echo $bill_fdata->home_delivery_address; 
                               

                            ?> 
                          </td> 
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- this row will not appear when printing -->
            
          </section>


          <?php
            }
          ?>
          <div class="pull-right">
            <button class="btn btn-primary  ws_generate_bill pdf" style="margin-right: 5px;"><i class="fa fa-file-pdf-o" href=""></i> Generate PDF</button>
               <!--  <button class="btn btn-default  pull-right bill_retail_print" onclick="print_current_page();"><i class="fa fa-print"></i> Print</button> -->
            <a class="btn btn-default prt" href="javascript:void(0)" target="_blank" onclick="print_current_page();"><i class="fa fa-print"></i> Print</a> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


 <script type="text/javascript">
    // var invoice = jQuery('.invoice_id').val();
    // jQuery('.invoice_id').focus().val('').val(invoice);
    jQuery('.prt').focus();
 //<-----After keydown submit using tab goto first text box in Return billing--->
  jQuery(".prt").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.pdf').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('.invoice_id').focus();
      } 
      else {
        jQuery('.prt').focus();
      }
        

  });

  jQuery(".invoice_id").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.prt').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('.year').focus();
      } 
      else {
        jQuery('.invoice_id').focus();
      }
        

  });

 </script>
  

























































































<style type="text/css">
  @media screen {
    .A4 {
      display: none;
    }

  }
  /** Fix for Chrome issue #273306 **/
  @media print {
    #adminmenumain, #wpfooter, .print-hide {
      display: none;
    }
    body, html {
      height: auto;
      padding:0px;
      font-family: normal;
      font-size: 12px;
    }
    html.wp-toolbar {
      padding:0;
    }
    #wpcontent {
      background: white;
      box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
      margin: 1mm;
      display: block;
      padding: 0;
    }



  }


</style>



<!DOCTYPE html>
<html>
<head>
  

<meta charset="utf-8">
<style type="text/css">



  /** Fix for Chrome issue #273306 **/
  @page {
    size: A4;
    margin: 20px;
  }
  @media print {
    html, body {
      width: 210mm;
      height: 297mm;
      
    }

   
    /* ... the rest of the rules ... */
  }

 
    .sheet {
      margin: 0;
    }
    
    .inner-container {
      padding-left: 10mm;
      padding-right: 0mm;        
      width: 210mm;
    }      
    

/*  New Format csss */
    .print-table {
      padding-top: 5mm;
      
    }
    .print-table hr {
      color: #000;
    }
    .print-table tr td {
      border: 1px solid #000;
      padding: 5px;
    }
    .print-table table {
      color: #000;
      /*border-collapse: collapse;*/
    }
    .declare_section {
      padding-top: 20px;
      padding-left: 30px;
    }
    .text_bold {
      font-weight: bold;
    }
    .text_center {
      text-align: center;
    }
    .footer table tr td {
      border: none;
    }

     table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
.exempted span{
  margin-left: 45%;
  font-size: 18px;
}
</style>
<!-- New Table -->

<div class="A4 page-break">
<div class=" print-table">
    <div class="sheet padding-10mm">

    <div class="inner-container" >
      <table class="customer-detail " style="margin-top: 20px;margin-bottom:2px;  border-collapse: collapse; " >
        <tbody>
            <tr>
                <td colspan="12" style=" text-align: center; font-weight: bold; font-size: 22px;"  ><b>ORIGINAL INVOICE</b></td>
            </tr>
            <tr>
                <td colspan="8">
                  <p><strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
                <br/><?php echo $profile ? $profile->address : '';  ?>
                <br/><?php echo $profile ? $profile->address2 : '';  ?>
                <br/>Cell : <?php echo $profile ? $profile->phone_number : '';  ?>
                <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?></p>
                </td>
                <td colspan="4">
                  <b>INVOICE NO - <?php echo 'Inv '.$_GET['id']; ?><br> 
                  DATE - <?php $timestamp = $bill_fdata->modified_at; 
        $splitTimeStamp = explode(" ",$timestamp);
        echo $date = $splitTimeStamp[0];?> </b>
                  <hr>
                  <b>STATE             : TAMILNADU <br> 
                  STATE CODE : 33 </b></td>
            </tr>
             <tr>
                <td colspan="8">
                  <b>Buyer,</b><br>
                  <b><?php echo $bill_fdata->company_name; ?></b><br>
                  <?php echo $bill_fdata->customer_name; ?><br>
                  <?php echo $bill_fdata->mobile; ?><br>
                  <?php echo $bill_fdata->address; ?><br>
                  <b> GST NO<?php echo $bill_fdata->gst_number; ?></b>
                </td>                 
                <td colspan="4">
                  <b>DELIVERY ADDRESS</b><br>
                  <?php echo $bill_fdata->home_delivery_name; ?><br>
                  <?php echo $bill_fdata->home_delivery_mobile; ?><br>
                  <?php echo $bill_fdata->home_delivery_address; ?><br>
                </td>                
            </tr>
            <tr class="text_bold text_center">
              <td rowspan="2">S.NO</td>
              <td rowspan="2">HSN CODE</td>
              <td rowspan="2">PRODUCTS</td>
              <td rowspan="2">QTY</td>
              <td rowspan="2">MRP Per Piece</td>
              <td rowspan="2">Discounted Price</td>
              <td rowspan="2">AMOUNT</td>
              <td colspan="2">CGST</td>
              <td colspan="2">SGST</td>
              <td colspan="2">TOTAL</td>
            </tr>
            <tr class="text_bold text_center">
              <td>RATE</td>
              <td>AMOUNT</td>
              <td>RATE</td>
              <td>AMOUNT</td>
              <td>AMOUNT</td>
            </tr>

                <?php
               if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                  $i = 1;
                  $page_start = 1;
                  foreach ($bill_ldata as $value) {
                ?>


            <tr class=" text_center">
                <td><?php echo $page_start; ?></td>
                <td><?php echo $value->hsn; ?></td>
                <td><?php echo $value->product_name; ?></td>
                <td><?php echo $value->sale_unit; ?></td>                
                <td><?php echo $value->unit_price; ?></td>
                <td><?php echo $value->discount;?></td>
                <td><?php echo $value->amt; ?></td>
                <td><?php echo $value->cgst; ?></td>
                <td><?php echo $value->cgst_value; ?></td>
                <td><?php echo $value->sgst; ?></td>
                <td><?php echo $value->sgst_value; ?></td>
                <td><?php echo $value->sub_total; ?></td>                
            </tr>
            <?php
           
            $page_start++;
            }

            
            ?>   
            <tr>
              <td colspan="11" style=" text-align: right;" ><div  >Total</div></td>
              <td>
                <div class="text-center"> 
                  <?php echo $final_total=$bill_fdata->sub_total; ?>
                  
                </div>
              </td>
            </tr>                       
            <?php
              

            ?>
             <?php
            }
          ?>
            <tr>
                <td colspan="12">Amount Chargable ( In Words)  <b> <?php echo ucfirst(ucwords(convert_number_to_words_full($final_total))); ?></b></td>
            </tr>

          </tbody>
        </table>
        </div>

        <?php 
        if(isset($gst_data)) {
          $total_tax=0;
          foreach( $gst_data as $g_data) {
            $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
            $gst_tot = $g_data->sale_sgst + $gst_tot;
          }
          if($gst_tot == '0.00'){
            echo "<div class='exempted'><span><b>GST EXEMPTED</span></b></div>";
          }
        } 
        
      ?>
        <!-- TAX TABLE START -->
        <div class="inner-container" > 
        <table  class="customer-detail" style="margin-top: 20px;margin-bottom:2px; text-align: center;  border-collapse: collapse; ">
          <tbody>
            <tr class="text_bold text_center" >                
                <td rowspan="2">TAXABLE VALUE</td>
                <td colspan="2">CENTRAL SALES TAX</td>
                <td colspan="2">STATE SALES TAX</td>                
            </tr>
            <tr class="text_bold text_center">                
                
                <td>%</td>
                <td>AMOUNT</td>
                <td>%</td>
                <td>AMOUNT</td>                
            </tr>
            <?php  
            if(isset($gst_data)) { 
              $total_tax=0;
              foreach( $gst_data as $g_data) {
           ?>
                <tr class="">
                  <td class="amt_zero">Rs. <?php  echo $g_data->sale_amt; ?></td>
                  <td class="cgst_zero"><?php echo $g_data->cgst; ?> % </td>
                  <td class="cgst_val_zero"><?php echo $g_data->sale_cgst; ?></td>
                  <td class="sgst_zero"><?php echo $g_data->cgst; ?> % </td>
                  <td class="sgst_val_zero"><?php echo $g_data->sale_sgst; ?></td>
                </tr>
                <?php $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
                }
              } 
            ?>
            <tr>
                <td class="text_center" colspan="4" ><b>TOTAL  TAX</b></td>                
                <td><b><?php echo $total_tax; ?></b></td>                
            </tr>
            <tr>   
              <td colspan="12" style=" text-align: left;" ><b>Tax Amount (in words) : <?php echo ucfirst(ucwords(convert_number_to_words_full($total_tax))); ?>  </b></td>
            </tr>
          </tbody>
        </table>
        </div>
        <!-- TAX TABLE END  -->
        <style type="text/css">
/*      .customer-signature, .company-signature {
        width: 85mm;
      }*/
    </style>

      <div class="footer" style="margin-bottom:20px;">
          <div class="" style="margin-top: 5px;">

            <table style="width:97%;margin-left: 10mm;margin-right: 10mm;">
              <tr>
                <td colspan="2">
                  <b><u>Declaration</u></b>
                  <div style="margin-bottom:10px;">We declare that  this  invoice  shows  the  actual price of the goods described and that all particulars are true and correct</div>
                </td>
              </tr>
              <?php if($internet_check == 'internet'){ ?>
              <tr>
                <td>
                  <table border="1" style="margin-bottom:10px;">
                     <tr><td><b>Banking Details,</b><br/>
                     Name : <?php echo $netbank ? $netbank->shop_name : ''; ?>
                     Bank Name : <?php echo $netbank ? $netbank->bank : ''; ?>
                     Account Number : <?php echo $netbank ? $netbank->account : ''; ?>
                     IFSC Code : <?php echo $netbank ? $netbank->ifsc : ''; ?>
                     Account Type : <?php echo $netbank ? $netbank->account_type : ''; ?>
                     Branch : <?php echo $netbank ? $netbank->branch : ''; ?> </td></tr>
                  </table>
                </td>
              </tr>
              <?php } ?>
              <tr>
                <td>
                  <div class="customer-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 16px;">
                      Customer Seal & Signature
                    </div>
                    <div style="height: 80px;"></div>
                  </div>
                </td>
                <td>
                  <div class="company-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 16px;text-align:right;">
                      For Saravana Health Store
                    </div>
                    <div style="margin-top: 60px;text-align:right;">Authorised Signatory</div>
                  </div>
                </td>
              </tr>
            </table>

          </div>
      </div>
  </div>
</div>
</div>







 <!--  <div class="A4">
    <div class="sheet padding-10mm">
    


    </div>
  </div> -->
<body>
  </html>