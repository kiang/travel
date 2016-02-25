<?php

class TbrocShell extends Shell {

    var $tasks = array();
    var $tmpPath;

    function initialize() {
        $this->uses = array('Point', 'Link');
        $this->_loadModels();
    }

    function main() {
        $content = file_get_contents(dirname(__FILE__) . '/data/hotels_20121203.html');
        $pos = 0;
        while ($pos = strpos($content, '<tr align="center" bgcolor="#F3F3F3">', $pos)) {
            $posEnd = strpos($content, '</tr>', $pos);
            $line = substr($content, $pos, $posEnd - $pos);
            $fields = explode('</td>', $line);
            foreach ($fields AS $key => $val) {
                $fields[$key] = trim(strip_tags($val));
            }
            if ($posTitle = strpos($fields[1], '(')) {
                $fields[1] = substr($fields[1], 0, $posTitle);
            }
            $point = $this->Point->find('first', array(
                'conditions' => array(
                    'OR' => array(
                        'title_zh_tw' => $fields[1],
                        'title' => $fields[1],
                    )
                )
                    ));
            if (empty($point)) {
                $this->Point->create();
            } else {
                $this->Point->id = $point['Point']['id'];
            }
            $fields[10] = str_replace(array('http://無', ' ', '..'), array('', '', '.'), $fields[10]);
            if (false !== strpos($fields[10], '@')) {
                $fields[10] = '';
            }
            $fields[6] = str_replace(' ', '', $fields[6]);
            $data = array('Point' => array(
                    'is_active' => 1,
                    'title_zh_tw' => $fields[1],
                    'title' => $fields[1],
                    'title_en_us' => $fields[2],
                    'address' => $fields[6],
                    'address_zh_tw' => $fields[6],
                    'address_en_us' => $fields[7],
                    'website' => $fields[10],
                    'telephone' => str_replace(' ', '', $fields[4]),
                    'fax' => str_replace(' ', '', $fields[5]),
                    'PointType' => array(
                        1
                    ),
                    ));
            if (empty($point['Point']['latitude'])) {
                $address = $fields[6];
                $posAdd = strpos($address, '號');
                if (false !== $posAdd) {
                    $address = substr($fields[6], 0, $posAdd + 3);
                }
                if(mb_substr($address, 0, 1, 'utf-8') === '臺') {
                    $address = '台' . mb_substr($address, 1, 100, 'utf-8');
                }
                $address = '台灣' . $address;
                $coordinates = $this->Point->geocode($address);

                if (empty($coordinates[0])) {
                    echo "{$fields[6]} can't\n";
                } else {
                    echo 'get ' . implode(',', $coordinates) . "\n";
                }

                $data['Point']['longitude'] = $coordinates[0];
                $data['Point']['latitude'] = $coordinates[1];
            }
            if (!$this->Point->save($data)) {
                print_r($fields);
                print_r($this->Point->validationErrors);
            }
            $pos = $posEnd;
        }
    }

}