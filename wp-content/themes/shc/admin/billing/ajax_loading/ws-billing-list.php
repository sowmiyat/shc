
<style>
.x_content{
         padding: 0px; 
}
.pointer td{
    text-align: center;
}
.headings th {
    text-align: center;
}
.round-c {
    width: 10px;
    height: 10px;
    border-radius: 5px;
   
    top: 28px;
    right: 2px;
}
.payment-green {
    background: #009013;
    margin: 0 auto;
}
.payment-red {
    background: #f00;
    margin: 0 auto;
}
</style>

<?php
	global $wpdb;
    $bill_table = $wpdb->prefix.'shc_ws_sale';
	$bill_detail_table = $wpdb->prefix.'shc_ws_sale_detail';
    $bill_return_table = $wpdb->prefix.'shc_ws_return_items';
    $bill_return_detail_table = $wpdb->prefix.'shc_ws_return_items_details';
    $payment_table             = $wpdb->prefix.'shc_ws_payment';


    if($_GET['action']=='delete'){
        $id = $_GET['delete_id'];
        $data_delete        = $wpdb->update( $bill_table ,array( 'active' =>'0','cancel' =>'1' ),array( 'id' => $id,'active' => '1' ));
        $wpdb->update($bill_detail_table, array('active' => '0','cancel' =>'1'), array('sale_id' => $id,'active' => '1'));
        $data_return_delete = $wpdb->update( $bill_return_table ,array( 'active' =>'0','cancel' =>'1' ),array( 'inv_id' => $id,'active' => '1' ));
        $wpdb->update($bill_return_detail_table, array('active' => '0','cancel' =>'1'), array('sale_id' => $id,'active' => '1'));
        $wpdb->update($payment_table, array('active' => 0), array('sale_id' =>$id)); 
    }

    $ppage = false;
    if(!$billing) {
        $billing = new Billing();
        $ppage = 5;
    }

    $result_args = array(
        'orderby_field' => 'created_at',
        'page' => $billing->cpage,
        'order_by' => 'DESC',
        'items_per_page' => ($ppage) ? $ppage : $billing->ppage ,
        'condition' => '',
    );
    $billing_list = $billing->ws_billing_list_pagination($result_args);
    


/*    echo "<pre>";
    var_dump($billing_list);*/
?>
   <div class="x_content" style="width:100%;">
        <div class="table-responsive" style="margin: 0 auto;margin-bottom:20px;width:70%;">
            <table class="table table-striped jambo_table bulk_action">
                <thead>
                    <tr class="headings" >
                        <th style="text-align:center">Cash</th>    
                        <th style="text-align:center">Card</th>    
                        <th style="text-align:center">Cheque</th>    
                        <th style="text-align:center">Netbanking</th>    
                        <th style="text-align:center">Credit</th>  
                        <th style="text-align:center">COD</th>  
                        <th style="text-align:center">Total<br/> Sale</th>    
                        <th style="text-align:center">Total <br/>Due</th>    
                            
                    </tr>
                </thead>
                <tbody>
                    <tr> <?php  $cash_amount = $billing_list['c_result']->amt - $billing_list['payto_result']->pay_to;  ?>
                        <td style="text-align:center" ><?php echo $cash_amount; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['card_result']->amt; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['cheque_result']->amt; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['interbanking_result']->amt; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['cr_result']->amt; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['s_result']->cod; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['s_result']->total_amount; ?></td>
                        <td style="text-align:center" ><?php                         
                        $total_cash_paid = $cash_amount + $billing_list['card_result']->amt + $billing_list['cheque_result']->amt + $billing_list['interbanking_result']->amt; 
                        echo  $billing_list['s_result']->total_amount - $total_cash_paid;
                         ?></td>
                    </tr>
                </tbody>
            </table>
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
                            <th class="column-title">Customer <br/> Name</th>
                            <th class="column-title">Customer <br/>Mobile</th>
                            <th class="column-title">Purchase <br/>Total</th>
                            <th class="column-title">Payment <br/> Type</th>
                            <th class="column-title">Purchase <br/> Date</th>
                            <th class="column-title">Delivery <br/> Print</th>
                            <th class="column-title">Payment</th>
                            <th class="column-title">Action</th>
                            <th class="column-title">Due Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if( isset($billing_list['result']) && $billing_list['result'] ) {
                            $i = $billing_list['start_count']+1;

                            foreach ($billing_list['result'] as $b_value) {
                                $bill_id    = $b_value->id;
                                $inv_id     = $b_value->inv_id;
                                $delivered  = $b_value->delivered;
                    ?> 
                                <tr class="odd pointer" <?php if($delivered == 0) { echo 'style="color:red"';} ?>>
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td><?php echo 'Inv '.$b_value->inv_id; ?></td>
<!--                                     <td class=""><?php echo $b_value->order_id; ?></td> -->
                                    <td class=""><?php echo $b_value->name; ?> </td>
                                    <td class=""><?php echo $b_value->mobile; ?> </td>
                                    <td class=""><?php echo $b_value->sub_total; ?></td>
                                   <td style="width: 140px;"><?php 
                                                    
                                                $payment_type = ws_paymenttypeGroupByType($b_value->inv_id,$b_value->financial_year);
                                                $total_paid = 0;
                                                $amount = 0;
                                                foreach ($payment_type['WithOutCredit'] as $p_value) {
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
                                                    foreach ($payment_type['WithCredit'] as $p_value) {
                                                        if($p_value->payment_type == 'credit'){
                                                            echo  'Credit : ';
                                                            echo $b_value->sub_total - $total_paid.'</br>';
                                                        } 
                                                    }
                                                    if($b_value->cod_check == '1'){
                                                        echo '<b>COD : ';
                                                        echo  $b_value->sub_total - $total_paid;
                                                    }
                                                    ?>
                                                </td>
                                    <td class=""><?php echo $b_value->modified_at; ?></td>
                                     <td>
                                        <a href="#"  class="ws_delivery_print bill_view">Print</a>
                                        <input type="hidden" name="year" class="year" value = "<?php echo $b_value->financial_year; ?>"/>
                                        <input type="hidden" name="invoice_id" class="invoice_id" value="<?php echo $b_value->inv_id; ?>"/>
                                    </td>
                                    <td>
                                        <?php if($total_paid < $b_value->sub_total) {
                                            ?>
                                        
                                        <div class="round-c payment-red"></div>
                                        <?php } else { ?>
                                        <div class="round-c payment-green"></div>
                                        <?php } ?>   
                                    </td>
                                    <td>
                                        <a href="<?php echo admin_url('admin.php?page=ws_invoice')."&id=${inv_id}&year=$b_value->financial_year"; ?>" class="bill_view">View</a>/
                                        <a href="#" class="ws_print_bill bill_view">Print</a>
                                         <?php if(is_super_admin()) { ?> /<a href="<?php echo admin_url('admin.php?page=ws_new_billing')."&id=${bill_id}&inv_id=${inv_id}&year=$b_value->financial_year"; ?>" class="bill_view list_update">Update</a>/
                                        
										<a href="#" class="print_bill_delete delete-ws-bill bill_view last_list_view" style="width:50px;" data-id="<?php echo $b_value->id; ?>">Cancel</a> <?php }?>
                                        <input type="hidden" name="year" class="year" value = "<?php echo $b_value->financial_year; ?>"/>
                                        <input type="hidden" name="invoice_id" class="invoice_id" value="<?php echo $b_value->inv_id; ?>"/>

                                    </td>
                                    <td class=""><?php    echo ( $b_value->sub_total - $total_paid); ?></td>
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