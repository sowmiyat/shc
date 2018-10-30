

<?php
    $stocks_class = new Stocks();
        $result_args = array(
            'orderby_field'         => 'tab.balance_stock',
            'second_orderby_field'  => ',tab.tot_sale',
            'page'                  => $stocks_class->cpage,
            'order_by'              => 'ASC',
            'second_order_by'       => 'DESC',
            'items_per_page'        => $stocks_class->ppage ,
            'condition'             => '',
        );
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
                        <option value="5" <?php echo ($stocks_class->ppage == 5) ? 'selected' : '' ?>>5</option>
                        <option value="10" <?php echo ($stocks_class->ppage == 10) ? 'selected' : '' ?>>10</option>
                        <option value="20" <?php echo ($stocks_class->ppage == 20) ? 'selected' : '' ?>>20</option>
                        <option value="50" <?php echo ($stocks_class->ppage == 50) ? 'selected' : '' ?>>50</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="brand_name" class="brand_name_pagi" value="<?php echo $stocks_class->brand_name; ?>" placeholder="Brand Name">
                </div>
                <div class="col-md-2">
                    <input type="text" name="product_name" class="product_name" value="<?php echo $stocks_class->product_name; ?>" placeholder="Product Name">
                </div>
                <!-- <div class="col-md-2">
                 <select name="comparison" class="comparison">
                    <option value='less_than' <?php echo ($stocks->comparison == 'less_than') ? 'selected' : '' ?>>less than</option>
                    <option value='greater_than' <?php echo ($stocks->comparison == 'greater_than') ? 'selected' : '' ?>>greater than</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <input type="text" name="count" class="count" value="<?php echo $stocks->stock_to; ?>" placeholder="Count">
                </div>
              </div> -->
                <div  class="col-md-2">
                    <select name="stock_alert" class="stock_alert">
                        <option value='all' <?php echo ($stocks_class->stock_alert == 'all') ? 'selected' : '' ?>>ALL</option>
                        <option value='min_alert' <?php echo ($stocks_class->stock_alert == 'min_alert') ? 'selected' : '' ?>>Min Inventory</option>
                    </select>
                </div>
               <!-- <div  class="col-md-2">
                  <select name="fast_moving" class="fast_moving">
                    <option value='all' <?php echo ($stocks_class->stock_alert == 'all') ? 'selected' : '' ?>>ALL</option>
                    <option value='most_active' <?php echo ($stocks_class->stock_alert == 'most_active') ? 'selected' : '' ?>>Most Active Stock</option>
                  </select>
              </div> -->
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
    
    jQuery(document).live('keydown', function(e){
        if(jQuery(document.activeElement).closest("#wpbody-content").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                jQuery('.ppage').focus()
            }
        }
    });
    jQuery(".ppage").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (event.shiftKey && event.keyCode == 9) { 
            e.preventDefault(); 
        // call custom function here
            jQuery('.stock_alert').focus();
        } else if(event.keyCode == 9){
            e.preventDefault(); 
            jQuery('.brand_name_pagi').focus();
        } else {
         jQuery('.ppage').focus();
        }
    });
   
    jQuery('.stock_alert').live('keydown', function(e){

        if(jQuery('.jambo_table td a').length == 0 && jQuery(".next.page-numbers").length == 0 ) {

        var keyCode = e.keyCode || e.which; 

        if (event.shiftKey && event.keyCode == 9) { 
            e.preventDefault(); 
        // call custom function here
            jQuery('.product_name').focus();
        } else if(event.keyCode == 9){
            e.preventDefault(); 
            jQuery('.ppage').focus();
        } else {
         jQuery('.stock_alert').focus();
        }
        }

    });
   

    jQuery(".next.page-numbers").live('keydown', function(e) { 
      var keyCode = e.keyCode || e.which; 


      if (event.shiftKey && event.keyCode == 9) { 
            e.preventDefault(); 
        // call custom function here
            jQuery('.page-numbers').focus();
        } else if(event.keyCode == 9){
            e.preventDefault(); 
            jQuery('.ppage').focus();
        } else {
         jQuery('.next.page-numbers').focus();
        }

  });
    
})    

</script>