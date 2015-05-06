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

var markError = -1;
var markNone = 0;
var markDone = 1;
var markMiss = 2;

var colorDone = '#d6e685';
var colorNone  = '#e3e3e3';
//var colorStart = '#bee685';
var colorMiss = '#e6c785';

var counterModalId = '#add-counter-modal';

function counterSettingClicked(counterId) {
    console.log('counter setting clicked ' + counterId);
    $.ajax({
        type:'GET',
        dataType:'json',
        url: counterDataUrl + '?counterId=' + counterId,
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('Counter data error ' + textStatus + ' ' + errorThrown);
        },
        success: function(data, textStatus, jqXHR) {
            console.log('Counter data success ' + data);
            CounterForm.setMode('update');
            CounterForm.setTitle('Counter Settings');
            CounterForm.setCounterId(data['counterId']);
            CounterForm.setLabel(data['label']);
            CounterForm.setSummary(data['summary']);
            CounterForm.setType(data['type']);
            CounterForm.setEvery(data['every']);
            CounterForm.setWeekdays(data['on'] == null ? [] : data['on'].split(','));
            CounterForm.setStartDate(data['startDate']);
            CounterForm.setTimeZone(data['timeZone']['timezone']);
            CounterForm.setPublic(data['public']);
            CounterForm.show();
        }
    });
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
                $('#counter-container-' + counterId).fadeOut(400, function(){this.remove();});
            }
        });

        $('#remove-confirm-modal').modal('hide');
    });
}