<?php

/*
 * http://www.travel.com.tw/TOU/tou0013.aspx?dep=ALL
 * uc_JWPager:PageList
 * http://www.travel.com.tw/TOU/TOU0020.aspx?GROUP_NO=EII122912CI12A&TOUR_NO=EII1200010
 */
App::uses('HttpSocket', 'Network/Http');

class PhoenixShell extends Shell {

    function initialize() {
        $this->uses = array('ScheduleDay');
        $this->_loadModels();
    }

    function main() {
        $this->extractTours();
    }

    function extractTours() {
        $pagePath = TMP . 'Phoenix' . DS . 'page' . DS;
        $titleStack = array();
        $records = array();
        foreach (glob($pagePath . '*') AS $file) {
            $records[$file] = array();
            $fileContent = file_get_contents($file);
            $fileParts = explode('_', basename($file));
            $records[$file]['source'] = 'http://www.travel.com.tw/TOU/TOU0020.aspx?GROUP_NO=' .$fileParts[1] . '&TOUR_NO=' . $fileParts[0];
            $pos = strpos($fileContent, '<meta name="title" content="') + 28;
            $posEnd = strpos($fileContent, '"/>', $pos);
            $records[$file]['title'] = trim(substr($fileContent, $pos, $posEnd - $pos));
            if (empty($records[$file]['title'])) {
                continue;
            }
            $pos = strpos($fileContent, '出發日：<span id="lbl_beg_dt">') + strlen('出發日：<span id="lbl_beg_dt">');
            $posEnd = strpos($fileContent, '日', $pos);
            $dateString = explode('年', substr($fileContent, $pos, $posEnd - $pos));
            $dateString[1] = explode('月', $dateString[1]);
            $records[$file]['date'] = mktime(8, 0, 0, $dateString[1][0], $dateString[1][1], $dateString[0]);
            $records[$file]['date'] = date('Y-m-d H:i:s', $records[$file]['date']);
            $pos = strpos($fileContent, '<!-- 每日行程預組版-->') + strlen('<!-- 每日行程預組版-->');
            $posEnd = strpos($fileContent, '<!-- 每日行程 end-->', $pos);
            $schedules = explode("\n", substr($fileContent, $pos, $posEnd - $pos));
            foreach ($schedules AS $schedule) {
                $schedule = trim($schedule);
                if (empty($schedule))
                    continue;
                $pos = strpos($schedule, '天') + strlen('天');
                $posEnd = strpos($schedule, '</div>', $pos);
                $day_title = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                $pointStack = array();
                while ($pos = strpos($schedule, '<font>', $pos)) {
                    $pos += strlen('<font>');
                    $posEnd = strpos($schedule, '</font>', $pos);
                    $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                    $pointStack[$pos] = $posEnd;
                    $pos = $posEnd;
                }
                $pos = 0;
                while ($pos = strpos($schedule, '<font color="#0000ff">', $pos)) {
                    $pos += strlen('<font color="#0000ff">');
                    $posEnd = strpos($schedule, '</font>', $pos);
                    $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                    $pointStack[$pos] = $posEnd;
                    $pos = $posEnd;
                }
                $pos = 0;
                while ($pos = strpos($schedule, '【', $pos)) {
                    $pos += strlen('【');
                    $posEnd = strpos($schedule, '】', $pos);
                    $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                    $pointStack[$pos] = $posEnd;
                    $pos = $posEnd;
                }
                $pos = 0;
                while ($pos = strpos($schedule, '★', $pos)) {
                    $pos += strlen('★');
                    $posEnd = strpos($schedule, '，', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '◆', $pos)) {
                    $pos += strlen('◆');
                    $posEnd = strpos($schedule, '，', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '◆', $pos)) {
                    $pos += strlen('◆');
                    $posEnd = strpos($schedule, '：', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '■', $pos)) {
                    $pos += strlen('■');
                    $posEnd = strpos($schedule, ' ', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '◎', $pos)) {
                    $pos += strlen('◎');
                    $posEnd = strpos($schedule, '，', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                ksort($pointStack);
                foreach($pointStack AS $pos => $posEnd) {
                    if($posEnd - $pos < 50) {
                        $point = explode('、◎', strip_tags(substr($schedule, $pos, $posEnd - $pos)));
                        $pointStack[$pos] = $point[0];
                        if(isset($point[1])) {
                            $pointStack[$pos+1] = $point[1];
                        }
                    } else {
                        unset($pointStack[$pos]);
                    }
                }
                foreach($pointStack AS $key => $val) {
                    $pointStack[$key] = str_replace(array('「', '」', '【', '】', '參觀', '&#160;', '團費外加', '您不可不知', '世界遺產', '午餐'), '', $val);
                    if(false !== strpos($pointStack[$key], '郵輪') || false !== strpos($pointStack[$key], '安排') ||  false !== strpos($pointStack[$key], '全包') ||   false !== strpos($pointStack[$key], '距離參考') || empty($pointStack[$key])) {
                        unset($pointStack[$key]);
                    }
                }
                $pos = strpos($schedule, '住宿：') + strlen('住宿：');
                $posEnd = strpos($schedule, '或同級', $pos);
                $day_hotel = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                $records[$file][] = array(
                    'title' => trim($day_title),
                    'hotel' => trim($day_hotel),
                    'points' => array_unique($pointStack),
                    'source' => preg_replace('/ +/', ' ', strip_tags($schedule)),
                );
            }
        }
        file_put_contents(dirname(__FILE__) . '/data/Phoenix_' . date('Ymd', filemtime($file)), serialize($records));
    }

    function fetchTours() {
        $tmpPath = TMP . 'Phoenix' . DS . 'list' . DS;
        $tours = array();
        foreach (glob($tmpPath . '*') AS $file) {
            $fileContent = file_get_contents($file);
            $pos = 0;
            while ($pos = strpos($fileContent, 'http://www.travel.com.tw/TOU/TOU0020.aspx', $pos)) {
                $posEnd = strpos($fileContent, '\'', $pos);
                $url = substr($fileContent, $pos, $posEnd - $pos);
                $parts = end(explode('GROUP_NO=', $url));
                $parts = explode('&amp;TOUR_NO=', $parts);
                if (!isset($tours[$parts[1]])) {
                    $tours[$parts[1]] = array();
                }
                $tours[$parts[1]][] = $parts[0];
                $pos = $posEnd;
            }
        }
        $pagePath = TMP . 'Phoenix' . DS . 'page' . DS;
        foreach ($tours AS $tour => $group) {
            $fileName = $pagePath . $tour . '_' . $group[0];
            file_put_contents($fileName, file_get_contents("http://www.travel.com.tw/TOU/TOU0020.aspx?GROUP_NO={$group[0]}&TOUR_NO={$tour}"));
        }
    }

    function fetchList() {
        $tmpPath = TMP . 'Phoenix' . DS . 'list' . DS;
        if (!file_exists($tmpPath)) {
            mkdir($tmpPath, 0777);
        }
        if (!file_exists($tmpPath . '1')) {
            file_put_contents($tmpPath . '1', file_get_contents('http://www.travel.com.tw/TOU/tou0013.aspx?dep=ALL'));
        }
        $firstPage = file_get_contents($tmpPath . '1');
        $pos = strpos($firstPage, '__VIEWSTATE');
        $pos = strpos($firstPage, 'value="', $pos) + 7;
        $posEnd = strpos($firstPage, '"', $pos);
        $viewState = substr($firstPage, $pos, $posEnd - $pos);
        for ($i = 2; $i <= 48; $i++) {
            $HttpSocket = new HttpSocket();
            $results = $HttpSocket->post('http://www.travel.com.tw/TOU/tou0013.aspx?dep=ALL', array(
                '__VIEWSTATE' => $viewState,
                'uc_JWPager:PageList' => $i,
                    ));
            file_put_contents($tmpPath . $i, $results);
        }
    }

}