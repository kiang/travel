<?php

class CronShell extends Shell {

    var $tasks = array();

    function initialize() {
        
    }

    function main() {
        //$this->fetchFeeds();
        $this->updateAreaCounter();
        $this->dropEmptySchedules();
    }

    function dropEmptySchedules() {
        Configure::write('loginMember', array(
            'id' => 0,
            'group_id' => 1,
            'username' => '',
        ));
        $this->uses = array('Schedule');
        $this->_loadModels();
        $schedules = $this->Schedule->find('list', array(
            'fields' => array('Schedule.id', 'Schedule.id'),
            'conditions' => array(
                'Schedule.member_id' => 0,
                'Schedule.count_points' => 0,
                'Schedule.created <' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ),
                ));
        if (!empty($schedules)) {
            $this->Schedule->deleteAll(array('Schedule.id' => $schedules), true, array('afterDelete'));
        }
    }

    function updateAreaCounter() {
        $this->uses = array('Area');
        $this->_loadModels();
        $areas = $this->Area->find('all');
        $this->Area->updateAll(array(
            'countMember' => 0,
            'countPoint' => 0,
            'countSchedule' => 0,
        ));
        foreach ($areas AS $area) {
            $countMember = $this->Area->query("SELECT COUNT(*) AS cnt FROM members AS M
INNER JOIN areas AS A ON A.id = M.area_id
WHERE A.lft >= {$area['Area']['lft']} AND A.rght <= {$area['Area']['rght']}");
            $countPoint = $this->Area->query("SELECT COUNT(*) AS cnt FROM points AS P
INNER JOIN areas AS A ON A.id = P.area_id
WHERE A.lft >= {$area['Area']['lft']} AND A.rght <= {$area['Area']['rght']}");
            $countSchedule = $this->Area->query("SELECT COUNT(*) AS cnt FROM areas_models AS AM
INNER JOIN areas AS A ON A.id = AM.area_id
WHERE model = 'Schedule' AND A.lft >= {$area['Area']['lft']} AND A.rght <= {$area['Area']['rght']}");
            if ($countMember[0][0]['cnt'] + $countPoint[0][0]['cnt'] + $countSchedule[0][0]['cnt'] > 0) {
                $this->Area->save(array('Area' => array(
                        'id' => $area['Area']['id'],
                        'countMember' => $countMember[0][0]['cnt'],
                        'countPoint' => $countPoint[0][0]['cnt'],
                        'countSchedule' => $countSchedule[0][0]['cnt'],
                        )));
            }
        }
    }

    function fetchFeeds() {
        $this->uses = array('FeedItem');
        $this->_loadModels();
        $timeToday = mktime();
        $feeds = $this->FeedItem->Feed->find('all', array(
            'conditions' => array(
                'Feed.is_active' => 1,
                'OR' => array(
                    'Feed.created = Feed.modified',
                    'Feed.modified <' => date('Y-m-d', $timeToday) . ' 00:00:00',
            )),
                ));
        foreach ($feeds AS $feed) {
            $rssFileCache = trim(file_get_contents($feed['Feed']['url']));
            $rssFileCache = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $rssFileCache);
            $xml = simplexml_load_string($rssFileCache);
            $this->FeedItem->Feed->updateAll(array('modified' => 'now()'), array('id = ' . $feed['Feed']['id']));
            if (!empty($xml->channel->item)) {
                foreach ($xml->channel->item AS $item) {
                    if ($this->FeedItem->find('count', array(
                                'conditions' => array(
                                    'url' => strval($item->link)
                                ),
                            )) == 0) {
                        $theDate = strtotime(strval($item->pubDate));
                        if (empty($theDate)) {
                            $theDate = strtotime(substr($item->pubDate, 0, 10));
                        }
                        $this->FeedItem->create();
                        $this->FeedItem->save(array('FeedItem' => array(
                                'feed_id' => $feed['Feed']['id'],
                                'url' => strval($item->link),
                                'title' => "[{$feed['Feed']['title']}]" . strval($item->title),
                                'summary' => str_replace(array("\n", "\t", ' ', '&nbsp;'), '', trim(strip_tags(strval($item->description)))),
                                'the_date' => date('Y-m-d', $theDate),
                                )));
                    }
                }
            }
        }
    }

}