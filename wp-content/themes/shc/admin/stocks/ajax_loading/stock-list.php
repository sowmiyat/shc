<?php
    $result_args = array(
        'orderby_field' => 'brand_name',
        'page' => $stocks->cpage,
        'order_by' => 'ASC',
        'items_per_page' => $stocks->ppage ,
        'condition' => '',
    );
    $stock_list = $stocks->stock_list_pagination($result_args);

?>
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
                            <th class="column-title">Stock Qty </th>
                            <th class="column-title">Selling Price </th>
                            <th class="column-title">Stock Added </th>
                            <th class="column-title">Action </th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        if( isset($stock_list['result']) && $stock_list['result'] ) {
                            $i = $stock_list['start_count']+1;

                            foreach ($stock_list['result'] as $s_value) {
                                $stock_id = $s_value->stock_id;
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo $s_value->brand_name; ?></td>
                                    <td class=""><?php echo $s_value->product_name; ?></td>
                                    <td class=""><?php echo $s_value->stock_count; ?></td>
                                    <td class=""><?php echo $s_value->selling_total; ?></td>
                                    <td class=""><?php echo $s_value->stock_created; ?></td>
                                    <td class=""><a href="<?php echo menu_page_url( 'add_stocks', 0 )."&stock_id=${stock_id}"; ?>"  class="list_update">Update</a></td>
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
 