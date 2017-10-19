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
                   <!--  <div class="col-md-1 form-group">
                      <select name="ppage" class="ppage ">
                        <option value="5" <?php echo ($billing->ppage == 5) ? 'selected' : '' ?>>5</option>
                        <option value="10" <?php echo ($billing->ppage == 10) ? 'selected' : '' ?>>10</option>
                        <option value="20" <?php echo ($billing->ppage == 20) ? 'selected' : '' ?>>20</option>
                        <option value="50" <?php echo ($billing->ppage == 50) ? 'selected' : '' ?>>50</option>
                      </select>
                    </div> -->
                    <div class="col-md-2 form-group">
                         <b>From</b><input type="text" name="bill_from" class="bill_from form-control" value="<?php echo date('Y-m-01'); ?>" placeholder="Bill From">
                    </div>

                    <div class="col-md-2 form-group">
                        <b>To</b> <input type="text" name="bill_to" class="bill_to form-control" value="<?php echo date('Y-m-t'); ?>" placeholder="Bill To">
                    </div>
                    <div class="col-md-8">
                        <button class="btn btn-default accountant_print pull-right"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-primary pull-right accountant_download" style="margin-right: 5px;"><i class="fa fa-file-pdf-o" href=""></i> Generate PDF</button>
                    </div>
                </div>
              <input type="hidden" name="filter_action" class="filter_action" value="stock_report_acc">
              
            </div>
        </div>
        <div class="stock_report_acc">
        <?php
            include( get_template_directory().'/admin/report/ajax_loading/stock-list-accountant.php' );
        ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    
jQuery(document).ready(function () {
    jQuery('.bill_from').focus();

    jQuery(document).live('keydown', function(e){
        if(jQuery(document.activeElement).closest("#wpbody-content").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                jQuery('.bill_from').focus()
            }
        }
    });
    
    jQuery('.filter-section input[type="text"]:last').live('keydown', function(e){

        if(jQuery('.jambo_table td a').length == 0 && jQuery(".next.page-numbers").length == 0 ) {

            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                jQuery('.bill_from').focus()
            }
        }

    });


    jQuery('.jambo_table td a').live('keydown', function(e) { 

        if(jQuery(this).parent().parent().next('tr').length == 0 && jQuery(".next.page-numbers").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                jQuery('.bill_from').focus()
            } 
        }
    });

    jQuery(".next.page-numbers").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (keyCode == 9) { 
        e.preventDefault(); 
        // call custom function here
        jQuery('.bill_from').focus()
      } 
    });

    
})    

</script>