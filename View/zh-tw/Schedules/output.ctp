<?php
if ($schedule['Schedule']['time_start'] == '0000-00-00 00:00:00') {
    $schedule['Schedule']['time_start'] = $schedule['Schedule']['created'];
}
$genderClass = 'spot_XY';
if (isset($schedule['Member']['gender']) && $schedule['Member']['gender'] === 'f') {
    $genderClass = 'spot_XX';
}
?>
<div id="schedule_info" class="block">

    <div class="title2">
        <h2 class="spot spot_route float-l"><?php echo $schedule['Schedule']['title']; ?></h2>
        <span class="spot overspots float-r <?php echo $genderClass; ?>"><?php echo $schedule['Schedule']['member_name']; ?></span>
        <div class="clearfix"></div>
    </div>
    <div class="list1">
        <ul class="table">
            <li class="dTable"><span class="table_td2">時程：</span><?php
echo $schedule['Schedule']['count_days'];
?>天 <?php
                if (empty($schedule['Schedule']['time_start'])) {
                    $timeEnd = '?';
                } else {
                    $timeStart = strtotime($schedule['Schedule']['time_start']);
                    $days = $schedule['Schedule']['count_days'] - 1;
                    $timeEnd = date('Y-m-d', strtotime("+{$days} days", $timeStart));
                    echo date('Y-m-d', $timeStart) . ' ~ ' . $timeEnd;
                }
?></li>
            <li class="dTable"><span class="table_td2">路程：</span>行經 <strong><?php echo $schedule['Schedule']['count_points']; ?></strong> 個地點<?php
                if (!empty($schedule['Schedule']['point_text'])) {
                    echo ' / 從<strong>' . $schedule['Schedule']['point_text'] . '</strong>出發';
                }
?></li>
            <li>&nbsp;<?php echo $schedule['Schedule']['intro']; ?></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <ul class="list1">
        <li class="txt_S color1b"><?php echo $schedule['Schedule']['created']; ?> posted</li>
        <li class="txt_S color1b"><?php echo $schedule['Schedule']['modified']; ?> updated</li>
    </ul>
    <?php
    if (!empty($scheduleNotes['schedule'])) {
        foreach ($scheduleNotes['schedule'] AS $note) {
            echo '<br /><i class="icon-leaf"></i><span class="color3a">[' . $note['title'] . ']</span><span class="color2b">' . $note['body'] . '</span>';
        }
    }
    ?>
</div>
<hr class="line clear-b" />
<div class="block">
    <?php
    $i = 0;
    $baseTime = strtotime($schedule['Schedule']['time_start']);
    $countDays = count($schedule['ScheduleDay']);
    $weekDays = array(
        1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日'
    );
    foreach ($schedule['ScheduleDay'] AS $scheduleDay) {
        ++$i;
        $theDay = strtotime('+' . ($i - 1) . 'day', $baseTime);
        if (empty($scheduleDay['ScheduleLine']) && empty($scheduleDay['point_name'])) {
            continue;
        }
        ?><div class="title">
            <h3>第 <?php echo $i; ?> 天 - <?php echo date('Y-m-d', $theDay); ?> (<?php echo $weekDays[date('N', $theDay)]; ?>)<br />
                <?php echo $scheduleDay['title']; ?></h3>
            <?php if (!empty($scheduleDay['point_name'])) { ?>
                <h4>住宿地點: <?php echo $scheduleDay['point_name']; ?></h4>
            <?php } ?>
        </div>
        <?php
        if (!empty($scheduleNotes['day'][$scheduleDay['id']])) {
            foreach ($scheduleNotes['day'][$scheduleDay['id']] AS $note) {
                echo '<br /><i class="icon-leaf"></i><span class="color3a">[' . $note['title'] . ']</span><span class="color2b">' . $note['body'] . '</span>';
            }
        }
        ?>
        <ul class="list2">
            <li class="dTable">
                <div class="table-cell_center table-cell_middle table_td1 bg_gary1">交通</div>
                <div class="table-cell_center table-cell_middle bg_gary1">時間地點</div>
                <div class="table-cell_center table-cell_middle table_td1 bg_gary1">活動</div>
                <div class="table-cell_center table-cell_middle table_td_30p bg_gary1">備註</div>
            </li>
            <?php
            foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                ?><li class="dTable">
                    <div class="table-cell_center table-cell_middle table_td1"> <?php echo $scheduleLine['transport_name']; ?> </div>
                    <div class="table-cell_middle"> <span class="color1a"><?php
        echo $this->Travel->formatTimePeriod($scheduleLine['time_arrive'], $scheduleLine['minutes_stay']);
                ?></span> <?php
                    echo $scheduleLine['point_name'];
                    if (!empty($scheduleLine['latitude']) && !empty($scheduleLine['longitude'])) {
                        echo '<a href="#" style="float:right;" class="lineMapMarker" data-latitude="' . $scheduleLine['latitude'] . '" data-longitude="' . $scheduleLine['longitude'] . '" data-map-obj=""><span class="ui-icon ui-icon-circle-triangle-s"></span></a>';
                    }
                    if (!empty($scheduleNotes['line'][$scheduleLine['id']])) {
                        foreach ($scheduleNotes['line'][$scheduleLine['id']] AS $note) {
                            echo '<br /><i class="icon-leaf"></i><span class="color3a">[' . $note['title'] . ']</span><span class="color2b">' . $note['body'] . '</span>';
                        }
                    }
                ?></div>
                    <div class="table-cell_center table-cell_middle table_td1"><?php echo $scheduleLine['activity_name']; ?></div>
                    <div class="table-cell_middle table_td_30p"><?php echo nl2br($scheduleLine['note']); ?></div>
                </li><?php
                }
            ?>

        </ul><?php
    }
        ?>

</div>
<?php
if (!empty($references)) {
    ?><hr class="line clear-b" />
    <div class="block">
        <ul class="list2">
            <li class="dTable">
                <div class="table-cell_center table-cell_middle table_td1 bg_gary1">編號</div>
                <div class="table-cell_center table-cell_middle bg_gary1">地點名稱</div>
                <div class="table-cell_center table-cell_middle table_td1 bg_gary1">電話</div>
                <div class="table-cell_center table-cell_middle table_td_30p bg_gary1">住址</div>
            </li>
            <?php
            foreach ($references AS $key => $val) {
                ?><li class="dTable">
                    <div class="table-cell_center table-cell_middle table_td1"> <?php echo $key; ?> </div>
                    <div class="table-cell_middle"> <span class="color1a"><?php echo $val['title']; ?></span></div>
                    <div class="table-cell_center table-cell_middle table_td1"><?php echo $val['phone']; ?></div>
                    <div class="table-cell_middle table_td_30p"><?php echo $val['address']; ?></div>
                </li><?php
    }
            ?>
        </ul>
    </div><?php
    }
    echo '行程細節： ' . $this->Html->url('/schedules/view/' . $schedule['Schedule']['id'], true);
        ?>
<script type="text/javascript">
    $(function() {
        $('a.lineMapMarker').click(function() {
            var selfObj = $(this);
            var Lat = selfObj.attr('data-latitude');
            var Lng = selfObj.attr('data-longitude');
            var LatLng = new google.maps.LatLng(Lat, Lng);
            var mapObjId = selfObj.attr('data-map-obj');
            var spanObj = $('span', selfObj);
            if(mapObjId == '') {
                var randomId = 'map' + Math.round(Math.random() * 1000);
                selfObj.parent().parent().after('<div id="' + randomId + '" style="width: 938px; height: 300px;"></div>');
                selfObj.attr('data-map-obj', randomId);
                var mapObj = $('div#' + randomId);
                mapObj.gmap({
                    zoom: 15,
                    scaleControl: true,
                    mapTypeControl: false,
                    navigationControl: true,
                    overviewMapControl: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: LatLng,
                    streetViewControl: false
                });
                mapObj.gmap('addMarker', {
                    position: LatLng
                });
                spanObj.removeClass('ui-icon-circle-triangle-s');
                spanObj.addClass('ui-icon-circle-triangle-n');
            } else {
                $('div#' + mapObjId).remove();
                selfObj.attr('data-map-obj', '');
                spanObj.removeClass('ui-icon-circle-triangle-n');
                spanObj.addClass('ui-icon-circle-triangle-s');
            }
            return false;
        });
    });
</script>