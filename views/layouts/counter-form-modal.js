var CounterForm = {};

$(function(){
    CounterForm.initDatePicker();
    CounterForm.initBtnClick();
});


CounterForm.resetForAdd = function() {
    this.setMode('add');
    this.setTitle('New Counter')
    this.setLabel('');
    this.setSummary('');
    this.setPublic(true);
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