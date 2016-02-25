<?php
/*
 * http://www.owamap.com.tw
 */
class OwamapShell extends Shell {
	var $tasks = array();
	var $tmpPath;

    function initialize() {
        $this->uses = array('Point', 'Link');
        $this->_loadModels();
        /*
         * 確認目錄是否存在
         */
        $this->tmpPath = TMP . 'owamap';
        if(!file_exists($this->tmpPath)) {
            mkdir($this->tmpPath);
        }
    }

	function main() {
	    /*
	     * 網站上儲存的是 big5 編碼
	    for($i = 50; $i < 100; $i++) {
	        $url = 'http://www.owamap.com.tw/poi/mediasp_' . $i . '.csv';
	        $data = file_get_contents($url);
	        if(!empty($data)) {
	            file_put_contents($this->tmpPath . DS . $i . '.csv', mb_convert_encoding($data, 'utf8', 'big5'));
	        }
	    }
	    */
	    $folder = new Folder($this->tmpPath);
	    $files = $folder->read();
	    foreach($files[1] AS $file) {
	        if(filesize($this->tmpPath . DS . $file) > 0) {
	            $fh = fopen($this->tmpPath . DS . $file, 'r');
	            while($data = fgetcsv($fh, 2048)) {
	                if(count($data) == 6) {
	                    $this->Point->create();
	                    if(strpos($data[0], '愛吃王') !== NULL) {
	                        $data[0] = preg_replace('/愛吃王[0-9]+/i', '', $data[0]);
	                    }
	                    if($this->Point->save(array('Point' => array(
	                        'is_active' => 1,
	                        'title' => $data[0],
	                        'address' => '台灣' . $data[1],
	                        'telephone' => $data[2],
	                        'latitude' => $data[3],
	                        'longitude' => $data[4],
	                    )))) {
	                        $link = explode(' ', $data[5]);
	                        $this->Link->create();
	                        $this->Link->save(array('Link' => array(
	                            'member_id' => 2,
	                            'member_name' => 'olctw',
	                            'model' => 'Point',
	                            'foreign_key' => $this->Point->getInsertID(),
	                            'url' => $link[2],
	                            'title' => $link[0] . $link[1],
	                            'is_active' => 1,
	                        )));
	                    }
	                }
	            }
	            fclose($fh);
	        }
	    }
	}
}