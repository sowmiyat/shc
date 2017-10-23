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
             alert("It is not valid mobile number.Enter 10 digits number!");
             jQuery('.mobile_check').val().focus();
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
                    jQuery('.mobile_check_wholesale').focus();

                }
                
                }
            });

            jQuery('.alert_primary_number').css('display','none'); 
        }
        else {

             jQuery('.mobile_check_wholesale').focus();
             jQuery('.alert_primary_number').css('display','block'); 

        }

    });
//<-----------customer name validation--------->

    jQuery('.wholesale_cus').keypress(function(e) {
         var capital_str = jQuery('.wholesale_cus').val();
//for validation

        var code =e.keyCode || e.which;
        if((code<65 || code>90)&&(code<97 || code>122)&&code!=32&&code!=46)  
        {

            jQuery("#customer_name").focus();
            jQuery('.alert_cus_name').css('display','block'); 
            return false;
        }

        else {
            jQuery('.alert_cus_name').css('display','none');
        }
//for display name in captialized

        if(isUpperCase(capital_str)){
             var product = capital_str;
          } 
        else {
           
            capital_str = capital_str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
        }

        jQuery('.wholesale_cus').val(capital_str);

    });
//<-----------company name validation--------->
    jQuery('.wholesale_company').keypress(function(e) {
        var capital_str = jQuery('.wholesale_company').val();
        var code =e.keyCode || e.which;
        if((code<65 || code>90)&&(code<97 || code>122)&&code!=32&&code!=46)  
        {

            jQuery("#company_name").focus();
            jQuery('.alert_company_name').css('display','block'); 
            return false;
        }
        else {
            jQuery('.alert_company_name').css('display','none');
        }

        if(isUpperCase(capital_str)){
             var product = capital_str;
          } 
        else {

            
            capital_str = capital_str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
        }

        jQuery('.wholesale_company').val(capital_str);

    });

//<------- Secondary mobile validation ------->
    jQuery('#secondarymobile').on('change',function() {
        var mobile_num = jQuery(this).val();
            if(!MobileValidate(mobile_num)) { 
                jQuery('.alert_secondary_number').css('display','block');
                jQuery('#secondarymobile').focus();
            }
            else {
                jQuery('.alert_secondary_number').css('display','none');
            }


    });
//<------- Address validation--->
    jQuery("#address").keypress(function(){
        var alphanumers = /^[a-zA-Z0-9\s,'-]*$/;
        if(!alphanumers.test(jQuery("#address").val())){
            jQuery('.alert_address').css('display','block');
            jQuery("#address").focus();
        }
        else {
            jQuery('.alert_address').css('display','none');
        }

    });
//<------- GST Validation --->
jQuery('#gst_number').unbind('keyup change input paste').bind('keyup change input paste',function(e){
    var this_val = jQuery(this);
    var val = this_val.val();
    var valLength = val.length;
    var maxCount = this_val.attr('maxlength');
    if(valLength>maxCount){
        this_val.val(this_val.val().substring(0,maxCount));
        jQuery('.alert_gst').css('display','block');
    }
    else {
        jQuery('.alert_gst').css('display','none');
    }
}); 
});


