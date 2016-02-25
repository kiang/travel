<?php

/*
 * http://www.settour.com.tw/Startravel/gdg/gdg0000.asp  [gdg0000~gdg0008]
 * 
 * http://www.startravel.com.tw/StarTravel.Web.IGRP.Prod.V2/AProd.aspx?Prod_No=IGRP000006619
 */
App::uses('HttpSocket', 'Network/Http');

class StartravelShell extends Shell {

    function initialize() {
        $this->uses = array('ScheduleDay');
        $this->_loadModels();
    }

    function main() {
        $this->fetchData();
    }

    function extractTours() {
        $pagePath = TMP . 'Startravel' . DS . 'page' . DS;
        foreach (glob($pagePath . '*') AS $file) {
            $fileContent = file_get_contents($file);
        }
    }

    function fetchData() {
        $tmpPath = TMP . 'Startravel' . DS . 'list' . DS;
        $pagePath = TMP . 'Startravel' . DS . 'page' . DS;
        if (!file_exists($tmpPath)) {
            mkdir($tmpPath, 0777, true);
            mkdir($pagePath, 0777, true);
        }
        $HttpSocket = new HttpSocket();
        $HttpSocket->get('http://www.settour.com.tw/');
        for ($i = 0; $i < 9; $i++) {
            if ($i == 0) {
                $firstUrl = "http://www.settour.com.tw/Startravel/gdg/gdg000{$i}.asp";
            } else {
                $firstUrl = "http://www.settour.com.tw/Startravel/gfg/gfg000{$i}.asp";
            }
            if (!file_exists($tmpPath . $i)) {
                file_put_contents($tmpPath . $i, file_get_contents($firstUrl));
            }
            $listContent = file_get_contents($tmpPath . $i);
            $pos = 0;
            while ($pos = strpos($listContent, '?iMGRUP_CD=', $pos)) {
                $pos += 11;
                $posEnd = strpos($listContent, '&', $pos);
                $key = substr($listContent, $pos, $posEnd - $pos);
                if (!file_exists($pagePath . $key)) {
                    $response = $HttpSocket->get("http://www.settour.com.tw/GSet/GFG/GFG_Index.asp?iMGRUP_CD={$key}&iSUB_CD=GO");
                    $count = 0;
                    while(!empty($response->headers['Location']) && $count < 5) {
                        $response = $HttpSocket->get($response->headers['Location']);
                        ++$count;
                    }
                    if(empty($response->body)) {
                        continue;
                    }
                    file_put_contents($pagePath . $key, $response->body);
                }
                $pos = $posEnd;
            }
        }
    }

}