<?php
/**
 * Template Name: SRC Download Page
 *
 * @package WordPress
 * @subpackage SHC
 */

  $bill_data = false;
    $invoice_id = '';
    if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoice($_GET['id'], 1) ) {
            $update = true;
            $year = $_GET['year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getBillData($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $invoice_id['inv_id'] = $bill_fdata->inv_id;
            $bill_id = $bill_fdata->id;
            $gst_slab = gst_group_retail($bill_id);
            $gst_data = $gst_slab['gst_data'];
    }
?>
<!DOCTYPE html>
<html>
<head>
  <link rel='stylesheet' id='bootstrap-min-css'  href="<?php echo get_template_directory_uri(); ?>'/admin/inc/css/bootstrap.min.css'" type='text/css' media='all' />


</head>

<body>

  <div class="A4_HALF">
    <div class="sheet padding-10mm">
      <?php
          if($bill_data) {
      ?>
        <table cellspacing='3' cellpadding='3' WIDTH='100%' >
          <tr>
          <td valign='top' WIDTH='50%'><strong>Saravana Health Store</strong>
          <br/>7/12,Mg Road,Thiruvanmiyur,
          <br/>Chennai,Tamilnadu,
          <br/>Pincode-600041.
          <br/>Cell:9841141648

          <td valign='top' WIDTH='50%'>
              <table>
                <tr><td>Inv No</td><td>: <?php echo 'INV '.$bill_fdata->inv_id; ?></td></tr>
                <tr><td>Name</td><td>: <?php echo $bill_fdata->customer_name; ?></td></tr>
                <tr><td>Date</td><td>: <?php echo date("d/m/Y"); ?></td></tr>
                <tr><td>Mobile</td><td>: <?php echo $bill_fdata->mobile; ?></td></tr>
                <tr><td>Addr</td><td>: <?php echo $bill_fdata->address; ?></td></tr>
              </table>
          </td>
          </tr>
        </table>

        <br />
        <br/>
        <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" >
          <tr>
          <th valign='top'>SNO</th>
          <th valign='top'>PRD</th>
          <th valign='top'>HSN</th>
          <th valign='top'>QTY</th>
          <th valign='top'>MRP</th>
          <th valign='top'>Dis.Price</th>
          <th valign='top'>SUB TOTAL</th>

          </tr>
          <tr>

          </tr>
          <?php
              if($bill_data && $bill_ldata && count($bill_ldata)>0) {
                  $i = 1;
                  foreach ($bill_ldata as $d_value) {
          ?>
                                
          <tr>
          <td valign='top' align='center'><?php echo $i; ?></td>
          <td valign='top'><?php echo $d_value->product_name; ?></td>
          <td valign='top'><?php echo $d_value->hsn; ?></td>
          <td valign='top' align='left'><?php echo $d_value->sale_unit; ?></td>
          <td valign='top' align='left'><?php echo $d_value->unit_price; ?></td>
          <td valign='top' align='left'><?php echo $d_value->discount; ?></td>
          <td valign='top' align='left'><?php echo $d_value->sub_total; ?></td></tr>

          <?php
                $i++;
                  }
                } 
              ?>  
        </table>
      <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
          <tr>
            <td valign='top' align='center'><b>NET AMOUNT:</b></td>
              <td valign='top' align='left' style="width:62px;"><span class="amount"><?php echo '<b>'.$bill_fdata->sub_total.'</b>'; ?></span></td>
          </tr>
          <tr>
            <td valign='top' align='right'>Discount:</td>
              <td valign='top' align='left'><span class="amount"><?php if($bill_fdata->discount_type == 'cash') {
                                  echo $bill_fdata->discount; }
                                  else {
                                    echo $bill_fdata->discount + 0;
                                    echo '%';
                                  }
                                  ?></span></td>
          </tr>
          <tr>
            <td valign='top' align='right'>Paid Amount:</td>
              <td valign='top' align='left'><span class="amount"><?php echo $bill_fdata->paid_amount; ?></span></td>
          </tr>
          <tr>
            <td valign='top' align='right'>Balance:</td>
              <td valign='top' align='left'><span class="amount"><?php echo $bill_fdata->return_amt; ?></span></td>
          </tr>
      </table>

          <?php
            }
          ?>
                <!-- <table class="table table-bordered" style="margin-top:10px;margin-bottom: 5px;width: 120mm;"> -->
        <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
          <thead>
            <tr>
              <th class="center-th" style="width:90px;padding:0;" rowspan="2">
                <div class="text-center">Taxable Value</div>
              </th>
              <th class="center-th" style="padding: 0;" colspan="2">
                <div class="text-center">CGST</div>
              </th>
              <th class="center-th" style="padding: 0;" colspan="2">
                <div class="text-center">SGST</div>
              </th>
            </tr>
            <tr>
              <th style="padding: 0;width: 70px;"><div class="text-center">Rate</div></th>
              <th style="padding: 0;width: 70px;"><div class="text-center">Amount</div></th>
              <th style="padding: 0;width: 70px;"><div class="text-center">Rate</div></th>
              <th style="padding: 0;width: 70px;"><div class="text-center">Amount</div></th>
            </tr>
          </thead>
          <tbody>


            <?php  if(isset($gst_data)) { 
                      $total_tax=0;
                            foreach( $gst_data as $g_data) {

                     ?>
                    <tr class="">
                        <td class=""><div class="text-right">Rs. <?php  echo $g_data->sale_amt; ?></div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->cgst; ?> % </div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->sale_cgst; ?></div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->cgst; ?> % </div></td>
                        <td class=""><div class="text-right"><?php echo $g_data->sale_sgst; ?></div></td>
                    </tr>
                      <?php $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
                            }
                          } ?>
                  <td colspan="4">
                    <div class="text-center">
                      Total Tax
                    </div>
                  </td>
                  <td>
                    <div class="text-right">
                     <?php echo $total_tax; ?>
                    </div>
                  </td>
                </tr>
          </tbody>
        </table>
    </div>

</div>
</body>
</html>