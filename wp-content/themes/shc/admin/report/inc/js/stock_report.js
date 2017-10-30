jQuery(document).ready(function (argument) {

/* 	jQuery('.return_report_print').on('click',function(){
		console.log("jlkjklj");
     	var slap = jQuery('.slap').val();
     	 var bill_form = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'return-report-print/?bill_form='+bill_form+'&bill_to='+bill_to + '&slap='+slap;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    }); */


	jQuery('.accountant_download').on('click',function() {
        console.log("fdsfdf");
        var bill_form = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'acc-download/?bill_form='+bill_form+'&bill_to='+bill_to;

        // billing_list_single
        var thePopup = window.open( datapass, "Download Report","" );
       
    });

     jQuery('.accountant_print').on('click',function(){
     var bill_form = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'acc-print/?bill_form='+bill_form+'&bill_to='+bill_to;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });


     jQuery('.stock_download').on('click',function() {

       	var slap = jQuery('.slap').val(); 
        var bill_form = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'report-download/?bill_form='+bill_form+'&bill_to='+bill_to + '&slap='+slap;

        // billing_list_single
        var thePopup = window.open( datapass, "Download Report","" );
       
    });

       jQuery('.return_report_download').on('click',function() {

        var slap = jQuery('.slap').val(); 
        var bill_form = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'return-report-download/?bill_form='+bill_form+'&bill_to='+bill_to + '&slap='+slap;

        // billing_list_single
        var thePopup = window.open( datapass, "Download Report","" );
       
    });

     jQuery('.stock_print').on('click',function(){
     	var slap = jQuery('.slap').val();
     	 var bill_form = jQuery('.bill_from').val();
        var bill_to = jQuery('.bill_to').val();
        var datapass =   home_page.url+'report-print/?bill_form='+bill_form+'&bill_to='+bill_to + '&slap='+slap;

        // billing_list_single
        var thePopup = window.open( datapass, "Billing Wholesale Invoice","scrollbars=yes,menubar=0,location=0,top=50,left=300,height=700,width=950" );
        thePopup.print();  
    });
	
	
});