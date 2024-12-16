// Check if the URL has the "loggedout" or "registered" parameter
const urlParams = new URLSearchParams(window.location.search);

// Show logout toast if the user logged out
if (urlParams.has('loggedout')) {
    var logoutToastElement = document.getElementById('logoutToast');
    var logoutToast = new bootstrap.Toast(logoutToastElement);
    logoutToast.show();
}

// Show registration toast if the user just registered
if (urlParams.has('registered')) {
    var registerToastElement = document.getElementById('registerToast');
    var registerToast = new bootstrap.Toast(registerToastElement);
    registerToast.show();
}



