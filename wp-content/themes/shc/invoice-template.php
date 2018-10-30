<?php
/**
 * Template Name: SRC Invoice
 *
 * @package WordPress
 * @subpackage SHC
 */

    $bill_data = false;
    $invoice_id = '';
    if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoice($_GET['id'],$_GET['cur_year'],1) ) {
            $update = true;
            $year = $_GET['cur_year'];
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
      $profile = get_profile1();
      $netbank = get_netbank1();
      $payment_type = get_paymenttype($_GET['id'],$_GET['year']);

 $internet_check = '';
foreach ($payment_type as $p_value) {
	if($p_value->payment_type == 'internet'){
		$internet_check = $p_value->payment_type; 	
	}		
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
      font-family: normal;
      font-size: 13px;
      
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
    font-size: 14px
  }


      .A4_HALF {
        width: 100mm;
      }
      .inner-container {
        padding-left: 20mm;
        padding-right: 20mm;
        width: 100mm;

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
	.exempted span{
        margin-left: 40%;
        font-size: 14px;
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
					  <strong><?php echo $profile ? $profile->company_name : '';  ?></strong>
					  <span style=" font-size: 13px;line-height:12px; " ><br/><?php echo $profile ? $profile->address : '';  ?>
					  <br/><?php echo $profile ? $profile->address2 : '';  ?> 	 
					  <br/>PH : <?php echo $profile ? $profile->phone_number : '';  ?>
					  <br/>GST No : <?php echo $profile ? $profile->gst_number : '';  ?></span>
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
	        <td valign='top' WIDTH='70%'>Inv No : <b><?php echo $bill_fdata->inv_id; ?></b></td>
	        <td valign='top' WIDTH='100%'>Date : <?php $timestamp = $bill_fdata->modified_at; 
	        $splitTimeStamp = explode(" ",$timestamp);
	        echo $date = $splitTimeStamp[0];?></td>    
	      </tr>
	      <tr>
	        <td valign='top' WIDTH='70%'></td>
	        <td valign='top' WIDTH='80%'>Time : <?php
	        echo $time = $splitTimeStamp[1]; ?></td>     
	      </tr>
	      <tr>
	        <!-- <td valign='top' WIDTH='30%'>Date : <?php echo date("d/m/Y"); ?></td> -->
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
				  <th class="dotted_border_top dotted_border_bottom text-center"  valign='top'>SNO</th>
				  <th class="dotted_border_top dotted_border_bottom text-center"  valign='top'>PRODUCT</th>
				  <th class="dotted_border_top dotted_border_bottom text-center"  valign='top'>HSN CODE</th>
				  <th class="dotted_border_top dotted_border_bottom text-center"  valign='top'>QTY</th>
				  <th class="dotted_border_top dotted_border_bottom text-center"  valign='top'>MRP</th>
				 <!--  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>Dis.Price</th> -->
				  <th class="dotted_border_top dotted_border_bottom text-center"  valign='top'>TOTAL</th>
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
			  <td valign='top' align='center'><?php echo $d_value->product_name; ?></td>
			  <td valign='top' align='center'><?php echo $d_value->hsn; ?></td>
			  <td valign='top' align='center'><?php echo $d_value->sale_unit; ?></td>
			  <td valign='top' align='center'><?php echo $d_value->unit_price; ?></td>
			  <td valign='top' align='right'><?php echo $d_value->total; ?>&nbsp;&nbsp;&nbsp;</td></tr>

			  <?php
					$i++;
					  }
					} 
				  ?> 

			 
			</table>


		  	<table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
			  <!-- <tr>
				  <td valign='top' colspan="3" >No.Of.Items : 4    </td>
				  <td valign='top'  align='right' >Total Qty : 10&nbsp;&nbsp;&nbsp;</td>
			  </tr> -->
			  
			   <tr> 
			  	 <td class="dotted_border_top " colspan="6" align='right'><span class="amount"><b>AMOUNT</b></span></td>
			  	 <td  class="dotted_border_top " align='right'><span class="amount"><?php echo '<b>'.$bill_fdata->before_total.'</b>'; ?>&nbsp;&nbsp;&nbsp;</span></td>
			  </tr>
			   <?php if( $bill_fdata->before_total != $bill_fdata->sub_total) { ?>
			  <tr> 
			  	 <td class="dotted_border_top " colspan="6" align='right'><span class="amount"><b>DISCOUNT</b></span></td>
			  	 <td  class="dotted_border_top " align='right'><span class="amount">				  	
			  	 	<?php 
							
						$before_total = $bill_fdata->before_total;	
						$after_total  = $bill_fdata->sub_total;
						$discount = $before_total - $after_total;
						echo '<b>'.$discount .'.00</b>';
						?>&nbsp;&nbsp;&nbsp;</span></td>
			  </tr>
			  <tr> 
			  	 <td class="dotted_border_top dotted_border_bottom" colspan="6"  align='right'><span class="amount"><b>TOTAL AMOUNT</b></span></td>
			  	 <td  class="dotted_border_top dotted_border_bottom"  align='right'><span class="amount"><?php echo '<b>'.$bill_fdata->sub_total.'</b>'; ?>&nbsp;&nbsp;&nbsp;</span></td>
			  </tr>
			   <?php } ?>
		  </table>

	          <?php
	            }
	          ?>
			  <br/>
			  	<?php 
			        if(isset($gst_data)) {
			          $total_tax=0;
			          foreach( $gst_data as $g_data) {
			            $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
			            $gst_tot = $g_data->sale_sgst + $gst_tot;
			          }
			          if($gst_tot == '0.00'){
			            echo "<div class='exempted'><span><b>GST EXEMPTED</span></b></div>";
			          }
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
				  <th style="padding: 0;width: 70px;"><div class="text-center">%</div></th>
				  <th style="padding: 0;width: 70px;"><div class="text-right">Amount</div></th>
				  <th style="padding: 0;width: 70px;"><div class="text-center">%</div></th>
				  <th style="padding: 0;width: 70px;"><div class="text-right">Amount</div></th>
				</tr>
			  </thead>
			  <tbody>

				<?php  
				if(isset($gst_data)) { 
				  	$total_tax=0;
				  	$gst_tot=0;
					foreach( $gst_data as $g_data) {
				?>
						<tr class="">
							<td class=""><div class="text-center"><?php  echo $g_data->sale_amt; ?></div></td>
							<td class=""><div class="text-center"><?php echo $g_data->cgst; ?> % </div></td>
							<td class=""><div class="text-center"><?php echo $g_data->sale_cgst; ?></div></td>
							<td class=""><div class="text-center"><?php echo $g_data->cgst; ?> % </div></td>
							<td class=""><div class="text-center"><?php echo $g_data->sale_sgst; ?></div></td>
						</tr>
						 <?php 
						 $total_tax = ( 2 * $g_data->sale_sgst) +$total_tax;
						 $gst_tot = $g_data->sale_sgst + $gst_tot;

					}
				} ?>
				<tr class="">
					<td class=""><div class="text-center"></div></td>
					<td class=""><div class="text-center"></div></td>
					<td class=""><div class="text-center"><?php echo $gst_tot; ?></div></td>
					<td class=""><div class="text-center"></div></td>
					<td class=""><div class="text-center"><?php echo $gst_tot; ?></div></td>
				</tr>
				<tr>
				  <td  class="dotted_border_bottom" colspan="4">
					<div class="text-center">
					  <b>Total Tax</b>
					</div>
				  </td>
				  <td class="dotted_border_bottom" >
					<div class="text-center">
					 <b><?php echo $total_tax; ?></b>
					</div>
				  </td>
				</tr>
			  </tbody>
			</table>
			<div style="text-align: center;" >Thank You !!!. Visit Again !!!.</div>
			    <?php $is_delivery = $bill_fdata->is_delivery; 
    			if($is_delivery == '1') { ?>
				 <table>

				    <tr><td><b>Delivery To</b></td><td>  </td></tr>
				    <tr><td>Name</td><td>  : <?php echo $bill_fdata->home_delivery_name; ?></td></tr>
				    <tr><td>Address</td><td>  : <?php echo $bill_fdata->home_delivery_mobile; ?></td></tr>
				    <tr><td>Phone No</td><td>  : <?php echo $bill_fdata->home_delivery_address ; ?></td></tr>
			  	</table> 
		    <?php } ?>
		    <?php if($internet_check == 'internet'){ ?>
				  <table>
				    <tr> <td><b>Banking Details,</b></td><td></td></tr>
				    <tr> <td>Name</td><td> : <?php echo $netbank ? $netbank->shop_name : ''; ?></td></tr>
				    <tr> <td>Bank Name</td><td> : <?php echo $netbank ? $netbank->bank : ''; ?></td></tr>
				    <tr> <td>Account Number</td><td> : <?php echo $netbank ? $netbank->account : ''; ?></td></tr>
				    <tr> <td>IFSC Code</td><td> : <?php echo $netbank ? $netbank->ifsc : ''; ?></td></tr>
				    <tr> <td>Account Type</td><td> : <?php echo $netbank ? $netbank->account_type : ''; ?></td></tr>
				    <tr> <td>Branch</td><td> : <?php echo $netbank ? $netbank->branch : ''; ?></td></tr>
				  </table>
		<?php   } ?>
		</div>

	</div>
</body>
</html>