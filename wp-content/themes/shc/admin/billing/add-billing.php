<script type="text/javascript">
	if(!!window.performance && window.performance.navigation.type === 2)
	{
	    console.log('Reloading');
	    window.location.reload();
	}

</script>
<?php
	$update = false;
	$bill_data = false;
	if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoice($_GET['inv_id'],$_GET['year'] ,1) ) {
		$update = true;
		$invoice_id['inv_id'] 		= $_GET['inv_id'];
		$year 						= $_GET['year'];
		$bill_data 					= getBillData($invoice_id['inv_id'],$year);

		$bill_fdata 				= $bill_data['bill_data'];

		$bill_ldata 				= $bill_data['ordered_data'];
		$invoice_id['invoice_id']   = $bill_fdata->id;
	} else {
		$invoice_id = generateInvoice();
		$year 		= $invoice_id['year'];
	}
	$netbank 		= get_netbank1();
	$bill_pdata 	= get_paymenttype($_GET['inv_id'],$_GET['year']);
	$bill_duedata 	= getDueDate($_GET['id']);
	$paid_due = 0;
	foreach ($bill_duedata as $due_value1) {
		$due_amount 	= $due_value1->due_amount; 
		$search_id 		= $due_value1->search_id; 
		$year 			= $due_value1->year; 
		$sale_id 		= $due_value1->sale_id; 
		$paid_due 		= $due_value1->amount + $paid_due;
	}

?>





<?php 

if(isset($_GET['id'])){
echo "<script language='javascript'>
  jQuery(document).ready(function (argument) { 
  	addTotalToDue();
    duepaid_fun('".$bill_fdata->customer_id."');
	jQuery('#update_payment').focus();
	jQuery('.paid_amount').trigger('change');
  	
  });

</script>";
}
else{
echo "<script language='javascript'>
	jQuery(document).ready(function (argument) {
		jQuery('.lot_id').focus();
	 });
</script>";
}
?>
<style>

.billing-structure {
    border: 2px solid red;
    padding: 5px;
}

.billing-structure .balance_amount {
    font-weight: bold;
}


#bill_lot_add_retail .retail_sub_unit {
	border: 0;
    background-color: #f1ad76;
}


.retail_sub_delete{
    color: #0073aa;
    text-decoration: underline;
}
 .retail_sub_delete:hover {
    color:#0073aa;
    cursor: pointer; 
    cursor: hand;
}
.payment_sub_delete{
	font-size: 16px;
    font-weight: bold;
    color: #ff0000;
}
.payment_sub_delete:focus {
	font-size: 24px;
    font-weight: bold;
    color: #ff0000;
    cursor: pointer; 
    cursor: hand;
}
#bill_lot_add_retail .retail_sub_discount {
	border: 0;
    background-color: #f1ad76;
}

.cheque_date {
	margin-top: 10px;
}
.stock_bal_table thead {
	background: #58606b;
    color: #fff;
}
.form-control-feedback{
	color: #000;
}
.secondary_mobile, .landline_mobile {
	display: none;
}
.x_title{
padding:0px;
border-bottom:0px;
}
.x_content{
margin-top: 0px;
}
.x_title h2{
    margin: 0px 0 0px;
    font-size: 16px;
}
.row{
	font-size: 13px;

}
.new_payment_pay_type_cash,.new_payment_pay_type_card,.new_payment_pay_type_internet,.new_payment_pay_type_cheque,.new_payment_pay_type_credit,.cod_check {
	width: 10px !important;
    height: 16px !important;
}
.payment_cash,.payment_completed {
	width: 10px !important;
    height: 16px !important;	
}
.payment_details_card,.payment_details_cheque,.payment_details_internet,.payment_details_cash,.cod_amount_div {
	display: none;
	margin-top: 13px;
    margin-bottom: 10px;
}
.payment_tab tr td th{
	padding: 5px;
}
.no_display {
	    display: none;
	}
/*<?php 
if(isset($bill_fdata)){
	if($bill_fdata->gst_type =='cgst') { ?>
	.igst_display {
	    display: none;
	}
	<?php  } else { ?>
	.cgst_display {
	    display: none;
	}
	<?php } ?>
	.no_display {
	    display: none;
	}
<?php }
else { ?>
	.no_display {
	    display: none;
	}
<?php }  ?>*/





</style>

<div class="">
	<div class="col-md-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Invoice </h2>
				<?php
				if($bill_data && isset($bill_fdata) && $bill_fdata) {
					//echo "<b>Order ID : </b> ".$bill_fdata->order_id;
				}
				?>
				<h2 style="float:right;"><b>Invoice ID : </b> <?php echo $invoice_id['inv_id']; ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form class="billing_validation" id="billing_container">
					<section class="content invoice" id="">
						<!-- title row -->
						<div class="row">
							<div class="row">
								<div class="col-xs-12 invoice-header">
									<input type="hidden" value="off" name="form_submit_prevent" class="form_submit_prevent_r_bill" id="form_submit_prevent_r_bill"/>
									<input type="hidden" class="reference_id" name="reference_id" value="<?php  echo isset($_GET['id'])? $_GET['id']:''; ?>"/>
									<input type="hidden" class="reference_screen" name="reference_screen" value="<?php  echo isset($_GET['id'])? 'billing_screen':''; ?>"/>
								</div>
							<!-- /.col -->
							</div>
							<!-- /.col -->
						</div>
						<!-- info row -->
						<!-- /.col -->
						<div class="row invoice-info">
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Name
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="billing_customer" name="name" autocomplete="off" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_name; } ?>" class="customer_check"/>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Primary Mobile
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="billing_mobile" name="mobile" autocomplete="off" class="form-control has-feedback-left"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->mobile; } ?>" maxlength="10" onkeypress="return isNumberKey(event)" style="padding-right: 5px;">
										<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+91</span> 
										<input type="hidden" class="customer_id" value="<?php echo ($bill_fdata) ? $bill_fdata->customer_id : '0'; ?>"/>
										<input type="hidden" class="unique_mobile_action" value="check_unique_mobile"/>
										
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 secondary_mobile">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Secondary Mobile
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="billing_secondary_mobile"  name="secondary_mobile" class="form-control has-feedback-left"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->secondary_mobile; } ?>"  maxlength="10" onkeypress="return isNumberKey(event)">
										<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">0</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12 landline_mobile">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Landline
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="billing_landline_mobile" name="landline" autocomplete="off" class="form-control has-feedback-left"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->landline; } ?>" maxlength="8" onkeypress="return isNumberKey(event)" >
										<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">044</span>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Address
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="billing_address" name="address" autocomplete="off"  class="address" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->address; } ?>" >
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">GST Type
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="radio" name="gst_type" class="gst_type" value="cgst" <?php  if(isset($bill_fdata)){ if($bill_fdata->gst_type == 'cgst') { echo 'checked'; }} else { echo 'checked'; }?>>CGST/SGST
										<input type="radio" name="gst_type" class="gst_type" value="igst"  <?php  if(isset($bill_fdata)){ if($bill_fdata->gst_type == 'igst') { echo 'checked'; }}?>>IGST
									</div>
								</div>
							</div>
							<input type="hidden" name="ws_user_type" value="new" class="ws_user_type"/>
			                <input type="hidden" name="old_customer_id" autocomplete="off" class="old_customer_id" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_id;} else { echo '0'; } ?>"/>
						</div>
						<div class="row invoice-info">
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Product Name <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" name="lot_number" class="lot_id" id="lot_id" />
										<input type="hidden" name="retail_lot_id_orig" class="retail_lot_id_orig">
										<input type="hidden" name="retail_product" class="retail_product" /> 
										<input type="hidden" name="retail_brand" class="retail_brand" /> 
										<input type="hidden" name="retail_unit_price" class="retail_unit_price"/>
										<input type="hidden" name="retail_wholesale_price" class="retail_wholesale_price"/>
										<input type="hidden" name="retail_mrp" class="retail_mrp"/>
										<input type="hidden" name="retail_hsn" class="retail_hsn"/>
										<input type="hidden" name="retail_cgst" class="retail_cgst_percentage"/>
										<input type="hidden" name="retail_sgst" class="retail_sgst_percentage"/>
										<input type="hidden" name="retail_igst" class="retail_igst_percentage"/>
										<input type="hidden" name="retail_cess" class="retail_cess_percentage"/>
										
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Unit(Quantity):<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="number" name="retail_unit" id="retail_unit" class="retail_unit" min="1"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="hidden" name="retail_discount" value="0.00" id="retail_discount" class="retail_discount"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name"><span class="required"></span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<a class="btn btn-success retailer_add-button" style="padding: 0px 6px;"  id="">ADD</a>
									</div>
								</div>
								<input type="hidden" name="invoice_id" class="invoice_id" id="invoice_id" autocomplete="off" value="<?php echo $invoice_id['invoice_id']; ?>">
								<input type="hidden" name="year" value="<?php echo $year; ?>" class="year"/> 
								<input type="hidden" name="inv_id" value="<?php echo $invoice_id['inv_id']; ?>" class="inv_id"/> 
							</div>

							<div class="col-md-8 col-sm-8 col-xs-12">
								<div class="stock_bal_table">
									<table class="table table-bordered">
										<thead style="background-color: #169f85;color:#fff">
											<th>#S.No</th>
											<th>Product Name</th>
											<th>MRP</th>
											<th>Discounted Price</th>
											<th>Stock</th>
										</thead>
										<tbody class="stock_table_body">
											<tr>
												
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
							
						<!-- Table row -->
						<div class="row">
							<div class="col-xs-12 table">							
								<div class="billing-repeater ws_sale_detail" style="margin-top:20px;">
									<table class="table table-striped" data-repeater-list="ws_sale_detail">
										<thead style="background-color: #169f85;color:#fff">
											<tr>
												<th>S.No</th>
												<th>Brand <br/>Name</th>
												<th>Product </br> Name</th>
												<th>HSN<br/>Code</th>
												<th>Unit</th>
												<th>Total <br/>Stock</th>
												<th>MRP</th>
												<th>Discounted <br/>Price</th>
												<th class="highlighter">Wholesale <br/>Price</th>
												<th>Taxless <br/> Amount</th>
												<th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?> cgst_display">CGST</th>
												<th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?> cgst_display">CGST <br/> Value</th>
												<th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?> cgst_display">SGST</th>
												<th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; ?> cgst_display">SGST <br/>Value</th>
												<th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'cgst')) ? 'no_display' : ''; ?> <?php echo (!isset($bill_fdata))? 'no_display' : ''; ?> igst_display">IGST</th>
												<th class="<?php echo ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'cgst')) ? 'no_display' : ''; ?> <?php echo (!isset($bill_fdata))? 'no_display' : ''; ?> igst_display">IGST <br/>Value</th>
												<th class="">CESS</th>
												<th>Total</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody class="bill_lot_add_retail" id="bill_lot_add_retail">
											<?php 
											if($bill_data['ordered_data']) {
												$i = 1;
												foreach ($bill_ldata as $c_value) {
													 $cgst_display_class = ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'igst')) ? 'no_display' : ''; 
													 $igst_display_class = ((isset($bill_fdata)) && ($bill_fdata->gst_type == 'cgst')) ? 'no_display' : ''; 
														echo '<tr data-randid='.getToken().' data-productid='.$c_value->lot_id.' class="customer_table_retail" >
														<td class="td_id">'.$i.'</td> <input type="hidden" value="'.$c_value->lot_id.'" name="customer_detail['.$i.'][id]" class="sub_id" />
														<td class="td_brand">' .$c_value->brand_name. '</td> <input type="hidden" value = "'.$c_value->brand_name. '" name="customer_detail['.$i.'][brand]" class="sub_brand"/>
														<td class="td_product">' .$c_value->product_name. '</td> <input type="hidden" value = "'.$c_value->product_name. '" name="customer_detail['.$i.'][product]" class="sub_product"/>
														<td class="td_hsn">' .$c_value->hsn. '</td> <input type="hidden" value = "'.$c_value->hsn. '" name="customer_detail['.$i.'][hsn]" class="sub_hsn"/>
														<td class=""><input type="text" onkeypress="return isNumberKey(event)" value = "'.$c_value->sale_unit. '" name="customer_detail['.$i.'][unit]" class="retail_sub_unit" size="4" autocomplete="off"/> </td> 
														<td class="td_stock">' .$c_value->stock. '</td><input type="hidden" value = "'.$c_value->stock.'" name="customer_detail['.$i.'][stock]" class="retail_sub_stock"/>
														<td class="td_price">' .$c_value->unit_price. '</td> <input type="hidden" value = "'.$c_value->unit_price. '" name="customer_detail['.$i.'][price]" class="sub_price"/> 
														<td><input type="text"  value ="'.$c_value->discount.'" name="customer_detail['.$i.'][discount]" class="retail_sub_discount" size="4" style="width: 70px;" autocomplete="off"/></td>
														<td class="highlighter">'.$c_value->wholesale_price.'<input type="hidden"  value ="'.$c_value->wholesale_price.'" name="customer_detail['.$i.'][wholesale_price]" class="sub_wholesale_price"/></td>
														<input type="hidden" value ="'.$c_value->discount_type.'" name="customer_detail['.$i.'][discount_type]" class="discount_type"/>
														<td class="td_amt">' .$c_value->amt. '</td> <input type="hidden" value = "'.$c_value->amt. '" name="customer_detail['.$i.'][amt]" class="sub_amt"/>
														<td class="td_cgst cgst_display '.$cgst_display_class.'">' .$c_value->cgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->cgst. '" name="customer_detail['.$i.'][cgst]" class="sub_cgst"/> 
														<td class="td_cgst_value cgst_display '.$cgst_display_class.'">'.$c_value->cgst_value.'</td> <input type="hidden" value = "'.$c_value->cgst_value.'" name="customer_detail['.$i.'][cgst_value]" class="sub_cgst_value"/>
														<td class="td_sgst cgst_display '.$cgst_display_class.'">' .$c_value->sgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->sgst. '" name="customer_detail['.$i.'][sgst]" class="sub_sgst"/>
														<td class="td_sgst_value cgst_display '.$cgst_display_class.'">'.$c_value->sgst_value.'</td> <input type="hidden" value = "'.$c_value->sgst_value.'" name="customer_detail['.$i.'][sgst_value]" class="sub_sgst_value"/>
														<td class="td_igst igst_display '.$igst_display_class.'">' .$c_value->igst. '  %' . '</td> <input type="hidden" value = "'.$c_value->igst. '" name="customer_detail['.$i.'][igst]" class="sub_igst"/> 
														<td class="td_igst_value igst_display '.$igst_display_class.'">'.$c_value->igst_value.'</td> <input type="hidden" value = "'.$c_value->igst_value.'" name="customer_detail['.$i.'][igst_value]" class="sub_igst_value"/>
														<td class="cess_value_td">'.$c_value->cess_value.'</td> <input type="hidden" value = "'.$c_value->cess_value.'" name="customer_detail['.$i.'][cess_value]" class="cess_value"/>
														<input type="hidden" value = "5.00" name="customer_detail['.$i.'][cess]" class="cess"/>
														<td class="td_total">'.$c_value->total.'</td> <input type="hidden" value ="'.$c_value->sub_total.'" name="customer_detail['.$i.'][subtotal]" class="sub_total"/><input type="hidden" value ="'.$c_value->total.'" name="customer_detail['.$i.'][total]" class="total"/><td><a href="#" class="retail_sub_delete">Delete</a></td></tr>';
													
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
						<!-- /.row -->
						<div class="row billing-repeater-extra">
							<!-- accepted payments column -->
							<div class="col-xs-6">	
								<div class="table-responsive">
									<table class="table">
										<tbody>
											<tr>
												<th style="width:50%">Subtotal:</th>
												<td>
													<div class="form-horizontal form-label-left input_mask" style="position:relative;">
														<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
															<input type="text" class="form-control f_total highlighter" tabindex="-1"  value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->before_total : 0;  ?>" readonly name="f_total">
															<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th style="width:50%">Total:</th>
												<td>
													<div class="form-horizontal form-label-left input_mask" style="position:relative;">
														<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
															<input type="text" class="form-control fsub_total highlighter" tabindex="-1"  value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->sub_total : 0;  ?>" readonly name="fsub_total">
															<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<td>											
													<div class="payment-mode">
									                    <div class="payment-container-top">
									                        <div class="payment-span" style="width: 180%;">
									                        	<b>Mode Of Payment  :     </b>
									                        	<input type="hidden" name="bill_paid" class="bill_paid" value="0"/>
									                            <input type="checkbox" name="payment_cash[]" value="cash_content" class="payment_cash" data-paytype="cash"> Cash 
									                            <input type="checkbox" name="payment_cash[]" value="card_content" class="payment_cash" data-paytype="card"> Card 
									                            <input type="checkbox" name="payment_cash[]" value="cheque_content" class="payment_cash" data-paytype="cheque"> Cheque 
									                            <input type="checkbox" name="payment_cash[]" value="internet_content" class="payment_cash" data-paytype="internet"> Neft
									                            <input type="checkbox" name="payment_cash[]" value="credit_content" class="payment_cash" data-paytype="credit"> Credit  
									                            <!-- <input type="checkbox" name="payment_cash" value="credit"> Credit -->
									                        </div>
									                    </div>
									                </div>
							            		
								            		<table class="payment_tab" style="width: 105%;">
								            			<thead>
								            				<th style="padding:5px;width: 165px;">Payment Type</th>
								            				<th style="padding:5px;">Amount</th>	
								            				<th style="padding:20px;">Date</th>	
								            				<th style="padding:5px;">Delete</th>
								            			</thead>
														<tbody class="bill_payment_tab" id="bill_payment_tab" style="width: 100%;">
															<?php 
																if($bill_data['ordered_data']) {
																	$i = 1;
																	foreach ($bill_pdata as $p_value) {
																		if($p_value->reference_screen == "due_screen"){ 
																			$readonly  = "readonly"; 
																			$display = "display:none";
																		} else{
																			$readonly  = "";
																			$display = "";
																		}
																		if($p_value->payment_type !='credit') {
																			if($p_value->payment_type == 'internet'){
																				$display_type = 'Netbanking';
																			}  else{
																				$display_type = ucfirst($p_value->payment_type);
																			}
																			echo '<tr  class="payment_table" >
																			<td style="padding:5px;">'.$display_type.' <input type="hidden" value="'.$p_value->payment_type.'" name="payment_detail['.$i.'][payment_type]" class="payment_type"  /> </td>
																			<td style="padding:5px;"><input type="text" '.$readonly.'  value ="'.$p_value->amount.'" name="payment_detail['.$i.'][payment_amount]" class="payment_amount" data-paymenttype="'.$p_value->payment_type.'" data-uniqueName="'.getToken().'" style="width: 74px;"/><input type="hidden" name="payment_detail['.$i.'][reference_screen]" value="'.$p_value->reference_screen.'" /><input type="hidden" name="payment_detail['.$i.'][reference_id]" value="'.$p_value->reference_id.'" /></td>
																			<td style="width: 204px;">'.$p_value->payment_date.'</td>
																			<td style="padding:5px;"><a href="#" style="'.$display.'" class="payment_sub_delete">x</a></td></tr>';
																		}
																		$i++;
																	}	
																}
															?>
														</tbody>
								            		</table>
								            		<table class="payment_tab">
								            			<thead>
								            				<th style="padding:5px;width:127px;"></th>
								            				<th style="padding:5px;"></th>
								            				<th style="padding:5px;"></th>
								            				<th style="padding:5px;"></th>
								            			</thead>
								            			<tbody class="bill_payment_tab_cheque" id="bill_payment_tab_cheque" style="width: 100%;">
								            				<?php 
																if($bill_data['ordered_data']) {
																	$i = 1;
																	foreach ($bill_pdata as $p_value) {
																		if($p_value->payment_type == 'credit'){ 
																			echo '<tr  class="payment_cheque" >
																			<td style="padding:5px;">'.ucfirst($p_value->payment_type).' <input type="hidden" value="'.$p_value->payment_type.'" name="pay_cheque" class="pay_cheque"  /> </td>
																			<td style="padding:5px;"><input type="text" value ="'.$p_value->amount.'" name="pay_amount_cheque" class="pay_amount_cheque" readonly style="width: 74px;"/><input type="hidden" name="reference_screen" value="'.$p_value->reference_screen.'" /><input type="hidden" name="reference_id" value="'.$p_value->reference_id.'" /></td>
																			<td style="width: 190px;">'.$p_value->payment_date.'</td>
																			<td style="padding:5px;width: 75px;"><a href="#"  class="payment_sub_delete">x</a></td></tr>';
																		}
																		$i++;
																	}	
																}
															?>
								            			</tbody>
								            		</table>
							            		</td> 
							            	</tr>

											<tr>
												<th></th>
												<td>
													<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
														<input type="hidden" class="form-control paid_amount"  value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->paid_amount : 0;  ?>" name="paid_amount">
														
													</div>
												</td>
											</tr>
											<!-- <tr>
												<td>
													<table class="table">
								            			<thead>
								            				<th style="padding:5px;width:98px;">Invoice Id</th>
								            				<th style="padding:5px;">Balance</th>
								            				<th style="padding:5px;">Amount</th>
								            				<th style="padding:5px;">Payment Type</th>
								            			</thead>
								            			<tbody class="due_tab" id="due_tab" style="width: 100%;">
								            				<?php 
																// if($bill_data['ordered_data']) {
																// 	$i = 1;
																// 		echo '<tr  class="due_data" >
																// 		<td style="padding:5px;">'.$sale_id.' <input type="hidden" value="'.$sale_id.'" name="due_detail['.$i.'][due_id]" /> </td>
																// 		<input type="hidden" name="due_detail['.$i.'][due_search_id]" value="'.$search_id.'" style="width:20px;" class="due_search_id"/>
																// 		<input type="hidden" name="due_detail['.$i.'][due_year]" value="'.$year.'" style="width:20px;" class="due_year"/>
																// 		<td style="padding:5px;">'.$due_amount.'<input type="hidden" value ="'.$due_amount.'" name="due_detail['.$i.'][due_amount]" class="due_amount"/></td>
																// 		<td style="padding:5px;"><input type="text" name="due_detail['.$i.'][paid_due]" class="paid_due"  style="width: 74px;" value="'.$paid_due.'" onkeypress="return isNumberKey(event)"/><input type="hidden" name="paid_due_hidden" class="paid_due_hidden" value="0"/></td><td><table class="duePaymentType">';
																//  	foreach ($bill_duedata as $due_value) {

																 		
																// 	echo '<tr class="aa" ref-uniquename="'.$due_value->uniquename.'">
																// 	<td class="ab"><input type="text" ref-uniquename="'.$due_value->uniquename.'" ref-paytype="'.$due_value->payment_type.'" class="row_cash_paid" name="duepayAmount[]" value="'.$due_value->amount.'"></td>
																// 	<input type="hidden" name="duepayUniquename[]" value="'.$due_value->uniquename.'"/>
																// 	<input type="hidden" name="duePaytype[]" value="'.$due_value->payment_type.'"/>
																// 	<input type="hidden" name="dueId[]" value="'.$due_value->id.'"/><input type="hidden" name="dueYear[]" value="'.$due_value->year.'"/>
																// 	<input type="hidden" name="dueInvid[]" value="'.$due_value->inv_id.'"/>
																// 	<input type="hidden" name="dueDueAmount[]" value="'.$due_value->due_amount.'"/></tr>
																// ';
																// 	$i++;
																// }
																// 	echo '</table></td></tr>';
																// }
																?>
																
								            			</tbody>
								            		</table>
												</td>
											</tr>
											<tr> -->

											<tr style="font-weight:bold;">
												<th>To Pay:
													<input type="checkbox" name="cur_bal_check_box" style="visibility:hidden;" class="cur_bal_check_box" style="width: 20px;height: 18px;" <?php if($bill_data && $bill_fdata){ $paid = $bill_fdata->pay_to_check; if($paid == '1' ){ echo 'checked'; }  } else { echo 'checked'; } ?>>

												</th>
												<td>
													<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
														<span class="current_bal_txt"><?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->pay_to_bal : 0;  ?></span>
														<input type="hidden" name="current_bal" class="current_bal"  value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->pay_to_bal : 0;  ?>"> 
														
														<!-- <span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span> -->
													</div>
												</td>
											</tr>
											<tr style="color:red;font-weight:bold;">
												
												<th>Balance:
												</th>
												<td>
													<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
														<span class="balance_pay"></span>
														
														
														<!-- <span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span> -->
													</div>
												</td>
											</tr>
											<tr <?php echo ( $bill_data && $bill_fdata )? 'style:"display:block"' : 'style="display:none"'; ?>>
												
												<th>Payment Completed:
												</th>
												<td>
													<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
														<input type="checkbox" name="payment_completed" class="payment_completed" <?php if($bill_data && $bill_fdata){ if($bill_fdata->payment_completed == 1){ echo 'checked';} else { echo ''; }  } ?> id="payment_completed"/>  
														
														
														<!-- <span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span> -->
													</div>
												</td>
											</tr>

										</tbody>
									</table>
								</div>
							</div>
							<!-- /.col -->

							<div class="col-xs-6">
								
								<div class="billing-structure">Due Amount:<span class="balance_amount"></span><br/>
									<input type="hidden" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->prev_bal : 0;  ?>" name="balance_amount_val" class="balance_amount_val"/>
									<input type="hidden" class="form-control current_due_bill" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->tot_due_amt : 0;  ?>" name="current_due_bill_txt">
									Total Due Balance <span class="current_due_bill_txt"><?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->tot_due_amt : 0;  ?></span>
								</div>
								<div class="cash_on_delivery">
									<div class="cash_on_delivery_in">
										<div style="width: 20%; float:left">
											COD 										
										 <input type="checkbox" name="cod_check" class="cod_check" value="cod" <?php if($bill_fdata->cod_check == '1'){ echo 'checked'; } ?>/>
										</div>
										
										<div class="cod_amount_div" <?php if($bill_data && $bill_fdata){
												if($bill_fdata->cod_check == '1') { echo 'style=display:block;width: 20%; float:right;'; } else { echo 'style=display:none;width: 20%; float:right'; }
											} else{ echo 'style=width: 20%; float:right'; }  ?>>

											<input type="text" name="cod_amount" style="width:60px;"  class="cod_amount" tabindex="-1"  value="<?php echo ($bill_data && $bill_fdata ) ? $bill_fdata->cod_amount : '0'; ?>" readonly />
										</div>										
									</div>
								</div>
								<div>
									Delivery: 
									<div>
										<?php if(isset($_GET['id'])) { 
											
											$is_delivery = $bill_fdata->is_delivery;
											 ?>
											<input type="radio" name="delivery_need" value="no"  class="ret_delivery_need" <?php if($is_delivery == '0'){ echo 'checked'; } ?>/> No
											<input type="radio" name="delivery_need" value="yes"  class="ret_delivery_need" <?php if($is_delivery == '1'){ echo 'checked'; } ?> /> Yes
											<div class="ret_delivery_display" style="display:none;">
												
												<input type="text" name="delivery_name" class="delivery_name customer_check" placeholder="Name" value="<?php echo $bill_fdata->home_delivery_name; ?>" style="height: 40px;" autocomplete="off"/>
												<input type="text" name="delivery_phone" class="delivery_phone" placeholder="Phone" value="<?php echo $bill_fdata->home_delivery_mobile; ?>" style="height: 40px;" autocomplete="off"/>
												<textarea  placeholder="Address" name="delivery_address" class="delivery_address customer_check" style="width:100%;border: 2px solid rgb(238, 238, 238);"><?php echo $bill_fdata->home_delivery_address; ?></textarea>	
											</div>
										<?php } else { ?>

											<input type="radio" name="delivery_need" value="no" class="ret_delivery_need" checked /> No
											<input type="radio" name="delivery_need" value="yes" class="ret_delivery_need" /> Yes
											<div class="ret_delivery_display" style="display:none;">
												<input type="text" name="delivery_name" class="delivery_name customer_check" placeholder="Name" style="height: 40px;"/>
												<input type="text" name="delivery_phone" class="delivery_phone" placeholder="Phone" onkeypress="return isNumberKeyDelivery(event)" style="height: 40px;"/>
												<textarea  placeholder="Address" name="delivery_address" class="delivery_address customer_check" style="width:100%;border: 2px solid rgb(238, 238, 238);"></textarea>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>

							<!-- /.col -->
						</div>
						<!-- /.row -->
						<!-- this row will not appear when printing -->
						<div class="row no-print">
							<div class="col-xs-12">
								<?php 
									if($update) {
								?>
								<input type="hidden" name="id" class="invoice_id_new" value="<?php echo $bill_fdata->id; ?>">
									
									<button class="btn btn-success pull-right bill_submit" id="update_payment" style="margin-top: 10px;" ><i class="fa fa fa-edit"></i> Update Invoice</button>
									
								<?php
									} else {
								?>
									<button class="btn btn-success pull-right bill_submit" id="submit_payment" style="margin-top: 10px;" ><i class="fa fa-credit-card" ></i> Create Invoice</button>
								<?php
									}
								?>

							</div>
						</div>
					</section>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- jQuery('.payment_amount').on('change',function(){
	var bill_paid = parseFloat(jQuery('.bill_paid').val());
	var total = parseFloat(jQuery('.fsub_total').val());
	var current_pay = parseFloat(jQuery(this).val());
	var tot_paid = current_pay + bill_paid;
	if(total > tot_paid){
		jQuery('.bill_paid').val(tot_paid);
console.log(tot_paid);
	} else {
		jQuery('.bill_paid').val(total);
		var bal = tot_paid - total;
	}
}); -->