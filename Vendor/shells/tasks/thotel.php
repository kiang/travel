<?php
class ThotelTask extends HotelShell {
    function execute() {
	    parent::initialize();
	    $this->extractData('general_hotels');
	    $this->extractData('international');
	}

	function extractData($file) {
        $content = file_get_contents($this->tmpPath . DS . $file);
        $startPosition = strpos($content, '<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="1" class="styleBLACK">');
        $startPosition = strpos($content, '</tr>', $startPosition);
        $startPosition += 5;
        $startPosition1 = $startPosition;
        while($startPosition1 = strpos($content, '<tr', $startPosition1)) {
            $startPosition2 = strpos($content, '</tr>', $startPosition1) + 5;
            $length = $startPosition2 - $startPosition1;
            if($length < 300) {
                $startPosition1 = $startPosition2;
                continue;
            }
            $part = substr($content, $startPosition1, $length);
            $offset = 0;
            $offset = strpos($part, '<td nowrap>') + 11;
            $posPart = strpos($part, '<', $offset);
            $data['Hotel']['title_zh_tw'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<br>', $posPart) + 4;
            $posPart = strpos($part, '<', $offset);
            $data['Hotel']['title_en_us'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<td>', $posPart) + 4;
            $posPart = strpos($part, '<', $offset);
            $data['Hotel']['address_zh_tw'] = trim(substr($part, $offset, ($posPart - $offset)));
            $data['Hotel']['postcode'] = substr($data['Hotel']['address_zh_tw'], 0, 3);
            $data['Hotel']['address_zh_tw'] = substr($data['Hotel']['address_zh_tw'], 3);
            $offset = strpos($part, '<br>', $posPart) + 4;
            $posPart = strpos($part, '<', $offset);
            $data['Hotel']['address_en_us'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<td nowrap>', $posPart) + 11;
            $posPart = strpos($part, '<', $offset);
            $data['Hotel']['telephone'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<td nowrap>', $posPart) + 11;
            $posPart = strpos($part, '<', $offset);
            $data['Hotel']['fax'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<a href="', $posPart) + 9;
            $posPart = strpos($part, '"', $offset);
            $data['Hotel']['website'] = trim(substr($part, $offset, ($posPart - $offset)));
            $startPosition1 = $startPosition2;
            $this->Hotel->create();
            if($this->Hotel->save($data)) {
                if($file == 'international') {
                    $tagId = 69;
                } else {
                    $tagId = 70;
                }
                $this->Hotel->query('INSERT INTO hotels_tags (hotel_id, tag_id) VALUES (' .
                $this->Hotel->getInsertID() . ', ' . $tagId . ')');
                $this->Hotel->Tag->updateAll(array('Tag.count' => 'Tag.count + 1'), array('Tag.id' => $tagId));
            }
        }
	}
}