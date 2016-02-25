<?php
$scheduleUrl = 'http://travel.olc.tw/schedules/view/' . $schedule['Schedule']['id'];
$mapBaseUrl = 'http://maps.googleapis.com/maps/api/staticmap?sensor=false&size=600x300';
$rawHtml = '';
$rawHtml .= '<h1><a href="' . $scheduleUrl . '" target="_blank">' . $schedule['Schedule']['title'] . '</a></h1>';
$rawHtmlSimple = $rawHtml;
foreach($schedule['ScheduleDay'] AS $scheduleDay) {
    $scheduleDayUrl = $scheduleUrl . '/' . $scheduleDay['id'];
    $theText = '<h2><a href="' . $scheduleDayUrl . '" target="_blank">第 ' . $scheduleDay['sort'] . ' 天 - ' . $scheduleDay['title'] . '</a></h2>';
    $rawHtml .= $theText;
    $rawHtmlSimple .= $theText;
    $markers = array();
    $linesHtml = '';
    $lineCounter = 0;
    foreach($scheduleDay['ScheduleLine'] AS $line) {
        ++ $lineCounter;
        $linesHtml .= '<p>' . $lineCounter . '. <strong>' . $line['point_name'] . '</strong></p>';
        if(!empty($line['latitude'])) {
            $pos = $line['latitude'] . ',' . $line['longitude'];
            $markers[] = $pos;
            $linesHtml .= '<p><a href="' . $scheduleDayUrl . '" target="_blank"><img border="0" src="' . $mapBaseUrl . '&markers=' . $pos . '" /></a></p>';
        }
    }
    if(!empty($markers)) {
        $theText = '<p><a href="' . $scheduleDayUrl . '" target="_blank"><img border="0" src="' . $mapBaseUrl . '&markers=' . implode('&markers=', $markers) . '" /></a></p>';
        $rawHtml .= $theText;
        $rawHtmlSimple .= $theText;
    }
    $rawHtml .= $linesHtml;
    if(!empty($scheduleDay['point_name'])) {
        $rawHtml .= '<p>住宿： <strong>' . $scheduleDay['point_name'] . '</strong></p>';
        if(!empty($scheduleDay['latitude'])) {
            $rawHtml .= '<p><a href="' . $scheduleDayUrl . '" target="_blank"><img border="0" src="' . $mapBaseUrl . '&markers=' . $scheduleDay['latitude'] . ',' . $scheduleDay['longitude'] . '" /></a></p>';
        }
    }
}
?>
<p>下面為 "<?php echo $this->Html->link($schedule['Schedule']['title'], $scheduleUrl); ?>" 行程帶有地圖的 HTML 格式原始碼，只要將它貼入自己的部落格中就可以做後續的編輯</p>
<h2>每日摘要版</h2>
<textarea rows="10" style="width: 100%;"><?php echo $rawHtmlSimple; ?></textarea>
<h2>每日細節版</h2>
<textarea rows="10" style="width: 100%;"><?php echo $rawHtml; ?></textarea>