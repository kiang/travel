<?php
$scheduleUrl = 'http://travel.olc.tw/schedules/view/' . $schedule['Schedule']['id'];
$mapBaseUrl = 'http://maps.googleapis.com/maps/api/staticmap?sensor=false&size=600x300';
$rawHtml = '';
$rawHtml .= '<h1><a href="' . $scheduleUrl . '" target="_blank">' . $schedule['Schedule']['title'] . '</a></h1>';
$rawHtmlSimple = $rawHtml;
foreach($schedule['ScheduleDay'] AS $scheduleDay) {
    $scheduleDayUrl = $scheduleUrl . '/' . $scheduleDay['id'];
    $theText = '<h2><a href="' . $scheduleDayUrl . '" target="_blank">Day ' . $scheduleDay['sort'] . ' - ' . $scheduleDay['title'] . '</a></h2>';
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
<p>It's the HTML source code of "<?php echo $this->Html->link($schedule['Schedule']['title'], $scheduleUrl); ?>" itinerary with static google map included. You could simply copy and paste them into your editor in blog site and then continue your editing.</p>
<h2>Summary version</h2>
<textarea rows="10" style="width: 100%;"><?php echo $rawHtmlSimple; ?></textarea>
<h2>Detail version</h2>
<textarea rows="10" style="width: 100%;"><?php echo $rawHtml; ?></textarea>