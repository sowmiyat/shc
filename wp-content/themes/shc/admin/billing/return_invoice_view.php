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

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    
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
                                        <h1>
                                            <i class="fa fa-globe"></i> Invoice.
                                            <small class="pull-right"><?php echo date('d/m/Y'); ?></small>
                                        </h1>
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
                                            <b>Invoice Id : </b> <?php echo $invoice_id; ?><br/>
                                            <b>Return Id : </b> <?php echo 'GR'.$bill_fdata->id; ?>
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
                                        <button class="btn btn-default pull-right return_print" style="border-color: #bc2323;"><i class="fa fa-print"></i> Print</button>
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

<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));
}
</script>
