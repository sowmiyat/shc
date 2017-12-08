jQuery(document).ready(function () {
    jQuery('.user_name').focus();

    jQuery(document).live('keydown', function(e){
        if(jQuery(document.activeElement).closest("#wpbody-content").length == 0) {
            var keyCode = e.keyCode || e.which; 
            if (keyCode == 9) { 
                e.preventDefault(); 
                jQuery('.user_name').focus()
            }
        }
    });

    jQuery('.admin_cancel').live('keydown',function(e){
           var keyCode = e.keyCode || e.which; 
          if(event.shiftKey && event.keyCode == 9) {  
             e.preventDefault(); 
            jQuery('.admin_reset').focus();
          }
          else if (event.keyCode == 9) { 
            e.preventDefault(); 
            jQuery('.user_name').focus();
          } 
          else {
            jQuery('.admin_cancel').focus();
          }
    });


    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });

    jQuery( ".admin_submit" ).validate({
        rules: {
            user_name: {
                required: true,
                userNameValidate: true,
            },
            password : {
                required: true,

            },
            mobile: {
                required: true,
                minlength: 10,
                maxlength: 10,
            },
            email: {
                 required: true,
                 userEmailValidate :true,
            },
            role : {
                 required: true,
            },
        },
        messages: {
            user_name: {
                required: 'Please Enter User name!',
                userNameValidate : 'Special Characters and Spaces Not Allowed!'
            },
            password : {
                required: 'Please Enter Password!',
            },
            mobile : {
                required: "Please Enter Mobile Number!",
                minlength: "Mobile Number Must Be 10 Numbers!",
                maxlength: "Mobile Number Must Be 10 Numbers!",
            },
            email: {
                required: "Please Enter Email Id!!!",
                userEmailValidate : "Enter valid Email id!!!"
            },
            role : {
                required: "Please Select Role!",
            },
            
        }
    });

	jQuery("#create_user").bind('submit', function (e) {
        var valid = jQuery(".admin_submit").valid();
        
        if( valid) {
            jQuery('#lightbox').css('display','block');
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                width:'25%',
                url: frontendajax.ajaxurl,
                data: {
                    action : jQuery('#create_user .user_action').val(),
                    data : jQuery('#create_user :input').serialize()
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


    var response = true;
    jQuery.validator.addMethod(
        "userNameValidate", 
        function(value, element) {
            var alphanumers = /^[a-zA-Z0-9]*$/;
            if(!alphanumers.test(value)){
                return false;
            }
            return true;
        }
    );





    jQuery.validator.addMethod(
        "userEmailValidate", 
        function(value, element) {
            var alphanumers = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(!alphanumers.test(value)){
                return false;
            }
            return true;
        }
    );

})