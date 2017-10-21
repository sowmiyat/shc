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
					<form class="form-horizontal form-label-left" id="create_customer">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="name" name="name" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off" value="<?php echo ($customer) ? $customer->name : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Primary Mobile <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="mobile" name="mobile" required="required" class="form-control col-md-7 col-xs-12 has-feedback-left" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($wholesale_customer) ? $wholesale_customer->mobile : ''; ?>">
								<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+91</span> 
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Secondary Mobile <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="secondary mobile" name="secondary mobile" required="required" class="form-control col-md-7 col-xs-12 has-feedback-left" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($wholesale_customer) ? $wholesale_customer->secondary_mobile : ''; ?>">
								<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">0</span>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Landline <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="landline" name="landline" required="required" class="form-control col-md-7 has-feedback-left col-xs-12" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($wholesale_customer) ? $wholesale_customer->landline : ''; ?>">
								<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">044</span>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Address <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea id="address" name="address" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off"><?php echo ($customer) ? $customer->address : ''; ?></textarea>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success submit_form">Submit</button>
	                          	<button class="btn btn-primary" type="button"  onclick="window.location = '<?php echo admin_url('admin.php?page=customer_list'); ?>';">Cancel</button>
							  	<button class="btn btn-primary" type="reset">Reset</button>
	                          	
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


<script>


jQuery("#name").on('change',function(){
	var alphanumers = /^[a-zA-Z0-9 ]+$/;
	if(!alphanumers.test(jQuery("#name").val())){
    	alert("name can have only alphabets and numbers.");
    	jQuery("#name").val('');
	}

});

</script>
