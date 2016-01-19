var ResetModal = {
    okClicked: function(){
        var counterId = this.getCounterId();
        var resetDate = this.getResetDate();

        console.log('counter id: ' + counterId);

        //Validate date
        if(!this.validateDateInput())
            return;

        $.ajax({
            type:'GET',
            url: resetUrl,
            data: {counterId: counterId, resetDate: resetDate},
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Counter reset ajax error');
                var responseText = jqXHR.responseText;
                var start = responseText.indexOf(':');
                var message = responseText.substring(start + 1).trim();
                ResetModal.showError(message);
            },
            success: function(data, textStatus, jqXHR) {
                ResetModal.hide();
                
                console.log(data);

                var counter = '#counter-container-' + counterId;
                //Set current count
                $(counter + ' .current-count').text(data.count.count);
                //Set since
                $(counter + ' .since').text('since ' + new Date(data.count.startDate.date).toString('MMM d, yyyy'));
                //Set best count
                $(counter + ' .best-count').text(data.best.count);
                //Set best range
                $(counter + ' .best-range').text(new Date(data.best.startDate.date).toString('MMM d, yyyy') + ' - ' +
                    new Date(data.best.endDate.date).toString('MMM d, yyyy'));
            }
        });
    },
    /**
     * Displays an error message.
     */
    showError: function(msg) {
        var alert = $('#reset-modal .alert-danger').text(msg).show();
        console.log(msg);
    },
    clearError: function() {
        var alert = $('#reset-modal .alert-danger').text('').hide();
    },
    /**
     * Validate the value of the date input.
     * returns true if successful, false otherwise.
     */
    validateDateInput: function() {
        if(!$('#reset-modal-date').val().trim()) {
            this.showError('Please enter a date.');
            return false;
        }

        return true;
    },
    setCounterId: function(counterId) {
        $('#reset-modal').attr('counter-id', counterId);
    },
    getCounterId: function() {
        return $('#reset-modal').attr('counter-id');
    },
    getResetDate: function() {
        return $('#reset-modal-date').val().trim();
    },
    /**
     * Show the modal dialog.
     */
    show: function() {
        $('#reset-modal').modal('show');
    },
    hide: function() {
        $('#reset-modal').modal('hide');
    },
    setResetDate: function(dateStr) {
        var date = new Date(dateStr);
        $('#reset-modal-date').datepicker('setDate', date);
    },
    initDatePicker: function() {
        $('#reset-modal-date').datepicker({dateFormat: 'MM d, yy'});
        var now = new Date();
        $('#reset-modal-date').val(now.toString('MMMM') + ' ' + now.getDate() + ', ' + now.getFullYear());
    }
};

$(function(){
    ResetModal.initDatePicker();
    $('#reset-modal .modal-footer button').click(function(){ResetModal.okClicked();});
});


