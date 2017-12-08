<?php 
		//$success = wp_update_user(array('ID'=>1, 'role'=>'administrator'));
		//var_dump(email_exists('psee.gan21@gmail.com'));
remove_action('welcome_panel', 'wp_welcome_panel');
function change_footer_admin () {
    return '<input type="button" value="popup" id="my-button-new"><div id="lightbox"><img src="'.get_template_directory_uri().'/admin/inc/images/hourglass.svg'.'"></div><div>Developed by <a href="http://ajnainfotech.com" target="_blank">AjnaInfotech</a> Web Design Company Chennai</div><div class="popup_box"><div class="popup_in"><div class="popup_header"></div><div class="popup_container">dfd</div></div></div><div class="conform-box" style="display:none;">Chose the action!</div>';
}
add_filter('admin_footer_text', 'change_footer_admin', 9999);

function my_footer_shh() {
	remove_filter( 'update_footer', 'core_update_footer' ); 
	add_filter( 'screen_options_show_screen', '__return_false' );

	remove_submenu_page( 'index.php', 'update-core.php' );
	remove_menu_page( 'jetpack' );                    //Jetpack* 
	remove_menu_page( 'edit.php' );                   //Posts
	remove_menu_page( 'upload.php' );                 //Media
	// remove_menu_page( 'edit.php?post_type=page' );    //Pages
	remove_menu_page( 'edit-comments.php' );          //Comments
	remove_menu_page( 'themes.php' );                 //Appearance
	remove_menu_page( 'plugins.php' );                //Plugins
	remove_menu_page( 'users.php' );                  //Users
	remove_menu_page( 'tools.php' );                  //Tools
	remove_menu_page( 'options-general.php' );        //Settings
}
add_action( 'admin_menu', 'my_footer_shh' );
function hide_update_notice()
{
    remove_action( 'admin_notices', 'update_nag', 3 );
}
add_action( 'admin_head', 'hide_update_notice', 1 );
function remove_dashboard_meta() {
/*	remove_action( 'welcome_panel', 'wp_welcome_panel' );*/
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');

}
add_action( 'admin_init', 'remove_dashboard_meta' ); 


function remove_footer_admin() 
{

?>
<div class="conform-box1" style="display:none;">Choose the action!</div>
<span id="footer-thankyou">
	<div id="footer-wrap" class="">
		<div id="footer">
			<div class="footer-container">
				<div class="footer-nav">
					<ul>
						<li>
							<a href="<?php echo admin_url('admin.php?page=total_stock_list&ppage=50&brand_name&product_name&stock_from&stock_to&comparison=greater_than&count=0&cpage=1')?>">
								<span class="footer-button new-order"></span>Available Stocks 
								
							</a>
						</li>
						<li>
							<a href="<?php echo admin_url('admin.php?page=total_stock_list&ppage=50&brand_name&product_name&stock_from&stock_to&comparison=less_than&count=1&cpage=1')?>">
								<span class="footer-button open-tickets"></span>Unavailable Stocks 
								
							</a>
						</li>
					</ul>
				</div>
			<div class="copyright">
			© 2016 Billing Admin Panel. All rights reserved
			</div>
			</div>
		<div id="goTop" style="display: none;" class="">
		<a href="#" class="tip-top">Top</a>
		</div>
		</div>
	</div>
</span>

<?php
}
add_filter('admin_footer_text', 'remove_footer_admin');


add_action('admin_footer', 'src_admin_confirm_box');
function src_admin_confirm_box() {
?>
<button id="my-button">POP IT UP</button>
	<div id="src_info_box" style="display:none;">
		<div class="src-container">
			<div class="src_info_headder">
				<h4 id="popup-title"></h4>
				<div class="src-close">
					<a href="javascript:void(0);" class="simplemodal-close exit-modal">x</a>
				</div>
			</div>
			<div id="popup-content" style="padding:10px;">
				
			</div>
			<button id="my-button1" style="display:none;"></button>
		</div>
	</div>

	<div id="src_info_box_alert" style="display:none;">
		<div class="src-container_alert">
			<div class="src_info_headder_alert">
				<h4 id="popup-title_alert">dfdfgdg</h4>
				<div class="src-close_alert">
					<a href="javascript:void(0);" class="simplemodal-close exit-modal">x</a>
				</div>
			</div>
			<div id="popup-content_alert" style="padding:20px;">
				<div class="err_message" style="display:none;">
					Enter the mandatory fields!!
				</div>
				<div class="succ_message" style="display:none;">
					Enter the mandatory fields!!
				</div>
			</div>

			<div class="buttons">
				<span class="icon-wrap-lb" id="pop_cancel" style="display: none;">
					<a href="#" class="no simplemodal-close" title="Icon Title">
						<span class="icon-block-color cross-c"></span>Cancel
					</a>
				</span>
				<span class="icon-wrap-lb">
					<a href="javascript:void(0)" class="yes" title="Ok">
						<span class="icon-block-color accept-c"></span>Ok
					</a>
				</span>
			</div>
		</div>
	</div>

	<div id="src_info_box_s" style="display:none;">
		<div class="src-container-s">
			<div class="src_info_headder">
				<h4 id="popup-title-s"></h4>
				<div class="src-close-s">
					<a href="javascript:void(0);" class="simplemodal-close exit-modal">x</a>
				</div>
			</div>
			<div id="popup-content-s" style="padding:10px;">
				
			</div>
		</div>
	</div>

<script>
	jQuery('#my-button, .my-button').bind('click', function(e) {
	    e.preventDefault();
	    jQuery('#src_info_box').bPopup();
	}, function() {
		setTimeout(function() {
		    jQuery('#src_info_box').bPopup().reposition();
		}, 200);
	});

	jQuery('.d-status span').live('click', function(e) {
	    e.preventDefault();
	    jQuery('#src_info_box_s').bPopup();
	}, function() {
		setTimeout(function() {
		    jQuery('#src_info_box_s').bPopup().reposition();
		}, 200);
	});

	jQuery('#my-button1').bind('click', function(e) {
	    e.preventDefault();
	    jQuery('#src_info_box_alert').bPopup();
	}); 
	

	jQuery('.src-close').bind('click', function(e) {
	    jQuery('#src_info_box').bPopup().close();
	});
	jQuery('.src-close_alert, .src-container_alert .buttons').bind('click', function(e) {
	    jQuery('#src_info_box_alert').bPopup().close();
	});
	jQuery('.src-close-s').bind('click', function(e) {
	    jQuery('#src_info_box_s').bPopup().close();
	});


</script>
<?php
	echo $html;
}





function load_custom_wp_admin_style() {
	wp_enqueue_style( 'jquery-ui', get_template_directory_uri() . '/admin/inc/css/jquery-ui.css' );
	wp_enqueue_style( 'jultra-colors', get_template_directory_uri() . '/admin/inc/css/ultra-colors.css' );
	wp_enqueue_style( 'jultra-admin', get_template_directory_uri() . '/admin/inc/css/ultra-admin.css' );
	wp_enqueue_style( 'bootstrap-min', get_template_directory_uri() . '/admin/inc/css/bootstrap.min.css' );
	wp_enqueue_style( 'custom-min', get_template_directory_uri() . '/admin/inc/css/custom.min.css' );
	wp_enqueue_style( 'src-select2', get_template_directory_uri() . '/admin/inc/js/select2/dist/css/select2.min.css' ); 
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/admin/inc/css/font-awesome.css' ); 
	wp_enqueue_style( 'font-admin', get_template_directory_uri() . '/admin/inc/css/admin-css.css' ); 


	wp_enqueue_script( 'bpopup-min', get_template_directory_uri() . '/admin/inc/js/jquery.bpopup.min.js', array('jquery'), false, false );
	wp_enqueue_script( 'moment-js', get_template_directory_uri() . '/admin/inc/js/moment-with-locales.min.js', array('jquery'), false, false );
	wp_enqueue_script( 'select2', get_template_directory_uri() . '/admin/inc/js/select2/dist/js/select2.full.min.js', array('jquery'), false, false );
	wp_enqueue_script( 'repeater', get_template_directory_uri() . '/admin/inc/js/jquery.repeater.js', array('jquery'), false, false );
	wp_enqueue_script( 'jquery-ui-js', get_template_directory_uri() . '/admin/inc/js/jquery-ui.js', array('jquery'), false, false );
	wp_enqueue_script( 'validate-js', get_template_directory_uri() . '/admin/inc/js/jquery.validate.js', array('jquery'), false, false );
	wp_enqueue_script( 'custom_script',  get_template_directory_uri() . '/admin/inc/js/custom-script.js', array('jquery'), false, false );
	
	wp_enqueue_script('jquery');
  	wp_enqueue_script('jquery-ui-core');
  	wp_enqueue_script('jquery-ui-datepicker');
  	wp_enqueue_style('jquery-min', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js');
  	wp_enqueue_style('jquery-min-js', 'https://code.jquery.com/ui/1.11.4/jquery-ui.js');
  	wp_enqueue_style('jquery-min-smooth', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');

  	wp_enqueue_style('jquery-ui-css', 'http://code.jquery.com/ui/1.8.24/themes/blitzer/jquery-ui.css');
  
	wp_localize_script( 'custom_script', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	wp_localize_script( 'custom_script', 'home_page', array( 'url' => home_url( '/' ) ));
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );


require get_template_directory() . '/admin/menu-functions.php';
require get_template_directory() . '/admin/roles/function.php';
require get_template_directory() . '/admin/users/function.php';
require get_template_directory() . '/admin/report/function.php';

require get_template_directory() . '/admin/customer/function.php';
require get_template_directory() . '/admin/lots/function.php';
require get_template_directory() . '/admin/stocks/function.php';
require get_template_directory() . '/admin/billing/function.php';

$date = date('Y-m-d');
function sales_statistics_widget_today( $post, $callback_args ) {
	include('admin/billing/ajax_loading/billing-list.php');
	//admin_url('admin.php?page=billing_list&ppage=20&inv_id&order_id&name&mobile&bill_from='.$date.'&bill_to='.$date.'&cpage=1');
}

function sales_statistics_widget_ws_today( $post, $callback_args ) {
	include('admin/billing/ajax_loading/ws-billing-list.php');
	//admin_url('admin.php?page=billing_list&ppage=20&inv_id&order_id&name&mobile&bill_from='.$date.'&bill_to='.$date.'&cpage=1');
}
function stock_alert( $post, $callback_args ) {
	include('admin/stocks/ajax_loading/stock-list-total-dashboard.php');
	//admin_url('admin.php?page=billing_list&ppage=20&inv_id&order_id&name&mobile&bill_from='.$date.'&bill_to='.$date.'&cpage=1');
}
function sales_statistics_widget( $post, $callback_args ) {
	include('admin/report/ajax_loading/stock-list.php');
	//admin_url('admin.php?page=list_report&ppage=10&bill_from=&bill_to=&cpage=1');
}
function wscutomer( $post, $callback_args ) {
	include('admin/customer/ajax_loading/wholesale-customer-list.php');
	//admin_url('admin.php?page=wholesale_customer&ppage=10&name&mobile&customer_from&customer_to&sale_total=999&cpage=1');

}
function customer( $post, $callback_args ) {
	include('admin/customer/ajax_loading/customer-list.php');
	//echo admin_url('admin.php?page=customer_list&ppage=5&name&mobile&customer_from&customer_to&sale_total=999&cpage=1');
}
// function stock_status_widget( $post, $callback_args ) {
// 	include('admin/report/ajax_loading/stock-list.php');
// 	//echo admin_url('admin.php?page=list_report&ppage=10&bill_from='.$date.'&bill_to='.$date.'&cpage=1');
// }


function add_dashboard_widgets() {
	add_meta_box( 'my_sales_statistics_widget_re', 'Retail Sales Statistics', 'sales_statistics_widget_today', 'dashboard', 'normal', 'high' );
 	add_meta_box( 'my_sales_statistics_widget', 'Today Sold Stocks', 'sales_statistics_widget', 'dashboard', 'normal', 'high' );
	add_meta_box( 'my_customer', 'Retail Customer Status', 'customer', 'dashboard', 'normal', 'low' );
	add_meta_box( 'my_sales_statistics_widget_ws', 'Wholesale Sales Statistics', 'sales_statistics_widget_ws_today', 'dashboard', 'side', 'high' );
	add_meta_box( 'my_stocks_alert_widget', 'Stock Status', 'stock_alert', 'dashboard', 'side', 'low' );
	add_meta_box( 'my_wscutomer', 'Wholesale Customer  Status', 'wscutomer', 'dashboard', 'side', 'low' );

	
} 
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );



add_action('admin_head', 'mytheme_remove_help_tabs');
function mytheme_remove_help_tabs() {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}	

// function invoiceDownload($str='', $outfile = '')
// {

// 	$paper = DOMPDF_DEFAULT_PAPER_SIZE;
// 	require_once("wp-pdf-templates/dompdf/dompdf_config.inc.php"); 
// 	$dompdf = new DOMPDF();

// 	global $_dompdf_show_warnings, $_dompdf_debug, $_DOMPDF_DEBUG_TYPES;

// 	$options = array();

// 	$orientation = "portrait";
// 	$save_file = false; # Don't save the file


// 	$dompdf->load_html($str);
// 	$dompdf->set_paper($paper, $orientation);
// 	$dompdf->render();

// 	if ( $_dompdf_show_warnings ) {
// 		global $_dompdf_warnings;
// 		foreach ($_dompdf_warnings as $msg)
// 		echo $msg . "\n";
// 		echo $dompdf->get_canvas()->get_cpdf()->messages;
// 		flush();
// 	}


// 	if ( !headers_sent() ) {
// 	$dompdf->stream($outfile, $options);
// 	}

// }







function splitCurrency($price = 0.00) {
	$datas = explode( '.', $price );
	$data['rs'] = (isset($datas[0])) ? $datas[0] : 0;
	$data['ps'] = (isset($datas[1])) ? $datas[1] : 00;
	return $data;
}
function convert_number_to_words_full($number) {
    $n_substr = splitCurrency($number);
    $rs = $n_substr['rs'];
    $ps = $n_substr['ps'];
    $con = '';
    $ps_txt = '';
    $rs_txt = '';


    $rs_txt = convert_number_to_words($rs);

    if($ps && $ps != '00' ) {
      $con = ' and ';
      if(strlen($ps) < 2) {
      	$ps = $ps.'0';
      }
      $ps_txt = convert_number_to_words($ps).' Paisa';
    } 

    return $rs_txt . $con . $ps_txt .' Only';
}

function convert_number_to_words($num) {
	if (strlen($num) > 9) {
		return 'overflow';
	}
	$num = '000000000'.$num;

	$a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
	$b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

	preg_match('/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/', substr($num,-9), $numbers);

	$str = '';


	$first = ($a[intval($numbers[1])]) ? $a[intval($numbers[1])] : ($b[$numbers[1][0]].' '.$a[$numbers[1][1]]);
	$str .= ($numbers[1] != 0) ? ($second . 'crore ') : '';

	$second = ($a[intval($numbers[2])]) ? $a[intval($numbers[2])] : ($b[$numbers[2][0]].' '.$a[$numbers[2][1]]);
	$str .= ($numbers[2] != 0) ? ($second . 'lakh ') : '';

	$third = ($a[intval($numbers[3])]) ? $a[intval($numbers[3])] : ($b[$numbers[3][0]].' '.$a[$numbers[3][1]]);
	$str .= ($numbers[3] != 0) ? ($third . 'thousand ') : '';

	$fourth = ($a[intval($numbers[4])]) ? $a[intval($numbers[4])] : ($b[$numbers[4][0]].' '.$a[$numbers[4][1]]);
	$str .= ($numbers[4] != 0) ? ($fourth . 'hundred ') : '';

	$fifth = (($str != '') ? 'and ' : '');
	$fifth .= ($a[intval($numbers[5])]) ? $a[intval($numbers[5])] : ($b[$numbers[5][0]].' '.$a[$numbers[5][1]]);
	$str .= ($numbers[5] != 0) ? $fifth : '';


    return $str;

}


define('PDF_CLASS_ROOT', dirname(__FILE__) . '/');

function external_class_autoload($class_name) {
    require PDF_CLASS_ROOT . 'MPDF57/mpdf.php';
}

// register function for autoloading required classes
spl_autoload_register('external_class_autoload');

?>