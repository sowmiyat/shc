<?php
/**
 * Template Name: SRC Wholesale Invoice
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
            $bill_id = $bill_fdata->id;
            $gst_slab = gst_group($bill_id);
            $gst_data = $gst_slab['gst_data'];
    }
?>
<!DOCTYPE html>
<html>
<head>
  <link rel='stylesheet' id='bootstrap-min-css'  href="<?php echo get_template_directory_uri(); ?>'/admin/inc/css/bootstrap.min.css'" type='text/css' media='all' />

<meta charset="utf-8">
<style type="text/css">



  /** Fix for Chrome issue #273306 **/
@page { margin: 0; }

    body, html {
      /*height: auto;
      padding:0px;*/
      margin: 0mm 25mm 25mm 0mm;

    }

  
    .sheet {
      margin: 0;
    }
    .A4 {
        width: 210mm;
    }
    .A4 .footer {
      position: fixed;
      bottom: 0px;
      left: 0px;
    }
    .A4 .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    } 
    .inner-container {
      padding-left: 20mm;
      padding-right: 20mm;        
      width: 210mm;
    }      
    .left-float {
      float: left;
    }
    .company-detail {
      height: 100px;
    }
    .company-detail .company-name h3 {
      font-family: serif;
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 3px;
    }
    .company-detail .company-address-txt {
        font-size: 13px;
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
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
     
    .company-logo {
      width: 50mm;
    }
    .company-address {
      width: 70mm;
    }
    .invoice-detail {
      width: 50mm;
    }

    .invoice-no {
      margin-bottom: 15px;
      font-size: 18px;
    }
    .buyer-detail, .delivery-detail {
      min-height: 100px;
      padding: 20px 10px 20px 10px;
    }
    .buyer-address, .delivery-address {
      padding-left: 10px;
      min-height: 80px;
    }
    .header-txt {
      font-size: 10px;
    } 
    .sale-table-invoice tbody {
      font-size: 13px;
    }



/*  New Format csss */
    .print-table {
      padding-top: 5mm;
      font-size: 14px;
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

</style>
<!-- New Table -->
<<<<<<< HEAD
<div class="A4 print-table ">
  <div class="sheet padding-10mm">
=======
<div class=" print-table ">
    <div class="sheet padding-10mm">
>>>>>>> d123dbcf0bae7481e21a05265fbd6a66a5d1e338
    <div class="inner-container" >
      <table class="customer-detail " style="margin-top: 20px;margin-bottom:2px;  border-collapse: collapse; " >
        <tbody>
            <tr>
                <td colspan="12" style=" text-align: center; font-weight: bold; font-size: 22px;"  ><b>TAX INVOICE</b></td>
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
                  <b>INVOICE NO - <?php echo $_GET['id']; ?><br> 
                  DATE - 05/09/2017 </b>
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
              <td colspan="11" style=" text-align: right;" ><div  >Discount (Hire Charges)</div></td>
              <td>
                <div class="text-center">
                  <?php echo $bill_fdata->discount; ?>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="11" style=" text-align: right;" ><div  >Total (Hire Charges)</div></td>
              <td>
                <div class="text-center"> 
                  <?php echo $final_total = $bill_fdata->sub_total;?>
                  
                </div>
              </td>
            </tr>                       
            <tr>                
                <td colspan="11" style=" text-align: right;"><b>TOTAL</b></td>
                <td class="text-center"  ><b><?php echo $final_total = $bill_fdata->sub_total; ?></b></td>
            </tr>
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







 <!--  <div class="A4">
    <div class="sheet padding-10mm">
    


    </div>
  </div> -->
<body>
  </html>