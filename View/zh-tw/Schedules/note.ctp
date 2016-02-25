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
            <h3>第 <?php echo $i; ?> 天 - <?php echo date('Y-m-d', $theDay); ?> (<?php echo $weekDays[date('N', $theDay)]; ?>) <?php echo $scheduleDay['title']; ?></h3>
            <?php if (!empty($scheduleDay['point_name'])) { ?>
                <h4>住宿地點: <?php echo $scheduleDay['point_name']; ?></h4>
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
echo '行程細節： ' . $this->Html->url('/schedules/view/' . $schedule['Schedule']['id'], true);