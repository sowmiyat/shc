jQuery(document).ready(function () {
	
    var comp_nam = jQuery('#profile_company').val();
    jQuery('#profile_company').focus().val('').val(comp_nam);

 

    jQuery(".reset_button_profile").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.submit_profile').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#profile_company').focus();
      } 
      else {
        jQuery('.reset_button_profile').focus();
      }
    });


    jQuery("#profile_company").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('.reset_button_profile').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#profile_mobile').select();
      } 
      else {
        jQuery('#profile_company').focus();
      }
    });


   
    jQuery("#profile_mobile").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('#profile_company').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
         var comp_nam = jQuery('#profile_address').val();
        jQuery('#profile_address').focus().val('').val(comp_nam);
      }
      else {
        jQuery('#profile_mobile').focus();
      }
    });


    jQuery("#profile_address").on('keydown',  function(e) { 

      var keyCode = e.keyCode || e.which; 
       if(event.shiftKey && event.keyCode == 9) {  
         e.preventDefault(); 
        jQuery('#profile_mobile').focus();
      }
      else if (keyCode == 9) { 
        e.preventDefault(); 
         var addr1 = jQuery('#profile_address2').val();
        jQuery('#profile_address2').focus().val('').val(addr1);
      }
      else {
        jQuery('#profile_address').focus();
      }
    });



    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

//<---  validation for whole sale--->
    jQuery( ".profile_submit" ).validate({
        rules: {
            profile_company: {
                required: true,
                nameValidite : true,
            },
            profile_mobile: {
               required: true,
                minlength: 10,
                maxlength: 10,
                uniqueUserMobile: true
            },
            
            profile_address: {
                required: true,
                addressValidate : true,
            },
             profile_address2: {
                required: true,
                addressValidate : true,
            },
            profile_gst_number : {
                required: true,
                gstValidate : true,
                minlength: 15,
                maxlength: 15,
            }
        },
        messages: {
            profile_company: {
                nameValidite: "Special Characters Not Allowed!",
                required: "Please Enter Company Name!",
            },
            profile_mobile: {
                required: "Please Enter Mobile Number!",
                minlength: "Mobile Number Must Be 10 Numbers!",
                maxlength: "Mobile Number Must Be 10 Numbers!",
                uniqueUserMobile : "Mobile Number Already Exist!",
            },
           
            profile_address: {
                required: "Please Enter Address !",
                addressValidate : "Address field contains only Alphabets,number , # , . , : , - and ,",
            },

             profile_address2: {
                required: "Please Enter Address !",
                addressValidate : "Address field contains only Alphabets,number , # , . , : , - and ,",
            },
            profile_gst_number : {
                required: "Please Enter GST Number!",
                gstValidate : "GST Numbers Does Not Contain Special Characters!",
                minlength: "GST Number Must Be 15 Letters!",
                maxlength: "GST Number Must Be 15 Letters!",
            }

        }
    });

    /*Add Customer Form Submit*/
    jQuery("#create_profile").bind('submit', function (e) {

        var valid = jQuery(".profile_submit").valid();
        var prevent = jQuery(".profile_frm_pre").val();
        
       if( valid && prevent == "off") {
            jQuery(".profile_frm_pre").val('on');
            jQuery('#lightbox').css('display','block');
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action : jQuery('.profile_action').val(),
                    data : jQuery('#create_profile :input').serialize()
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


