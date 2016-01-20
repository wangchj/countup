var StopModal = {
    okClicked: function(){
        var counterId = this.getCounterId();
        var stopDate = this.getStopDate();

        console.log('counter id: ' + counterId);

        //Validate date
        if(!this.validateDateInput())
            return;

        $.ajax({
            type:'GET',
            url: stopUrl,
            data: {counterId: counterId, stopDate: stopDate},
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Counter stop ajax error');
                var responseText = jqXHR.responseText;
                var start = responseText.indexOf(':');
                var message = responseText.substring(start + 1).trim();
                StopModal.showError(message);
            },
            success: function(data, textStatus, jqXHR) {
                StopModal.hide();
                
                console.log(data);

                var counter = '#counter-container-' + counterId;
                //Set current count
                $(counter + ' .current-count').text(data.count.count);
                //Set since
                $(counter + ' .since').text('not running');
                
                if(data.best) {
                    //Set best count
                    $(counter + ' .best-count').text(data.best.count);
                    //Set best range
                    $(counter + ' .best-range').text(new Date(data.best.startDate.date).toString('MMM d, yyyy') + ' - '
                        + new Date(data.best.endDate.date).toString('MMM d, yyyy'));
                }
                else {
                    $(counter + ' .best-count').text(0);
                    $(counter + ' .best-range').text('');
                }
            }
        });
    },
    /**
     * Displays an error message.
     */
    showError: function(msg) {
        var alert = $('#stop-modal .alert-danger').text(msg).show();
        console.log(msg);
    },
    clearError: function() {
        var alert = $('#stop-modal .alert-danger').text('').hide();
    },
    /**
     * Validate the value of the date input.
     * returns true if successful, false otherwise.
     */
    validateDateInput: function() {
        if(!$('#stop-modal-date').val().trim()) {
            this.showError('Please enter a date.');
            return false;
        }

        return true;
    },
    setCounterId: function(counterId) {
        $('#stop-modal').attr('counter-id', counterId);
    },
    getCounterId: function() {
        return $('#stop-modal').attr('counter-id');
    },
    getStopDate: function() {
        return $('#stop-modal-date').val().trim();
    },
    /**
     * Show the modal dialog.
     */
    show: function() {
        $('#stop-modal').modal('show');
    },
    hide: function() {
        $('#stop-modal').modal('hide');
    },
    setStopDate: function(dateStr) {
        var date = new Date(dateStr);
        $('#stop-modal-date').datepicker('setDate', date);
    },
    initDatePicker: function() {
        $('#stop-modal-date').datepicker({dateFormat: 'MM d, yy'});
        var now = new Date();
        $('#stop-modal-date').val(now.toString('MMMM') + ' ' + now.getDate() + ', ' + now.getFullYear());
    }
};

$(function(){
    StopModal.initDatePicker();
    $('#stop-modal .modal-footer button').click(function(){StopModal.okClicked();});
});


