var activityTarget = '';
var transportTarget = '';
var lineCount = 0;

function addLine(obj) {
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
    $('a.dbtn_add', clonedItem).click(function() {
        addLine($(this).parents('dl.dTable'));
        return false;
    });
    $('.hasPopover', clonedItem).popover({
        trigger: 'hover'
    });
    obj.after(clonedItem);
    var newSort = 0;
    $('input.lineSort').each(function() {
        ++newSort;
        $(this).val(newSort);
    });
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
        if($('.dTable').filter('.list3').length > 2) {
            $(this).parent().parent().remove();
        }
        return false;
    });
}
function scheduleDaysAdd() {
    $('a.transportBtn').click(function() {
        transportTarget = $(this).attr('data-target');
        return false;
    });
    $('a.activityBtn').click(function() {
        activityTarget = $(this).attr('data-target');
        return false;
    });
    $('a.lightbox_page').click(function() {
        dialogFull(this);
        return false;
    });
    $('a.lineLatLon').click(function() {
        var target = $(this).parent().parent().parent();
        findLatLng($('input.lineLat', target), $('input.lineLon', target));
        return false;
    });
        
    $('a.dbtn_add').click(function() {
        addLine($(this).parents('dl.dTable'));
        return false;
    }).trigger('click');
    $('a.dbtnSubmit').click(function() {
        $('form#ScheduleDayAddForm').submit();
    });
    regLineDelete();
    $('input#ScheduleTimeStart').datetimepicker();
        
    $('#ScheduleDayPointName').autocomplete({
        source: wwwRoot + 'points/auto_list/',
        select: function(event, ui) {
            $('#ScheduleDayPointId').val(ui.item.id);
            $('#ScheduleDayLatitude').val(ui.item.latitude);
            $('#ScheduleDayLongitude').val(ui.item.longitude);
        }
    });
        
    $('#scheduleDayLatLng').click(function() {
        findLatLng($('#ScheduleDayLatitude'), $('#ScheduleDayLongitude'));
        return false;
    });
    $('input.timepick').timepicker({
        timeOnly:true
    });
    $('input.linePoint').autocomplete({
        source: wwwRoot + 'points/auto_list/',
        select: function(event, ui) {
            var target = $(this).parent().parent().parent();
            $('input.linePointId', target).val(ui.item.id);
            $('input.lineLat', target).val(ui.item.latitude);
            $('input.lineLon', target).val(ui.item.longitude);
        }
    });
    $('.hasPopover').popover({
        trigger: 'hover'
    });
};