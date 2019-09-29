function mod_bookingcal_ajax(offset, year, month)
{
    var url = '?option=com_ajax&module=booking_calendar&format=raw&offset='+offset+'&year='+year+'&month='+month;
        
    jQuery.ajax({
        url: url,
        dataType: "text",
        type: "GET",
        success: function(responseText, status, xhr) {jQuery('.mod_booking_calendar_outer').html(responseText);},
        error: function(xhr, status, error) {jQuery('.mod_booking_calendar_outer').html(error);}
        });        
}

