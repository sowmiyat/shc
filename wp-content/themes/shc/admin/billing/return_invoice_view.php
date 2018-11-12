<?php
    $bill_data = false;
    $invoice_id = '';

    if(isset($_GET['id']) && $_GET['id'] != '' ) {                                               

        if(isValidInvoiceReturn($_GET['id'])) {

            $update = true;
            $invoice_id = $_GET['id'];
            $bill_data = getBillDataReturn($invoice_id);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];

        }
    }
$profile = get_profile1();
?>
<script>
function print_current_page()
{
var printPage = window.open(document.URL, '_blank');
setTimeout(printPage.print(), 5);
}
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12 print-hide">
            <div class="x_panel">
                <div class="">
                    
                    <div class="clearfix"></div>
                </div>
                <div class="">
                    <div class="">
                        <form action="<?php menu_page_url( 'return_items_view' ); ?>" method="GET">
                              <h2>
                                  <input type="hidden" name="page" value="return_items_view">
                                  <input type="hidden" name="id" class="invoice_id" value="<?php echo $_GET['id']; ?>" autocomplete="off"> 
                                           
                                 <!--  <input class="btn btn-success" type="submit" style="height: 38px;margin-left: 20px;"> -->
                              </h2>
                            <!-- <button class="btn btn-default pull-right " onclick="print_current_page();" 	 style="border-color: #bc2323;"><i class="fa fa-print"></i> Print</button> -->
                        </form>
                        <div class="clearfix"></div>
                    </div>
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
                        .no_display{
                            display: none;
                        }
                    </style>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">

                                    <h2>Goods Return Design</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">

                                    <section class="content invoice" id="ws_billing_return">
                                        <!-- title row -->
                                        <div class="row">
                                            <div class="col-xs-12 invoice-header">
                                                <h3>
                                                    <i class="fa fa-globe"></i>Goods Return
                                                    <small class="pull-right"><?php echo date('d/m/Y'); ?></small>
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
                                                    <b>Invoice Id : </b> <?php echo $bill_fdata->search_inv_id; ?><br/>
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
                                                    <h2>Billed Items</h2>
                                                    <div class="billing-repeater rtn_ws_sale_detail" style="margin-top:20px;">
                                                        <table class="table table-striped" data-repeater-list="rtn_ws_sale_detail">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2" style="text-align: center;">S.No/Reason</th>
                                                                    <th rowspan="2" style="text-align: center;">Product Name</th>
                                                                    <th rowspan="2" style="text-align: center;">HSN Code</th>
                                                                    <th rowspan="2" style="text-align: center;">Return Quantity</th>
                                                                    <th rowspan="2" style="text-align: center;">Sold Price</th>
                                                                    <th rowspan="2" style="text-align: center;">Taxless Amount</th>
                                                                    <th colspan="2" class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?>" style="text-align: center;">CGST</th>
                                                                    <th colspan="2" class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?>" style="text-align: center;">SGST</th>
                                                                    <th colspan="2" class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'cgst')) ? 'no_display' : ''; ?>" style="text-align: center;">IGST</th>
                                                                    <th colspan="2" style="text-align: center;">CESS</th>
                                                                    <th rowspan="2" style="text-align: center;">Subtotal</th>  
                                                                </tr>
                                                                 <tr class="text_bold text_center">
                                                                    <th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?>" style="border-top: none;text-align: center;" class="column-title" >Rate(%)</th>
                                                                    <th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?>" style="border-top: none;text-align: center;" class="column-title" >Amount</th>
                                                                    <th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?>" style="border-top: none;text-align: center;" class="column-title" >Rate(%)</th>
                                                                    <th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?>" style="border-top: none;text-align: center;" class="column-title" >Amount</th>
                                                                    <th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'cgst')) ? 'no_display' : ''; ?>" style="border-top: none;text-align: center;" class="column-title" >Rate(%)</th>
                                                                    <th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'cgst')) ? 'no_display' : ''; ?>" style="border-top: none;text-align: center;" class="column-title" >Amount</th>
                                                                    <th style="border-top: none;text-align: center;" class="column-title" >Rate(%)</th>
                                                                    <th style="border-top: none;text-align: center;" class="column-title" >Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="rtn_bill_lot_add" id="rtn_bill_lot_add" style="text-align: center;">
                                                               <?php
                                                                $cgst_class = ((isset($bill_fdata)) &&($bill_fdata->gst_type == "igst"))? "no_display" :"";
                                                                $igst_class = ((isset($bill_fdata)) &&($bill_fdata->gst_type == "cgst"))? "no_display" :"";
                                                                if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                                                                    $i = 1;
                                                                    foreach ($bill_ldata as $d_value) {

                                                                        
                                                                        echo '<tr><td>'.$i.' '.$d_value->return_reason.'</td>';
                                                                        echo '<td>'.$d_value->product_name.'</td>';
                                                                        echo '<td>'.$d_value->hsn.'</td>';
                                                                        echo '<td>'.$d_value->return_unit.'</td>';
                                                                        echo '<td>'.$d_value->mrp.'</td>';
                                                                        echo '<td>'.$d_value->amt.'</td>';
                                                                        echo '<td class="'.$cgst_class.'">'.$d_value->cgst.'</td>';
                                                                        echo '<td class="'.$cgst_class.'">'.$d_value->cgst_value.'</td>';
                                                                        echo '<td class="'.$cgst_class.'">'.$d_value->sgst.'</td>';
                                                                        echo '<td class="'.$cgst_class.'">'.$d_value->sgst_value.'</td>';
                                                                        echo '<td class="'.$igst_class.'">'.$d_value->igst.'</td>';
                                                                        echo '<td class="'.$igst_class.'">'.$d_value->igst_value.'</td>';
                                                                        echo '<td>5.00</td>';
                                                                        echo '<td>'.$d_value->cess_value.'</td>';
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
                                                                            <input type="text" class="form-control ws_rtn_fsub_total" value="<?php echo $bill_fdata->total_amount; ?>" readonly tabindex="-1" name="ws_rtn_fsub_total">
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
                    <div class="pull-right">
                        <button class="btn btn-primary  return_generate_bill" style="margin-right: 5px;"><i class="fa fa-file-pdf-o"></i> Generate PDF</button>
                        <a class="btn btn-default bill_return_print" href="javascript:void(0)" target="_blank" onclick="print_current_page();"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">   
    jQuery('.bill_return_print').focus()   
    jQuery(".bill_return_print").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 
           if(event.shiftKey && event.keyCode == 9) {  
             e.preventDefault(); 
            jQuery('.return_generate_bill').focus();
          }
          else if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.invoice_id').focus();
          } 
          else {
            jQuery('.bill_return_print').focus();
          }

    });       
     jQuery(".invoice_id").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 
        if(event.shiftKey && event.keyCode == 9){           
            e.preventDefault(); 
            jQuery('.bill_return_print').focus();
        }
        else if (keyCode == 9) {            
            e.preventDefault(); 
            jQuery('.btn-success').focus();
        } else {
           jQuery(".invoice_id").focus();  
        }
    });
</script>































<style>


    
   @media screen {
    .A4_HALF .footer {
      bottom: 0px;
      left: 0px;
    }
    .A4_HALF{
    display: none;
  }
    .A4_HALF .footer .foot {
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
        font-family: normal;
      font-size: 13px;
      
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




    .A4_HALF .footer {
      position: fixed;
      bottom: 0px;
      left: 0px;
    }
    .A4_HALF .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    }
  }

  @page { margin: 0;padding: 0; }
  .sheet {
    margin: 0;
    
  }


      .A4_HALF {
        width: 100mm;
      }
      .inner-container {
        padding-left: 20mm;
        padding-right: 20mm;
        width: 100mm;

      }

      .left-float {
        float: left;
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

      .A4_HALF h3 {
        margin-top: 10px;
      }
  .exempted span{
        margin-left: 40%;
        font-size: 14px;
      }
      
</style>   
</head>

<body>

 <div class="A4_HALF">
  <div class="sheet padding-10mm">
    <?php
      if($bill_data) {
    ?>
    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr class="text-center" >
        <td valign='top' WIDTH='50%'>
            <strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
            <span style="line-height:12px; " ><br/><?php echo $profile ? $profile->address : '';  ?>
            <br/><?php echo $profile ? $profile->address2 : '';  ?>    
            <br/>PH : <?php echo $profile ? $profile->phone_number : '';  ?>
            <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?></span>
        </td>
      </tr>
    </table>

    <table cellspacing='' cellpadding='' WIDTH='100%' >
        <tr>
          <td>
            <table>
              <tr>            
                <td valign='top' WIDTH='50%'>Date :<?php echo date("d/m/Y"); ?> </td>     
              </tr>
              <tr> 
                <td valign='top' WIDTH='50%'>State :TAMILNADU </td>    
              </tr> 
              <tr> 
                <td valign='top' WIDTH='50%'>State Code :33 </td>   
              </tr>     
          </table>
        </td>
          <td>
            <table>
              <tr> 
                 <td valign='top' WIDTH='50%'>Name : <?php echo $bill_fdata->customer_name; ?></td> 
              </tr>              
              <tr>
                <td valign='top' WIDTH='50%'>Mobile :<?php echo $bill_fdata->mobile; ?> </td>  
              </tr>
              <tr>   
                <td valign='top' WIDTH='50%'>Address :<?php echo $bill_fdata->address; ?></td>       
              </tr>
          </table>
        </td>
      </tr>
      
    </table>
    <div class="text-center" ><b style="font-size:14px;">RETURN BILL</b></div>
    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
        <td valign='top' WIDTH='70%'>Return No :  <?php echo $bill_fdata->return_id; ?> </td>    
        <td valign='top' WIDTH='100%'>Invoice No : <b><?php echo $bill_fdata->search_inv_id; ?></b></td>
      </tr>
      <tr>
        <!-- <td valign='top' WIDTH='30%'>Date : <?php echo date("d/m/Y"); ?></td> -->
        <td valign='top' WIDTH='30%'> </td>
      </tr>
    </table>
    <style>
      .dotted_border_top  {
        border-top: 1px dashed #000;        

      }
      .dotted_border_bottom  {        
        border-bottom: 1px dashed #000;
      }
    </style>

    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" >
      <tr>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>SNO</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>PRODUCT</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>HSN <br/> CODE</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>RETURN <br/>QTY</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>SOLD <br/>PRICE</th>
       <!--  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>Dis.Price</th> -->
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>TOTAL</th>
      </tr>
      <tr>

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
      <td valign='top' align='left'><?php echo $d_value->return_unit; ?></td>
      <td valign='top' align='left'><?php echo $d_value->mrp; ?></td>
     <!--  <td valign='top' align='left'><?php echo $d_value->discount; ?></td> -->
      <td valign='top' align='right'><?php echo$d_value->sub_total; ?>&nbsp;&nbsp;&nbsp;</td></tr>

      <?php
        $i++;
          }
        } 
        ?> 

     
    </table>


      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
      <!-- <tr>
        <td valign='top' colspan="3" >No.Of.Items : 4    </td>
        <td valign='top'  align='right' >Total Qty : 10&nbsp;&nbsp;&nbsp;</td>
      </tr> -->
    
      <tr> 
         <td class="dotted_border_top dotted_border_bottom" colspan="6" valign='top' align='center'><b>TOTAL AMOUNT</b></td>
         <td  class="dotted_border_top dotted_border_bottom" valign='top' align='right'><span class="amount"><?php echo '<b>'.$bill_fdata->total_amount.'</b>'; ?>&nbsp;&nbsp;&nbsp;</span></td>
      </tr>
    </table>

          <?php
            }
          ?>
      <br/>

      Amount Chargable ( In Words)
      <?php echo ucwords(convert_number_to_words_full($bill_fdata->total_amount)); ?> 
    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style="" >
      <thead>
        <tr>
          <th colspan="5" class="dotted_border_bottom"  align="center" >GST Details</th>
        </tr>     
      <tr>
        <th valign='top' class="center-th" style="width:90px;padding:0;" rowspan="2">
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

    <?php
        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
          $i = 1;
          $gst_tot= 0;
          $total_tax = 0.00;
          foreach ($bill_ldata as $d_value) {
      ?>
         
          <tr class="">
            <td class=""><div class="text-center"><?php  echo $d_value->amt; ?></div></td>
            <td class=""><div class="text-center"><?php echo $d_value->cgst + 0; echo ' %'; ?></div></td>
            <td class=""><div class="text-center"><?php echo $d_value->cgst_value; ?></div></td>
            <td class=""><div class="text-center"><?php echo $d_value->sgst + 0; echo ' %';  ?> </div></td>
            <td class=""><div class="text-center"><?php echo $d_value->sgst_value; ?></div></td>
          </tr>
           <?php 
           $total_tax   = ( 2 * $d_value->cgst_value) + $total_tax;
           $gst_tot     = $d_value->cgst_value + $gst_tot;
        }
      } ?>
      <tr class="">
        <td class=""><div class="text-center"></div></td>
        <td class=""><div class="text-center"></div></td>
        <td class=""><div class="text-center"><?php echo $gst_tot; ?></div></td>
        <td class=""><div class="text-center"></div></td>
        <td class=""><div class="text-center"><?php echo $gst_tot; ?></div></td>
      </tr>
      <tr>
        <td  class="dotted_border_bottom" colspan="4">
        <div class="text-center">
          <b>Total Tax</b>
        </div>
        </td>
        <td class="dotted_border_bottom" >
        <div class="text-right">
         <b><?php echo $total_tax; ?></b>
        </div>
        </td>
      </tr>
      </tbody>
    </table>
    <div style="text-align: center;" >Thank You !!!. Visit Again !!!.</div> 
  </div>
    <div style="margin-top:20px;">
       <b style="float:right;">Authorised Signatory</b>
    </div>
</div>
</body>
</html>