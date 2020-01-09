function validateForm(){
    $('#installation_form').validate({
        errorElement: 'span',
        // onfocusin: function(element) { 
        //     $(element).valid(); 
        // },
        onfocusout: function(element) { 
            $(element).valid(); 
        },

        rules: {
            store_name: {
                required: true,
            },
        },

        messages: {
          store_name: {
            required: "Please enter a store url.",
          },
        },

        errorPlacement: function(error, element) {
            $(element).prev().append(error);
        },
        success: function(error, element) {
        //                
        },
    });
}

$(document).ready(function(){
    validateForm();
});