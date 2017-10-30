<?php

    $result_args = array(
        'orderby_field' => 'gst',
        'page' => $report->cpage,
        'order_by' => 'ASC',
        'items_per_page' => $report->ppage ,
        'condition' => '',
    );
    $stock_report = $report->stock_report_pagination_accountant($result_args);
?>
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th>S.No</th>
                            <th class="column-title">Number of Goods Sold</th>
                            <th class="column-title">CGST</th>
                            <th class="column-title">SGST</th>
                            <th class="column-title">CGST Amount</th>
                            <th class="column-title">SGST Amonut</th>
                            <th class="column-title">Amount</th>
                            <th class="column-title">Cost Of Goods Sold(COGS)</th>                           
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if( isset($stock_report['result']) && $stock_report['result'] ) {
                            $i = $stock_report['start_count']+1;

                            foreach ($stock_report['result'] as $b_value) {
                                $bill_id = $b_value->id;
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo round($b_value->total_unit); ?></td>
                                    <td class=""><?php echo $b_value->gst; ?> </td>
                                    <td class=""><?php echo $b_value->gst; ?> </td>
                                    <td class=""><?php echo $b_value->cgst_value; ?></td>
                                    <td class=""><?php echo $b_value->cgst_value; ?></td>
                                    <td class=""><?php echo $b_value->amt; ?></td> 
                                    <td class=""><?php echo $b_value->total; ?></td>                               
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
                    echo $stock_report['pagination'];
                    ?>
                </div>
            </div>
            <div class="col-sm-5">
                <?php  echo $stock_report['status_txt']; ?>
            </div>
        </div>


        
<script>
    jQuery(document).ready(function($) {
        $('#welcome-panel').after($('#custom-id').show());
    });
</script>




