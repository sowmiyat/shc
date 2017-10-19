<?php
	$update = false;
	$bill_data = false;
	if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoicews($_GET['id'], 1) ) {

		$update = true;
		$invoice_id['inv_id'] 	= $_GET['id'];
		$year = date('Y');

		$bill_data 					= getBillDataws($invoice_id['inv_id'],$year);

		$bill_fdata 				= $bill_data['bill_data'];

		$bill_ldata 				= $bill_data['ordered_data'];
		$delivery 					= getHomedelivery($_GET['id']);
		$invoice_id['invoice_id']       = $bill_fdata->id;
	} else {
		$invoice_id 				= generateInvoicews();
	}

?>
<style>
.ws_old_user_bill,.ws_new_customer {
    display: none;
    font-size: 16px;
}

.ws_old_user_bill, .ws_new_user_bill,.ws_new_customer{
    cursor: pointer;
    font-size: 16px;
}

.billing-structure {
    border: 2px solid red;
    padding: 20px;
}
.billing-structure .ws_balance_amount {
    font-weight: bold;
}
.add-button{
	margin-left: 45%;
    margin-top: 15px;


}

#bill_lot_add .sub_unit {
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


#bill_lot_add .sub_discount {
	border: 0;
    background-color: #f1ad76;
}
.payment_details_card,.payment_details_cheque,.payment_details_internet{
	display: none;
	margin-top: 13px;
    margin-bottom: 10px;
}
.cheque_date{
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

							<section class="content invoice" id="ws_billing_container">
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

												<div class="ws_billing_customer_div">

													<div class="ui-widget">
													  <label for="ws_billing_customer" style="width:100px;">Name: </label>
													  <input id="ws_billing_customer" name="name" required value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_name; } ?>" <?php if(isset($bill_fdata)){  echo 'readonly'; } ?>  >
													  <br/><br/>
													  <label for="ws_billing_mobile" style="width:100px;">Mobile: </label>
													  <input type="tel" id="ws_billing_mobile" name="mobile" class="mobile_check_wholesale"  value="<?php if(isset($bill_fdata)){ echo $bill_fdata->mobile; } ?>" <?php if(isset($bill_fdata)){ echo  'readonly'; } ?> >
													  <br/><br/>
													  <label for="ws_billing_address" style="width:100px;">Address: </label>
													  <input id="ws_billing_address" name="address" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->address; } ?>" <?php if(isset($bill_fdata)){ echo  'readonly'; } ?> >
													  <br/><br/>
													  <label for="ws_billing_company" style="width:100px;">Company Name: </label>
													  <input id="ws_billing_company" name="company" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->company_name; } ?>" <?php if(isset($bill_fdata)){ echo  'readonly'; } ?> >
													  <br/><br/>
													  <label for="ws_billing_gst" style="width:100px;">GST: </label>
													  <input id="ws_billing_gst" type="number" name="gst" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->gst_number; } ?>" <?php if(isset($bill_fdata)){ echo  'readonly'; } ?> >
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
					                                <input type="hidden" name="ws_old_customer_id" class="ws_old_customer_id" value="<?php if(isset($bill_fdata)){ echo $bill_fdata->customer_id;} else { echo '0'; } ?>"/>
					                                <input type="hidden" name="ws_customer_id_new" class="ws_customer_id_new"/>
					                            </div>
												<br><span class="ws_address1"></span>
												<br><span class="ws_customer_name"></span>
												<br><span class="ws_customer_company"></span>
												
											</address>							
											
									</div>
									<!-- /.col -->
									<div class="col-sm-4 invoice-col">
										<b>
											<input type="hidden" name="invoice_id" class="invoice_id" id="invoice_id" autocomplete="off" value="<?php echo $invoice_id['invoice_id']; ?>">
											 <input type="hidden" name="year" value="<?php echo $year; ?>" class="year"/> 
										</b>
										<br>
										<b>Invoice ID : </b> <?PHP echo $invoice_id['inv_id']; ?>
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
									<select name="lot_number" class="ws_lot_id" id="ws_lot_id" tabindex="-1" aria-hidden="true" />
									</select>


									<input type="hidden" name="ws_product" class="ws_product" /> 
									<input type="hidden" name="ws_unit_price" class="ws_unit_price"/>
									<input type="hidden" name="ws_hsn" class="ws_hsn"/>
									<input type="hidden" name="ws_cgst" class="cgst_percentage"/>
									<input type="hidden" name="ws_sgst" class="sgst_percentage"/>
									<input type="hidden" name="ws_stock" class="ws_slab_sys_txt"/>

									<span style="margin-left: 10%;">
										<B>Unit(Quantity):</B>
										<input type="number" name="unit" value="0" id="unit" class="unit" min="1"/>
									</span>	
									<span style="margin-left: 10%;">
										<B>Discounted Price:</B>
										<input type="number" name="discount" value="0.00" id="discount" class="discount"/>
									</span>
								</div>
								<div class="row"> 
									<div class="">
										<button class="btn btn-success add-button"  id="">ADD</button>
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
													<tbody class="bill_lot_add" id="bill_lot_add">
														<?php 
														if($bill_data['ordered_data']) {

															// if( jQuery('.customer_table[data-productid='+ product_id +']').length != 0 ) {
															// 	// var selector = jQuery('.customer_table[data-productid='+ product_id +']');
												   //  //             var actual_unit = selector.find('.sub_unit').val();
												   //  //             var final_unit = parseFloat(unit) + parseFloat(actual_unit);
												   //  //             selector.find('.sub_unit').val(final_unit);
												   //          }
												   //          else {
														
																$i = 1;
																foreach ($bill_ldata as $c_value) {
		 															echo '<tr data-randid='.getToken().' data-productid='.$c_value->sale_id.' class="customer_table" >
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
															//}
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
										
										<div class="billing-structure">Payment Due From Previous Bills:<span class="ws_balance_amount"></span>
											<input type="hidden" value="0.00" name="ws_balance_amount_val" class="ws_balance_amount_val"/> 
										</div>
										</br>

									</div>
									<!-- /.col -->
									<div class="col-xs-6">
										
										<div class="table-responsive">
											<table class="table">
												<tbody>
													<tr>
														<th>Discount: <br/>	
														<td>
															<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																<input type="text" class="form-control ws_discount" value="<?php 
																echo ( $bill_data && $bill_fdata ) ? $bill_fdata->discount : 0;  ?>" name="ws_discount">									
																<span class="fa fa-percent form-control-feedback right ws_dis_fa_per"></span>
																	
															</div>
														</td>
													</tr>
													<tr>
														<th style="width:50%">Subtotal:</th>
														<td>
															<div class="form-horizontal form-label-left input_mask" style="position:relative;">
																<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																	<input type="text" class="form-control ws_fsub_total" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->sub_total : 0;  ?>" readonly name="ws_fsub_total">
																	<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<th>Paid Amount:</th>
														<td>
															<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																<input type="text" class="form-control ws_paid_amount" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->paid_amount : 0;  ?>" name="ws_paid_amount">
																<span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span>
															</div>
														</td>
													</tr>
													<tr>
														<th>Balance Amount:</th>
														<td>
															<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
																<input type="hidden" class="form-control ws_return_amt" value="<?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->return_amt : 0;  ?>" name="ws_return_amt">
																<span class="ws_return_amt_txt"><?php echo ( $bill_data && $bill_fdata ) ? $bill_fdata->return_amt : 0;  ?></span>
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
																<tr><td><input type="radio" name="ws_payment_pay_type" value="Internet Banking" class="payment_pay_type"/>Internet Banking</td></tr>
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
															
															<input type="text" name="ws_delivery_name" class="ws_delivery_name" placeholder="Name" value="<?php echo $bill_fdata->home_delivery_name; ?>"/>
															<input type="text" name="ws_delivery_phone" class="ws_delivery_phone" placeholder="Phone" value="<?php echo $bill_fdata->home_delivery_mobile; ?>"/>
															<textarea  placeholder="Address" name="ws_delivery_address" class="ws_delivery_address"><?php echo $bill_fdata->home_delivery_address; ?></textarea>	
														</td>
													<?php } else { ?>
														<td>

															<input type="text" name="ws_delivery_name" class="ws_delivery_name" placeholder="Name"/>
															<input type="text" name="ws_delivery_phone" class="ws_delivery_phone" placeholder="Phone"/>
															<textarea  placeholder="Address" name="ws_delivery_address" class="ws_delivery_address"></textarea>
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
										<input type="hidden" name="id" class="invoice_id_new" value="<?php echo $invoice_id['invoice_id']; ?>">
											 <button class="btn btn-default ws_print_bill pull-right"><i class="fa fa-print"></i> Print</button>
											<button class="btn btn-success pull-right" id="ws_update_payment"><i class="fa fa fa-edit"></i> Update Invoice</button>
											<button class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-file-pdf-o ws_generate_bill"> Generate PDF</i></button>
										<?php
											} else {
										?>
											<button class="btn btn-success pull-right ws_bill_submit" id="ws_submit_payment" style="display:none;"><i class="fa fa-credit-card"></i> Create Invoice</button>
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


			var inputEl = document.getElementById('ws_billing_mobile');
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

			   
			</SCRIPT>

