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

		$delivery 						= getHomedelivery($_GET['id']);
		$invoice_id['invoice_id']       = $bill_fdata->id;


		
	} else {
		$invoice_id = generateInvoice();
	}

?>
<style>
.old_user_bill,.new_customer {
    display: none;
    font-size: 11px;
}

.old_user_bill, .new_user_bill,.new_customer{
    cursor: pointer;
    font-size: 11px;
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

.billing-structure {
    border: 2px solid red;
    padding: 20px;
}

.billing-structure .balance_amount {
    font-weight: bold;
}

.retailer_add-button {
	margin-left: 45%;
    margin-top: 15px;


}

#bill_lot_add_retail .sub_unit {
	border: 0;
    background-color: #f1ad76;
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




</style>
			<div class="row">
				<div class="col-md-12">
					<div class="x_panel">
						<div class="x_title">

							<h2>Invoice Design</h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">

							<section class="content invoice" id="billing_container">
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
											<address>												
												<div class="billing_customer_div">

													<div class="ui-widget">
													  <label for="billing_customer" style="width:100px;">Name: </label>
													  <input id="billing_customer" name="name" required value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_name; } ?>" <?php if(isset($bill_fdata)){  echo 'readonly'; } ?>  >
													  <br/><br/>
													  <label for="billing_mobile" style="width:100px;">Mobile: </label>
													  <input type="tel" id="billing_mobile" name="mobile" class="mobile_check"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->mobile; } ?>" <?php if(isset($bill_fdata)){ echo  'readonly'; } ?> >
													  <br/><br/>
													  <label for="billing_address" style="width:100px;">Address: </label>
													  <input id="billing_address" name="address" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->address; } ?>" <?php if(isset($bill_fdata)){ echo  'readonly'; } ?> >
													  <br/><br/>
													</div>
													<!-- <select id="ws_billing_customer" name="ws_customer_id" class="ws_billing_customer" tabindex="-1" aria-hidden="true">
													<?php
														if($bill_data && isset($bill_fdata) && $bill_fdata) {
															echo '<option selected value="'.$bill_fdata->customer_id.'">'.$bill_fdata->mobile.'</option>';
														}
													?>
													</select> -->
												
												<!-- <div class="new_customername">
													<input type="text" class="ws_new_customer" name="ws_new_customer" readonly/>
												</div> -->
												
					                                <input type="hidden" name="ws_user_type" value="new" class="ws_user_type"/>
					                                <input type="hidden" name="old_customer_id" class="old_customer_id" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_id;} else { echo '0'; } ?>"/>
					                               
					                            </div>
											</address>
									</div>
									<!-- /.col -->
									<div class="col-sm-4 invoice-col">
										<b>
											<input type="hidden" name="invoice_id" class="invoice_id" id="invoice_id" autocomplete="off" value="<?php echo  $invoice_id['invoice_id']; ?>">
											<input type="hidden" name="year" value="<?php echo $year; ?>" class="year"/> 
											<b>Invoice Id : </b> <?php echo '#INV'.$invoice_id['inv_id']; ?>
										</b>
										<br>
										<br>
										<?php
										if($bill_data && isset($bill_fdata) && $bill_fdata) {
											echo "<b>Order ID : </b> ".$bill_fdata->order_id;
										}
										
										?>
									</div>
									<!-- /.col -->
								</div>
								<!-- /.row -->
								<div class="row">
									<B>Product Name :</B>									
									<select name="lot_number" class="retail_lot_id" id="retail_lot_id" tabindex="-1" aria-hidden="true" />
									</select>


									<input type="hidden" name="retail_product" class="retail_product" /> 
									<input type="hidden" name="retail_unit_price" class="retail_unit_price"/>
									<input type="hidden" name="retail_hsn" class="retail_hsn"/>
									<input type="hidden" name="retail_cgst" class="retail_cgst_percentage"/>
									<input type="hidden" name="retail_sgst" class="retail_sgst_percentage"/>
									<input type="hidden" name="retail_stock" class="retail_slab_sys_txt"/>

									<span style="margin-left: 10%;">
										<B>Unit(Quantity):</B>
										<input type="number" name="unit" value="1" id="retail_unit" class="retail_unit" min="0"/>
									</span>	
									<span style="margin-left: 10%;">
										<B>Discounted Price:</B>
										<input type="number" name="retail_main_discount" value="0.00" id="retail_discount" min="0" class="retail_discount"/>
									</span>
								</div>
								<div class="row"> 
									<div class="">
										<button class="btn btn-success retailer_add-button"  id="">ADD</button>
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
														<th>Stock</th>
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
 															<td class=""><input type="text" value = "'.$c_value->sale_unit. '" name="customer_detail['.$i.'][unit]" class="sub_unit"/> </td> 
 															<td class="td_stock"></td> <input type="hidden" value = "" name="customer_detail['.$i.'][stock]" class="sub_stock"/>
 															<td class="td_price">' .$c_value->unit_price. '</td> <input type="hidden" value = "'.$c_value->unit_price. '" name="customer_detail['.$i.'][price]" class="sub_price"/> 
 															<td><input type="text" value ="'.$c_value->discount.'" name="customer_detail['.$i.'][discount]" class="sub_discount"/></td>
 															<input type="hidden" value ="each" name="customer_detail['.$i.'][discount_type]" class="discount_type"/>
 															<td class="td_amt">' .$c_value->amt. '</td> <input type="hidden" value = "'.$c_value->amt. '" name="customer_detail['.$i.'][amt]" class="sub_amt"/>
 															<td class="td_cgst">' .$c_value->cgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->cgst. '" name="customer_detail['.$i.'][cgst]" class="sub_cgst"/> 
 															<td class="td_cgst_value">'.$c_value->cgst_value.'</td> <input type="hidden" value = "'.$c_value->cgst_value.'" name="customer_detail['.$i.'][cgst_value]" class="sub_cgst_value"/>
 															<td class="td_sgst">' .$c_value->sgst. '  %' . '</td> <input type="hidden" value = "'.$c_value->sgst. '" name="customer_detail['.$i.'][sgst]" class="sub_sgst"/>
 															<td class="td_sgst_value">'.$c_value->sgst_value.'</td> <input type="hidden" value = "'.$c_value->sgst_value.'" name="customer_detail['.$i.'][sgst_value]" class="sub_sgst_value"/>
 															<td class="td_subtotal">'.$c_value->sub_total.'</td> <input type="hidden" value ="'.$c_value->sub_total.'" name="customer_detail['.$i.'][subtotal]" class="sub_total"/><td><span class="sub_delete">Delete</span></td></tr>';
															
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
										<div class="billing-structure">Payment Due From Previous Bills:<span class="balance_amount"></span></div>
										<input type="hidden" value="0.00" name="balance_amount_val" class="balance_amount_val"/>
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
													<tr>
													<th>Payment Type: <br/></th>
													<?php if(isset($_GET['id'])) { 
														$payment_type = $bill_fdata->payment_type; ?>
														<td>
														<?php 	echo $payment_type; 
																echo '<br/>';
																echo $bill_fdata->payment_details;
														 ?>
														</td>
													<?php } else { ?>
														<td>
															<table>
																<tr><td><input type="radio" name="ws_payment_pay_type" value="cash" class="payment_pay_type" checked/> Cash</td></tr>
																<tr><td><input type="radio" name="ws_payment_pay_type" value="card" class="payment_pay_type"/> Card</td></tr>
																<tr><td class="payment_details_card"><input type="textarea" name="card_number" value=""  placeholder="Card Details" class="card_number"/> </td></tr>
																<tr><td><input type="radio" name="ws_payment_pay_type" value="cheque" class="payment_pay_type"/> Cheque</td> </tr>
																<tr>
																	<td class="payment_details_cheque">
																		<input type="textarea" name="cheque_number" value="" placeholder="Cheque Details"  class="cheque_number"/>

																		<input type="textarea" name="cheque_date" value="" placeholder="Cheque Date"  class="cheque_date"/>
																	 </td> 
																	
																	 	
																	
																</tr>
																<tr>
																	<td>
																		<input type="radio" name="ws_payment_pay_type" value="Internet Banking" class="payment_pay_type"/>Internet Banking
																	</td>
																</tr>
																<tr>
																	<td class="payment_details_internet">
																		<textarea  name="internet_banking_details" class="internet_banking_details" placeholder="Bank Details" ></textarea>
																	</td>
																</tr>
																<tr>
																	<td>
																		<input type="radio" name="ws_payment_pay_type" value="credit" class="payment_pay_type"/>Credit
																	</td>
																</tr>
															</table>
														</td>
															<?php } ?>
													</tr>
													<tr>
													<th>Home Delivery: <input type ="hidden" value="wholesale_customer" class="customer_type"/><br/></th>

													<?php if(isset($_GET['id'])) { 
														$payment_type = $bill_fdata->payment_type; ?>
														<td>
															<input type="text" name="delivery_name" class="delivery_name" placeholder="Name" value="<?php echo $bill_fdata->home_delivery_name; ?>"/>
															<input type="text" name="delivery_phone" class="delivery_phone" placeholder="Phone" value="<?php echo $bill_fdata->home_delivery_mobile; ?>"/>
															<textarea  placeholder="Address" name="delivery_address" class="delivery_address"><?php echo $bill_fdata->home_delivery_address; ?></textarea>	
														</td>
													<?php } else { ?>
														<td>
															<input type="text" name="delivery_name" class="delivery_name" placeholder="Name"/>
															<input type="text" name="delivery_phone" class="delivery_phone" placeholder="Phone"/>
															<textarea  placeholder="Address" name="delivery_address" class="delivery_address"></textarea>
														</td>
															<?php } ?>
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
											<button class="btn btn-success pull-right bill_submit" id="submit_payment" style="display:none;"><i class="fa fa-credit-card" ></i> Create Invoice</button>
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