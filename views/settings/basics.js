$(function(){
    initEvents();
});

function initEvents() {
    $('#save-btn').click(saveClicked);
}

function saveClicked() {
    $.ajax({
        type: 'POST',
        url: userUpdateUrl,
        data: $('#account-basics-form').serialize(),
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Account basics update error ' + textStatus + ' ' + errorThrown);
            console.log(jqXHR);
            var responseText = jqXHR.responseText;
            var start = responseText.indexOf(':');
            var message = responseText.substring(start + 1).trim();
            $('#note').attr('class', 'alert alert-danger').text(message).show();
        },
        success: function(data, textStatus, jqXHR) {
            console.log('Account basics update success');
            console.log(data);
            $('#note').attr('class', 'alert alert-success').text('Your information has been updated!').show();
        }
    });
}