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
	if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoicews($_GET['inv_id'],$_GET['year'] ,1)) {

		$update = true;
		$invoice_id['inv_id'] 	= $_GET['inv_id'];
		$year = $_GET['year'];
		$bill_data 					= getBillDataws($invoice_id['inv_id'],$year);
		$bill_fdata 				= $bill_data['bill_data'];
		$bill_ldata 				= $bill_data['ordered_data'];
		$invoice_id['invoice_id']       = $bill_fdata->id;
	} else {
		$invoice_id 				= generateInvoicews();
		$year = $invoice_id['year'];
	}
	$netbank = get_netbank1();
	$bill_pdata = get_wspaymenttype($_GET['inv_id'],$_GET['year']);

	$bill_duedata 	= getWsDueDate($_GET['id']);
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
    ws_duepaid_fun('".$bill_fdata->customer_id."');
    jQuery('#ws_update_payment').focus();
    jQuery('.ws_paid_amount').trigger('change');
  });

</script>";
}
else{
echo "<script language='javascript'>
	jQuery(document).ready(function (argument) {
		var cus_ws = jQuery('#ws_billing_company').val();
		jQuery('#ws_billing_company').focus().val('').val(cus_ws);
	 });
</script>";
}
?>
<style>
	.billing-structure {
	    border: 2px solid red;
	    padding: 5px;
	}
	.billing-structure .ws_balance_amount {
	    font-weight: bold;
	}

	#bill_lot_add .sub_unit {
		border: 0;
	    background-color: #f1ad76;
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

	.ws_payment_sub_delete{
		font-size: 16px;
	    font-weight: bold;
	    color: #ff0000;
	}
	.ws_payment_sub_delete:focus {
		font-size: 24px;
	    font-weight: bold;
	    color: #ff0000;
	    cursor: pointer; 
	    cursor: hand;
	}
	#bill_lot_add .sub_discount {
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
	.form-control-feedback {
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
	.ws_payment_cash,.ws_cod_check {
		width: 10px !important;
	    height: 16px !important;	
	}
	.ws_payment_details_card,.ws_payment_details_cheque,.ws_payment_details_internet,.ws_payment_details_cash,.ws_cod_amount_div{
		display: none;
		margin-top: 13px;
	    margin-bottom: 10px;
	}
	.ws_payment_tab tr td th{
		    padding: 5px;
	}
</style>

			<div class="">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_title">
							<h2>
								Invoice 
								<?php 
									if($bill_data && isset($bill_fdata) && $bill_fdata) {
												echo "<b>Order ID : </b> ".$bill_fdata->order_id;
									}
								?>
							</h2>
							<h2 style="float:right;"><b>Invoice ID : </b> <?PHP echo 'Inv '.$invoice_id['inv_id']; ?></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<form class="ws_billing_validation" id="ws_billing_container">
								<section class="content invoice" id="">
									<div class="row">	
										<input type="hidden" value="off" name="form_submit_prevent" class="form_submit_prevent_ws_bill" id="form_submit_prevent_ws_bill"/>
									</div>
									<!-- info row -->
									<div class="row invoice-info">
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Company Name<span class="required">*</span>
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" id="ws_billing_company" name="company" autocomplete="off" class="customer_check" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->company_name; } ?>" >
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Name
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" id="ws_billing_customer"  class="customer_check" name="name" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_name; } ?>"  >
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Primary Mobile
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" id="ws_billing_mobile" name="mobile" class="form-control has-feedback-left" onkeypress="return isNumberKey(event)" maxlength="10"   value="<?php if(isset($bill_fdata)){ echo $bill_fdata->mobile; } ?>" style="padding-right: 5px;">
													<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+91</span> 
													<input type="hidden" class="customer_id" value="<?php echo ($bill_fdata) ? $bill_fdata->customer_id : '0'; ?>"/>
													<input type="hidden" class="unique_mobile_action" value="check_unique_mobile_wholesale"/>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12 secondary_mobile">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Secondary Mobile
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" id="ws_billing_secondary_mobile" name="secondary_mobile" autocomplete="off" class="form-control has-feedback-left" onkeypress="return isNumberKey(event)"  maxlength="10"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->secondary_mobile; } ?>">
													<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">0</span>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12 landline_mobile">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Landline
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" id="ws_billing_landline_mobile" name="landline" autocomplete="off" class="form-control has-feedback-left" onkeypress="return isNumberKey(event)" maxlength="8"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->landline; } ?>" >
													<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">044</span>
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Address
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input type="text" id="ws_billing_address" class="address" name="address" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->address; } ?>" >
												</div>
											</div>
										</div>
										
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">GST No.
												</label>
												<div class="col-md-6 col-sm-6 col-xs-12">
													<input id="ws_billing_gst" type="text" name="gst" maxlength="15" autocomplete="off" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->gst_number; } ?>">
												</div>
											</div>
										</div>

										<input type="hidden" name="ws_user_type" value="new" class="ws_user_type"/>
										<input type="hidden" name="ws_old_customer_id" class="ws_old_customer_id" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_id; } else { echo '0'; } ?>"/>
										<input type="hidden" name="ws_customer_id_new" class="ws_customer_id_new"/>
									</div>
									<!-- <form class="add_submit"> -->
										<!-- info row -->
										<div class="row invoice-info product_control">

											<div class="col-md-4 col-sm-4 col-xs-12">

												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Product Name <span class="required">*</span>
													</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<input type="text" name="lot_number" class="ws_lot_id" id="ws_lot_id" />
														<input type="hidden" name="ws_lot_id_orig" class="ws_lot_id_orig">
														<input type="hidden" name="ws_product" class="ws_product" /> 
														<input type="hidden" name="ws_brand" class="ws_brand" /> 
														<input type="hidden" name="ws_unit_price" class="ws_unit_price"/>
														<input type="hidden" name="ws_wholesale_price" class="ws_wholesale_price"/>
														<input type="hidden" name="ws_hsn" class="ws_hsn"/>
														<input type="hidden" name="ws_cgst" class="cgst_percentage"/>
														<input type="hidden" name="ws_sgst" class="sgst_percentage"/>
														
													</div>
												</div>

												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Unit(Quantity):<span class="required">*</span>
													</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<input type="number" name="unit" id="unit" class="unit"/>
													</div>
												</div>

												<div class="form-group">
													
													<div class="col-md-6 col-sm-6 col-xs-12">
														<input type="hidden" name="discount" value="" id="discount" class="discount"/>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name"><span class="required"></span>
													</label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<button class="btn btn-success add-button" style="padding: 0px 6px;" id="">ADD</button>
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
															<th>Stock</th>
														</thead>
														<tbody class="stock_table_body">
															<!-- <tr>
																<td class="ws_slab_id"></td>
																<td class="ws_slab_pro"></td>
																<td class="ws_slab_sys_text"></td>
															</tr> -->
														</tbody>
													</table>
												</div>
											</div>

										</div>
									<!-- </form> -->
									
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
																<th>HSN <br/> Code</th>
																<th>Unit</th>
																<th>Total <br/>Stock</th>
																<th>MRP</th>
																<th>Discounted <br/>Price</th>
																<th>Wholesale <br/>Price</th>
																<th>Taxless <br/> Amount</th>
																<th>CGST</th>
																<th>CGST <br/> Value</th>
																<th>SGST</th>
																<th>SGST <br/>Value</th>
																<th>Total</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody class="bill_lot_add" id="bill_lot_add">
															<?php 
															if($bill_data['ordered_data']) {
																$i = 1;
																foreach ($bill_ldata as $c_value) {
		 															echo '<tr data-randid='.getToken().' data-productid='.$c_value->lot_id.' class="customer_table" >
		 															<td class="td_id">'.$i.'</td> <input type="hidden" value="'.$c_value->lot_id.'" name="customer_detail['.$i.'][id]" class="sub_id" />
		 															<td class="td_brand">' .$c_value->brand_name. '</td> <input type="hidden" value = "'.$c_value->brand_name. '" name="customer_detail['.$i.'][brand]" class="sub_brand"/>
		 															<td class="td_product">' .$c_value->product_name. '</td> <input type="hidden" value = "'.$c_value->product_name. '" name="customer_detail['.$i.'][product]" class="sub_product"/>
		 															<td class="td_hsn">' .$c_value->hsn. '</td> <input type="hidden" value = "'.$c_value->hsn. '" name="customer_detail['.$i.'][hsn]" class="sub_hsn"/>
		 															<td class=""><input type="text" onkeypress="return isNumberKey(event)" value = "'.$c_value->sale_unit. '" name="customer_detail['.$i.'][unit]" style="width: 40px;" class="sub_unit"  autocomplete="off"/> </td> 
		 															<td class="">' .$c_value->stock. '</td> <input type="hidden" value = "'.$c_value->stock.'" name="customer_detail['.$i.'][stock]" class="sub_stock"/> 
		 															<td class="td_price">' .$c_value->unit_price. '</td> <input type="hidden" value = "'.$c_value->unit_price.'" name="customer_detail['.$i.'][price]" class="sub_price"/> 
		 															<td class=""> <input type="text" onkeypress="return isNumberKey(event)" value ="'.$c_value->discount.'" name="customer_detail['.$i.'][discount]" class="sub_discount" style="width: 70px;" autocomplete="off" /></td>
		 															<td>'.$c_value->wholesale_price.'<input type="hidden"  value ="'.$c_value->wholesale_price.'" name="customer_detail['.$i.'][wholesale_price]" class="sub_wholesale_price"/></td>
		 															<input type="hidden" value ="'.$c_value->discount_type.'" name="customer_detail['.$i.'][discount_type]" class="discount_type"/>
		 															<td class="td_amt">' .$c_value->amt. '</td> <input type="hidden" value = "'.$c_value->amt. '" name="customer_detail['.$i.'][amt]" class="sub_amt"/>
		 															<td class="td_cgst">' .$c_value->cgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->cgst. '" name="customer_detail['.$i.'][cgst]" class="sub_cgst"/> 
		 															<td class="td_cgst_value">'.$c_value->cgst_value.'</td> <input type="hidden" value = "'.$c_value->cgst_value.'" name="customer_detail['.$i.'][cgst_value]" class="sub_cgst_value"/>
		 															<td class="td_sgst">' .$c_value->sgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->sgst. '" name="customer_detail['.$i.'][sgst]" class="sub_sgst"/>
		 															<td class="td_sgst_value">'.$c_value->sgst_value.'</td> <input type="hidden" value = "'.$c_value->sgst_value.'" name="customer_detail['.$i.'][sgst_value]" class="sub_sgst_value"/>
		 															<td class="td_subtotal">'.$c_value->sub_total.'</td> <input type="hidden" value ="'.$c_value->sub_total.'" name="customer_detail['.$i.'][subtotal]" class="sub_total"/><input type="hidden" value ="'.$c_value->mrp_total.'" name="customer_detail['.$i.'][mrp_tot]" class="mrp_tot"/><td><a href="#" class="sub_delete">Delete</a></td></tr>';
	 															
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

										<div class="col-xs-6">
											
											<div class="table-responsive">
												<table class="table">
													<tbody>
														<input type="hidden" class="form-control ws_total"  onkeypress="return isNumberKeyWithDot(event)" value="<?php 
														echo ( $bill_data && $bill_fdata ) ? $bill_fdata->before_total : '';  ?>" name="ws_total" onkeypress="return isNumberKeyWithDot(event)"   style="margin: 0;">									
														
																		
														<tr>
															<th>Discount: <br/>	
															<td>
																<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																	<input type="text" class="form-control ws_discount"  onkeypress="return isNumberKeyWithDot(event)" value="<?php 
																	echo ( $bill_data && $bill_fdata ) ? $bill_fdata->discount : '';  ?>" name="ws_discount" onkeypress="return isNumberKeyWithDot(event)" autocomplete="off"  style="margin: 0;">									
																	<span class="fa fa-percent form-control-feedback right ws_dis_fa_per"></span>
																		
																</div>
															</td>
														</tr>
														<tr>
															<th style="width:50%">Total:</th>
															<td>
																<div class="form-horizontal form-label-left input_mask" style="position:relative;">
																	<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																		<input type="text" class="form-control ws_fsub_total" tabindex="-1" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->sub_total : 0;  ?>" readonly name="ws_fsub_total" autocomplete="off" style="margin: 0;">
																		<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
																	</div>
																</div>
															</td>
														</tr>
														<tr>
															<td>											
																<div class="payment-mode">
												                    <div class="payment-container-top">
												                        <div class="payment-span" style="    width: 190%;">
												                        	<b>Mode Of Payment  :     </b>
												                            <input type="checkbox" name="payment_cash[]" value="cash_content" class="ws_payment_cash" data-paytype="cash"> Cash 
												                            <input type="checkbox" name="payment_cash[]" value="card_content" class="ws_payment_cash" data-paytype="card"> Card 
												                            <input type="checkbox" name="payment_cash[]" value="cheque_content" class="ws_payment_cash" data-paytype="cheque"> Cheque 
												                            <input type="checkbox" name="payment_cash[]" value="internet_content" class="ws_payment_cash" data-paytype="internet"> Neft
												                            <input type="checkbox" name="payment_cash[]" value="credit_content" class="ws_payment_cash" data-paytype="credit"> Credit  
												                            <!-- <input type="checkbox" name="payment_cash" value="credit"> Credit -->
												                        </div>
												                    </div>
												                </div>
										            		
											            		<table style="" class="payment_tab">
											            			<thead>
											            				<th style="padding:5px;width: 165px;">Payment Type</th>
											            				<th style="padding:5px;">Amount</th>	
											            				<th style="padding:20px;">Date</th>	
											            				<th style="padding:5px;">Delete</th>
											            			</thead>
																	<tbody class="ws_bill_payment_tab" id="ws_bill_payment_tab" style="width: 100%;">
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
																							echo '<tr  class="ws_payment_table" >
																							<td style="padding:5px;">'.$display_type.' <input type="hidden" value="'.$p_value->payment_type.'" name="payment_detail['.$i.'][payment_type]" class="ws_payment_type"  /> </td>
																							<td style="padding:5px;"><input type="text" '.$readonly.'  value ="'.$p_value->amount.'" name="payment_detail['.$i.'][payment_amount]" class="ws_payment_amount" onkeypress="return isNumberKeyWithDot(event)" data-paymenttype="'.$p_value->payment_type.'" data-uniqueName="'.getToken().'" style="width: 74px;"/><input type="hidden" name="payment_detail['.$i.'][reference_screen]" value="'.$p_value->reference_screen.'" /><input type="hidden" name="payment_detail['.$i.'][reference_id]" value="'.$p_value->reference_id.'" /></td>
																							<td style="width: 204px;">'.$p_value->payment_date.'</td>
																							<td style="padding:5px;width: 75px;"><a href="#" style="'.$display.'" class="ws_payment_sub_delete">x</a></td></tr>';
																						}
																					$i++;
																				}	
																			}
																		?>
																	</tbody>
											            		</table>
											            		<table class="payment_tab">
											            			<thead>
											            				<th style="padding:5px;width:98px;"></th>
											            				<th style="padding:5px;"></th>
											            				<th style="padding:5px;"></th>
											            			</thead>
											            			<tbody class="ws_bill_payment_tab_cheque" id="ws_bill_payment_tab_cheque" style="width: 100%;">
											            				<?php 
																			if($bill_data['ordered_data']) {
																				$i = 1;
																				foreach ($bill_pdata as $p_value) {
																						if($p_value->payment_type == 'credit'){ 
																							echo '<tr  class="wsws__payment_cheque" >
																							<td style="padding:5px;">'.$p_value->payment_type.' <input type="hidden" value="'.$p_value->payment_type.'" name="pay_cheque" class="ws_pay_cheque"  /> </td>
																							<td style="padding:5px;"><input type="text" value ="'.$p_value->amount.'" name="pay_amount_cheque" class="ws_pay_amount_cheque" readonly style="width: 74px;"/><input type="hidden" name="reference_screen" value="'.$p_value->reference_screen.'" /><input type="hidden" name="reference_id" value="'.$p_value->reference_id.'" /></td>
																							<td style="width: 190px;">'.$p_value->payment_date.'</td>
																							<td style="padding:5px;width: 75px;"><a href="#"  class="ws_payment_sub_delete">x</a></td></tr>';
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
																	<input type="hidden" class="form-control ws_paid_amount"  value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->paid_amount : 0;  ?>" name="ws_paid_amount">
																	
																</div>
															</td>
														</tr>
														<!-- <tr>
															<th>Paid Amount:</th>
															<td>
																<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																	<input type="text" class="form-control ws_paid_amount" onkeypress="return isNumberKey(event)" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->paid_amount : '';  ?>" onkeypress="return isNumberKey(event)"  name="ws_paid_amount" autocomplete="off" style="margin: 0;">
																	<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
																</div>
															</td>
														</tr> -->

														<tr style="font-weight:bold;">
															<th>To pay: <input type="checkbox" name="cur_bal_check_box" style="visibility:hidden;" class="cur_bal_check_box" style="width: 20px;height: 18px;" <?php if($bill_data && $bill_fdata){ $paid = $bill_fdata->pay_to_check; if($paid == '1' ){ echo 'checked'; }  } else { echo 'checked'; } ?>>
															</th>
															<td>
																<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																	<span class="ws_current_bal_txt"><?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->current_bal : 0;  ?></span>
																	<input type="hidden" name="ws_current_bal" class="ws_current_bal"  value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->current_bal : 0;  ?>"> 
														
														<!-- <span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span> -->
																</div>
															</td>
														</tr>
														<tr style="color:red;font-weight:bold;">
															<th >Balance:
															</th>
															<td>
																<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																	<span class="ws_balance_pay"></span>
																	
																	
																	<!-- <span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span> -->
																</div>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<!-- /.col -->
										<!-- accepted payments column -->
										<div class="col-xs-6">
											
											<div class="billing-structure">Due Amount:<span class="ws_balance_amount"></span></br/>
												<input type="hidden" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->prev_bal : 0;  ?>" name="ws_balance_amount_val"  class="ws_balance_amount_val"/>
												<input type="hidden" class="form-control ws_return_amt" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->tot_due_amt : 0;  ?>" name="ws_return_amt">
												Total Due Balance: <span class="ws_return_amt_txt"><?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->tot_due_amt : 0;  ?></span>
											</div>

											<div class="cash_on_delivery">
												<div class="cash_on_delivery_in">
													<div style="width: 20%; float:left">
														COD 										
													 	<input type="checkbox" name="cod_check" class="ws_cod_check" value="cod" <?php if($bill_fdata->cod_check == '1'){ echo 'checked'; } ?>/>
													</div>
													<div class="ws_cod_amount_div" <?php if($bill_data && $bill_fdata){
															if($bill_fdata->cod_check == '1') { echo 'style=display:block;width: 20%; float:right;'; } else { echo 'style=display:none;width: 20%; float:right'; }
														} else{ echo 'style=width: 20%; float:right'; }  ?>>

														<input type="text" name="cod_amount" style="width:60px;" class="ws_cod_amount" tabindex="-1"  value="<?php echo ($bill_data && $bill_fdata ) ? $bill_fdata->cod_amount : '0'; ?>" readonly />
													</div>										
												</div>
											</div>
											<div style="margin-top: 5px;">
												
													Delivery:
												
												
													<?php if(isset($_GET['id'])) { 
														$payment_type = $bill_fdata->payment_type; ?>

														<!-- <input type="radio" name="delivery_need" value="no" class="delivery_need" checked /> No
														<input type="radio" name="delivery_need" value="yes" class="delivery_need" /> Yes -->
														<div class="delivery_display">
															
															<input type="text" name="ws_delivery_name" class="ws_delivery_name customer_check" placeholder="Name" value="<?php echo $bill_fdata->home_delivery_name; ?>" style="height: 40px;"/>
															<input type="text" name="ws_delivery_phone" class="ws_delivery_phone" placeholder="Phone" value="<?php echo $bill_fdata->home_delivery_mobile; ?>" style="height: 40px;"/>
															<textarea  placeholder="Address" name="ws_delivery_address" class="ws_delivery_address customer_check" style="width:100%;border: 2px solid rgb(238, 238, 238);"><?php echo $bill_fdata->home_delivery_address; ?></textarea>	
														</div>
													<?php } else { ?>
														<input type="radio" name="delivery_need" value="no" class="delivery_need" checked /> No
														<input type="radio" name="delivery_need" value="yes" class="delivery_need" /> Yes
														
														<div class="delivery_display" style="display:none;">

															<input type="text" name="ws_delivery_name" class="ws_delivery_name customer_check" placeholder="Name" style="height: 40px;"/>
															<input type="text" name="ws_delivery_phone" class="ws_delivery_phone" placeholder="Phone" onkeypress="return isNumberKeyDelivery(event)"  style="height: 40px;"/>
															<textarea  placeholder="Address" name="ws_delivery_address" class="ws_delivery_address customer_check"  style="width:100%;border: 2px solid rgb(238, 238, 238);"></textarea>
														</div>
													<?php } ?>
												
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
											<input type="hidden" name="id" class="invoice_id_new" value="<?php echo $invoice_id['invoice_id']; ?>">
												<button class="btn btn-success pull-right ws_bill_submit" id="ws_update_payment" style="margin-top: 10px;"><i class="fa fa fa-edit"></i> Update Invoice</button>
											<?php
												} else {
											?>
												<button class="btn btn-success pull-right ws_bill_submit" id="ws_submit_payment" style="margin-top: 10px;" ><i class="fa fa-credit-card"></i> Create Invoice</button>
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


