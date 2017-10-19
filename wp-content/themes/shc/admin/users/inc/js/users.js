jQuery(document).ready(function () {
	jQuery(".chosen-select").select2();


	jQuery("#create_user").bind('submit', function (e) {
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
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
        e.preventDefault();
        return false;
    });

})