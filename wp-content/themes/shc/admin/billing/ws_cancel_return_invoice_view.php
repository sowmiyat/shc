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
$profile = get_profile1();
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
                        <a class="btn btn-default pull-right ws_bill_return_print" href="javascript:void(0)" target="_blank" onclick="print_current_page();"><i class="fa fa-print"></i> Print</a>

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

@page{
  margin: 20px;
}
.A4 {font-family: arial, Arial, Helvetica, sans-serif; font-size: 12px;margin-left: 20px;margin-right: 30px;border:1px solid #73879c;}
.A4{
        width: 210mm;
        height: 297mm;

}
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

<div class="A4 print_padding">
  

        <?php
            if($bill_data) {
        ?>
  <div class="title"><div style="margin-left: 40%;margin-bottom: 10px;margin-top: 10px;"><b>GOODS RETURN CHALLAN</b><h1><b style="color:red;">CANCELLED</b></h1></div></div>

  <div class="body_style">
      <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
          <td valign='top' WIDTH='50%'>
            <strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
                <br/><?php echo $profile ? $profile->address : '';  ?>
                <br/><?php echo $profile ? $profile->address2 : '';  ?>
                <br/>Cell : <?php echo $profile ? $profile->phone_number : '';  ?>
                <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?>
          <td valign='top' WIDTH='50%'>
              <table>
                <tr><td>Return Number</td><td>: <?php echo $bill_fdata->return_id; ?></td></tr>
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
              <tr><td><b>Return to , </b></td><td><b></b></td></tr>
              <tr><td style="width: 100px;">Name</td><td>: <?php echo $bill_fdata->customer_name; ?></td></tr>
              <tr><td>Company</td><td>: <?php echo $bill_fdata->company_name; ?></td></tr>
              <tr><td>Mobile</td><td>: <?php echo $bill_fdata->mobile; ?></td></tr>
              <tr><td>Address</td><td>: <?php echo $bill_fdata->address; ?></td></tr>
              <tr><td>GST Number</td><td>: <?php echo $bill_fdata->gst_number; ?></td></tr>
            </table>
          </td>
          
        </tr>
        

      </table>

      <br />

      <br/>


      

      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style=" border-collapse: collapse;border: 1px solid black;">
        <tr style="border: 1px solid black;">
          <th valign='top' style="border: 1px solid black;">SNO</th>
          <th valign='top' style="border: 1px solid black;">PRODUCTS</th>
          <th valign='top' style="border: 1px solid black;">HSN </br> Code</th>
          <th valign='top' style="border: 1px solid black;">Return Quantity</th>
          <th valign='top' style="border: 1px solid black;">MRP(Per Item)</th> 
          <th valign='top' style="border: 1px solid black;">AMOUNT</th>
          <th valign='top' style="border: 1px solid black;">CGST (%) </th>
          <th valign='top' style="border: 1px solid black;">CGST VALUE</th>
          <th valign='top' style="border: 1px solid black;">SGST (%) </th>
          <th valign='top' style="border: 1px solid black;">SGST VALUE</th>
          <th valign='top' style="border: 1px solid black;">SUB TOTAL</th>
        </tr>
      <?php
          if($bill_data && $bill_ldata && count($bill_ldata)>0) {
              $i = 1;
              foreach ($bill_ldata as $d_value) {
      ?>
                            
        <tr style="border: 1px solid black;">
          <td valign='top' style="border: 1px solid black;" align='center'><?php echo $i; ?></td>
          <td valign='top' style="border: 1px solid black;"><?php echo $d_value->product_name; ?></td>
          <td valign='top' style="border: 1px solid black;"><?php echo $d_value->hsn; ?></td>
          <td valign='top' style="border: 1px solid black;" align='left'><?php echo $d_value->sale_unit; ?></td>
          <td valign='top' style="border: 1px solid black;" align='left'><?php echo $d_value->mrp; ?></td>
          <td valign='top' style="border: 1px solid black;" align='left'><?php echo $d_value->amt; ?></td>
          <td valign='top' style="border: 1px solid black;"><?php echo $d_value->cgst + 0; echo ' %'; ?></td>
          <td valign='top' style="border: 1px solid black;"><?php echo $d_value->cgst_value; ?></td>
          <td valign='top' style="border: 1px solid black;"><?php echo $d_value->sgst + 0; echo ' %';  ?></td>
          <td valign='top' style="border: 1px solid black;"><?php echo $d_value->sgst_value; ?></td>
          <td valign='top' style="border: 1px solid black;" style="padding:3px;" align='left'><?php echo $d_value->sub_total; ?></td>
        </tr>
      <?php
            $i++;
              }
            } 
          ?>  
      </table>
      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
      <tr>
        <td valign='top' align='right'>Total:</td>
          <td valign='top' align='left' style="width:62px;"><span class="amount" style="line-height: 0px;"><?php echo $bill_fdata->total_amount; ?></span></td>
      </tr>
      
      </table>

      Amount Chargable ( In Words)<br/>
      <?php echo ucwords(convert_number_to_words_full($bill_fdata->total_amount)); ?>
 
       <br/>
        <?php
                  }
                ?>
        
    </div>

    <div>
       <b style="float:right;">Authorised Signatory</b>
    </div>

</div>