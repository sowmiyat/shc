<?php

$profile = get_profile1();


?>
<style type="text/css">

textarea:hover,textarea:focus{
	border-color: rgba(31,181,172,1.0) !important;
	border: 2px solid rgba(31,181,172,1.0) !important;
}
</style>
<div class="">
	<div class="">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Update Profile <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<form class="form-horizontal form-label-left profile_submit" id="create_profile">
						<input type="hidden" value="off" name="form_submit_prevent" class="profile_frm_pre" id="profile_frm_pre"/>
						<input type="hidden" value="<?php echo $profile ? $profile->id : '0';  ?>" name="profile_id" class="profile_id" id="profile_id"/>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Company Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="profile_company"  value="<?php echo $profile ? $profile->company_name : '';  ?>" name="profile_company"  class="form-control col-md-7 col-xs-12 customer_check" autocomplete="off" >
								
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Phone Mobile <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">  
								<input type="text" id="profile_mobile"  maxlength="10"  value="<?php echo $profile ? $profile->phone_number : '';  ?>" name="profile_mobile"  class="form-control col-md-7 col-xs-12 has-feedback-left" onkeypress="return isNumberKey(event)" autocomplete="off" >
								<span class="form-control-feedback left" aria-hidden="true" style="margin-top: 2px;">+91</span> 
								
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Address Line 1<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea id="profile_address" name="profile_address" class="form-control col-md-7 col-xs-12 address" style="border: 2px solid rgb(238, 238, 238);"  autocomplete="off"><?php echo $profile ? $profile->address : '';  ?></textarea>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Address Line 2<span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<textarea id="profile_address2" name="profile_address2" class="form-control col-md-7 col-xs-12 address" style="border: 2px solid rgb(238, 238, 238);" autocomplete="off"><?php echo $profile ? $profile->address2 : '';  ?></textarea>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">GST Number <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" id="profile_gst_number" name="profile_gst_number" maxlength="15"  value="<?php echo $profile ? $profile->gst_number : '';  ?>" class="form-control col-md-7 col-xs-12" autocomplete="off"  >
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
								<button type="submit" class="btn btn-success submit_profile">Update</button>
							  	<button class="btn btn-primary reset_button_profile" type="reset">Reset</button>
								<input type="hidden" name="action" class="profile_action" value="update_profile">
	                        </div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
