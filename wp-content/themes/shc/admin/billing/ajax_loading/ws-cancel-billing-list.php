<?php


    $result_args = array(
        'orderby_field' => 'created_at',
        'page' => $billing->cpage,
        'order_by' => 'DESC',
        'items_per_page' => $billing->ppage ,
        'condition' => '',
    );
    $billing_list = $billing->ws_cancel_billing_list_pagination($result_args);
    


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
    <div class="x_content" style="width:100%;">
        <div class="table-responsive" style="width:150px;margin: 0 auto;margin-bottom:20px;">
           <!--  <table class="table table-striped jambo_table bulk_action">
                <thead>
                    <tr class="headings" >
                        <th style="text-align:center">Total Return</th>    
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center" ><?php echo $billing_list['s_result']->total_amount; ?></td>
                    </tr>
                </tbody>
            </table> -->
        </div>
    </div>
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">INV.No</th>
                            <th class="column-title">Order ID</th>
                            <th class="column-title">Customer <br/> Name</th>
                            <th class="column-title">Customer <br/> Mobile</th>
                            <th class="column-title">Purchase <br/> Total</th>
                            <th class="column-title">Purchase <br/> Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if( isset($billing_list['result']) && $billing_list['result'] ) {
                            $i = $billing_list['start_count']+1;

                            foreach ($billing_list['result'] as $b_value) {
                                $bill_id = $b_value->id;
                                $inv_id = $b_value->inv_id;
                    ?> 
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td><?php echo 'Inv '.$b_value->inv_id; ?></td>
                                    <td class=""><?php echo $b_value->order_id; ?></td>
                                    <td class=""><?php echo $b_value->name; ?> </td>
                                    <td class=""><?php echo $b_value->mobile; ?> </td>
                                    <td class=""><?php echo $b_value->sub_total; ?></td>
                                    <td class=""><?php echo $b_value->modified_at; ?></td>
                                    <td>
                                        <a href="<?php echo admin_url('admin.php?page=ws_cancel_invoice_view')."&id=${inv_id}&year=$b_value->financial_year"; ?>" class="bill_view last_list_view">View</a>
                                        <input type="hidden" name="year" class="year" value = "<?php echo $b_value->financial_year; ?>"/>
                                        <input type="hidden" name="invoice_id" class="invoice_id" value="<?php echo $b_value->inv_id; ?>"/>

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