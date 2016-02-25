<?php
class PointShell extends Shell {
    var $tasks = array('PointArea');

    function initialize() {
        $this->uses = array('Point', 'Area');
        $this->_loadModels();
    }

	function main() {
	    /*
	     * 取出地點中重複的資料，將新的刪除
	     */
	    $points = $this->Point->find('all', array(
	        'fields' => array('id', 'title'),
	        'order' => array('Point.title ASC', 'Point.id ASC'),
	    ));
	    $current = '';
	    foreach($points AS $point) {
	        if($current == $point['Point']['title']) {
	            $this->Point->delete($point['Point']['id']);
	        } else {
	            $current = $point['Point']['title'];
	        }
	    }
	}
}