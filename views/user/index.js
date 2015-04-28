/*asdfas*/
$(function(){
    initCounterSettingsPopup();
    //initEvents();
});

function initCounterSettingsPopup() {
    $('.counter-setting').popover({
        content:   counterSettingMenu,
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

function resetClicked(counterId) {
    console.log(counterId);
}

function stopClicked(counterId) {

}

function removeClicked(counterId) {
    
}

function counterSettingMenu() {
    return $(this).siblings('ul').html();
}

function markClicked(action, counterId, date) {
    console.log('markClicked: ' + counterId + ', ' + date);
    var state = getCellState(counterId, date);
    console.log(state);

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
        }
    });
}

function getCellState(counterId, date) {
    var cell = $("g.cell[counter='" + counterId + "']" + "[date='" + date + "']");
    var rect = cell.find('rect');
    var color = rect.attr('fill');

    console.log(cell);

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