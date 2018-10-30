jQuery(document).ready(function () {
	
    
    var shop = jQuery('#nb_shop').val();
    jQuery('#nb_shop').focus().val('').val(shop);

 

    jQuery(".reset_button_netbank").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.submit_netbank').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#nb_shop').focus();
      } 
      else {
        jQuery('.reset_button_netbank').focus();
      }
    });


    jQuery("#nb_shop").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.reset_button_netbank').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#nb_bank').select();
      } 
      else {
        jQuery('#nb_shop').focus();
      }
    });


    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

//<---  validation for whole sale--->
    jQuery( ".netbank_submit" ).validate({
        rules: {
            nb_shop: {
                required: true,
                nameValidite : true,
            },
            nb_bank: {
              required: true,
              nameValidite : true,
            },
            
            nb_account: {
                required: true,
                minlength: 9,
                maxlength: 20,
                accountValidate : true,
            },
             nb_ifsc: {
                required: true,
                ifscValidate : true,
            },
             nb_branch: {
                required: true,
                nameValidite : true,
            },
            nb_account_type : {
                required: true,
                 nameValidite : true,
            }
        },
        messages: {
            nb_shop: {
                nameValidite: "Special Characters Not Allowed!",
                required: "Please Enter Name!",
            },
            nb_bank: {
                required: "Please Enter Bank!",
                nameValidite: "Special Characters Not Allowed!",
               
            },
           
            nb_account: {
                required: "Please Enter Address !",
                minlength: "Account number atleast have 9 digits",
                maxlength: "Please enter no more than 20 characters.",
                accountValidate: "Only numbers are Allowed!",
            },

             nb_ifsc: {
                  required: "Please Enter IFSC code!",
                  ifscValidate: "Special Characters Not Allowed!",
            },
             nb_branch: {
                required: "Please Enter Branch!",
                nameValidite: "Special Characters Not Allowed!",
            },
            nb_account_type : {
                required: "Please Enter account Type!",
                nameValidite: "Special Characters Not Allowed!",
            }

        }
    });

    /*Add Customer Form Submit*/
    jQuery("#create_netbank").bind('submit', function (e) {

        var valid = jQuery(".netbank_submit").valid();
        var prevent = jQuery(".netbank_frm_pre").val();
        
       if( valid && prevent == "off") {
            jQuery(".netbank_frm_pre").val('on');
            jQuery('#lightbox').css('display','block');
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action : jQuery('.netbank_action').val(),
                    data : jQuery('#create_netbank :input').serialize()
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


