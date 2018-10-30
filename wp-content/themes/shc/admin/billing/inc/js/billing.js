jQuery(document).ready(function (argument) {

jQuery('#ws_billing_company').on('keydown',function(e){
     var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.ws_bill_submit').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#ws_billing_customer').focus();
      } 
      else {
        jQuery('#ws_billing_company').focus();
      }
});
jQuery('.ws_bill_submit').on('keydown',function(e){
    var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.delivery_need').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#ws_billing_company').focus();
      } 
      else {
        jQuery('.ws_bill_submit').focus();
      }
});
//<----- Payment type JS------>
jQuery('.ws_payment_cash').live('click',function(){  
    var today = moment().format(' YYYY-MM-DD'); 
    var reference_id = jQuery('.invoice_id').val();
    jQuery('.ws_payment_tab').css('display','block');
    if(jQuery(this).is(':checked')) {
        var type            = jQuery(this).attr('data-paytype'); 
        
        if(type == 'credit'){  
            var readonly        = 'readonly';
            var existing_count  = parseInt( jQuery('#ws_bill_payment_tab_cheque tr').length );
            var current_row     = existing_count + 1;
            if(current_row == 1){
                var str1            = '<tr class="ws_payment_cheque"><td style="padding:5px;">' + Capital(type) + '<input type="hidden" name="pay_cheque" value="'+type+'" style="width:20px;" class="ws_pay_cheque"/></td><td style="padding:5px;"><input type="text" name="pay_amount_cheque" class="ws_pay_amount_cheque" '+ readonly +' value="'+ jQuery('.ws_fsub_total').val() +'" style="width: 74px;" onkeypress="return isNumberKey(event)"/><input type="hidden" name="payment_detail['+current_row+'][reference_screen]" value="billing_screen" /><input type="hidden" name="payment_detail['+current_row+'][reference_id]" value="'+ reference_id +'" /></td><td style="width: 190px;">'+today+'</td><td style="padding:5px;width:75px;"><a  href="#" class="ws_payment_sub_delete" style="">x</a></td></tr>';
                jQuery('#ws_bill_payment_tab_cheque').append(str1);  
            }
           
        } else {
            if(type == 'internet'){
                var type_text   = 'Netbanking';
            } else{
                var type_text = Capital(type);
            }
            var existing_count  = parseInt( jQuery('#ws_bill_payment_tab tr').length );
            var current_row     = existing_count + 1;
            var str             = '<tr class="ws_payment_table"><td style="padding:5px;">' + type_text + '<input type="hidden" name="payment_detail['+current_row+'][payment_type]" value="'+type+'" style="width:20px;" class="ws_payment_type"/></td><td style="padding:5px;"><input type="text" name="payment_detail['+current_row+'][payment_amount]" class="ws_payment_amount" data-paymenttype="'+type+'"  data-uniqueName="'+makeid()+'"  value="" style="width: 74px;" onkeypress="return isNumberKeyWithDot(event)"/><input type="hidden" name="payment_detail['+current_row+'][reference_screen]" value="billing_screen" /><input type="hidden" name="payment_detail['+current_row+'][reference_id]" value="'+ reference_id +'" /></td><td style="padding"5px;>'+today+'</td><td style="padding:5px;"><a  href="#" class="ws_payment_sub_delete" style="">x</a></td></tr>';                
            jQuery('#ws_bill_payment_tab').append(str);
        }
        ws_payment_calculation();
        
        
    }

 });

jQuery('.ws_payment_sub_delete').live('click',function(e){
    var sub_tot     = 0;
    if (confirm('Are you sure want to delete?')) {
        jQuery(this).parent().parent().remove();
    }
    e.preventDefault();
     var existing_count  = parseInt( jQuery('#ws_bill_payment_tab tr').length );
     if(existing_count >= 1){
        jQuery('.ws_payment_amount').focus();
    } else{
        jQuery('.ws_payment_cash').focus();
    }
    
    ws_payment_calculation();
    jQuery('.ws_paid_amount').trigger('change');

    var uniquename = jQuery(this).parent().parent().find('.ws_payment_amount').data('uniquename');
    deleteWsDueCash(uniquename);
    
});
jQuery('.ws_payment_cash').live('keydown', function(e){
    var keyCode = e.keyCode || e.which; 
    if (keyCode == 40) {
        e.preventDefault();
        jQuery('.ws_payment_amount').focus();
    }
});

jQuery('.ws_payment_amount').live('keydown', function(e){
    var keyCode = e.keyCode || e.which; 
    if (keyCode == 38) { 
        e.preventDefault();
        jQuery('.ws_payment_cash').focus();
    }
});


jQuery('.ws_payment_amount').live('change click',function(){
    var current_balance = ws_payment_calculation();
    var amount          = parseFloat(jQuery(this).parent().parent().find('.ws_payment_amount').val());
    var payment_type    = jQuery(this).parent().parent().find('.ws_payment_type').val();
    var sub_tot = 0;
    if( payment_type == 'card' || payment_type == 'internet' ||  payment_type == 'cheque' ){
        if(current_balance >= 0) {
            console.log("ok");
        } else {
            alert("Please Enter Amount as less than Total amount!!!");
            parseFloat(jQuery(this).parent().parent().find('.ws_payment_amount').val(0));
            
        }
    } 
    ws_payment_calculation();
    jQuery('.ws_paid_amount').trigger('change');   
    
});

jQuery('.ws_cod_check').on('click',function(){
    if(jQuery('.ws_cod_check:checked').val()=='cod'){
        jQuery('.ws_cod_amount_div').css("display","block");
        ws_payment_calculation();

    } else {
        jQuery('.ws_cod_amount_div').css("display","none");
        jQuery('.ws_cod_amount').val('0');
        
    }

});

//ws delivery need or not 
jQuery('.delivery_need').live('click',function(){
    var delivery  = jQuery('.delivery_need:checked').val();
    if(delivery == "yes"){
        jQuery('.delivery_display').css('display','block');
    }
    else{
        jQuery('.delivery_display').css('display','none');
    }

});

//Ws delivery check products

     jQuery(".ws_check_all").click(function(){
        var check_all = jQuery(".ws_check_all").is(":checked");
        if(check_all == '1'){
           jQuery(".ws_delivery_check").trigger("click"); 
           jQuery(".ws_delivery_check").attr('checked', true); // if i remove this line then selectall for checkbox don't works 
        } else {
            jQuery(".ws_delivery_check").trigger("click"); 
            jQuery(".ws_delivery_check").attr('checked', false);
            jQuery('.ws_delivery_count_div').css("display","none");
        }
                     
     });  

    jQuery('.ws_delivery_check').live('click',function(){

        var unit_count = parseFloat(jQuery(this).parent().parent().find('.ws_unit_count').val());
        

        var delivery_check = jQuery(this).parent().parent().find('.ws_delivery_check').is(":checked");
        if(delivery_check){
            var delivery        = 1;  
            jQuery(this).parent().parent().find('.ws_delivery_count').css("display","inline-block");
            jQuery(this).parent().parent().find('.ws_delivery_count').val(unit_count);
            var delivery_count  = parseFloat(jQuery(this).parent().parent().find('.ws_delivery_count').val());

        } else {

            var delivery        = 0;
            jQuery(this).parent().parent().find('.ws_delivery_count').css("display","none"); 
            jQuery(this).parent().parent().find('.ws_delivery_count').val(0);
            var delivery_count  = 0;
        }
        var delivery_id = jQuery(this).parent().parent().find('.ws_delivery_id').val();
        
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action          :'ws_product_delivery',
                id              : delivery_id,
                delivery        : delivery,
                delivery_count  : delivery_count,
            },
            success: function (data) {
                clearPopup();     
                
            }
        });
     });

     jQuery('.ws_delivery_count').on('change',function(){
        var delivery_count_check = parseFloat(jQuery(this).parent().parent().find('.ws_delivery_count').val());
        var unit_count = parseFloat(jQuery(this).parent().parent().find('.ws_unit_count').val());
        if( delivery_count_check > unit_count){
            alert('please enter correct delivery product count !!!');
            jQuery(this).parent().parent().find('.ws_delivery_count').val(unit_count);

        }
        if(delivery_count_check == 0){
            jQuery(this).parent().parent().find('.ws_delivery_check').attr('checked',false);
        }
        var delivery_check = jQuery(this).parent().parent().find('.ws_delivery_check').is(":checked");
      
        if(delivery_check){
            var delivery = 1;
            
            jQuery(this).parent().parent().find('.ws_delivery_count').css("display","inline-block");
             var delivery_count = parseFloat(jQuery(this).parent().parent().find('.ws_delivery_count').val());
             
        } else {
            var delivery = 0;
            jQuery(this).parent().parent().find('.ws_delivery_count').css("display","none");
            jQuery(this).parent().parent().find('.ws_delivery_count').val(0);
            var delivery_count = 0;
        }
        var delivery_id = jQuery(this).parent().parent().find('.ws_delivery_id').val();
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action          :'ws_product_delivery',
                id              : delivery_id,
                delivery        : delivery,
                delivery_count  : delivery_count,
            },
            success: function (data) {
                clearPopup();     

            }
        });

     });




    jQuery('#ws_billing_mobile').live('keydown', function(e){
        var keyCode = e.keyCode || e.which; 
        if (keyCode == 40) { 

            if(jQuery('#ui-id-1').css('display') != 'block') {
                e.preventDefault();
                jQuery('.secondary_mobile').css('display', 'block');
                jQuery('#ws_billing_secondary_mobile').focus();
            }

        }
    });

    //<-------- display secondary and landline textboxes----->
    jQuery('#ws_billing_secondary_mobile').live('keydown', function(e){
        var keyCode = e.keyCode || e.which; 
        if (keyCode == 40) { 
            e.preventDefault(); 
            jQuery('.landline_mobile').css('display', 'block');
            jQuery('#ws_billing_landline_mobile').focus();
        }
        if (keyCode == 38) { 
            e.preventDefault(); 
            jQuery('.secondary_mobile').css('display', 'none');
            jQuery('#ws_billing_secondary_mobile').val('');
            jQuery('#ws_billing_mobile').focus();
        }
    });
    jQuery('#ws_billing_landline_mobile').live('keydown', function(e){ 
        console.log(e);
        var keyCode = e.keyCode || e.which; 
        if (keyCode == 38) { 
            e.preventDefault(); 
            jQuery('.landline_mobile').css('display', 'none');
            jQuery('#ws_billing_landline_mobile').val('');
            jQuery('#ws_billing_secondary_mobile').focus();
        }
    });
//<----------- End mobile textboxes------>

    jQuery("#ws_submit_payment").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
        if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.ws_delivery_address').focus();
        }
        else if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('#ws_billing_company').focus();
        }
        else {
            jQuery('#ws_submit_payment').focus();
        } 

    });
	
	 jQuery("#ws_update_payment").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 

     if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.ws_delivery_address').focus();
        }
        else if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('#ws_billing_company').focus();
        }
        else {
            jQuery('#ws_update_payment').focus();
        } 

    });
    jQuery('.year').datepicker({
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy',
        onClose: function(dateText, inst) { 
            var year = jQuery("#ui-datepicker-div .ui-datepicker-year :selected").val();
            jQuery(this).datepicker('setDate', new Date(year, 1));
        }
    });

    //<------- Validation Function --------> 
   
 jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

//<---  validation for whole sale--->
    jQuery( ".ws_billing_validation" ).validate({
        rules: {
            name: {
                nameValidite : true,
            },
            company_name : {
                nameValidite : true,
                required : true
            },
            mobile: { 
                minlength: 10,
                maxlength: 10,
                uniqueUserMobile: true
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
            gst : {
                gstValidate : true,
                minlength: 15,
                maxlength: 15,
            },
            delivery_name: {
                nameValidite : true,
            },
            ws_delivery_phone: {
                minlength:6,
            },
            ws_delivery_address : {
                addressValidate : true,
            }

        },
        messages: {
            name: {
                nameValidite: "Special Characters Not Allowed!",
            },
            company_name : {
                nameValidite : "Special Characters Not Allowed!",
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
            gst : {
                gstValidate : "GST Numbers Does Not Contain Special Characters!",
                minlength: "GST Number Must Be 15 Letters!",
                maxlength: "GST Number Must Be 15 Letters!",
            },
            delivery_name : {
                nameValidite: "Special Characters Not Allowed!",
            },
            ws_delivery_phone : {
                minlength: "Please Enter Valid  Number!",
            },
             ws_delivery_address: {
                addressValidate : "Address field contains only Alphabets,number , # , . , : , - and ,",
            },

        }
    });






      /*WS Submit Payment*/
    jQuery('#ws_billing_container #ws_submit_payment').on('click', function() {

        var valid = jQuery(".ws_billing_validation").valid();
        var prevent = jQuery(".form_submit_prevent_ws_bill").val();
        if( valid) {
            var existing_count = parseInt( jQuery('#bill_lot_add tr').length );
            if(existing_count != 0 && prevent == "off") {
                jQuery(".form_submit_prevent_ws_bill").val('on');
                var bill_update_url = bill_updatews.updateurlws;
                jQuery('#lightbox').css('display','block');
                jQuery.ajax({
                    type: "POST",
                    dataType : "json",
                    url: frontendajax.ajaxurl,
                    data: {
                        action : 'ws_create_order',
                        data   : jQuery('#ws_billing_container :input').serialize()
                    },
                    success: function (data) {
                        clearPopup();
                        popItUp('Success', 'Bill Created!');
                        jQuery('#lightbox').css('display','none');

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

   
    /*WS Update Payment*/
    jQuery('#ws_billing_container #ws_update_payment').on('click', function() {

        var valid = jQuery(".ws_billing_validation").valid();
        var prevent = jQuery(".form_submit_prevent_ws_bill").val();
       if( valid ) {
          
            var existing_count = parseInt( jQuery('#bill_lot_add tr').length );
            if(existing_count != 0 && prevent == "off") {
                jQuery(".form_submit_prevent_ws_bill").val('on');
                var bill_invoice_url = bill_invoicews.invoiceurlws;
                jQuery('#lightbox').css('display','block');
                jQuery.ajax({
                    type: "POST",
                    dataType : "json",
                    url: frontendajax.ajaxurl,
                    data: {
                        action : 'ws_update_order',
                        data : jQuery('#ws_billing_container :input').serialize()
                    },
                    success: function (data) {
                        console.log(data);
                        clearPopup();
                        popItUp('Success', 'Successfully Updated!');
                        jQuery('#lightbox').css('display','none');

                        window.location = bill_invoice_url+'&id='+ data.inv_id + '&year='+ data.year + '&action=update';

                    }
                });
            }
        else {
            alert('Please Add Atleast One Product!!! Empty Bill Can'+"'"+'t Submit');
        }
    }
       return false; 
    });


//<---- Billing mobile and customer search------->
     jQuery( "#ws_billing_customer, #ws_billing_mobile,#ws_billing_company" ).autocomplete ({
      source: function( request, response ) {
        var billing_field = jQuery(this.element).attr('id');
        jQuery.ajax( {
          url: frontendajax.ajaxurl,
          type: 'POST',
          dataType: "json",
          data: {
            action: 'get_ws_customer_mobile',
            search_key: request.term
          },
          success: function( data ) {
            response(jQuery.map( data.result, function( item ) {
                if(billing_field == 'ws_billing_customer') {
                    // var field_val = item.customer_name +' \('+ item.mobile +'\)';
                    var field_val = item.customer_name;
                    var identification = 'name';
                } else if(billing_field == 'ws_billing_company')
                {
                    var field_val = item.company_name;
                    var identification = 'company';
                }
                else
                {
                    var field_val = item.mobile;
                    var identification = 'mobile';
                }
                return {
                    id: item.id,
                    value: field_val,
                    address : item.address,
                    mobile : item.mobile,
                    company_name : item.company_name,
                    gst : item.gst_number,
                    secondary_mobile : item.secondary_mobile,
                    landline : item.landline,
                    name:item.customer_name,
                    identification : identification
                }
            }));
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) {

            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    id      : ui.item.id,
                    action  :'ws_customer_balance'
                },
                  success: function (data) {
                    
                    jQuery('.ws_balance_amount').text(data.final_bal);
                    jQuery('.ws_balance_amount_val').val(data.final_bal);
                    ws_rowCalculate();


                }
            });
            jQuery('.ws_old_customer_id').val(ui.item.id);
            jQuery('.customer_id').val(ui.item.id);
            jQuery('#ws_billing_address').val(ui.item.address);

            if(ui.item.identification == 'mobile' ) {
                jQuery('#ws_billing_mobile').val(ui.item.value);
                jQuery('.ws_delivery_phone').val(ui.item.value);
                jQuery('#ws_billing_customer').val(ui.item.name); 
                jQuery('#ws_billing_company').val(ui.item.company_name);
               

            } else if(ui.item.identification == 'company' ){
                jQuery('#ws_billing_mobile').val(ui.item.mobile);
                jQuery('.ws_delivery_phone').val(ui.item.mobile);
                jQuery('#ws_billing_customer').val(ui.item.name);
                jQuery('#ws_billing_company').val(ui.item.value);
            }
             else {
                jQuery('#ws_billing_mobile').val(ui.item.mobile);
                jQuery('.ws_delivery_phone').val(ui.item.mobile);
                jQuery('#ws_billing_customer').val(ui.item.value);
                jQuery('#ws_billing_company').val(ui.item.company_name);
            }

        
            jQuery('#ws_billing_secondary_mobile').val(ui.item.secondary_mobile);
            jQuery('#ws_billing_landline_mobile').val(ui.item.landline);
            jQuery('#ws_billing_gst').val(ui.item.gst);
            jQuery('.ws_delivery_name').val(ui.item.company_name);
            jQuery('.ws_delivery_address').val(ui.item.address);

            jQuery('.ws_lot_id').focus();
            jQuery('.ws_paid_amount').trigger('change');
      },
        response: function(event, ui) {
        if (ui.content.length == 1)
        {
            jQuery(this).val(ui.content[0].value);
            jQuery(this).autocomplete( "close" );
         
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    id      : ui.content[0].id,
                    action  :'ws_customer_balance'
                },
                  success: function (data) {
                    
                    jQuery('.ws_balance_amount').text(data.final_bal);
                    jQuery('.ws_balance_amount_val').val(data.final_bal);
                    ws_rowCalculate();


                }
            });
            jQuery('.ws_old_customer_id').val(ui.content[0].id);
            jQuery('.customer_id').val(ui.content[0].id);
            jQuery('#ws_billing_address').val(ui.content[0].address);

            if(ui.content[0].identification == 'mobile' ) {
                jQuery('#ws_billing_mobile').val(ui.content[0].value);
                jQuery('#ws_billing_customer').val(ui.content[0].name);
                jQuery('.ws_delivery_phone').val(ui.content[0].value);
                jQuery('#ws_billing_company').val(ui.content[0].company_name);
            } 
            else if(ui.content[0].identification == 'company' ){
                jQuery('#ws_billing_mobile').val(ui.content[0].mobile);
                jQuery('#ws_billing_customer').val(ui.content[0].name);
                jQuery('.ws_delivery_phone').val(ui.content[0].mobile);
                jQuery('#ws_billing_company').val(ui.content[0].value);
            }
            else {
                jQuery('#ws_billing_mobile').val(ui.content[0].mobile);
                jQuery('#ws_billing_customer').val(ui.content[0].value);
                jQuery('.ws_delivery_phone').val(ui.content[0].mobile);
                jQuery('#ws_billing_company').val(ui.content[0].company_name);
            }   
            jQuery('#ws_billing_secondary_mobile').val(ui.content[0].secondary_mobile);
            jQuery('#ws_billing_landline_mobile').val(ui.content[0].landline);

            jQuery('#ws_billing_gst').val(ui.content[0].gst);

            jQuery('.ws_delivery_name').val(ui.content[0].company_name);
            jQuery('.ws_delivery_address').val(ui.content[0].address);
            

            jQuery('.ws_lot_id').focus();
            jQuery('.ws_paid_amount').trigger('change');

        }
      }
    });

     //<---- Generate Print and Download Page ---> 
    jQuery('.generate_bill_new').live('click',function() {
        var inv_id = jQuery('.invoice_id_new').val();
        var year = jQuery('.year').val();
        var datapass =   home_page.url+'invoice-download/?id='+inv_id+'&year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });

    jQuery('.ws_generate_bill').live('click',function() {
        var inv_id = jQuery('.invoice_id').val();
        var year = jQuery('.year').val();
        var datapass =   home_page.url+'ws-download/?id='+inv_id+'&cur_year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });

    //download page
    jQuery('.ws_return_generate_bill').live('click',function() {
        var id = jQuery('.invoice_id').val();
        var datapass =   home_page.url+'ws-goods-return-download/?id='+id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });



    jQuery('.ws_print_bill').live('click',function(){
        var inv_id = jQuery(this).parent().parent().find('.invoice_id').val();
        var year = jQuery(this).parent().parent().find('.year').val();
        var datapass =   home_page.url+'ws-invoice/?id='+inv_id+'&cur_year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });


    jQuery('.ws_delivery_print').live('click',function(){
        var inv_id = jQuery(this).parent().parent().find('.invoice_id').val();
        var year = jQuery(this).parent().parent().find('.year').val();
        var datapass =   home_page.url+'ws-delivery-print/?id='+inv_id+'&cur_year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });


    jQuery('.ws_return_print').live('click',function() {
        var gr_id = jQuery(this).parent().parent().find('.gr_id').val();
        var datapass =   home_page.url+'ws-return/?id='+gr_id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });

    jQuery('.return_print').live('click',function() {
        var gr_id = jQuery(this).parent().parent().find('.gr_id').val();
        var datapass =   home_page.url+'return/?id='+gr_id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });


    jQuery('.ws_print_invoice_list').live('click',function() {
        var  inv_id = jQuery('.inv_id').val();
        var  cus_name = jQuery('.name').val();
        var  mobile = jQuery('.mobile').val();
        var bill_from = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'ws-sale-report-print/?bill_from='+bill_from+'&bill_to='+bill_to + '&inv_id='+inv_id+'&cus_name='+cus_name+'&mobile='+mobile;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","");
        thePopup.print();  
    });

        jQuery('.ws_download_invoice_list').live('click',function() {
        var  inv_id = jQuery('.inv_id').val();
        var  cus_name = jQuery('.name').val();
        var  mobile = jQuery('.mobile').val();
        var bill_from = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'ws-sale-report-download/?bill_from='+bill_from+'&bill_to='+bill_to + '&inv_id='+inv_id+'&cus_name='+cus_name+'&mobile='+mobile;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","");
    });


     //<---- End Generate Print and Download Page ---> 
//<------ Credit Notes ------->
    jQuery('.cur_bal_check_box').on('click',function(){
        jQuery('.ws_paid_amount').trigger('change');  
        jQuery('.paid_amount').trigger('change');  
        
    });

//<--- Display Balance -----> 
    jQuery('.ws_paid_amount').on('change click',function() {
        

        prev_bal = parseFloat(jQuery('.ws_balance_amount_val').val());
        current_bal = parseFloat(jQuery('.ws_fsub_total').val());
        
         if ( jQuery('.ws_paid_amount').val() == '')
         {
            var paid = parseFloat('0.00');   
         } else {
            var paid = parseFloat(jQuery('.ws_paid_amount').val());

         }

        bal = (current_bal) - paid;

//current_bal 
        currt_bal = paid - current_bal;

       

        if(currt_bal < 0 ){
            jQuery('.ws_current_bal').val(0);
            jQuery('.ws_current_bal_txt').text(0);
          
            // jQuery('.cur_bal_check_box').css('display','none');
            // jQuery('.cur_bal_check_box').prop('checked',false);

        }
        else {
            jQuery('.ws_current_bal').val(currt_bal);
            jQuery('.ws_current_bal_txt').text(currt_bal);
            // jQuery('.cur_bal_check_box').css('display','block');


        }

        if(jQuery('.cur_bal_check_box').attr('checked')){
           var final_data =  bal + currt_bal;
            
        }  else {
            var final_data = (prev_bal + current_bal) - paid;
        }
        bal = (bal > 0)? bal: 0 ;
        jQuery('.ws_balance_pay').text(bal);
        jQuery('.ws_return_amt').val(final_data.toFixed(2));
        jQuery('.ws_return_amt_txt').text(final_data.toFixed(2));


    });

//<--- End Display Balance -----> 

});


function customer_create_submit_popup_ws(action = '', length = 0) {
    jQuery.ajax   ({
        dataType : "json",
        type: "POST",
        url: frontendajax.ajaxurl,
        data: {
            key     : 'post_popup_content',
            data    : jQuery("#ws_add_customer").serialize(),
            length  : length,
            action  : action
        },
        success: function (data1) { 

            jQuery('.ws_customer_id_new').val(data1.id);
            jQuery('.ws_new_customer').val(data1.mobile);
            jQuery('.ws_address1').text(data1.address);
            jQuery('.ws_customer_name').text(data1.customer_name);
            jQuery('.ws_customer_company').text(data1.company_name);
            clear_main_popup();
            jQuery('#src_info_box').bPopup().close();
            alert_popup('<span class="success_msg">Customer Account Created!</span>', 'Success');

        }
    });
}



function create_popup(action = '', title = '') {
    jQuery.ajax({
        type: "POST",
        url: frontendajax.ajaxurl,
        data: {
            key : 'get_popup_content',
            action : action
        },
        success: function (data) {
            jQuery('#popup-title').html(title);
            clear_main_popup();
            jQuery('#popup-content').html(data);
        }
    });
}

function clear_main_popup() {
    jQuery('#popup-content').html('');              
}




function populateSelectws(selector, v) {

     jQuery( "#ws_lot_id" ).autocomplete ({
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
                        unit_price : item.mrp,
                        wholesale_price : item.wholesale_price,
                        value : item.product_name +' \('+item.brand_name+'\)',
                    }
                }));
              }
            } );
        },
        minLength: 2,
        select: function( event, ui ) {


            jQuery('.ws_lot_id_orig').val(ui.item.id);  
            jQuery('.ws_product').val(ui.item.product_name);  
            jQuery('.ws_brand').val(ui.item.brand_name);  
            jQuery('.ws_unit_price').val(ui.item.unit_price);
            jQuery('.ws_wholesale_price').val(ui.item.wholesale_price);
            jQuery('.discount').val(ui.item.unit_price);
            jQuery('.ws_hsn').val(ui.item.hsn);
            jQuery('.cgst_percentage').val(ui.item.cgst);
            jQuery('.sgst_percentage').val(ui.item.sgst);

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
                    var str = '<td class="td_id">'+'1'+'</td><td class="stock_prod">' + ui.item.product_name + '</td><td class="stock_prod">' + ui.item.unit_price + '</td><td class="slap_stock">'+ data +'</td><input type="hidden" name="ws_slab_sys_txt" value="'+ data +'"  class="ws_slab_sys_txt"/>';                
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

                jQuery('.ws_lot_id_orig').val(ui.content[0].id);  
                jQuery('.ws_product').val(ui.content[0].product_name); 
                jQuery('.ws_brand').val(ui.content[0].brand_name); 
                jQuery('.ws_unit_price').val(ui.content[0].unit_price);
                jQuery('.ws_wholesale_price').val(ui.content[0].wholesale_price);
                jQuery('.discount').val(ui.content[0].unit_price);
                jQuery('.ws_hsn').val(ui.content[0].hsn);
                jQuery('.cgst_percentage').val(ui.content[0].cgst);
                jQuery('.sgst_percentage').val(ui.content[0].sgst);

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
                        var str = '<td class="td_id">'+'1'+'</td><td class="stock_prod">' + ui.content[0].product_name + '</td><td class="stock_prod">' + ui.content[0].unit_price + '</td><td class="slap_stock">'+ data +'</td><input type="hidden" name="ws_slab_sys_txt" value="'+ data +'"  class="ws_slab_sys_txt"/>';                
                        jQuery('.stock_table_body').html(str);

                    }
                }); 
            }
        }

    });

}



function validatePhone(txtPhone) {
    var filter = /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/;
    if (filter.test(txtPhone)) {
        return true;
    }
    else {
        return false;
    }
}

function clear_main_popup() {
    jQuery('#popup-content').html('');   
    jQuery('#popup-content-s').html('');            
}

function clear_alert_popup() {
    jQuery('#popup-title_alert').html('');
    jQuery('#popup-content_alert .err_message').html('');               
}

function alert_popup(msg = '', title = '') {
    clear_alert_popup();
    jQuery('#popup-title_alert').html(title);
    if(title == 'Error') {
        jQuery('#popup-content_alert .succ_message').css('display','none');
        jQuery('#popup-content_alert .err_message').css('display','block');

        jQuery('#popup-content_alert .err_message').html(msg);

    }
    if(title = 'Success') {
        jQuery('#popup-content_alert .succ_message').css('display','block');
        jQuery('#popup-content_alert .err_message').css('display','none');

        jQuery('#popup-content_alert .succ_message').html(msg);
    }

    jQuery('#my-button1').click();
}


//<---- Billing With Add button --->
jQuery( document ).ready(function() {

    populateSelectws('.ws_lot_id', 'old');

    jQuery('.unit').live('change keyup',function(){
        var stock = parseFloat(jQuery('.ws_slab_sys_txt').val());
        var unit = parseFloat(jQuery('#unit').val());
        if(unit > stock){
            alert('Available stock is '+ stock + ' !!! Enter Quantity as small as avalible stock!!!');
            jQuery('.unit').val(Math.ceil(stock));
        }
    });




    jQuery('.sub_unit').live('change keyup',function(){

        var unit = parseFloat(jQuery(this).parent().parent().find('.sub_unit').val());
        var stock = parseFloat(jQuery(this).parent().parent().find('.sub_stock').val());
        if( unit > stock){
            alert('Available stock is '+ stock +' !!! Enter Quantity as small as avalible stock!!!');
            jQuery(this).parent().parent().find('.sub_unit').val(Math.ceil(stock));
        }

        if(unit <= '0'){
            alert("please enter unit!!!");
            jQuery(this).parent().parent().find('.sub_unit').focus();
            jQuery(this).parent().parent().find('.sub_unit').val('1');
        }
        
             ws_rowCalculate();
       
        
    });

    jQuery('.sub_discount').live('change',function(){

        jQuery(this).parent().parent().find('.discount_type').val('each');
        var unit = jQuery(this).parent().parent().find('.sub_discount').val();

        if(unit <= '0'){
            alert("please enter price!!!");
            jQuery(this).parent().parent().find('.sub_discount').focus();
            jQuery(this).parent().parent().find('.sub_discount').val('1');
        }
        ws_rowCalculate(); 
    });
    jQuery('.ws_discount').live('change keyup',function() {
        ws_rowCalculate();
    });




    jQuery('.add-button').live('click',function() {


        var product_id          = jQuery('.ws_lot_id_orig').val();
        var product_name        = jQuery('.ws_product').val();
        var brand_name          = jQuery('.ws_brand').val();
        var hsn_code            = jQuery('.ws_hsn').val();
        var stock               = jQuery('.ws_slab_sys_txt').val();
        var price               = jQuery('.ws_unit_price').val();
        var wholesale_price     = jQuery('.ws_wholesale_price').val();
        var unit                = jQuery('.unit').val();
        var discount            = jQuery('.discount').val();
        var cgst                = jQuery('.cgst_percentage').val();
        var sgst                = jQuery('.sgst_percentage').val();


       if( !!product_id && unit !='0' &&  unit != '' && unit > 0 && discount != '0.00' &&  discount != '' && discount != '0') {
            var existing_count = parseInt( jQuery('#bill_lot_add tr').length );
            var current_row = existing_count + 1;
            if( jQuery('.customer_table[data-productid="'+ product_id +'"]').length != 0 ) {
                var selector = jQuery('.customer_table[data-productid='+ product_id +']');
                var actual_unit = selector.find('.sub_unit').val();
                var final_unit = parseFloat(unit) + parseFloat(actual_unit);
                selector.find('.sub_unit').val(final_unit);

                addFromProductControl();
                jQuery('.product_control_error').remove();
            } else {
                var str = '<tr data-randid='+makeid()+' data-productid='+product_id+' class="customer_table" ><td class="td_id">'+current_row+'</td> <input type="hidden" value="'+ product_id + '" name="customer_detail['+current_row+'][id]" class="sub_id" /><td class="td_brand">'+brand_name+'</td> <input type="hidden" value="'+ brand_name + '" name="customer_detail['+current_row+'][brand]" class="sub_brand" /><td class="td_product">' + product_name + '</td> <input type="hidden" value = "'+ product_name + '" name="customer_detail['+current_row+'][product]" class="sub_product"/><td class="td_hsn">' + hsn_code + '</td> <input type="hidden" value = "'+ hsn_code + '" name="customer_detail['+current_row+'][hsn]" class="sub_hsn"/><td class=""><input type="text" onkeypress="return isNumberKey(event)"  value = "'+ unit + '" name="customer_detail['+current_row+'][unit]" class="sub_unit" autocomplete="off" style="width: 40px;" /> </td> <td>' + stock + '</td><input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][stock]" class="sub_stock"/><td class="td_price">' + price + '</td> <input type="hidden" value = "'+ price + '" name="customer_detail['+current_row+'][price]" class="sub_price"/> <td><input type="text" onkeypress="return isNumberKeyWithDot(event)" value ="'+discount +'" name="customer_detail['+current_row+'][discount]" autocomplete="off"  style="width: 70px;" class="sub_discount"/></td><td>'+ wholesale_price +'<input type="hidden" value="'+ wholesale_price +'"  name="customer_detail['+current_row+'][wholesale_price]" class="sub_wholesale_price"/></td><input type="hidden" value ="each" name="customer_detail['+current_row+'][discount_type]" class="discount_type"/><td class="td_amt">' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][amt]" class="sub_amt"/><td class="td_cgst">' + cgst + '  %' + '</td> <input type="hidden" value = "'+ cgst + '" name="customer_detail['+current_row+'][cgst]" class="sub_cgst"/> <td class="td_cgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][cgst_value]" class="sub_cgst_value"/><td class="td_sgst">' + sgst + '  %' + '</td> <input type="hidden" value = "'+ sgst + '" name="customer_detail['+current_row+'][sgst]" class="sub_sgst"/><td class="td_sgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][sgst_value]" class="sub_sgst_value"/><td class="td_subtotal"></td> <input type="hidden" value ="" name="customer_detail['+current_row+'][subtotal]" class="sub_total"/><td><a href="#" class="sub_delete">Delete</a></td></tr>';                
                jQuery('#bill_lot_add').append(str);

                addFromProductControl();
                jQuery('.product_control_error').remove();
            }
            jQuery('.sub_unit').trigger('change');
             ws_rowCalculate();

        } else {
            var product_name_selector = jQuery('#ws_lot_id');
            var lot_id_selector = jQuery('.ws_lot_id_orig');
            var unit_selector = jQuery('#unit');
            var discount_selector = jQuery('#discount');

            var product_name = product_name_selector.val();
            var lot_id = lot_id_selector.val();
            var unit = unit_selector.val();
            var discount = discount_selector.val();
            jQuery('.product_control_error').remove();

            if(product_name == '' || lot_id == '' || lot_id <= 0  ) { 
                product_name_selector.after('<div class="product_control_error control_error">Please Enter Valid Product!</div>');
                product_name_selector.focus();
            }
            if(unit == '' || unit <= 0) {
                unit_selector.after('<div class="product_control_error control_error">Unit Must be above 0</div>');
                unit_selector.focus();
            }
            // if(discount == '' || discount <= 0) {
            //     discount_selector.after('<div class="product_control_error control_error">Please Enter Valid Discounted Price!</div>');
            //     discount_selector.focus();
            // }
        }
        /*else if(product_id == ''){
            alert_popup('Select Product !!!');
        }
        else if ( unit == '0' & unit == ''){
            console.log(discount);
            alert_popup('Enter Unit !!!');
        }
        else {
            alert_popup('Enter Discounted Price !!!');
        }*/

    
    });

    jQuery('.sub_delete').live('click',function(){
       jQuery(this).parent().parent().remove();
        ws_rowCalculate();
        jQuery('.ws_lot_id').focus();
    });

});

function addFromProductControl() {
    jQuery('.ws_lot_id').val('');
    jQuery('.ws_lot_id_orig').val('');
    jQuery('.unit').val('');
    jQuery('.discount').val('');
    jQuery('#ws_lot_id').focus();
}


function makeid() {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < 50; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}


function ws_rowCalculate() {
    var discount = '0.00';
    var sub ='0.00';
    var count = '0.00';
    var unit = '0.00';
    var existing_count = '0';   
    var before_total = parseFloat('0.00');
    var discount =parseFloat(jQuery('.ws_discount').val());

    discount = isNaN(discount) ? '0.00' : discount;
    existing_count = parseInt( jQuery('.discount_type[value="whole"]').length);
    if(jQuery('.discount_type[value="whole"]').length == '0'){
        existing_count = 1;
    };
    var sub_tot=parseFloat(0);
    jQuery('.customer_table').each(function() { 
    var row_sub                 = parseFloat('0.00');
    var row_discount            = parseFloat(jQuery(this).find('.sub_discount').val());
    var row_mrp                 = parseFloat(jQuery(this).find('.sub_price').val());
    unit                        = parseFloat(jQuery(this).find('.sub_unit').val());
    var cgst                    = parseFloat(jQuery(this).find('.sub_cgst').val());
    var sgst                    = parseFloat(jQuery(this).find('.sub_sgst').val());
    if( row_discount == row_mrp  || jQuery(this).find('.discount_type').val() == 'whole' ) {

            // var unit_price      =   jQuery(this).find('.sub_price').val();

            var whole_unit_total      = row_mrp * unit;
           
            var whole_dis   = (whole_unit_total  * discount)/100;
            var unit_total = whole_unit_total - whole_dis;

            var unit_price      = (unit_total / unit);

        jQuery(this).find('.discount_type').val('whole');
       
    }

    else {
        var unit_price          = parseFloat(row_discount);
        var unit_count          = parseFloat(jQuery(this).find('.sub_unit').val());
        var unit_total          = (unit_price * unit_count);
       
    }
        var diviser         = 100 + cgst + sgst ;
        var amt             = (unit_total *  100)/(diviser);
        var full_gst        = unit_total - amt;
        var row_per_cgst    = full_gst/2;
        var row_per_sgst    = full_gst/2;


        jQuery(this).find('.sub_discount').val(unit_price.toFixed(2));
      
        unit_total   = (isNaN(unit_total) ? '0.00' : unit_total);

        jQuery(this).find('.td_subtotal').text(unit_total.toFixed(2)); 
        jQuery(this).find('.sub_total').val(unit_total.toFixed(2));

        jQuery(this).find('.td_amt').text(amt.toFixed(2)); 
        jQuery(this).find('.sub_amt').val(amt.toFixed(2));

        jQuery(this).find('.sub_cgst_value').val(row_per_cgst.toFixed(2));
        jQuery(this).find('.sub_sgst_value').val(row_per_sgst.toFixed(2));

        jQuery(this).find('.td_cgst_value').text(row_per_cgst.toFixed(2));
        jQuery(this).find('.td_sgst_value').text(row_per_sgst.toFixed(2));
        sub_tot = sub_tot + parseFloat(jQuery(this).find('.sub_total').val());


    //<---- Display Total before discount ------->
     var total = row_mrp * unit;

     before_total = total + before_total;

    });

    jQuery('.ws_total').val(before_total);
    jQuery('.ws_fsub_total').val(sub_tot);
    //jQuery('.ws_paid_amount').val(sub_tot);
    jQuery('.ws_paid_amount').trigger('change');
}





//<---- Return Goods --->

jQuery(document).ready(function (argument) {

    jQuery('#ws_billing_return #ws_return_submit').on('click', function() {
        var return_val = jQuery('.rtn_fsub_total').val();
        if(return_val > 0){


        var bill_update_url = ws_bill_return.ws_return_page;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'ws_create_return',
                data   : jQuery('#ws_billing_return :input').serialize(),
                year   : jQuery( ".year" ).val(),
    			search_inv_id : jQuery(".ws_return_inv_id").val(),
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
    jQuery('#ws_billing_return #ws_return_update').on('click', function() {
        var return_val = jQuery('.rtn_fsub_total').val();
        if(return_val > 0){

        var bill_update_url = bill_return_viewws.returnviewws;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'ws_update_return',
                data   : jQuery('#ws_billing_return :input').serialize(),
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
 //<------- Return invoice start---------->



  jQuery('.delete-ws-bill').live( "click", function() {
    if(confirm('Are you sure you want to cancel this Invoice?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=ws_billing_list&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Bill------->
  	//<-------Delete Return Bill------->

  jQuery('.delete-ws-return-bill').live( "click", function() {
    if(confirm('Are you sure you want to Delete this Goods Return Challan?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=ws_return_items_list&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Return Bill------->


});

function deleteWsDueCash(uniquename){
    jQuery('tr.ws_due_data').each(function(){
        jQuery(this).find('[ref-uniquename="'+uniquename+'"]').remove();
        var previous_paid = billBalancePaidIndividual(jQuery(this));
        jQuery(this).find('.ws_paid_due').val(previous_paid);

    });
    //individualBillPaidCalculation();
}

function ws_payment_calculation(){
    var total         = parseFloat(jQuery('.ws_fsub_total').val());
    var due             = parseFloat(jQuery('.ws_balance_amount_val').val());
    var total_payment   = due + total;
    var paid_tot = 0;
    jQuery('.ws_payment_table').each(function() {  
        var tot     = parseFloat(jQuery(this).find('.ws_payment_amount').val());
        tot         = isNaN(tot) ? 0 : tot ;
        paid_tot    = paid_tot + tot;       
    });
   

    var total_pay     = total_payment - paid_tot;
    var cur_pay       = total - paid_tot;
    cur_pay = (cur_pay >= 0)? cur_pay : 0 ;
    jQuery('.ws_pay_amount_cheque').val(cur_pay);
    jQuery('.ws_cod_amount').val(cur_pay);
    jQuery('.ws_paid_amount').val(paid_tot);
    jQuery('.ws_paid_amount').trigger('change');
    return total_pay;
}
function ws_duepaid_fun(customer_id = 0){
jQuery.ajax({
        type: "POST",
        dataType : "json",
        url: frontendajax.ajaxurl,
        data: {
            id                  : customer_id,
            action              :'DuePaid',
            type                : 'ws',
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