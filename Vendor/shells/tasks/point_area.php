<?php
class PointAreaTask extends PointShell {
    function execute() {
        /*
         * 取出台灣所有的縣市
         */
        $areas = $this->Area->find('list', array(
            'conditions' => array(
                'Area.parent_id' => 3,
            ),
        ));
        foreach($areas AS $id => $val) {
            $areas[$id] = substr($val, strrpos($val, ' ') + 1);
        }
	    /*
	     * 取出所有台灣的地點資料，只要住址與 id
	     */
        if($points = $this->Point->find('all', array(
            'conditions' => array('Point.address LIKE \'台灣%\''),
            'fields' => array('Point.id', 'Point.address'),
        ))) {
            foreach($points AS $point) {
                $point['Point']['address'] = str_replace('台灣', '', $point['Point']['address']);
                $point['Point']['address'] = str_replace('臺', '台', $point['Point']['address']);
                /*
                 * 取得縣市
                 */
                if($pos = mb_strpos($point['Point']['address'], '縣', 0, 'utf8') ||
                    $pos = mb_strpos($point['Point']['address'], '市', 0, 'utf8')
                ) {
                    $address = mb_substr($point['Point']['address'], 0, $pos + 2, 'utf8');
                    if($key = array_search($address, $areas)) {
                        $this->Point->save(array('Point' => array(
                            'id' => $point['Point']['id'],
                            'area_id' => $key,
                        )));
                    }
                }
            }
        }
	}
}