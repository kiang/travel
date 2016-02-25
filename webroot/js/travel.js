var viewingSchedule = 0;
var viewingScheduleDay = 0;
var selectedScheduleDay = 0;

function wideDialog(url) {
    if ($('#wideDialog').length == 0) {
        $('body').append('<div id="wideDialog"></div>');
        $('#wideDialog').load(url).dialog( {
            title : '請確認',
            width : 800
        });
    } else {
        $('#wideDialog').load(url, null, function() {
            $(this).dialog('open');
        });
    }
}

function dialogFull(linkObject, title) {
    if ($('#dialogFull').length == 0) {
        $('body').append('<div id="dialogFull"></div>');
        $('#dialogFull').dialog( {
            autoOpen : false,
            width : 950
        });
    }
    if (typeof title == 'undefined') {
        if (typeof linkObject.rel == 'undefined') {
            title = '操作';
        } else {
            title = linkObject.rel;
        }
    }
    $('#dialogFull').load(linkObject.href, null, function() {
        $(this).dialog('option', 'title', title).dialog('open');
    });
}

function dialogHalf(linkObject, title) {
    if ($('#dialogHalf').length == 0) {
        $('body').append('<div id="dialogHalf"></div>');
        $('#dialogHalf').dialog( {
            autoOpen : false,
            width : 470
        });
    }
    if (typeof title == 'undefined') {
        if (typeof linkObject.rel == 'undefined') {
            title = '操作';
        } else {
            title = linkObject.rel;
        }
    }
    $('#dialogHalf').load(linkObject.href, null, function() {
        $(this).dialog('option', 'title', title).dialog('open');
    });
}

function stop(event) {
    if (event.stopPropagation) {
        event.stopPropagation();
    }
}

function focusTo(obj) {
    var body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
    body.animate({
        scrollTop: obj.offset().top
    }, 0);
}

$.datepicker.setDefaults({
    dateFormat: 'yy-mm-dd'
});
$.timepicker.setDefaults({
    dateFormat: 'yy-mm-dd'
});