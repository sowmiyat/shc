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