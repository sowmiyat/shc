<?php

   global $wpdb;
    $credit_table               = $wpdb->prefix.'shc_creditdebit';
    $credit_table_details       = $wpdb->prefix.'shc_creditdebit_details';
    if($_GET['type'] == 'retail'){
        $payment_table          = $wpdb->prefix.'shc_payment';
     } else{
        $payment_table           = $wpdb->prefix.'shc_ws_payment';
     }
   

    if($_GET['action']=='delete'){
        $id = $_GET['delete_id'];
        $data_delete=$wpdb->update( $credit_table ,array( 'active' =>'0' ),array( 'id' => $id ));
        $wpdb->update($credit_table_details, array('active' => 0), array('cd_id' => $id));  
        $wpdb->update($payment_table, array('active' => 0), array('reference_id' => $id,'reference_screen' => 'due_screen'));
    }
  
    $result_args = array(
        'orderby_field' => 'id',
        'page' => $creditdebit->cpage,
        'order_by' => 'DESC',
        'items_per_page' => $creditdebit->ppage ,
        'condition' => '',
    );
    $credit_list = $creditdebit->creditdebit_list_pagination($result_args);

  
?>
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">Date </th>
                            <th class="column-title">Customer name </th>
                            <th class="column-title">Customer Type </th>
                            <th class="column-title">Description </th>
                            <th class="column-title">Payment </th>
                            <th class="column-title">Total Paid </th>
                            <th class="column-title">Action </th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        if( isset($credit_list['result']) && $credit_list['result'] ) {
                            $i = $credit_list['start_count']+1;

                            foreach ($credit_list['result'] as $l_value) {
                                $credit_id = $l_value->id;
                    ?>
                            <tr class="odd pointer">
                                <td class="a-center ">
                                    <?php echo $i; ?>
                                </td>
                                <td class=""><?php echo $l_value->date; ?></td>
                                <td class=""><?php echo $l_value->customer_name; ?></td>
                                <td class=""><?php echo $l_value->customer_type; ?></td>
                                <td class=""><?php echo $l_value->description; ?></td>
                                <td class=""><?php 
                                if($l_value->customer_type == 'retail'){
                                    $payment = get_creditdebit($credit_id); 
                                }
                                else {
                                    $payment = get_Wscreditdebit($credit_id);
                                }
                               
                                        $total_paid = 0;
                                        foreach ($payment['payment_tab'] as $p_value) {
                                            if($p_value->payment_type == 'cash'){
                                                echo 'Cash : ';
                                                echo  $p_value->amount.'</br>';
                                            } 
                                            if($p_value->payment_type == 'card'){
                                                echo 'Card : ';
                                              echo $p_value->amount.'</br>';
                                            }
                                            if($p_value->payment_type == 'cheque'){

                                                echo 'Cheque : ';
                                                echo  $p_value->amount.'</br>';
                                            } 
                                            if($p_value->payment_type == 'internet'){
                                                echo 'Netbanking : ';
                                                echo $p_value->amount.'</br>';
                                            }
                                             $total_paid = $p_value->amount + $total_paid;     
                                        }


                                 ?></td>
                                 <td><?php echo $total_paid; ?></td>
                                <td class="">
                                    <?php if(is_super_admin()) { ?> <a href="<?php echo admin_url('admin.php?page=add_credit_debit')."&id=${credit_id}"; ?>" class="list_update">Update</a> /<?php } ?>
                                    <a href = "#" class="list_delete delete-creditdebit last_list_view" data-type="<?php echo $l_value->customer_type; ?>" data-id="<?php echo $l_value->id; ?>">Delete</a>
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
                    echo $credit_list['pagination'];
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <?php  echo $credit_list['status_txt']; ?>
            </div>
        </div>
