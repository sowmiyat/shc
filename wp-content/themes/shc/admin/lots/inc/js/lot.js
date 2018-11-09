jQuery(document).ready(function () {
 var b_name = jQuery('#brand_name').val();

  jQuery('#brand_name').focus().val('').val(b_name);

  jQuery(".reset_button").on('keydown',  function(e) { 
    var keyCode = e.keyCode || e.which; 
    if(event.shiftKey && event.keyCode == 9) {  
       e.preventDefault(); 
      jQuery('.cancel_button').focus();
    }
    else if (event.keyCode == 9) { 
      e.preventDefault(); 
      jQuery('#brand_name').select();
    } 
    else {
      jQuery('.reset_button').focus();
    }

  });
  jQuery("#brand_name").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
      if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.reset_button').focus();
      }
      else if (event.keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#product_name').select();
      } 
      else {
        jQuery('#brand_name').focus();
      }

    });


 jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

    jQuery( ".lot_submit" ).validate({
        rules: {
            brand_name: {
                required: true,
                nameValidite : true,
            },
            product_name : {
                required: true,
                nameValidite : true,
                uniqueProducts : true,

            },

            mrp : {
              required: true,
            },
            selling_price: {
                 required: true,
            },
            wholesale_price: {
                 required: true,
            },
            purchase_price: {
                 required: true,
            },
            hsn : {
                 required: true,
            },
            stock_alert : {
                required: true,
            }
        },
        messages: {
            brand_name: {
                required: 'Please Enter Brand Name!',
                nameValidite: "Special Characters Not Allowed!",
            },
            product_name : {
                required: 'Please Enter Product Name!',
                nameValidite : "Special Characters Not Allowed!",
                uniqueProducts : "Product name Already Exists !!!",
            },
            mrp: {
              required: "Please Enter MRP!",
            },
           
            selling_price: {
                required: "Please Enter Selling Price!",
            },
            wholesale_price: {
                required: "Please Enter Whole Sale Price!",
            },
             purchase_price : {
                 required: "Please Enter Purchase Price!",
            },
            hsn : {
                required: "Please Enter HSN code!",
            },
            stock_alert : {
                required: "Please Stock Alert Count!",
            }
            
        }
    });

    var response = true;
    jQuery.validator.addMethod(
          "uniqueProducts", 
          function(value, element) {
              jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action          : 'check_unique_product',
                    productname     : value,
                    brandname       : jQuery('.unique_brand').val(),
                    id              : jQuery('.lot_no').val(),
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
 



    /*Add Lot Form Submit*/
    jQuery("#create_lot").bind('submit', function (e) {
        var valid = jQuery(".lot_submit").valid();
        var prevent = jQuery(".form_submit_prevent_lot").val();
       if( valid && prevent == "off") {
          jQuery(".form_submit_prevent_lot").val('on');
          jQuery('#lightbox').css('display','block');
          jQuery.ajax({
              type: "POST",
              dataType : "json",
              url: frontendajax.ajaxurl,
              data: {
                  action : jQuery('.lot_action').val(),
                  data : jQuery('#create_lot :input').serialize()
              },
              success: function (data) {
                  jQuery("#create_lot")[0].reset();
                  clearPopup();
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
                  }
              }
          });
        }
        e.preventDefault();
        return false;
    });


  jQuery('.unique_brand').keyup(function() {
      this.value = this.value.toUpperCase();
      jQuery('.unique_brand').val(this.value);
  });


  jQuery('#cgst').on('change',function() {
      var cgst = jQuery('#cgst').val();
      jQuery('#sgst').val(cgst);

  });


//<-------Delete Lot------->

  jQuery('.delete-lot').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=list_lots&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Lot------->

jQuery(document).on('click','.cess',function(){
    var sess_value  = jQuery("input[name='cess']:checked").val();
  if( sess_value == 'yes'){
    jQuery('.cess_percentage').val(5.00);
    } else { 
    jQuery('.cess_percentage').val(0.00);
    }
});


});






