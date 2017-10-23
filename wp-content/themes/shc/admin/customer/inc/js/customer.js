jQuery(document).ready(function () {


    jQuery('#customer_name').focus();
    jQuery('#name').focus();


    jQuery.validator.setDefaults({
      debug: true,
      success: "valid"
    });


    jQuery( ".wholesale_submit" ).validate({
        rules: {
            customer_name: {
                nameValidite : true,
            },
            company_name : {
                nameValidite : true
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
            gst_number : {
                required: true,
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



    /*Add Lot Form Submit*/
    jQuery("#create_customer").bind('submit', function (e) {
        

        var valid = jQuery(".wholesale_submit").valid();
        if(valid) {
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


    var response = true;
    jQuery.validator.addMethod(
        "uniqueUserMobile", 
        function(value, element) {
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                async: false,
                url: frontendajax.ajaxurl,
                data: {
                    action       : jQuery('.unique_mobile_action').val(),
                    mobile       : value,
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
        },
        "Username is Already Taken"
    );


    jQuery.validator.addMethod(
        "addressValidate", 
        function(value, element) {
            var alphanumers = /^[a-zA-Z0-9\s,\(\)\/#'-]*$/;
            if(!alphanumers.test(value)){
                return false
            }
            return true;
        }
    );


    jQuery.validator.addMethod(
        "gstValidate", 
        function(value, element) {
            var alphanumers = /^[a-zA-Z0-9]*$/;
            if(!alphanumers.test(value)){
                return false
            }
            return true;
        }
    );

    jQuery.validator.addMethod(
        "nameValidite", 
        function(value, element) {
            var alphanumers = /^[a-zA-Z0-9\s\(\),-]*$/;
            if(!alphanumers.test(value)){
                return false
            }
            return true;
        }
    );
    





//<-----------company name validation--------->
    jQuery('.customer_check').on('change', function(e) {
        var capital_str = jQuery(this).val();

        var res = capital_str.split(" ");
        var full_str = '';
        jQuery.each(res, function(e, value){
             if(isUpperCase(value)){
                 full_str = full_str + value + ' ';
             } 
             else {
                value = value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                });
                full_str = full_str + value + ' ';
             }
        });

        jQuery(this).val(full_str.trim());
    });

    jQuery('#mobile, #secondarymobile, #landline').unbind('keyup change input paste').bind('keyup change input paste',function(e){
        var this_val = jQuery(this).val();
        var valLength = this_val.length;
        var maxCount = jQuery(this).attr('maxlength');
        if(valLength>maxCount){
            this_val = this_val.substring(0,maxCount);
        }
        jQuery(this).val(this_val);
    });

    //<------- GST Validation --->
    jQuery('#gst_number').unbind('keyup change input paste').bind('keyup change input paste',function(e){
        var this_val = jQuery(this);
        var val = this_val.val();
        var valLength = val.length;
        var maxCount = this_val.attr('maxlength');
        if(valLength>maxCount){
            var gst_num = val.substring(0,maxCount);
            this_val.val(gst_num.toUpperCase());
        }
        this_val.val(val.toUpperCase());
    }); 


});


