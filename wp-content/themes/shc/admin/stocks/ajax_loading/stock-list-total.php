<?php
 if(!$stocks_class) {
        $stocks_class = new Stocks();
        $result_args = array(
            'orderby_field' => 'tab.balance_stock',
            'second_orderby_field' => ',tab.tot_sale',
            'page' => $stocks_class->cpage,
            'order_by' => 'ASC',
            'second_order_by' => 'DESC',
            'items_per_page' => $stocks_class->ppage ,
            'condition' => '',
        );
       
    } 

    $stock_list = $stocks_class->stock_list_pagination_total($result_args);

?>  
<style>
.pointer td{
    text-align: center;
}
.headings th {
    text-align: center;
}
</style>
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">Brand Name </th>
                            <th class="column-title">Product Name </th>
                             <th class="column-title">Sold Count </th>
                            <th class="column-title">Available Stock </th>
                            <th class="column-title">Stock Alert Count </th>
                        </tr>
                    </thead>

                    <tbody style="text-align: center;">
                    <?php
                        if( isset($stock_list['result']) && $stock_list['result'] ) {
                            $i = $stock_list['start_count']+1;

                            foreach ($stock_list['result'] as $s_value) {
                                $stock_id = $s_value->stock_id;
                    ?>
                                <tr class="odd pointer" <?php if($s_value->is_alert == 1){ echo'style="color:red"'; } ?>>
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class="brand"><?php echo $s_value->brand_name; ?></td>
                                    <td class="product"><?php echo $s_value->product_name; ?></td>
                                    <td class="product"><?php echo $s_value->tot_sale; ?></td>
                                    <td class="bal_stock"><?php echo $s_value->balance_stock; ?></td>
                                    <td><?php echo $s_value->stock_alert; ?></td>
                                </tr>
                    <?php
                                $i++;
                            }
                        }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>



        <div class="row">
            <div class="col-sm-7">
                <div class="paging_simple_numbers" id="datatable-fixed-header_paginate">
                    <?php
                    echo $stock_list['pagination'];

                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <?php  echo $stock_list['status_txt']; ?>
            </div>
        </div>