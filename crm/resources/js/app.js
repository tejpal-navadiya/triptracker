import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();



// Ensure the CSRF token is set in the headers for all AJAX requests
$(document).ready(function () {
    // Automatically include CSRF token in every AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Optionally, you can listen for CSRF token changes across tabs using localStorage/sessionStorage
  

    // If your CSRF token changes dynamically (on login, session refresh, etc.):
    // Example of handling token update
    // For example, after a successful login, call:
    // updateCSRFToken(response.csrf_token);
});
