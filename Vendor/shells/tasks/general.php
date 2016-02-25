<?php
class GeneralTask extends HotelShell {
    var $baseUrl = 'http://202.39.225.136/hotel/h_general.asp?SYS_PageSize=%u&PageNo=%u';
    var $key = '<td width="70"><img src="run.JPG" border="0">';
    var $dataFile = 'general.html';
    var $type_id = 1;
	function execute() {
	    parent::initialize();
	    $this->extractData();
	}

	function extractData() {
	    $string = file_get_contents(TMP . $this->dataFile);
	    $string = substr($string,
	    strpos($string, '<table border="0" width="950" cellspacing="0" cellpadding="0" class="deps">'));
	    $string = substr($string, 0, strpos($string, '</table>'));
	    $string = $this->strim($string);
	    preg_match_all("|<tr>.*?</tr>|s", $string, $newArray);
	    $count = count($newArray[0]);
	    for($i = 3; $i <= $count; $i++) {
	        preg_match_all("|<td.*?</td>|s", $newArray[0][$i], $arr1);
	        preg_match_all("|<td.*?</td>|s", $newArray[0][++$i], $arr2);
	        $i++;
	        $data['Hotel']['type_id'] = $this->type_id;
	        $data['Hotel']['title'] = $this->trimTags($arr1[0][1]);
	        if(empty($data['Hotel']['title'])) {
	            continue;
	        }
	        $data['Hotel']['etitle'] = $this->trimTags($arr2[0][1]);
	        $status = $this->trimTags($arr1[0][2]);
	        switch(trim($status)) {
	            case '營業中':
	                $data['Hotel']['status_id'] = 1;
	                break;
	            case '歇業':
	                $data['Hotel']['status_id'] = 2;
	                break;
	            case '停業':
	                $data['Hotel']['status_id'] = 3;
	                break;
	        }
	        $data['Hotel']['rooms'] = $this->trimTags($arr1[0][5]);
	        $data['Hotel']['phone'] = substr($this->trimTags($arr1[0][3]), 4);
	        $data['Hotel']['fax'] = substr($this->trimTags($arr2[0][3]), 4);
	        $data['Hotel']['address'] = $this->trimTags($arr1[0][4]);
	        $data['Hotel']['eaddress'] = $this->trimTags($arr2[0][4]);
	        $price = explode('-', $this->trimTags($arr2[0][5]));
	        $data['Hotel']['price_l'] = $price[0];
	        $data['Hotel']['price_h'] = $price[1];
	        preg_match('/mailto:([^"]*)/', $arr1[0][6], $mail);
	        $data['Hotel']['email'] = $mail[1];
	        preg_match('/http:[^"]*/', $arr2[0][6], $website);
	        $data['Hotel']['website'] = $website[0];
	        $this->Hotel->create();
	        $this->Hotel->save($data);
	    }
	}
}
?>