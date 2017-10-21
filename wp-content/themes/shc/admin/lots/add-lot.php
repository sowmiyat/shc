<style>
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button {
     -webkit-appearance: none;
     }
 #cgst{
 	    width: 100%;
 }
</style>


<?php
$lot = false;
if(isset($_GET['id']) && $lot = get_lot($_GET['id']) ) {
	$lot_id = $_GET['id'];
}
?>
<div class="container">

	<div class="row" style="margin-top:20px;">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Add New Lot <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" id="create_lot">
						<input type="hidden" id="lot_no" name="lot_no" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off" value="<?php echo ($lot) ? $lot->lot_no : '0'; ?>">





						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Brand Name <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="brand_name" name="brand_name" required="required" class="form-control col-md-7 col-xs-12 unique_brand" autocomplete="off" value="<?php echo ($lot) ? $lot->brand_name : ''; ?>">
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Product Name <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="product_name" name="product_name" required="required" class="form-control col-md-7 col-xs-12 unique_product"  autocomplete="off" value="<?php echo ($lot) ? $lot->product_name : ''; ?>">
									</div>
								</div>
							</div>
						</div>

						<div class="divider-dashed"></div>

						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Selling Price<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="selling_price" name="selling_price"  required="required" class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($lot) ? $lot->selling_price : ''; ?>">
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Purchase Price <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="purchase_price" name="purchase_price"  required="required" class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKey(event)" autocomplete="off" value="<?php echo ($lot) ? $lot->purchase_price : ''; ?>">
									</div>
								</div>
							</div>
						</div>

						<div class="divider-dashed"></div>

						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">CGST %<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select id="cgst" name="cgst">
											<option value="0.00" <?php if(($lot->cgst) == '0.00'){ echo 'selected'; } ?>>0.00%</option>
											<option value="2.50" <?php if(($lot->cgst) == '2.50'){ echo 'selected'; } ?>>2.50%</option>
											<option value="6.00" <?php if(($lot->cgst) == '6.00'){ echo 'selected'; } ?>>6.00%</option>
											<option value="9.00" <?php if(($lot->cgst) == '9.00'){ echo 'selected'; } ?>>9.00%</option>
											<option value="14.00" <?php if(($lot->cgst) == '14.00'){ echo 'selected'; } ?>>14.00%</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">SGST % <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="sgst" name="sgst" required="required" class="form-control col-md-7 col-xs-12" readonly  value="<?php echo ($lot) ? $lot->sgst : '0.00'; ?>">
									</div>
								</div>
							</div>
						</div>

						<div class="divider-dashed"></div>



						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">HSN Code <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="hsn" name="hsn" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off"  onkeypress="return isNumberKey(event)" value="<?php echo ($lot) ? $lot->hsn : ''; ?>">
									</div>
								</div>
							</div>
						</div>

						
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div style="text-align:center;">
								<button type="submit" class="btn btn-success sub_form">Submit</button>
	                          	<button class="btn btn-primary" type="button" onclick="window.location = '<?php echo admin_url('admin.php?page=list_lots'); ?>';">Cancel</button>
							  	<button class="btn btn-primary reset_button" type="reset" >Reset</button>
	                          	
								<?php 
									if(  $lot ) {
										echo '<input type="hidden" name="lot_id" value="'.$lot_id.'">';
										echo '<input type="hidden" name="action" class="lot_action" value="update_lot">';
									} else {
										echo '<input type="hidden" name="action" class="lot_action" value="create_lot">';
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


