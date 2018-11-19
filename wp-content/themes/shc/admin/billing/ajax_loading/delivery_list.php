<?php

	global $wpdb;
    $bill_table         = $wpdb->prefix.'shc_return_items';
    $bill_detail_table  = $wpdb->prefix.'shc_return_items_details';
    $cd_notes           = $wpdb->prefix.'shc_cd_notes';
	$return_payment     = $wpdb->prefix.'shc_payment_return';

    if(isset($_GET['action']) && $_GET['action']=='delete'){
        $id = $_GET['delete_id'];
        $data_delete=$wpdb->update( $bill_table ,array( 'active' =>'0','cancel' => '1'),array( 'id' => $id,'active' => '1' ));
        $wpdb->update($bill_detail_table, array('active' => '0','cancel'=> '1'), array('return_id' => $id,'active' => '1'));
        $wpdb->update($cd_notes, array('active' => '0'), array('return_id' => $id,'active' => '1'));
        $wpdb->update($return_payment, array('active' => '0'), array('return_id' => $id,'active' => '1'));
        
    }


    $result_args = array(
        'orderby_field' => 'created_at',
        'page' => $billing->cpage,
        'order_by' => 'DESC',
        'items_per_page' => $billing->ppage ,
        'condition' => '',
    );

    $billing_list = $billing->delivery_list_pagination($result_args);



/*    echo "<pre>";
    var_dump($billing_list);*/
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
                <table class="table table-striped jambo_table bulk_action" >
                    <thead>
                        <tr class="headings">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">INV.No</th>
                            <th class="column-title">Customer <br/> Name</th>
                            <th class="column-title">Customer  <br/>Mobile</th>
                            <th class="column-title">Billed  <br/>Date</th>
                            <th class="column-title">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php                    
                        if( isset($billing_list['result']) && $billing_list['result'] ) {
                            $i = $billing_list['start_count']+1;

                            foreach ($billing_list['result'] as $b_value) {
                                $bill_id = $b_value->id;
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo $b_value->search_inv_id; ?></td>
                                    <td class=""><?php echo $b_value->name; ?> </td>
                                    <td class=""><?php echo $b_value->mobile; ?> </td>  
                                    <td class=""><?php echo $b_value->modified_at; ?> </td>  
                                    <td>
									<a href="#" class="return_print" >Print</a>
                                     <?php if(is_super_admin()) { ?> /<a href="<?php echo admin_url('admin.php?page=return_items')."&return_id=$b_value->id&id=$b_value->search_inv_id&year=$b_value->financial_year"; ?>" class="bill_view_update">Update</a>/
									<a href="#" class="print_bill_delete delete-return-bill last_list_view" data-id="<?php echo $b_value->id; ?>">Delete</a> <?php }?>
									<input type="hidden" name="gr_id" class="gr_id" value="<?php echo $b_value->id; ?>"/></td>
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
                    echo $billing_list['pagination'];
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <?php  echo $billing_list['status_txt']; ?>
            </div>
        </div>


        
<script>
    jQuery(document).ready(function($) {
        $('#welcome-panel').after($('#custom-id').show());
    });
</script>