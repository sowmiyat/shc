<?php
    $stocks = new Stocks();
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
                    <option value="5" <?php echo ($stocks->ppage == 5) ? 'selected' : '' ?>>5</option>
                    <option value="10" <?php echo ($stocks->ppage == 10) ? 'selected' : '' ?>>10</option>
                    <option value="20" <?php echo ($stocks->ppage == 20) ? 'selected' : '' ?>>20</option>
                    <option value="50" <?php echo ($stocks->ppage == 50) ? 'selected' : '' ?>>50</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <input type="text" name="brand_name" class="brand_name_pagi" value="<?php echo $stocks->brand_name; ?>" placeholder="Brand Name">
                </div>
                <div class="col-md-2">
                  <input type="text" name="product_name" class="product_name" value="<?php echo $stocks->product_name; ?>" placeholder="Product Name">
                </div>
                <div class="col-md-2">
                  <input type="text" name="stock_from" class="stock_from" value="<?php echo $stocks->stock_from; ?>" placeholder="Stock From">
                </div>
                <div class="col-md-2">
                  <input type="text" name="stock_to" class="stock_to" value="<?php echo $stocks->stock_to; ?>" placeholder="Stock To">
                </div>
              </div>
              <input type="hidden" name="filter_action" class="filter_action" value="stock_filter_total">
              
            </div>
        </div>
        <div class="stock_filter_total">
        <?php
            include( get_template_directory().'/admin/stocks/ajax_loading/stock-list-total.php' );
        ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    
jQuery(document).ready(function () {
    jQuery('.ppage').focus();
    
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