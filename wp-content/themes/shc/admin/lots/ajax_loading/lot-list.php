<?php
    $result_args = array(
        'orderby_field' => 'id',
        'page' => $lots->cpage,
        'order_by' => 'DESC',
        'items_per_page' => $lots->ppage ,
        'condition' => '',
    );
    $lot_list = $lots->lot_list_pagination($result_args);
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
                            <th class="column-title">Selling Price </th>
                            <th class="column-title">Purchase Price </th>
                            <th class="column-title">CGST(%) </th>
                            <th class="column-title">SGST(%) </th>
                            <th class="column-title">HSN Code</th>
                            <th class="column-title">Action </th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        if( isset($lot_list['result']) && $lot_list['result'] ) {
                            $i = $lot_list['start_count']+1;

                            foreach ($lot_list['result'] as $l_value) {
                                $lot_id = $l_value->id;
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo $l_value->brand_name; ?></td>
                                    <td class=""><?php echo $l_value->product_name; ?></td>
                                    <td class=""><?php echo $l_value->selling_price; ?></td>
                                    <td class=""><?php echo $l_value->purchase_price; ?></td> 
                                    <td class=""><?php echo $l_value->cgst; ?></td>
                                    <td class=""><?php echo $l_value->sgst; ?></td>
                                    <td class=""><?php echo $l_value->hsn; ?></td>
                                    <td class=""><a href="<?php echo menu_page_url( 'add_lot', 0 )."&id=${lot_id}"; ?>">Update</a></td>
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
                    echo $lot_list['pagination'];
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <?php  echo $lot_list['status_txt']; ?>
            </div>
        </div>
