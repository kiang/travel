<?php
class HotelAreaTask extends HotelShell {
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
	     * 取出所有台灣的旅館資料，只要住址與 id
	     */
        if($hotels = $this->Hotel->find('all', array(
            'conditions' => array('Hotel.address_zh_tw LIKE \'台灣%\''),
            'fields' => array('Hotel.id', 'Hotel.address_zh_tw'),
        ))) {
            foreach($hotels AS $hotel) {
                $hotel['Hotel']['address_zh_tw'] = str_replace('台灣', '', $hotel['Hotel']['address_zh_tw']);
                /*
                 * 取得縣市
                 */
                if($pos = mb_strpos($hotel['Hotel']['address_zh_tw'], '縣', 0, 'utf8') ||
                    $pos = mb_strpos($hotel['Hotel']['address_zh_tw'], '市', 0, 'utf8')
                ) {
                    $address = mb_substr($hotel['Hotel']['address_zh_tw'], 0, $pos + 2, 'utf8');
                    if($key = array_search($address, $areas)) {
                        $this->Hotel->save(array('Hotel' => array(
                            'id' => $hotel['Hotel']['id'],
                            'area_id' => $key,
                        )));
                    }
                }
            }
        }
	}
}