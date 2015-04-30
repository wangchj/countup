$(function(){
    initModalEvent();
    initTypeSelect();
    initWeekdaySelect();
    initDatePicker();
    initIsPublicEvent();
    initBtnEvent();
});

function initModalEvent() {
    $('#add-counter-modal').on('show.bs.modal', function() {
        resetInputFields();
    });
}

function initTypeSelect() {
    $('#add-counter-modal li.type-select-item').click(function(){
        var selectId = $(this).attr('id');
        
        $('#add-counter-modal li.type-select-item').removeClass('active');
        $(this).addClass('active');

        if(selectId == 'type-select-item-daily') {
            $('#add-counter-modal input#counter-type').val('daily');
            $('#add-counter-modal #every-label').text('day');
            $('#add-counter-modal #weekly-day-select').css('display', 'none');
        }
        else if(selectId == 'type-select-item-weekly') {
            $('#add-counter-modal input#counter-type').val('weekly');
            $('#every-label').text('week');
            $('#add-counter-modal #weekly-day-select').css('display', '');
        }
    });
}

function initWeekdaySelect() {
    $('#add-counter-modal #weekly-day-select li').click(function() {
        $(this).toggleClass('active');
        var day = $(this).find('a').text();
        var on = $(this).hasClass('active') ? 1 : 0;
        if(day == 'Mon')
            $('#add-counter-modal #weekly-day-select input#day-mon').val(on);
        else if(day == 'Tue')
            $('#add-counter-modal #weekly-day-select input#day-tue').val(on);
        else if(day == 'Wed')
            $('#add-counter-modal #weekly-day-select input#day-wed').val(on);
        else if(day == 'Thu')
            $('#add-counter-modal #weekly-day-select input#day-thu').val(on);
        else if(day == 'Fri')
            $('#add-counter-modal #weekly-day-select input#day-fri').val(on);
        else if(day == 'Sat')
            $('#add-counter-modal #weekly-day-select input#day-sat').val(on);
        else if(day == 'Sun')
            $('#add-counter-modal #weekly-day-select input#day-sun').val(on);
    });
}

function resetInputFields() {
    resetLabelAndSummary();
    resetTypeSelect();
    resetWeekdaySelect();
    resetIsPublic();
    $('#new-counter-more').collapse('hide');
}

function resetLabelAndSummary() {
    $('#add-counter-modal input#counter-label').val('');
    $('#add-counter-modal textarea#counter-summary').val('');
}

function resetTypeSelect() {
    $('#add-counter-modal input#counter-type').val('daily');
    $('#add-counter-modal li.type-select-item').removeClass('active');
    $('#add-counter-modal #type-select-item-daily').addClass('active');
    $('#add-counter-modal #every-label').text('day');
    $('#add-counter-modal input#counter-every').val(1);
    $('#add-counter-modal #weekly-day-select').css('display', 'none');
}

function resetWeekdaySelect() {
    $('#add-counter-modal #weekly-day-select li').removeClass('active').first().addClass('active');
    $('#add-counter-modal #weekly-day-select input').val(0).first().val(1);
}

function resetIsPublic() {
    $('#add-counter-modal input#counter-public').val(1);
    $('#add-counter-modal input#counter-public').prop('checked', true);
}

function initDatePicker() {
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    $('#counter-startdate').datepicker({dateFormat: 'MM d, yy'});
    var now = new Date();
    $('#counter-startdate').val(monthNames[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear());
}

function initIsPublicEvent() {
    $('#add-counter-modal #counter-public').click(function() {
        if($(this).val() == 0)
            $(this).val(1);
        else
            $(this).val(0);
    });
}

function initBtnEvent() {
    $('#add-counter-modal .modal-footer button').click(function() {
        console.log(counterAddUrl);
        console.log($('#counter-form').serialize());
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
    });
}