$(function() {
    var sortable = Sortable.create(
        $('.counters-sortable').get(0),
        {
            onUpdate: function(event) {
                console.log('onUpdate');
                console.log(event);
                var counterId = $(event.item).attr('counterid');

                $.ajax({
                    type: 'GET',
                    url: updateOrderUrl,
                    data: {'counterId':counterId, 'oldIndex':event.oldIndex, 'newIndex':event.newIndex},
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Update display order error ' + textStatus + ' ' + errorThrown);
                    },
                    success: function(data, textStatus, jqXHR) {
                        console.log('Update display order success ' + data);     
                    }
                });
            }
        }
    );
});