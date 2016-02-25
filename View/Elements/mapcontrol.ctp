<?php
if (!isset($mapControlOption)) {
    $mapControlOption = array(
        'height' => '350px',
        'switch' => '',
        'animate' => 'map',
    );
}
?>
<div class="span-11">
    <div id="map" class="span-11 last" style="height: <?php echo $mapControlOption['height']; ?>;"></div>
    <div class="span-11 last">
        <a href="#" id="mapSizeDefault" style="display:none;">[預設地圖] &nbsp;</a>
        <a href="#" id="mapSizeWide">[寬版地圖] &nbsp;</a> 
        <a href="#" id="mapSizeMax">[最大地圖] &nbsp;</a> 
        <a href="#" id="mapZoomFocus">[全部圖標]</a>
    </div>
</div>
<script type="text/javascript">
    $('a#mapSizeWide').click(function() {
<?php echo (!empty($mapControlOption['switch']) ? '$(\'div#' . $mapControlOption['switch'] . '\').hide();' : ''); ?>
        $('div#map').width('950px').parent().width('950px');
        $('div#map').height('<?php echo $mapControlOption['height']; ?>');
        $('a#mapSizeDefault').show();
        $('a#mapSizeMax').show();
        $(this).hide();
        google.maps.event.trigger(map, 'resize');
        map.fitBounds(bounds);
        $('html, body').animate({
            scrollTop: $('#<?php echo $mapControlOption['animate']; ?>').offset().top
        }, 1000);
        return false;
    });
    $('a#mapSizeMax').click(function() {
<?php echo (!empty($mapControlOption['switch']) ? '$(\'div#' . $mapControlOption['switch'] . '\').hide();' : ''); ?>
        $('div#map').width('950px').parent().width('950px');
        $('div#map').height('950px');
        $('a#mapSizeDefault').show();
        $('a#mapSizeMax').show();
        $(this).hide();
        google.maps.event.trigger(map, 'resize');
        map.fitBounds(bounds);
        $('html, body').animate({
            scrollTop: $('#<?php echo $mapControlOption['animate']; ?>').offset().top
        }, 1000);
        return false;
    });
    $('a#mapSizeDefault').click(function() {
<?php echo (!empty($mapControlOption['switch']) ? '$(\'div#' . $mapControlOption['switch'] . '\').show();' : ''); ?>
        $('div#map').width('430px').parent().width('430px');
        $('div#map').height('<?php echo $mapControlOption['height']; ?>');
        $('a#mapSizeWide').show();
        $('a#mapSizeMax').show();
        $(this).hide();
        google.maps.event.trigger(map, 'resize');
        map.fitBounds(bounds);
        $('html, body').animate({
            scrollTop: $('#<?php echo $mapControlOption['animate']; ?>').offset().top
        }, 1000);
        return false;
    });
    $('a#mapZoomFocus').click(function() {
        map.fitBounds(bounds);
        $('html, body').animate({
            scrollTop: $('#<?php echo $mapControlOption['animate']; ?>').offset().top
        }, 1000);
        return false;
    });
</script>