$(function(){
    initEvents();
});


function initEvents() {
    $('#save-btn').click(saveClicked);
    $('#preview-btn').click(function(event){
        event.preventDefault();
    }).mousedown(function(event){
        console.log('mouse down');
        $('#old-password').attr('type', 'text');
        $('#new-password').attr('type', 'text');
        event.preventDefault();
    }).mouseup(function(event){
        console.log('mouse up');
        $('#old-password').attr('type', 'password');
        $('#new-password').attr('type', 'password');
    });
}

function saveClicked(event) {
    console.log('saveClicked()');

    $.ajax({
        type: 'POST',
        url: changePasswordUrl,
        data: $('#change-password-form').serialize(),
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Change password error ' + textStatus + ' ' + errorThrown);
            console.log(jqXHR);
            var responseText = jqXHR.responseText;
            var start = responseText.indexOf(':');
            var message = responseText.substring(start + 1).trim();
            $('#note').attr('class', 'alert alert-danger').text(message).show();
        },
        success: function(data, textStatus, jqXHR) {
            console.log('Change password success');
            console.log(data);
            $('#note').attr('class', 'alert alert-success').text('Your password has been updated!').show();
        }
    });

    event.preventDefault();
}