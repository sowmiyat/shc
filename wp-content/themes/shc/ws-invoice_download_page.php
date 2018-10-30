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
    if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoicews($_GET['id'],$_GET['cur_year'],1) ) {
          $update = true;
            $year = $_GET['cur_year'];
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

$profile = get_profile1();
$netbank = get_netbank1();
$payment_type = get_wspaymenttype($_GET['id'],$_GET['cur_year']);

$internet_check = '';
foreach ($payment_type as $p_value) {
  if($p_value->payment_type == 'internet'){
    $internet_check = $p_value->payment_type;   
  }   
}
?>


<link rel='stylesheet' id='bootstrap-min-css'  href="<?php echo get_template_directory_uri(); ?>'/admin/inc/css/bootstrap.min.css'" type='text/css' media='all' />

<style type="text/css">



  /** Fix for Chrome issue #273306 **/
  @page {
    
    margin: 20px;
  }
  @media print {
    html, body {
      width: 210mm;
      height: 297mm;
      font-family: normal;
      font-size: 10px;
      
    }

   
    /* ... the rest of the rules ... */
  }

 
    .sheet {
      margin: 0;
    }
    
    .inner-container {
      padding-left: 20mm;
      padding-right: 20mm;        
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
  .exempted {
    margin-left: 45%;
    font-size: 14px;
  }

     table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }

</style>
<!-- New Table -->

<div class="page-break">
<div class=" print-table">
    <div class="sheet padding-10mm">

    <div class="inner-container" >
      <table class="customer-detail " style="margin-top: 20px;margin-bottom:2px;  border-collapse: collapse; " >
        <tbody>
            <tr>
                <td colspan="12" style=" text-align: center; font-weight: bold; font-size: 14px;"  ><b>ORIGINAL INVOICE</b></td>
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
                  <b>Delivery Address,</b><br>
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
                <td colspan="12">Amount Chargable ( In Words)  <b> <?php echo ucfirst(ucwords(convert_number_to_words_full($final_total))); ?></b></td>
            </tr>

          </tbody>
        </table>
        </div>
        <!-- TAX TABLE START -->

         <?php 
        if(isset($gst_data)) {
          $total_tax=0;
          foreach( $gst_data as $g_data) {
            $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
            $gst_tot = $g_data->sale_sgst + $gst_tot;
          }
          if($gst_tot == '0'){
            echo "<div class='exempted'><span ><b>GST EXEMPTED</span></b></div>";
          }
        } 
        
      ?>
        <div class="inner-container" > 
        <table  class="customer-detail" style="margin-top: 20px;margin-bottom:2px; text-align: center;  border-collapse: collapse;font-size:10px; ">
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

        <!-- TAX TABLE END  -->
        <style type="text/css">
/*      .customer-signature, .company-signature {
        width: 85mm;
      }*/
    </style>

    
    </div>

  <div class="footer" style="margin-bottom:20px;">
          <div class="inner-container" style="margin-top: 5px;">

            <table>
              <tr>
                <td colspan="2">
                  <b><u>Declaration</u></b>
                  <div style="margin-bottom:10px">We declare that  this  invoice  shows  the  actual price of the goods described and that all particulars are true and correct</div>
                </td>
              </tr>
              <?php if($internet_check == 'internet'){ ?>
              <tr>
                <td>
                  <table border="1" style="margin-bottom:10px;border-collapse: collapse;">
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
             <!--  <tr>
                <td>
                  <div class="customer-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 10px;">
                      Customer Seal & Signature
                    </div>
                    <div style="height: 80px;"></div>
                  </div>
                </td>
                <td>
                  <div class="company-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 10px;text-align:right;float:right;">
                      For Saravana Health Store
                    </div>
                    
                  </div>
                </td>
              </tr> -->
            </table>

          </div>
      </div>
  </div>
</div>
</div>
