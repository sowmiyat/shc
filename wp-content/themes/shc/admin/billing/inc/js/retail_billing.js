 jQuery(document).ready(function (argument) {

    //** Customer Select billing **//
jQuery('#billing_customer').focus();
    // populateReturnProductsRetail('.rtn_lot_id');
//delivery check products
     jQuery('.delivery_check').on('click',function(){
        var delivery_check = jQuery(this).parent().parent().find('.delivery_check').is(":checked");
        if(delivery_check){
            var delivery = 1;
        } else {
            var delivery = 0;
        }
        var delivery_id = jQuery(this).parent().parent().find('.delivery_id').val();
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action      :'product_delivery',
                id          : delivery_id,
                delivery    : delivery,
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
        alert('Enter Quantity as small as avalible stock!!!');
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

      if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#billing_customer').focus();
      } 

    });


 //<-------- search customer using name and mobile------>   
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
                rowCalculate();

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


//<----- Payment type JS------>
  jQuery('.payment_pay_type').on('click',function(){
    if(jQuery('.payment_pay_type:checked').val() == 'internet_banking'){
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
                var str = '<tr data-randid='+makeid()+' data-productid='+product_id+' class="customer_table_retail" ><td class="td_id">'+current_row+'</td> <input type="hidden" value="'+ product_id + '" name="customer_detail['+current_row+'][id]" class="sub_id" /><td class="td_product">' + product_name + '</td> <input type="hidden" value = "'+ product_name + '" name="customer_detail['+current_row+'][product]" class="sub_product"/><td class="td_hsn">' + hsn_code + '</td> <input type="hidden" value = "'+ hsn_code + '" name="customer_detail['+current_row+'][hsn]" class="sub_hsn"/><td class=""><input type="text" value = "'+ unit + '" name="customer_detail['+current_row+'][unit]" class="retail_sub_unit"/> </td>  <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][stock]" class="retail_sub_stock"/><td class="td_price">' + price + '</td> <input type="hidden" value = "'+ price + '" name="customer_detail['+current_row+'][price]" class="sub_price"/> <td><input type="text" value ="'+ discount +'" name="customer_detail['+current_row+'][discount]" class="retail_sub_discount"/></td><input type="hidden" value ="each" name="customer_detail['+current_row+'][discount_type]" class="discount_type"/><td class="td_amt">' + stock + '</td> <input type="hidden" value = "'+ stock + '" name="customer_detail['+current_row+'][amt]" class="sub_amt"/><td class="td_cgst">' + cgst + '  %' + '</td> <input type="hidden" value = "'+ cgst + '" name="customer_detail['+current_row+'][cgst]" class="sub_cgst"/> <td class="td_cgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][cgst_value]" class="sub_cgst_value"/><td class="td_sgst">' + sgst + '  %' + '</td> <input type="hidden" value = "'+ sgst + '" name="customer_detail['+current_row+'][sgst]" class="sub_sgst"/><td class="td_sgst_value"></td> <input type="hidden" value = "" name="customer_detail['+current_row+'][sgst_value]" class="sub_sgst_value"/><td class="td_subtotal"></td> <input type="hidden" value ="" name="customer_detail['+current_row+'][subtotal]" class="sub_total"/><td><a  href="#" class="retail_sub_delete">Delete</a></td></tr>';                
                jQuery('#bill_lot_add_retail').append(str);

                addFromProductControlRetail();
                jQuery('.product_control_error_retail').remove();
            }
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
            if(discount == '' || discount <= 0) {
                discount_selector.after('<div class="product_control_error_retail control_error">Please Enter Valid Discounted Price!</div>');
                discount_selector.focus();
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
            delivery_name: {
                nameValidite : true,
            },
            delivery_phone: {
                minlength: 10,
                maxlength: 10,
            },
            delivery_address : {
                addressValidate : true,
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
                addressValidate : "Please Enter Valid Address",
            },
            delivery_name : {
                nameValidite: "Special Characters Not Allowed!",
            },
            delivery_phone : {
                minlength: "Please Enter Valid  Number!",
                maxlength: "Please Enter Valid  Number!",
            },
             delivery_address: {
                addressValidate : "Please Enter Valid Address",
            },

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

                        window.location = bill_invoice_url+'&id='+data.inv_id + '&year='+ data.year;

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
    jQuery('.return_inv_id').focus();
    jQuery('.invoice_id').focus();

 //<-----After keydown submit using tab goto first text box in Return billing--->
    jQuery("#return_submit,#return_update").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 

        if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.return_inv_id').focus();
        } 

    });

	
	//<-------Delete Bill------->

  jQuery('.delete-bill').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=billing_list&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Bill------->
  	//<-------Delete Return Bill------->

  jQuery('.delete-return-bill').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
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
        var bal_qty =parseFloat(jQuery(this).parent().parent().find('.return_bal_qty').val());
        var return_qty = parseFloat(jQuery(this).parent().parent().find('.return_qty_ret').val());

        if(  bal_qty < return_qty ){
            alert('Please Enter Return quantity as below as avalible quantity!!!');
            jQuery(this).parent().parent().find('.return_qty_ret').val('1');
            var balance = bal_qty - 1;
        } else {
            var balance = bal_qty - return_qty;
        }
        
        jQuery(this).parent().parent().find('.return_bal_qty_td').text(balance);
        jQuery(this).parent().parent().find('.return_bal').val(balance);
        Return_rowCalculate();
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



    jQuery('.print_bill').on('click',function() {
        var inv_id = jQuery(this).parent().parent().find('.invoice_id').val();
        var year = jQuery(this).parent().parent().find('.year').val();
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

//download page
    jQuery('.return_generate_bill').on('click',function(){
        var id = jQuery('.invoice_id').val();
        var datapass =   home_page.url+'goods-return-download/?id='+id;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Invoice","" );
       
    });


  //<------- Goods Return Items ---->
    jQuery('#billing_return #return_submit').on('click', function() {

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
    });

    //<------- Goods Return Items Update ---->
    jQuery('#billing_return #return_update').on('click', function() {

        var bill_update_url = bill_return_view.returnview;
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : 'update_return',
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
        });
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
         
    });
        jQuery('.rtn_fsub_total').val(sub_tot);

}
function addFromProductControlRetail() {
    jQuery('.lot_id').val('');
    jQuery('.retail_lot_id_orig').val('');
    jQuery('.retail_unit').val('');
    jQuery('.retail_discount').val('');
    jQuery('#lot_id').focus();
}
