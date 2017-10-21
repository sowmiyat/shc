<?php
	$update = false;
	$bill_data = false;
	if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoice($_GET['id'], 1) ) {
		$update = true;
		$invoice_id['inv_id'] 	= $_GET['id'];
		$year = date('Y');
		$bill_data = getBillData($invoice_id['inv_id'],$year);
		$bill_fdata = $bill_data['bill_data'];
		$bill_ldata = $bill_data['ordered_data'];

		
		$invoice_id['invoice_id']       = $bill_fdata->id;


		
	} else {
		$invoice_id = generateInvoice();
	}

?>
<style>

.billing-structure {
    border: 2px solid red;
    padding: 10px;
}

.billing-structure .balance_amount {
    font-weight: bold;
}


#bill_lot_add_retail .sub_unit {
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

#bill_lot_add_retail .sub_discount {
	border: 0;
    background-color: #f1ad76;
}
.payment_details_card,.payment_details_cheque,.payment_details_internet {
	display: none;
	margin-top: 13px;
    margin-bottom: 10px;
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



</style>
			<div class="row">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_title">

							<h2>Invoice </h2>
							<?php
							if($bill_data && isset($bill_fdata) && $bill_fdata) {
								echo "<b>Order ID : </b> ".$bill_fdata->order_id;
							}
							?>
							<h2 style="float:right;"><b>Invoice ID : </b> <?PHP echo 'INV'.$invoice_id['inv_id']; ?></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">

							<section class="content invoice" id="billing_container">
								<!-- title row -->
								<div class="row">
									<div class="row">
										<div class="col-xs-12 invoice-header">
											<h4>Customer Details</h4>
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
												<input type="text" id="billing_customer" name="name" required value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_name; } ?>"  >
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12">
										<div class="form-group">
											<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Primary Mobile<span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="tel" id="billing_mobile" name="mobile" class="form-control has-feedback-left"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->mobile; } ?>" style="padding-right: 5px;">
												<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+91</span> 
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12 secondary_mobile">
										<div class="form-group">
											<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Secondary Mobile<span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="tel" id="billing_secondary_mobile" name="secondary_mobile" class="form-control has-feedback-left"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->secondary_mobile; } ?>">
												<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+0</span>
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12 landline_mobile">
										<div class="form-group">
											<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Landline<span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="tel" id="billing_landline_mobile" name="landline" class="form-control has-feedback-left"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->landline; } ?>" >
												<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+044</span>
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-12">
										<div class="form-group">
											<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Address
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" id="billing_address" name="address" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->address; } ?>" >
											</div>
										</div>
									</div>
										<input type="hidden" name="ws_user_type" value="new" class="ws_user_type"/>
						                <input type="hidden" name="old_customer_id" class="old_customer_id" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_id;} else { echo '0'; } ?>"/>
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
												<input type="hidden" name="retail_unit_price" class="retail_unit_price"/>
												<input type="hidden" name="retail_hsn" class="retail_hsn"/>
												<input type="hidden" name="retail_cgst" class="retail_cgst_percentage"/>
												<input type="hidden" name="retail_sgst" class="retail_sgst_percentage"/>
												
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
											<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Discounted Price:<span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="number" name="retail_discount" value="0.00" id="retail_discount" class="retail_discount"/>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name"><span class="required"></span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<button class="btn btn-success retailer_add-button"  id="">ADD</button>
											</div>
										</div>
										<input type="hidden" name="invoice_id" class="invoice_id" id="invoice_id" autocomplete="off" value="<?php echo $invoice_id['invoice_id']; ?>">
										<input type="hidden" name="year" value="<?php echo $year; ?>" class="year"/> 
									</div>

									<div class="col-md-8 col-sm-8 col-xs-12">
										<div class="stock_bal_table">
											<table class="table table-bordered">
												<thead>
													<th>#S.No</th>
													<th>Product Name</th>
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
										<h2>Billed Items</h2>
										<div class="billing-repeater ws_sale_detail" style="margin-top:20px;">
											<table class="table table-striped" data-repeater-list="ws_sale_detail">
												<thead>
													<tr>
														<th>S.No</th>
														<th>Product Name</th>
														<th>HSN Code</th>
														<th>Unit</th>
														<th>MRP</th>
														<th>Discounted Price</th>
														<th>Amount</th>
														<th>CGST</th>
														<th>CGST Value</th>
														<th>SGST</th>
														<th>SGST Value</th>
														<th>Subtotal</th>
														<th>Action</th>
													</tr>
												</thead>
												<tbody class="bill_lot_add_retail" id="bill_lot_add_retail">
													<?php 
													if($bill_data['ordered_data']) {

														$i = 1;
														foreach ($bill_ldata as $c_value) {
 															echo '<tr data-randid='.getToken().' data-productid='.$c_value->sale_id.' class="customer_table_retail" >
 															<td class="td_id">'.$i.'</td> <input type="hidden" value="'.$c_value->lot_id.'" name="customer_detail['.$i.'][id]" class="sub_id" />
 															<td class="td_product">' .$c_value->product_name. '</td> <input type="hidden" value = "'.$c_value->product_name. '" name="customer_detail['.$i.'][product]" class="sub_product"/>
 															<td class="td_hsn">' .$c_value->hsn. '</td> <input type="hidden" value = "'.$c_value->hsn. '" name="customer_detail['.$i.'][hsn]" class="sub_hsn"/>
 															<td class=""><input type="text" value = "'.$c_value->sale_unit. '" name="customer_detail['.$i.'][unit]" class="retail_sub_unit"/> </td> 
 															<input type="hidden" value = "" name="customer_detail['.$i.'][stock]" class="retail_sub_stock"/>
 															<td class="td_price">' .$c_value->unit_price. '</td> <input type="hidden" value = "'.$c_value->unit_price. '" name="customer_detail['.$i.'][price]" class="sub_price"/> 
 															<td><input type="text" value ="'.$c_value->discount.'" name="customer_detail['.$i.'][discount]" class="retail_sub_discount"/></td>
 															<input type="hidden" value ="each" name="customer_detail['.$i.'][discount_type]" class="discount_type"/>
 															<td class="td_amt">' .$c_value->amt. '</td> <input type="hidden" value = "'.$c_value->amt. '" name="customer_detail['.$i.'][amt]" class="sub_amt"/>
 															<td class="td_cgst">' .$c_value->cgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->cgst. '" name="customer_detail['.$i.'][cgst]" class="sub_cgst"/> 
 															<td class="td_cgst_value">'.$c_value->cgst_value.'</td> <input type="hidden" value = "'.$c_value->cgst_value.'" name="customer_detail['.$i.'][cgst_value]" class="sub_cgst_value"/>
 															<td class="td_sgst">' .$c_value->sgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->sgst. '" name="customer_detail['.$i.'][sgst]" class="sub_sgst"/>
 															<td class="td_sgst_value">'.$c_value->sgst_value.'</td> <input type="hidden" value = "'.$c_value->sgst_value.'" name="customer_detail['.$i.'][sgst_value]" class="sub_sgst_value"/>
 															<td class="td_subtotal">'.$c_value->sub_total.'</td> <input type="hidden" value ="'.$c_value->sub_total.'" name="customer_detail['.$i.'][subtotal]" class="sub_total"/><td><span class="retail_sub_delete">Delete</span></td></tr>';
															
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
										
										<div class="billing-structure">Payment Due From Previous Bills:<span class="balance_amount"></span>
											<input type="hidden" value="0.00" name="balance_amount_val" class="balance_amount_val"/>
										</div>


										<div class="payment_options">
											<div class="payment_status">
												Payment Type : <?php 
													if(isset($_GET['id'])) { 
														$payment_type = $bill_fdata->payment_type;
														echo $payment_type; 
														echo "<br>";
														echo $bill_fdata->payment_details;
													} else {
													?>
														<div class="row">
															<div class="col-md-2">
																<input type="radio" name="payment_pay_type" value="cash" class="payment_pay_type" checked/> Cash
															</div>
															<div class="col-md-2">
																<input type="radio" name="payment_pay_type" value="card" class="payment_pay_type"/> Card
															</div>
															<div class="col-md-3">
																<input type="radio" name="payment_pay_type" value="cheque" class="payment_pay_type"/> Cheque
															</div>
															<div class="col-md-5">
																<input type="radio" name="payment_pay_type" value="Internet Banking" class="payment_pay_type"/>Internet Banking
															</div>
															<div class="col-md-5">
																<input type="radio" name="payment_pay_type" value="credit" class="payment_pay_type"/>Credit
															</div>
														</div>
														<div>
															<div class="payment_details_card"><input type="textarea" name="card_number" value=""  placeholder="Card Details" class="card_number"/> </div>
															<div class="payment_details_cheque">
																<input type="textarea" name="cheque_number" value="" placeholder="Cheque Details"  class="cheque_number"/>
																<input type="textarea" name="cheque_date" value="" placeholder="Cheque Date"  class="cheque_date"/>
															</div>
															<div class="payment_details_internet">
																<textarea  name="internet_banking_details" class="internet_banking_details" placeholder="Bank Details" style="width:100%;"></textarea>
															</div> 
														</div>
													<?php
													}
												?>
											</div>
										</div>
										<div>
											<div>
												Home Delivery: 
											</div>
											<div>
												<?php if(isset($_GET['id'])) { 
													$payment_type = $bill_fdata->payment_type; ?>
													<div>
														
														<input type="text" name="delivery_name" class="delivery_name" placeholder="Name" value="<?php echo $bill_fdata->home_delivery_name; ?>" style="height: 40px;"/>
														<input type="text" name="delivery_phone" class="delivery_phone" placeholder="Phone" value="<?php echo $bill_fdata->home_delivery_mobile; ?>" style="height: 40px;"/>
														<textarea  placeholder="Address" name="delivery_address" class="delivery_address" style="width:100%;"><?php echo $bill_fdata->home_delivery_address; ?></textarea>	
													</div>
												<?php } else { ?>
													<div>

														<input type="text" name="delivery_name" class="delivery_name" placeholder="Name" style="height: 40px;"/>
														<input type="text" name="delivery_phone" class="delivery_phone" placeholder="Phone" style="height: 40px;"/>
														<textarea  placeholder="Address" name="delivery_address" class="delivery_address" style="width:100%;"></textarea>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>

									<!-- /.col -->
									<div class="col-xs-6">
										
										<div class="table-responsive">
											<table class="table">
												<tbody>
													<tr>
														<th>Discount: <br/>	
															percentage
															<?php if(isset($_GET['id'])) { 
															$discount_type = $bill_fdata->discount_type; ?>
															<input type="radio" name="discount_per" value="percentage" class="discount_per" <?php if($discount_type == 'percentage') {echo 'checked'; } ?>> yes
																<input type="radio" name="discount_per" value="cash" class="discount_per" <?php if($discount_type == 'cash') {echo 'checked'; } ?> > no<br>
																<?php } else { ?>
															<input type="radio" name="discount_per" value="percentage" checked class="discount_per" > yes
															<input type="radio" name="discount_per" value="cash"  class="discount_per" > no<br>
																<?php } ?>
														</th>
														<td>
															<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																<input type="text" class="form-control discount" value="<?php 
																echo ( $bill_data && $bill_fdata ) ? $bill_fdata->discount : 0;  ?>" 
																name="discount">
																<?php if(isset($_GET['id'])) { 

																			if($discount_type == 'percentage') { 
																				?><span class="fa fa-percent form-control-feedback right dis_fa_per" aria-hidden="true"></span> 
																			<?php } 
																			else { ?>
																				<span class="fa fa-inr form-control-feedback right dis_fa_inr" aria-hidden="true"></span> <?php 
																			} 
																		} else { ?>
																			<span class="fa fa-inr form-control-feedback right dis_fa_inr" style="display: none;"></span>
																			<span class="fa fa-percent form-control-feedback right dis_fa_per"  ></span>
																<?php } ?>
																	
															</div>
														</td>
													</tr>
													<tr>
														<th style="width:50%">Subtotal:</th>
														<td>
															<div class="form-horizontal form-label-left input_mask" style="position:relative;">
																<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																	<input type="text" class="form-control fsub_total" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->sub_total : 0;  ?>" readonly name="fsub_total">
																	<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<th>Paid Amount:</th>
														<td>
															<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																<input type="text" class="form-control paid_amount" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->paid_amount : 0;  ?>" name="paid_amount">
																<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
															</div>
														</td>
													</tr>
													<tr>
														<th>Balance Amount:</th>
														<td>
															<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																<input type="hidden" class="form-control return_amt" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->return_amt : 0;  ?>" name="return_amt">
																<span class="return_amt_txt"><?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->return_amt : 0;  ?></span>
																<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
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
											 <button class="btn btn-default print_bill pull-right"><i class="fa fa-print"></i> Print</button>
											<button class="btn btn-success pull-right" id="update_payment"><i class="fa fa fa-edit"></i> Update Invoice</button>
											<button class="btn btn-primary pull-right " style="margin-right: 5px;"><i class="fa fa-file-pdf-o generate_bill"> Generate PDF</i></button>
										<?php
											} else {
										?>
											<button class="btn btn-success pull-right bill_submit" id="submit_payment"><i class="fa fa-credit-card" ></i> Create Invoice</button>
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

			<SCRIPT language=Javascript>
			var inputEl = document.getElementById('billing_mobile');
			var goodKey = '0123456789+ ';
			var key = null;

			var checkInputTel = function() {
			  var start = this.selectionStart,
			    end = this.selectionEnd;

			  var filtered = this.value.split('').filter(filterInput);
			  this.value = filtered.join("");

			  /* Prevents moving the pointer for a bad character */
			  var move = (filterInput(String.fromCharCode(key)) || (key == 0 || key == 8)) ? 0 : 1;
			  this.setSelectionRange(start - move, end - move);
			}

			var filterInput = function(val) {
			  return (goodKey.indexOf(val) > -1);
			}

			/* This function save the character typed */
			var res = function(e) {
			  key = (typeof e.which == "number") ? e.which : e.keyCode;
			}

			inputEl.addEventListener('input', checkInputTel);
			inputEl.addEventListener('keypress', res);
			   <!--
			   function isNumberKey(evt)
			   {
			      var charCode = (evt.which) ? evt.which : evt.keyCode;
			      if (charCode != 46 && charCode > 31 
			        && (charCode < 48 || charCode > 57))
			         return false;

			      return true;
			   }
			   //-->

			   // Select your input element.
				var numInput = document.querySelector('.retail_unit');

				// Listen for input event on numInput.
				numInput.addEventListener('input', function(){
				    // Let's match only digits.
				    var num = this.value.match(/^\d+$/);
				    if (num === null) {
				        // If we have no match, value will be empty.
				        this.value = "";
				    }
				}, false)
			</SCRIPT>