$(document).ready(function(){
    // The error part where I would like to hide message after some seconds
    $(".error-message").delay(3200).fadeOut(500);

    // As like as error message
    $(".success-message").delay(3200).fadeOut(500);

    // As like as error message
    $(".warning-message").delay(15000).fadeOut(500);
});