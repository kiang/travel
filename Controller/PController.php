<?php

App::uses('Sanitize', 'Utility');

class PController extends AppController {

    var $name = 'P';
    var $uses = array();

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index');
    }

    function index() {
        $this->layout = 'front';
        $this->loadModel('ScheduleDay');
        $this->loadModel('Area');
        $selectedStack = array(31, 3714, 3750, 3798, 3801, 3811, 3914, 4000, 4039, 4016, 4104, 4106);
        shuffle($selectedStack);
        $scheduleDay = $this->ScheduleDay->find('first', array(
            'conditions' => array('ScheduleDay.id' => $selectedStack[0]),
            'contain' => array(
                'Schedule' => array(
                    'fields' => array('title', 'member_id', 'member_name'),
                ),
                'ScheduleLine' => array(
                    'order' => array('sort' => 'asc'),
                ),
            ),
                ));
        $areas = $this->Area->find('all', array(
            'fields' => array('id', 'code', 'countPoint', 'countSchedule'),
            'conditions' => array(
                'Area.code != \'\'',
            ),
            'order' => array('code ASC'),
                ));
        $areas = Set::combine($areas, '{n}.Area.code', '{n}.Area');
        $codeMapColor = array();
        foreach ($areas AS $key => $val) {
            $codeMapColor[$key] = ($val['countSchedule'] + $val['countPoint']);
        }
        $codeMapColor['_3'] = $codeMapColor['SO'];
        $codeMapColor['UNDEFINED'] = 0;
        $this->set('scheduleDay', $scheduleDay);
        $this->set('codeMap', $areas);
        $this->set('codeMapColor', $codeMapColor);
        $this->set('title_for_layout', '首頁');
    }

}

/*
$this->loadModel('Area');
        $countries = array('BD' => '孟加拉','BE' => '比利時','BF' => '布基納法索','BG' => '保加利亞','BA' => '波斯尼亞和黑塞哥維那','BN' => '文萊','BO' => '玻利維亞','JP' => '日本','BI' => '布隆迪','BJ' => '貝寧','BT' => '不丹','JM' => '牙買加','BW' => '博茨瓦納','BR' => '巴西','BS' => '巴哈馬','BY' => '白俄羅斯','BZ' => '伯利茲','RU' => '俄羅斯','RW' => '盧旺達','RS' => '塞爾維亞共和國','LT' => '立陶宛','LU' => '盧森堡','LR' => '利比里亞','RO' => '羅馬尼亞','GW' => '幾內亞比紹','GT' => '危地馬拉','GR' => '希臘','GQ' => '赤道幾內亞','GY' => '圭亞那','GE' => '格魯吉亞','GB' => '英國','GA' => '加蓬','GN' => '幾內亞','GM' => '岡比亞','GL' => '格陵蘭島','KW' => '科威特','GH' => '加納','OM' => '阿曼','_3' => '索馬里蘭','_2' => '西撒哈拉','_1' => '科索沃','_0' => '北塞浦路斯','JO' => '約旦','HR' => '克羅地亞','HT' => '海地','HU' => '匈牙利','HN' => '洪都拉斯','PR' => '波多黎各','PS' => '西岸','PT' => '葡萄牙','PY' => '巴拉圭','PA' => '巴拿馬','PG' => '巴布亞新幾內亞','PE' => '秘魯','PK' => '巴基斯坦','PH' => '菲律賓','PL' => '波蘭','ZM' => '贊比亞','EE' => '愛沙尼亞','EG' => '埃及','ZA' => '南非','EC' => '厄瓜多爾','AL' => '阿爾巴尼亞','AO' => '安哥拉','KZ' => '哈薩克斯坦','ET' => '埃塞俄比亞','ZW' => '津巴布韋','ES' => '西班牙','ER' => '厄立特里亞','ME' => '黑山','MD' => '摩爾多瓦','MG' => '馬達加斯加','MA' => '摩洛哥','UZ' => '烏茲別克斯坦','MM' => '緬甸','ML' => '馬里','MN' => '蒙古','MK' => '馬其頓','MW' => '馬拉維','MR' => '毛里塔尼亞','UG' => '烏干達','MY' => '馬來西亞','MX' => '墨西哥','VU' => '瓦努阿圖','FR' => '法國','FI' => '芬蘭','FJ' => '斐濟','FK' => '福克蘭群島','NI' => '尼加拉瓜','NL' => '荷蘭','NO' => '挪威','NA' => '納米比亞','NC' => '新喀裡多尼亞','NE' => '尼日爾','NG' => '尼日利亞','NZ' => '紐西蘭','NP' => '尼泊爾','CI' => '象牙海岸','CH' => '瑞士','CO' => '哥倫比亞','CN' => '中國','CM' => '喀麥隆','CL' => '智利','CA' => '加拿大','CG' => '剛果共和國','CF' => '中非共和國','CD' => '在剛果民主共和國','CZ' => '捷克共和國','CY' => '塞浦路斯','CR' => '哥斯達黎加','CU' => '古巴','SZ' => '斯威士蘭','SY' => '敘利亞','KG' => '吉爾吉斯斯坦','KE' => '肯尼亞','SS' => '蘇丹南部','SR' => '蘇里南','KH' => '柬埔寨','SV' => '薩爾瓦多','SK' => '斯洛伐克','KR' => '韓國','SI' => '斯洛文尼亞','KP' => '朝鮮','SO' => '索馬里','SN' => '塞內加爾','SL' => '塞拉利昂','SB' => '索羅門群島','SA' => '沙特阿拉伯','SE' => '瑞典','SD' => '蘇丹','DO' => '多米尼加共和國','DJ' => '吉布提','DK' => '丹麥','DE' => '德國','YE' => '葉門','AT' => '奧地利','DZ' => '阿爾及利亞','US' => '美國','LV' => '拉脫維亞','UY' => '烏拉圭','LB' => '黎巴嫩','LA' => '老撾','TW' => '台灣','TT' => '特里尼達和多巴哥','TR' => '土耳其','LK' => '斯里蘭卡','TN' => '突尼斯','TL' => '東帝汶','TM' => '土庫曼斯坦','TJ' => '塔吉克斯坦','LS' => '萊索托','TH' => '泰國','TF' => '法國南部和南極領地','TG' => '多哥','TD' => '乍得','LY' => '利比亞','AE' => '阿拉伯聯合酋長國','VE' => '委內瑞拉','AF' => '阿富汗','IQ' => '伊拉克','IS' => '冰島','IR' => '伊朗','AM' => '亞美尼亞','IT' => '意大利','VN' => '越南','AR' => '阿根廷','AU' => '澳大利亞','IL' => '以色列','IN' => '印度','TZ' => '坦桑尼亞','AZ' => '阿塞拜疆','IE' => '愛爾蘭','ID' => '印尼','UA' => '烏克蘭','QA' => '卡塔爾','MZ' => '莫桑比克',);
        $fileContent = file_get_contents(JS . 'jquery-jvectormap-world-mill-en.js');
        $pos = strpos($fileContent, '{');
        $posEnd = strrpos($fileContent, ')');
        $jsonData = substr($fileContent, $pos, $posEnd - $pos);
        $jsonData = json_decode($jsonData);
        foreach($jsonData->paths AS $key => $path) {
            $area = $this->Area->find('first', array(
                'conditions' => array('OR' => array(
                    'name LIKE \'%' . $path->name . '%\'',
                    'name LIKE \'%' . $countries[$key] . '%\'',
                )),
            ));
            if(!empty($area) && empty($area['Area']['code'])) {
                $this->Area->save(array('Area' => array(
                    'id' => $area['Area']['id'],
                    'code' => $key,
                )));
            }
            if(!empty($area)) {
                $fileContent = str_replace('"name": "' . $path->name . '"', '"name": "' . $area['Area']['name'] . '"', $fileContent);
            }
        }
        file_put_contents(JS . 'jquery-jvectormap-world-mill-en.js', $fileContent);
 */