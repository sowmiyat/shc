jQuery(document).ready(function () {

 jQuery('#brand_name').focus();

    jQuery('.sub_form').on('click',function() {
        if(jQuery('form')[0].checkValidity()) {
            jQuery('.sub_form').css('display','none');
            jQuery('#lightbox').css('display','block');
        }

    });
    jQuery(".reset_button").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#brand_name').focus();
      } 
    });
    /*Add Lot Form Submit*/
    jQuery("#create_lot").bind('submit', function (e) {
       
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
            

                /*setTimeout(function() {
                    managePopupContent(data);
                }, 4000);*/
            }
        });
        e.preventDefault();
        return false;
    });


jQuery('.unique_brand').on('change',function() {
    this.value = this.value.toUpperCase();
    jQuery('.unique_brand').val(this.value);
    

        //     jQuery.ajax({
        //     type: "POST",
        //     dataType : "json",
        //     url: frontendajax.ajaxurl,
        //     data: {
        //         action      : 'check_unique_brand',
        //         brandname   : this.value
        //     },
        //     success: function (data) {
        //        if(data == 1){
        //         alert('Brand name Already Exists!!!');
        //         jQuery('.unique_brand').val('').focus();

        //     }
            
        //     }
        // });
    

});


jQuery('.unique_product').on('change',function() {
    var capital_str = jQuery('.unique_product').val();

  if(isUpperCase(capital_str)){
     var product = capital_str;
  } 
  else {

     capital_str = capital_str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
    });
    var product = jQuery('.unique_product').val(capital_str);

  }
       jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action          : 'check_unique_product',
                productname     : capital_str,
                brandname       : jQuery('.unique_brand').val()
            },
            success: function (data) {
               if(data == 1){
                alert('Product name Already Exists!!!');
                jQuery('.unique_product').val('').focus();

            }
            
            }
        });
    

});

jQuery('#cgst').on('change',function() {
    var cgst = jQuery('#cgst').val();
    jQuery('#sgst').val(cgst);

});


});

  // function isUpperCase(str) {
  //   return str === str.toUpperCase();
  //   }






