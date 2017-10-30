jQuery(document).ready(function (argument) {

jQuery('#ws_billing_customer').focus();


//Ws delivery check products
     jQuery('.ws_delivery_check').on('click',function(){
        var delivery_check = jQuery(this).parent().parent().find('.ws_delivery_check').is(":checked");
        if(delivery_check){
            var delivery = 1;
        } else {
            var delivery = 0;
        }
        var delivery_id = jQuery(this).parent().parent().find('.ws_delivery_id').val();
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action      :'ws_product_delivery',
                id          : delivery_id,
                delivery    : delivery,
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
    jQuery('#ws_billing_landline_mobile').live('keydown', function(e){ console.log(e);
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

      if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('#ws_billing_customer').focus();
      } 

    });
	
	 jQuery("#ws_update_payment").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('#ws_billing_customer').focus();
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
                nameValidite : true
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
                minlength: 10,
                maxlength: 10,
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
                addressValidate : "Please Enter Valid Address",
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
                maxlength: "Please Enter Valid  Number!",
            },
             ws_delivery_address: {
                addressValidate : "Please Enter Valid Address",
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
                        console.log("efklf");
                        console.log(data);
                      window.location = bill_update_url+'&id='+data.inv_id;

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
                        clearPopup();
                        popItUp('Success', 'Successfully Updated!');
                        jQuery('#lightbox').css('display','none');

                        window.location = bill_invoice_url+'&id='+ data.inv_id + '&year='+ data.year;

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
     jQuery( "#ws_billing_customer, #ws_billing_mobile" ).autocomplete ({
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
                    var field_val = item.customer_name;
                    var identification = 'name';
                } else {
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
                action  :'ws_customer_balance'
            },
              success: function (data) {
                
                jQuery('.ws_balance_amount').text(data);
                jQuery('.ws_balance_amount_val').val(data);
                ws_rowCalculate();


            }
        });
        jQuery('.ws_old_customer_id').val(ui.item.id);
        jQuery('#ws_billing_address').val(ui.item.address);

        if(ui.item.identification == 'mobile' ) {
            jQuery('#ws_billing_mobile').val(ui.item.value);
            jQuery('#ws_billing_customer').val(ui.item.name);
        } else {
            jQuery('#ws_billing_mobile').val(ui.item.mobile);
            jQuery('#ws_billing_customer').val(ui.item.value);
        }

        
        jQuery('#ws_billing_secondary_mobile').val(ui.item.secondary_mobile);
        jQuery('#ws_billing_landline_mobile').val(ui.item.landline);
        jQuery('#ws_billing_company').val(ui.item.company_name);
        jQuery('#ws_billing_gst').val(ui.item.gst);
        console.log(ui.item.address);
        

        jQuery('.ws_lot_id').focus();
        jQuery('.ws_paid_amount').trigger('change');



        console.log( "id: " + ui.item.id + " name " + ui.item.value + " address " + ui.item.address + "gst ");
      }
    });



     //<---- Generate Print and Download Page ---> 
    jQuery('.generate_bill_new').on('click',function() {
        var inv_id = jQuery('.invoice_id_new').val();
        var year = jQuery('.year').val();
        var datapass =   home_page.url+'invoice-download/?id='+inv_id+'&year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });

    jQuery('.ws_generate_bill').on('click',function() {
        console.log("fdsfdf");
        var inv_id = jQuery('.invoice_id').val();
        var year = jQuery('.year').val();
        var datapass =   home_page.url+'ws-download/?id='+inv_id+'&year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });

    //download page
    jQuery('.ws_return_generate_bill').on('click',function() {
        var id = jQuery('.invoice_id').val();
        var datapass =   home_page.url+'ws-goods-return-download/?id='+id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });



    jQuery('.ws_print_bill').on('click',function(){
        var inv_id = jQuery(this).parent().parent().find('.invoice_id').val();
        var year = jQuery(this).parent().parent().find('.year').val();
        var datapass =   home_page.url+'ws-invoice/?id='+inv_id+'&year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });


    jQuery('.ws_return_print').on('click',function() {
        var gr_id = jQuery(this).parent().parent().find('.gr_id').val();
        var datapass =   home_page.url+'ws-return/?id='+gr_id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });

    jQuery('.return_print').on('click',function() {
        var gr_id = jQuery(this).parent().parent().find('.gr_id').val();
        var datapass =   home_page.url+'return/?id='+gr_id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });

     //<---- End Generate Print and Download Page ---> 


//<--- Display Balance -----> 
    jQuery('.ws_paid_amount').on('change',function() {
        var prev_bal = parseFloat(jQuery('.ws_balance_amount_val').val());
        var current_bal = parseFloat(jQuery('.ws_fsub_total').val());
        var paid = parseFloat(jQuery('.ws_paid_amount').val());
        var bal = (prev_bal + current_bal) - paid;
        jQuery('.ws_return_amt').val(Math.ceil(bal));
        jQuery('.ws_return_amt_txt').text(Math.ceil(bal));

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
                    unit_price : item.selling_price,
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
        jQuery('.ws_unit_price').val(ui.item.unit_price);
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
                // jQuery('.ws_slab_sys_text').text(data);
                // jQuery('.ws_slab_id').text("1");
                //jQuery('.ws_slab_sys_txt').val(data);
                //jQuery('.ws_slab_pro').text(ui.item.product_name);

                
                var str = '<td class="td_id">'+'1'+'</td><td class="stock_prod">' + ui.item.product_name + '</td><td class="slap_stock">'+ data +'</td><input type="hidden" name="ws_slab_sys_txt" value="'+ data +'"  class="ws_slab_sys_txt"/>';                
                jQuery('.stock_table_body').html(str);

            }
        }); 



        console.log( "id: " + ui.item.id);
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
            alert('Enter Quantity as small as avalible stock!!!');
            jQuery('.unit').val(Math.ceil(stock));
        }
    });




    jQuery('.sub_unit').live('change keyup',function(){

        var unit = parseFloat(jQuery(this).parent().parent().find('.sub_unit').val());
        var stock = parseFloat(jQuery(this).parent().parent().find('.sub_stock').val());
        if( unit > stock){
            alert('Enter Quantity as small as avalible stock!!!');
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
        var hsn_code            = jQuery('.ws_hsn').val();
        var stock               = jQuery('.ws_slab_sys_txt').val();
        var price               = jQuery('.ws_unit_price').val();
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
                var str = '<tr data-randid='+makeid()+' data-productid='+product_id+' class="customer_table" ><td class="td_id">'+current_row+'</td> <input type="hidden" value="'+ product_id + '" name="customer_detail['+current_row+'][id]" class="sub_id" /><td class="td_product">' + product_name + '</td> <input type="hidden" value = "'+ product_name + '" name="customer_detail['+current_row+'][product]" class="sub_product"/><td class="td_hsn">' + hsn_code + '</td> <input type="hidden" value = "'+ hsn_code + '" name="customer_detail['+current_row+'][hsn]" class="sub_hsn"/><td class=""><input type="text" value = "'+ unit + '" name="customer_detail['+current_row+'][unit]" class="sub_unit"/> </td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][stock]" class="sub_stock"/><td class="td_price">' + price + '</td> <input type="hidden" value = "'+ price + '" name="customer_detail['+current_row+'][price]" class="sub_price"/> <td><input type="text" value ="'+discount +'" name="customer_detail['+current_row+'][discount]" class="sub_discount"/></td><input type="hidden" value ="each" name="customer_detail['+current_row+'][discount_type]" class="discount_type"/><td class="td_amt">' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][amt]" class="sub_amt"/><td class="td_cgst">' + cgst + '  %' + '</td> <input type="hidden" value = "'+ cgst + '" name="customer_detail['+current_row+'][cgst]" class="sub_cgst"/> <td class="td_cgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][cgst_value]" class="sub_cgst_value"/><td class="td_sgst">' + sgst + '  %' + '</td> <input type="hidden" value = "'+ sgst + '" name="customer_detail['+current_row+'][sgst]" class="sub_sgst"/><td class="td_sgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][sgst_value]" class="sub_sgst_value"/><td class="td_subtotal"></td> <input type="hidden" value ="" name="customer_detail['+current_row+'][subtotal]" class="sub_total"/><td><a href="#" class="sub_delete">Delete</a></td></tr>';                
                jQuery('#bill_lot_add').append(str);

                addFromProductControl();
                jQuery('.product_control_error').remove();
            }
            
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
            if(discount == '' || discount <= 0) {
                discount_selector.after('<div class="product_control_error control_error">Please Enter Valid Discounted Price!</div>');
                discount_selector.focus();
            }
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

    var discount =parseFloat(jQuery('.ws_discount').val());
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

        


    });

    jQuery('.ws_fsub_total').val(sub_tot.toFixed(2));
    jQuery('.ws_paid_amount').trigger('change');
}





//<---- Return Goods --->

jQuery(document).ready(function (argument) {


       jQuery('#ws_billing_return #ws_return_submit').on('click', function() {

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
    });

    //<------- Goods Return Items Update ---->
    jQuery('#ws_billing_return #ws_return_update').on('click', function() {

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
    });



 //<------- Return invoice start---------->
    jQuery('.ws_return_inv_id').focus();

 //<-----After keydown submit using tab goto first text box in Return billing--->
    jQuery("#ws_return_submit,#ws_return_update").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 

        if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.ws_return_inv_id').focus();
        } 

    });

	//<-------Delete Bill------->

 //<-----After keydown submit using tab goto first text box in Return billing--->
    jQuery(".bill_retail_print").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 

        if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.invoice_id').focus();
        } 

    });

       //<-----After keydown submit using tab goto first text box in Return billing--->
    jQuery('.ws_return_bill_submit').live('keydown', function(e) { 
        console.log("dd");

        if(jQuery(this).parent().parent().next('tr').length == 0) {
            console.log("kdowd");
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                jQuery('.ws_return_inv_id').focus();
            } 
        }
        else {
            jQuery(this).parent().parent().next('tr').focus();
        }
    });

    jQuery('#ws_return_submit').live('keydown', function(e) { 
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                // call custom function here
                jQuery('.ws_return_inv_id').focus()
            } 
    });



  jQuery('.delete-ws-bill').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=ws_billing_list&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Bill------->
  	//<-------Delete Return Bill------->

  jQuery('.delete-ws-return-bill').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=ws_return_items_list&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Return Bill------->


});

function wsReturn_rowCalculate() {
    var sub_tot=parseFloat(0);
   
    jQuery('.rtn_customer_tab').each(function() { 
        var unit_price          = parseFloat(jQuery(this).find('.rtn_sub_discount').val());
        var unit_count          = parseFloat(jQuery(this).find('.sub_rtn_qty').val());
        var cgst                = parseFloat(jQuery(this).find('.rtn_sub_cgst').val());
        var sgst                = parseFloat(jQuery(this).find('.rtn_sub_sgst').val());
        var unit_total          = (unit_price * unit_count);
        var row_per_cgst        = ( (cgst * unit_total) / 100 );
        var row_per_sgst        = ( (sgst * unit_total) / 100 );
        var amt = unit_total - (row_per_cgst + row_per_sgst); 
        unit_total               = (isNaN(unit_total) ? '0.00' : unit_total);

        jQuery(this).find('.td_rtn_subtotal').text(unit_total); 
        jQuery(this).find('.rtn_sub_total').val(unit_total);

        jQuery(this).find('.td_rtn_amt').text(amt); 
        jQuery(this).find('.rtn_sub_amt').val(amt);

        jQuery(this).find('.rtn_sub_cgst_value').val(row_per_cgst);
        jQuery(this).find('.rtn_sub_sgst_value').val(row_per_sgst);

        jQuery(this).find('.td_rtn_cgst_value').text(row_per_cgst);
        jQuery(this).find('.td_rtn_sgst_value').text(row_per_sgst);
        sub_tot = sub_tot + parseFloat(jQuery(this).find('.rtn_sub_total').val());
         
    });
        jQuery('.ws_rtn_fsub_total').val(sub_tot);
        jQuery('.ws_paid_amount').trigger('change');
   

}


//jQuery('form')[0].checkValidity()