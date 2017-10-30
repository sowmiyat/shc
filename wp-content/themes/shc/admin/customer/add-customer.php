<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button {
     -webkit-appearance: none;
     }
</style>

<?php
	$customer = false;
	if(isset($_GET['id']) && $customer = get_customer($_GET['id']) ) {
		$user_id = $_GET['user_id'];
	}
?>
<div class="container">

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Add New Customer <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left retail_submit" id="create_customer">
						<div class="form-group">
						<input type="hidden" value="off" name="form_submit_prevent" class="form_submit_prevent_customer" id="form_submit_prevent_customer"/>

							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Name 
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12 customer_check" autocomplete="off" value="<?php echo ($customer) ? $customer->name : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Primary Mobile <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="mobile" maxlength="10"  name="mobile"  class="form-control col-md-7 col-xs-12 has-feedback-left" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($customer) ? $customer->mobile : ''; ?>">
								<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+91</span>
								<input type="hidden" class="customer_id" value="<?php echo ($customer) ? $customer->id : '0'; ?>"/>
								<input type="hidden" class="unique_mobile_action" value="check_unique_mobile"/>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Secondary Mobile 
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="secondarymobile" maxlength="10" name="secondary_mobile" class="form-control col-md-7 col-xs-12 has-feedback-left" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($customer) ? $customer->secondary_mobile : ''; ?>">
								<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">0</span>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Landline 
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="landline" name="landline" maxlength="8" class="form-control col-md-7 has-feedback-left col-xs-12" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($customer) ? $customer->landline : ''; ?>">
								<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">044</span>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Address 
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea id="address" name="address" class="form-control col-md-7 col-xs-12" autocomplete="off"><?php echo ($customer) ? $customer->address : ''; ?></textarea>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success submit_form1">Submit</button>
	                          	<button class="btn btn-primary" type="button"  onclick="window.location = '<?php echo admin_url('admin.php?page=customer_list'); ?>';">Cancel</button>
							  	<button class="btn btn-primary reset_button_cus" type="reset">Reset</button>
	                          	
	                          	<?php 
									if( $customer) {
										echo '<input type="hidden" name="action" class="customer_action" value="update_customer">';
										echo '<input type="hidden" name="customer_id" value="'.$_GET['id'].'">';
									} else {
										echo '<input type="hidden" name="action" class="customer_action" value="create_customer">';
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

