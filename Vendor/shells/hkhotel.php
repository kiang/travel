<?php
class HkhotelShell extends Shell {
	var $tmpPath;

    function initialize() {
        $this->uses = array('Hotel', 'Area');
        $this->_loadModels();
        /*
         * 確認目錄與暫存檔案是否存在
         */
        $this->tmpPath = TMP . 'hotels' . DS . 'hk';
        if(!file_exists($this->tmpPath)) {
            mkdir($this->tmpPath);
        }
    }

	function main() {
	    /*
	     * 下載網頁資料
	    $fileStack = array();
	    for($i = 1; $i <= 7; $i ++) {
	        $url = 'http://www.discoverhongkong.com/tc/jsp/hotel/search-result.jsp?ob=name&seq=a&pageno=' . $i;
	        file_put_contents(TMP . 'hotels' . DS . $i, file_get_contents($url));
	        $list = file_get_contents(TMP . 'hotels' . DS . $i);
	        preg_match_all('/javascript:getHotelDetail\(\'([^\']*)\',\'([^\']*)\'\)/', $list, $matches);
	        if(!empty($matches)) {
	            foreach($matches[1] AS $key => $val1) {
	                $url = 'http://www.discoverhongkong.com/tc/jsp/hotel/search-details.jsp?vhid=' .
	                $val1 . '&addrid=' . $matches[2][$key];
	                $fileName = $val1 . '_' . $matches[2][$key];
	                file_put_contents($this->tmpPath . DS . $fileName, file_get_contents($url));
	                $fileStack[] = $fileName;
	            }
	        }
	    }
	    file_put_contents($this->tmpPath . DS . 'list', implode("\n", $fileStack));
	    */
	    /*
	     * 整理出原始資料
	    $fileContent = file_get_contents($this->tmpPath . DS . 'list');
	    $files = explode("\n", $fileContent);
	    $count = 0;
	    $rawDataAll = '';
	    foreach($files AS $file) {
	        $fileContent = file_get_contents($this->tmpPath . DS . $file);
	        $pos = strpos($fileContent, '<td id="searchResultHeading">') + 29;
	        $rawData['title_zh_tw'] = trim(substr($fileContent, $pos, strpos($fileContent, '</td>', $pos) - $pos));
	        while($pos = strpos($fileContent, '<td class="hotelInfoTitle">', $pos)) {
	            $pos += 27;
	            $field = trim(substr($fileContent, $pos, strpos($fileContent, '</td>', $pos) - $pos));
	            $pos = strpos($fileContent, '<td class="hotelInfoDetail">', $pos) + 28;
	            $fieldValue = trim(substr($fileContent, $pos, strpos($fileContent, '</td>', $pos) - $pos));
	            switch($field) {
	                case 'Name:':
	                    list($area, $address) = explode('<br/>', $fieldValue);
	                    $area = trim($area);
	                    $rawData['address_zh_tw'] = $area . trim(str_replace(array($area, '香港'), array('', ''), $address));
	                    if(empty($rawData['address_zh_tw'])) {
	                        unset($rawData['address_zh_tw']);
	                    }
	                    break;
	                case '網站:':
	                    $rawData['website'] = trim(strip_tags($fieldValue));
	                    if(empty($rawData['website'])) {
	                        unset($rawData['website']);
	                    } elseif(FALSE === strpos($rawData['website'], 'http://')) {
	                        $rawData['website'] = 'http://' . $rawData['website'];
	                    }
	                    break;
	                case '電郵:':
	                    $rawData['email'] = trim(strip_tags($fieldValue));
	                    break;
	                case '電話:':
	                    $rawData['telephone'] = $fieldValue;
	                    break;
	                case '傳真:':
	                    $rawData['fax'] = $fieldValue;
	                    break;
	            }
	        }
	        $rawDataAll .= implode('[=]', $rawData) . chr(10);
	    }
	    file_put_contents($this->tmpPath . DS . 'rawData', $rawDataAll);
	     */
	    /*
	     * 手動整理完成，匯入資料庫
	     */
	    $rawData = file_get_contents($this->tmpPath . DS . 'rawData');
	    $lines = explode(chr(10), $rawData);
	    foreach($lines AS $line) {
	        if(!empty($line)) {
	            list(
	            $data['Hotel']['title_zh_tw'],
	            $data['Hotel']['address_zh_tw'],
	            $data['Hotel']['website'],
	            $data['Hotel']['email'],
	            $data['Hotel']['telephone'],
	            $data['Hotel']['fax']
	            ) = explode('[=]', $line);
	            $coordinates = $this->Hotel->geocode($data['Hotel']['address_zh_tw'], false);
	            if(!empty($coordinates)) {
	                $data['Hotel']['latitude'] = $coordinates[0];
	                $data['Hotel']['longitude'] = $coordinates[1];
	            }
	            $data['Hotel']['address_zh_tw'] = '香港' . $data['Hotel']['address_zh_tw'];
	            $data['Hotel']['rooms'] = 100;
	            $data['Hotel']['area_id'] = 11;
	            $data['Hotel']['is_active'] = 1;
	            $this->Hotel->create();
	            $this->Hotel->save($data);
	        }
	    }
	}
}