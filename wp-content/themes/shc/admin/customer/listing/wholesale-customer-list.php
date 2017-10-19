<?php
    $customer = new Customer();
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
                <div class="col-md-1">
                  <select name="ppage" class="ppage">
                    <option value="5" <?php echo ($customer->ppage == 5) ? 'selected' : '' ?>>5</option>
                    <option value="10" <?php echo ($customer->ppage == 10) ? 'selected' : '' ?>>10</option>
                    <option value="20" <?php echo ($customer->ppage == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?php echo ($customer->ppage == 50) ? 'selected' : '' ?>>50</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <input type="text" name="name" class="name" value="<?php echo $customer->name; ?>" placeholder="Customer Name">
                </div>
                <div class="col-md-2">
                  <input type="text" name="mobile" class="mobile" value="<?php echo $customer->mobile; ?>" placeholder="Mobile">
                </div>
                <div class="col-md-2 form-group">
                  <input type="text" name="customer_from" class="customer_from form-control" value="<?php echo $customer->customer_from; ?>" placeholder="Customer From">
                </div>
                <div class="col-md-2 form-group">
                  <input type="text" name="customer_to" class="customer_to form-control" value="<?php echo $customer->customer_to; ?>" placeholder="Customer To">
                </div>
              </div>
              <input type="hidden" name="filter_action" class="filter_action" value="wholesale_customer_filter">
              
            </div>
        </div>
        <div class="wholesale_customer_filter">
        <?php
            include( get_template_directory().'/admin/customer/ajax_loading/wholesale-customer-list.php' );
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

    
})    

</script>