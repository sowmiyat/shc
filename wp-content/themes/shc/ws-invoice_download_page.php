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
             $bill_id = $bill_fdata->id;
            $gst_slab = gst_group($bill_id);
            $gst_data = $gst_slab['gst_data'];
    }


 
?>

  <link rel='stylesheet' id='bootstrap-min-css'  href=<?php echo get_template_directory_uri(); ?>'/admin/inc/css/bootstrap.min.css' type='text/css' media='all' />


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
          <table>
            <thead>
                <tr>
                <th colspan="">
                  <div class="">
                      BUYER,<br>
                      <div class="">
                       <?php echo $bill_fdata->company_name; ?><br>
        <?php echo $bill_fdata->customer_name; ?><br>
        <?php echo $bill_fdata->mobile; ?><br>
                       <?php echo $bill_fdata->address; ?><br>
        <b> GST NO<?php echo $bill_fdata->gst_number; ?></b>
                      </div>
                    </div>
                  </th>
                  <th colspan="">
                   <!--  <div class="delivery-detail">
                      DELIVERY ADDRESS,<br>
                      <div class="delivery-address">
                        <?php echo $bill_fdata->home_delivery_name; ?><br>
        <?php echo $bill_fdata->home_delivery_mobile; ?><br>
        <?php echo $bill_fdata->home_delivery_address; ?><br>
                      </div>
                    </div> -->
                  </th>
                </tr>
              </thead>
            </table>


      <?php
    if($bill_data) {
          ?>
            <tr>
              <td>
                <div class="inner-container" >
                  <div class="bill-detail">
                    <table class="table table-bordered sale-table-invoice" style="margin-bottom: 2px;">
                      <thead>
                        <!-- <tr>
                          <th colspan="6">
                            <div class="">
                              BUYER,<br>
                              <div class="">
                               <?php echo $bill_fdata->company_name; ?><br>
                <?php echo $bill_fdata->customer_name; ?><br>
                <?php echo $bill_fdata->mobile; ?><br>
                               <?php echo $bill_fdata->address; ?><br>
                <b> GST NO<?php echo $bill_fdata->gst_number; ?></b>
                              </div>
                            </div>
                          </th>
                          <th colspan="6">
                            <div class="delivery-detail">
                              DELIVERY ADDRESS,<br>
                              <div class="delivery-address">
                                <?php echo $bill_fdata->home_delivery_name; ?><br>
                <?php echo $bill_fdata->home_delivery_mobile; ?><br>
                <?php echo $bill_fdata->home_delivery_address; ?><br>
                              </div>
                            </div>
                          </th>
                        </tr> -->
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
                    $i=1;
                      foreach ($bill_ldata as $key => $value) {
                      ?>
                        <tr>
                          <td>
                            <div class="text-center">
                              <?php echo $i; ?>
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
                        $i++;
                      }
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
                            <td colspan="11"><div class="text-center" style="text-align:center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              Discount (Hire Charges)</div></td>
                            <td>
                              <div class="text-rigth">
                                <?php echo $bill_fdata->discount; ?>
                              </div>
                            </td>
                          </tr>

                          <tr>
                            <td colspan="11"><div class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              Total (Hire Charges)</div></td>
                            <td>
                              <div class="text-rigth">
                                <?php echo $final_total = $bill_fdata->sub_total;?>

                              </div>
                            </td>
                          </tr>
                      
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
              <br/>
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
                    <br/>
                    <br/>
                    <div style="margin-top: 60px;text-align:right;">Authorised Signatory</div>
                  </div>
                </td>
              </tr>
            </table>

          </div>
      </div>


    </div>
  </div>