var MarkDateModal = {
    okClicked: function(){
        console.log(counterAddUrl);
        //console.log($('#mark-date-form').serialize());
        var action = this.getAction();
        var counterId = this.getCounterId();
        var date = this.getDate();

        console.log('action: ' + action);
        console.log('counter id: ' + counterId);

        //Validate action
        if(action < 0 || action > 2) {
            this.showError('Action is invalid');
            return;
        }

        //Validate date
        if(!this.validateDateInput())
            return;

        $.ajax({
            type:'GET',
            url: markUrl + '?action=' + action + '&counterId=' + counterId + '&date=' + date,
            error: function(jqXHR, textStatus, errorThrown) {
                var responseText = jqXHR.responseText;
                var start = responseText.indexOf(':');
                var message = responseText.substring(start + 1).trim();
                MarkDateModal.showError(message);
            },
            success: function(data, textStatus, jqXHR) {
                //console.log(this.url);
                //console.log('mark ajax success:' + textStatus);
                var cell = Snap.select("g.cell[counter='" + counterId + "']" + "[date='" + date + "'] rect");
                if(cell)
                    cell.animate({fill: action == markNone ? colorNone : action == markDone ? colorDone : colorMiss}, 400);
                
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
    },
    /**
     * Gets the action that is selected on the form.
     * @returns an integer: 1 = Mark done, 2 = Mark miss, 0 = Clear
     */
    getAction: function() {
        return $('#mark-date-modal-action').val();
    },

    /**
     * Displays an error message.
     */
    showError: function(msg) {
        var alert = $('#mark-date-modal .alert-danger').text(msg).show();
        console.log(msg);
    },
    clearError: function() {
        var alert = $('#mark-date-modal .alert-danger').text('').hide();
    },
    /**
     * Validate the value of the date input.
     * returns true if successful, false otherwise.
     */
    validateDateInput: function() {
        if(!$('#mark-date-modal-date').val().trim()) {
            this.showError('Please enter a date.');
            return false;
        }

        return true;
    },
    setCounterId: function(counterId) {
        $('#mark-date-modal').attr('counter-id', counterId);
    },
    getCounterId: function() {
        return $('#mark-date-modal').attr('counter-id');
    },
    getDate: function() {
        return $('#mark-date-modal-date').val().trim();
    },
    /**
     * Show the modal dialog.
     */
    show: function() {
        $('#mark-date-modal').modal('show');
    }
};

$(function(){
    //CounterForm.initDatePicker();
    $('#mark-date-modal .modal-footer button').click(function(){MarkDateModal.okClicked();});
});

MarkDateModal.initDatePicker = function() {
    $(dateInputSel).datepicker({dateFormat: 'MM d, yy'});
    var now = new Date();
    $(dateInputSel).val(monthNames[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear());
}

MarkDateModal.setStartDate = function(dateStr) {
    var date = new Date(dateStr);
    $(dateInputSel).datepicker('setDate', date);
}