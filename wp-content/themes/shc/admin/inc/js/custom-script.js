function popItUp (title, content) {
	jQuery('#my-button-new').click();

	jQuery('.popup_header').html(title);
	jQuery('.popup_container').html(content);

}
function clearPopup() {
	jQuery('.popup_header').html('');
	jQuery('.popup_container').html('');
}

jQuery(document).ready(function(){
    jQuery('.stock_from, .stock_to, .bill_from, .bill_to, .customer_from, .customer_to,.cheque_date').datepicker({dateFormat: "yy-mm-dd"});

	jQuery('#my-button-new').on('click', function(){
		jQuery('.popup_box').bPopup();
	});


    jQuery('.filter-section :input').on('change', function(){
        var filter_action   = jQuery('.filter_action').val();
        var container_class = '.'+filter_action;

        jQuery.ajax({
            type: "POST",
            url: frontendajax.ajaxurl,
            data: {
                action : filter_action,
                data : jQuery('.filter-section :input').serialize()
            },
            success: function (data) { 
                if (/^[\],:{}\s]*$/.test(data.replace(/\\["\\\/bfnrtu]/g, '@').
                replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
                    var obj = jQuery.parseJSON(data);
                    if(obj.success == 0) {
                        alert_popup('<span class="error_msg">Something Went Wrong! try again!</span>', 'Error');
                    }
                } else { 
                    jQuery(container_class).html(data);
                }
            }
        });
    });
	
//<------ Validation function------->
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
                    customer_id   :jQuery('.customer_id').val(),
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
    





//<-----------company name and customer name validation--------->
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
//<---- maxlength validation----->
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


//<------ End Validation function------->


});



function slugify(text){
  return text.toString().toLowerCase()
    .replace(/\s+/g, '_')           // Replace spaces with -
    .replace(/[^\u0100-\uFFFF\w\-]/g,'_') // Remove all non-word chars ( fix for UTF-8 chars )
    .replace(/\-\-+/g, '_')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '')            // Trim - from end of text
    .replace(/-+/g, '_');            // Trim - from end of text
}


function managePopupContent( data ) {
    window.location = data.redirect;
}


   function isNumberKey(evt)
   {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
         return false;

      return true;
   }

    function isUpperCase(str) {
        return str === str.toUpperCase();
    }

    function MobileValidate(mobile) {
        var pattern = /^\d{10}$/;
        if (pattern.test(mobile)) {
           
            return true;
        }
       
        return false;
    }
