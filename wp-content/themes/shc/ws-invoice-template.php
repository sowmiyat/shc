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
  @media screen {
    .A4 {
      display: none;
    }

    .A4 .footer {
      bottom: 0px;
      left: 0px;
    }
    .A4 .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
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




    .A4 .footer {
      position: fixed;
      bottom: 0px;
      left: 0px;
    }
    .A4 .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    }
  }

  @page { margin: 0;padding: 0; }
  .sheet {
    margin: 0;
  }


      .A4 {
        width: 210mm;
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
      .A4 h3 {
        margin-top: 0px;
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


</style>
  <div class="A4">
    <div class="sheet padding-10mm">
      <table> 
        <thead>
          <tr>
            <td>
              <div class="customer-detail inner-container" style="margin-top: 20px;margin-bottom:2px;">
                  <table>
                    <tr>
                      <td>
                        <div class="company-logo">
                          <img style="width:165px" src="<?php echo get_template_directory_uri().'/admin/billing/inc/images/tax.png'; ?>">
                        </div>
                      </td>
                      <td>
                        <div class="company-address company-detail">
                          <div class="company-name">
                            <h3>SARAVANA HEALTH STORE</h3>
                          </div>
                          <div class="company-address-txt">
                            No-12/7, MG Road,
                          </div>
                          <div class="company-address-txt">
                            Thiruvanmiyur,
                          </div>
                          <div class="company-address-txt">
                            Chennai - 600041
                          </div>
                          <div class="company-address-txt">
                            <b>GST No - 33BMDPA4840E1ZP</b>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="invoice-detail company-detail">
                          <div class="company-address-txt">
                            <div class="invoice-no">INVOICE NO - <?php echo $_GET['id']; ?></div>
                          </div>
                          <div class="company-address-txt">
                            <b>DATE - 13/10/2017</b>
                          </div>

                          <div class="company-address-txt">
                            <b>STATE : TAMILNADU</b>
                          </div>
                          <div class="company-address-txt">
                            <b>STATE CODE : 33</b>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </table>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="customer-detail inner-container" style="margin-top: 20px;margin-bottom:2px;">
                
              </div>
            </td>
          </tr>
        </thead>
        <tbody>

      <?php
        $pages = false;
        $per_page = 12;
        $pieces = false;
        $tota_row = 0;

        if($bill_data) {
          $pages = ceil(count($bill_ldata)/$per_page);
          $pieces = array_chunk($bill_ldata, $per_page);
          $tota_row = count($bill_ldata);
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
                    <table class="table table-bordered sale-table-invoice" style="margin-bottom: 2px;">
                      <thead>
                        <tr>
                          <th colspan="7">
                            <div class="buyer-detail">
                              BUYER,<br>
                              <div class="buyer-address">
                               <?php echo $bill_fdata->company_name; ?><br>
								<?php echo $bill_fdata->customer_name; ?><br>
								<?php echo $bill_fdata->mobile; ?><br>
                               <?php echo $bill_fdata->address; ?><br>
							  <b> GST NO<?php echo $bill_fdata->gst_number; ?></b>
                              </div>
                            </div>
                          </th>
                          <th colspan="5">
                            <div class="delivery-detail">
                              DELIVERY ADDRESS,<br>
                              <div class="delivery-address">
                                <?php echo $bill_fdata->home_delivery_name; ?><br>
								<?php echo $bill_fdata->home_delivery_mobile; ?><br>
								<?php echo $bill_fdata->home_delivery_address; ?><br>
                              </div>
                            </div>
                          </th>
                        </tr>
                        <tr class="header-txt">
                          <th style="width:25px;padding:0;line-height: 40px;" class="center-th" rowspan="2">
                            <div class="text-center">S.No</div>
                          </th>
                          <th class="center-th" style="width:50px;line-height: 15px;" rowspan="2">
                            <div class="text-center">HSN<br>CODE</div>
                          </th>
                          <th class="center-th" style="line-height: 40px;" rowspan="2">
                            <div class="text-center">PRODUCTS</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 40px;" rowspan="2">
                            <div class="text-center">QTY</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 13px;" rowspan="2">
                            <div class="text-center">MRP<br>Per Piece</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 15px;" rowspan="2">
                            <div class="text-center">DISCOUNTED<br>Price</div>
                          </th>
                          <th class="center-th" style="width:35px;padding:0;line-height: 40px;" rowspan="2">
                            <div class="text-center">AMOUNT</div>
                          </th>
                          <th class="center-th" style="padding: 0;" colspan="2">
                            <div class="text-center">CGST</div>
                          </th>
                          <th class="center-th" style="padding: 0;" colspan="2">
                            <div class="text-center">SGST</div>
                          </th>
                          <th class="center-th" style="padding: 0;width: 80px;line-height: 15px;" rowspan="2">
                            <div class="text-center">TOTAL<br>AMOUNT</div>
                          </th>
                        </tr>
                        <tr class="header-txt">
                          <th style="padding: 0;width: 35px;"><div class="text-center">RATE</div></th>
                          <th style="padding: 0;width: 50px;"><div class="text-center">AMOUNT</div></th>
                          <th style="padding: 0;width: 35px;"><div class="text-center">RATE</div></th>
                          <th style="padding: 0;width: 50px;"><div class="text-center">AMOUNT</div></th>
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
                            <div class="text-center">
                              <?php echo $value->hsn; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-left">
                              <?php echo $value->product_name; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-center">
                              <?php echo $value->sale_unit; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->unit_price; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->discount;?>
                            </div>
                          </td>
                          <td>
                              <?php echo $value->amt; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-center" style="text-align: right;">
                              <?php echo $value->cgst; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->cgst_value; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-center">
                              <?php echo $value->sgst; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->sgst_value; ?>
                            </div>
                          </td>
                          <td>
                            <div class="text-rigth">
                              <?php echo $value->sub_total; ?>
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          
                          <tr>
                            <td colspan="11"><div class="text-center">Discount (Hire Charges)</div></td>
                            <td>
                              <div class="text-rigth">
                                <?php echo $bill_fdata->discount; ?>
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td colspan="11"><div class="text-center">Total (Hire Charges)</div></td>
                            <td>
                              <div class="text-rigth">
                                <?php echo $final_total = $bill_fdata->sub_total;?>

                              </div>
                            </td>
                          </tr>
                          
                      <?php
                        } else {
                      ?>
                          <tr>
                            <td colspan="11">
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

      <div class="inner-container" style="margin-top: 0px;">
        <div>Amount Chargable (in words)</div>
        <b>Rs <?php echo convert_number_to_words_full($final_total); ?></b>


        <table class="table table-bordered" style="margin-top:10px;margin-bottom: 5px;width: 120mm;">
          <thead>
            <tr>
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


            <?php  if(isset($gst_data)) { 
                      $total_tax=0;
                            foreach( $gst_data as $g_data) {

                     ?>
                    <tr class="">
                        <td class=""><div class="text-right">Rs. <?php  echo $g_data->sale_amt; ?></div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->cgst; ?> % </div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->sale_cgst; ?></div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->cgst; ?> % </div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->sale_sgst; ?></div></td>
                    </tr>
                      <?php $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
                            }
                          } ?>
                  <td colspan="4">
                    <div class="text-center">
                      Total Tax
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                     <?php echo $total_tax; ?>
                    </div>
                  </td>
                </tr>
          </tbody>
        </table>
        <table>
          <div>Tax Amount (in words)</div>
          <b>Rupees <?php echo convert_number_to_words_full($total_tax); ?> </b>
        </table>
      </div>


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
                  <div style="margin-bottom:20px;">We declare that  this  invoice  shows  the  actual price of the goods described and that all particulars are true and correct</div>
                </td>
              </tr>
              <tr>
                <td>
                  <div class="customer-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 16px;">
                      For Saravana Health Store
                    </div>
                    <div style="height: 80px;"></div>
                  </div>
                </td>
                <td>
                  <div class="company-signature">
                    <div class="company-name" style="font-family: serif;font-weight: bold;font-size: 16px;text-align:right;">
                      Customer Seal & Signature
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
<body>
  </html>