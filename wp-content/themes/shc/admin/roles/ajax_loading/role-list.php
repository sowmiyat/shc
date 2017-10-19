<?php
    $result_args = array(
        'orderby_field' => 'id',
        'page' => $admin_roles->cpage,
        'order_by' => 'DESC',
        'items_per_page' => $admin_roles->ppage ,
        'condition' => '',
    );
    $admin_role_list = $admin_roles->user_list_pagination($result_args);

?>
        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped jambo_table bulk_action">
                    <thead>
                        <tr class="headings">
                            <th>
                                S.No
                            </th>
                            <th class="column-title">Role Nname </th>
                            <th class="column-title">Role Permission</th>
                            <th class="column-title">Action </th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        if( isset($admin_role_list['result']) && $admin_role_list['result'] ) {
                            //$i = $admin_role_list['start_count']+1;
                            $i = 1;
                            foreach ($admin_role_list['result'] as $ar_key => $ar_value) {
                    ?>
                                <tr class="odd pointer">
                                    <td class="a-center ">
                                        <?php echo $i; ?>
                                    </td>
                                    <td class=""><?php echo $ar_value['name']; ?></td>
                                    <td class="">
                                        
                                        <?php
                                            $j = 1;
                                            $sep = ' ';
                                            $cap_total = count($ar_value['capabilities']);
                                            foreach ($ar_value['capabilities'] as $key => $value) {

                                                if($admin_role_list['capabilities_order'][$key]) {
                                                    if($j  < $cap_total) {
                                                        $sep = ', ';
                                                    } else {
                                                        $sep = '';
                                                    }
                                                    echo $admin_role_list['capabilities_order'][$key].$sep;
                                                }
                                                $j++;
                                            }
                                        ?>

                                    </td>
                                    <td class=""><a href="<?php echo menu_page_url( 'add_admin_role', 0 )."&role_slg=${ar_key}"; ?>">Update</a></td>
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
                    echo $admin_role_list['pagination'];
                    ?>
                </div>
            </div>

        </div>
