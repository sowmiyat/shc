<?php
    $report = new report();
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Table design <small>Custom design</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                    </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-1 form-group">
                      <select name="ppage" class="ppage ">
                        <option value="5" <?php echo ($billing->ppage == 5) ? 'selected' : '' ?>>5</option>
                        <option value="10" <?php echo ($billing->ppage == 10) ? 'selected' : '' ?>>10</option>
                        <option value="20" <?php echo ($billing->ppage == 20) ? 'selected' : '' ?>>20</option>
                        <option value="50" <?php echo ($billing->ppage == 50) ? 'selected' : '' ?>>50</option>
                      </select>
                    </div>


                    
                    <div class="col-md-1 form-group">
                        <b></b>
                        <select name="slap" class="slap">
                        <option value="" >slab</option>
                        <option value="0.00" <?php echo ($billing->slap == '0.00') ? 'selected' : '' ?>>0.00</option>
                        <option value="2.50" <?php echo ($billing->slap == '2.50') ? 'selected' : '' ?>>5.00</option>
                        <option value="6.00" <?php echo ($billing->slap == '6.00') ? 'selected' : '' ?>>12.00</option>
                        <option value="9.00" <?php echo ($billing->slap == '9.00') ? 'selected' : '' ?>>18.00</option>
                        <option value="14.00" <?php echo ($billing->slap == '14.00') ? 'selected' : '' ?>>28.00</option>
                      </select>
                    </div>
                    <div class="col-md-2 form-group">
                         <b></b><input type="text" name="bill_from" class="bill_from form-control" value="<?php echo ($billing->bill_from) ? $billing->bill_from : date('Y-m-d'); ?>" placeholder="Bill From">
                    </div>

                    <div class="col-md-2 form-group">
                        <b></b> <input type="text" name="bill_to" class="bill_to form-control" value="<?php echo ($billing->bill_to) ? $billing->bill_to : date('Y-m-d'); ?>" placeholder="Bill To">
                    </div>
                    <div class="col-md-6 form-group">
						<button class="btn btn-default  pull-right return_report_print"><i class="fa fa-print"></i> Print</button>
						<button class="btn btn-primary pull-right return_report_download" style="margin-right: 5px;"><i class="fa fa-file-pdf-o" href=""></i> Generate PDF</button>
                    </div>
                </div>  	 
              <input type="hidden" name="filter_action" class="filter_action" value="return_report">
              
            </div>
        </div>
        <div class="return_report">
        <?php
            include( get_template_directory().'/admin/report/ajax_loading/return-list.php' );
        ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    
jQuery(document).ready(function () {
    jQuery('.ppage').focus();

    jQuery(document).live('keydown', function(e){
        if(jQuery(document.activeElement).closest("#wpbody-content").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                jQuery('.ppage').focus()
            }
        }
    });
    
    jQuery('.filter-section input[type="text"]:last').live('keydown', function(e){

        if(jQuery('.jambo_table td a').length == 0 && jQuery(".next.page-numbers").length == 0 ) {

            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                jQuery('.ppage').focus()
            }
        }

    });


    jQuery('.jambo_table td a').live('keydown', function(e) { 

        if(jQuery(this).parent().parent().next('tr').length == 0 && jQuery(".next.page-numbers").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                jQuery('.ppage').focus()
            } 
        }
    });

    jQuery(".next.page-numbers").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (keyCode == 9) { 
        e.preventDefault(); 
        // call custom function here
        jQuery('.ppage').focus()
      } 
    });
	
	
	jQuery('.return_report_print').on('click',function(){
		console.log("jlkjklj");
     	var slap = jQuery('.slap').val();
     	 var bill_form = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'return-report-print/?bill_form='+bill_form+'&bill_to='+bill_to + '&slap='+slap;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });
    
})    

</script>