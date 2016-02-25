<?php

/*
 * http://www.settour.com.tw/Settour/gdg/gdg0000.asp  [gdg0000~gdg0008]
 * 
 * http://www.settour.com.tw/GSet/GFG/GFG_Index.asp?iMGRUP_CD=EUPPST15E&iSUB_CD=GO
 */
App::uses('HttpSocket', 'Network/Http');

class SettourShell extends Shell {

    function initialize() {
        $this->uses = array('ScheduleDay');
        $this->_loadModels();
    }

    function main() {
        $this->extractTours();
    }

    function extractTours() {
        $pagePath = TMP . 'Settour' . DS . 'page' . DS;
        $titleStack = array();
        $records = array();
        foreach (glob($pagePath . '*') AS $file) {
            $records[$file] = array();
            $fileContent = str_replace(array(chr(131) . chr(193), chr(132) . chr(196)), '', file_get_contents($file));
            $fileContent = mb_convert_encoding($fileContent, 'utf-8', 'big5');
            $records[$file]['source'] = 'http://www.settour.com.tw/GSet/GFG/GFG_Index.asp?iSUB_CD=GO&iMGRUP_CD=' . basename($file);
            $pos = strpos($fileContent, "\t" . 'tmpTitle=');
            if(false === $pos) {
                unset($records[$file]);
                continue;
            } else {
                $pos += 11;
            }
            $posEnd = strpos($fileContent, '－東南旅遊', $pos);
            $records[$file]['title'] = trim(substr($fileContent, $pos, $posEnd - $pos));
            if (empty($records[$file]['title'])) {
                continue;
            }
            $records[$file]['title'] = str_replace(array('(含稅、無購物)'), '', $records[$file]['title']);
            $pos = strpos($fileContent, '<tr height="25" align="center" valign="middle"');
            $pos = strpos($fileContent, '<td>', $pos) + 4;
            $pos = strpos($fileContent, '<td>', $pos) + 4;
            $posEnd = strpos($fileContent, '(', $pos);
            $dateString = explode('/', substr($fileContent, $pos, $posEnd - $pos));
            if ($dateString[0] === '12') {
                $records[$file]['date'] = mktime(8, 0, 0, intval($dateString[0]), intval($dateString[1]), 2012);
            } else {
                $records[$file]['date'] = mktime(8, 0, 0, intval($dateString[0]), intval($dateString[1]), 2013);
            }
            $records[$file]['date'] = date('Y-m-d H:i:s', $records[$file]['date']);
            $pos = strpos($fileContent, '<!--↓區塊：行程計劃↓-->');
            $pos = strpos($fileContent, '<div class="grp-data">', $pos);
            $posEnd = strpos($fileContent, '<!--↑區塊：行程計劃↑-->', $pos);
            $schedules = explode('<td class="tour-detail">', substr($fileContent, $pos, $posEnd - $pos));
            unset($schedules[0]);
            foreach ($schedules AS $schedule) {
                $schedule = trim($schedule);
                if (empty($schedule))
                    continue;
                $pos = strpos($schedule, '<td align="left" class="tour-detail">') + strlen('<td align="left" class="tour-detail">');
                $posEnd = strpos($schedule, "\n", $pos);
                $day_title = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                $refPos = strpos($day_title, '參考');
                if (false !== $refPos) {
                    $day_title = substr($day_title, 0, $refPos);
                }
                $pointStack = array();
                while ($pos = strpos($schedule, '<em', $pos)) {
                    $pos += strlen('<em class="st03">');
                    $posEnd = strpos($schedule, '</em>', $pos);
                    if (false === $posEnd) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '▲', $pos)) {
                    $pos += strlen('▲');
                    $posEnd = strpos($schedule, '、', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '▲', $pos)) {
                    $pos += strlen('▲');
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
                while ($pos = strpos($schedule, '▲', $pos)) {
                    $pos += strlen('▲');
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
                while ($pos = strpos($schedule, '★', $pos)) {
                    $pos += strlen('★');
                    $posEnd = strpos($schedule, ',', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '★', $pos)) {
                    $pos += strlen('★');
                    $posEnd = strpos($schedule, '.', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '「', $pos)) {
                    $pos += strlen('「');
                    $posEnd = strpos($schedule, '」', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '【', $pos)) {
                    $pos += strlen('【');
                    $posEnd = strpos($schedule, '】', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '●', $pos)) {
                    $pos += strlen('●');
                    $posEnd = strpos($schedule, '、', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                $pos = 0;
                while ($pos = strpos($schedule, '●', $pos)) {
                    $pos += strlen('●');
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
                    $posEnd = strpos($schedule, ',', $pos);
                    if (false === $posEnd || ($posEnd - $pos > 60)) {
                        $pos += 5;
                    } else {
                        $point = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                        $pointStack[$pos] = $posEnd;
                        $pos = $posEnd;
                    }
                }
                ksort($pointStack);
                foreach ($pointStack AS $pos => $posEnd) {
                    if ($posEnd - $pos < 50) {
                        $pointStack[$pos] = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                    } else {
                        unset($pointStack[$pos]);
                    }
                }
                foreach ($pointStack AS $key => $val) {
                    $pointStack[$key] = str_replace(array('【', '】', '★', '搭船', '搭乘遊船', '—', '，', ',', '「', '」', '△', '“', '”', '▲', '◎', '◆', '●', '『', '』', '：', '》', '；', '。'), '', $val);
                    if (empty($pointStack[$key])) {
                        unset($pointStack[$key]);
                    }
                }
                $pos = strpos($schedule, '<td align="right" valign="top">旅館：');
                if (false !== $pos) {
                    $pos = strpos($schedule, '<td align="left">', $pos) + strlen('<td align="left">');
                    $posEnd = strpos($schedule, '或同級', $pos);
                    $day_hotel = strip_tags(substr($schedule, $pos, $posEnd - $pos));
                    $day_hotel = str_replace(array('准★★★★★', '準5★', '准5★', '5★'), '', $day_hotel);
                } else {
                    $day_hotel = '';
                }
                $source = str_replace(array(chr(10), chr(13), '　', "\t"), '', strip_tags($schedule));
                $records[$file][] = array(
                    'title' => trim($day_title),
                    'hotel' => trim($day_hotel),
                    'points' => array_unique($pointStack),
                    'source' => preg_replace('/[ ]+/', ' ', $source),
                );
            }
        }
        file_put_contents(dirname(__FILE__) . '/data/Settour_' . date('Ymd', filemtime($file)), serialize($records));
    }

    function fetchData() {
        $tmpPath = TMP . 'Settour' . DS . 'list' . DS;
        $pagePath = TMP . 'Settour' . DS . 'page' . DS;
        if (!file_exists($tmpPath)) {
            mkdir($tmpPath, 0777, true);
            mkdir($pagePath, 0777, true);
        }
        $HttpSocket = new HttpSocket();
        $HttpSocket->get('http://www.settour.com.tw/');
        for ($i = 0; $i < 9; $i++) {
            if ($i == 0) {
                $firstUrl = "http://www.settour.com.tw/Settour/gdg/gdg000{$i}.asp";
            } else {
                $firstUrl = "http://www.settour.com.tw/Settour/gfg/gfg000{$i}.asp";
            }
            if (!file_exists($tmpPath . $i)) {
                file_put_contents($tmpPath . $i, file_get_contents($firstUrl));
            }
            $listContent = file_get_contents($tmpPath . $i);
            $pos = 0;
            while ($pos = strpos($listContent, '?iMGRUP_CD=', $pos)) {
                $pos += 11;
                $posEnd = strpos($listContent, '&', $pos);
                $key = substr($listContent, $pos, $posEnd - $pos);
                if (!file_exists($pagePath . $key)) {
                    $response = $HttpSocket->get("http://www.settour.com.tw/GSet/GFG/GFG_Index.asp?iMGRUP_CD={$key}&iSUB_CD=GO");
                    $count = 0;
                    while (!empty($response->headers['Location']) && $count < 5) {
                        $response = $HttpSocket->get($response->headers['Location']);
                        ++$count;
                    }
                    if (empty($response->body) || strlen($response->body) < 200) {
                        continue;
                    }
                    file_put_contents($pagePath . $key, $response->body);
                }
                $pos = $posEnd;
            }
        }
    }

}