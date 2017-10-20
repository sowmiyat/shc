jQuery(document).ready(function (argument) {

jQuery('#ws_billing_customer').focus();

    jQuery('.year').datepicker({
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy',
        onClose: function(dateText, inst) { 
            var year = jQuery("#ui-datepicker-div .ui-datepicker-year :selected").val();
            jQuery(this).datepicker('setDate', new Date(year, 1));
        }
    });
    jQuery(".year").focus(function () {
        jQuery(".ui-datepicker-month").hide();
    });

    jQuery("#ws_billing_customer").on('change',function(){
        var alphanumers = /^[a-zA-Z0-9]+$/;
        if(!alphanumers.test(jQuery("#ws_billing_customer").val())){
            alert("name can have only alphabets and numbers.");
            jQuery("#ws_billing_customer").val('');
        }

    });

    jQuery("#ws_billing_address").on('change',function(){
        var alphanumers = /^[a-zA-Z0-9]+$/;
        if(!alphanumers.test(jQuery("#ws_billing_address").val())){
            alert("address can have only alphabets and numbers.");
            jQuery("#ws_billing_address").val('');
        }

    });

     jQuery("#ws_billing_company").on('change',function(){
        var alphanumers = /^[a-zA-Z0-9]+$/;
        if(!alphanumers.test(jQuery("#ws_billing_company").val())){
            alert("Company name can have only alphabets and numbers.");
            jQuery("#ws_billing_company").val('');
        }

    });


//<-------- Mobile Number Check With Database --->
    jQuery('.mobile_check_wholesale').live('change',function() {
       jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action       : 'check_unique_mobile_wholesale_bill',
                mobile       : jQuery('.mobile_check_wholesale').val()
            },
            success: function (data) {
               if(data != 0){
                alert('Mobile Number Already Exists!!!');
                jQuery('.mobile_check_wholesale').val('').focus();

            }
            
            }
        });
    

    });
//<-------- End Mobile Number Check With Database --->


//<-------- Popup Display for Home Delivery Click  --->
    jQuery('.home_delivery').on('change',function() {
        var delivery = jQuery(".home_delivery:checked").val(); 
        if(delivery == 'yes') {
            jQuery('.ws_delivery_name').css('display','block');
            jQuery('.ws_delivery_phone').css('display','block');
            jQuery('.ws_delivery_address').css('display','block');


        }
        else {
            jQuery('.ws_delivery_name').css('display','none');
            jQuery('.ws_delivery_phone').css('display','none');
            jQuery('.ws_delivery_address').css('display','none');
        }

    });
//<-------- End  Popup Display for Home Delivery Click  --->

      /*WS Submit Payment*/
    jQuery('#ws_billing_container #ws_submit_payment').on('click', function() {

        var existing_count = parseInt( jQuery('#bill_lot_add tr').length );
        if(existing_count != 0 ){

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
            alert('Please Add Atleast One Product!!! Empty Bill Can'+" ' "+'t Submit');
        }
    });

   
    /*WS Update Payment*/
    jQuery('#ws_billing_container #ws_update_payment').on('click', function() {

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
        
    });

//<------- Goods Return Items ---->
    jQuery('#ws_billing_return #ws_return_submit').on('click', function() {

        var bill_update_url = bill_return_viewws.returnviewws;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'ws_create_return',
                data   : jQuery('#ws_billing_return :input').serialize()
            },
            success: function (data) {
                console.log(data);
                clearPopup();
                popItUp('Success', 'Bill Created!');
                jQuery('#lightbox').css('display','none');

              window.location = bill_update_url+'&id='+data.id;

            }
        });
    });

     /*Update Wholesale Return Payment*/

    jQuery('#ws_return_billing_container #ws_return_update_payment').on('click', function(){
      

        var bill_invoice_url = bill_return_listws.return_itemsws;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'ws_return_order',
                data : jQuery('#ws_return_billing_container :input').serialize()
            },
            success: function (data) {
                clearPopup();
                popItUp('Success', 'Successfully Updated!');
                jQuery('#lightbox').css('display','none');

                window.location = bill_invoice_url+'&id='+data.invoice_id;

            }
        });
        
    });


     jQuery( "#ws_billing_customer" ).autocomplete ({
      source: function( request, response ) {
        jQuery.ajax( {
          url: frontendajax.ajaxurl,
          type: 'POST',
          dataType: "json",
          data: {
            action: 'get_ws_customer_name',
            search_key: request.term
          },
          success: function( data ) {
            response(jQuery.map( data.result, function( item ) {
                return {
                    id: item.id,
                    value: item.customer_name,
                    address : item.address,
                    phone : item.mobile,
                    company_name : item.company_name,
                    gst : item.gst_number
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
               

            }
        });
        jQuery('.ws_old_customer_id').val(ui.item.id);
        jQuery('#ws_billing_address').val(ui.item.address);
        jQuery('#ws_billing_mobile').val(ui.item.phone);
        jQuery('#ws_billing_customer').val(ui.item.value);
        jQuery('#ws_billing_company').val(ui.item.company_name);
        jQuery('#ws_billing_gst').val(ui.item.gst);
        console.log(ui.item.address);
        

        jQuery('.ws_lot_id').focus();
        jQuery('.ws_paid_amount').trigger('change');



        console.log( "id: " + ui.item.id + " name " + ui.item.value + " address " + ui.item.address + "gst ");
      }
    });



// //** Wholesale Customer Select billing **//    
//   jQuery('#ws_billing_customer').select2({
//         allowClear: true,
//         width: '50%',
//         multiple: true,
//         minimumInputLength: 1,
//         maximumSelectionLength: 1,
//         ajax: {
//             type: 'POST',
//             url: frontendajax.ajaxurl,
//             delay: 250,
//             dataType: 'json',
//             data: function(params) {
//                 return {
//                     action: 'get_ws_customer_name', //search term
//                     page: 1,
//                     search_key: params.term,
//                 };
//             },

//             processResults: function(data) {
//                 var results = [];
//                 return {
//                     results: jQuery.map(data.result, function(obj) {
//                         return { id: obj.id, customer_name: obj.customer_name, address: obj.address, mobile: obj.mobile, company_name:obj.company_name };
//                     })
//                 };
//             },
//             cache: true
//         },
//         initSelection: function (element, callback) {
//             callback({ id: jQuery(element).val(), mobile: jQuery(element).find(':selected').text() });
//         },
//         templateResult: formatCustomerNameResult,
//         templateSelection: formatCustomerName
//     }).on("select2:select", function (e) {
//         jQuery.ajax({
//             type: "POST",
//             dataType : "json",
//             url: frontendajax.ajaxurl,
//             data: {
//                 id      : e.params.data.id,
//                 action  :'ws_customer_balance'
//             },
//               success: function (data) {
                
//                 jQuery('.ws_balance_amount').text(data);
//                 jQuery('.ws_balance_amount_val').val(data);
               

//             }
//         });
//         jQuery('.ws_old_customer_id').val(e.params.data.id);
//         jQuery('.ws_address1').text('Address : '+e.params.data.address);
//         jQuery('.ws_customer_name').text('Name : '+e.params.data.customer_name);
//         jQuery('.ws_customer_company').text('Company name : '+e.params.data.company_name);
//         jQuery('.ws_bill_submit').css('display', 'block');

//         jQuery('.ws_lot_id').focus();
//         jQuery('.ws_paid_amount').trigger('change');
//     });

//      //<----- End Wholesale Customer Select billing --->

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



    jQuery('.ws_print_bill').on('click',function(){
        var inv_id = jQuery('.invoice_id').val();
        var year = jQuery('.year').val();
        var datapass =   home_page.url+'ws-invoice/?id='+inv_id+'&year='+year;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });


    jQuery('.ws_return_print').on('click',function() {
        var gr_id = jQuery('.gr_id').val();
        var datapass =   home_page.url+'ws-return/?id='+gr_id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });

    jQuery('.return_print').on('click',function() {
        var gr_id = jQuery('.gr_id').val();
        var datapass =   home_page.url+'return/?id='+gr_id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });

     //<---- End Generate Print and Download Page ---> 

     //<---- New and Old Customer Link In billing --->

    jQuery('.ws_new_user_bill').on('click', function() {
        jQuery('.ws_new_user_bill,.ws_billing_customer_div').css('display', 'none');
        jQuery('.ws_old_user_bill,.ws_new_customer').css('display', 'block');
        jQuery('.ws_user_type').val('new'); 
        jQuery('.ws_popup-add-customer').trigger('click');

    });

    jQuery('.ws_old_user_bill').on('click', function() {
        jQuery('.ws_new_user_bill,.ws_billing_customer_div').css('display', 'block');
        jQuery('.ws_old_user_bill,.ws_new_customer').css('display', 'none');
       jQuery('.ws_user_type').val('old');
    });
 //<---- End New and Old Customer Link In billing --->

//<----- Pop up For Customers and home delivery--->

    jQuery('.popup-add-customer').live('click', function() {
        create_popup('get_customer_create_form_popup', 'Add New Customer');
    });


    jQuery('.ws_popup-add-customer').live('click', function() {
        create_popup('ws_get_customer_create_form_popup', 'Add New Customer');
    });



    jQuery('.popup-add-homedelivery').live('click', function() {
        create_popup('get_homedelivery_create_form_popup', 'Add Home Delivery Address');
    });

    jQuery('a.customer_edit').live('click', function(e) {
        e.preventDefault();
        jQuery('#src_info_box').bPopup();
    });

//<----- End Pop up For Customers and home delivery--->
//<--- Display Balance -----> 
    jQuery('.ws_paid_amount').on('change',function(){
        var prev_bal = parseFloat(jQuery('.ws_balance_amount_val').val());
        var current_bal = parseFloat(jQuery('.ws_fsub_total').val());
        var paid = parseFloat(jQuery('.ws_paid_amount').val());
        var bal = (prev_bal + current_bal) - paid;
        jQuery('.ws_return_amt').val(bal);
        jQuery('.ws_return_amt_txt').text(bal);

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

function formatCustomerName (state) {
  if (!state.id) {
    return state.id;
  }

  var $state = jQuery(
    '<span>' +
      state.mobile +
    '</span>'
  );
  return $state;
};

function formatCustomerNameResult(data) {
  if (!data.id) { // adjust for custom placeholder values
    return 'Searching ...';
  }
  var $state = jQuery(
    '<span>Name : ' +
      data.customer_name +
    '</span>' +
    '<br><span> Mobile : ' +
      data.mobile +
    '</span>'
  );
  return $state;
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
                jQuery('.ws_slab_sys_txt').val(data);

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

        jQuery('.sub_unit').live('change',function(){
            var unit = jQuery(this).parent().parent().find('.sub_unit').val();
            if(unit <= '0'){
                alert("please enter unit!!!");
                jQuery(this).parent().parent().find('.sub_unit').focus();
                jQuery(this).parent().parent().find('.sub_unit').val('1');
            }
            
                 ws_rowCalculate();
           
            
        });

         jQuery('.sub_discount').live('change',function(){
            var unit = jQuery(this).parent().parent().find('.sub_discount').val();

            if(unit <= '0'){
                alert("please enter price!!!");
                jQuery(this).parent().parent().find('.sub_discount').focus();
                jQuery(this).parent().parent().find('.sub_discount').val('1');
            }
            
                 ws_rowCalculate();
           
            
        });
    jQuery('.ws_discount').live('change',function(){

        ws_rowCalculate();
    });
    //  jQuery('.sub_discount').live('click',function(){
    //    jQuery(this).parent().parent().find('.sub_discount').css('background-color','#f1ad76');
        
    // });

    //   jQuery('.sub_unit').live('click',function(){
    //    jQuery(this).parent().parent().find('.sub_unit').css('background-color','#f1ad76');
        
    // });

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

       if( !!product_id && unit !='0' &&  unit != '' && discount != '0.00' &&  discount != '' && discount != '0') {
            var existing_count = parseInt( jQuery('#bill_lot_add tr').length );
            var current_row = existing_count + 1;
            if( jQuery('.customer_table[data-productid="'+ product_id +'"]').length != 0 ) {
                var selector = jQuery('.customer_table[data-productid='+ product_id +']');
                var actual_unit = selector.find('.sub_unit').val();
                var final_unit = parseFloat(unit) + parseFloat(actual_unit);
                selector.find('.sub_unit').val(final_unit);

                
            } else {
                var str = '<tr data-randid='+makeid()+' data-productid='+product_id+' class="customer_table" ><td class="td_id">'+current_row+'</td> <input type="hidden" value="'+ product_id + '" name="customer_detail['+current_row+'][id]" class="sub_id" /><td class="td_product">' + product_name + '</td> <input type="hidden" value = "'+ product_name + '" name="customer_detail['+current_row+'][product]" class="sub_product"/><td class="td_hsn">' + hsn_code + '</td> <input type="hidden" value = "'+ hsn_code + '" name="customer_detail['+current_row+'][hsn]" class="sub_hsn"/><td class=""><input type="text" value = "'+ unit + '" name="customer_detail['+current_row+'][unit]" class="sub_unit"/> </td> <td class="td_stock">' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][stock]" class="sub_stock"/><td class="td_price">' + price + '</td> <input type="hidden" value = "'+ price + '" name="customer_detail['+current_row+'][price]" class="sub_price"/> <td><input type="text" value ="'+discount +'" name="customer_detail['+current_row+'][discount]" class="sub_discount"/></td><input type="hidden" value ="each" name="customer_detail['+current_row+'][discount_type]" class="discount_type"/><td class="td_amt">' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][amt]" class="sub_amt"/><td class="td_cgst">' + cgst + '  %' + '</td> <input type="hidden" value = "'+ cgst + '" name="customer_detail['+current_row+'][cgst]" class="sub_cgst"/> <td class="td_cgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][cgst_value]" class="sub_cgst_value"/><td class="td_sgst">' + sgst + '  %' + '</td> <input type="hidden" value = "'+ sgst + '" name="customer_detail['+current_row+'][sgst]" class="sub_sgst"/><td class="td_sgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][sgst_value]" class="sub_sgst_value"/><td class="td_subtotal"></td> <input type="hidden" value ="" name="customer_detail['+current_row+'][subtotal]" class="sub_total"/><td><span class="sub_delete">Delete</span></td></tr>';                
                jQuery('#bill_lot_add').append(str);

                    jQuery('.ws_lot_id').val('');
                    jQuery('.ws_lot_id').text('');

                    jQuery('.unit').val('0');
                    jQuery('.discount').val('0.00');
            }
            // jQuery('.ws_bill_submit').css('display','block');
             ws_rowCalculate();
           
        } 
        else if(product_id == ''){
            alert_popup('Select Product !!!');
        }
        else if ( unit == '0' & unit == ''){
            console.log(discount);
            alert_popup('Enter Unit !!!');
        }
        else {
          
            alert_popup('Enter Discounted Price !!!');
        }

      
    
    });

    jQuery('.sub_delete').live('click',function(){
       jQuery(this).parent().parent().remove();
        
    });

});


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
    var row_sub         = parseFloat('0.00');
    var row_discount    = parseFloat(jQuery(this).find('.sub_discount').val());
    var row_mrp         = parseFloat(jQuery(this).find('.sub_price').val());
    unit                = parseFloat(jQuery(this).find('.sub_unit').val());
    if( row_discount == row_mrp  || jQuery(this).find('.discount_type').val() == 'whole' ) {
         var sub_tot1    = (parseFloat(jQuery(this).find('.sub_price').val()) * parseFloat(jQuery(this).find('.sub_unit').val()));
             
            count               = parseFloat(discount / existing_count);
            sub                 = (sub_tot1 *  count)/100;
            var row_dis_new     = (sub / unit);
            var unit_price      = row_mrp - row_dis_new;
        
        jQuery(this).find('.discount_type').val('whole');
       
    }

    else {
        var unit_price    = row_discount;

    }
        jQuery(this).find('.sub_discount').val(unit_price);
        var unit_price          = parseFloat(jQuery(this).find('.sub_discount').val());
        var unit_count          = parseFloat(jQuery(this).find('.sub_unit').val());
        var cgst                = parseFloat(jQuery(this).find('.sub_cgst').val());
        var sgst                = parseFloat(jQuery(this).find('.sub_sgst').val());
        var unit_total          = (unit_price * unit_count);
        var row_per_cgst        = ( (cgst * unit_total) / 100 );
        var row_per_sgst        = ( (sgst * unit_total) / 100 );
        var amt = unit_total - (row_per_cgst + row_per_sgst); 
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

    jQuery('.ws_fsub_total').val(sub_tot.toFixed(2));
    jQuery('.ws_paid_amount').trigger('change');
}


function gstGrouping() {

    var percentages = jQuery('.sub_cgst').map(function () {
        return this.value; // $(this).val()
    }).get();


    var arr = [];
    var sum = [];

    jQuery.each( percentages, function(index, value){

      var myname = value;
      if( jQuery.inArray( myname, arr ) < 0 ) {
         arr.push(myname);
         sum[myname] =  { 
                            'cgst' : parseFloat(jQuery('[value="'+value+'"].sub_cgst').parent().find('.sub_cgst_value').val()), 
                            'sgst' :  parseFloat(jQuery('[value="'+value+'"].sub_cgst').parent().find('.sub_cgst_value').val())
                        };
    
      } else {
      
         sum[myname] = {
            'cgst' : sum[myname]['cgst'] + parseFloat(jQuery('[value="5.00"].sub_cgst').parent().find('.sub_cgst_value').val()), 
            'sgst' : sum[myname]['sgst'] + parseFloat(jQuery('[value="5.00"].sub_cgst').parent().find('.sub_cgst_value').val()) 
        };
      }

    });

/*
    jQuery('.sub_cgst').each(function(){
        var myname = this.value;
    })*/





console.log(sum);

    // if(selector.find('.sub_cgst').val() == '0.00') {    

    //     jQuery(".zero").css("display", "inline-block");
    //     jQuery('.amt_zero').text(selector.find('.sub_amt').val());
    //     jQuery('.cgst_val_zero').text(selector.find('.sub_cgst_value').val());
    //     jQuery('.sgst_val_zero').text(selector.find('.sub_sgst_value').val());

    // }

    // if(jQuery('.sub_cgst') == '5.00' ) {
    //      jQuery(".five").css("display", "block");
    // }
    // if(jQuery('.sub_cgst') == '12.00' ) {
    //      jQuery(".twelve").css("display", "block");
    // }
    // if(jQuery('.sub_cgst') == '18.00' ) {
    //      jQuery(".eighteen").css("display", "block");

    // } if(jQuery('.sub_cgst') == '28.00' ) {
    //      jQuery(".twentyeight").css("display", "block");
    // }




}


//<---- Return Goods --->

jQuery(document).ready(function (argument) {

     populateReturnProducts('.rtn_ws_lot_id');

    jQuery('.return_qty').on('change',function() {
        var unit                = parseFloat(jQuery('.qty_hidden').val());
        var return_qty          = parseFloat(jQuery('.return_qty').val());
        if( unit >= return_qty){
            var bal_qty = unit - return_qty;
            jQuery('.qty').val(bal_qty);
        }
        else {
            alert('Please Enter Return quantity as small as Quantity!!!');
            jQuery('.return_qty').val('0');
            jQuery('.qty').val(unit);
        }

    });



    jQuery('.add-button-return').live('click',function() {

        var product_id          = jQuery('.rtn_ws_lot_id').val();
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


    jQuery('#ws_return_billing_container #ws_return_update_payment').on('click', function(){
      

        var bill_invoice_url = bill_return_listws.return_itemsws;

        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'ws_return_order',
                data : jQuery('#ws_return_billing_container :input').serialize()
            },
            success: function (data) {
                clearPopup();
                popItUp('Success', 'Successfully Updated!');
                jQuery('#lightbox').css('display','none');

                window.location = bill_invoice_url+'&id='+data.invoice_id;

            }
        });
        
    });


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


function populateReturnProducts(selector) {

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
                    action: 'get_return_lot_data', // search term
                    page: 1,
                    search_key: params.term,
                    inv_id  : jQuery('.return_invoice_id').val()
                   
                };
            },

            processResults: function(data) {
                var results = [];

                return {
                    results: jQuery.map(data.items, function(obj) {
                        return { id: obj.lot_id, lot_no:obj.lot_no,sale_unit:obj.sale_unit, brand_name: obj.brand_name, product_name:obj.product_name,hsn:obj.hsn, bal_qty:obj.bal_qty, unit_price:obj.selling_price, cgst:obj.cgst,sgst:obj.sgst, discount:obj.discount };
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

        if( (e.params.data.bal_qty) == 0 ){
            alert("This Product is Already Fully Return.Please Select Correct Product");
        } else {

       
        jQuery('.rtn_ws_lot_id').val(e.params.data.id);  
        jQuery('.rtn_ws_product').val(e.params.data.product_name);  
        jQuery('.rtn_ws_unit_price').val(e.params.data.unit_price);
        jQuery('.rtn_discount').val(e.params.data.unit_price);
        jQuery('.rtn_ws_hsn').val(e.params.data.hsn);
        jQuery('.rtn_cgst_percentage').val(e.params.data.cgst);
        jQuery('.rtn_sgst_percentage').val(e.params.data.sgst);
        jQuery('.rtn_discount_amt').val(e.params.data.discount);
        jQuery('.qty').val(e.params.data.bal_qty); 
        jQuery('.qty_hidden').val(e.params.data.sale_unit); 

         }
    });

   
}

function delivery_create_submit_popup(action = '', length = 0) {
    jQuery.ajax({
        dataType : "json",
        type: "POST",
        url: frontendajax.ajaxurl,
        data: {
            key         : 'post_popup_content',
            data        : jQuery("#add_delivery").serialize(),
            length      : length,
            action      : action
        },
        success: function (data) {
                jQuery('.delivery_id').val(data.id);
                jQuery('.ws_delivery_name').text(data.delivery_name);
                jQuery('.ws_delivery_phone').text(data.delivery_mobile);
                jQuery('.ws_delivery_address').text(data.delivery_address);
                jQuery('.list_customers').html(data);
                clear_main_popup();
                jQuery('#src_info_box').bPopup().close();
                alert_popup('<span class="success_msg">Delivery Added Successfully!</span>', 'Success');
                

        }
    });
}

//jQuery('form')[0].checkValidity()