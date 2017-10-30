<?php
    $ppage = false;
    if(!$report) {
        $report = new report();
        $ppage = 10;
    }

    $result_args = array(
        'orderby_field' => 'cgst',
        'page' => $report->cpage,
        'order_by' => 'ASC',
        'items_per_page' => $report->ppage ,
        'condition' => '',
    );
    $return_report = $report->return_report_pagination($result_args);
?>

    <div class="x_content" style="width:100%;">
        <div class="table-responsive" style="width:400px;margin: 0 auto;margin-bottom:20px;">
            <table class="table table-striped jambo_table bulk_action">
                <thead>
                    <tr class="headings">
                        <th>Total Stock Sold Out</th>
                        <th>Total CGST(Rs)</th>
                        <th>Total SGST(Rs)</th>
                        <th>Total COGS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $return_report['s_result']->sold_qty; ?></td>
                        <td><?php echo $return_report['s_result']->total_cgst; ?></td>
                        <td><?php echo $return_report['s_result']->total_cgst; ?></td>
                        <td><?php echo $return_report['s_result']->sub_tot; ?></td>
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
                            <th class="column-title">Product name</th>
                            <th class="column-title">Brand Name</th>
                            <th class="column-title">Stock Sold Out</th>
							<th class="column-title">AMONUT</th>
                            <th class="column-title">CGST</th>
                            <th class="column-title">SGST</th>
                            <th class="column-title">CGST Amount</th>
                            <th class="column-title">SGST Amonut</th>
                            <th class="column-title">Cost Of Goods Sold(COGS)</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if( isset($return_report) && $return_report['result'] ) {
                            $i = $return_report['start_count']+1;

                            foreach ($return_report['result'] as $b_value) {
                                $bill_id = $b_value->id;
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo $b_value->product_name; ?></td>
                                    <td class=""><?php echo $b_value->brand_name; ?></td>
                                    <td class=""><?php echo round($b_value->return_unit); ?></td>
									<td class=""><?php echo $b_value->amt; ?></td>
                                    <td class=""><?php echo $b_value->cgst; ?> </td>
                                    <td class=""><?php echo $b_value->cgst; ?> </td>
                                    <td class=""><?php echo $b_value->cgst_value; ?></td>
                                    <td class=""><?php echo $b_value->cgst_value; ?></td>
                                    <td class=""><?php echo $b_value->subtotal; ?></td>
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
                    echo $return_report['pagination'];
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <?php  echo $return_report['status_txt']; ?>
            </div>
        </div>


        
<script>
    jQuery(document).ready(function($) {
        $('#welcome-panel').after($('#custom-id').show());
    });
</script>