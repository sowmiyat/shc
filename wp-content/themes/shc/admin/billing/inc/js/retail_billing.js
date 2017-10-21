 jQuery(document).ready(function (argument) {
    //** Customer Select billing **//
jQuery('#billing_customer').focus();
    populateReturnProductsRetail('.rtn_lot_id');


jQuery('#billing_mobile').live('keydown', function(e){
    var keyCode = e.keyCode || e.which; 
    if (keyCode == 40) { 

        if(jQuery('#ui-id-1').css('display') != 'block') {
            e.preventDefault();
            jQuery('.secondary_mobile').css('display', 'block');
            jQuery('#billing_secondary_mobile').focus();
        }

    }
});

jQuery('.retail_unit').live('change keyup',function(){
    var stock = parseFloat(jQuery('.ws_slab_sys_txt').val());
    var unit = parseFloat(jQuery('#retail_unit').val());
    if(unit > stock){
        alert('Enter Quantity as small as avalible stock!!!');
        jQuery('.retail_unit').val(Math.ceil(stock));
    }
});




jQuery('#billing_secondary_mobile').live('keydown', function(e){
    var keyCode = e.keyCode || e.which; 
    if (keyCode == 40) { 
        e.preventDefault(); 
        jQuery('.landline_mobile').css('display', 'block');
        jQuery('#billing_landline_mobile').focus();
    }
    if (keyCode == 38) { 
        e.preventDefault(); 
        jQuery('.secondary_mobile').css('display', 'none');
        jQuery('#billing_secondary_mobile').val('');
        jQuery('#billing_mobile').focus();
    }
});


jQuery('#billing_landline_mobile').live('keydown', function(e){ console.log(e);
    var keyCode = e.keyCode || e.which; 
    if (keyCode == 38) { 
        e.preventDefault(); 
        jQuery('.landline_mobile').css('display', 'none');
        jQuery('#billing_landline_mobile').val('');
        jQuery('#billing_secondary_mobile').focus();
    }
});


//<-----After keydown submit using tab goto first text box--->
 jQuery("#submit_payment").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#billing_customer').focus();
      } 

    });




    // jQuery("#billing_customer").on('change',function(){
    //     var alphanumers = /^[a-zA-Z0-9]+$/;
    //     if(!alphanumers.test(jQuery("#billing_customer").val())){
    //         alert("name can have only alphabets and numbers.");
    //         jQuery("#billing_customer").val('');
    //     }

    // });

    // jQuery("#billing_address").on('change',function(){
    //     var alphanumers = /^[a-zA-Z0-9]+$/;
    //     if(!alphanumers.test(jQuery("#billing_address").val())){
    //         alert("address can have only alphabets and numbers.");
    //         jQuery("#billing_address").val('');
    //     }

    // });

    
jQuery( "#billing_customer, #billing_mobile" ).autocomplete ({
      source: function( request, response ) {
        var billing_field = jQuery(this.element).attr('id');
        jQuery.ajax( {
          url: frontendajax.ajaxurl,
          type: 'POST',
          dataType: "json",
          data: {
            action: 'get_customer_name',
            search_key: request.term
          },
          success: function( data ) {
            response(jQuery.map( data.result, function( item ) {

                if(billing_field == 'billing_customer') {
                    var field_val = item.name;
                    var identification = 'name';
                } else {
                    var field_val = item.mobile;
                    var identification = 'mobile';
                }


                return {
                    id: item.id,
                    value: field_val,
                    address : item.address,
                    name : item.name,
                    mobile : item.mobile,
                    secondary_mobile : item.secondary_mobile,
                    landline : item.landline,
                    identification : identification 

                }
            }));
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {



         jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                id      : ui.item.id,
                action  :'customer_balance'
            },
              success: function (data) {
                
                jQuery('.balance_amount').text(data);
                jQuery('.balance_amount_val').val(data);
               

            }
        });

         if(ui.item.identification == 'mobile' ) {
            jQuery('#billing_mobile').val(ui.item.value);
            jQuery('#billing_customer').val(ui.item.name);
        } else {
            jQuery('#billing_mobile').val(ui.item.mobile);
            jQuery('#billing_customer').val(ui.item.value);
        }

        jQuery('#billing_secondary_mobile').val(ui.item.secondary_mobile);
        jQuery('#billing_landline_mobile').val(ui.item.landline);
        jQuery('.old_customer_id').val(ui.item.id);
        jQuery('#billing_address').val(ui.item.address);
        console.log(ui.item.address);
       

        jQuery('.lot_id').focus();
        jQuery('.paid_amount').trigger('change');



        console.log( "id: " + ui.item.id);
      }
    });
//<------ Select Product ------>

  populateSelect2('.lot_id', 'old');


   jQuery('.retail_sub_unit').live('change keyup',function(){

        var unit = parseFloat(jQuery(this).parent().parent().find('.retail_sub_unit').val());
        var stock = parseFloat(jQuery(this).parent().parent().find('.retail_sub_stock').val());
        if( unit > stock){
            alert('Enter Quantity as small as avalible stock!!!');
            jQuery(this).parent().parent().find('.retail_sub_unit').val(Math.ceil(stock));
        }

        if(unit <= '0'){
            alert("please enter unit!!!");
            jQuery(this).parent().parent().find('.retail_sub_unit').focus();
            jQuery(this).parent().parent().find('.retail_sub_unit').val('1');
        }
        
             rowCalculate();
       
        
    });

    jQuery('.retail_sub_discount').live('change keyup',function(){

        jQuery(this).parent().parent().find('.discount_type').val('each');
        var unit = jQuery(this).parent().parent().find('.retail_sub_discount').val();

        if(unit <= '0'){
            alert("please enter price!!!");
            jQuery(this).parent().parent().find('.retail_sub_discount').focus();
            jQuery(this).parent().parent().find('.retail_sub_discount').val('1');
        }
        
        rowCalculate();
        
    });


  jQuery('.discount').live('change keyup',function() {
    rowCalculate();
  });



   jQuery('.paid_amount').on('change keyup',function(){
        var prev_bal = parseFloat(jQuery('.balance_amount_val').val());

        var current_bal = parseFloat(jQuery('.fsub_total').val());
        var paid = parseFloat(jQuery('.paid_amount').val());
        var bal = (prev_bal + current_bal) - paid;
        jQuery('.return_amt').val(bal);
        jQuery('.return_amt_txt').text(bal);

    });



  jQuery('.payment_pay_type').on('click',function(){
    if(jQuery('.payment_pay_type:checked').val() == 'Internet Banking'){
        jQuery('.payment_details_card').css("display","none");
        jQuery('.payment_details_cheque').css("display","none");
        jQuery('.payment_details_internet').css("display","block");

    }
     if(jQuery('.payment_pay_type:checked').val() == 'cash'){
        jQuery('.payment_details_card').css("display","none");
        jQuery('.payment_details_cheque').css("display","none");
        jQuery('.payment_details_internet').css("display","none");
           
    }
     if(jQuery('.payment_pay_type:checked').val() == 'card'){
        jQuery('.payment_details_card').css("display","block");
        jQuery('.payment_details_cheque').css("display","none");
        jQuery('.payment_details_internet').css("display","none");
           
    }
     if(jQuery('.payment_pay_type:checked').val() == 'cheque'){
        jQuery('.payment_details_card').css("display","none");
        jQuery('.payment_details_cheque').css("display","block");
        jQuery('.payment_details_internet').css("display","none");
        
    }

     if(jQuery('.payment_pay_type:checked').val() == 'credit'){
        jQuery('.payment_details_card').css("display","none");
        jQuery('.payment_details_cheque').css("display","none");
        jQuery('.payment_details_internet').css("display","none");
        
    }
  });

//<--- Add table data-----> 

    jQuery('.retailer_add-button').live('click',function() {

       

        var product_id          = jQuery('.retail_lot_id_orig').val();
        var product_name        = jQuery('.retail_product').val();
        var hsn_code            = jQuery('.retail_hsn').val();
        var stock               = jQuery('.ws_slab_sys_txt').val();
        var price               = jQuery('.retail_unit_price').val();
        var unit                = jQuery('.retail_unit').val();
        var discount            = jQuery('.retail_discount').val();
        var cgst                = jQuery('.retail_cgst_percentage').val();
        var sgst                = jQuery('.retail_sgst_percentage').val();

       
    
       if( !!product_id && unit !='0' &&  unit != '' && discount != '0.00' &&  discount != '' && discount != '0') {
            var existing_count = parseInt( jQuery('#bill_lot_add_retail tr').length );
            var current_row = existing_count + 1;
            if( jQuery('.customer_table_retail[data-productid='+ product_id +']').length != 0 ) {
                var selector = jQuery('.customer_table_retail[data-productid='+ product_id +']');
                var actual_unit = selector.find('.retail_sub_unit').val();
                var final_unit = parseFloat(unit) + parseFloat(actual_unit);
                selector.find('.retail_sub_unit').val(final_unit);

                
            } else {
                var str = '<tr data-randid='+makeid()+' data-productid='+product_id+' class="customer_table_retail" ><td class="td_id">'+current_row+'</td> <input type="hidden" value="'+ product_id + '" name="customer_detail['+current_row+'][id]" class="sub_id" /><td class="td_product">' + product_name + '</td> <input type="hidden" value = "'+ product_name + '" name="customer_detail['+current_row+'][product]" class="sub_product"/><td class="td_hsn">' + hsn_code + '</td> <input type="hidden" value = "'+ hsn_code + '" name="customer_detail['+current_row+'][hsn]" class="sub_hsn"/><td class=""><input type="text" value = "'+ unit + '" name="customer_detail['+current_row+'][unit]" class="retail_sub_unit"/> </td>  <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][stock]" class="retail_sub_stock"/><td class="td_price">' + price + '</td> <input type="hidden" value = "'+ price + '" name="customer_detail['+current_row+'][price]" class="sub_price"/> <td><input type="text" value ="'+ discount +'" name="customer_detail['+current_row+'][discount]" class="retail_sub_discount"/></td><input type="hidden" value ="each" name="customer_detail['+current_row+'][discount_type]" class="discount_type"/><td class="td_amt">' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][amt]" class="sub_amt"/><td class="td_cgst">' + cgst + '  %' + '</td> <input type="hidden" value = "'+ cgst + '" name="customer_detail['+current_row+'][cgst]" class="sub_cgst"/> <td class="td_cgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][cgst_value]" class="sub_cgst_value"/><td class="td_sgst">' + sgst + '  %' + '</td> <input type="hidden" value = "'+ sgst + '" name="customer_detail['+current_row+'][sgst]" class="sub_sgst"/><td class="td_sgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][sgst_value]" class="sub_sgst_value"/><td class="td_subtotal"></td> <input type="hidden" value ="" name="customer_detail['+current_row+'][subtotal]" class="sub_total"/><td><span class="retail_sub_delete">Delete</span></td></tr>';                
                jQuery('#bill_lot_add_retail').append(str);
            }
            rowCalculate();
            jQuery('.retail_unit').val('0');
            jQuery('.retail_discount').val('0.00');
            jQuery('.retail_lot_id').text('');
            jQuery('.retail_lot_id').val('');
          
         } 
       
        else {
            if(discount == '0.00')  {
                alert_popup('Enter Discounted Price !!!');
            }
            else if(discount == ''){
                alert_popup('Enter Discounted Price !!!');
                jQuery('.lot_id').text('');
                jQuery('.lot_id').val('');
            }
             else if ( unit == '0' && unit == '') {
                alert_popup('Enter Unit !!!');
            }
            else 
            {
                alert_popup('Select Products!!!');
            }
        }

       
    
    });

    jQuery('.retail_sub_delete').live('click',function(){
       jQuery(this).parent().parent().remove();
        rowCalculate();
       
        
    });


    //<--- discount ---->
       jQuery('.discount_per').live('change', function()   {
       var value = jQuery("[name=discount_per]:checked").val(); 
       if(value == 'cash'){
        jQuery('.dis_fa_per').css('display', 'none');
        jQuery('.dis_fa_inr').css('display', 'block');
       }else {
        jQuery('.dis_fa_inr').css('display', 'none');
        jQuery('.dis_fa_per').css('display', 'block');
       }

       rowCalculate();
    });

    /*Submit Payment*/
    jQuery('#billing_container #submit_payment').on('click', function() {

        var existing_count = parseInt( jQuery('#bill_lot_add_retail tr').length );
        if(existing_count != 0 ) {

            var bill_update_url = bill_update.updateurl;
            jQuery('#lightbox').css('display','block');
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action : 'create_order',
                    data : jQuery('#billing_container :input').serialize()
                },
                success: function (data) {
                    clearPopup();
                    popItUp('Success', 'Bill Created!');
                    jQuery('#lightbox').css('display','none');
                    console.log("efklf");
                    console.log(data);
                    window.location = bill_update_url+'&id='+data.inv_id;

                }
            });
        }
        else {
            alert('Please Add Atleast One Product!!! Empty Bill Can'+" ' "+'t Submit');
        }
    });


     /*Update Payment*/
    jQuery('#billing_container #update_payment').on('click', function(){

        var existing_count = parseInt( jQuery('#bill_lot_add_retail tr').length );
        if(existing_count != 0 ) {

            var bill_invoice_url = bill_invoice.invoiceurl;
            jQuery('#lightbox').css('display','block');
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action : 'update_order',
                    data : jQuery('#billing_container :input').serialize()
                },
                success: function (data) {
                    clearPopup();
                    popItUp('Success', 'Successfully Updated!');
                    jQuery('#lightbox').css('display','none');

                    window.location = bill_invoice_url+'&id='+data.inv_id + '&year='+ data.year;

                }
            });
        }
        else {
            alert('Please Add Atleast One Product!!! Empty Bill Can'+" ' "+'t Submit');
        }
        
    });

      /*Update Return Payment*/
    jQuery('#return_billing_container #return_update_payment').on('click', function() {
      

        var bill_invoice_url = bill_return_list.return_items;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'return_order',
                data : jQuery('#return_billing_container :input').serialize()
            },
            success: function (data) {
                clearPopup();
                popItUp('Success', 'Successfully Updated!');
                jQuery('#lightbox').css('display','none');

                window.location = bill_invoice_url+'&id='+data.invoice_id;

            }
        });
        
    });




    jQuery('.print_bill').on('click',function() {
        var inv_id = jQuery('.invoice_id').val();
        var year = jQuery('.year').val();
        var datapass =   home_page.url+'invoice/?id='+inv_id+'&year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });

    jQuery('.generate_bill').on('click',function(){
        var year = jQuery('.year').val();
        var inv_id = jQuery('.invoice_id').val();
        var datapass =   home_page.url+'invoice-download/?id='+inv_id+'&year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });



    jQuery('.new_user_bill').on('click', function() {
        jQuery('.new_user_bill,.billing_customer_div').css('display', 'none');
        jQuery('.old_user_bill,.new_customer').css('display', 'block');
        jQuery('.user_type').val('new'); 
        jQuery('.popup-add-customer').trigger('click');

    });

    jQuery('.old_user_bill').on('click', function() {
        jQuery('.new_user_bill,.billing_customer_div').css('display', 'block');
        jQuery('.old_user_bill,.new_customer').css('display', 'none');
       jQuery('.user_type').val('old');
    });


    
    jQuery('.mobile_check').live('change',function() {

       jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action       : 'check_unique_mobile_bill',
                mobile       : jQuery('.mobile_check').val()
            },
            success: function (data) {
               if(data != 0){
                alert('Mobile Already Exists!!!');
                jQuery('.mobile_check').val('').focus();

            }
            
            }
        });
    

    });



//<--------- Return Submit--->
   jQuery('.add-button-return-retail').live('click',function() {

        var product_id          = jQuery('.rtn_lot_id').val();
        var product_name        = jQuery('.rtn_ws_product').val();
        var hsn_code            = jQuery('.rtn_ws_hsn').val();
        var discount            = jQuery('.rtn_discount_amt').val();
        var unit                = jQuery('.qty').val();
        var rtn_qty             = jQuery('.return_qty').val();
        var cgst                = jQuery('.rtn_cgst_percentage').val();
        var sgst                = jQuery('.rtn_sgst_percentage').val();

       if( rtn_qty != '0'   ) {
            var existing_count = parseInt( jQuery('#rtn_bill_lot_add tr').length );
            var current_row = existing_count + 1;
            if( jQuery('.rtn_customer_tab[data-productid='+ product_id +']').length != 0 ) {
                var selector = jQuery('.rtn_customer_tab[data-productid='+ product_id +']');
                selector.find('.td_rtn_qty').text(rtn_qty);
                selector.find('.td_rtn_unit').text(unit);
                selector.find('.sub_rtn_qty').val(rtn_qty);
                selector.find('.rtn_sub_unit').val(unit);

                
            } else {
                var str = '<tr data-randid='+makeid()+' data-productid='+product_id+' class="rtn_customer_tab" ><td class="td_rtn_id">'+current_row+'</td> <input type="hidden" value="'+ product_id + '" name="rtn_customer_table['+current_row+'][id]" class="rtn_sub_id" /><td class="td_rtn_product">' + product_name + '</td> <input type="hidden" value = "'+ product_name + '" name="rtn_customer_table['+current_row+'][product]" class="rtn_sub_product"/><td class="td_rtn_hsn">' + hsn_code + '</td> <input type="hidden" value = "'+ hsn_code + '" name="rtn_customer_table['+current_row+'][hsn]" class="rtn_sub_hsn"/><td class="td_rtn_unit">'+ unit + ' </td><input type="hidden" value = "'+ unit + '" name="rtn_customer_table['+current_row+'][unit]" class="rtn_sub_unit"/> <td class="td_rtn_qty">' + rtn_qty + '</td> <input type="hidden" value = "'+ rtn_qty + '" name="rtn_customer_table['+current_row+'][rtn_qty]" class="sub_rtn_qty"/><td class="td_rtn_discount">'+ discount +'</td><input type="hidden" value ="'+discount +'" name="rtn_customer_table['+current_row+'][mrp]" class="rtn_sub_discount"/><td class="td_rtn_amt"></td> <input type="hidden" value = "" name="rtn_customer_table['+current_row+'][amt]" class="rtn_sub_amt"/><td class="td_rtn_cgst">' + cgst + '  %' + '</td> <input type="hidden" value = "'+ cgst + '" name="rtn_customer_table['+current_row+'][cgst]" class="rtn_sub_cgst"/> <td class="td_rtn_cgst_value"></td> <input type="hidden" value = "" name="rtn_customer_table['+current_row+'][cgst_value]" class="rtn_sub_cgst_value"/><td class="td_rtn_sgst">' + sgst + '  %' + '</td> <input type="hidden" value = "'+ sgst + '" name="rtn_customer_table['+current_row+'][sgst]" class="rtn_sub_sgst"/><td class="td_rtn_sgst_value"></td> <input type="hidden" value = "" name="rtn_customer_table['+current_row+'][sgst_value]" class="rtn_sub_sgst_value"/><td class="td_rtn_subtotal"></td> <input type="hidden" value ="" name="rtn_customer_table['+current_row+'][subtotal]" class="rtn_sub_total"/><td><span class="sub_delete">Delete</span></td></tr>';                
                jQuery('#rtn_bill_lot_add').append(str);
            }

             wsReturn_rowCalculate();
           
        } else {

            alert_popup('Enter All Fields !!!');
        }

        jQuery('.qty').val('0');
        jQuery('.return_qty').val('0');
    
    });

  //<------- Goods Return Items ---->
    jQuery('#ws_billing_return #return_submit').on('click', function() {

        var bill_update_url = bill_return_view.returnview;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'create_return',
                data   : jQuery('#ws_billing_return :input').serialize()
            },
            success: function (data) {
                clearPopup();
                popItUp('Success', 'Bill Created!');
                jQuery('#lightbox').css('display','none');

              window.location = bill_update_url+'&id='+data.id;

            }
        });
    });

});



function populateSelect2(selector, v) {

     jQuery( "#lot_id" ).autocomplete ({
      source: function( request, response ) {
        jQuery.ajax( {
          url: frontendajax.ajaxurl,
          type: 'POST',
          dataType: "json",
          data: {
            action: 'get_lot_data',
            search_key: request.term
          },
          success: function( data ) {
            response(jQuery.map( data.result, function( item ) {
                return {
                    id: item.id,
                    lot_no: item.lot_no,
                    brand_name : item.brand_name,
                    product_name : item.product_name,
                    hsn : item.hsn,
                    cgst : item.cgst,
                    sgst : item.sgst,
                    unit_price : item.selling_price,
                    value : item.product_name +' \('+item.brand_name+'\)',
                }
            }));
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {


        jQuery('.retail_lot_id_orig').val(ui.item.id);  
        jQuery('.retail_product').val(ui.item.product_name);  
        jQuery('.retail_unit_price').val(ui.item.unit_price);
        jQuery('.retail_discount').val(ui.item.unit_price);
        jQuery('.retail_hsn').val(ui.item.hsn);
        jQuery('.retail_cgst_percentage').val(ui.item.cgst);
        jQuery('.retail_sgst_percentage').val(ui.item.sgst);

       var selector = jQuery(this);
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                id      : ui.item.id,
                action  :'ws_slap'
            },
              success: function (data) {
                           
                var str = '<td class="td_id">'+'1'+'</td><td class="stock_prod">' + ui.item.product_name + '</td><td class="slap_stock">'+ data +'</td><input type="hidden" name="ws_slab_sys_txt" value="'+ data +'"  class="ws_slab_sys_txt"/>';                
                jQuery('.stock_table_body').html(str);

            }
        }); 



        console.log( "id: " + ui.item.id);
      }
    });
}


function rowCalculate() {


    var discount = '0.00';
    var sub ='0.00';
    var count = '0.00';
    var unit = '0.00';
    var existing_count = '0';

    var discount =parseFloat(jQuery('.discount').val());
    existing_count = parseInt( jQuery('.discount_type[value="whole"]').length);
    if(jQuery('.discount_type[value="whole"]').length == '0'){
        existing_count = 1;
    };
    var sub_tot=parseFloat(0);
    jQuery('.customer_table_retail').each(function() { 
    var row_sub                 = parseFloat('0.00');
    var row_discount            = parseFloat(jQuery(this).find('.retail_sub_discount').val());
    var row_mrp                 = parseFloat(jQuery(this).find('.sub_price').val());
    unit                        = parseFloat(jQuery(this).find('.retail_sub_unit').val());
    var cgst                    = parseFloat(jQuery(this).find('.sub_cgst').val());
    var sgst                    = parseFloat(jQuery(this).find('.sub_sgst').val());
    if( row_discount == row_mrp  || jQuery(this).find('.discount_type').val() == 'whole' ) {
        var whole_unit_total    = row_mrp * unit;
        if(jQuery('.discount_per:checked').val() == 'percentage') {    

            
            var whole_dis           = (whole_unit_total  * discount)/100;
            var unit_total          = whole_unit_total - whole_dis;
            var unit_price      = (unit_total / unit);
           
        } else {
            count               = parseFloat(discount / existing_count);    
            var unit_total      = whole_unit_total -  count;
            var unit_price      = (unit_total / unit);
        }

        jQuery(this).find('.discount_type').val('whole');
       
    }
    else {

        var unit_price          = parseFloat(row_discount);
        var unit_count          = parseFloat(jQuery(this).find('.retail_sub_unit').val());
        var unit_total          = (unit_price * unit_count);
    }
   
        var diviser         = 100 + cgst + sgst ;
        var amt             = (unit_total *  100)/(diviser);
        var full_gst        = unit_total - amt;
        var row_per_cgst    = full_gst/2;
        var row_per_sgst    = full_gst/2;

        jQuery(this).find('.retail_sub_discount').val(unit_price.toFixed(2));

        unit_total               = (isNaN(unit_total) ? '0.00' : unit_total);

        jQuery(this).find('.td_subtotal').text(unit_total.toFixed(2)); 
        jQuery(this).find('.sub_total').val(unit_total.toFixed(2));

        jQuery(this).find('.td_amt').text(amt.toFixed(2)); 
        jQuery(this).find('.sub_amt').val(amt.toFixed(2));

        jQuery(this).find('.sub_cgst_value').val(row_per_cgst.toFixed(2));
        jQuery(this).find('.sub_sgst_value').val(row_per_sgst.toFixed(2));

        jQuery(this).find('.td_cgst_value').text(row_per_cgst.toFixed(2));
        jQuery(this).find('.td_sgst_value').text(row_per_sgst.toFixed(2));
        sub_tot = sub_tot + parseFloat(jQuery(this).find('.sub_total').val());

    });
    
    jQuery('.fsub_total').val(sub_tot.toFixed(2));
    jQuery('.paid_amount').trigger('change');

}
function populateReturnProductsRetail(selector) {

   jQuery(selector).select2({

        allowClear: true,
        width: '15%',

        multiple: true,
        minimumInputLength: 1,
        maximumSelectionLength: 1,
        ajax: {
        type: 'POST',
        url: frontendajax.ajaxurl,
        delay: 250,
        dataType: 'json',
            data: function(params) {
                return {
                    action: 'get_return_lot_data_retail', // search term
                    page: 1,
                    search_key: params.term,
                    inv_id  : jQuery('.return_invoice_id_retail').val()
                   
                };
            },

            processResults: function(data) {
                var results = [];

                return {
                    results: jQuery.map(data.items, function(obj) {
                        return { id: obj.lot_id, sale_unit:obj.sale_unit, brand_name: obj.brand_name, product_name:obj.product_name,hsn:obj.hsn, bal_qty:obj.bal_qty, unit_price:obj.selling_price, cgst:obj.cgst,sgst:obj.sgst, discount:obj.discount };
                    })
                };
            },
            cache: true
        },
        initSelection: function (element, callback) {
          callback({ id: jQuery(element).val(), product_name: jQuery(element).find(':selected').text() });
        },
        templateResult: formatStateBillCreate,
        templateSelection: formatStateBillCreate1
    }).on("select2:select", function (e) {  

        jQuery("select2:select").next('.return_qty').focus(); 

        if( (e.params.data.bal_qty) == 0 ) {
            alert("This Product is Already Fully Return.Please Select Correct Product");
        } else {

       
        jQuery('.rtn_lot_id').val(e.params.data.id);  
        jQuery('.rtn_ws_product').val(e.params.data.product_name);  
        jQuery('.rtn_ws_unit_price').val(e.params.data.unit_price);
        jQuery('.rtn_discount').val(e.params.data.unit_price);
        jQuery('.rtn_ws_hsn').val(e.params.data.hsn);
        jQuery('.rtn_cgst_percentage').val(e.params.data.cgst);
        jQuery('.rtn_sgst_percentage').val(e.params.data.sgst);
        jQuery('.rtn_discount_amt').val(e.params.data.discount);
        jQuery('.qty').val(e.params.data.bal_qty); 
        jQuery('.qty_hidden').val(e.params.data.bal_qty); 

         }
    });

   
}

 
function formatStateBillCreate (state) {
    if (!state.id) {
        return state.id;
    }
    var $state = jQuery(
    '<span><b>Brand Name  :</b>' +
    state.brand_name +
    '</span><br/>' +
    '<span><b>Product Name : </b>' +
    state.product_name +
    '</span>'
   
    );
    return $state;
};

function formatStateBillCreate1 (state) {
    if (!state.id) {
        return state.id;
    }
    var $state = jQuery(
    '<span>' +
    state.product_name +
    '</span><br/>'
   
    );
    return $state;
};
