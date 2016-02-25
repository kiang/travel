<?php
class NationTask extends HotelShell {
    var $baseUrl = 'http://202.39.225.136/hotel/h_nation.asp?SYS_PageSize=%u&PageNo=%u';
    var $key = '<td width="80"><img src="../share-image/4.JPG" border="0">';
    var $dataFile = 'nation.html';
    var $type_id = 3;
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
	        $data['Hotel']['status_id'] = 1;
	        $data['Hotel']['rooms'] = $this->trimTags($arr1[0][4]);
	        $data['Hotel']['phone'] = substr($this->trimTags($arr1[0][2]), 4);
	        $data['Hotel']['fax'] = substr($this->trimTags($arr2[0][2]), 4);
	        $data['Hotel']['address'] = $this->trimTags($arr1[0][3]);
	        $data['Hotel']['eaddress'] = $this->trimTags($arr2[0][3]);
	        preg_match('/mailto:([^"]*)/', $arr1[0][5], $mail);
	        $data['Hotel']['email'] = $mail[1];
	        preg_match('/http:[^"]*/', $arr2[0][5], $website);
	        $data['Hotel']['website'] = $website[0];
	        $this->Hotel->create();
	        $this->Hotel->save($data);
	    }
	}
}
?>