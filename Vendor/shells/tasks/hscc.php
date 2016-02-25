<?php
class HsccTask extends HotelShell {
    function execute() {
	    parent::initialize();
	    $this->extractData('bb');
	    $this->extractData('hotels');
	}

	function extractData($file) {
	    if($file == 'hotels') {
            $bb = false;
        } else {
            $bb = true;
        }
        $content = file_get_contents($this->tmpPath . DS . $file);
        $startPosition = strpos($content, '<table border="0" width="100%" cellspacing="1" class="c-13-table" cellpadding="3">');
        $startPosition = strpos($content, '</tr>', $startPosition);
        $startPosition += 5;
        $startPosition1 = $startPosition;
        $folder = $this->tmpPath . DS . 'detail' . DS;
        while($startPosition1 = strpos($content, '<tr>', $startPosition1)) {
            $startPosition2 = strpos($content, '</tr>', $startPosition1) + 5;
            $length = $startPosition2 - $startPosition1;
            if($length < 300) {
                $startPosition1 = $startPosition2;
                continue;
            }
            $part = substr($content, $startPosition1, $length);
            $offset = 0;
            $offset = strpos($part, '<center>') + 8;
            $posPart = strpos($part, '<', $offset);
            $data[$this->counter]['gov_id'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, 'HPID=', $posPart) + 5;
            $posPart = strpos($part, '"', $offset);
            $data[$this->counter]['gov_db_id'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '>', $posPart) + 1;
            $posPart = strpos($part, '<br>', $offset);
            $data[$this->counter]['title_zh_tw'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<br>', $posPart) + 4;
            $posPart = strpos($part, '<', $offset);
            $data[$this->counter]['title_en_us'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '"#FEF7CB">', $posPart) + 10;
            $posPart = strpos($part, '<', $offset);
            $data[$this->counter]['atelephone'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<br>', $posPart) + 4;
            $posPart = strpos($part, '<', $offset);
            $data[$this->counter]['afax'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '"#FEF7CB">', $posPart) + 10;
            $posPart = strpos($part, '&nbsp;', $offset);
            $data[$this->counter]['postcode'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '&nbsp;&nbsp;', $posPart) + 12;
            $posPart = strpos($part, '<', $offset);
            $data[$this->counter]['address_zh_tw'] = trim(substr($part, $offset, ($posPart - $offset)));
            $offset = strpos($part, '<br>', $posPart) + 4;
            $posPart = strpos($part, '<', $offset);
            $data[$this->counter]['address_en_us'] = trim(substr($part, $offset, ($posPart - $offset)));
            $startPosition1 = $startPosition2;
            if(!file_exists($folder . $data[$this->counter]['hpid'])) {
                file_put_contents($folder . $data[$this->counter]['hpid'], mb_convert_encoding(
                    file_get_contents('http://hscc.tbroc.gov.tw/detail_hp.asp?HPID=' . $data[$this->counter]['hpid']),
                	'utf8', 'big5'
                ));
            }
            $detail = file_get_contents($folder . $data[$this->counter]['gov_db_id']);
            $posPart = strpos($detail, '名稱:');
            $offset = strpos($detail, 'class="c-13-bk">', $posPart) + 16;
            $posPart = strpos($detail, '<', $offset);
            $data[$this->counter]['dtitle'] = trim(substr($detail, $offset, ($posPart - $offset)));
            $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
            $posPart = strpos($detail, '<', $offset);
            $data[$this->counter]['telephone'] = trim(substr($detail, $offset, ($posPart - $offset)));
            $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
            $posPart = strpos($detail, '<', $offset);
            $data[$this->counter]['daddress'] = trim(substr($detail, $offset, ($posPart - $offset)));
            if(strpos($detail, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;真:')) {
                $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['fax'] = trim(substr($detail, $offset, ($posPart - $offset)));
            } else {
                $data[$this->counter]['dfax'] = '';
            }
            if(strpos($detail, '負&nbsp;責&nbsp;人') || strpos($detail, '經&nbsp;營&nbsp;者')) {
                $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['owner'] = trim(substr($detail, $offset, ($posPart - $offset)));
            }
            if(strpos($detail, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;址:', $posPart)) {
                $offset = strpos($detail, ' target=_blank>', $posPart) + 15;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['website'] = trim(substr($detail, $offset, ($posPart - $offset)));
            } else {
                $data[$this->counter]['website'] = '';
            }
            if(strpos($detail, 'E-mail:')) {
                $offset = strpos($detail, ' target=_blank>', $posPart) + 15;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['email'] = trim(substr($detail, $offset, ($posPart - $offset)));
            } else {
                $data[$this->counter]['email'] = '';
            }
            if(strpos($detail, '經營特色')) {
                $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['type'] = trim(substr($detail, $offset, ($posPart - $offset)));
            }
            if(strpos($detail, '經營類型')) {
                $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['type'] = trim(substr($detail, $offset, ($posPart - $offset)));
            }
            if(!isset($data[$this->counter]['type'])) {
                $data[$this->counter]['type'] = '';
            }
            $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
            $posPart = strpos($detail, '<', $offset);
            $data[$this->counter]['rooms'] = trim(substr($detail, $offset, ($posPart - $offset)));
            if(strpos($detail, '交通地點')) {
                $offset = strpos($detail, '<td valign="top" class="c-13-bk">', $posPart) + 33;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['description'] = trim(substr($detail, $offset, ($posPart - $offset)));
            }
            $posPart = strpos($detail, '參考房價', $offset);
            $offset = strpos($detail, '<td class=c-13-bk>', $posPart);
            if(empty($offset)) {
                $offset = strpos($detail, '<td>', $posPart) + 4;
                $posPart = strpos($detail, '</table>', $offset) + 8;
                $result = substr($detail, $offset, $posPart - $offset);
                $result = str_replace('	', '', $result);
                $result = str_replace(' ', '', $result);
                $result = str_replace('&nbsp;', '', $result);
                $result = strip_tags($result);
                $result = preg_replace('/\\n+/', chr(10), $result);
                $result = preg_replace('/：\\n/', '：', $result);
                $data[$this->counter]['price'] = nl2br(trim($result));
            } else {
                $offset += 18;
                $posPart = strpos($detail, '<', $offset);
                $data[$this->counter]['price'] = trim(substr($detail, $offset, ($posPart - $offset)));
            }
            foreach($data[$this->counter] AS $key => $val) {
                if(strpos($val, '&#')) {
                    $data[$this->counter][$key] = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
                }
            }
            $hotel = new hotels($data[$this->counter]);
            $hotel->created = $hotel->modified = date('Y-m-d H:i:s');
            $hotel->setIsNewRecord(true);
            $hotel->save(false);
            if(!empty($data[$this->counter]['type'])) {
                $types = explode(',', $data[$this->counter]['type']);
                foreach($types AS $typeKey => $typeValue) {
                    $typeValue = trim($typeValue);
                    if(empty($typeValue)) {
                        unset($types[$typeKey]);
                    } else {
                        $types[$typeKey] = $typeValue;
                    }
                }
            }
            if($bb == true) {
                $types[] = '民宿';
            } else {
                $types[] = '一般旅館';
            }
            $types = array_unique($types);
            foreach($types AS $type) {
                if(isset($this->tags[$type])) {
                    $hotel->dbConnection->createCommand('
                    INSERT INTO hotels_tags VALUES (' . $hotel->id . ', ' . $this->tags[$type] . ')
                    ')->execute();
                    $hotel->dbConnection->createCommand('
                    UPDATE tags SET count = count + 1 WHERE id = ' . $this->tags[$type]
                    )->execute();
                } else {
                    $tag = new tags(array(
                        'name' => $type,
                        'count' => 1,
                    ));
                    $tag->setIsNewRecord(true);
                    $tag->save(false);
                    $this->tags[$type] = $tag->id;
                    $hotel->dbConnection->createCommand('
                    INSERT INTO hotels_tags VALUES (' . $hotel->id . ', ' . $tag->id . ')
                    ')->execute();
                }
            }
            unset($hotel);
            unset($tag);
            ++$this->counter;
        }
        echo 'done' . chr(10);
	}
}