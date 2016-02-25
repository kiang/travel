var activityTarget = '';
var transportTarget = '';
function schedulesAdd() {
    var lineCount = 0;
    function addLine() {
        ++lineCount;
        var clonedItem = $('#lineTemplate').tmpl([
        {
            lineSort: lineCount
        }
        ]);
        if(lineCount < 10) {
            $('.olcSprite', clonedItem).addClass('m0' + lineCount);
        } else {
            $('.olcSprite', clonedItem).addClass('m' + lineCount);
        }
        regLineDelete(clonedItem);
        $('a.lineLatLon', clonedItem).click(function() {
            var target = $(this).parent().parent().parent();
            findLatLng($('input.lineLat', target), $('input.lineLon', target));
            return false;
        });
        $('a.activityBtn', clonedItem).click(function() {
            activityTarget = $(this).attr('data-target');
            return false;
        });
        $('a.transportBtn', clonedItem).click(function() {
            transportTarget = $(this).attr('data-target');
            return false;
        });
        $('input.timepick', clonedItem).timepicker({
            timeOnly:true
        });
        $('input.linePoint', clonedItem).autocomplete({
            source: wwwRoot + 'points/auto_list/',
            select: function(event, ui) {
                var target = $(this).parent().parent().parent();
                $('input.linePointId', target).val(ui.item.id);
                $('input.lineLat', target).val(ui.item.latitude);
                $('input.lineLon', target).val(ui.item.longitude);
            }
        });
        $('a.lightbox_page', clonedItem).click(function() {
            dialogFull(this);
            return false;
        });
        $('.hasPopover', clonedItem).popover({
            trigger: 'hover'
        });
        clonedItem.appendTo('div#lineBlock');
        focusTo(clonedItem);
        
        $('div#lineBlock').sortable({
            handle: 'a.dbtn_move',
            update: function() {
                var newSort = 0;
                $('input.lineSort', this).each(function() {
                    ++newSort;
                    $(this).val(newSort);
                });
            }
        });
    }
    
    function regLineDelete(target) {
        $('.dbtn_delete', target).click(function() {
            if($('.table').filter('.list3').length > 2) {
                $(this).parent().parent().remove();
            }
            return false;
        });
    }
    
    $('a.dbtnAdvanced').click(function() {
        $(this).hide();
        $('div.advancedForm').show();
        return false;
    });
    
    $('a.transportBtn').click(function() {
        transportTarget = $(this).attr('data-target');
        return false;
    });
    $('a.lightbox_page').click(function() {
        dialogFull(this);
        return false;
    });
    $('a.addLine').click(function() {
        addLine();
        return false;
    });
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
    regLineDelete();
    $('input#ScheduleTimeStart').datetimepicker();
        
    $('#SchedulePointText').autocomplete({
        source: wwwRoot + 'points/auto_list/',
        select: function(event, ui) {
            $('#SchedulePointId').val(ui.item.id);
            $('#ScheduleLatitude').val(ui.item.latitude);
            $('#ScheduleLongitude').val(ui.item.longitude);
        }
    });
    $('#ScheduleDayPointName').autocomplete({
        source: wwwRoot + 'points/auto_list/',
        select: function(event, ui) {
            $('#ScheduleDayPointId').val(ui.item.id);
            $('#ScheduleDayLatitude').val(ui.item.latitude);
            $('#ScheduleDayLongitude').val(ui.item.longitude);
        }
    });
        
    $('#scheduleLatLng').click(function() {
        findLatLng($('#ScheduleLatitude'), $('#ScheduleLongitude'));
        return false;
    });
    $('#scheduleDayLatLng').click(function() {
        findLatLng($('#ScheduleDayLatitude'), $('#ScheduleDayLongitude'));
        return false;
    });
    $('input.timepick').timepicker({
        timeOnly:true
    });
    addLine();
    $('.hasPopover').popover({
        trigger: 'hover'
    });
}