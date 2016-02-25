<?php

/*
 * http://www.garmin.com.tw/travelroute/
 */

class GarminTravelrouteShell extends Shell {

    var $tasks = array();
    var $tmpPath;

    function initialize() {
        $this->uses = array('Schedule', 'Link');
        $this->_loadModels();
        /*
         * 確認目錄是否存在
         */
        $this->tmpPath = TMP . 'gamin';
        if (!file_exists($this->tmpPath)) {
            mkdir($this->tmpPath);
        }
    }

    function main() {
        $zip = new ZipArchive;
        $baseFile = $this->tmpPath . DS . 'base.html';
        if (!file_exists($baseFile)) {
            file_put_contents($baseFile, file_get_contents('http://www.garmin.com.tw/travelroute/'));
        }
        $baseContent = file_get_contents($baseFile);
        $posCurrent = 0;
        $links = array();
        while ($posStart = strpos($baseContent, 'http://www.garmin.com.tw/travelroute/detail/travel_', $posCurrent)) {
            $posEnd = strpos($baseContent, '"', $posStart);
            $links[] = substr($baseContent, $posStart, ($posEnd - $posStart));
            $posCurrent = $posEnd + 1;
        }
        $links = array_unique($links);
        $targetLinks = $finalResult = array();
        foreach ($links AS $link) {
            $targetFile = $this->tmpPath . DS . str_replace(array('http://', '/'), array('', '_'), $link) . '.html';
            if (!file_exists($targetFile)) {
                file_put_contents($targetFile, file_get_contents($link));
            }
            $targetContent = file_get_contents($targetFile);
            $posCurrent = 0;
            while ($posStart = strpos($targetContent, 'http://download.garmin.com/tw/download/RichPOI/TravelRoute/', $posCurrent)) {
                $posEnd = strpos($targetContent, '"', $posStart);
                $targetLink = substr($targetContent, $posStart, ($posEnd - $posStart));
                $targetLinks[] = $targetLink;
                $finalResult[$targetLink] = array(
                    'src' => $link
                );
                $posCurrent = $posEnd + 1;
            }
            $pos = strpos($targetContent, 'Garmin輕旅行');
            if (false !== $pos) {
                $posEnd = strpos($targetContent, '"', $pos);
                $finalResult[$targetLink]['src_title'] = substr($targetContent, $pos, $posEnd - $pos);
            }
        }
        $targetLinks = array_unique($targetLinks);
        $finalResult[$targetLink]['points'] = array();
        $charArr = array('.', '*', ' ');
        foreach ($targetLinks AS $targetLink) {
            $pos = strrpos($targetLink, '/');
            $zipFile = substr($targetLink, $pos + 1);
            if (!file_exists($this->tmpPath . DS . $zipFile)) {
                file_put_contents($this->tmpPath . DS . $zipFile, file_get_contents($targetLink));
            }
            //$zip->open($this->tmpPath . DS . $zipFile);
            $extractedPath = $this->tmpPath . DS . $zipFile . '_files';
            if (!file_exists($extractedPath)) {
                mkdir($extractedPath, 0777);
            }
            /*
            if (false === $zip->extractTo($extractedPath)) {
                file_put_contents($this->tmpPath . DS . $zipFile, file_get_contents($targetLink));
            }
             * 
             */
            //$zip->close();
            foreach (glob($extractedPath . DS . '*.gpx') AS $gpxFile) {
                $gpxFileContent = file_get_contents($gpxFile);
                $points = simplexml_load_string($gpxFileContent);
                $pos = strpos($finalResult[$targetLink]['src_title'], '|') + 1;
                $posEnd = strpos($finalResult[$targetLink]['src_title'], '-', $pos);
                $finalResult[$targetLink]['scheduleTitle'] = trim(substr($finalResult[$targetLink]['src_title'], $pos, $posEnd - $pos));
                foreach ($points->wpt AS $point) {
                    $targetPoint = array(
                        'title' => (string) $point->name,
                        'latitude' => (string) $point->attributes()->lat,
                        'longitude' => (string) $point->attributes()->lon,
                    );
                    $pos = strpos($targetPoint['title'], '.');
                    if (false !== $pos) {
                        $targetPoint['title'] = substr($targetPoint['title'], $pos);
                    }
                    while (in_array(substr($targetPoint['title'], 0, 1), $charArr)) {
                        $targetPoint['title'] = substr($targetPoint['title'], 1);
                    }
                    $finalResult[$targetLink]['points'][] = $targetPoint;
                }
            }
        }
        foreach ($finalResult AS $schedule) {
            $this->Schedule->create();
            $this->Schedule->save(array('Schedule' => array(
                    'is_draft' => 0,
                    'member_id' => 7,
                    'member_name' => '工讀生',
                    'count_joins' => 2,
                    'title' => $schedule['scheduleTitle'],
                    'time_start' => '2012-11-15 08:30:00',
                    'count_points' => count($schedule['points']),
                    'intro' => '本行程內容參考自 ' . $schedule['src_title'] . ', 從相關連結可以開啟原始網頁瀏覽',
                    )));
            $scheduleId = $this->Schedule->getInsertID();
            $this->Schedule->ScheduleDay->create();
            $this->Schedule->ScheduleDay->save(array('ScheduleDay' => array(
                    'schedule_id' => $scheduleId,
                    )));
            $scheduleDayId = $this->Schedule->ScheduleDay->getInsertID();
            $sort = 1;
            foreach ($schedule['points'] AS $point) {
                $this->Schedule->ScheduleDay->ScheduleLine->create();
                $this->Schedule->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
                        'model' => 'Point',
                        'foreign_key' => 0,
                        'schedule_day_id' => $scheduleDayId,
                        'point_name' => $point['title'],
                        'latitude' => $point['latitude'],
                        'longitude' => $point['longitude'],
                        'count_lines' => count($schedule['points']),
                        'sort' => $sort++,
                        )));
            }
            $this->Link->create();
            $this->Link->save(array('Link' => array(
                    'member_id' => 7,
                    'member_name' => '工讀生',
                    'model' => 'Schedule',
                    'foreign_key' => $scheduleId,
                    'url' => $schedule['src'],
                    'title' => $schedule['src_title'],
                    'is_active' => 1,
                    )));
        }
    }

}