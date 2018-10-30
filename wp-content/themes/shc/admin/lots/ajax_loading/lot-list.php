<?php

   global $wpdb;
    $lot_table              = $wpdb->prefix.'shc_lots';

    if($_GET['action']=='delete'){
        $id = $_GET['delete_id'];
        $data_delete=$wpdb->update( $lot_table ,array( 'active' =>'0' ),array( 'id' => $id ));
    }
  
    $result_args = array(
        'orderby_field' => 'id',
        'page' => $lots->cpage,
        'order_by' => 'DESC',
        'items_per_page' => $lots->ppage ,
        'condition' => '',
    );
    $lot_list = $lots->lot_list_pagination($result_args);

  
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
                            <th class="column-title">MRP </th>
                            <th class="column-title">Selling Price </th>
                            <th class="column-title">Wholesale Price </th>
                            <th class="column-title">Purchase Price </th>
                            <th class="column-title">GST(%) </th>
                            <th class="column-title">SESS </th>
                            <th class="column-title">HSN Code</th>
                            <th class="column-title">Action </th>
                        </tr>
                    </thead>

                    <tbody style="text-align:center;">
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
                                    <td class=""><?php echo $l_value->mrp; ?></td>
                                    <td class=""><?php echo $l_value->selling_price; ?></td>
                                    <td class=""><?php echo $l_value->wholesale_price; ?></td>
                                    <td class=""><?php echo $l_value->purchase_price; ?></td> 
                                    <td class=""><?php echo $l_value->gst_percentage; ?></td>
                                    <td class=""><?php echo $l_value->sess; ?></td>
                                    <td class=""><?php echo $l_value->hsn; ?></td>

                                    <td class="">
                                        <?php if(is_super_admin()) { ?> <a href="<?php echo admin_url('admin.php?page=add_lot')."&id=${lot_id}"; ?>" class="list_update">Update</a> /<?php } ?>
                                        <a href = "#" class="list_delete delete-lot last_list_view" data-id="<?php echo $l_value->id; ?>">Delete</a>
                                    </td>
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
