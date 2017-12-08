<?php
global $src_capabilities;

$current_cap = '';
if(isset($_GET['role_slg'])) {
	$current_cap = get_role($_GET['role_slg']);
	$editable_roles = get_editable_roles();
}
?>



<div class="container">
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">
			<div class="x_panel">
				<div class="x_title">
					<h2>Add New Role <small>Sessions</small></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left role_submit" id="create_role">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Role Name <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="role_name" id="role_name"  <?php echo ( isset($_GET['role_slg']) ) ? 'readonly' : '' ?> value="<?php echo ( isset($_GET['role_slg']) ) ? $editable_roles[$current_cap->name]['name'] : '' ?>" class="form-control col-md-7 col-xs-12">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Role Slug <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<input type="text" name="role_slug" id="role_slug" <?php echo ( isset($_GET['role_slg']) ) ? 'readonly' : '' ?> value="<?php echo $current_cap->name ?>" class="form-control col-md-7 col-xs-12">
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Role Permission <span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								




								<ul class="permission_modules input-uniform">
									<?php
									foreach ($src_capabilities as $r_key => $r_value) {

										if(is_array($r_value)) {

											echo '<li class="main_menu" style="border-bottom: 1px solid #ddd;">';
												echo '<div class="checker">';
												echo '	<span class="main_span">';
												echo '		<input type="checkbox" name="main_menu[]" value="'.$r_key.'" style="opacity: 0;" class="main_role">';
												echo '	</span>';
												echo '</div>';
												echo '<strong>'.$r_value['name'].'</strong>';

											foreach ($r_value['data'] as $d_key => $d_value) {
												if( isset( $current_cap->capabilities[$d_key] ) && $current_cap->capabilities[$d_key] ) {
													$checked = 'checked';
												} else {
													$checked = '';
												}
												echo '<ul>';
												echo '	<li class="sub_menu_li">';
												echo '		<div class="checker">';
												echo '			<span class="'.$checked.'">';
												echo '				<input type="checkbox" name="main_menu[]" value="'.$d_key.'" style="opacity: 0;" class="sub_role" '.$checked.'>';
												echo '			</span>';
												echo '		</div>';
												echo 		$d_value;
												echo '	</li>';
												echo '</ul>';
											}
											echo '</li>';
										} else {

											if( isset( $current_cap->capabilities[$r_key] ) && $current_cap->capabilities[$r_key] ) {
												$checked = 'checked';
											} else {
												$checked = '';
											}
											echo '<li class="main_menu" style="border-bottom: 1px solid #ddd;">';
											echo '	<div class="checker">';
											echo '		<span class="'.$checked.'">';
											echo '			<input type="checkbox" name="main_menu[]" value="'.$r_key.'" style="opacity: 0;" class="main_role" '.$checked.'>';
											echo '		</span>';
											echo '	</div>';
											echo '	<strong>'.$r_value.'</strong>';
											echo '</li>';
										}
									}
									?>
								</ul>
							</div>
						</div>
						<div class="divider-dashed"></div>
						<div class="form-group">
							<div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-3">
	                          	
	                          	<button type="submit" class="btn btn-success">Submit</button>
							  	<button class="btn btn-primary role_reset" type="reset">Reset</button>
							  	<button class="btn btn-primary role_cancel" type="button">Cancel</button>
	                          
								<?php 
									if(isset( $_GET['role_slg']) && $edit_cap = $_GET['role_slg']) {
										echo '<input type="hidden" name="action" class="role_action" value="update_roles">';
									} else {
										echo '<input type="hidden" name="action" class="role_action" value="create_roles">';
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





















<script type="text/javascript">


jQuery(document).ready(function(){
	jQuery('.permission_modules li.main_menu').each(function(c, main) {
		jQuery(main).find('ul li.sub_menu_li .checker .checked input').each(function(c, sub) {

			sub_checked = jQuery(sub).prop( "checked" );
           	sub_check(sub, sub_checked)
		});
		
	});
})

	jQuery("#role_name").keyup(function(){
	    var Text = jQuery(this).val();
	    Text = slugify(Text);
	    jQuery("#role_slug").val(Text);
	});



	var main_checked, sub_checked, main_block;
	jQuery('.main_menu input').click(function(){
		if(jQuery(this).hasClass('main_role')) {
			main_checked = jQuery(this).prop( "checked" );
           	main_check(this, main_checked);
        }

		if(jQuery(this).hasClass('sub_role')) {
			sub_checked = jQuery(this).prop( "checked" );
           	sub_check(this, sub_checked);
        }
	});



function main_check(data, checked) {
   main_block = jQuery(data).parent().parent().parent();

   if(checked) {
   		jQuery(main_block).find('input:checkbox').attr('checked','checked');
		jQuery(data).parent().parent().find('span').addClass('checked');
   } else {
   		jQuery(main_block).find('input:checkbox').removeAttr('checked');
		jQuery(data).parent().parent().find('span').removeClass('checked');
   }

   jQuery( jQuery(main_block).find('ul li') ).each(function() {
   		if(checked){
   			jQuery(this).find('span').addClass('checked');
   		} else {
   			jQuery(this).find('span').removeClass('checked');
   		}
   		
   });
   
}

function sub_check(data, checked) {

	main_block = jQuery(data).parent().parent().parent().parent().parent();

    if(checked) {
		jQuery(data).parent().parent().find('span').addClass('checked');
  	} else {
		jQuery(data).parent().parent().find('span').removeClass('checked');
   	}


   	var is_checked = true;
   	jQuery(jQuery(main_block).find('ul li span')).each(function() {
		if( !jQuery(this).find('input:checkbox').prop( "checked" ) ) {
			is_checked = false;
		}
   	});


	if(is_checked) {
		jQuery(main_block).find('.main_span').addClass('checked');
		jQuery(main_block).find('.main_span input:checkbox').attr('checked','checked')
	} else {
		jQuery(main_block).find('.main_span').removeClass('checked');
		jQuery(main_block).find('.main_span input:checkbox').removeAttr('checked')
	}

}

/*	jQuery('.main_menu input').click(function(){
		console.log(jQuery(this).val());
	})*/
</script>