/** The picture file to be uploaded. */
var fileData = null;

$(function(){
    initFileUpload();
    initEvents();
});

function initFileUpload() {
    $('#fileupload').fileupload({
       autoUpload: false,
       url: userPictureUploadUrl,
       acceptFileTypes: /(\.|\/)(jpe?g|png)$/i,
       maxFileSize: 1000000, //1 MB
    }).on('fileuploadadd', function (e, data) {
        fileData = data;
        var file = fileData.files[0];
        if(file.size > userPictureMaxSize) {
            $('#note').attr('class', 'alert alert-danger').text('Picture exceeds file size limit.').show();
            fileData = null;
            return;
        }

        if(!file.type.match(/(\.|\/)(jpe?g|png)$/i)) {
            $('#note').attr('class', 'alert alert-danger').text('Only png or jpg pictures files are allowed.').show();
            fileData = null;
            return;
        }

        //Load preview
        loadImage(file,
            function (img) {
                $(img).attr({'id': 'user-picture', 'class':'img-circle'}).
                    css({'width':60,'margin-right':20}).
                    removeAttr('width').removeAttr('height');
                $('#user-picture').replaceWith(img);
            },
            {maxWidth: 600} // Options
        );
    });
}

function initEvents() {
    $('#change-pic-btn').click(changePictureClicked);
    $('#save-btn').click(saveClicked);
}

function changePictureClicked() {
    $('#picture-input').trigger('click');
}

function saveClicked() {
    if(fileData == null) {
        updateUserInfo();
        return;
    }

    //Upload picture
    fileData.submit().error(function(jqXHR, textStatus, errorThrown){
        var responseText = jqXHR.responseText;
        var start = responseText.indexOf(':');
        var message = responseText.substring(start + 1).trim();
        $('#note').attr('class', 'alert alert-danger').text(message).show();
    }).success(function(result, textStatus, jqXHR){
        updateUserInfo();
    });
}

function updateUserInfo() {
    $.ajax({
        type: 'POST',
        url: userUpdateUrl,
        data: $('#account-basics-form').serialize(),
        error: function(jqXHR, textStatus, errorThrown) {
            var responseText = jqXHR.responseText;
            var start = responseText.indexOf(':');
            var message = responseText.substring(start + 1).trim();
            $('#note').attr('class', 'alert alert-danger').text(message).show();
        },
        success: function(data, textStatus, jqXHR) {
            $('#note').attr('class', 'alert alert-success').text('Your information has been updated!').show();
        }
    });
}
