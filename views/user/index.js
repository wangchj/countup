$(function(){
    initCounterMenu();
    initRemoveModalBtnEvents();
});

function initCounterMenu() {
    $('.counter-menu-toggle').popover({
        content: function() {
            var counterId = $(this).attr('counterId');
            var setStr = '<li class="counter-menu-item" onclick="counterSettingClicked(' + counterId + ')">' +
                '<span class="glyphicon glyphicon-cog"></span> Counter Settings</li>';
            var remStr =
                '<li class="counter-menu-item" onclick="counterRemoveClicked(' + counterId + ')">' +
                    '<span class="glyphicon glyphicon-fire"></span> Remove Counter' +
                '</li>';

            var res = '<ul class="counter-menu">' + setStr + '<hr style="margin:5px">' + remStr + '</ul>';

            return res;
        },
        container: 'body',
        html:      true,
        placement: 'bottom',
        trigger:   'focus'
    });
}

//function initEvents() {
//    $('a.cs-reset').click(function(){
//        alert('hello');
//    });
//}

var markError = -1;
var markNone = 0;
var markDone = 1;
var markMiss = 2;

var colorDone = '#d6e685';
var colorNone  = '#e3e3e3';
//var colorStart = '#bee685';
var colorMiss = '#e6c785';

function counterSettingClicked(counterId) {
    console.log('counter setting clicked ' + counterId);
}

function counterRemoveClicked(counterId) {
    $('#remove-confirm-modal').modal('show');
    $('#remove-confirm-modal').attr('counterId', counterId);
}

function initRemoveModalBtnEvents() {
    $('#remove-confirm-modal .btn-no').click(function() {
        $('#remove-confirm-modal').modal('hide');
    });

    
    $('#remove-confirm-modal .btn-yes').click(function() {
        var counterId = $('#remove-confirm-modal').attr('counterId');
        
        $.ajax({
            type:'GET',
            url: counterRemoveUrl + '?counterId=' + counterId,
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Counter remove error ' + textStatus + ' ' + errorThrown);
            },
            success: function(data, textStatus, jqXHR) {
                console.log('Counter remove success');
                $('#counter-container-' + counterId).remove();
            }
        });

        $('#remove-confirm-modal').modal('hide');
    });
}

function markClicked(action, counterId, date) {
    //console.log('markClicked: ' + counterId + ', ' + date);
    
    //Make sure we're not marking date in the future.
    var markDate = new Date(date);
    var today = new Date();
    if(dateGreater(markDate, today))
        return;

    var state = getCellState(counterId, date);
    //console.log(state);

    if(action == state || state == markError || action < 0 || action > 2)
        return;

    $.ajax({
        type:'GET',
        url: markUrl + '?action=' + action + '&counterId=' + counterId + '&date=' + date,
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + ':' + errorThrown);
        },
        success: function(data, textStatus, jqXHR) {
            //console.log(this.url);
            //console.log('mark ajax success:' + textStatus);
            Snap.select("g.cell[counter='" + counterId + "']" + "[date='" + date + "'] rect").
                animate({fill: action == markNone ? colorNone : action == markDone ? colorDone : colorMiss}, 400);
            
            $.ajax({
                type:'GET',
                url: getDaysUrl + '?counterId=' + counterId,
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('get-days ajax ' + textStatus + ':' + errorThrown);
                },
                success: function(data, textStatus, jqXHR) {
                    $('div#counter-container-' + counterId + ' ' + 'span.current-count').text(data);
                }
            });
        }
    });
}

function getCellState(counterId, date) {
    var cell = $("g.cell[counter='" + counterId + "']" + "[date='" + date + "']");
    var rect = cell.find('rect');
    var color = rect.attr('fill');

    //console.log(cell);

    switch(color) {
        case colorDone:
            return markDone;
        case colorNone:
            return markNone;
        case colorMiss:
            return markMiss;
        default:
            return markError;
    }
}