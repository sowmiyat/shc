<?php
/**
 * Template Name: Ws Delivery Print
 *
 * @package WordPress
 * @subpackage SHC
 */

    $bill_data = false;
    $invoice_id = '';
    if(isset($_GET['id']) && $_GET['id'] != '' && isValidInvoicews($_GET['id'],$_GET['cur_year'],1) ) {
          $update = true;
            $year = $_GET['cur_year'];
            $invoice_id['invoice_id'] = $_GET['id'];
            $bill_data = getBillDataws($invoice_id['invoice_id'],$year);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];
            $bill_rdata = $bill_data['returned_data'];
            $invoice_id['inv_id'] = $bill_fdata->inv_id;
            $bill_id = $bill_fdata->id;
            $gst_slab = gst_group($bill_id);
            $gst_data = $gst_slab['gst_data'];
    }
    $profile = get_profile1();
    $total_paid = 0;
    foreach ($bill_pdata as $p_value) {
      $total_paid = $p_value->amount +  $total_paid;
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
		<div class="text-center"><b>DELIVERY BILL</b></div>
		<table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
        <td valign='top' WIDTH='70%'>Inv No : <b><?php echo 'Inv '.$bill_fdata->inv_id; ?></b></td>
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
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>SNO</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>PRODUCT</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>HSN CODE</th>
			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>DELIVERY <br/>QTY</th>

			  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>TOTAL</th>
		  </tr>
		  <tr>

		  </tr>
		  <?php
			  if($bill_data && $bill_ldata && count($bill_ldata)>0) {
				  $i = 1;
				  foreach ($bill_ldata as $d_value) {
            $sal_qty = $d_value->sale_unit;
            $delivered = $d_value->delivery_count;
            $delivery_count = $sal_qty - $delivered;
            if($delivery_count !=0){
		  ?>
								
		  <tr>
		  <td valign='top' align='center'><?php echo $i; ?></td>
		  <td valign='top' align='center'><?php echo $d_value->product_name; ?></td>
		  <td valign='top' align='center'><?php echo $d_value->hsn; ?></td>
		  <td valign='top' align='center'><?php echo  $delivery_count;?></td>

		  <td valign='top' align='right'><?php echo $d_value->sub_total; ?>&nbsp;&nbsp;&nbsp;</td></tr>

		  <?php
  				$i++;
  				  }
          }
				} 
			  ?> 

		 
		</table>


	  	<table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped">
		<tr> 
	  	 <td class="dotted_border_top dotted_border_bottom" colspan="6" valign='top' align='center'>TOTAL AMOUNT</td>
	  	 <td  class="dotted_border_top dotted_border_bottom" valign='top' align='right'><span class="amount"><?php  echo $sub_tot = $bill_fdata->sub_total; ?>&nbsp;&nbsp;&nbsp;</span></td>
		</tr>
		<tr> 
	  	 <td class="dotted_border_bottom" colspan="6" valign='top' align='center'>PAID AMOUNT</td>
	  	 <td  class=" dotted_border_bottom" valign='top' align='right'><span class="amount"><?PHP echo $total_paid; ?>&nbsp;&nbsp;&nbsp;</span></td>
		</tr>
		<tr> 
	  	 <td class="dotted_border_bottom" colspan="6" valign='top' align='center'><b>BALANCE AMOUNT</b></td>
	  	 <td  class=" dotted_border_bottom" valign='top' align='right'><span class="amount"><?php echo (($sub_tot-$total_paid) > 0 )? $sub_tot-$total_paid : 0 ; ?>&nbsp;&nbsp;&nbsp;</span></td>
		</tr>
	  	</table>

          <?php
            }
          ?>
		  <br/>
         <!-- <table class="table table-bordered" style="margin-top:10px;margin-bottom: 5px;width: 120mm;"> -->

		<!-- <div style="text-align: center;" >Thank You !!!. Visit Again !!!.</div> -->
	<table>
	    <tr><td>Delivery To</td><td>  </td></tr>
	    <tr><td>Company Name</td><td>  : <?php echo $bill_fdata->home_delivery_name; ?></td></tr>
	    <tr><td>Address</td><td>  : <?php echo $bill_fdata->home_delivery_mobile; ?></td></tr>
	    <tr><td>Phone No</td><td>  : <?php echo $bill_fdata->home_delivery_address ; ?></td></tr>
	  </table> 
	</div>

</div>








</body>
</html>