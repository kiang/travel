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
            <li class="dTable"><span class="table_td2"></span><?php
echo $schedule['Schedule']['count_days'];
?> days <?php
                if (empty($schedule['Schedule']['time_start'])) {
                    $timeEnd = '?';
                } else {
                    $timeStart = strtotime($schedule['Schedule']['time_start']);
                    $days = $schedule['Schedule']['count_days'] - 1;
                    $timeEnd = date('Y-m-d', strtotime("+{$days} days", $timeStart));
                    echo date('Y-m-d', $timeStart) . ' ~ ' . $timeEnd;
                }
?></li>
            <li class="dTable"><span class="table_td2">Path: </span>Bypass <strong><?php echo $schedule['Schedule']['count_points']; ?></strong> points<?php
                if (!empty($schedule['Schedule']['point_text'])) {
                    echo ' / Depart from <strong>' . $schedule['Schedule']['point_text'] . '</strong>';
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
</div>
<hr class="line clear-b" />
<div class="block">
    <?php
    $i = 0;
    $baseTime = strtotime($schedule['Schedule']['time_start']);
    $countDays = count($schedule['ScheduleDay']);
    $weekDays = array(
        1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'
    );
    foreach ($schedule['ScheduleDay'] AS $scheduleDay) {
        ++$i;
        $theDay = strtotime('+' . ($i - 1) . 'day', $baseTime);
        if (empty($scheduleDay['ScheduleLine']) && empty($scheduleDay['point_name'])) {
            continue;
        }
        ?><div class="title">
            <h3>Day <?php echo $i; ?> - <?php echo date('Y-m-d', $theDay); ?> (<?php echo $weekDays[date('N', $theDay)]; ?>) <?php echo $scheduleDay['title']; ?></h3>
            <?php if (!empty($scheduleDay['point_name'])) { ?>
                <h4>Accommodation: <?php echo $scheduleDay['point_name']; ?></h4>
            <?php } ?>
        </div>
        <ul>
            <?php
            foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                ?><li><?php echo $scheduleLine['point_name']; ?></li><?php
    }
            ?>

        </ul><?php
    }
        ?>

</div>
<?php
echo 'Details: ' . $this->Html->url('/schedules/view/' . $schedule['Schedule']['id'], true);