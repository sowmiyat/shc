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
.payment-blue{
    background: #093cf5;
    margin: 0 auto;
}
.payment-black{
    background: #000;
    margin: 0 auto;
}
</style>

<?php
	global $wpdb;
    $bill_table                 = $wpdb->prefix.'shc_sale';
	$bill_detail_table          = $wpdb->prefix.'shc_sale_detail';
    $bill_return_table          = $wpdb->prefix.'shc_return_items';
    $bill_return_detail_table   = $wpdb->prefix.'shc_return_items_details';
    $payment_table              = $wpdb->prefix.'shc_payment';

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
    $billing_list = $billing->billing_list_pagination($result_args);

    $individualReturnCash = 0;
    $individualReturnCard = 0;
    $individualReturnCheque = 0;
    $individualReturnInternet = 0;
    $individualReturnCredit = 0;

    $individualReturnType = getDueAmountInReturnDataIndividual();
    foreach ($individualReturnType as $individualReturn) {
        if($individualReturn->payment_type == 'cash'){
           $individualReturnCash = $individualReturn->amount.'</br>';
        } 
        if($individualReturn->payment_type == 'card'){
          $individualReturnCard = $individualReturn->amount.'</br>';
        }
        if($individualReturn->payment_type == 'cheque'){
            $individualReturnCheque =  $individualReturn->amount.'</br>';
        } 
        if($individualReturn->payment_type == 'internet'){
            $individualReturnInternet = $individualReturn->amount.'</br>';
        }
        if($individualReturn->payment_type == 'credit'){
            $individualReturnCredit = $individualReturn->amount.'</br>';
        }
    }
    $FinalIndividualTotal = $individualReturnCash + $individualReturnCard + $individualReturnCheque + $individualReturnInternet + $individualReturnCredit;



/*    echo "<pre>";
    var_dump($billing_list);*/
?>
    <div class="x_content" style="width:100%;">
       <!-- <div style="width:20%;float:left" >
            <div style="width:70%;float:left">Payment Completed -</div> <div style="width:30%;float:right;margin-top: 6px;"><div class="round-c payment-green"></div></div> 
            <div style="width:70%;float:left">Payment Incomplete -</div> <div style="width:30%;float:right;margin-top: 6px;"><div class="round-c payment-red"></div></div>
            <div style="width:70%;float:left">Wholesale Rate -</div> <div style="width:30%;float:right;margin-top: 6px;"><div class="round-c payment-blue"></div></div> 
        </div> -->
        <div class="table-responsive" style="margin: 0 auto;margin-bottom:20px;width:60%;">
        
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
                        <td style="text-align:center" ><?php echo $cash_amount - $individualReturnCash; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['card_result']->amt - $individualReturnCard ; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['cheque_result']->amt - $individualReturnCheque; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['interbanking_result']->amt - $individualReturnInternet; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['cr_result']->amt - $individualReturnCredit; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['s_result']->cod; ?></td>
                        <td style="text-align:center" ><?php echo $billing_list['s_result']->total_amount - $FinalIndividualTotal; ?></td>
                        <td style="text-align:center" >
                        <?php 
                        $total_cash_paid = $cash_amount + $billing_list['card_result']->amt + $billing_list['cheque_result']->amt + $billing_list['interbanking_result']->amt; 
                        echo  $billing_list['s_result']->total_amount - $total_cash_paid - $individualReturnCredit;
                         ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action" >
                    <thead>
                        <tr class="headings"  align="center">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">INV.No</th>
<!--                             <th class="column-title">Order ID</th> -->
                            <th class="column-title">Customer <br/> Name</th>
                            <th class="column-title">Customer <br/>Mobile</th>
                            <th class="column-title">Purchase <br/>Total</th>
                            <th class="column-title">Payment <br/> Type</th>
                            <th class="column-title">Purchase <br/> Date</th>
                            <th class="column-title">Delivery <br/> Print</th>
                            <th class="column-title">Deliveried</th>
                            <th class="column-title">Payment</th>
                            <th class="column-title">Wholesale <br/> Rate</th>
                            <th class="column-title">Action</th>
                            <th class="column-title">Due Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if( isset($billing_list['result']) && $billing_list['result'] ) {
                            $i = $billing_list['start_count']+1;

                            foreach ($billing_list['result'] as $b_value) {
                                $payment_completed = $b_value->payment_completed;
                                $bill_id = $b_value->id;
                                $inv_id =  $b_value->inv_id;
                                $delivered = $b_value->delivered;
                                if($payment_completed == 0){
                                    $Style = 'style="color:red;"';
                                } else{
                                    $Style = '';
                                }
                                if($delivered == 0) {
                                    $invoice_status = '<span class="c-process">Pending</span>';
                                }
                                if($delivered > 0) {
                                    $invoice_status = '<span class="c-delivered">Delivered</span>';
                                }   
                                $payment_done = ($total_paid < $b_value->sub_total)? '<span class="c-process">Incomplete</span>' : '<span class="c-delivered">Completed</span>';
                                 $wholesale_rate = isWholesaleRate($b_value->id);
                                $margin_rate = ($wholesale_rate->isWholesale_rate >= 1) ? '<span class="w-wsrate">End Price</span>' : '<span class="w-normal">Normal Price</span>';

                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i;  ?>
                                        
                                    </td>
                                    <td class=""><?php echo $b_value->inv_id; ?></td>
<!--                                     <td class=""><?php echo $b_value->order_id; ?></td> -->
                                    <td class=""><?php echo $b_value->name; ?> </td>
                                    <td class=""><?php echo $b_value->mobile; ?> </td>
                                    <td class=""><?php echo $b_value->sub_total; ?></td>
                                    <td style="width: 140px;"><?php 
                                       
                                        $payment_type = paymenttypeGroupByType($b_value->inv_id,$b_value->financial_year);
                                        $total_paid = 0;
                                        $amount = 0;
                                        foreach ($payment_type['WithOutCredit'] as $p_value) {
                                            if($p_value->payment_type == 'cash'){
                                                echo '<span>Cash : ';
                                                echo  $p_value->amount.'</span></br>';
                                            } 
                                            if($p_value->payment_type == 'card'){
                                                echo '<span>Card : ';
                                              echo $p_value->amount.'</span></br>';
                                            }
                                            if($p_value->payment_type == 'cheque'){

                                                echo '<span '.$Style.'>Cheque : ';
                                                echo  $p_value->amount.'</span></br>';
                                            } 
                                            if($p_value->payment_type == 'internet'){
                                                echo '<span '.$Style.'>Netbanking : ';
                                                echo $p_value->amount.'</span></br>';
                                            }
                                            $total_paid = $p_value->amount + $total_paid;    
                                        }

                                        $total_paid = is_nan($total_paid) ? '0': $total_paid;
                                         foreach ($payment_type['WithCredit'] as $p_value) {
                                            if($p_value->payment_type == 'credit'){
                                                echo  '<span '.$Style.'>Credit : ';
                                                echo $b_value->sub_total - $total_paid.'</span></br>';
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
                                        <a href="#"  class="delivery_print bill_view">Print</a>
                                        <input type="hidden" name="year" class="year" value = "<?php echo $b_value->financial_year; ?>"/>
                                        <input type="hidden" name="invoice_id" class="invoice_id" value="<?php echo $b_value->inv_id; ?>"/>
                                    </td>
                                    <td class="c-status" data-status-id="<?php echo $b_value->id; ?>"><?php echo $invoice_status; ?></td>
                                    <td class="c-status" style="position:relative;"> <?php echo $payment_done; ?></td>
                                    <td class="c-status" style="position:relative;"> <?php echo $margin_rate; ?></td>
                                    <td style="width: 140px;">
                                        <a href="<?php echo admin_url('admin.php?page=invoice')."&id=${inv_id}&year=$b_value->financial_year"; ?>"  class="bill_view">View</a>/
                                         <a href="#" class="print_bill bill_view">Print</a>
                                         <?php if(is_super_admin()) { ?> / <a href="<?php echo admin_url('admin.php?page=new_billing')."&id=${bill_id}&inv_id=${inv_id}&year=$b_value->financial_year"; ?>"  class="bill_view list_update">Update</a>/

										<a href="#" class="print_bill_delete delete-bill bill_view last_list_view" data-id="<?php echo $b_value->id; ?>">Cancel</a> <?php } ?>
                                        <input type="hidden" name="year" class="year" value = "<?php echo $b_value->financial_year; ?>"/>
                                        <input type="hidden" name="invoice_id" class="invoice_id" value="<?php echo $b_value->inv_id; ?>"/>
                                    </td>
                                    <td class=""><?php 
                                    $bal_amount = 0;
                                    $DueAmount = $b_value->sub_total - $total_paid;
                                    $return_data = getDueAmountInReturnData($b_value->id);
                                    if(isset($return_data)) {
                                        $returnAmount = $return_data->amount;
                                        if($DueAmount < $returnAmount){
                                            $bal_amount  = 0;
                                        } else{
                                            $bal_amount = $DueAmount - $returnAmount;
                                        }
                                    } else{
                                       $bal_amount = $DueAmount; 
                                    }

                                    echo $bal_amount; ?></td>

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