<?php
/**
 * Template Name: SRC Invoice
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

<meta charset="utf-8">
<style>

	
		
	 @media screen {
    .A4_HALF .footer {
      bottom: 0px;
      left: 0px;
    }
    .A4_HALF .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    }

  }
  /** Fix for Chrome issue #273306 **/
  @media print {
    #adminmenumain, #wpfooter, .print-hide {
      display: none;
    }
    body, html {
      height: auto;
      padding:0px;
    }
    html.wp-toolbar {
      padding:0;
    }
    #wpcontent {
      background: white;
      box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
      margin: 1mm;
      display: block;
      padding: 0;
    }




    .A4_HALF .footer {
      position: fixed;
      bottom: 0px;
      left: 0px;
    }
    .A4_HALF .footer .foot {
        background-color: #67a3b7 !important;
        -webkit-print-color-adjust: exact;
    }
  }

  @page { margin: 0;padding: 0; }
  .sheet {
    margin: 0;
  }


      .A4_HALF {
        width: 100mm;
      }
      .inner-container {
        padding-left: 20mm;
        padding-right: 20mm;
        width: 110mm;
      }
      .left-float {
        float: left;
      }

      .text-center {
        text-align: center;
      }
      .text-rigth {
        text-align: right;
      }
      .table td, .table th {
        background-color: transparent !important;
      }


      .table>tbody>tr>td {
        padding: 0 3px;
        height: 20px;
      }
      .table-bordered>tbody>tr>td, .table-bordered>thead>tr>th {
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
      }

      .A4_HALF h3 {
        margin-top: 10px;
      }


        
      
</style>   
</head>

<body>

 <div class="A4_HALF">
	<div class="sheet padding-10mm">
	  <?php
		  if($bill_data) {
	  ?>
		<table cellspacing='3' cellpadding='3' WIDTH='100%' >
		  <tr class="text-center" >
			  <td valign='top' WIDTH='50%'>
				  <strong>Saravana Health Store</strong>
				  <span style=" font-size: 13px; " ><br/>7/12,Mg Road,Thiruvanmiyur,
				  <br/>Chennai-41, Tamilnadu.		 
				  <br/>PH : 9841141648
				  <br/>GST No : 33BMDPA4840E1ZP</span>
			  </td>
		  </tr>
		</table>

		<table cellspacing='3' cellpadding='3' WIDTH='100%' >
		  <tr>
			  <td valign='top' WIDTH='50%'>Customer : <?php echo $bill_fdata->customer_name; ?> </td>			 	  
			  <td valign='top' WIDTH='50%'>Address : <?php echo $bill_fdata->address; ?></td>			 	  
		  </tr>
		  <tr>			  
			  <td valign='top' WIDTH='50%'>Phone No :<?php echo $bill_fdata->mobile; ?> </td>		  
		  </tr>
		  
		</table>
		<div class="text-center" >CASH BILL</div>
		<table cellspacing='3' cellpadding='3' WIDTH='100%' >
		  <tr>
			  <td valign='top' WIDTH='70%'>Inv No : <b><?php echo 'INV '.$bill_fdata->inv_id; ?></b></td>
			  <td valign='top' WIDTH='70%'>Time : </td>		  
		  </tr>
		  <tr>
		  	<td valign='top' WIDTH='30%'>Date : <?php echo date("d/m/Y"); ?></td>
		  	<td valign='top' WIDTH='30%'> </td>
		  </tr>
		</table>
		<style>
			.dotted_border_top  {
				border-top: 1px dashed #000;				

			}
			.dotted_border_bottom  {				
				border-bottom: 1px dashed #000;
			}
		</style>

		<table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" >
			<tr>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>SNO</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>PRD</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>HSN</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>QTY</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>MRP</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>Dis.Price</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>SUB TOTAL</th>
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
		  <td valign='top' align='right'><?php echo $d_value->sub_total; ?>&nbsp;&nbsp;&nbsp;</td></tr>

		  <?php
				$i++;
				  }
				} 
			  ?> 

		  <tr> 
		  	 <td class="dotted_border_top dotted_border_bottom" colspan="6" valign='top' align='center'><b>NET AMOUNT:</b></td>
		  	 <td  class="dotted_border_top dotted_border_bottom" valign='top' align='right'><span class="amount"><?php echo '<b>'.$bill_fdata->sub_total.'</b>'; ?>&nbsp;&nbsp;&nbsp;</span></td>
		  </tr>
		</table>


	  <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
		  <!-- <tr>
			  <td valign='top' colspan="3" >No.Of.Items : 4    </td>
			  <td valign='top'  align='right' >Total Qty : 10&nbsp;&nbsp;&nbsp;</td>
		  </tr> -->
		  <tr>
			<td colspan="3" valign='top'  align='right' >Discount:</td>
			  <td valign='top' align='right' >
				  <span class="amount">
				  	<?php 
				  	if($bill_fdata->discount_type == 'cash') {
						echo $bill_fdata->discount; 
					} else {
								echo $bill_fdata->discount + 0;
								echo '%';
							}
					?>
						
					</span>
				</td>
		  </tr>
		  <tr>
			<td colspan="3" valign='top'  align='right'>Paid Amount:</td>
			<td valign='top' align='right'><span class="amount"><?php echo $bill_fdata->paid_amount; ?>&nbsp;&nbsp;&nbsp;</span></td>
		  </tr>
		  <tr>
			<td  colspan="3" align='right' class=" dotted_border_bottom" valign='top' align='right'>Balance:</td>
			<td  class=" dotted_border_bottom" valign='top' align='right'><span class="amount"><?php echo $bill_fdata->return_amt; ?>&nbsp;&nbsp;&nbsp;</span></td>
		  </tr>
	  </table>

          <?php
            }
          ?>
                <!-- <table class="table table-bordered" style="margin-top:10px;margin-bottom: 5px;width: 120mm;"> -->
		<table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style="font-size:15px;" >
		  <thead>
		  	<tr>
		  		<th colspan="5" class="dotted_border_bottom"  align="center" >GST Details</th>
		  	</tr>		  
			<tr>
			  <th valign='top' class="center-th" style="width:90px;padding:0;" rowspan="2">
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


			<?php  
			if(isset($gst_data)) { 
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
					 <?php 
					 $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
				}
			} ?>
			<tr class="">
				<td class=""><div class="text-right"></div></td>
				<td class=""><div class="text-right"></div></td>
				<td class=""><div class="text-right"><?php echo $g_data->sale_cgst; ?></div></td>
				<td class=""><div class="text-right"></div></td>
				<td class=""><div class="text-right"><?php echo $g_data->sale_sgst; ?></div></td>
			</tr>
			<tr>
			  <td  class="dotted_border_bottom" colspan="4">
				<div class="text-center">
				  <b>Total Tax</b>
				</div>
			  </td>
			  <td class="dotted_border_bottom" >
				<div class="text-right">
				 <b><?php echo $total_tax; ?></b>
				</div>
			  </td>
			</tr>
		  </tbody>
		</table>
		<div style="text-align: center;" >Thank You !!!. Visit Again !!!.</div>
	</div>

</div>








</body>
</html>