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
                        <b>Slab</b>
                        <select name="slap" class="slap">
                        <option value="" >select</option>
                        <option value="0.00" <?php echo ($billing->slap == '0.00') ? 'selected' : '' ?>>0.00</option>
                        <option value="2.50" <?php echo ($billing->slap == '2.50') ? 'selected' : '' ?>>2.50</option>
                        <option value="6.00" <?php echo ($billing->slap == '6.00') ? 'selected' : '' ?>>6.00</option>
                        <option value="9.00" <?php echo ($billing->slap == '9.00') ? 'selected' : '' ?>>9.00</option>
                        <option value="14.00" <?php echo ($billing->slap == '14.00') ? 'selected' : '' ?>>14.00</option>
                      </select>
                    </div>
                    <div class="col-md-2 form-group">
                         <b>From</b><input type="text" name="bill_from" class="bill_from form-control" value="<?php echo date('Y-m-d'); ?>" placeholder="Bill From">
                    </div>

                    <div class="col-md-2 form-group">
                        <b>To</b> <input type="text" name="bill_to" class="bill_to form-control" value="<?php echo date('Y-m-d'); ?>" placeholder="Bill To">
                    </div>
                    <div class="col-md-6 form-group">
                        <button class="btn btn-default stock_print pull-right"><i class="fa fa-print"></i> Print</button>
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