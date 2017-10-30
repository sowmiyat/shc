jQuery(document).ready(function () {

    jQuery('#pro_number').focus();

    jQuery(".stock_cancel").on('keydown',  function(e) { 
        var keyCode = e.keyCode || e.which; 

        if (keyCode == 9) { 
            e.preventDefault(); 
            jQuery('#pro_number').focus();
        } 
    }); 

    //<-------- Select Product----->   
jQuery( "#pro_number" ).autocomplete ({
      source: function( request, response ) {

        jQuery.ajax( {
          url: frontendajax.ajaxurl,
          type: 'POST',
          dataType: "json",
          data: {
            action: 'search_lot',
            search_key: request.term
          },
          success: function( data ) {
            response(jQuery.map( data.result, function( item ) {

                return {
                    id              : item.id,
                    value           : item.product_name,
                    selling_price   : item.selling_price,
                    brand_name      : item.brand_name,

                }
            }));
          }
        });
      },
      minLength: 2,
      select: function( event, ui ) {

       jQuery('#brand_name').val(ui.item.brand_name);
        jQuery('.lot_number').val(ui.item.id);
        jQuery('#product_name').val(ui.item.value);
        jQuery('#unit_price').val(ui.item.selling_price);
        jQuery('#selling_price').val(ui.item.selling_price);

        jQuery('#stock_count').focus();




        
      }
    });                                               
//<------- Validation Function --------> 
   
 jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

    jQuery( ".stock_validation" ).validate({
        rules: {
            pro_number: {
                required: true,
                productCheck : true,
            },
            stock_count : {
                required: true,
                stockCheck : true,
                maxlength: 10,
            },
           
        },
        messages: {
            pro_number: {
                required: 'Please Enter Product Name!',
                productCheck: "Select Products! New Products are not allowed Here!!!",
            },
            stock_count : {
                required: 'Please Enter Stock Count!',
                stockCheck : 'Please Enter Stock!',
                maxlength : 'This Field Allowed Maximum 10 digits!!'
            },
          
        }
    });

    var response = true;
    jQuery.validator.addMethod(
          "productCheck", 
          function(value, element) {
              jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action          : 'productCheck',
                    productname     : value,
                },
                success: function (msg) {
                    if( msg === 1 ) {
                        response = false;
                    } else {
                        response =  true;
                    }
                }
            });
              return response;
          }
      );
    jQuery.validator.addMethod(
        "stockCheck", 
        function(value, element) {
            if(value > 0){
                return true;
            }
            else {
                return false;
            }
        }
    );


    /*Add stock Form Submit*/
    jQuery("#add_stock").bind('submit', function (e) {

        var valid = jQuery(".stock_validation").valid();
        if( valid ) { 
		jQuery('.submit_form').css('display','none');
            /* jQuery('#lightbox').css('display','block'); */
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action : jQuery('.stock_action').val(),
                    data : jQuery('#add_stock :input').serialize()
                },
                success: function (data) {
                   /*  clearPopup();
                    jQuery('#lightbox').css('display','none');

                    if(data.redirect != 0) { 
                        setTimeout(function() {
                            managePopupContent(data);
                        }, 1000);
                    }

                    if(data.success == 0) {
                        popItUp('Error', data.msg);
                    } else {
                        popItUp('Success', data.msg);
                    } */
                
                }
            });
        }
        e.preventDefault();
        return false;
    });

    jQuery('.stock_count').on('change',function() {

        var change_count = jQuery('.stock_count').val();
        var selling = jQuery('#unit_price').val();
        var stock_count = selling * change_count;
        jQuery('#selling_price').val(stock_count);



    });

//<-------Delete Stock------->

  jQuery('.delete-stock').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=list_stocks&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Stock------->


});


