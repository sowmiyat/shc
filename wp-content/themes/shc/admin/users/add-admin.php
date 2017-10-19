<?php
$editable_roles = get_editable_roles();
unset($editable_roles['administrator']);
unset($editable_roles['editor']);
unset($editable_roles['author']);
unset($editable_roles['contributor']);
unset($editable_roles['subscriber']);
unset($editable_roles['customer']);
unset($editable_roles['employee']);

$user = false;
$current_role = array();
if(isset($_GET['user_id']) && $user = get_userdata($_GET['user_id']) ) {
	$user_id = $_GET['user_id'];
	$current_role = implode(', ', $user->roles);
}
?>

<div class="container">

	<div class="row">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Add New Admin <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" id="create_user">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Name (Login Name) <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="user_name" id="user_name" required="required" <?php echo ($user) ? 'readonly' : ''; ?> value="<?php echo ($user) ? $user->data->user_login : '';  ?>" class="form-control col-md-7 col-xs-12">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Password <span class="required"><?php echo ($user) ? '' : '*';  ?></span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="password" id="password" class="form-control col-md-7 col-xs-12" <?php echo ($user) ? '' : 'required=required';  ?>>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Mobile <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="mobile" id="mobile" required="required" value="<?php echo get_user_meta($user->data->ID, 'mobile', true ); ?>" class="form-control col-md-7 col-xs-12">
							</div>
						</div>

						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Email <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="email" id="email" value="<?php echo ($user) ? $user->data->user_email : '';  ?>" class="form-control col-md-7 col-xs-12">
							</div>
						</div>


						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Role <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="role" data-placeholder="Choose a Role..." class="chosen-select" style="width:350px;" tabindex="2" class="form-control col-md-7 col-xs-12">
									<?php
									$selected = '';
									echo '<option value="0">No Role</option>';
									foreach ($editable_roles as $key => $role_value) {
										if($user && $current_role === $key) {
											$selected = 'selected';
										} else {
											$selected = '';
										}
										echo '<option '.$selected.'  value="'.$key.'">'.$role_value['name'].'</option>';
									}
									?>
								</select>
							</div>
						</div>


						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
	                          	<button class="btn btn-primary" type="button">Cancel</button>
							  	<button class="btn btn-primary" type="reset">Reset</button>
	                          	<button type="submit" class="btn btn-success">Submit</button>

								<?php 
									if(  $user ) {
										echo '<input type="hidden" name="user_id" value="'.$user_id.'">';
										echo '<input type="hidden" name="action" class="user_action" value="update_admin_user">';
									} else {
										echo '<input type="hidden" name="action" class="user_action" value="create_admin_user">';
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