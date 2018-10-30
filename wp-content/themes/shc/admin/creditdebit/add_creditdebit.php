
<style>

 .payment_cash_cd{
	width: 10px !important;
    height: 16px !important;	
}
.payment_sub_delete_cd{
	font-size: 16px;
    font-weight: bold;
    color: #ff0000;
}
.payment_sub_delete_cd:focus {
	font-size: 24px;
    font-weight: bold;
    color: #ff0000;
    cursor: pointer; 
    cursor: hand;
}

</style>


<?php
$lot = false;
if(isset($_GET['id']) && $credit_debit = get_creditdebit($_GET['id']) ) {
	$credit_id = $_GET['id'];
}

?>
<div class="">
	<div class="" style="margin-top:20px;">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Add New Notes <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left creditdebit_submit" id="create_creditdebit">
						<input type="hidden" value="off" name="form_submit_prevent" class="form_submit_prevent_credit" id="form_submit_prevent_credit"/>
						<input type="hidden" id="creditdebit_id" name="creditdebit_id" class="form-control col-md-7 col-xs-12 creditdebit_id" autocomplete="off" value="<?php echo ($credit_debit) ? $credit_id : '0'; ?>">
						<input type="hidden" id="creditdebit_cus_id" name="creditdebit_cus_id" class="form-control col-md-7 col-xs-12 creditdebit_cus_id" value="<?php echo ($credit_debit) ? $credit_debit['main_tab']->customer_id : '0'; ?>">
						<input type="hidden" id="creditdebit_screen" name="creditdebit_screen" class="form-control col-md-7 col-xs-12 creditdebit_screen" value="<?php echo ($credit_debit) ? 'due_screen': ''; ?>">
						
							<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Customer Type <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select name="customer_type" class="customer_type">
											<option <?php echo ($credit_debit['main_tab']->customer_type == 'ws') ? 'selected': ''; ?> value="ws">Wholesale</option>
											<option <?php echo ($credit_debit['main_tab']->customer_type == 'retail') ? 'selected': ''; ?> value="retail">Retail</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Company/Mobile <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="creditdebit_customer" name="creditdebit_customer"  class="form-control col-md-7 col-xs-12 creditdebit_customer" autocomplete="off" value="<?php echo ($credit_debit) ? $credit_debit['main_tab']->customer_name : ''; ?>">
									</div>
								</div>
							</div>
						</div>
						
						<div class="divider-dashed"></div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Description
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<textarea name="description" id="description" class="description" style="border: 2px solid rgb(238, 238, 238);"><?php echo ($credit_debit) ? $credit_debit['main_tab']->description : '';  ?></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Date <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="creditdebit_date" name="creditdebit_date"  class="form-control col-md-7 col-xs-12 creditdebit_date" autocomplete="off" value="<?php echo ($credit_debit) ? $credit_debit['main_tab']->date : date("Y-m-d"); ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Due Amount
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<span class="total_due_text"></span>
										<input type="hidden" id="total_due" name="total_due"  class="form-control col-md-7 col-xs-12 total_due" value="<?php echo ($credit_debit) ? $credit_debit['main_tab']->due_amount : 0; ?>"/>
									</div>
								</div>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="row">
							<div class="payment-mode">
			                    <div class="payment-container-top">
			                        <div class="payment-span" style="">
			                        	<b>Mode Of Payment <span class="required">*</span> :     </b>
			                            <input type="checkbox" name="payment_cash[]" value="cash" class="payment_cash_cd" data-paytype="cash"> Cash 
			                            <input type="checkbox" name="payment_cash[]" value="card" class="payment_cash_cd" data-paytype="card"> Card 
			                            <input type="checkbox" name="payment_cash[]" value="cheque" class="payment_cash_cd" data-paytype="cheque"> Cheque 
			                            <input type="checkbox" name="payment_cash[]" value="internet" class="payment_cash_cd" data-paytype="internet"> Neft
			                            <!-- <input type="checkbox" name="payment_cash" value="credit"> Credit -->
			                        </div>
			                    </div>
			                </div>
	            		
		            		<table class="payment_tab_cd" >
		            			<thead>
		            				<th style="padding:5px;">Payment Type</th>
		            				<th style="padding:5px;">Amount</th>
		            				<th style="padding:5px;">Date</th>	
		            				<th style="padding:5px;">Delete</th>	
		            				
		            			</thead>
								<tbody class="bill_payment_tab_cd" id="bill_payment_tab_cd" style="width: 100%;">
									<?php 
										if($credit_debit) {
											$i = 1;
											foreach ($credit_debit['sub_tab'] as $p_value) {
													if($p_value->payment_type !='credit') { 
														echo '<tr  class="payment_table_cd" >
														<td style="padding:5px;">'.ucfirst($p_value->payment_type).' <input type="hidden" value="'.$p_value->payment_type.'" name="payment_detail['.$i.'][payment_type]" class="payment_type_cd"  /> </td>
														<td style="padding:5px;"><input type="text" value ="'.$p_value->amount.'" name="payment_detail['.$i.'][payment_amount]" class="payment_amount_cd" data-paymenttype="'.$p_value->payment_type.'" data-uniqueName="'.getToken().'" style="width: 74px;" onkeypress="return isNumberKey(event)"/></td>
														<td style="padding:5px;">'.$p_value->payment_date.'</td>
														<td style="padding:5px;"><a href="#" class="payment_sub_delete_cd">x</a></td></tr>';
													}
												$i++;
											}	
										}
									?>
								</tbody>
		            		</table>
						</div>
						<div class="divider-dashed"></div>
						<table class="table">
	            			<thead>
	            				<th style="padding:5px;width:98px;">Invoice Id</th>
	            				<th style="padding:5px;">Balance</th>
	            				<th style="padding:5px;">Amount</th>
	            				<th style="padding:5px;">Payment type</th>
	            			</thead>
	            			<tbody class="due_tab_cd" id="due_tab_cd" style="width: 100%;">

	            			</tbody>
	            		</table>
	            		<div class="divider-dashed"></div>
	            		<table>
	            			<th style="width: 100px;">To Pay:
							</th>
							<td>
								<div class="col-xs-12 col-md-8 col-lg-6 form-group has-feedback nopadding">
									<span class="current_bal_txt_cd"><?php echo ( $credit_debit ) ? $credit_debit['main_tab']->to_pay_amt : 0;  ?></span>
									<input type="hidden" name="to_pay_amt" class="to_pay_amt_cd"  value="<?php echo ( $credit_debit ) ? $credit_debit['main_tab']->to_pay_amt : 0;  ?>"> 
									
									<!-- <span class="fa fa-inr form-control-feedback right" aria-hidden="true"></span> -->
								</div>
							</td>
						</table>

						<div class="divider-dashed"></div>
						<div class="form-group">
							<div style="text-align:center;">
								<button type="submit" class="btn btn-success credit_submit">Submit</button>
	                          	
	                          	
								<?php 
									if(  $credit_debit ) {
										echo '<input type="hidden" name="creditdebit_id" value="'.$credit_id.'">';
										echo '<input type="hidden" name="action" class="creditdebit_action" value="update_creditdebit">';
									} else {
										echo '<input type="hidden" name="action" class="creditdebit_action" value="create_creditdebit">';
									}
								?>

	                        </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
if(isset($_GET['id'])){
echo "<script language='javascript'>
  jQuery(document).ready(function (argument) { 
    duePaidCusCd('".$credit_debit['main_tab']->customer_id."');
  });
 
</script>";
}
?>