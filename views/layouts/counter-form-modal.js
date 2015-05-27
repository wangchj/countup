var CounterForm = {};

$(function(){
    CounterForm.initTypeClick();
    CounterForm.initWeekdayClick();
    CounterForm.initDatePicker();
    CounterForm.initBtnClick();
});

CounterForm.initTypeClick = function() {
    $('#add-counter-modal li.type-select-item').click(function(){
        var selectId = $(this).attr('id');

        if(selectId == 'type-select-item-daily') {
            CounterForm.setType('daily');
        }
        else if(selectId == 'type-select-item-weekly') {
            CounterForm.setType('weekly');
        }
    });
}

CounterForm.initWeekdayClick = function() {
    $('#add-counter-modal #weekly-day-select li').click(function() {
        CounterForm.toggleWeekday($(this).text());
    });
}

CounterForm.resetForAdd = function() {
    this.setMode('add');
    this.setTitle('New Counter')
    this.setLabel('');
    this.setSummary('');
    this.setType('daily');
    this.setEvery('1');
    this.setWeekdays(['mon']);
    this.setPublic(true);
    $('#new-counter-more').collapse('hide');
}

CounterForm.initBtnClick = function() {
    $('#add-counter-modal .modal-footer button').click(function() {
        console.log(counterAddUrl);
        console.log($('#counter-form').serialize());
        var mode = CounterForm.getMode();

        if(mode == 'add') {
            $.ajax({
                type: 'POST',
                url: counterAddUrl,
                data: $('#counter-form').serialize(),
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Counter add ' + textStatus + ' ' + errorThrown);
                },
                success: function(data, textStatus, jqXHR) {
                    $('#add-counter-modal').modal('hide');
                    console.log(data);
                    location.reload();
                }
            });
        }
        else if(mode == 'update') {
            console.log($('#counter-form').serialize());
            $.ajax({
                type: 'POST',
                url: counterUpdateUrl,
                data: $('#counter-form').serialize(),
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Counter update error ' + textStatus + ' ' + errorThrown);
                },
                success: function(data, textStatus, jqXHR) {
                    $('#add-counter-modal').modal('hide');
                    console.log('counter update success');
                    //location.reload();
                }
            });
        }
    });
}

CounterForm.setCounterId = function(counterId) {
    $('#add-counter-modal input#counter-counterid').val(counterId);
}

CounterForm.setLabel = function(text) {
    $('#add-counter-modal input#counter-label').val(text);
}

CounterForm.setSummary = function(text) {
    $('#add-counter-modal textarea#counter-summary').val(text);
}

/**
 * Type is 'daily' or 'weekly'.
 */
CounterForm.setType = function(type) {
    $('#add-counter-modal li.type-select-item').removeClass('active');

    if(type.toLowerCase() == 'daily') {
        $('#add-counter-modal input#counter-type').val('daily');
        $('#add-counter-modal #type-select-item-daily').addClass('active');
        $('#add-counter-modal #every-label').text('day');
        $('#add-counter-modal #weekly-day-select').css('display', 'none');
    }
    else {
        $('#add-counter-modal input#counter-type').val('weekly');
        $('#add-counter-modal #type-select-item-weekly').addClass('active');
        $('#add-counter-modal #every-label').text('week');
        $('#add-counter-modal #weekly-day-select').css('display', '');
    }
}

/**
 * Gets the type that is current selected.
 * @return 'daily' or 'weekly'
 */
CounterForm.getType = function() {
    if($('#add-counter-modal li#type-select-item-daily').hasClass('active'))
        return 'daily';
    if($('#add-counter-modal li#type-select-item-weekly').hasClass('active'))
        return 'weekly';
}

/**
 * Sets the value of the 'every' input field.
 * $param period is an integer. For example when type is 'daily' and period is 2, this mean 2 days.
 *        this also apply to weeks. If this parameter is not numeric, no effect.
 */
CounterForm.setEvery = function(period) {
    if(!$.isNumeric(period) ||
        period < 1 ||
        (this.getType() == 'daily' && period > 7)
    )
        return;
    $('#add-counter-modal input#counter-every').val(Math.floor(period));
}

/**
 * Sets which days are selected on the form.
 * @param weekdays array containing;
 *     'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'
 */
CounterForm.setWeekdays = function(weekdays) {
    $('#add-counter-modal #weekly-day-select input').val(0);
    $('#add-counter-modal #weekly-day-select li').removeClass('active');

    for(i = 0; i < weekdays.length; i++) {
        this.addWeekday(weekdays[i]);
    }
}

/**
 * Adds a weekday to selections.
 * @param day is a string which should be one of: 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'
 */
CounterForm.addWeekday = function(day) {
    var num   = this.weekdayNum(day);
    var input = '#add-counter-modal #weekly-day-select input:eq(' + (num - 1) + ')';
    var li    = '#add-counter-modal #weekly-day-select li:nth-child(' + num + ')';
    $(input).val(1);
    $(li).addClass('active');
}

CounterForm.toggleWeekday = function(day) {
    var num   = this.weekdayNum(day);
    var input = '#add-counter-modal #weekly-day-select input:eq(' + (num - 1) + ')';
    var li    = '#add-counter-modal #weekly-day-select li:nth-child(' + num + ')';
    $(input).val($(input).val() == 0 ? 1 : 0);
    $(li).toggleClass('active');
}

/**
 * Return integer representation of a weekday where mon = 1, tue = 2, ..., sun = 7.
 * If name is not valid, undefined is returned.
 */
CounterForm.weekdayNum = function(name) {
    switch(name.toLowerCase()) {
        case 'mon': return 1;
        case 'tue': return 2;
        case 'wed': return 3;
        case 'thu': return 4;
        case 'fri': return 5;
        case 'sat': return 6;
        case 'sun': return 7;
    }
}

CounterForm.setPublic = function(public) {
    $('#add-counter-modal input#counter-public').prop('checked', public);
}

/**
 * mode should be 'add' or 'update'
 */
CounterForm.setMode = function(mode) {
    $('#add-counter-modal').attr('mode', mode.toLowerCase());
}

/**
 * Returns either 'add' or 'update'
 */
CounterForm.getMode = function() {
    return $('#add-counter-modal').attr('mode');
}

CounterForm.setTitle = function(title) {
    $('#add-counter-modal .modal-title').html('<b>' + title + '</b>');
}

var dateInputSel = '#counter-startdate';

CounterForm.initDatePicker = function() {
    $(dateInputSel).datepicker({dateFormat: 'MM d, yy'});
    var now = new Date();
    $(dateInputSel).val(Date.CultureInfo.monthNames[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear());
}

CounterForm.setStartDate = function(dateStr) {
    var date = new Date(dateStr);
    $(dateInputSel).datepicker('setDate', date);
}

CounterForm.setTimeZone = function(name) {
    $('#add-counter-modal select#counter-timezone option').prop('selected', false);
    $('#add-counter-modal select#counter-timezone option[value="' + name + '"]').prop('selected', true);
}

/**
 * Shows the counter form modal.
 */
CounterForm.show = function(name) {
    $('#add-counter-modal').modal('show');
}