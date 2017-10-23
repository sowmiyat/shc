jQuery(document).ready(function () {
     jQuery('#pro_number').live('keyup');

     jQuery('.submit_form').on('click',function(){
        if(jQuery('form')[0].checkValidity()) {
                jQuery('.submit_form').css('display','none');
                jQuery('#lightbox').css('display','block');
            }

    });
    jQuery(".stock_cancel").on('keydown',  function(e) { 
      var keyCode = e.keyCode || e.which; 

      if (keyCode == 9) { 
        e.preventDefault(); 
        jQuery('#pro_number').live('keyup');
      } 
    });  
   
    jQuery("#pro_number").select2({
        allowClear: true,
        width: '100%',
        multiple: true,
        minimumInputLength: 1,
        maximumSelectionLength: 1,
        ajax: {
        type: 'POST',
        url: frontendajax.ajaxurl,
        delay: 250,
        dataType: 'json',
            data: function(params) {
                return {
                    action: 'search_lot', // search term
                    page: 1,
                    search_key: params.term,
                };
            },
            processResults: function(data) {
                console.log(data);
                var results = [];
                return {
                    results: jQuery.map(data.items, function(obj) {
                        return { id: obj.id, lot_no:obj.lot_no, brand_name: obj.brand_name, product_name:obj.product_name, selling_price:obj.selling_price };
                    })
                };
            },
            cache: false
        },
        initSelection: function (element, callback) {
            callback({ id: jQuery(element).val(), product_name: jQuery(element).find(':selected').text() });
        },
        templateResult: formatStateStockCreate,
        templateSelection: formatStateStockCreate1
    }).on("select2:select", function (e) { 
        jQuery('#brand_name').val(e.params.data.brand_name);
        jQuery('.lot_number').val(e.params.data.id);
        jQuery('#product_name').val(e.params.data.product_name);
        jQuery('#unit_price').val(e.params.data.selling_price);
        jQuery('#selling_price').val(e.params.data.selling_price);

        jQuery('#stock_count').focus();
        var tmpStr = jQuery('#stock_count').val();
        jQuery('#stock_count').val('');
        jQuery('#stock_count').val(tmpStr);

    });



    /*Add stock Form Submit*/
    jQuery("#add_stock").bind('submit', function (e) {
        jQuery('#lightbox').css('display','block');
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: frontendajax.ajaxurl,
            data: {
                action : jQuery('.stock_action').val(),
                data : jQuery('#add_stock :input').serialize()
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

    jQuery('.stock_count').on('change',function() {

        var change_count = jQuery('.stock_count').val();
        var selling = jQuery('#unit_price').val();
        var stock_count = selling * change_count;
         jQuery('#selling_price').val(stock_count);



    });

//<-------Delete Stock------->

  jQuery('.delete-stock').live( "click", function() {
    if(confirm('Are you sure you want to delete this element?')){
      var data=jQuery(this).attr("data-id");
      console.log(data);
      window.location.replace('admin.php?page=list_stocks&delete_id='+data+'&action=delete');
    }

  });
  //<-------End Delete Stock------->


});

function formatStateStockCreate (state) {
  if (!state.id) {
    return state.id;
  }
  var $state = jQuery(
    '<span><b>Brand Name &nbsp;&nbsp;&nbsp;&nbsp;:</b>' +
      state.brand_name +
    '</span><br/>' +
    '<span><b>Product Name &nbsp;:</b>' +
      state.product_name +
    '</span>'
  );
  return $state;
};




function formatStateStockCreate1 (state) {
    if (!state.id) {
        return state.id;
    }
    var $state = jQuery(
    '<span>' +
    state.product_name +
    '</span><br/>'
   
    );
    return $state;
};

