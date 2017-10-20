jQuery(document).ready(function () {

     jQuery('.submit_form').on('click',function(){
        if(jQuery('form')[0].checkValidity()) {
                jQuery('.submit_form').css('display','none');
                jQuery('#lightbox').css('display','block');
            }

    });

    jQuery('#customer_name').focus();
    jQuery('#name').focus();

    /*Add Lot Form Submit*/
    jQuery("#create_customer").bind('submit', function (e) {
       
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
        e.preventDefault();
        return false;
    });

    jQuery('.mobile_check').on('change',function() {

        var mobile_num = jQuery('.mobile_check').val();
        if(MobileValidate(mobile_num)) { 

            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action       : 'check_unique_mobile',
                    mobile       : jQuery('.mobile_check').val()
                },
                success: function (data) {
                   if(data != 0){
                    alert('Mobile Already Exists!!!');
                    jQuery('.mobile_check').val('').focus();

                }
                
                }
            });
        }
        else {
             alert("It is not valid mobile number.input 10 digits number!");
             jQuery('.mobile_check').val('').focus();
        }
    });

    jQuery('.mobile_check_wholesale').on('change',function() {

        var mobile_num = jQuery('.mobile_check_wholesale').val();
        if(MobileValidate(mobile_num)) {  
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: frontendajax.ajaxurl,
                data: {
                    action       : 'check_unique_mobile_wholesale',
                    mobile       : jQuery('.mobile_check_wholesale').val()
                },
                success: function (data) {
                   if(data != 0){
                    alert('Mobile Already Exists!!!');
                    jQuery('.mobile_check_wholesale').val('').focus();

                }
                
                }
            });
        }
        else {

             alert("It is not valid mobile number.input 10 digits number!");
             jQuery('.mobile_check_wholesale').val('').focus();
        }

    });


    jQuery('.wholesale_cus').on('change',function() {

        var capital_str = jQuery('.wholesale_cus').val();
        capital_str = capital_str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });

        jQuery('.wholesale_cus').val(capital_str);

    });

        jQuery('.wholesale_company').on('change',function() {

        var capital_str = jQuery('.wholesale_company').val();
        capital_str = capital_str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });

        jQuery('.wholesale_company').val(capital_str);

    });


});

function MobileValidate(mobile) {
        var pattern = /^\d{10}$/;
        if (pattern.test(mobile)) {
            alert("Your mobile number : " + mobile);
            return true;
        }
       
        return false;
    }
