/*asdfas*/
$(function(){
    initCounterMenu();
    //initEvents();
});

function initCounterMenu() {
    $('.counter-setting').popover({
        content: function() {
            var counterId = $(this).attr('counterId');
            var setStr = '<li class="counter-menu-item">' +
                '<a onclick="counterSettingClicked(' + counterId + ')" href="#">' +
                '<span class="glyphicon glyphicon-cog"></span> Counter Settings</a></li>';
            var remStr = '<li class="counter-menu-item">' +
                '<a onclick="counterRemoveClicked(' + counterId + ')" href="#">' +
                '<span class="glyphicon glyphicon-fire"></span> Remove Counter</a></li>';

            var res = '<ul class="counter-menu">' + setStr + '<hr style="margin:5px">' + remStr + '</ul>';

            return res;
        },
        container: 'body',
        html:      true,
        placement: 'bottom',
        trigger:   'click'
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
    console.log('counter setting clicked');
}

function counterRemoveClicked(counterId) {
    
    
    $.ajax({
        type:'GET',
        url: counterRemoveUrl + '?counterId=' + counterId,
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Counter remove error ' + textStatus + ' ' + errorThrown);
        },
        success: function(data, textStatus, jqXHR) {
            $('.counter-container-' + counterId).remove();
        }
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

    if(action == state || action < 0 || action > 2)
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