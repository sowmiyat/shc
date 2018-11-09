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
<div class="">
	<div class="" style="margin-top:20px;">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Add New Lot <small>cessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left lot_submit" id="create_lot">
						<input type="hidden" value="off" name="form_submit_prevent" class="form_submit_prevent_lot" id="form_submit_prevent_lot"/>
						<input type="hidden" id="lot_no" name="lot_no" class="form-control col-md-7 col-xs-12 lot_no" autocomplete="off" value="<?php echo ($lot) ? $lot->lot_no : '0'; ?>">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Brand Name <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="brand_name" name="brand_name" class="form-control col-md-7 col-xs-12 unique_brand" autocomplete="off" value="<?php echo ($lot) ? $lot->brand_name : ''; ?>">
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Product Name <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="product_name" name="product_name"  class="form-control col-md-7 col-xs-12 unique_product" autocomplete="off" value="<?php echo ($lot) ? $lot->product_name : ''; ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="divider-dashed"></div>

						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">MRP<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="mrp" name="mrp"  class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKeyWithDot(event)" autocomplete="off" value="<?php echo ($lot) ? $lot->mrp : ''; ?>">
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Selling Price<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="selling_price" name="selling_price"  class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKeyWithDot(event)" autocomplete="off" value="<?php echo ($lot) ? $lot->selling_price : ''; ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Wholesale Price<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="wholesale_price" name="wholesale_price"  class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKeyWithDot(event)" autocomplete="off" value="<?php echo ($lot) ? $lot->wholesale_price : ''; ?>">
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Purchase Price <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="purchase_price" name="purchase_price"  class="form-control col-md-7 col-xs-12" onkeypress="return isNumberKeyWithDot(event)" autocomplete="off" value="<?php echo ($lot) ? $lot->purchase_price : ''; ?>">
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
										<input type="text" id="hsn" name="hsn" class="form-control col-md-7 col-xs-12" autocomplete="off"  onkeypress="return isNumberKey(event)" value="<?php echo ($lot) ? $lot->hsn : ''; ?>">
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">GST %<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<select id="gst_percentage" name="gst_percentage">
											<option value="0.00" <?php if(($lot->gst_percentage) == '0.00'){ echo 'selected'; } ?>>0.00%</option>
											<option value="5.00" <?php if(($lot->gst_percentage) == '5.00'){ echo 'selected'; } ?>>5.00%</option>
											<option value="12.00" <?php if(($lot->gst_percentage) == '12.00'){ echo 'selected'; } ?>>12.00%</option>
											<option value="18.00" <?php if(($lot->gst_percentage) == '18.00'){ echo 'selected'; } ?>>18.00%</option>
											<option value="28.00" <?php if(($lot->gst_percentage) == '28.00'){ echo 'selected'; } ?>>28.00%</option>
										</select>
									</div>
								</div>
							</div>

							<!-- <div class="col-md-6 col-sm-6 col-xs-12">
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
							</div> -->
						
							
						</div>						
						<div class="divider-dashed"></div>

						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">CESS<span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="radio" name="cess" value="no" class="cess" <?php if(($lot)){ if(($lot->cess) == 'no'){ echo 'checked'; } }  else{
											echo 'checked';
										} ?> >No  
										<input type="radio" name="cess" value="yes" class="cess" <?php if(($lot->cess) == 'yes'){ echo 'checked'; } ?>>Yes
										<input type="hidden" name="cess_percentage" class="cess_percentage" value="<?php echo ($lot)? $lot->cess_percentage : '0.00'; ?>">
										<!-- <input type="text" id="sgst" name="sgst" required="required" tabindex="-1" class="form-control col-md-7 col-xs-12" readonly  value="<?php echo ($lot) ? $lot->sgst : '0.00'; ?>"> -->
									</div>
								</div>
							</div>	
							<div class="col-md-6 col-sm-6 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-6 col-sm-6 col-xs-12" for="first-name">Stock Alert (Pieces) <span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="text" id="stock_alert" name="stock_alert" class="form-control col-md-7 col-xs-12" autocomplete="off"  onkeypress="return isNumberKey(event)" value="<?php echo ($lot) ? $lot->stock_alert : ''; ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div style="text-align:center;">
								<button type="submit" class="btn btn-success">Submit</button>
	                          	<button class="btn btn-primary cancel_button" type="button" onclick="window.location = '<?php echo admin_url('admin.php?page=list_lots'); ?>';">Cancel</button>
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


