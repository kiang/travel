function schedulesEdit() {
    $('a.dbtnSubmit').click(function() {
        $('input#ScheduleIsDraft').val('0');
        $(this).parents('form').submit();
        return false;
    });
    $('a.dbtnDraft').click(function() {
        $('input#ScheduleIsDraft').val('1');
        $(this).parents('form').submit();
        return false;
    });
    $('input#ScheduleTimeStart').datetimepicker();
        
    $('#SchedulePointText').autocomplete({
        source: wwwRoot + 'points/auto_list/',
        open: function() {
            $(this).autocomplete('widget').css('z-index', 2000);
            return false;
        },
        select: function(event, ui) {
            $('#SchedulePointId').val(ui.item.id);
            $('#ScheduleLatitude').val(ui.item.latitude);
            $('#ScheduleLongitude').val(ui.item.longitude);
        }
    });
        
    $('#scheduleLatLng').click(function() {
        findLatLng($('#ScheduleLatitude'), $('#ScheduleLongitude'));
        return false;
    });
    $('input.timepick').timepicker({
        timeOnly:true
    });
    $('.hasPopover').popover({
        trigger: 'hover'
    });
}