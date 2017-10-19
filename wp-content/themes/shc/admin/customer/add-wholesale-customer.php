<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button {
     -webkit-appearance: none;
     }
</style>

<?php
	$wholesale_customer = false;
	if(isset($_GET['id']) && $wholesale_customer = get_wholesale_customer($_GET['id']) ) {
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
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Customer Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="customer_name" name="customer_name" class="form-control col-md-7 col-xs-12 wholesale_cus" autocomplete="off" value="<?php echo ($wholesale_customer) ? $wholesale_customer->customer_name : ''; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Company Name
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="company_name" name="company_name" class="form-control col-md-7 col-xs-12 wholesale_company" autocomplete="off" value="<?php echo ($wholesale_customer) ? $wholesale_customer->company_name : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Mobile <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="tel" id="mobile" name="mobile" required="required" class="form-control col-md-7 col-xs-12 mobile_check_wholesale" autocomplete="off" value="<?php echo ($wholesale_customer) ? $wholesale_customer->mobile : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Address <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea id="address" name="address" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off"><?php echo ($wholesale_customer) ? $wholesale_customer->address : ''; ?></textarea>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">GST Number <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="number" id="gst_number" name="gst_number" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off" value="<?php echo ($wholesale_customer) ? $wholesale_customer->gst_number : ''; ?>">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success">Submit</button>
	                          	<button class="btn btn-primary" type="button"  onclick="window.location = '<?php echo admin_url('admin.php?page=wholesale_customer'); ?>';">Cancel</button>
							  	<button class="btn btn-primary" type="reset">Reset</button>
	                          
	                          	<?php 
									if( $wholesale_customer) {
										echo '<input type="hidden" name="action" class="customer_action" value="update_wholesale_customer">';
										echo '<input type="hidden" name="customer_id" value="'.$_GET['id'].'">';
									} else {
										echo '<input type="hidden" name="action" class="customer_action" value="create_wholesale_customer">';
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
var inputEl = document.getElementById('mobile');
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
jQuery("#customer_name").on('change',function(){
	var alphanumers = /^[a-zA-Z0-9]+$/;
	if(!alphanumers.test(jQuery("#customer_name").val())){
    	alert("name can have only alphabets and numbers.");
    	jQuery("#customer_name").val('');
	}

});

jQuery("#company_name").on('change',function(){
	var alphanumers = /^[a-zA-Z0-9]+$/;
	if(!alphanumers.test(jQuery("#company_name").val())){
    	alert("name can have only alphabets and numbers.");
    	jQuery("#company_name").val('');
	}

});

var filterInput = function(val) {
  return (goodKey.indexOf(val) > -1);
}

/* This function save the character typed */
var res = function(e) {
  key = (typeof e.which == "number") ? e.which : e.keyCode;
}

inputEl.addEventListener('input', checkInputTel);
inputEl.addEventListener('keypress', res);
</script>