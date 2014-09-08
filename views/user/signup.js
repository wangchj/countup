$(function(){
    $('#timezone-image').timezonePicker({
        maphilight:true,
        pin:null,
        //target: '#edit-date-default-timezone',
        //countryTarget: '#edit-site-default-country',
        fillColor:'FFFFFF',
        //alwaysOn:true
    });

    //Name of selected timezone
    var timezone = '';

    $('#timezone-map area').click(function(e){
        if($(this).attr('data-timezone') != timezone)
        {
            timezone = $(this).data('timezone');
            offset = $(this).data('offset');
            $('#zone-label').text(timezone + ' UTC' + (offset >= 0 ? '+' : '') + offset);

            //Update hidden field
            $('#user-timezone').val(timezone);

            //Update zone highlight
            $('#timezone-map area').data('maphilight',{});
            $(this).data('maphilight', {'alwaysOn':true}).trigger('alwaysOn.maphilight');
        }
    });

    //If timezone is already set, update map
    if($('#user-timezone').val() != null && $('#user-timezone').val() != '')
    {
        timezone = $('#user-timezone').val();
        //Get offset
        offset = $('#timezone-map area[data-timezone=\'' + timezone + '\']').data('offset');
        //update label
        $('#zone-label').text(timezone + ' UTC' + (offset >= 0 ? '+' : '') + offset);
        //update map
        $('#timezone-map area[data-timezone=\'' + timezone + '\']').data('maphilight', {'alwaysOn':true}).trigger('alwaysOn.maphilight');
    }
});