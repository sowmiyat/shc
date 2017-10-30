<?php


    $bill_data = false;
     $invoice_id['inv_id'] = '';
     $invoice_id['invoice_id'] = '';

    if(isset($_GET['id']) && $_GET['id'] != '' ) {   
     if(isset($_GET['return_id']) && $_GET['return_id'] != '' ) {

            $update                        = true;
            $year                           = $_GET['year'];
            $invoice_id['inv_id']           = $_GET['id'];
            $bill_data                      = getBillDataReturnDataWs($_GET['id'] , $year,$_GET['return_id']);
            $bill_fdata                     = $bill_data['bill_data'];
            $bill_ldata                     = $bill_data['return_ordered_data'];
            $bill_rdata                     = $bill_data['return_data'];
            $invoice_id['invoice_id']       = $bill_fdata->inv_id;

    } else {                                            
         if(isValidInvoice($_GET['id'], 1)) {

            $display                        = true;
            $year                           = $_GET['year'];
            $invoice_id['inv_id']           = $_GET['id'];
            $bill_data                      = getBillDataReturnDataWs($invoice_id['inv_id'] , $year);
            $bill_fdata                     = $bill_data['bill_data'];
            $bill_ldata                     = $bill_data['ordered_data'];
            $bill_rdata                     = $bill_data['return_data'];
            $invoice_id['invoice_id']       = $bill_fdata->inv_id;

        }
        else {
             echo "<script>alert('INVOICE NOT FOUND!!! Try another number');</script>";
        }
    }
}
   

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <form action="<?php menu_page_url('ws_return_items'); ?>" method="GET">
                        <h2>Invoice
                            <input type="hidden" name="page" value="ws_return_items">
                            <input type="text" name="id" class="ws_return_inv_id" value="<?php echo $invoice_id['inv_id']; ?>" autocomplete="off" >
                            <!-- <input type="hidden" name="rtn_id" class="return_invoice_id_retail" value="<?php echo $bill_fdata->id; ?>"> -->

                    Year
                        <select name="year" class="year">
                            <?php   $current_year = date('Y');
                                    $display_year = $current_year - 30;
                                    $added_year = 0;
                                    for($i=0;$i<60;$i++) {
                                        echo $years = $display_year + $added_year;
                                        if($years == $current_year ){ $selected = 'selected'; } else {
                                           $selected = ''; 
                                        }
                                        echo '<option value="'.$years.'"'.$selected.'>'.$years.'</option>';
                                        $added_year++;
                                    } 
                            ?>
                        </select>
                            <input type="submit" class="btn btn-success ws_return_bill_submit" style="height: 38px;margin-left: 20px;">
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
                                <div class="row invoice-info">
                                    <div class="col-sm-4 invoice-col">
                                        To,

                                            <address> 
                                                <input type="hidden" name="customer_id" value="<?php echo $bill_fdata->customer_id; ?>"/>
                                                <input type="hidden" name="inv_id" value="<?php echo $_GET['id']; ?>"/>
                                                <br><span><?php echo $bill_fdata->mobile; ?></span>
                                                <br><span class="ws_customer_name"><?php echo $bill_fdata->customer_name; ?></span>
                                                <br><span class="ws_address1"><?php echo $bill_fdata->address; ?></span>                      
                                            </address>                          
                                            
                                    </div>
                                    <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <div class="col-xs-12 table">
                                        <?php if($bill_rdata) {  ?>
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
                                                    if($display || $update) {  $i=1;
                                                        foreach($bill_rdata as $table_data) { ?>

                                                    <tr>
                                                        <td><?php echo $i; ?> </td>
                                                        <td><?php echo $table_data->product_name; ?> </td>
                                                        <td><?php echo $table_data->hsn; ?> </td>
                                                        <td><?php echo $table_data->return_unit; ?> </td>
                                                        <td><?php echo $table_data->mrp; ?> </td>
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
                                        <?php } ?>
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
                                                            <th>Balance Qty</th>
                                                            <th>Return Qty</th>
                                                            <th>MRP</th>
                                                            <th>Amount</th>
                                                            <th>CGST</th>
                                                            <th>CGST Value</th>
                                                            <th>SGST</th>
                                                            <th>SGST Value</th>
                                                            <th>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                      <tbody >
                                                         <?php 
                                                    if($display || $update) {  $i=1;
                                                        foreach($bill_ldata as $table_data) { ?>

                                                    
                                                     <tr class="rtn_bill_lot_add" name="rtn_customer_table" data-productid="<?php echo $table_data->lot_id; ?>">
                                                        <td><?php echo $i; ?> </td><input type="hidden" name="customer_detail[<?php echo $i; ?>][id]" value="<?php echo $table_data->lot_id; ?>"  />
                                                        <td><?php echo $table_data->product_name; ?> </td>
                                                        <td><?php echo $table_data->hsn; ?> </td>
                                                        <td><?php echo $table_data->sale_unit; ?> </td><input type="hidden" name="customer_detail[<?php echo $i; ?>][sale_qty]" id="sale_qty" class="sale_qty" value="<?php echo $table_data->sale_unit; ?>" />
                                                        <td class="return_bal_qty_td"><?php if($update ) { echo $table_data->new_bal_qty;  } else {  echo $table_data->balance_unit; }?></td>
                                                        <input type="hidden" name="customer_detail[<?php echo $i; ?>][return_bal_qty]" id="return_bal_qty" class="return_bal_qty" value="<?php echo $table_data->balance_unit; ?>" />
                                                        <input type="hidden" name="customer_detail[<?php echo $i; ?>][return_bal]" id="return_bal" class="return_bal" value="<?php echo $table_data->balance_unit; ?>" />
                                                        <td><input type="text" name="customer_detail[<?php echo $i; ?>][return_qty_ret]" class="return_qty_ret" onkeypress="return isNumberKey(event)" style="width: 80px;" value="<?php if($update){ echo $table_data->return_unit; } else { echo 0; } ?>"/></td>
                                                        <td><?php echo $table_data->discount; ?> </td><input type="hidden" value="<?php echo $table_data->discount; ?>" name="customer_detail[<?php echo $i; ?>][return_mrp]" class="return_mrp" />
                                                        <td class="return_amt_td"> <?php if($update) { echo $table_data->amt; } ?></td><input type="hidden" value="<?php echo $table_data->amt; ?>" name="customer_detail[<?php echo $i; ?>][return_amt]" class="return_amt" value="<?php if($update){ echo $table_data->amt; } ?>" />
                                                        <td><?php  echo $table_data->cgst;   ?> </td><input type="hidden" value="<?php echo $table_data->cgst; ?>" name="customer_detail[<?php echo $i; ?>][return_cgst]" class="return_cgst" value="<?php if($update){ echo $table_data->cgst; } ?>" />
                                                        <td class="return_cgst_value_td"> <?php if($update) { echo $table_data->cgst_value; } ?> </td><input type="hidden"  name="customer_detail[<?php echo $i; ?>][return_cgst_value]" class="return_cgst_value" value="<?php if($update){ echo $table_data->cgst_value; } ?>" />
                                                        <td><?php  echo $table_data->sgst;  ?> </td><input type="hidden" value="<?php echo $table_data->sgst; ?>" name="customer_detail[<?php echo $i; ?>][return_sgst]" class="return_sgst" value="<?php if($update){ echo $table_data->sgst; } ?>" />
                                                        <td class="return_sgst_value_td"><?php if($update) { echo $table_data->cgst_value; } ?></td><input type="hidden"  name="customer_detail[<?php echo $i; ?>][return_sgst_value]" class="return_sgst_value" value="<?php if($update){ echo $table_data->cgst_value; } ?>" />
                                                        <td class="return_sub_total_td"><?php if($update) { echo $table_data->sub_total; } ?></td><input type="hidden"  name="customer_detail[<?php echo $i; ?>][return_sub_total]" class="return_sub_total" value="<?php  if($update){ echo $table_data->sub_total; } ?>" />
                                                    </tr>
                                                       
                                                   <?php  if($table_data->total != 0){
                                                   $total = $table_data->total;
                                                   }
                                                   $i++;
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
                                                                    <input type="text" class="form-control rtn_fsub_total" value="<?php if($update) { echo $total; } ?>" readonly name="rtn_fsub_total">
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
                                            if($display) {
                                        ?>
                                        <input type="hidden" name="id" class="invoice_id_new" value="<?php echo $bill_fdata->id; ?>">
                                        <button class="btn btn-success pull-right" id="ws_return_submit"><i class="fa fa fa-edit"></i> Create Goods Return</button>
                                        <?php
                                            } else {
                                        ?>  <input type="hidden" name="return_id" class="return_id_new" value="<?php echo $_GET['return_id']; ?>">
                                            <button class="btn btn-success pull-right" id="ws_return_update" ><i class="fa fa-credit-card"></i> Update Goods Return </button>
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
