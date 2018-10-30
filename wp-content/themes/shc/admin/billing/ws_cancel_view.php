<?php
    $bill_data = false;
    $invoice_id['inv_id'] = '';

    if(isset($_GET['id']) && $_GET['id'] != '' ) {

            $update = true;
            $year = $_GET['year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getCancelBillDataws($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $bill_id = $bill_fdata->id;
            $invoice_id['inv_id'] = $bill_fdata->inv_id;
            $gst_slab = gst_group_cancel($bill_id);
            $gst_data = $gst_slab['gst_data'];
    }
$profile = get_profile1();
?>
<script>
function print_current_page()
{
//window.print();
var printPage = window.open(document.URL, '_blank');
setTimeout(printPage.print(), 5);
}
</script>
<div class="container">
  <div class="row">
    <div class="col-md-12 print-hide">
      <div class="x_panel">
        <div class="x_title">
            
               
           
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
                    <h1>
                        <i class="fa fa-globe"></i> Invoice Number.   <?php echo $bill_fdata->id; ?>
                        <small class="pull-right"><?php echo $bill_fdata->modified_at; ?></small>
                    </h1>
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
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width:50px;">S.No</th>
                      <th style="width:300px;">PRODUCT</th>
                      <th style="width:300px;">HSN CODE</th>
                      <th style="width:80px;">QTY</th>
                      <th style="width:120px;">MRP</th>
                      <th style="width:140px;">DISCOUNT</th>
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
                            <span class="span_sub_total"><?php echo $d_value->amt; ?></span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->cgst; ?><span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->cgst_value; ?><span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->sgst; ?><span>
                          </td>
                           <td>
                            <span class="span_unit_price"><?php echo $d_value->sgst_value; ?><span>
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
                        
                        <th>CGST-RATE</th> 
                        <th>CGST-AMOUNT</th>
                        <th>SGST-RATE</th> 
                        <th>SGST-AMOUNT</th>
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
                      
                      <tr>
                        <?php if($bill_fdata->discount != 0){ ?>
                        <th>Discount:</th>
                        <td><?php 
                          echo $bill_fdata->discount + 0;
                          echo '%';
                        
                        ?></td>

                      <?php   } ?>
                        
                      </tr>
                      <tr>
                        <th style="width:50%">Amount Payable:</th>
                        <td><?php echo $bill_fdata->sub_total; ?></td>
                      </tr>
                       <tr>
                        <th>Amount Paid :</th>
                        <td><?php echo $bill_fdata->paid_amount; ?></td>
                      </tr>
                      <!-- <tr>
                        <th>Balance Amount:</th>
                        <td><?php echo $bill_fdata->return_amt; ?></td>
                      </tr> -->
                     <!--  <tr>
                        <th>Payment Type:</th>
                        <td><?php echo $bill_fdata->payment_type; ?></td>
                      </tr> -->
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

                   <a class="btn btn-default pull-right" href="javascript:void(0)" target="_blank" onclick="print_current_page();"><i class="fa fa-print"></i> Print</a> 
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


        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    jQuery('.pull-right').focus();
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
      padding-left: 0mm;
      padding-right: 20mm;        
      width: 210mm;
    }      
    

/*  New Format csss */
    .print-table {
      padding-top: 5mm;
      font-size: 16px;
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
    .strikethrough {
      position: relative;
    }
    .strikethrough:before {
      position: absolute;
      content: "";
      left: 0;
      top: 50%;
      right: 0;
      border-top: 1px solid;
      border-color: inherit;

      -webkit-transform:rotate(-45deg);
      -moz-transform:rotate(-45deg);
      -ms-transform:rotate(-45deg);
      -o-transform:rotate(-45deg);
      transform:rotate(-45deg);
    }
     table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }

</style>
<!-- New Table -->

<div class="A4 page-break strikethrough">
<div class=" print-table">
    <div class="sheet padding-10mm">

    <div class="inner-container" >
      <table class="customer-detail " style="margin-top: 20px;margin-bottom:2px;  border-collapse: collapse; " >
        <tbody>
            <tr>
                <td colspan="12" style=" text-align: center; font-weight: bold; font-size: 22px;"  ><b>TAX INVOICE</b><h1><b style="color:red;">CANCELLED</b></h1></td>
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
                  STATE CODE : 33 </b>
                </td>
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
            <?php
            // $pages = false;
            // $per_page = 31;
            // $pieces = false;
            // $tota_row = 0;

            
              // $pages = ceil(count($bill_ldata)/$per_page);
              // $pieces = array_chunk($bill_ldata, $per_page);
              // $tota_row = count($bill_ldata);
              // $reminder = ($tota_row % $per_page);
            // }

            // $page_total[-1] = 0;
            // for ($i = 0; $i < $pages; $i++) { 
            //   $tot_tmp = 0;
            //   foreach ($pieces[$i] as $key => $h_value) {
            //     $tot_tmp = $tot_tmp + $h_value->hiring_amt;
            //   }
            //   $page_total[$i] = $page_total[$i-1] + $tot_tmp;
            // }


                // for ($i = 0; $i < $pages; $i++) { 
                //   $page_start = ( $i * $per_page ) + 1;
                //   $current_page = ($i + 1);

            ?>
           
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
                      //if($current_page > 1) {

                      ?>
                       <!-- 
                          <tr>
                          <td></td>
                          <td>
                            <div class="text-center">BF / TOTAL</div>
                          </td>
                          <td></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-center">-</div></td>
                          <td><div class="text-right">-</div></td>
                          <td>
                            <div class="text-right">
                             
                            </div>
                          </td>
                        </tr> -->

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
            <!-- <tr>
              <td colspan="11" style=" text-align: right;" ><div  >Total</div></td>
              <td>
                <div class="text-center">
                  <?php   $before = $bill_fdata->before_total; 
                          $final_total = $bill_fdata->sub_total;
                          echo $before; 
                          ?>
                </div>
              </td>
            </tr>   -->                     
           <!--  <tr>
              <td colspan="11" style=" text-align: right;" ><div  >Discount (%)</div></td>
              <td>
                <div class="text-center">
                  <?php echo $bill_fdata->discount; ?>
                </div>
              </td>
            </tr> -->
            <!--  <tr>
              <td colspan="11" style=" text-align: right;" ><div  >Discount (Amount)</div></td>
              <td>
                <div class="text-center">
                  <?php echo $before - $final_total; ?>
                </div>
              </td>
            </tr> -->
            <tr>
              <td colspan="11" style=" text-align: right;" ><div  >Total</div></td>
              <td>
                <div class="text-center"> 
                  <?php echo $final_total; ?>
                  
                </div>
              </td>
            </tr>                       
           <!--  <tr>                
                <td colspan="11" style=" text-align: right;"><b>TOTAL</b></td>
                <td class="text-center"  ><b><?php echo $final_total = $bill_fdata->sub_total; ?></b></td>
            </tr> -->
            <?php
             
            ?>
                <!-- <tr>
                  <td colspan="11" style="padding-bottom: 2em;border: none;">
                    <div class="text-center">CF / TOTAL</div>
                  </td>
                  <td style="padding-bottom: 2em;border: none;">
                    <div class="text-right">
                      <?php echo $tot; ?>
                    </div>
                  </td>
                </tr> -->
            <?php
              

            ?>
             <?php
            }
          ?>
            <tr>
                <td colspan="12">Amount Chargable ( In Words)  <b> <?php echo ucfirst(convert_number_to_words_full($final_total)); ?></b></td>
            </tr>

          </tbody>
        </table>
        </div>
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
                
                <td>RATE</td>
                <td>AMOUNT</td>
                <td>RATE</td>
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
              <td colspan="12" style=" text-align: left;" ><b>Tax Amount (in words) : <?php echo ucfirst(convert_number_to_words_full($total_tax)); ?>  </b></td>
            </tr>
          </tbody>
        </table>
        </div>
        <!-- TAX TABLE END  -->
        <style type="text/css">
      .customer-signature, .company-signature {
        width: 85mm;
      }
    </style>

      <div class="footer" style="margin-bottom:20px;">
          <div class="inner-container" style="margin-top: 5px;">

            <table>
              <tr>
                <td colspan="2">
                  <b><u>Declaration</u></b>
                  <div style="margin-bottom:10px;">We declare that  this  invoice  shows  the  actual price of the goods described and that all particulars are true and correct</div>
                </td>
              </tr>
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






<?php //require get_template_directory().'/admin/billing/inline_invoice/ws-invoice.php';  ?>