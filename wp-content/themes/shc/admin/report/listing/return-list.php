<?php
    $report = new report();
    $profile = get_profile1();
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


                    
                    <div class="col-md-2 form-group">
                        <b></b>
                        <select name="slap" class="slap">
                            <option value="" >GST Tax %</option>
                            <option value="0.00" <?php echo ($billing->slap == '0.00') ? 'selected' : '' ?>>0 %</option>
                            <option value="2.50" <?php echo ($billing->slap == '2.50') ? 'selected' : '' ?>>5 %</option>
                            <option value="6.00" <?php echo ($billing->slap == '6.00') ? 'selected' : '' ?>>12 %</option>
                            <option value="9.00" <?php echo ($billing->slap == '9.00') ? 'selected' : '' ?>>18 %</option>
                            <option value="14.00" <?php echo ($billing->slap == '14.00') ? 'selected' : '' ?>>28 %</option>
                      </select>
                    </div>
                    <div class="col-md-2 form-group">
                         <b></b><input type="text" name="bill_from" class="bill_from form-control" value="<?php echo ($billing->bill_from) ? $billing->bill_from : date('Y-m-d'); ?>" placeholder="Bill From">
                    </div>

                    <div class="col-md-2 form-group">
                        <b></b> <input type="text" name="bill_to" class="bill_to form-control" value="<?php echo ($billing->bill_to) ? $billing->bill_to : date('Y-m-d'); ?>" placeholder="Bill To">
                    </div>
                    <div class="col-md-5 form-group">
                      <div class="pull-right">
                        <button class="btn btn-default return_report_print "><i class="fa fa-print"></i> Print</button>
						            <button class="btn btn-primary  return_report_download" style="margin-right: 5px;"><i class="fa fa-file-pdf-o" href=""></i> Generate PDF</button>
                      </div>
                    </div>
                </div>  	 
              <input type="hidden" name="filter_action" class="filter_action" value="return_report">
              
            </div>
        </div>
         <div class="print_view">
                    
            <div class="col-xs-12 invoice-header">
                <h4 style="margin-left: -15px;">
                   Goods Return Report
                    <small class="pull-right">Date: <?php echo date("d/m/Y"); ?></small>
                </h4>
            </div>

            <table cellspacing='3' cellpadding='3' WIDTH='100%' >
                <tr>
                    <td valign='top' WIDTH='50%'><strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
                        <br/><?php echo $profile ? $profile->address : '';  ?>
                        <br/><?php echo $profile ? $profile->address2 : '';  ?>
                        <br/>Cell : <?php echo $profile ? $profile->phone_number : '';  ?>
                        <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?>
                    </td>
                </tr>
            </table>
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

     jQuery(".ppage").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (event.shiftKey && event.keyCode == 9) { 
            e.preventDefault(); 
        // call custom function here
            jQuery('.return_report_download').focus();
        } else if(event.keyCode == 9){
            e.preventDefault(); 
            jQuery('.slap').focus();
        } else {
         jQuery('.ppage').focus();
        }
    });


    jQuery('.return_report_download').live('keydown', function(e){
         if (event.shiftKey && event.keyCode == 9) { 
            e.preventDefault(); 
        // call custom function here
            jQuery('.return_report_print').focus();
        } else if(event.keyCode == 9){
            e.preventDefault(); 
            jQuery('.ppage').focus();
        } else {
         jQuery('.return_report_download').focus();
        }
    });    
})   

function print_current_page()
{
// window.print();
var printPage = window.open(document.URL, '_blank');
setTimeout(printPage.print(), 5);
} 

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