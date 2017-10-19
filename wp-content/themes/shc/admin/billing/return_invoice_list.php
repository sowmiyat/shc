<?php
    $billing = new Billing();
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


                    <div class="col-md-1 form-group has-feedback">
                        <input type="text" class="form-control has-feedback-left inv_id" name="inv_id" value="<?php echo $billing->inv_id; ?>" placeholder="Invoice Number" style="padding-right: 5px;">
                        <span class="form-control-feedback left " aria-hidden="true" style="margin-top: 6px;">INV</span>
                    </div>
                    <div class="col-md-2 form-group">
                        <input type="text" name="mobile" class="mobile form-control" value="<?php echo $billing->mobile; ?>" placeholder="Customer Mobile">
                    </div>
                </div>
              <input type="hidden" name="filter_action" class="filter_action" value="return_billing_filter">
              
            </div>
        </div>
        <div class="return_billing_filter">
        <?php
            include( get_template_directory().'/admin/billing/ajax_loading/return-list.php' );
        ?>
        </div>
    </div>
</div>