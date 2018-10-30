jQuery(document).ready(function (argument) {
//ws delivery need or not 
jQuery('.ret_delivery_need').live('click',function(){
    var delivery  = jQuery('.ret_delivery_need:checked').val();
    if(delivery == "yes"){
        jQuery('.ret_delivery_display').css('display','block');
    }
    else {
        jQuery('.ret_delivery_display').css('display','none');
    }

});


//<----- Payment type JS------>
jQuery('.payment_cash').live('click',function(){ 
    var today = moment().format(' YYYY-MM-DD'); 
    var reference_id = jQuery('.invoice_id').val(); 
    jQuery('.payment_tab').css('display','block');
    if(jQuery(this).is(':checked')) {
        var type            = jQuery(this).attr('data-paytype'); 
        
        if(type == 'credit'){  
            var readonly        = 'readonly';
            var existing_count  = parseInt( jQuery('#bill_payment_tab_cheque tr').length );
            var current_row     = existing_count + 1;
            if(current_row == 1){
                var str1            = '<tr class="payment_cheque"><td style="padding:5px;">' + Capital(type) + '<input type="hidden" name="pay_cheque" value="'+type+'" style="width:20px;" class="pay_cheque"/></td><td style="padding:5px;"><input type="text" name="pay_amount_cheque" class="pay_amount_cheque" '+ readonly +' value="'+ jQuery('.fsub_total').val() +'" style="width: 74px;" onkeypress="return isNumberKey(event)"/><input type="hidden" name="payment_detail['+current_row+'][reference_screen]" value="billing_screen" /><input type="hidden" name="payment_detail['+current_row+'][reference_id]" value="'+ reference_id +'" /></td><td style="width: 190px;">'+today+'</td><td style="padding:5px;width:75px;"><a  href="#" class="payment_sub_delete" style="">x</a></td></tr>';
                jQuery('#bill_payment_tab_cheque').append(str1);  
            }
           
        } else {
            if(type == 'internet'){
                var type_text   = 'Netbanking';
            } else {
                var type_text = Capital(type);
            }
            var existing_count  = parseInt( jQuery('#bill_payment_tab tr').length );
            var current_row     = existing_count + 1;
            var str             = '<tr class="payment_table"><td style="padding:5px;">' + type_text + '<input type="hidden" name="payment_detail['+current_row+'][payment_type]" value="'+type+'" style="width:20px;" class="payment_type"/></td><td style="padding:5px;"><input type="text" name="payment_detail['+current_row+'][payment_amount]" class="payment_amount" data-paymenttype="'+type+'"  data-uniqueName="'+makeid()+'" value="" style="width: 74px;" onkeypress="return isNumberKey(event)"/><input type="hidden" name="payment_detail['+current_row+'][reference_screen]" value="billing_screen" /><input type="hidden" name="payment_detail['+current_row+'][reference_id]" value="'+ reference_id +'" /></td><td style="padding"5px;>'+today+'</td><td style="padding:5px;"><a  href="#" class="payment_sub_delete" style="">x</a></td></tr>';                
            jQuery('#bill_payment_tab').append(str);
        }
        payment_calculation();               
    }
 });


jQuery('.payment_sub_delete').live('click',function(e){
    var sub_tot     = 0;
    if (confirm('Are you sure want to delete?')) {
        jQuery(this).parent().parent().remove();
    }
    e.preventDefault();
    var existing_count  = parseInt( jQuery('#bill_payment_tab tr').length );
    if(existing_count >= 1){
        jQuery('.payment_amount').focus();
    } else{
        jQuery('.payment_cash').focus();
    }
    payment_calculation();
    jQuery('.paid_amount').trigger('change');
    // jQuery('.payment_amount').trigger('change');
    var uniquename = jQuery(this).parent().parent().find('.payment_amount').data('uniquename');
    deleteDueCash(uniquename);
});
jQuery('.payment_cash').live('keydown', function(e){
    var keyCode = e.keyCode || e.which; 
    if (keyCode == 40) { 
        e.preventDefault();
        jQuery('.payment_amount').focus();
    }
});

jQuery('.payment_amount').live('keydown', function(e){
    var keyCode = e.keyCode || e.which; 
    if (keyCode == 38) { 
        e.preventDefault();
        jQuery('.payment_cash').focus();
    }
});


jQuery('.payment_amount').live('change',function(){
    var current_balance = payment_calculation();
    var amount          = parseFloat(jQuery(this).parent().parent().find('.payment_amount').val());
    var payment_type    = jQuery(this).parent().parent().find('.payment_type').val();
    var sub_tot = 0;
    if( payment_type == 'card' || payment_type == 'internet' ||  payment_type == 'cheque' ){
        if(current_balance >= 0) {
            
        } else {
            alert("Please Enter Amount as less than Total amount!!!");
            parseFloat(jQuery(this).parent().parent().find('.payment_amount').val(0));
            
        }
    }  
    payment_calculation();
    jQuery('.paid_amount').trigger('change');   
    //individualBillPaidCalculation();
    
});

jQuery('.cod_check').on('click',function(){
    if(jQuery('.cod_check:checked').val()=='cod'){
        jQuery('.cod_amount_div').css("display","block");
        payment_calculation();
    } else {
        jQuery('.cod_amount_div').css("display","none");
        jQuery('.cod_amount').val('0');
        
    }
});

jQuery('#billing_customer').on('keydown',function(e){
     var keyCode = e.keyCode || e.which; 
    if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.bill_submit').focus();
    }
    else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#billing_mobile').focus();
    } 
    else {
        jQuery('#billing_customer').focus();
    }
});
jQuery('.bill_submit').on('keydown',function(e){
    var keyCode = e.keyCode || e.which; 
    if(event.shiftKey && event.keyCode == 9) {  
        e.preventDefault(); 
        jQuery('.ret_delivery_need').focus();
    }
    else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#billing_customer').focus();
    } 
    else {
        jQuery('.bill_submit').focus();
    }
})


//delivery check products

    jQuery(".check_all").click(function(){
        var check_all = jQuery(".check_all").is(":checked");
        if(check_all == '1'){
        
            jQuery(".delivery_check").trigger("click"); 
            jQuery(".delivery_check").attr('checked', true);    // if i remove this line then selectall for checkbox don't works   
        } else {
            jQuery(".delivery_check").trigger("click");
            jQuery(".delivery_check").attr('checked', false);

        }          
    });  

    jQuery('.delivery_check').live('click',function(){
        var unit_count = parseFloat(jQuery(this).parent().parent().find('.unit_count').val());        
        var delivery_check = jQuery(this).parent().parent().find('.delivery_check').is(":checked");
        if(delivery_check){
            var delivery        = 1;  
            jQuery(this).parent().parent().find('.delivery_count').css("display","inline-block");
            jQuery(this).parent().parent().find('.delivery_count').val(unit_count);
            var delivery_count  = parseFloat(jQuery(this).parent().parent().find('.delivery_count').val());
        } else {
            var delivery        = 0;
            jQuery(this).parent().parent().find('.delivery_count').css("display","none"); 
            jQuery(this).parent().parent().find('.delivery_count').val(0);
            var delivery_count  = 0;
        }
        var delivery_id = jQuery(this).parent().parent().find('.delivery_id').val();        
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action          :'product_delivery',
                id              : delivery_id,
                delivery        : delivery,
                delivery_count  : delivery_count,
            },
            success: function (data) {
                clearPopup();                     
            }
        });
    });

     jQuery('.delivery_count').live('change',function(){

        var delivery_count_check = parseFloat(jQuery(this).parent().parent().find('.delivery_count').val());
        var unit_count = parseFloat(jQuery(this).parent().parent().find('.unit_count').val());
        if( delivery_count_check > unit_count) { 
            alert('please enter correct product count !!!');
            jQuery(this).parent().parent().find('.delivery_count').val(unit_count);

        } 
        if(delivery_count_check == 0){
            jQuery(this).parent().parent().find('.delivery_check').attr('checked',false);
        }
            var delivery_check = jQuery(this).parent().parent().find('.delivery_check').is(":checked");
      
        if(delivery_check){

            var delivery        = 1;  
            jQuery(this).parent().parent().find('.delivery_count').css("display","inline-block");
            var delivery_count  = parseFloat(jQuery(this).parent().parent().find('.delivery_count').val());
             
        } else {
            var delivery        = 0;
            jQuery(this).parent().parent().find('.delivery_count').css("display","none");
            jQuery(this).parent().parent().find('.delivery_count').val(0);

            var delivery_count  = 0;
        }
        var delivery_id = jQuery(this).parent().parent().find('.delivery_id').val();
     
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action          :'product_delivery',
                id              : delivery_id,
                delivery        : delivery,
                delivery_count  : delivery_count,
            },
            success: function (data) {
                clearPopup();     

            }
        });

     });



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
        alert('Available stock is '+ stock + ' !!! Enter Quantity as small as avalible stock!!!');
        jQuery('.retail_unit').val(Math.ceil(stock));
    }
});


//<-------- display secondary and landline textboxes----->
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
        if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.delivery_address').focus();
        }
        else if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('#billing_customer').focus();
        }
        else {
            jQuery('#submit_payment').focus();
        } 

    });
    
     jQuery("#update_payment").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 

        if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.delivery_address').focus();
        }
        else if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('#billing_customer').focus();
        }
        else {
            jQuery('#update_payment').focus();
        } 

    });



 //<-------- search customer using name and mobile------>   
jQuery( "#billing_customer, #billing_mobile" ).autocomplete ({

    source: function( request, response ) {
        var billing_field = jQuery(this.element).attr('id');
        jQuery("#due_tab").empty();
        jQuery.ajax({
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
                    } 
                    else {
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
        });
    },
    minLength: 2,
    select: function( event, ui ) {
        customerBalance(ui.item.id);
        //duepaid_fun(ui.item.id);
        if(ui.item.identification == 'mobile' ) {
            jQuery('#billing_mobile').val(ui.item.value);
            jQuery('#billing_customer').val(ui.item.name);
            jQuery('.delivery_name').val(ui.item.name);
            jQuery('.delivery_phone').val(ui.item.value);
        } else {
            jQuery('#billing_mobile').val(ui.item.mobile);
            jQuery('#billing_customer').val(ui.item.value);
            jQuery('.delivery_name').val(ui.item.value);
            jQuery('.delivery_phone').val(ui.item.mobile);
        }

        jQuery('#billing_secondary_mobile').val(ui.item.secondary_mobile);
        jQuery('#billing_landline_mobile').val(ui.item.landline);
        jQuery('.old_customer_id').val(ui.item.id);
        jQuery('.customer_id').val(ui.item.id);
        jQuery('#billing_address').val(ui.item.address);
        jQuery('.delivery_address').val(ui.item.address);
        jQuery('.lot_id').focus();
        jQuery('.paid_amount').trigger('change');
        //individualBillPaidCalculation();

        jQuery('.Duepaid').trigger('change');


    },


   response: function(event, ui) {
        if (ui.content.length == 1)
        {
            jQuery(this).val(ui.content[0].value);
            jQuery(this).autocomplete( "close" );
            customerBalance(ui.content[0].id);
            //duepaid_fun(ui.content[0].id);
            if(ui.content[0].identification == 'mobile' ) {
                jQuery('#billing_mobile').val(ui.content[0].value);
                jQuery('#billing_customer').val(ui.content[0].name);
                jQuery('.delivery_name').val(ui.content[0].name);
                jQuery('.delivery_phone').val(ui.content[0].value);
            } else {
                jQuery('#billing_mobile').val(ui.content[0].mobile);
                jQuery('#billing_customer').val(ui.content[0].value);
                jQuery('.delivery_name').val(ui.content[0].value);
                jQuery('.delivery_phone').val(ui.content[0].mobile);
            }
            jQuery('#billing_secondary_mobile').val(ui.content[0].secondary_mobile);
            jQuery('#billing_landline_mobile').val(ui.content[0].landline);
            jQuery('.old_customer_id').val(ui.content[0].id);
            jQuery('.customer_id').val(ui.content[0].id);
            jQuery('#billing_address').val(ui.content[0].address);
            jQuery('.delivery_address').val(ui.content[0].address);
            jQuery('.lot_id').focus();
            jQuery('.paid_amount').trigger('change');
            jQuery('.Duepaid').trigger('change');
            //individualBillPaidCalculation();

        }
    }
});
//<------ Select Product ------>

  populateSelect2('.lot_id', 'old');


   jQuery('.retail_sub_unit').live('change keyup',function(){

        var unit = parseFloat(jQuery(this).parent().parent().find('.retail_sub_unit').val());
        var stock = parseFloat(jQuery(this).parent().parent().find('.retail_sub_stock').val());
        if( unit > stock){
            alert('Available stock is '+ stock + ' !!! Enter Quantity as small as avalible stock!!!');
            jQuery(this).parent().parent().find('.retail_sub_unit').val(Math.ceil(stock));
        }

        if(unit <= '0'){
            alert("please enter unit!!!");
            jQuery(this).parent().parent().find('.retail_sub_unit').focus();
            jQuery(this).parent().parent().find('.retail_sub_unit').val('1');
        }
        
            rowCalculate();
            //individualBillPaidCalculation();
        
    });

    jQuery('.retail_sub_discount').live('change',function(){
        jQuery(this).parent().parent().find('.discount_type').val('each');
        var unit                = parseFloat(jQuery(this).parent().parent().find('.retail_sub_discount').val());
        unit = isNaN(unit) ? 0 : unit;
        var wholesale_price     = parseFloat(jQuery(this).parent().parent().find('.sub_wholesale_price').val());
        if(unit <= '0'){
            alert("please enter price!!!");
            jQuery(this).parent().parent().find('.retail_sub_discount').focus();
            jQuery(this).parent().parent().find('.retail_sub_discount').val(wholesale_price);
        }
        if(wholesale_price > unit){
            alert("Discountant Price dose not less than wholesale rate!!!");
            jQuery(this).parent().parent().find('.retail_sub_discount').focus();
            jQuery(this).parent().parent().find('.retail_sub_discount').val(wholesale_price);
        }
        rowCalculate();
        
    });


  jQuery('.discount').live('change keyup',function() {
    rowCalculate();
  });



   jQuery('.paid_amount').on('change click',function(){
        var prev_bal = parseFloat(jQuery('.balance_amount_val').val());
        var current_bal = parseFloat(jQuery('.fsub_total').val());
         if ( jQuery('.paid_amount').val() == '')
         {
            var paid = parseFloat('0.00');   
         } else {
            var paid = parseFloat(jQuery('.paid_amount').val());
         }
        var bal = (current_bal) - paid;
//current_bal 
        currt_bal = paid - current_bal; 
        currt_bal = isNaN(currt_bal)? 0 :currt_bal;
         if(currt_bal < 0 ){
            jQuery('.current_bal').val(0);
            jQuery('.current_bal_txt').text(0);

           // jQuery('.cur_bal_check_box').css('display','none');
           // jQuery('.cur_bal_check_box').prop('checked',false);
        }
        else {
            jQuery('.current_bal').val(currt_bal);
            jQuery('.current_bal_txt').text(currt_bal);
            // jQuery('.cur_bal_check_box').css('display','block');
            // jQuery('.cur_bal_check_box').prop('checked',true);
        }

        if(jQuery('.cur_bal_check_box').attr('checked')){
            var final_data =  bal + currt_bal;            
        }  else {
            var final_data = (prev_bal + current_bal) - paid;           
        }
        bal = (bal > 0)? bal: 0 ;
        jQuery('.balance_pay').text(bal);
        final_data = isNaN(final_data)? 0 : final_data;
        jQuery('.return_amt').val(Math.round(final_data));
        jQuery('.return_amt_txt').text(Math.round(final_data));      
    });



    //<------- credit and payment type------->
 


//<--- Add table data-----> 

    jQuery('.retailer_add-button').live('click',function(e) {
       
        var product_id          = jQuery('.retail_lot_id_orig').val();
        var product_name        = jQuery('.retail_product').val();
        var brand_name          = jQuery('.retail_brand').val();
        var hsn_code            = jQuery('.retail_hsn').val();
        var stock               = jQuery('.ws_slab_sys_txt').val();
        var price               = jQuery('.retail_unit_price').val();
        var wholesale_price     = jQuery('.retail_wholesale_price').val();
        var mrp                 = jQuery('.retail_mrp').val();
        var unit                = jQuery('.retail_unit').val();
        var discount            = jQuery('.retail_discount').val();
        var cgst                = jQuery('.retail_cgst_percentage').val();
        var sgst                = jQuery('.retail_sgst_percentage').val();

       
    
       if( !!product_id && unit !='0' &&  unit != '' && unit > 0 && discount != '0.00' &&  discount != '' && discount != '0') {
            var existing_count = parseInt( jQuery('#bill_lot_add_retail tr').length );
            var current_row = existing_count + 1;
            if( jQuery('.customer_table_retail[data-productid='+ product_id +']').length != 0 ) {
                var selector = jQuery('.customer_table_retail[data-productid='+ product_id +']');
                var actual_unit = selector.find('.retail_sub_unit').val();
                var final_unit = parseFloat(unit) + parseFloat(actual_unit);
                selector.find('.retail_sub_unit').val(final_unit);

                addFromProductControlRetail();
                jQuery('.product_control_error_retail').remove();
                
            } else {
                var str = '<tr data-randid='+makeid()+' data-productid='+product_id+' class="customer_table_retail" ><td class="td_id">'+current_row+'</td> <input type="hidden" value="'+ product_id + '" name="customer_detail['+current_row+'][id]" class="sub_id" /><td class="td_brand">'+brand_name+'</td> <input type="hidden" value="'+ brand_name + '" name="customer_detail['+current_row+'][brand]" class="sub_brand" /><td class="td_product">' + product_name + '</td> <input type="hidden" value = "'+ product_name + '" name="customer_detail['+current_row+'][product]" class="sub_product"/><td class="td_hsn">' + hsn_code + '</td> <input type="hidden" value = "'+ hsn_code + '" name="customer_detail['+current_row+'][hsn]" class="sub_hsn"/><td class=""><input type="text" onkeypress="return isNumberKey(event)" value = "'+ unit + '" name="customer_detail['+current_row+'][unit]" class="retail_sub_unit" size="4" autocomplete="off"/> </td> <td>' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][stock]" class="retail_sub_stock"/><td class="td_price">' + mrp + '</td> <input type="hidden" value = "'+ mrp + '" name="customer_detail['+current_row+'][price]" class="sub_price"/> <td><input type="text"  onkeypress="return isNumberKeyWithDot(event)" value ="'+ discount +'" name="customer_detail['+current_row+'][discount]" class="retail_sub_discount" size="4" style="width: 70px;" autocomplete="off"/></td><td>'+ wholesale_price +'<input type="hidden" value="'+ wholesale_price +'"  name="customer_detail['+current_row+'][wholesale_price]" class="sub_wholesale_price"/></td><input type="hidden" value ="each" name="customer_detail['+current_row+'][discount_type]" class="discount_type"/><td class="td_amt">' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][amt]" class="sub_amt"/><td class="td_cgst">' + cgst + '  %' + '</td> <input type="hidden" value = "'+ cgst + '" name="customer_detail['+current_row+'][cgst]" class="sub_cgst"/> <td class="td_cgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][cgst_value]" class="sub_cgst_value"/><td class="td_sgst">' + sgst + '  %' + '</td> <input type="hidden" value = "'+ sgst + '" name="customer_detail['+current_row+'][sgst]" class="sub_sgst"/><td class="td_sgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][sgst_value]" class="sub_sgst_value"/><td class="td_total"></td> <input type="hidden" value ="" name="customer_detail['+current_row+'][subtotal]" class="sub_total"/><input type="hidden" value ="" name="customer_detail['+current_row+'][total]" class="total"/><td><a  href="#" class="retail_sub_delete">Delete</a></td></tr>';                
                jQuery('#bill_lot_add_retail').append(str);

                addFromProductControlRetail();
                jQuery('.product_control_error_retail').remove();
            }

            jQuery('.retail_sub_unit').trigger('change');
           rowCalculate();

          
         } 
       
        else {
            var product_name_selector = jQuery('#lot_id');
            var lot_id_selector = jQuery('.retail_lot_id_orig');
            var unit_selector = jQuery('#retail_unit');
            var discount_selector = jQuery('#retail_discount');

            var product_name = product_name_selector.val();
            var lot_id = lot_id_selector.val();
            var unit = unit_selector.val();
            var discount = discount_selector.val();
            jQuery('.product_control_error_retail').remove();

            if(product_name == '' || lot_id == '' || lot_id <= 0  ) { 
                product_name_selector.after('<div class="product_control_error_retail control_error">Please Enter Valid Product!</div>');
                product_name_selector.focus();
            }
            if(unit == '' || unit <= 0) {
                unit_selector.after('<div class="product_control_error_retail control_error">Unit Must be above 0</div>');
                unit_selector.focus();
            }
            // if(discount == '' || discount <= 0) {
            //     discount_selector.after('<div class="product_control_error_retail control_error">Please Enter Valid Discounted Price!</div>');
            //     discount_selector.focus();
            // }
        }
    //individualBillPaidCalculation();
       
    
    });


    //Prevent Form Submission 
    jQuery('.lot_id,.retailer_add-button').live('keydown',function(e){        
        if ( e.which == 13 ) // Enter key = keycode 13
        {   
            jQuery('.retailer_add-button').trigger('click');  //Use whatever selector necessary to focus the 'next' input
            return false;
        }

    });
    jQuery('.retail_unit').live('keydown',function(e){
        if ( e.which == 9 ) // Enter key = keycode 13
        {   
             jQuery('.product_control_error_retail').css('display','none'); //Use whatever selector necessary to focus the 'next' input
            
        }
        else  if ( e.which == 13 ) {
            jQuery('.retailer_add-button').trigger('click');  //Use whatever selector necessary to focus the 'next' input
            return false;  
        }
          else{ 
            console.log("reyrtyt");
          } 
        
    });

    jQuery('.retail_sub_delete').live('click',function(){
        if (confirm('Are you sure want to delete?')) {
            jQuery(this).parent().parent().remove();
        }
        rowCalculate();
        jQuery('.lot_id').focus();       
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







 //<------- Validation Function --------> 
   
 jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

//<---  validation for whole sale--->
    jQuery( ".billing_validation" ).validate({
        rules: {
            name: {
                nameValidite : true,
            },
            mobile: { 
                minlength: 10,
                maxlength: 10,
                uniqueUserMobile: true,
            },
            secondary_mobile: {
                minlength: 10,
                maxlength: 10,
            },
            landline: {
                minlength: 6,
                maxlength: 8,
            },
            address: {
                addressValidate : true,
            },
            delivery_name: {
                nameValidite : true,
            },
            delivery_phone: {
                minlength: 6,
            },
            delivery_address : {
                addressValidate : true,
            },
            payment_type : {
                required : true,
            }

        },
        messages: {
            name: {
                nameValidite: "Special Characters Not Allowed!",
            },
            mobile: {  
                minlength: "Mobile Number Must Be 10 Numbers!",
                maxlength: "Mobile Number Must Be 10 Numbers!",
                uniqueUserMobile : "Mobile Number Already Exist!",
            },
            secondary_mobile : {
                minlength: "Please Enter Valid  Number!",
                maxlength: "Please Enter Valid  Number!",
            },
            landline : {
                minlength: "Please Enter Valid Landline Number!",
                maxlength: "Please Enter Valid Landline Number!",
            },
            address: {
                addressValidate : "Address field contains only Alphabets,number , # , . , : , - and ,",
            },
            delivery_name : {
                nameValidite: "Special Characters Not Allowed!",
            },
            delivery_phone : {
                minlength: "Please Enter Valid  Number!",
            },
             delivery_address: {
                addressValidate : "Address field contains only Alphabets,number , # , . , : , - and ,",
            },
            payment_type : {
                required : 'please Select Atleast One check Box!!!',
            }

        }
    });



    /*Submit Payment*/
    jQuery('#billing_container #submit_payment').on('click', function() {
        var valid = jQuery(".billing_validation").valid();
        var prevent = jQuery(".form_submit_prevent_r_bill").val();
        if( valid) {
          
            var existing_count = parseInt( jQuery('#bill_lot_add_retail tr').length );
            if(existing_count != 0 && prevent == "off" ) {
                jQuery(".form_submit_prevent_r_bill").val('on');

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
                        // console.log("efklf");
                        // console.log(data);
                        window.location = bill_update_url+'&id='+data.id+'&inv_id='+data.inv_id+'&year='+data.year;

                    }
                });
            }
            else {
                alert('Please Add Atleast One Product!!! Empty Bill Can'+"'"+'t Submit');
            }
        }
        return false;
    });


     /*Update Payment*/
    jQuery('#billing_container #update_payment').on('click', function(){

        var valid = jQuery(".billing_validation").valid();
        var prevent = jQuery(".form_submit_prevent_r_bill").val();
        if( valid ) {
            var existing_count = parseInt( jQuery('#bill_lot_add_retail tr').length );
            if(existing_count != 0  && prevent == "off" ) {
                jQuery(".form_submit_prevent_r_bill").val('on');
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

                        window.location = bill_invoice_url+'&id='+data.inv_id + '&year='+ data.year + '&action=update';

                    }
                });
            }
            else {
                alert('Please Add Atleast One Product!!! Empty Bill Can'+"'"+'t Submit');
            }
        }
        return false;
        
    });





 //<------- Return invoice start---------->


	
	//<-------Delete Bill------->

  jQuery('.delete-bill').live( "click", function() {
    if(confirm('Are you sure you want to cancel this Invoice?')){
      var data=jQuery(this).attr("data-id");
      //console.log(data);
      window.location.replace('admin.php?page=billing_list&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Bill------->
  	//<-------Delete Return Bill------->

  jQuery('.delete-return-bill').live( "click", function() {
    if(confirm('Are you sure you want to Delete this Goods Return Challan?')){
      var data=jQuery(this).attr("data-id");
      //console.log(data);
      window.location.replace('admin.php?page=return_items_list&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Return Bill------->

	
	
	
	
    //<-----After keydown submit using tab goto first text box in Return billing View in Retail Return--->
  /*   jQuery(".return_print").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 

        if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.invoice_id').focus();
        } 

    }); */



      //<-----After keydown submit using tab goto first text box in Return billing View in Wholesale Return--->
    /* jQuery(".ws_return_print").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 

        if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.invoice_id').focus();
        } 

    }); */
//<-------return quantity check ------>
    jQuery('.return_qty_ret').live('change',function(){
       

        var bal_qty =parseFloat(jQuery(this).parent().parent().find('.return_bal_check').val());
        var delivery_count =parseFloat(jQuery(this).parent().parent().find('.ret_delivery_qty').val());

        var return_qty = parseFloat(jQuery(this).parent().parent().find('.return_qty_ret').val());
        var sale_qty = parseFloat(jQuery(this).parent().parent().find('.sale_qty').val());
        if(sale_qty < return_qty ){
             alert('Enter Return quantity as below as avalible quantity!!!');
             jQuery(this).parent().parent().find('.return_qty_ret').val('0');
         } else{
                if(delivery_count < return_qty){
                    alert('Products Not Delivered!!!');
                    jQuery(this).parent().parent().find('.return_qty_ret').val('0');
                }
                else { 
                    if(  bal_qty < return_qty ){
                        alert('Enter Return quantity as below as avalible quantity!!!');
                        jQuery(this).parent().parent().find('.return_qty_ret').val('0');
                        var balance = bal_qty - 1;
                    } else {
                        if(isNaN(return_qty)){
                             alert('Enter Return quantity!!!');
                             jQuery(this).parent().parent().find('.return_qty_ret').val('0');
                             var balance = bal_qty - 1;
                        } else {
                            var balance = bal_qty - return_qty;
                        }
                        
                    }
                    //console.log(balance);
                    jQuery(this).parent().parent().find('.return_bal_qty_td').text(balance);
                    jQuery(this).parent().parent().find('.return_bal').val(balance);
                    Return_rowCalculate();
                }
        }
        
    });

      /*Update Return Payment*/
    // jQuery('#return_billing_container #return_update_payment').on('click', function() {
      

    //     var bill_invoice_url = bill_return.return_page;
    //     jQuery('#lightbox').css('display','block');
    //     jQuery.ajax({
    //         type: "POST",
    //         dataType : "json",
    //         url: frontendajax.ajaxurl,
    //         data: {
    //             action : 'return_order',
    //             data : jQuery('#return_billing_container :input').serialize()
    //         },
    //         success: function (data) {
    //             clearPopup();
    //             popItUp('Success', 'Successfully Updated!');
    //             jQuery('#lightbox').css('display','none');

    //             window.location = bill_invoice_url+'&id='+data.invoice_id;

    //         }
    //     });
        
    // });



    jQuery('.print_bill').live('click',function() {
        var inv_id = jQuery(this).parent().parent().find('.invoice_id').val();
        var year = jQuery(this).parent().parent().find('.year').val();
        var datapass =   home_page.url+'invoice/?id='+inv_id+'&cur_year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });



    jQuery('.delivery_print').live('click',function() {
        var inv_id = jQuery(this).parent().parent().find('.invoice_id').val();
        var year = jQuery(this).parent().parent().find('.year').val();
        var datapass =   home_page.url+'delivery-print/?id='+inv_id+'&cur_year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });



    jQuery('.print_invoice_list').live('click',function() {
        var  inv_id = jQuery('.inv_id').val();
        var  cus_name = jQuery('.name').val();
        var  mobile = jQuery('.mobile').val();
        var bill_from = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'sale-report-print/?bill_from='+bill_from+'&bill_to='+bill_to + '&inv_id='+inv_id+'&cus_name='+cus_name+'&mobile='+mobile;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","");
        thePopup.print();  
    });

        jQuery('.download_invoice_list').live('click',function() {
        var  inv_id = jQuery('.inv_id').val();
        var  cus_name = jQuery('.name').val();
        var  mobile = jQuery('.mobile').val();
        var bill_from = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'sale-report-download/?bill_from='+bill_from+'&bill_to='+bill_to + '&inv_id='+inv_id+'&cus_name='+cus_name+'&mobile='+mobile;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","");
    });




    jQuery('.generate_bill').live('click',function(){
        var year = jQuery('.year').val();
        var inv_id = jQuery('.invoice_id').val();
        var datapass =   home_page.url+'invoice-download/?id='+inv_id+'&cur_year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });

//download page
    jQuery('.return_generate_bill').live('click',function(){
        var id = jQuery('.invoice_id').val();
        var datapass =   home_page.url+'goods-return-download/?id='+id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });


  //<------- Goods Return Items ---->
    jQuery('#billing_return #return_submit').on('click', function() {
        var return_val = jQuery('.rtn_fsub_total').val();
        if(return_val > 0){
        var bill_update_url = bill_return.return_page;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'create_return',
                data   : jQuery('#billing_return :input').serialize(),
                year   : jQuery( ".year" ).val(),
				search_inv_id : jQuery(".return_inv_id").val(),
            },
            success: function (data) {
                clearPopup();
                popItUp('Success', 'Bill Created!');
                jQuery('#lightbox').css('display','none');

              window.location = bill_update_url+'&return_id='+data.id+'&id='+data.search_inv_id+'&year='+data.year;

            }
        });
         } else {
            alert("Empty Bill Can't Submit!!!");
        }
    });

    //<------- Goods Return Items Update ---->
    jQuery('#billing_return #return_update').on('click', function() {
        var return_val = jQuery('.rtn_fsub_total').val();
        if(return_val > 0){
        var bill_update_url = bill_return_view.returnview;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'update_return',
                search_inv_id : jQuery(".return_inv_id").val(),
                data   : jQuery('#billing_return :input').serialize(),
                year   : jQuery( ".year" ).val(),

            },
            success: function (data) {
                clearPopup();
                popItUp('Success', 'Bill Created!');
                jQuery('#lightbox').css('display','none');

              window.location = bill_update_url+'&id='+data.id;

            }
        });

         } else {
            alert("Empty Bill Can't Submit!!!");
        }
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
                        mrp : item.mrp,
                        hsn : item.hsn,
                        cgst : item.cgst,
                        sgst : item.sgst,
                        unit_price : item.selling_price,
                        wholesale_price : item.wholesale_price,
                        value : item.product_name +' \('+item.brand_name+'\)',
                    }
                }));
              }
            });
        },
        minLength: 2,
        select: function( event, ui ) {


            jQuery('.retail_lot_id_orig').val(ui.item.id);  
            jQuery('.retail_product').val(ui.item.product_name);  
            jQuery('.retail_brand').val(ui.item.brand_name);  
            jQuery('.retail_unit_price').val(ui.item.unit_price);
            jQuery('.retail_wholesale_price').val(ui.item.wholesale_price);
            jQuery('.retail_mrp').val(ui.item.mrp);
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
                               
                    var str = '<td class="td_id">'+'1'+'</td><td class="stock_prod">' + ui.item.product_name + '</td><td class="stock_prod">' + ui.item.mrp + '</td><td class="stock_prod">' + ui.item.unit_price + '</td><td class="slap_stock">'+ data +'</td><input type="hidden" name="ws_slab_sys_txt" value="'+ data +'"  class="ws_slab_sys_txt"/>';                
                    jQuery('.stock_table_body').html(str);

                }
            }); 

            console.log( "id: " + ui.item.id);
        },
        response: function(event, ui) {
            if (ui.content.length == 1)
            {
                jQuery(this).val(ui.content[0].value);
                jQuery(this).autocomplete( "close" );

                jQuery('.retail_lot_id_orig').val(ui.content[0].id);  
                jQuery('.retail_product').val(ui.content[0].product_name);  
                jQuery('.retail_brand').val(ui.content[0].brand_name);  
                jQuery('.retail_unit_price').val(ui.content[0].unit_price);
                jQuery('.retail_wholesale_price').val(ui.content[0].wholesale_price);
                jQuery('.retail_mrp').val(ui.content[0].mrp);
                jQuery('.retail_discount').val(ui.content[0].unit_price);
                jQuery('.retail_hsn').val(ui.content[0].hsn);
                jQuery('.retail_cgst_percentage').val(ui.content[0].cgst);
                jQuery('.retail_sgst_percentage').val(ui.content[0].sgst);

               var selector = jQuery(this);
                jQuery.ajax({
                    type: "POST",
                    dataType : "json",
                    url: frontendajax.ajaxurl,
                    data: {
                        id      : ui.content[0].id,
                        action  :'ws_slap'
                    },
                      success: function (data) {
                                   
                        var str = '<td class="td_id">'+'1'+'</td><td class="stock_prod">' + ui.content[0].product_name + '</td><td class="stock_prod">' + ui.content[0].mrp + '</td><td class="stock_prod">' + ui.content[0].unit_price + '</td><td class="slap_stock">'+ data +'</td><input type="hidden" name="ws_slab_sys_txt" value="'+ data +'"  class="ws_slab_sys_txt"/>';                
                        jQuery('.stock_table_body').html(str);

                    }
                }); 
            }
        }
    });
jQuery('.payment_amount').trigger('change');
}


function rowCalculate() {
    var discount = '0.00';
    var sub ='0.00';
    var count = '0.00';
    var unit = '0.00';
    var existing_count = '0';
    var before_total = parseFloat('0.00');
    var discount =parseFloat(jQuery('.discount').val());
    discount = isNaN(discount) ? '0.00' : discount;
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

        
        jQuery(this).find('.sub_total').val(unit_total.toFixed(2));

        jQuery(this).find('.td_amt').text(amt.toFixed(2)); 
        jQuery(this).find('.sub_amt').val(amt.toFixed(2));

        jQuery(this).find('.sub_cgst_value').val(row_per_cgst.toFixed(2));
        jQuery(this).find('.sub_sgst_value').val(row_per_sgst.toFixed(2));

        jQuery(this).find('.td_cgst_value').text(row_per_cgst.toFixed(2));
        jQuery(this).find('.td_sgst_value').text(row_per_sgst.toFixed(2));
        sub_tot = sub_tot + parseFloat(jQuery(this).find('.sub_total').val());

        var total = row_mrp * unit;
        jQuery(this).find('.total').val(total.toFixed(2));
        jQuery(this).find('.td_total').text(total.toFixed(2));

        before_total = total + before_total;

    });
    jQuery('.f_total').val(before_total.toFixed(2));
    
    jQuery('.fsub_total').val(Math.round(sub_tot));
    //jQuery('.paid_amount').val(Math.round(sub_tot));
    jQuery('.paid_amount').trigger('change');
    payment_calculation();
    //individualBillPaidCalculation();
    //addTotalToDue();

}


function Return_rowCalculate() {
    var sub_tot=parseFloat(0);
    jQuery('.rtn_bill_lot_add').each(function() { 

        var unit_price          = parseFloat(jQuery(this).find('.return_mrp').val());
        var unit_count          = parseFloat(jQuery(this).find('.return_qty_ret').val());
        var cgst                = parseFloat(jQuery(this).find('.return_cgst').val());
        var sgst                = parseFloat(jQuery(this).find('.return_sgst').val());
        var unit_total          = (unit_price * unit_count);
        var row_per_cgst        = ( (cgst * unit_total) / 100 );
        var row_per_sgst        = ( (sgst * unit_total) / 100 );
        var diviser         = 100 + cgst + sgst ;
        var amt             = (unit_total *  100)/(diviser);
        var full_gst        = unit_total - amt;
        var row_per_cgst    = full_gst/2;
        var row_per_sgst    = full_gst/2;
        unit_total               = (isNaN(unit_total) ? '0.00' : unit_total);
        jQuery(this).find('.return_sub_total_td').text(unit_total.toFixed(2)); 
        jQuery(this).find('.return_sub_total').val(unit_total.toFixed(2));

        jQuery(this).find('.return_amt_td').text(amt.toFixed(2)); 
        jQuery(this).find('.return_amt').val(amt.toFixed(2));

        jQuery(this).find('.return_cgst_value').val(row_per_cgst.toFixed(2));
        jQuery(this).find('.return_sgst_value').val(row_per_sgst.toFixed(2));

        jQuery(this).find('.return_cgst_value_td').text(row_per_cgst.toFixed(2));
        jQuery(this).find('.return_sgst_value_td').text(row_per_sgst.toFixed(2));
        sub_tot = sub_tot + parseFloat(jQuery(this).find('.return_sub_total').val());
        //console.log(sub_tot); 
    });
        jQuery('#rtn_fsub_total').val(sub_tot.toFixed(2));
        jQuery('#new_rtn_fsub_total').val(sub_tot.toFixed());
        ReturnPaymentTypeCalculation();
}
function addFromProductControlRetail() {
    jQuery('.lot_id').val('');
    jQuery('.retail_lot_id_orig').val('');
    jQuery('.retail_wholesale_price').val('');
    jQuery('.retail_unit').val('');
    jQuery('.retail_discount').val('');
    jQuery('#lot_id').focus();
}


function payment_calculation(){
    var total           = parseFloat(jQuery('.fsub_total').val());
    var due             = parseFloat(jQuery('.balance_amount_val').val());
    var total_payment   = due + total;
    var paid_tot = 0;
    jQuery('.payment_table').each(function() {  
        var tot     = parseFloat(jQuery(this).find('.payment_amount').val());
        tot         = isNaN(tot) ? 0 : tot ;
        paid_tot    = paid_tot + tot;       
    });
   

    var total_pay     = total_payment - paid_tot;
    var cur_pay       = total - paid_tot;
    cur_pay = (cur_pay >= 0)? cur_pay : 0 ;
    jQuery('.pay_amount_cheque').val(cur_pay);
    jQuery('.cod_amount').val(cur_pay);
    jQuery('.paid_amount').val(paid_tot);
    jQuery('.paid_amount').trigger('change');
    return total_pay;
}

///<--- End individual calculation function--->
function billBalancePaidIndividual(selector) {
    var sum = 0;
    jQuery(selector).find('.row_cash_paid').each(function(){
        sum += parseFloat(this.value);
    });
    return sum;
}

function deleteDueCash(uniquename){
    jQuery('tr.due_data').each(function(){
        jQuery(this).find('[ref-uniquename="'+uniquename+'"]').remove();
        var previous_paid = billBalancePaidIndividual(jQuery(this));
        jQuery(this).find('.paid_due').val(previous_paid);

    });
    //individualBillPaidCalculation();
}
function customerBalance(customer_id = 0){
    jQuery.ajax({
        type: "POST",
        dataType : "json",
        url: frontendajax.ajaxurl,
        data: {
            id      : customer_id,
            action  :'customer_balance'
        },
          success: function (data) {
            jQuery('.balance_amount').text(data.final_bal);
            jQuery('.balance_amount_val').val(data.final_bal);
            rowCalculate();

        }
    });
}

function duepaid_fun(customer_id = 0){
jQuery.ajax({
        type: "POST",
        dataType : "json",
        url: frontendajax.ajaxurl,
        data: {
            id                  : customer_id,
            action              :'DuePaid',
            type                : 'retail',
            reference_id        : jQuery('.reference_id').val(),
            reference_screen    : jQuery('.reference_screen').val(),
        },
         success: function (data) {
            var due_data = data.due_data;
            jQuery.each( due_data, function(a,b) {
                //console.log(parseFloat(b.sale_id));
                if(b.final_bal != '0.00' && parseFloat(b.sale_id) != parseFloat(jQuery('.invoice_id').val())){ 
                    var existing_count  = parseInt( jQuery('#due_tab tr').length );
                    var current_row     = existing_count + 1; 
                    var tab_data = '<tr class="due_data"><td style="padding:5px;">' + b.sale_id + '<input type="hidden" name="due_detail['+current_row+'][due_id]" value="'+b.sale_id+'" style="width:20px;" class="due_id"/><input type="hidden" name="due_detail['+current_row+'][due_search_id]" value="'+b.search_id+'" style="width:20px;" class="due_search_id"/><input type="hidden" name="due_detail['+current_row+'][due_year]" value="'+b.year+'" style="width:20px;" class="due_year"/><input type="hidden" name="due_detail['+current_row+'][type_payment]" class="type_payment" value="due"/></td><td style="padding:5px;">' + b.final_bal + '<input type="hidden" name="due_detail['+current_row+'][due_amount]" value="'+b.final_bal+'" style="width:20px;" class="due_amount"/></td><td style="padding:5px;"><input type="text" name="due_detail['+current_row+'][paid_due]" class="paid_due" value="" style="width: 74px;" onkeypress="return isNumberKey(event)"/><input type="hidden" name="paid_due_hidden" class="paid_due_hidden" value="0"/></td><td><table class="duePaymentType"></table></td></tr>';
                    jQuery('#due_tab').append(tab_data);
                }
            });   
            //individualBillPaidCalculation();
        }
    });
}

function addTotalToDue(){
    jQuery('.current_paid_row').remove();
    var year          = jQuery('.year').val();
    var inv_id        = jQuery('.inv_id').val();
    var invoice_id    = jQuery('.invoice_id').val();
    var amount        = jQuery('.fsub_total').val();
    var existing_count  = parseInt( jQuery('#due_tab tr').length );
    var current_row     = existing_count + 1;
    var tab_data = '<tr class="due_data current_paid_row"><td style="padding:5px;">' + invoice_id + '<input type="hidden" name="due_detail['+current_row+'][due_id]" value="'+invoice_id+'" style="width:20px;" class="due_id"/><input type="hidden" name="due_detail['+current_row+'][due_search_id]" value="'+inv_id+'" style="width:20px;" class="due_search_id"/><input type="hidden" name="due_detail['+current_row+'][due_year]" value="'+year+'" style="width:20px;" class="due_year"/><input type="hidden" name="due_detail['+current_row+'][type_payment]" class="type_payment" value="current"/></td><td style="padding:5px;">' + amount + '<input type="hidden" name="due_detail['+current_row+'][due_amount]" value="'+amount+'" style="width:20px;" class="due_amount"/></td><td style="padding:5px;"><input type="text" name="due_detail['+current_row+'][paid_due]" class="paid_due" value="" style="width: 74px;" onkeypress="return isNumberKey(event)"/><input type="hidden" name="paid_due_hidden" class="paid_due_hidden" value="0"/></td><td><table class="duePaymentType"></table></td></tr>';
    jQuery('#due_tab').prepend(tab_data);
}

 function individualBillPaidCalculation(){
    // jQuery('.bill_paid').val(0);
    // //LOOP 1
    // jQuery('.payment_amount').each(function(){
    //     var bill_paid       = parseFloat(jQuery('.bill_paid').val());
    //     var bill_total      = parseFloat(jQuery('.fsub_total').val());
    //     var pay_type        = jQuery(this).data('paymenttype');
    //     var uniquename      = jQuery(this).data('uniquename');
        
    //     var current_pay     = parseFloat(jQuery(this).val());
    //     var pay_now         = current_pay;
    //     // if(bill_total >= pay_now){
    //     //     jQuery('.bill_paid').val(pay_now);   
    //     //     jQuery('tr.due_data').each(function(){
    //     //         jQuery(this).find('[ref-uniquename="'+uniquename+'"]').remove();
    //     //     });    
    //     // } else {
    //         jQuery('.bill_paid').val(bill_total);
    //         //loop 1 bal
    //         var bal = current_pay;
    //         jQuery('tr.due_data').find('[ref-uniquename="'+uniquename+'"]').remove();
    //         //LOOP 2
    //         jQuery('tr.due_data').each(function(){
    //             //Delete code here
    //             var id                      = jQuery(this).find('.due_id').val();
    //             var inv_id                  = jQuery(this).find('.due_search_id').val();
    //             var year                    = jQuery(this).find('.due_year').val();
    //             var due_amount              = jQuery(this).find('.due_amount').val();
    //             var type_payment            = jQuery(this).find('.type_payment').val();
    //             //jQuery(this).find('[ref-uniquename="'+uniquename+'"]').remove();
    //             var previous_paid           = billBalancePaidIndividual(jQuery(this));     
    //             var bill_due                = jQuery(this).find('.due_amount').val() ? parseFloat(jQuery(this).find('.due_amount').val()) : 0;
    //             var due_paid                = jQuery(this).find('.paid_due_hidden').val() ? parseFloat(jQuery(this).find('.paid_due_hidden').val()) : 0;
    //             due_paid                    = due_paid+previous_paid;          
    //             var current_row_pay_total   = bal+due_paid;
    //             if(bill_due >= current_row_pay_total) {
    //                 jQuery(this).find('.paid_due').val(current_row_pay_total);
    //                 var str = '<tr class="aa" ref-uniquename="'+uniquename+'"><td class="ab"><input type="text" ref-uniquename="'+uniquename+'" ref-paytype="'+pay_type+'" class="row_cash_paid" name="duepayAmount[]['+type_payment+']" value="'+bal+'"></td><input type="hidden" name="duepayUniquename[]['+type_payment+']" value="'+uniquename+'"/><input type="hidden" name="duePaytype[]['+type_payment+']" value="'+pay_type+'"/><input type="hidden" name="dueId[]['+type_payment+']" value="'+id+'"/><input type="hidden" name="dueYear[]['+type_payment+']" value="'+year+'"/><input type="hidden" name="dueInvid[]['+type_payment+']" value="'+inv_id+'"/><input type="hidden" name="dueDueAmount[]['+type_payment+']" value="'+due_amount+'"/></tr>';
    //                 jQuery(this).find('.duePaymentType').append(str);
    //                 bal = 0;
    //                 return false;
    //             } else {
    //                 var current_pay = ((bill_due - due_paid) );
    //                 jQuery(this).find('.paid_due').val(bill_due);
    //                 var str = '<tr class="aa" ref-uniquename="'+uniquename+'"><td class="ab"><input type="text" ref-uniquename="'+uniquename+'" ref-paytype="'+pay_type+'" class="row_cash_paid" name="duepayAmount[]['+type_payment+']" value="'+current_pay+'"></td><input type="hidden" name="duepayUniquename[]['+type_payment+']" value="'+uniquename+'"/><input type="hidden" name="duePaytype[]['+type_payment+']" value="'+pay_type+'"/><input type="hidden" name="dueId[]['+type_payment+']" value="'+id+'"/><input type="hidden" name="dueYear[]['+type_payment+']" value="'+year+'"/><input type="hidden" name="dueInvid[]['+type_payment+']" value="'+inv_id+'"/><input type="hidden" name="dueDueAmount[]['+type_payment+']" value="'+due_amount+'"/></tr>';
    //                 jQuery(this).find('.duePaymentType').append(str);
    //                 bal = bal-current_pay;
    //             }
               
    //         });
    //     //}   
    // }); 
}

function Capital(str){
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    return str;
}

function ReturnPaymentTypeCalculation(){
    var return_amount = parseFloat(jQuery('.rtn_fsub_total').val());
    var amount_new = 0;
    var cur_pay = 0;
    jQuery('.tr_return_payment').each(function(){
        var cur_row = parseFloat(jQuery(this).find('.paid_amount').val());
        if(return_amount < cur_row )    {
            jQuery(this).find('.ret_amount').val(return_amount);
            return_amount = 0;
        }
        else {
            
             return_amount = return_amount - cur_row;
            jQuery(this).find('.ret_amount').val(cur_row);
        }

});
}