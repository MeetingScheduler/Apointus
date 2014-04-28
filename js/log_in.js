// Login Form

$(function() {
    var button = $('#login-button');
    var box = $('#login-box');
    var form = $('#login-form');
    // button.removeAttr('href');
    $('#login-box').hide();
    $('#login-button').show(); 

    button.mouseup(function(login) {
        box.toggle();
        button.toggleClass('active');
    });
    form.mouseup(function() { 
        return false;
    });
    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#login-button').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });
});
