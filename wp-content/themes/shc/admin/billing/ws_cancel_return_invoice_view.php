<?php
    $bill_data = false;
    $invoice_id = '';

    if(isset($_GET['id']) && $_GET['id'] != '' ) {                                               



            $update = true;
            $invoice_id = $_GET['id'];
            $bill_data = getCancelBillDataReturnws($invoice_id);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];

    }

?>

<script>
function print_current_page()
{
// window.print();
var printPage = window.open(document.URL, '_blank');
setTimeout(printPage.print(), 5);
}
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12 print-hide">
            <div class="x_panel">
                <div class="x_title">
                    <div class="x_title">
                        <a class="btn btn-default pull-right ws_bill_return_print" href="#" target="_blank" onclick="print_current_page();"><i class="fa fa-print"></i> Print</a>
                        <div class="clearfix"></div>
                    </div>
                <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <?php
                        if($bill_data) {
                    ?>
                     <style>
                        .old_user_bill,.new_customer {
                            display: none;
                            font-size: 16px;
                        }

                        .old_user_bill, .new_user_bill,.new_customer {
                            cursor: pointer;
                            font-size: 16px;
                        }
                        .tooltip {
                            position: relative;
                            display: inline-block;
                            border-bottom: 1px dotted black;
                        }

                        .tooltip .tooltiptext {
                            visibility: hidden;
                            width: 240px;
                            background-color: black;
                            color: #fff;
                            text-align: center;
                            border-radius: 6px;
                            padding: 5px 0;

                            /* Position the tooltip */
                            position: absolute;
                            z-index: 1;
                        }
                        .stock_system{
                            position: relative;
                        }
                        .tooltip:hover .tooltiptext {
                            visibility: visible;
                        }
                        .weight-original-block {
                            position: relative;
                        }
                        .weight_cal_tooltip {
                            width: 30px;
                            position: absolute;
                            right: 0
                        }

                        .sub_delete {
                            color: #0073aa;
                            text-decoration: underline;
                        }
                         .sub_delete:hover {
                            color:#0073aa;
                            cursor: pointer; 
                            cursor: hand;
                        }
                        .add-button-return {
                            margin-left: 45%;
                            margin-top: 15px;


                        }
                        .select2-container--default .select2-selection--single {
                                border-radius: 0px;
                        }

                    </style>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">

                            <h2>Invoice Design</h2>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <section class="content invoice" id="ws_billing_return">
                                <!-- title row -->
                                <div class="row">
                                    <div class="col-xs-12 invoice-header">
                                        <h4>
                                            <i class="fa fa-globe"></i> Goods Return.
                                            <small class="pull-right"><?php echo date('d/m/Y'); ?></small>
                                        </h4>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- info row -->
                                <div class="row invoice-info">
                                    <div class="col-sm-4 invoice-col">
                                        From
                                        <address>
                                            <strong>Saravana Health Store</strong>
                                            <br>7/12,Mg Road,Thiruvanmiyur
                                            <br>Chennai,Tamilnadu,
                                            <br>Pincode-600041.
                                            <br>Cell:9841141648.
                                        </address>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        To,

                                            <address> 
                                                <input type="hidden" name="customer_id" value="<?php echo $bill_fdata->customer_id; ?>"/>
                                                <input type="hidden" name="gr_id" value="<?php echo $bill_fdata->id; ?>" class="gr_id"/>
                                                <br><span><?php echo $bill_fdata->mobile; ?></span>
                                                <br><span class="ws_customer_name"><?php echo $bill_fdata->customer_name; ?></span>
                                                <br><span class="ws_customer_company"><?php echo $bill_fdata->company_name; ?></span>
                                                <br><span class="ws_address1"><?php echo $bill_fdata->address; ?></span>                      
                                            </address>                          
                                            
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        <b>
                                            <input type="hidden" name="invoice_id" class="invoice_id" id="invoice_id" autocomplete="off" value="<?php echo $invoice_id ?>">
                                            <b>Invoice Id : </b> <?php echo  $bill_fdata->inv_id; ?><br/>
                                            <b>Return Id : </b> <?php echo $bill_fdata->return_id; ?>
                                        </b>
                                        <br>
                                        <br>
                                       
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                                
                                <!-- Table row -->
                                <div class="row">
                                    <div class="col-xs-12 table">
                                            <h2>Return Items</h2>
                                            <div class="billing-repeater rtn_ws_sale_detail" style="margin-top:20px;">
                                                <table class="table table-striped" data-repeater-list="rtn_ws_sale_detail">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Product Name</th>
                                                            <th>HSN Code</th>
                                                            <th>Return Quantity</th>
                                                            <th>MRP</th>
                                                            <th>Amount</th>
                                                            <th>CGST (%) </th>
                                                            <th>CGST Value</th>
                                                            <th>SGST (%)</th>
                                                            <th>SGST Value</th>
                                                            <th>Subtotal</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody class="rtn_bill_lot_add" id="rtn_bill_lot_add">
                                                       <?php
                                                        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                                                            $i = 1;
                                                            foreach ($bill_ldata as $d_value) {
                                                                echo '<tr><td>'.$i.'</td>';
                                                                echo '<td>'.$d_value->product_name.'</td>';
                                                                echo '<td>'.$d_value->hsn.'</td>';
                                                                echo '<td>'.$d_value->return_unit.'</td>';
                                                                echo '<td>'.$d_value->mrp.'</td>';
                                                                echo '<td>'.$d_value->amt.'</td>';
                                                                echo '<td>'.$d_value->cgst.'</td>';
                                                                echo '<td>'.$d_value->cgst_value.'</td>';
                                                                echo '<td>'.$d_value->sgst.'</td>';
                                                                echo '<td>'.$d_value->sgst_value.'</td>';
                                                                echo '<td>'.$d_value->sub_total.'</td></tr>';
                                                        }
                                                    }
                                                ?>
                                                    </tbody>                                                
                                                </table>
                                            </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <div class="row billing-repeater-extra">
                                    <!-- accepted payments column -->
                                    <div class="col-xs-6">

                                    </div>
                                    <!-- /.col -->
                                    <div class="col-xs-6">  
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:50%">Subtotal:</th>
                                                        <td>
                                                            <div class="form-horizontal form-label-left input_mask" style="position:relative;">
                                                                <div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
                                                                    <input type="text" class="form-control ws_rtn_fsub_total" value="<?php echo $bill_fdata->total_amount; ?>" readonly name="ws_rtn_fsub_total">
                                                                    <span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- this row will not appear when printing -->
                                <div class="row no-print">
                                    <div class="col-xs-12">
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

                      <?php
                        }
                      ?>

                </div>
            </div>
        </div>
    </div>
</div>





















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
    padding: 8px 10px 5px 10px;
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
                            <div class="invoice-no">RETURN NO - <?php echo  'GR'.$_GET['id']; ?></div>
                          </div>
                          <div class="company-address-txt">
                            <b>DATE - <?php echo $bill_fdata->created_at; ?></b>
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
                          <th colspan="12">
                            <div class="buyer-detail">
                              Return To,<br>
                             <div class="buyer-address">
                               <?php echo $bill_fdata->company_name; ?><br>
								<?php echo $bill_fdata->customer_name; ?><br>
								<?php echo $bill_fdata->mobile; ?><br>
                               <?php echo $bill_fdata->address; ?><br>
							  <b> GST NO<?php echo $bill_fdata->gst_number; ?></b>
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
                          <th class="center-th" style="width:35px;padding:0;line-height: 40px;" rowspan="2">
                            <div class="text-center">AMOUNT</div>
                          </th>
                          <th class="center-th" style="padding: 0;" colspan="2">
                            <div class="text-center">CGST</div>
                          </th>
                          <th class="center-th" style="padding: 0;" colspan="2">
                            <div class="text-center">SGST</div>
                          </th>
                          <th class="center-th" style="padding: 0;width: 100px;line-height: 15px;" rowspan="2">
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
                              <?php echo $value->mrp; ?>
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
                          </tr>
                          <tr>
                            <td colspan="10"><div class="text-center">Total (Hire Charges)</div></td>
                            <td>
                              <div class="text-rigth">
                                <?php echo $final_total = $bill_fdata->total_amount; ?>
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
                                <?php echo $final_total = $bill_fdata->total_amount; ?>
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
        <b>Rs <?php echo ucwords(convert_number_to_words_full($bill_fdata->total_amount)); ?></b>
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
                  <div style="margin-bottom:20px;">We declare that  this  return invoice  shows  the  actual price of the goods described and that all particulars are true and correct</div>
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