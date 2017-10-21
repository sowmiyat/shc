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
/*	remove_menu_page( 'users.php' );                  //Users*/
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


add_action('after_setup_theme', 'remove_admin_bar');
 
function remove_admin_bar() {

  show_admin_bar(false);

}

function remove_footer_admin() 
{

	global $wpdb;
  	$stock_table =  $wpdb->prefix.'shc_stock';
    $lots_table =  $wpdb->prefix.'shc_lots';
    $sale_details = $wpdb->prefix.'shc_sale_detail';
    $sale =$wpdb->prefix.'shc_sale';
	$query = "SELECT  count(*) from (SELECT count(*) as avl_stock from
(SELECT l.*, s.id as stock_id, s.stock_count, s.selling_total, s.created_at as stock_created FROM {$stock_table} as s JOIN {$lots_table} as l ON s.lot_number = l.id WHERE s.active = 1) as stock_table
LEFT JOIN 
(SELECT  t1.lot_number, 
( CASE 
WHEN t1.stock_qty IS NULL
THEN 0
ELSE t1.stock_qty
END ) as stock_qty,
( CASE 
WHEN t2.sale_qty IS NULL
THEN 0
ELSE t2.sale_qty
END ) as sale_qty,

(
    ( CASE 
    WHEN t1.stock_qty IS NULL
    THEN 0
    ELSE t1.stock_qty
    END )
    - 
    ( CASE 
    WHEN t2.sale_qty IS NULL
    THEN 0
    ELSE t2.sale_qty
    END )

) as bal_stock 

FROM 
(
SELECT st.lot_number, SUM(st.stock_count) as stock_qty FROM {$stock_table} as st WHERE st.active = 1 GROUP BY st.lot_number
) as t1
LEFT JOIN
(
SELECT sd.lot_id, SUM(sd.sale_unit) as sale_qty FROM {$sale_details} as sd JOIN {$sale} as s ON sd.sale_id = s.id WHERE sd.active = 1 AND s.active = 1 GROUP BY sd.lot_id
) as t2
ON t1.lot_number = t2.lot_id) as balance_table
ON stock_table.id = balance_table.lot_number where stock_table.active = 1 and balance_table.bal_stock>0 GROUP by `id`) as count_table";
$avalible_stock              = $wpdb->get_var( $query );

$lot_query = "SELECT COUNT(*) FROM {$lots_table} WHERE active = 1";
$lot_count              = $wpdb->get_var( $lot_query );


$unavalible_stock = $lot_count - $avalible_stock ;
?>
<div class="conform-box1" style="display:none;">Choose the action!</div>
<span id="footer-thankyou">
	<div id="footer-wrap" class="">
		<div id="footer">
			<div class="footer-container">
				<div class="footer-nav">
					<ul>
						<li>
							<a href="<?php echo admin_url('admin.php?page=total_stock_list')?>">
								<span class="footer-button new-order"></span>Available Stocks 
								<span class="ticket-counter"><?php echo $avalible_stock ?></span>
							</a>
						</li>
						<li>
							<a href="http://localhost/src/wp-admin/admin.php?page=total_stock_list">
								<span class="footer-button open-tickets"></span>Unavailable Stocks 
								<span class="task-counter"><?php echo $unavalible_stock ?></span>
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
	wp_enqueue_script( 'select2', get_template_directory_uri() . '/admin/inc/js/select2/dist/js/select2.full.min.js', array('jquery'), false, false );
	wp_enqueue_script( 'repeater', get_template_directory_uri() . '/admin/inc/js/jquery.repeater.js', array('jquery'), false, false );
	wp_enqueue_script( 'jquery-ui-js', get_template_directory_uri() . '/admin/inc/js/jquery-ui.js', array('jquery'), false, false );

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


function sales_statistics_widget( $post, $callback_args ) {
	include('admin/customer/ajax_loading/customer-list-dashboard.php');
}

function stock_status_widget( $post, $callback_args ) {
	include('admin/stocks/ajax_loading/stock-list-total.php');
}
// function sales_delivery_status_widget( $post, $callback_args ) {
// 	include('report/sales-delivery-status.php');
// }
function add_dashboard_widgets() {
	add_meta_box( 'my_sales_tatistics_widget', 'Sales Statistics', 'sales_statistics_widget', 'dashboard', 'normal', 'high' );
	add_meta_box( 'my_stock_status_widget', 'Stock Status', 'stock_status_widget', 'dashboard', 'side', 'high' );
	//add_meta_box( 'my_stock_status_widget', 'Stock ', 'sales_delivery_status_widget', 'dashboard', 'normal', 'low' );
	
	
} 
add_action('wp_dashboard_setup', 'add_dashboard_widgets' );



add_action('admin_head', 'mytheme_remove_help_tabs');
function mytheme_remove_help_tabs() {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}

function invoiceDownload($str='', $outfile = '')
{

	$paper = DOMPDF_DEFAULT_PAPER_SIZE;
	require_once("wp-pdf-templates/dompdf/dompdf_config.inc.php"); 
	$dompdf = new DOMPDF();

	global $_dompdf_show_warnings, $_dompdf_debug, $_DOMPDF_DEBUG_TYPES;

	$options = array();

	$orientation = "portrait";
	$save_file = false; # Don't save the file


	$dompdf->load_html($str);
	$dompdf->set_paper($paper, $orientation);
	$dompdf->render();

	if ( $_dompdf_show_warnings ) {
		global $_dompdf_warnings;
		foreach ($_dompdf_warnings as $msg)
		echo $msg . "\n";
		echo $dompdf->get_canvas()->get_cpdf()->messages;
		flush();
	}


	if ( !headers_sent() ) {
	$dompdf->stream($outfile, $options);
	}

}

?>