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
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Mobile <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="tel" id="tel" name="mobile" required="required" class="form-control col-md-7 col-xs-12 mobile_check" autocomplete="off" value="<?php echo ($customer) ? $customer->mobile : ''; ?>">
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
	var alphanumers = /^[a-zA-Z0-9]+$/;
	if(!alphanumers.test(jQuery("#name").val())){
    	alert("name can have only alphabets and numbers.");
    	jQuery("#name").val('');
	}

});

jQuery("#address").on('change',function(){
	var alphanumers = /^[a-zA-Z0-9]+$/;
	if(!alphanumers.test(jQuery("#address").val())){
    	alert("name can have only alphabets and numbers.");
    	jQuery("#address").val('');
	}

});




var inputEl = document.getElementById('tel');
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
</script>
