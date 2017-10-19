jQuery(document).ready(function () {

    /*Add Lot Form Submit*/
    jQuery("#create_role").bind('submit', function (e) {
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : jQuery('#create_role .role_action').val(),
                data : jQuery('#create_role :input').serialize()
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



});