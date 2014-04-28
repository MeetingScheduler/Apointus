// Login Form

$(function() {
    var button = $('#profile-name');
    var box = $('#settings');
    var form = $('#settings-box');
    button.removeAttr('href');
    button.mouseup(function(login) {
        box.toggle();
        button.toggleClass('active');
    });
    form.mouseup(function() { 
        return false;
    });
    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#profile-name').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });
});
