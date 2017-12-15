jQuery(document).ready(function () {


    jQuery('#customer_name').focus();
    jQuery('#name').focus();

    jQuery(".reset_button_ws_cus").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.cancel_button_ws_cus').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#customer_name').focus();
      } 
      else {
        jQuery('.reset_button_ws_cus').focus();
      }
    });

    jQuery(".reset_button_cus").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.cancel_button_cus').focus();
      }

      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#name').focus();
      } 
      else {
        jQuery('.reset_button_cus').focus();
      }
    });


    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

//<---  validation for whole sale--->
    jQuery( ".wholesale_submit" ).validate({
        rules: {
            customer_name: {
                nameValidite : true,
            },
            company_name : {
                nameValidite : true,
                required: true,
            },
            mobile: {
               
                minlength: 10,
                maxlength: 10,
                uniqueUserMobile: true
            },
            secondary_mobile: {
                minlength: 10,
            },
            landline: {
                minlength: 6,
                maxlength: 8,
            },
            address: {
                addressValidate : true,
            },
            gst_number : {
                
                gstValidate : true,
                minlength: 15,
                maxlength: 15,
            }
        },
        messages: {
            customer_name: {
                nameValidite: "Special Characters Not Allowed!",
            },
            company_name : {
                nameValidite : "Special Characters Not Allowed!",
            },
            mobile: {
                required: "Please Enter Valid Mobile Number!",
                minlength: "Mobile Number Must Be 10 Numbers!",
                maxlength: "Mobile Number Must Be 10 Numbers!",
                uniqueUserMobile : "Mobile Number Already Exist!",
            },
            secondary_mobile : {
                minlength: "Please Enter Valid Mobile Number!",
            },
            landline : {
                minlength: "Please Enter Valid Landline Number!",
                maxlength: "Please Enter Valid Landline Number!",
            },
            address: {
                addressValidate : "Please Enter Valid Address",
            },
            gst_number : {
                required: "Please Enter Valid GST Number!",
                gstValidate : "GST Numbers Does Not Contain Special Characters!",
                minlength: "GST Number Must Be 15 Letters!",
                maxlength: "GST Number Must Be 15 Letters!",
            }

        }
    });


//<--- validation for retail customer ---->

    jQuery( ".retail_submit" ).validate({
        rules: {
            name: {
                nameValidite : true,
            },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10,
                uniqueUserMobile: true
            },
            secondary_mobile: {
                minlength: 10,
            },
            landline: {
                minlength: 6,
                maxlength: 8,
            },
            address: {
                addressValidate : true,
            },
        },
        messages: {
            name: {
                nameValidite: "Special Characters Not Allowed!",
            },
            mobile: {
                required: "Please Enter Valid Mobile Number!",
                minlength: "Mobile Number Must Be 10 Numbers!",
                maxlength: "Mobile Number Must Be 10 Numbers!",
                uniqueUserMobile : "Mobile Number Already Exist!",
            },
            secondary_mobile : {
                minlength: "Please Enter Valid Mobile Number!",
            },
            landline : {
                minlength: "Please Enter Valid Landline Number!",
                maxlength: "Please Enter Valid Landline Number!",
            },
            address: {
                addressValidate : "Please Enter Valid Address",
            },
        }
    });

    /*Add Customer Form Submit*/
    jQuery("#create_customer").bind('submit', function (e) {

        if(jQuery(this).hasClass('retail_submit')) {
            var valid = jQuery(".retail_submit").valid();
            var prevent = jQuery(".form_submit_prevent_customer").val();
        }
        if(jQuery(this).hasClass('wholesale_submit')) {
            var valid = jQuery(".wholesale_submit").valid();
            var prevent = jQuery(".form_submit_prevent_ws_customer").val();
        }
        
       if( valid && prevent == "off") {
            jQuery(".form_submit_prevent_customer").val('on');
            jQuery(".form_submit_prevent_ws_customer").val('on');
            jQuery('#lightbox').css('display','block');
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action : jQuery('.customer_action').val(),
                    data : jQuery('#create_customer :input').serialize()
                },
                success: function (data) {
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





});


