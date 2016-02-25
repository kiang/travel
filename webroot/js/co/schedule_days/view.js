function scheduleDaysView() {
    $('a.scheduleLineMove').click(function() {
        var targetDay = $('select#schedule_day_id').val();
        var lines = [];
        $('input.scheduleLineBox').each(function() {
            if(true === this.checked) {
                lines.push($(this).attr('data-id'));
            }
        });
        if(lines.length > 0) {
            var formTarget = wwwRoot + 'schedule_days/move_lines/' + dayViewInfo.schedule_id + '/' + dayViewInfo.id + '/' + targetDay;
            $.post(formTarget, {
                data: lines
            }, function() {
                document.location.href = wwwRoot + 'schedules/view/' + dayViewInfo.schedule_id + '/' + dayViewInfo.id;
            });
        }
        return false;
    });
    $('a.scheduleLineRemove').click(function() {
        var lines = [];
        $('input.scheduleLineBox').each(function() {
            if(true === this.checked) {
                lines.push($(this).attr('data-id'));
            }
        });
        if(lines.length > 0) {
            var formTarget = wwwRoot + 'schedule_days/remove_lines/' + dayViewInfo.schedule_id + '/' + dayViewInfo.id;
            $.post(formTarget, {
                data: lines
            }, function() {
                document.location.href = wwwRoot + 'schedules/view/' + dayViewInfo.schedule_id + '/' + dayViewInfo.id;
            });
        }
        return false;
    });
    $('#scheduleDayListButton').click(function() {
        charIndex = 1;
        $(this).addClass('current');
        $('#scheduleDayTableButton').removeClass('current');
        $('#scheduleDayList').show();
        $('#scheduleDayTable').hide();
        pointsToMap(dayListPoints);
    }).trigger('click');
    $('#scheduleDayTableButton').click(function() {
        charIndex = 1;
        $(this).addClass('current');
        $('#scheduleDayListButton').removeClass('current');
        $('#scheduleDayTable').show();
        $('#scheduleDayList').hide();
        pointsToMap(dayTablePoints);
    });
    $('a.dialogSchedule').click(function() {
        dialogFull(this);
        return false;
    });
    $('a.scheduleDayNotesDelete').click(function() {
        $.get(this.href, {}, function(result) {
            if(result === 'ok') {
                $('select#ScheduleDayId').trigger('change');
            }
        })
        return false;
    });
};