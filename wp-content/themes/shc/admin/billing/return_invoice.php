<?php
    $bill_data = false;
     $invoice_id['inv_id'] = '';
     $invoice_id['invoice_id'] = '';

    if(isset($_GET['id']) && $_GET['id'] != '' ) {                                               
         if(isValidInvoice($_GET['id'], 1)){

            $update = true;
            $year = $_GET['year'];
            $invoice_id['inv_id']= $_GET['id'];
            $bill_data = getBillData($invoice_id['inv_id'] , $year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $invoice_id['invoice_id']      = $bill_fdata->inv_id;

            $home_delivery = getHomedelivery($bill_fdata->home_delivery_id);
        }
        else {
             echo "<script>alert('INVOICE NOT FOUND!!! Try another number');</script>";
        }
    }

?>
<style>
.ui-widget-header{
    color:#575f6c;
}
.ui-datepicker-calendar { display: none; }

</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <form action="<?php menu_page_url('return_items'); ?>" method="GET">
                        <h2>Invoice
                            <input type="hidden" name="page" value="return_items">
                            <input type="text" name="id" class="return_inv_id" value="<?php echo $invoice_id['inv_id']; ?>"  required>
                            <input type="hidden" name="rtn_id" class="return_invoice_id_retail" value="<?php echo $bill_fdata->id; ?>">

                    Year
                    <select name="year" class="year">
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                      </select> 


                             <input type="submit" style="height: 38px;margin-left: 20px;">
                        </h2>
                    </form>
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

                        .old_user_bill, .new_user_bill,.new_customer{
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

                        .sub_delete{
                            color: #0073aa;
                            text-decoration: underline;
                        }
                         .sub_delete:hover {
                            color:#0073aa;
                            cursor: pointer; 
                            cursor: hand;
                        }
                        .add-button-return-retail {
                            margin-left: 45%;
                            margin-top: 15px;


                        }
                        .select2-container--default .select2-selection--single{
                                border-radius: 0px;
                        }


                    </style>
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">

                            
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <section class="content invoice" id="ws_billing_return">
                                <!-- title row -->
                               <!--  <div class="row">
                                    <div class="col-xs-12 invoice-header">
                                        <h1>
                                            <i class="fa fa-globe"></i> Invoice.
                                            <small class="pull-right"><?php echo date('d/m/Y'); ?></small>
                                        </h1>
                                    </div>
                                
                                </div> -->
                                <!-- info row -->
                                <div class="row invoice-info">
                                    <!-- <div class="col-sm-4 invoice-col">
                                        From
                                        <address>
                                            <strong>Saravana Health Store</strong>
                                            <br>7/12,Mg Road,Thiruvanmiyur
                                            <br>Chennai,Tamilnadu,
                                            <br>Pincode-600041.
                                            <br>Cell:9841141648.
                                        </address>
                                    </div> -->
                                    <!-- /.col -->
                                    <div class="col-sm-4 invoice-col">
                                        To,

                                            <address> 
                                                <input type="hidden" name="customer_id" value="<?php echo $bill_fdata->customer_id; ?>"/>
                                                <input type="hidden" name="inv_id" value="<?php echo $_GET['id']; ?>"/>
                                                <br><span><?php echo $bill_fdata->mobile; ?></span>
                                                <br><span class="ws_customer_name"><?php echo $bill_fdata->customer_name; ?></span>
                                                <br><span class="ws_customer_company"><?php echo $bill_fdata->company_name; ?></span>
                                                <br><span class="ws_address1"><?php echo $bill_fdata->address; ?></span>                      
                                            </address>                          
                                            
                                    </div>
                                    <!-- /.col -->
                                                                        <div class="col-sm-4 invoice-col">
                                    <div class="col-xs-12 table">
                                        Billed Items
                                        <div class="billing-repeater ws_sale_detail" style="margin-top:20px;">
                                            <table class="table table-striped" data-repeater-list="ws_sale_detail">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Product</th>
                                                        <th>HSN</th>
                                                        <th>Unit</th>
                                                        <th>Price</th>
                                                        <th>total</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody class="bill_lot_add_retail" id="bill_lot_add_retail">
                                                    <?php 
                                                    if($update) {  $i=1;
                                                        foreach($bill_ldata as $table_data) { ?>

                                                    <tr>
                                                        <td><?php echo $i; ?> </td>
                                                        <td><?php echo $table_data->product_name; ?> </td>
                                                        <td><?php echo $table_data->hsn; ?> </td>
                                                        <td><?php echo $table_data->sale_unit; ?> </td>
                                                        <td><?php echo $table_data->discount; ?> </td>
                                                        <td><?php echo $table_data->sub_total; ?> </td>
                                                    </tr>
                                                       
                                                   <?php  
                                                   $i++;
                                                        }    
                                                    }
                                                ?>
                                                </tbody>                                                    
                                            </table>
                                        </div>
                                    </div>

                                                                   
                                            
                                    </div>
                                    <div class="col-sm-4 invoice-col">
                                        <b>
                                            <input type="hidden" name="invoice_id" class="invoice_id" id="invoice_id" autocomplete="off" value="<?php echo $invoice_id['invoice_id']; ?>">
                                            <!-- <b>Invoice Id : </b> <?php echo $invoice_id['inv_id']; ?> -->
                                        </b>
                                        <br/>
                                        <br/>
                                       
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                                <div class="row">
                                    <B>Product Name :</B>                                   
                                    <select name="lot_number" class="rtn_lot_id" id="rtn_lot_id" tabindex="-1" aria-hidden="true" />
                                    </select>


                                    <input type="hidden" name="rtn_ws_product" class="rtn_ws_product" /> 
                                    <input type="hidden" name="rtn_discount_amt" class="rtn_discount_amt" /> 
                                    <input type="hidden" name="rtn_ws_unit_price" class="rtn_ws_unit_price"/>
                                    <input type="hidden" name="rtn_ws_hsn" class="rtn_ws_hsn"/>
                                    <input type="hidden" name="rtn_ws_cgst" class="rtn_cgst_percentage"/>
                                    <input type="hidden" name="rtn_ws_sgst" class="rtn_sgst_percentage"/>
                                    <input type="hidden" name="rtn_ws_stock" class="rtn_ws_slab_sys_txt"/>
                                    

                                    <span style="margin-left: 10%;">
                                        <B>Quantity:</B>
                                        <input type="text" name="qty" id="qty" class="qty" readonly/>
                                        <input type="hidden" name="qty_hidden" id="qty_hidden" class="qty_hidden" value="0"/>
                                    </span>
                                     <span style="margin-left: 10%;">
                                        <B>Return Quantity:</B>
                                        <input type="number" name="return_qty" value="0" id="return_qty" class="return_qty" min="0" onkeypress="return isNumberKey(event);"/>
                                    </span> 
                                </div>
                                <div class="row"> 
                                    <div class="">
                                        <button class="btn btn-success add-button-return-retail"  id="">ADD</button>
                                    </div>
                                </div>

                                
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
                                                            <th>Quantity</th>
                                                            <th>Return Quantity</th>
                                                            <th>MRP</th>
                                                            <th>Amount</th>
                                                            <th>CGST</th>
                                                            <th>CGST Value</th>
                                                            <th>SGST</th>
                                                            <th>SGST Value</th>
                                                            <th>Subtotal</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                      <tbody class="rtn_bill_lot_add" id="rtn_bill_lot_add">
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
                                                                    <input type="text" class="form-control ws_rtn_fsub_total" value="" readonly name="ws_rtn_fsub_total">
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
                                        <?php 
                                            if($update) {
                                        ?>
                                        <input type="hidden" name="id" class="invoice_id_new" value="<?php echo $bill_fdata->id; ?>">
                                            <button class="btn btn-success pull-right" id="return_submit"><i class="fa fa fa-edit"></i> Submit Goods Return</button>
                                        <?php
                                            } else {
                                        ?>
                                            <button class="btn btn-success pull-right ws_bill_submit" id="ws_submit_payment" style="display:none;"><i class="fa fa-credit-card"></i> Create Goods Return </button>
                                        <?php
                                            }
                                        ?>

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
