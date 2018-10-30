<?php

   global $wpdb;
    $customer_table = $wpdb->prefix.'shc_wholesale_customer';

    if($_GET['action']=='delete'){
        $id = $_GET['delete_id'];
        $data_delete=$wpdb->update( $customer_table ,array( 'active' =>'0' ),array( 'id' => $id ));
    }

    $ppage = false;
    if(!$customer_class) {
        $customer_class = new Customer();
        $result_args = array(
            'orderby_field' => 'ff.modified_at',
            'page' => $customer_class->cpage,
            'order_by' => 'DESC',
            'items_per_page' => $customer_class->ppage ,
            'condition' => '',
        );
    }

    
    $customer_list = $customer_class->wholesale_customer_list_pagination($result_args);
  
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
                <table class="table table-striped jambo_table bulk_action" style="text-align:center">
                    <thead>
                        <tr class="headings">
                            <th style="text-align:center">
                                S.No
                            </th>
                             <th style="text-align:center" class="column-title">Company <br/> Name </th>
                            <th style="text-align:center" class="column-title">Customer <br/> Name </th>
                            <th style="text-align:center" class="column-title">Mobile </th>
                            <th style="text-align:center" class="column-title" style="width: 100px;">Address </th>
                            <th style="text-align:center" class="column-title">GST <br/> Number </th>
                            <th style="text-align:center" class="column-title">Sale <br/> Total </th>
							<th style="text-align:center" class="column-title">Due <br/> Amount </th>
							<!-- <th class="column-title">Paid </th> -->
                            <th style="text-align:center" class="column-title">Registered <br/> On </th>
                            <th style="text-align:center" class="column-title">Action </th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        if( isset($customer_list['result']) && $customer_list['result'] ) {
                            $i = $customer_list['start_count']+1;

                            foreach ($customer_list['result'] as $c_value) {
                                $customer_id = $c_value->customer_id;
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo $c_value->company_name; ?></td>
                                    <td class=""><?php echo $c_value->customer_name; ?></td>
                                    <td class=""><?php echo $c_value->mobile; ?></td>
                                    <td class="" style="width: 100px;"><?php echo $c_value->address; ?> </td>
                                    <td class=""><?php echo $c_value->gst_number; ?> </td>
                                    <td class=""><?php echo $c_value->new_sale_total1; ?></td>
                                    <td class=""><?php echo $final_bal = $c_value->final_bal; ?></td>
									<!-- <td>
                                   <?php  if($final_bal < 0){ ?>
                                    <input type="checkbox" name="ws_cur_bal_check" class="ws_cur_bal_check" data-amt="<?php echo $final_bal = $c_value->final_balance;  ?>" data-id="<?php echo $c_value->id; ?>" style="width: 20px;height: 18px;" >
                                   <?php  }
                                    ?>
                                     </td> -->
                                    <td class=""><?php echo $c_value->modified_at; ?></td>
                                    <td>
                                        <a href="<?php echo admin_url('admin.php?page=new_wholesale_customer')."&id=${customer_id}"; ?>" class="list_update">Update</a> / 
                                        <a href = "#" class="list_delete delete-ws-cus last_list_view" data-id="<?php echo $c_value->customer_id; ?>">Delete</a>
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
                    echo $customer_list['pagination'];
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
               <?php  echo $customer_list['status_txt']; ?>
            </div>
        </div>
<script>
//<-------Delete Lot------->
	jQuery('.ws_cur_bal_check').on('click',function(){
		var data=jQuery(this).attr("data-id");
		var amt=jQuery(this).attr("data-amt");
		jQuery(this).parent().parent().find('.ws_cur_bal_check').css('display','none');
		      jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action : 'ws_balance_paid',
                    id : data,
					amt : amt,
                },
                success: function (data) {
                    
					if(data.redirect != 0) { 
                        setTimeout(function() {
                            managePopupContent(data);
                        }, 1000);
                    }

                    if(data.success == 0) {
                        popItUp('Error', data.msg);
                    } else {
                        popItUp('Success', data.msg);
                    }
                
                }
            });
		
	});
  jQuery('.delete-ws-cus').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=wholesale_customer&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Lot------->


</script>