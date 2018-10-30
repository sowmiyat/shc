<?php
/**
 * Template Name: SRC Return Print Retail
 *
 * @package WordPress
 * @subpackage SHC
 */

    $bill_data = false;
    $invoice_id = '';
     if(isset($_GET['id']) && $_GET['id'] != '' ) {                                               

        if(isValidInvoiceReturn($_GET['id'])) {


            $update = true;
            $invoice_id = $_GET['id'];
            $bill_data = getBillDataReturn($invoice_id);
            $bill_fdata = $bill_data['bill_data'];
            $bill_ldata = $bill_data['ordered_data'];


        }
    }
$profile = get_profile1();
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

    <table cellspacing='' cellpadding='' WIDTH='100%' >
        <tr>
          <td>
            <table>
              <tr>            
                <td valign='top' WIDTH='50%'>Date :<?php echo date("d/m/Y"); ?> </td>     
              </tr>
              <tr> 
                <td valign='top' WIDTH='50%'>State :TAMILNADU </td>    
              </tr> 
              <tr> 
                <td valign='top' WIDTH='50%'>State Code :33 </td>   
              </tr>     
          </table>
        </td>
          <td>
            <table>
              <tr> 
                 <td valign='top' WIDTH='50%'>Name : <?php echo $bill_fdata->customer_name; ?></td> 
              </tr>              
              <tr>
                <td valign='top' WIDTH='50%'>Mobile :<?php echo $bill_fdata->mobile; ?> </td>  
              </tr>
              <tr>   
                <td valign='top' WIDTH='50%'>Address :<?php echo $bill_fdata->address; ?></td>       
              </tr>
          </table>
        </td>
      </tr>
      
    </table>
    <div class="text-center" ><b style="font-size:14px;">RETURN BILL</b></div>
    <table cellspacing='3' cellpadding='3' WIDTH='100%' >
      <tr>
        <td valign='top' WIDTH='70%'>Return No :  <?php echo $bill_fdata->return_id; ?> </td>    
        <td valign='top' WIDTH='100%'>Invoice No : <b><?php echo $bill_fdata->search_inv_id; ?></b></td>
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
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>RETURN <br/>QTY</th>
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>MRP</th>
       <!--  <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>Dis.Price</th> -->
        <th class="dotted_border_top dotted_border_bottom"  valign='top' align='center'>TOTAL</th>
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
      <td valign='top' align='center'><?php echo $d_value->return_unit; ?></td>
      <td valign='top' align='center'><?php echo $d_value->mrp; ?></td>
     <!--  <td valign='top' align='left'><?php echo $d_value->discount; ?></td> -->
      <td valign='top' align='right'><?php echo$d_value->sub_total; ?>&nbsp;&nbsp;&nbsp;</td></tr>

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
         <td class="dotted_border_top dotted_border_bottom" colspan="6" valign='top' align='right'><span class="amount"><b>TOTAL AMOUNT</b></span></td>
         <td  class="dotted_border_top dotted_border_bottom" valign='top' align='right'><span class="amount"><?php echo '<b>'.$bill_fdata->total_amount.'</b>'; ?>&nbsp;&nbsp;&nbsp;</span></td>
      </tr>
    </table>

          <?php
            }
          ?>
      <br/> 

      Amount Chargable ( In Words)
      <?php echo ucwords(convert_number_to_words_full($bill_fdata->total_amount)); ?>
    <table cellspacing='3' cellpadding='3' WIDTH='100%' class="table table-striped" style="" >
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
        <th style="padding: 0;width: 70px;"><div class="text-right">Amount</div></th>
        <th style="padding: 0;width: 70px;"><div class="text-center">Rate</div></th>
        <th style="padding: 0;width: 70px;"><div class="text-right">Amount</div></th>
      </tr>
      </thead>
      <tbody>

    <?php
        if($bill_data && $bill_ldata && count($bill_ldata)>0) {
          $i = 1;
          $gst_tot= 0;
          $total_tax = 0.00;
          foreach ($bill_ldata as $d_value) {
      ?>
         
          <tr class="">
            <td class=""><div class="text-center"><?php  echo $d_value->amt; ?></div></td>
            <td class=""><div class="text-center"><?php echo $d_value->cgst + 0; echo ' %'; ?></div></td>
            <td class=""><div class="text-center"><?php echo $d_value->cgst_value; ?></div></td>
            <td class=""><div class="text-center"><?php echo $d_value->sgst + 0; echo ' %';  ?> </div></td>
            <td class=""><div class="text-center"><?php echo $d_value->sgst_value; ?></div></td>
          </tr>
           <?php 
           $total_tax = ( 2 * $d_value->cgst_value) +$total_tax;
           $gst_tot = $d_value->cgst_value + $gst_tot;

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
  </div>
  <div style="margin-top:20px;">
      <b style="float:right;">Authorised Signatory</b>
  </div>
</div>
</body>
</html>