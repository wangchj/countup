$(function() {
    $('.btn-follow, .btn-unfollow').click(function(){
        var followeeId = $(this).attr('user-id');
        var followerId = app.user.userId;
        var mode = $(this).text().toLowerCase();
        console.log('mode ' + mode);
        if(mode == 'follow')
            followClicked(this, followerId, followeeId);
        else if (mode == 'unfollow')
            unfollowClicked(this, followerId, followeeId);
    });
});

function followClicked(button, followerId, followeeId) {
    console.log('follow clicked');
    $.ajax({
        type:'GET',
        url:followUrl,
        data: {'followerId': followerId, 'followeeId': followeeId},
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Follow ajax error ' + textStatus + ' ' + errorThrown);
        },
        success: function(data, textStatus, jqXHR) {
            console.log('Follow ajax success');
            $(button).removeClass('btn-follow').addClass('btn-unfollow').text('Unfollow');
        }
    });
}

function unfollowClicked(button, followerId, followeeId) {
    console.log('unfollow clicked');
    $.ajax({
        type:'GET',
        url:unfollowUrl,
        data: {'followerId': followerId, 'followeeId': followeeId},
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Unfollow ajax error ' + textStatus + ' ' + errorThrown);
        },
        success: function(data, textStatus, jqXHR) {
            console.log('Unfollow ajax success');
            $(button).removeClass('btn-unfollow').addClass('btn-follow').text('Follow');
        }
    });
}

