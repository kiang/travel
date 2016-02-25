<?php

/*
 * http://blog.xuite.net/frank.hgs/GE/13567806
 */

class AreaShell extends Shell {

    var $tasks = array();
    var $tmpPath;

    function initialize() {
        $this->uses = array('Area');
        $this->_loadModels();
    }

    function main() {
        $this->countries();
    }

    function countries() {
        // http://www.nationsonline.org/oneworld/population-by-country.htm
        $fh = fopen(dirname(__FILE__) . '/data/countries.csv', 'r');
        $header = false;
        $continents = array();
        while ($line = fgetcsv($fh, 2048)) {
            if (!$header) {
                $header = true;
                continue;
            }
            if (!isset($continents[$line[1]])) {
                $continentId = $this->Area->field('id', array(
                    'name LIKE' => $line[1] . '%',
                    'parent_id' => 0,
                        ));
                if (empty($continentId)) {
                    $this->Area->create();
                    $this->Area->save(array('Area' => array(
                            'name' => $line[1],
                            'parent_id' => 0,
                            )));
                    $continentId = $this->Area->getInsertID();
                }
                $continents[$line[1]] = $continentId;
            }
            switch ($line[0]) {
                case 'Guinea-Bissau':
                    $countryId = 457;
                    break;
                case 'Congo, DROC':
                    $countryId = 506;
                    break;
                case 'Congo, ROC':
                    $countryId = 504;
                    break;
                case 'Serbia':
                    $countryId = 453;
                    break;
                case 'Myanmar (Burma)':
                    $countryId = 270;
                    break;
                case 'Timor-Leste (East Timor)':
                    $countryId = 276;
                    break;
                case 'Cyprus':
                    $countryId = 471;
                    break;
                case 'Guinea':
                    $countryId = 462;
                    break;
                case 'Lao PDR':
                    $countryId = 267;
                    break;
                default:
                    $countryId = $this->Area->field('id', array(
                        'name LIKE' => $line[0] . '%',
                            ));
            }
            if (empty($countryId)) {
                $pos = strpos($line[0], '(');
                if (false !== $pos) {
                    $name = trim(substr($line[0], 0, $pos));
                    $countryId = $this->Area->field('id', array(
                        'name LIKE' => $name . '%',
                            ));
                }
                if (empty($countryId)) {
                    $this->Area->create();
                    $this->Area->save(array('Area' => array(
                            'parent_id' => $continents[$line[1]],
                            'name' => $line[0],
                            )));
                } else {
                    $this->Area->save(array('Area' => array(
                            'id' => $countryId,
                            'parent_id' => $continents[$line[1]],
                            )));
                }
            } else {
                $this->Area->save(array('Area' => array(
                        'id' => $countryId,
                        'parent_id' => $continents[$line[1]],
                        )));
            }
        }
        $this->Area->save(array('Area' => array(
                'id' => 475,
                'parent_id' => $continents['South-Central Asia'],
                )));
        $this->Area->save(array('Area' => array(
                'id' => 470,
                'parent_id' => $continents['Southern Europe'],
                )));
        $this->Area->save(array('Area' => array(
                'id' => 468,
                'parent_id' => $continents['Eastern Africa'],
                )));
        $this->Area->save(array('Area' => array(
                'id' => 501,
                'parent_id' => $continents['Western Africa'],
                )));
        $this->Area->save(array('Area' => array(
                'id' => 532,
                'parent_id' => $continents['Oceania'],
                )));
        $this->Area->delete(126);
        $this->Area->delete(91);
        $this->Area->deleteAll(array(
            'parent_id' => 0,
            'rght = lft + 1',
        ));
        fclose($fh);
    }

}