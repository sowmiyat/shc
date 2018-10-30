<?php

$netbank = get_netbank1();


?>
<div class="">
	<div class="">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Fund Transfer Info <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<form class="form-horizontal form-label-left netbank_submit" id="create_netbank">
						<input type="hidden" value="off" name="form_submit_prevent" class="netbank_frm_pre" id="netbank_frm_pre"/>
						<input type="hidden" value="<?php echo $netbank ? $netbank->id : '0';  ?>" name="netbank_id" class="netbank_id" id="netbank_id"/>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="nb_shop"  value="<?php echo $netbank ? $netbank->shop_name : '';  ?>" name="nb_shop"  class="form-control col-md-7 col-xs-12 customer_check" autocomplete="off" >
								
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Bank Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">  
								<input type="text" id="nb_bank"  value="<?php echo $netbank ? $netbank->bank : '';  ?>" name="nb_bank"  class="form-control col-md-7 col-xs-12 customer_check " autocomplete="off" >
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Account Number<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="nb_account"  value="<?php echo $netbank ? $netbank->account : '';  ?>" name="nb_account" maxlength="20"  onkeypress="return isNumberKey(event)" class="form-control col-md-7 col-xs-12 customer_check length" autocomplete="off" >

							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">IFSC Code<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="nb_ifsc"  value="<?php echo $netbank ? $netbank->ifsc : '';  ?>" name="nb_ifsc"  class="form-control col-md-7 col-xs-12" autocomplete="off" >
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Account Type <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="nb_account_type" name="nb_account_type"  value="<?php echo $netbank ? $netbank->account_type : '';  ?>" class="form-control col-md-7 col-xs-12 customer_check" autocomplete="off"  >
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Branch <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="nb_branch" name="nb_branch" value="<?php echo $netbank ? $netbank->branch : '';  ?>" class="form-control col-md-7 col-xs-12 customer_check" autocomplete="off"  >
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success submit_netbank">Update</button>
							  	<button class="btn btn-primary reset_button_netbank" type="reset">Reset</button>
								<input type="hidden" name="action" class="netbank_action" value="update_netbank">
	                        </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
