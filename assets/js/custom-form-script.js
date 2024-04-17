jQuery(document).ready(function($) {
    $('#dataForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting traditionally
        var formData = $(this).serialize(); // Serialize form data
        var nonce = $('#save_data_nonce').val(); // Get nonce value

        $.ajax({
            type: 'POST',
            url: ajaxurl, // WordPress AJAX URL
            data: {
                action: 'save_data', // Action hook for WordPress AJAX
                formData: formData, // Form data to be sent to the server-side script
                nonce: nonce // Include nonce value in the request
            },
            success: function(response) {
                // Handle success response from the server
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
            }
        });
    });
});
