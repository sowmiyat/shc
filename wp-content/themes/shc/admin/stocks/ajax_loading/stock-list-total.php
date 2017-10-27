<?php
$stocks = new Stocks();

    $result_args = array(
        'orderby_field' => 'final_stock',
        'page' => $stocks->cpage,
        'order_by' => 'ASC',
        'items_per_page' => $stocks->ppage ,
        'condition' => '',
    );
    $stock_list = $stocks->stock_list_pagination_total($result_args);

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
                            <th class="column-title">Available Stock </th>
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
                                    <td class=""><?php echo $s_value->final_stock; ?></td>
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