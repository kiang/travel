var placeServiceOptions = {
    bounds: new google.maps.LatLngBounds,
    iconPoint1: new google.maps.Point(0, 0),
    iconPoint2: new google.maps.Point(17, 34),
    iconPoint3: new google.maps.Point(40, 0),
    iconSize: new google.maps.Size(71, 71),
    iconSizeTarget: new google.maps.Size(34, 34),
    icon2034: new google.maps.Size(20, 34)
};
var placeServiceMarkers = [];
var pointsNearMarkers = [];
var placeService;
var lastLineId = 0;
function schedulesMapMode() {
    var gmapInstance = $('div#map_canvas');
    gmapInstance.gmap({
        zoom: 8,
        center: new google.maps.LatLng(23.00732,120.218329),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        mapTypeControl: false
    });
    var pointCount = 0;
    var gmapObj = gmapInstance.gmap('get', 'map');
    var dayLastSort = count(scheduleDays);
    gmapObj.mapTypes.set('styledJustPlayMap', styledJustPlayMap);
    gmapObj.setMapTypeId('styledJustPlayMap');
    placeService = new google.maps.places.PlacesService(gmapObj);
    $('#dayListOptions').tmpl({
        scheduleDays: scheduleDays
    }).insertBefore('div.scheduleLineList');
    $('select.scheduleDayList').val(scheduleDayId).change(scheduleDayListChange).trigger('change');
    $('a.addressBarGo').click(addressBarGoClick);
        
    google.maps.event.addListener(gmapObj, 'rightclick', function(e){
        $('input.addressBar').val(e.latLng);
        $('a.addressBarGo').trigger('click');
    });
    google.maps.event.addListener(gmapObj, 'click', function(e){
        $('input.addressBar').val(e.latLng);
        $('a.addressBarGo').trigger('click');
    });
        
    $('.hasPopover').popover({
        trigger: 'hover'
    });
    
    $('a.btnTutorial').click(function() {
        $('#tutorial').tutorial();
    });
    
    $('a.showBounds').click(function() {
        gmapObj.fitBounds(currentBounds);
        return false;
    });
    
    $('input.addressBar').autocomplete({
        source: wwwRoot + 'points/auto_list/',
        select: function(event, ui) {
            if(ui.item.latitude != '' && ui.item.latitude != '0') {
                $('div#map_canvas').gmap('addMarker', {
                    position: new google.maps.LatLng(ui.item.latitude, ui.item.longitude),
                    icon: mapIcons[0],
                    draggable: false,
                    pointId: ui.item.id,
                    pointTitle: ui.item.label,
                    lineTitle: ui.item.label
                }, function(iMap, iMarker) {
                    if('undefined' != typeof(collectedMarkers[0])) {
                        collectedMarkers[0].setMap(null);
                    }
                    collectedMarkers[0] = iMarker;
                    google.maps.event.addListener(collectedMarkers[0], 'dragend', function(e){
                        var latLngString = e.latLng.toString();
                        $('input.addressBar').val(latLngString);
                        collectedMarkers[0].lineTitle = latLngString;
                        google.maps.event.trigger(collectedMarkers[0], 'click');
                    });
                    iMap.panTo(iMarker.getPosition());
                    if('' == iMarker.lineTitle) {
                        $('input.addressBar').val(iMarker.getPosition().toString());
                    } else {
                        $('input.addressBar').val(iMarker.lineTitle);
                    }
                    google.maps.event.addListener(collectedMarkers[0], 'click', function(){
                        var clickedPoint = this.getPosition();
                        var theTitle = iMarker.lineTitle.replace('\'', '\\\'');
                        var infoContent = $('#pointsNearBackMarkerWindow').tmpl({
                            wwwRoot: wwwRoot,
                            pointId: iMarker.pointId,
                            pointTitle: iMarker.lineTitle,
                            theTitle: theTitle,
                            lat: clickedPoint.lat(),
                            lng: clickedPoint.lng()
                        }).html();
                        iMap.panTo(clickedPoint);
                        $('div#map_canvas').gmap('openInfoWindow', {
                            content: infoContent
                        }, iMarker);
                    });
                    google.maps.event.trigger(collectedMarkers[0], 'click');
                });
            }
        }
    });
    
    $('div.scheduleLineList').sortable({
        update: function() {
            var lineStack = {};
            var lineSort = 1;
            var dayId = $('select.scheduleDayList').val();
            $('.scheduleLineButton', $(this)).each(function() {
                var lineId = $(this).attr('data-id');
                scheduleLines[dayId][lineId]['sort'] = lineStack[lineId] = lineSort;
                ++lineSort;
            });
            $.post(wwwRoot + 'schedule_days/sort_lines/' + scheduleId + '/' + dayId, lineStack, function(result) {
                if('ok' === result) {
                    $('select.scheduleDayList').trigger('change');
                }
            });
        }
    });
    
    function scheduleDayListChange() {
        collectedMarkers = [];
        var k = $(this).val();
        $('ul.scheduleLineList').html('<li class="divider"></li>');
        lastLineId = 0;
        if(k == 0) {
            ++dayLastSort;
            $.get(wwwRoot + 'schedule_days/add/' + scheduleId, [], function(result) {
                if(result.split(':')[0] !== 'ok') {
                    alert(result);
                } else {
                    var theKey = result.split(':')[1];
                    $('select.scheduleDayList option[value=0]').each(function() {
                        $(this).before('<option value="' + theKey + '">[ ' + dayLastSort + ' ]</option>');
                    });
                    scheduleDays[dayLastSort] = {
                        id: theKey,
                        point_id: 0,
                        point_name: '',
                        count_lines: 0,
                        latitude: 0,
                        longitude: 0,
                        sort: dayLastSort,
                        title: ''
                    };
                    scheduleDaysMap[theKey] = dayLastSort;
                    scheduleLines[theKey] = {};
                    $('select.scheduleDayList').val(theKey).trigger('change');
                }
            });
            return false;
        }
        $('a.backLink').attr('href', wwwRoot + 'schedules/view/' + scheduleId + '/' + k);
        $('a.editLink').attr('href', wwwRoot + 'schedule_days/edit/' + k);
        var mapObj = gmapInstance.gmap('get', 'map');
        currentBounds = new google.maps.LatLngBounds;
        var lastPoint = false;
        var scheduleLineListBox = '';
        var dayIndex = scheduleDaysMap[k];
        gmapInstance.gmap('clear', 'markers');
        gmapInstance.gmap('clear', 'overlays');
        gmapInstance.gmap('closeInfoWindow');
        pointCount = 0;
        var sortedLines = [];
        for(j in scheduleLines[k]) {
            sortedLines[scheduleLines[k][j]['sort']] = j;
        }
        sortedLines = ksort(sortedLines);
        if(sortedLines.length == 0) {
            if(scheduleDays[dayIndex]['point_name'] != '' && null != scheduleDays[dayIndex]['point_name']) {
                if(scheduleDays[dayIndex]['latitude'] != '' && scheduleDays[dayIndex]['latitude'] != 0) {
                    var point = new google.maps.LatLng(scheduleDays[dayIndex]['latitude'], scheduleDays[dayIndex]['longitude']);
                    currentBounds.extend(point);
                    if(pointCount > 0) {
                        gmapInstance.gmap('addShape', 'Polyline', {
                            map: mapObj,
                            path: [lastPoint, point]
                        });
                    }
                    gmapInstance.gmap('addMarker', {
                        position: point,
                        icon: mapIcons[0],
                        lineId: 'day' + scheduleDays[dayIndex]['id'],
                        pointId: scheduleDays[dayIndex]['point_id'],
                        lineTitle: scheduleDays[dayIndex]['point_name'],
                        draggable: true
                    }, scheduleDayListMarkerAdded);
                    scheduleLineListBox += '<a href="#" data-id="day' + k + '" class="scheduleLineButton scheduleLineBar">' + scheduleDays[dayIndex]['point_name'] + '</a>';
                } else {
                    scheduleLineListBox += '<div><a class="btn scheduleLineText scheduleLineBarShort">' + scheduleDays[dayIndex]['point_name'] + '</a><a href="#" data-id="day' + k + '" class="btn scheduleLineButton"><i class="icon-map-marker"></i></a></div>';
                }
            }
            if(mapObj.getCenter().lat() === 0) {
                mapObj.setCenter(new google.maps.LatLng(23.007404,120.218366));
            }
            $('a.scheduleLineButton').click(scheduleLineButtonClick);
        } else {
            for(i in sortedLines) {
                j = sortedLines[i];
                var scheduleLineLabel = scheduleLines[k][j]['point_name'];
                if(scheduleLines[k][j]['latitude'] != '' && scheduleLines[k][j]['latitude'] != 0) {
                    var point = new google.maps.LatLng(scheduleLines[k][j]['latitude'], scheduleLines[k][j]['longitude']);
                    ++pointCount;
                    currentBounds.extend(point);
                    gmapInstance.gmap('addMarker', {
                        position: point,
                        icon: mapIcons[pointCount],
                        lineId: scheduleLines[k][j]['id'],
                        pointId: scheduleLines[k][j]['foreign_key'],
                        lineTitle: scheduleLines[k][j]['point_name'],
                        draggable: true
                    }, scheduleDayListMarkerAdded);
                    if(pointCount > 1) {
                        gmapInstance.gmap('addShape', 'Polyline', {
                            map: mapObj,
                            path: [lastPoint, point]
                        });
                    }
                    lastPoint = point;
                    scheduleLineLabel = '[' + pointCount + '] ' + scheduleLineLabel;
                    scheduleLineListBox += '<div><a class="scheduleLineButton btn scheduleLineBarLong" data-id="' + scheduleLines[k][j]['id'] + '">' + scheduleLineLabel + '</a></div>';
                } else {
                    scheduleLineListBox += '<div><a class="btn scheduleLineText scheduleLineBarShort">' + scheduleLineLabel + '</a><a href="#" data-id="' + scheduleLines[k][j]['id'] + '" class="btn scheduleLineButton"><i class="icon-map-marker"></i></a></div>';
                }
            }
            if(scheduleDays[dayIndex]['point_name'] != '' && null != scheduleDays[dayIndex]['point_name']) {
                scheduleLineListBox += '<hr />';
                if(scheduleDays[dayIndex]['latitude'] != '' && scheduleDays[dayIndex]['latitude'] != 0) {
                    var point = new google.maps.LatLng(scheduleDays[dayIndex]['latitude'], scheduleDays[dayIndex]['longitude']);
                    currentBounds.extend(point);
                    if(pointCount > 0) {
                        gmapInstance.gmap('addShape', 'Polyline', {
                            map: mapObj,
                            path: [lastPoint, point]
                        });
                    }
                    gmapInstance.gmap('addMarker', {
                        position: point,
                        icon: mapIcons[0],
                        lineId: 'day' + scheduleDays[dayIndex]['id'],
                        pointId: scheduleDays[dayIndex]['point_id'],
                        lineTitle: scheduleDays[dayIndex]['point_name'],
                        draggable: true
                    }, scheduleDayListMarkerAdded);
                    scheduleLineListBox += '<div><a href="#" data-id="day' + k + '" class="scheduleLineButton btn scheduleLineBarLong">' + scheduleDays[dayIndex]['point_name'] + '</a></div>';
                } else {
                    scheduleLineListBox += '<div><a class="btn scheduleLineText scheduleLineBarShort">' + scheduleDays[dayIndex]['point_name'] + '</a><a href="#" data-id="day' + k + '" class="btn scheduleLineButton"><i class="icon-map-marker"></i></a></div>';
                }
            }
            $('div.scheduleLineList').html(scheduleLineListBox);
            $('a.scheduleLineButton').click(scheduleLineButtonClick);
            $('a.scheduleLineText').click(scheduleLineTextClick);
            if(false === currentBounds.isEmpty()) {
                mapObj.fitBounds(currentBounds);
            }
        }
    }
    
    function scheduleLineButtonClick() {
        var lineId = $(this).attr('data-id');
        var dayObj = $('select.scheduleDayList');
        var dayId = dayObj.val();
        var mapObj = gmapInstance.gmap('get', 'map');
        var currentCenter = mapObj.getCenter();
        var pLat = currentCenter.lat();
        var pLng = currentCenter.lng();
        var formUrl = wwwRoot + 'schedule_lines/add/' + dayId;
        lastLineId = lineId;
        if(lineId == 0) {
            gmapInstance.gmap('closeInfoWindow');
            mapObj.fitBounds(currentBounds);
        } else if(lineId == 'day' + dayId) {
            var daySort = scheduleDaysMap[dayId];
            if(scheduleDays[daySort]['latitude'] != 0 && scheduleDays[daySort]['latitude'] != '') {
                var point = new google.maps.LatLng(scheduleDays[daySort]['latitude'], scheduleDays[daySort]['longitude']);
                mapObj.setZoom(15);
                mapObj.panTo(point);
                google.maps.event.trigger(collectedMarkers['day' + dayId], 'click');
            } else {
                var daySort = scheduleDaysMap[dayId];
                var pointName = $('input.addressBar').val();
                if(pointName == '') {
                    pointName = scheduleDays[daySort]['point_name'];
                }
                $.post(formUrl + '/stay', {
                    'data': {
                        'ScheduleDay': {
                            'point_name': pointName,
                            'latitude': pLat,
                            'longitude': pLng
                        }
                    }
                }, function(result) {
                    if('ok' === result) {
                        scheduleDays[daySort]['point_name'] = pointName;
                        scheduleDays[daySort]['latitude'] = pLat;
                        scheduleDays[daySort]['longitude'] = pLng;
                    }
                    dayObj.trigger('change');
                });
            }
        } else if(scheduleLines[dayId][lineId]['latitude'] != '' && scheduleLines[dayId][lineId]['latitude'] != 0) {
            var point = new google.maps.LatLng(scheduleLines[dayId][lineId]['latitude'], scheduleLines[dayId][lineId]['longitude']);
            mapObj.setZoom(15);
            mapObj.panTo(point);
            google.maps.event.trigger(collectedMarkers[lineId], 'click');
        } else {
            var pointName = $('input.addressBar').val();
            if(pointName == '') {
                pointName = scheduleLines[dayId][lineId]['point_name'];
            }
            $.post(formUrl, {
                'data': {
                    'ScheduleLine': {
                        'id': lineId,
                        'point_name': pointName,
                        'latitude': pLat,
                        'longitude': pLng
                    }
                }
            }, function(result) {
                if('ok' === result) {
                    scheduleLines[dayId][lineId]['point_name'] = pointName;
                    scheduleLines[dayId][lineId]['latitude'] = pLat;
                    scheduleLines[dayId][lineId]['longitude'] = pLng;
                }
                dayObj.trigger('change');
            });
        }
        return false;
    }
    
    function scheduleLineTextClick() {
        $('input.addressBar').val($(this).html());
        $('a.addressBarGo').trigger('click');
        return false;
    }
    
    function scheduleDayListMarkerAdded(iMap, iMarker) {
        collectedMarkers[iMarker.lineId] = iMarker;
        google.maps.event.addListener(iMarker, 'click', function(){
            var clickedPoint = this.getPosition();
            var infoContent = $('#scheduleDayListMarkerWindow').tmpl({
                wwwRoot: wwwRoot,
                pointTitle: iMarker.lineTitle,
                pointId: iMarker.pointId,
                lineId: iMarker.lineId,
                encodedTitle: encodeURIComponent(iMarker.lineTitle),
                lat: clickedPoint.lat(),
                lng: clickedPoint.lng()
            }).html();
            iMap.setZoom(15);
            iMap.panTo(clickedPoint);
            $('div#map_canvas').gmap('openInfoWindow', {
                content: infoContent
            }, iMarker);
            $('a.scheduleLineButton[data-id=' + iMarker.lineId + ']').trigger('focus');
        });
        google.maps.event.addListener(iMarker, 'dragend', function(){
            var markerObj = this;
            var dragendPoint = markerObj.getPosition();
            var pLat = dragendPoint.lat();
            var pLng = dragendPoint.lng();
            var dayObj = $('select.scheduleDayList');
            var dayId = dayObj.val();
            var formUrl = wwwRoot + 'schedule_lines/add/' + dayId;
            if(true === isNaN(markerObj.lineId)) {
                var daySort = scheduleDaysMap[dayId];
                $.post(formUrl + '/stay', {
                    'data': {
                        'ScheduleDay': {
                            'point_name': scheduleDays[daySort]['point_name'],
                            'latitude': pLat,
                            'longitude': pLng
                        }
                    }
                }, function(result) {
                    if('ok' === result) {
                        scheduleDays[daySort]['latitude'] = pLat;
                        scheduleDays[daySort]['longitude'] = pLng;
                    }
                    dayObj.trigger('change');
                });
            } else {
                $.post(formUrl, {
                    'data': {
                        'ScheduleLine': {
                            'id': markerObj.lineId,
                            'latitude': pLat,
                            'longitude': pLng
                        }
                    }
                }, function(result) {
                    if('ok' === result) {
                        scheduleLines[dayId][markerObj.lineId]['latitude'] = pLat;
                        scheduleLines[dayId][markerObj.lineId]['longitude'] = pLng;
                    }
                    dayObj.trigger('change');
                });
            }
            
        });
    }
    
    function addressBarGoClick() {
        var address = $('input.addressBar').val();
        if('' !== address) {
            gmapInstance.gmap('search', {
                address: address
            }, function(result, status) {
                if('OK' === status) {
                    $('div#map_canvas').gmap('addMarker', {
                        position: result[0].geometry.location,
                        icon: mapIcons[0],
                        draggable: true,
                        lineTitle: result[0].formatted_address
                    }, function(iMap, iMarker) {
                        if('undefined' != typeof(collectedMarkers[0])) {
                            collectedMarkers[0].setMap(null);
                        }
                        collectedMarkers[0] = iMarker;
                        google.maps.event.addListener(collectedMarkers[0], 'dragend', function(e){
                            var latLngString = e.latLng.toString();
                            $('input.addressBar').val(latLngString);
                            collectedMarkers[0].lineTitle = latLngString;
                            google.maps.event.trigger(collectedMarkers[0], 'click');
                        });
                        iMap.panTo(result[0].geometry.location);
                        if('' == iMarker.lineTitle) {
                            $('input.addressBar').val(iMarker.getPosition().toString());
                        } else {
                            $('input.addressBar').val(iMarker.lineTitle);
                        }
                        google.maps.event.addListener(collectedMarkers[0], 'click', function(){
                            var clickedPoint = this.getPosition();
                            var theTitle = iMarker.lineTitle.replace('\'', '\\\'');
                            var infoContent = $('#placeServiceMarkerWindow').tmpl({
                                encodedTitle: encodeURIComponent(iMarker.lineTitle),
                                pointTitle: iMarker.lineTitle,
                                theTitle: theTitle,
                                lat: clickedPoint.lat(),
                                lng: clickedPoint.lng()
                            }).html();
                            iMap.panTo(clickedPoint);
                            $('div#map_canvas').gmap('openInfoWindow', {
                                content: infoContent
                            }, iMarker);
                        });
                        google.maps.event.trigger(collectedMarkers[0], 'click');
                    });
                }
            });
        }
    }
    
}

function quickAddLine(lat, lng, title, pointId) {
    var dayId = $('select.scheduleDayList').val();
    var lineId = lastLineId;
    var stayId = 'day' + dayId;
    if(isNaN(pointId)) {
        pointId = 0;
    }
    if(lineId == 0 || lineId == stayId) {
        var lastOption = $('a.scheduleLineButton:last');
        if(lastOption.attr('data-id') == stayId) {
            lastOption = lastOption.parent().prev().prev().find('a');
        }
        lineId = lastOption.attr('data-id');
    }
    if(typeof(lineId) === 'undefined' || lineId == stayId) {
        lineId = 0;
    }
    var formUrl = wwwRoot + 'schedule_lines/add/' + dayId + '/' + lineId;
    var lineData = {
        model: 'Point',
        foreign_key: pointId,
        point_name: title,
        latitude: lat,
        longitude: lng
    };
    $.post(formUrl, {
        data: {
            ScheduleLine: lineData
        }
    }, function(newLineId) {
        if(newLineId != '') {
            lineData['id'] = newLineId;
            if(lineId > 0 && undefined !== scheduleLines[dayId][lineId] && undefined !== scheduleLines[dayId][lineId]['sort']) {
                lineData['sort'] = parseInt(scheduleLines[dayId][lineId]['sort']) + 1;
            } else {
                lineData['sort'] = 1;
            }
            for(k in scheduleLines[dayId]) {
                if(scheduleLines[dayId][k]['sort'] >= lineData['sort']) {
                    scheduleLines[dayId][k]['sort'] = parseInt(scheduleLines[dayId][k]['sort']) + 1;
                }
            }
            scheduleLines[dayId][newLineId] = lineData;
            $('select.scheduleDayList').trigger('change');
            $('input.addressBar').val('');
        }
    });
}

function quickSetStay(lat, lng, title, pointId) {
    var dayId = $('select.scheduleDayList').val();
    var formUrl = wwwRoot + 'schedule_lines/add/' + dayId + '/stay';
    if(isNaN(pointId)) {
        pointId = 0;
    }
    $.post(formUrl, {
        data: {
            ScheduleDay: {  
                point_id: pointId,
                point_name: title,
                latitude: lat,
                longitude: lng
            }
        }
    }, function(result) {
        var daySort = scheduleDaysMap[dayId];
        if(result == 'ok') {
            scheduleDays[daySort]['point_id'] = pointId;
            scheduleDays[daySort]['point_name'] = title;
            scheduleDays[daySort]['latitude'] = lat;
            scheduleDays[daySort]['longitude'] = lng;
            $('select.scheduleDayList').trigger('change');
            $('input.addressBar').val('');
        }
    });
}

function pointsNearby(nLat, nLng) {
    var theLocation = new google.maps.LatLng(nLat, nLng);
    var req = {
        location: theLocation,
        radius: 1000,
        types: ['amusement_park', 'aquarium', 'art_gallery', 'bakery', 'bar', 'book_store', 'bowling_alley', 'cafe', 'casino', 'cemetery', 'church', 'city_hall', 'department_store', 'food', 'grocery_or_supermarket', 'hindu_temple', 'lodging', 'meal_delivery', 'meal_takeaway', 'mosque', 'museum', 'night_club', 'park', 'restaurant', 'rv_park', 'school', 'shopping_mall', 'spa', 'stadium', 'store', 'synagogue', 'university', 'zoo']
    };
    for(k in placeServiceMarkers) {
        placeServiceMarkers[k].setMap(null);
    }
    for(k in pointsNearMarkers) {
        pointsNearMarkers[k].setMap(null);
    }
    placeServiceMarkers = [];
    placeServiceOptions.bounds = new google.maps.LatLngBounds;
    placeServiceOptions.bounds.extend(theLocation);
    placeService.nearbySearch(req, nearbySearchBack);
    $.getJSON(wwwRoot + 'points/json_near/' + nLat + '/' + nLng, {}, pointsNearBack);
}

function nearbySearchBack(records, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        var gmapObj = $('div#map_canvas').gmap('get', 'map');
        var placeServiceMarkersIndex = placeServiceMarkers.length;
        for(k in records) {
            ++placeServiceMarkersIndex;
            placeServiceOptions.bounds.extend(records[k]['geometry']['location']);
            var gIcon = new google.maps.MarkerImage(
                records[k].icon, placeServiceOptions.iconSize,
                placeServiceOptions.iconPoint1, placeServiceOptions.iconPoint2,
                placeServiceOptions.iconSizeTarget);
            placeServiceMarkers[placeServiceMarkersIndex] = new MarkerWithLabel({
                position: records[k]['geometry']['location'],
                icon: gIcon,
                map: gmapObj,
                gReference: records[k]['reference'],
                lineTitle: records[k]['name'],
                labelContent: records[k]['name'],
                labelAnchor: placeServiceOptions.iconPoint3,
                labelClass: "markerLabels", // the CSS class for the label
                labelStyle: {
                    opacity: 0.75
                }
            });
            placeServiceMarkerAdded(gmapObj, placeServiceMarkers[placeServiceMarkersIndex]);
        }
        gmapObj.fitBounds(placeServiceOptions.bounds);
    } else {
        var errorMessage = '';
        switch(status) {
            case google.maps.places.PlacesServiceStatus.UNKNOWN_ERROR:
            case google.maps.places.PlacesServiceStatus.OVER_QUERY_LIMIT:
            case google.maps.places.PlacesServiceStatus.REQUEST_DENIED:
            case google.maps.places.PlacesServiceStatus.INVALID_REQUEST:
                errorMessage = 'There is something wrong. If you keep getting this message. Please contact us and sorry for the inconvenience.';
                break;
            case google.maps.places.PlacesServiceStatus.ZERO_RESULTS:
            case google.maps.places.PlacesServiceStatus.NOT_FOUND:
                errorMessage = 'Can not find recommended points nearby';
                break;
        }
        alert(errorMessage);
    }
}

function placeServiceMarkerAdded(iMap, iMarker) {
    google.maps.event.addListener(iMarker, 'click', function(){
        var clickedPoint = this.getPosition();
        var theTitle = iMarker.lineTitle.replace('\'', '\\\'');
        placeService.getDetails({
            reference: iMarker.gReference
        }, function(place, status) {
            var photo = '', photo_url = '';
            var titleLink = 'https://www.google.com/search?q=' + encodeURIComponent(iMarker.lineTitle);
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                if(typeof(place.photos) !== 'undefined' && typeof(place.photos[0].raw_reference) !== 'undefined') {
                    photo_url = place.photos[0].raw_reference.fife_url;
                    photo = 'http://images0-focus-opensocial.googleusercontent.com/gadgets/proxy?container=focus&gadget=a&resize_w=200&url=';
                    photo += encodeURIComponent(place.photos[0].raw_reference.fife_url);
                }
                if(typeof(place.website) !== '') {
                    titleLink = place.website;
                }
            }
            var infoContent = $('#placeServiceMarkerWindow').tmpl({
                titleLink: titleLink,
                pointTitle: iMarker.lineTitle,
                theTitle: theTitle,
                photo: photo,
                photo_url: photo_url,
                lat: clickedPoint.lat(),
                lng: clickedPoint.lng()
            }).html();
            iMap.panTo(clickedPoint);
            $('div#map_canvas').gmap('openInfoWindow', {
                content: infoContent
            }, iMarker);
        });
        
    });
}

function pointsNearBack(result) {
    if(result.length > 0) {
        var gmapObj = $('div#map_canvas').gmap('get', 'map');
        for(k in result) {
            var gIcon = new google.maps.MarkerImage(
                wwwRoot + 'css/mapMarkers.png', placeServiceOptions.icon2034,
                placeServiceOptions.iconPoint1);
            var gIconTitle = '';
            if(result[k]['Point']['title_zh_tw'] != null) {
                gIconTitle += ' ' + result[k]['Point']['title_zh_tw'];
            }
            if(result[k]['Point']['title_en_us'] != null) {
                gIconTitle += ' ' + result[k]['Point']['title_en_us'];
            }
            if(result[k]['Point']['title'] != null) {
                gIconTitle += ' ' + result[k]['Point']['title'];
            }
            gIconTitle = gIconTitle.replace(/^\s+|\s+$/g, '');
            pointsNearMarkers[k] = new MarkerWithLabel({
                position: new google.maps.LatLng(result[k]['Point']['latitude'], result[k]['Point']['longitude']),
                icon: gIcon,
                map: gmapObj,
                lineTitle: gIconTitle,
                pointId: result[k]['Point']['id'],
                labelContent: gIconTitle,
                labelAnchor: placeServiceOptions.iconPoint3,
                labelClass: "markerLabels", // the CSS class for the label
                labelStyle: {
                    opacity: 0.75
                }
            });
            pointsNearBackMarkerAdded(gmapObj, pointsNearMarkers[k]);
        }
    }
}

function pointsNearBackMarkerAdded(iMap, iMarker) {
    google.maps.event.addListener(iMarker, 'click', function(){
        var clickedPoint = this.getPosition();
        var theTitle = iMarker.lineTitle.replace('\'', '\\\'');
        var infoContent = $('#pointsNearBackMarkerWindow').tmpl({
            wwwRoot: wwwRoot,
            pointId: iMarker.pointId,
            pointTitle: iMarker.lineTitle,
            theTitle: theTitle,
            lat: clickedPoint.lat(),
            lng: clickedPoint.lng()
        }).html();
        iMap.panTo(clickedPoint);
        $('div#map_canvas').gmap('openInfoWindow', {
            content: infoContent
        }, iMarker);
    });
}

function scheduleDayListEdit(lineId) {
    var dayId = $('select.scheduleDayList').val();
    var linePointName = '';
    if(lineId === 'day' + dayId) {
        linePointName = scheduleDays[dayId]['point_name'];
    } else if(dayId > 0 && undefined !== scheduleLines[dayId][lineId]) {
        linePointName = scheduleLines[dayId][lineId]['point_name'];
    }
    $('#dayListEditForm').tmpl({
        dayId: dayId,
        lineId: lineId,
        linePointName: linePointName
    }).dialog({
        width: 400
    }).find('a').click(scheduleDayListEditSubmit);
}

function scheduleDayListEditSubmit() {
    var dayId = $(this).attr('data-day-id');
    var lineId = $(this).attr('data-id');
    var pointName = $(this).parent().find('input').val();
    if(pointName != '') {
        $.post(wwwRoot + 'schedule_lines/edit/' + dayId + '/' + lineId, {
            data: {
                ScheduleLine: {
                    point_name: pointName
                }
            }
        }, function(result) {
            if(result === 'ok') {
                if(lineId === 'day' + dayId) {
                    scheduleDays[dayId]['point_name'] = pointName;
                } else {
                    scheduleLines[dayId][lineId]['point_name'] = pointName;
                }
                $('select.scheduleDayList').trigger('change');
            }
        });
        $('form#lineEdit' + lineId).dialog('close');
    }
    return false;
}

function scheduleDayListDelete(lineId) {
    var dayId = $('select.scheduleDayList').val();
    if(lineId === 'day' + dayId) {
        scheduleDays[dayId]['point_name'] = '';
        scheduleDays[dayId]['point_id'] = 0;
        scheduleDays[dayId]['latitude'] = 0;
        scheduleDays[dayId]['longitude'] = 0;
        quickSetStay(0, 0, '', 0);
    } else if(dayId > 0 && undefined !== scheduleLines[dayId][lineId]) {
        $.get(wwwRoot + 'schedule_lines/delete/' + lineId, {}, function(result) {
            if(result === 'ok') {
                delete(scheduleLines[dayId][lineId]);
                $('select.scheduleDayList').trigger('change');
            }
        });
    }
}