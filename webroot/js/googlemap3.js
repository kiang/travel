var map;
var bounds;
var markers = new Array;
var charIndex = 1;
var latestPoint = false;
var scrolling = false;
var markerDragEnabled = false;
var olcKeyStatus = 0;
var scheduleDayPointPlaced = false;
var mapIcons = [];
var styleArray = [
{
    featureType: 'poi',
    elementType: 'labels',
    stylers: [{
        visibility: 'off'
    }]
}
];
var styledJustPlayMap = new google.maps.StyledMapType(styleArray,
{
    name: 'MAP @ Just Play'
});

function loadMap(){
    var mapInstance = $('#map').get(0);
    if (mapInstance) {
        $('#map').gmap({
            zoom: 15,
            scaleControl: true,
            mapTypeControl: false,
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false
        });
        map = $('#map').gmap('get', 'map');
        map.mapTypes.set('styledJustPlayMap', styledJustPlayMap);
        map.setMapTypeId('styledJustPlayMap');
        bounds = new google.maps.LatLngBounds();
    }
}

$(function(){
    loadMap();
    initMapIcons();
})

function clearOverlays(){
    var key = '';
    for (key in markers) {
        markers[key].marker.setMap(null);
    }
    markers = new Array;
}

function positionToMap(Lat, Lng, title){
    if (typeof(map) == 'undefined' || typeof(map.getDiv) == 'undefined') {
        loadMap();
    }
    else {
        clearOverlays();
    }
    var point = new google.maps.LatLng(Lat, Lng);
    $('#map').gmap('addMarker', {
        position: point
    }, function(iMap, iMarker) {
        google.maps.event.addListener(iMarker, 'click', function(){
            $('#map').gmap('openInfoWindow', {
                content: title
            }, iMarker);
        });
    });
    map.setCenter(point);
    bounds.extend(point);
    latestPoint = point;
}

function olcNewPoint(map, options) {
    var settings = $.extend({
        index: -1,
        model: 'ScheduleLine',
        key: 0,
        lat: 0,
        lng: 0,
        title: '',
        body: '',
        titleLink: false,
        refObject: false,
        panNow: true
    }, options||{});
    var pointLatLng = new google.maps.LatLng(settings.lat, settings.lng);
    var markerKey;
    if(settings.index == -1) {
        markerKey = settings.title;
    } else {
        markerKey = settings.index;
    }

    if(typeof(markers[markerKey]) != 'undefined') {
        if(false != settings.panNow) {
            map.panTo(markers[markerKey].point);
            google.maps.event.trigger(markers[markerKey].marker, 'click');
        }
    } else {
        var titleText = '';
        var icon = {};
        if(false != settings.titleLink) {
            titleText = '<a href="' + settings.titleLink + '" target="_blank">' + settings.title + '</a>';
        } else {
            titleText = settings.title;
        }
        if(settings.panNow == false && settings.model == 'ScheduleDay') {
            icon = {
                image: wwwRoot + 'css/mapMarkers.png',
                x: 22,
                y: 0,
                domClass: 'olcSprite mDot'
            };
            scheduleDayPointPlaced = true;
        } else {
            icon = {
                image: wwwRoot + 'css/mapMarkers.png',
                x: ((charIndex + 2) % 21) * 22,
                domClass: (charIndex) + ''
            };
            icon.y = (charIndex + 2 - (icon.x / 22)) / 21 * 36;
            if(icon.domClass.length < 2) {
                icon.domClass = '0' + icon.domClass;
            }
            icon.domClass = 'olcSprite m' + icon.domClass;

            charIndex++;
            if (charIndex == 99) {
                charIndex = 1;
            }
        }
        var mapIcon = new google.maps.MarkerImage(
            icon.image,
            new google.maps.Size(20, 34),
            new google.maps.Point(icon.x, icon.y));
        markers[markerKey] = {};
        bounds.extend(pointLatLng);
        markers[markerKey].point = pointLatLng;
        $('#map').gmap('addMarker', {
            position: pointLatLng,
            icon: mapIcon,
            infoContent: titleText + settings.body
        }, function(iMap, iMarker) {
            markers[markerKey].marker = iMarker;
            google.maps.event.addListener(iMarker, 'click', function(){
                $('#map').gmap('openInfoWindow', {
                    content: iMarker.infoContent
                }, iMarker);
                map.setZoom(15);
                map.panTo(this.getPosition());
            });
            if(false != latestPoint) {
                markers[markerKey].polyline = new google.maps.Polyline({
                    map: map,
                    path: [latestPoint, pointLatLng]
                });
                markers[markerKey].polyline.bindTo('map', markers[markerKey].marker);
            }
        });
        markers[markerKey].model = settings.model;
        markers[markerKey].key = settings.key;
        markers[markerKey].titleText = titleText;
        markers[markerKey].body = settings.body;
        markers[markerKey].iconClass = icon.domClass;
        if (false != settings.panNow) {
            map.panTo(pointLatLng);
            google.maps.event.trigger(markers[markerKey].marker, 'click');
            map.fitBounds(bounds);
        }
        else {
            latestPoint = pointLatLng;
        }
        if (false != settings.refObject) {
            var token;
            if (typeof(settings.refObject) == 'object') {
                token = settings.titleLink.replace(/[\s>~:#&\/\.]/g, '') + 'icon';
            }
            else {
                token = settings.refObject.replace(/[\s>:#\.]/g, '') + 'icon';
            }
            if ($('a.' + token).length == 0) {
                $(settings.refObject).prepend('<a href="#" class="' + token + ' ' + icon.domClass + '"></a>');
                $('a.' + token).click(function(){
                    map.panTo(markers[markerKey].point);
                    google.maps.event.trigger(markers[markerKey].marker, 'click');
                    map.setCenter(markers[markerKey].point);
                    if(false == scrolling) {
                        scrolling = true;
                        $('html, body').animate({
                            scrollTop: $('#map').offset().top - 20
                        }, 500, function() {
                            scrolling = false;
                        });
                    }
                    return false;
                });
            }
        }
    }
}

function pointsToMap(points){
    if (typeof(points) != 'undefined') {
        var sort = 1;
        clearOverlays();
        bounds = new google.maps.LatLngBounds();
        charIndex = 1;
        latestPoint = false;
        $.each(points, function(key, obj){
            olcNewPoint(map, {
                index: sort,
                model: obj.model,
                key: obj.key,
                lat: obj.latitude,
                lng: obj.longitude,
                title: obj.title,
                body: obj.body,
                titleLink: false,
                refObject: obj.id,
                panNow: false
            });
            sort++;
        })
        map.setCenter(bounds.getCenter());
        map.fitBounds(bounds);
    }
}

function resetMap(){
    positionToMap(23.6994795, 120.9612952, '瀏覽的部份沒有地圖需要的座標資料');
}

function findLatLng(elementLat, elementLng, address){
    var randomId;
    if (typeof(elementLat.data('mapID')) == 'undefined' || elementLat.data('mapID') == null) {
        randomId = 'map' + Math.round(Math.random() * 1000);
        var offset = elementLng.offset();
        offset.top += 30;
        elementLat.data('mapID', randomId);
        $('#gmapToolBox').tmpl({
            mapId: randomId
        }).appendTo('body').offset(offset);
        var mapObj = $('#' + randomId);
        mapObj.gmap({
            zoom: 15,
            scaleControl: true,
            mapTypeControl: true,
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            streetViewControl: false
        });
        $('#close' + randomId).click(function(){
            elementLat.removeData('mapID');
            $(this).parent().parent().remove();
            return false;
        });
        if (typeof(address) != 'undefined') {
            $('#text' + randomId).val(address);
        }
        else 
        if (elementLat.val() != '') {
            $('#text' + randomId).val(elementLat.val() + ',' + elementLng.val());
        }
        $('#button' + randomId).click(function(){
            var address = $('#text' + randomId).val();
            if (address == '') {
                alert('請先輸入住址！');
            }
            else {
                function putMarker(point) {
                    mapObj.gmap('clear', 'markers');
                    mapObj.gmap('closeInfoWindow');
                    mapObj.gmap('option', 'center', point[0].geometry.location);
                    elementLat.val(point[0].geometry.location.lat());
                    elementLng.val(point[0].geometry.location.lng());
                    var info = $('#gmapToolWindow').tmpl({
                        lat:point[0].geometry.location.lat(),
                        lng:point[0].geometry.location.lng(),
                        address:address,
                        formatted_address:point[0].formatted_address
                    }).html();
                    $('#address' + randomId).val(point[0].formatted_address);
                    mapObj.gmap('addMarker', {
                        position: point[0].geometry.location,
                        draggable: true
                    }, function(map, marker) {
                        mapObj.gmap('openInfoWindow', {
                            content: info
                        }, marker);
                        google.maps.event.addListener(marker, 'dragend', function(e){
                            elementLat.val(e.latLng.lat());
                            elementLng.val(e.latLng.lng());
                            $('#address' + randomId).val(e.latLng.toString());
                            google.maps.event.trigger(marker, 'click');
                        });
                    });
                }
                mapObj.gmap('search', {
                    'address' : address
                }, function(point) {
                    if (typeof(point[0]) == 'undefined') {
                        alert('這個住址目前找不到合適的經緯度！');
                    } else {
                        putMarker(point);
                        var mapInstance = mapObj.gmap('get', 'map');
                        $(mapInstance).click(function(latlng) {
                            mapObj.gmap('search', {
                                'location' : latlng.latLng
                            }, function(response) {
                                putMarker(response);
                            });
                        });
                    }
                });
            }
            return false;
        });
    }
    else {
        randomId = elementLat.data('mapID');
        if (typeof(address) != undefined) {
            $('#text' + randomId).val(address);
        }
    }
    if ($('#text' + randomId).val() != '') {
        $('#button' + randomId).trigger('click');
    }
}

function mapZoomIn(Lat, Lng){
    map.setCenter(new google.maps.LatLng(Lat, Lng));
}

function enableMarkerDrag() {
    markerDragEnabled = true;
    for(key in markers) {
        markers[key].marker.setDraggable(true);
        olcKeyStatus = markers[key].marker.olcKey = key;
        google.maps.event.addListener(markers[key].marker, 'dragend', function(mouse) {
            var previousKey = parseInt(this.olcKey) - 1;
            var nextKey = parseInt(this.olcKey) + 1;
            if(typeof(markers[previousKey]) != 'undefined') {
                markers[this.olcKey].polyline.setOptions({
                    path: [mouse.latLng, markers[previousKey].point],
                    strokeColor: '#CC3333'
                });
            }
            if(typeof(markers[nextKey]) != 'undefined') {
                markers[nextKey].polyline.setOptions({
                    path: [mouse.latLng, markers[nextKey].point],
                    strokeColor: '#CC3333'
                });
            } else {
                latestPoint = mouse.latLng;
            }
            markers[this.olcKey].point = mouse.latLng;
            $('#updateMarkerPositions').show();
        });
    }
}

function initMapIcons() {
    var gSize = new google.maps.Size(20, 34);
    var baseImage = wwwRoot + 'css/mapMarkers.png';
    var iconX = 22;
    var iconY = 0;
    mapIcons[0] = new google.maps.MarkerImage(
        baseImage,
        gSize,
        new google.maps.Point(iconX, iconY));
    for(i = 1; i < 100; i++) {
        iconX = ((i + 2) % 21) * 22;
        iconY = (i + 2 - (iconX / 22)) / 21 * 36;
        mapIcons[i] = new google.maps.MarkerImage(
            baseImage,
            gSize,
            new google.maps.Point(iconX, iconY));
    }
}