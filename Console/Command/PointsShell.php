<?php

/*
 * http://blog.xuite.net/frank.hgs/GE/13567806
 */

class PointsShell extends Shell {

    var $tasks = array();
    var $tmpPath;

    function initialize() {
        $this->uses = array('Point', 'ScheduleDay');
        $this->_loadModels();
    }

    function main() {
        $this->scenic_spot();
        $this->hotel_c();
    }

    function hotel_c() {
        // http://data.gov.tw/opendata/Details?sno=315080000H-00004
        $content = file_get_contents(dirname(__FILE__) . '/data/hotel_C.xml');
        $p = xml_parser_create();
        xml_parse_into_struct($p, $content, $points);
        xml_parser_free($p);
        $areas = $this->Point->Area->find('list', array('conditions' => array(
                'parent_id' => 3,
        )));
        foreach ($areas AS $key => $val) {
            $areas[$key] = substr($val, strrpos($val, ' ') + 1);
        }
        foreach ($points AS $point) {
            if ($point['tag'] === 'INFO' || $point['type'] === 'complete') {
                foreach ($point['attributes'] AS $key => $val) {
                    $point['attributes'][$key] = trim($val);
                }
                $pointId = $this->Point->field('id', array(
                    'OR' => array(
                        'title_zh_tw' => $point['attributes']['NAME'],
                        'title' => $point['attributes']['NAME'],
                    ),
                ));
                if(!empty($pointId)) continue;
                $areaId = 0;
                foreach ($areas AS $key => $val) {
                    if (false !== strpos($point['attributes']['ADD'], $val)) {
                        $areaId = $key;
                    }
                }
                if (empty($areaId))
                    continue;
                if (empty($pointId)) {
                    $this->Point->create();
                } else {
                    $this->Point->id = $pointId;
                }
                if (!$this->Point->save(array('Point' => array(
                                'is_active' => 1,
                                'area_id' => $areaId,
                                'title_zh_tw' => $point['attributes']['NAME'],
                                'address_zh_tw' => $point['attributes']['ADD'],
                                'telephone' => $point['attributes']['TEL'],
                                'fax' => $point['attributes']['FAX'],
                                'website' => $point['attributes']['WEBSITE'],
                                'latitude' => $point['attributes']['PY'],
                                'longitude' => $point['attributes']['PX'],
                                'PointType' => array(
                                    1
                                ),
                    )))) {
                    print_r($this->Point->validationErrors);
                }
            }
        }
    }

    function scenic_spot() {
        // http://data.gov.tw/opendata/Details?sno=315080000H-00001
        // scenic_spot_C.xml
        $content = file_get_contents(dirname(__FILE__) . '/data/scenic_spot_C.xml');
        $p = xml_parser_create();
        xml_parse_into_struct($p, $content, $points);
        xml_parser_free($p);
        $areas = $this->Point->Area->find('list', array('conditions' => array(
                'parent_id' => 3,
        )));
        foreach ($areas AS $key => $val) {
            $areas[$key] = substr($val, strrpos($val, ' ') + 1);
        }
        foreach ($points AS $point) {
            if ($point['tag'] === 'INFO' || $point['type'] === 'complete') {
                foreach ($point['attributes'] AS $key => $val) {
                    $point['attributes'][$key] = trim($val);
                }
                $pointId = $this->Point->field('id', array(
                    'OR' => array(
                        'title_zh_tw' => $point['attributes']['NAME'],
                        'title' => $point['attributes']['NAME'],
                    ),
                ));
                if(!empty($pointId)) continue;
                $areaId = 0;
                foreach ($areas AS $key => $val) {
                    if (false !== strpos($point['attributes']['ADD'], $val)) {
                        $areaId = $key;
                    }
                }
                if (empty($areaId))
                    continue;
                if (empty($pointId)) {
                    $this->Point->create();
                } else {
                    $this->Point->id = $pointId;
                }
                if (!$this->Point->save(array('Point' => array(
                                'is_active' => 1,
                                'area_id' => $areaId,
                                'title_zh_tw' => $point['attributes']['NAME'],
                                'address_zh_tw' => $point['attributes']['ADD'],
                                'telephone' => $point['attributes']['TEL'],
                                'website' => $point['attributes']['WEBSITE'],
                                'latitude' => $point['attributes']['PY'],
                                'longitude' => $point['attributes']['PX'],
                                'PointType' => array(
                                    2
                                ),
                    )))) {
                    print_r($this->Point->validationErrors);
                }
            }
        }
    }

    function bestfactory() {
        // http://www.taiwanplace21.org/factory/index.htm
        $fh = fopen(dirname(__FILE__) . '/data/bestfactorylist.csv', 'r');
        $lineCount = 0;
        $areas = $this->Point->Area->find('list', array('conditions' => array(
                'parent_id' => 3,
        )));
        foreach ($areas AS $key => $val) {
            $areas[$key] = substr($val, strrpos($val, ' ') + 1);
        }
        while ($line = fgetcsv($fh, 2048)) {
            ++$lineCount;
            if ($lineCount < 3) {
                continue;
            }
            foreach ($line AS $key => $val) {
                $line[$key] = trim($val);
            }
            $areaId = 0;
            foreach ($areas AS $key => $val) {
                if (false !== strpos($line[5], $val)) {
                    $areaId = $key;
                }
            }
            $pointId = $this->Point->field('id', array('OR' => array(
                    'title_zh_tw' => $line[3],
                    'title_en_us' => $line[3],
                    'title' => $line[3],
            )));
            $pos = explode(',', $line[7]);
            if (!empty($line[6]) && substr($line[6], 0, 4) !== 'http') {
                $line[6] = 'http://' . $line[6];
            }
            if (empty($pointId)) {
                $this->Point->create();
            } else {
                $this->Point->id = $pointId;
            }
            if (!$this->Point->save(array('Point' => array(
                            'is_active' => 1,
                            'area_id' => $areaId,
                            'title_zh_tw' => $line[3],
                            'title' => $line[3],
                            'address_zh_tw' => $line[5],
                            'address' => $line[5],
                            'telephone' => $line[4],
                            'website' => $line[6],
                            'latitude' => $pos[0],
                            'longitude' => $pos[1],
                            'PointType' => array(
                                2
                            ),
                )))) {
                print_r($this->Point->validationErrors);
                exit();
            }
        }
    }

    function gaminPoints() {
        // schedule_id between 563 & 644
        $days = $this->ScheduleDay->find('all', array(
            'fields' => array('ScheduleDay.id'),
            'conditions' => array(
                'schedule_id >=' => 563,
                'schedule_id <=' => 644,
            ),
            'contain' => array(
                'ScheduleLine' => array(
                    'conditions' => array('foreign_key' => 0),
                ),
            ),
        ));
        foreach ($days AS $day) {
            foreach ($day['ScheduleLine'] AS $line) {
                $line['point_name'] = str_replace(array('＊', '※', '－'), '', $line['point_name']);
                $pointId = $this->Point->field('id', array('OR' => array(
                        'title_zh_tw' => $line['point_name'],
                        'title_en_us' => $line['point_name'],
                        'title' => $line['point_name'],
                )));
                if (empty($pointId)) {
                    $this->Point->create();
                    $this->Point->save(array('Point' => array(
                            'is_active' => 1,
                            'title_zh_tw' => $line['point_name'],
                            'title' => $line['point_name'],
                            'latitude' => $line['latitude'],
                            'longitude' => $line['longitude'],
                            'PointType' => array(
                                '2'
                            )
                    )));
                    $pointId = $this->Point->getInsertID();
                }
                $this->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
                        'id' => $line['id'],
                        'model' => 'Point',
                        'foreign_key' => $pointId,
                )));
            }
        }
    }

    function kmzPoints() {
        $content = file_get_contents(dirname(__FILE__) . '/data/points.kml');
        $pos = 0;
        while ($pos = strpos($content, '<Placemark>', $pos)) {
            $posEnd = strpos($content, '</Placemark>', $pos) + 12;
            $point = simplexml_load_string(substr($content, $pos, $posEnd - $pos));
            if (!empty($point->Point->coordinates) && !empty($point->description)) {
                $name = str_replace(',', '', trim(strval($point->name)));
                if ($this->Point->find('count', array(
                            'conditions' => array('OR' => array(
                                    'title' => $name,
                                    'title_zh_tw' => $name,
                                    'title_en_us' => $name,
                                ))
                        )) == 0) {
                    $coordinates = explode(',', strval($point->Point->coordinates));
                    $this->Point->create();
                    $this->Point->save(array('Point' => array(
                            'is_active' => 1,
                            'title_zh_tw' => $name,
                            'title' => $name,
                            'latitude' => $coordinates[1],
                            'longitude' => $coordinates[0],
                            'PointType' => array(
                                '2'
                            )
                    )));
                }
            }
            $pos = $posEnd;
        }
    }

}