<?php

    if(!$report) {
        $report = new report();
        $ppage = 5;
    }

   
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Stock Report <small>Custom design</small></h2>
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
                        <option value="5" <?php echo ($report->ppage == 5) ? 'selected' : '' ?>>5</option>
                        <option value="10" <?php echo ($report->ppage == 10) ? 'selected' : '' ?>>10</option>
                        <option value="20" <?php echo ($report->ppage == 20) ? 'selected' : '' ?>>20</option>
                        <option value="50" <?php echo ($report->ppage == 50) ? 'selected' : '' ?>>50</option>
                      </select>
                    </div>    
                    <div class="col-md-2 form-group">
                        <b></b>
                        <select name="slap" class="slap" >
                            <option value="" >GST Tax</option>
                            <option value="0.00" <?php echo ($report->slap == '0.00') ? 'selected' : '' ?>>0 %</option>
                            <option value="2.50" <?php echo ($report->slap == '2.50') ? 'selected' : '' ?>>5 %</option>
                            <option value="6.00" <?php echo ($report->slap == '6.00') ? 'selected' : '' ?>>12 %</option>
                            <option value="9.00" <?php echo ($report->slap == '9.00') ? 'selected' : '' ?>>18 %</option>
                            <option value="14.00" <?php echo ($report->slap == '14.00') ? 'selected' : '' ?>>28 %</option>
                      </select>
                    </div>
                    <div class="col-md-2 form-group">
                         <b></b><input type="text" name="bill_from" class="bill_from form-control" value="<?php echo date('Y-m-d'); ?>" placeholder="Bill From">
                    </div>

                    <div class="col-md-2 form-group">
                        <b></b> <input type="text" name="bill_to" class="bill_to form-control" value="<?php echo date('Y-m-d'); ?>" placeholder="Bill To">
                    </div>
                    <div class="col-md-5 form-group">
                        <button class="btn btn-default stock_print pull-right"><i class="fa fa-print"></i> Print</button>
                        <!-- <a class="btn btn-default pull-right stock_print pull-right" href="#" target="_blank" ><i class="fa fa-print"></i> Print</a> -->
                        
                        <button class="btn btn-primary pull-right stock_download" style="margin-right: 5px;"><i class="fa fa-file-pdf-o" href=""></i> Generate PDF</button>
                    </div>
                </div>
              <input type="hidden" name="filter_action" class="filter_action" value="stock_report">
              
            </div>
        </div>
        <div class="stock_report">
        <?php
            include( get_template_directory().'/admin/report/ajax_loading/stock-list.php' );
        ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    
jQuery(document).ready(function () {
    jQuery('.ppage').focus();
    jQuery('.stock_download').live('keydown', function(e){
        var keyCode = e.keyCode || e.which; 
        if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.ppage').focus();
        }
    });    
})    


</script>






















 <div class="clearfix"></div>



<style type="text/css" >

    
     @media screen {
    .print_view {
      display: none !important;
    }

  }
  /** Fix for Chrome issue #273306 **/
  @media print {
    #adminmenumain, #wpfooter, .print-hide,.x_title,.dataTables_info {
      display: none;
    }
    body, html {
      height: auto;
      padding:0px;
    }
    html.wp-toolbar {
      padding:0;
    }
    #wpcontent {
      background: white;
      box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
      margin: 1mm;
      display: block;
      padding: 0;
    }
  }

  @page { margin: 0;padding: 0; }
  .sheet {
    margin: 0;
  }

      
</style>  