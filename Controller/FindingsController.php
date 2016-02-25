<?php

App::uses('Sanitize', 'Utility');

class FindingsController extends AppController {

    var $name = 'Findings';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index');
    }

    function index() {
        $title = '探索';
        //$this->Session->delete('Finding.list');
        $findingList = $this->Session->read('Finding.list');
        $keyword = '';
        if (!empty($this->request->data['Finding']['keyword'])) {
            $keyword = trim(Sanitize::clean($this->request->data['Finding']['keyword']));
            if (!empty($keyword)) {
                $hash = Security::hash($keyword);
                if (!empty($findingList[$hash])) {
                    /*
                     * 在 Session 中取得
                     */
                    $point = $findingList[$hash];
                    unset($findingList[$hash]);
                    $findingList[$hash] = $point;
                } elseif ($point = $this->Finding->find('first', array(
                    'conditions' => array('Finding.id' => $hash),
                        ))) {
                    /*
                     * 在 findings table 取得
                     */
                    $point = $findingList[$hash] = $point['Finding'];
                    $this->Finding->updateAll(array(
                        'count' => 'count + 1',
                        'Finding.modified' => '\'' . date('Y-m-d H:i:s') . '\'',
                            ), array('id' => $hash));
                } else {
                    $this->loadModel('Point');
                    if ($point = $this->Point->find('first', array(
                        'conditions' => array('OR' => array(
                                'Point.title_zh_tw' => $keyword,
                                'Point.title_en_us' => $keyword,
                                'Point.title' => $keyword,
                                'Point.address_zh_tw' => $keyword,
                                'Point.address_en_us' => $keyword,
                                'Point.address' => $keyword,
                        )),
                            ))) {
                        $data['Finding'] = array(
                            'id' => $hash,
                            'keyword' => $keyword,
                            'latitude' => $point['Point']['latitude'],
                            'longitude' => $point['Point']['longitude'],
                            'count' => 1,
                            'model' => 'Point',
                            'foreign_key' => $point['Point']['id'],
                        );
                        $this->Finding->create();
                        $this->Finding->save($data);
                        $point = $findingList[$hash] = $data['Finding'];
                    }
                }
                if (!$point && $point = $this->Finding->geocode($keyword)) {
                    /*
                     * 透過 Google 找到座標
                     */
                    $data['Finding'] = array(
                        'id' => $hash,
                        'keyword' => $keyword,
                        'longitude' => $point[0],
                        'latitude' => $point[1],
                        'count' => 1,
                        'model' => '',
                        'foreign_key' => 0,
                    );
                    $this->Finding->create();
                    $this->Finding->save($data);
                    $point = $findingList[$hash] = $data['Finding'];
                }
                $this->set('point', $point);
                if (!$point) {
                    $this->Session->setFlash('您輸入的位置目前找不到合適的資料！');
                } else {
                    $title .= $keyword;
                }
                while (count($findingList) > 30) {
                    array_shift($findingList);
                }
            }
        }
        $this->set('keyword', $keyword);
        $this->Session->write('Finding.list', $findingList);
        $this->set('findingList', $findingList);
        $this->set('newFindings', $this->Finding->find('all', array(
                    'fields' => array('keyword'),
                    'order' => array('Finding.count DESC', 'Finding.modified DESC'),
                    'limit' => 8,
                )));
        $this->set('title_for_layout', $title);
    }

    function new_point($findingId = null) {
        if (strlen($findingId) != 40 || !$finding = $this->Finding->find('first', array(
            'conditions' => array('id' => $findingId),
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        }
        if (!empty($this->request->data)) {
            if (!isset($this->Submit)) {
                $this->loadModel('Submit');
                $this->loadModel('Point');
            }
            $this->request->data['Point']['is_active'] = 1;
            $this->Point->create();
            if ($this->Point->save($this->request->data)) {
                $pointId = $this->Point->getInsertID();
                $this->Submit->create();
                $now = date('Y-m-d H:i:s');
                $this->Submit->save(array('Submit' => array(
                        'model' => 'Point',
                        'foreign_key' => $pointId,
                        'member_id' => $this->loginMember['id'],
                        'member_name' => $this->loginMember['username'],
                        'is_new' => 1,
                        'data' => serialize($this->Point->data),
                        'accepted' => $now,
                        'created' => $now
                        )));
                $this->Finding->updateAll(array(
                    'model' => '\'Point\'', 'foreign_key' => $pointId
                        ), array('Finding.id' => $findingId));
                $this->Session->write('Finding.list.' . $findingId . '.model', 'Point');
                $this->Session->write('Finding.list.' . $findingId . '.foreign_key', $pointId);
                $this->Session->setFlash('資料已經儲存，感謝您的提供！');
                $this->Session->delete('form.Point');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Point.data', $this->request->data);
                $this->Session->write('form.Point.validationErrors', $this->Point->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->Session->write('form.Point.data.Point', array(
            'title' => $finding['Finding']['keyword'],
            'address' => $finding['Finding']['keyword'],
            'latitude' => $finding['Finding']['latitude'],
            'longitude' => $finding['Finding']['longitude'],
        ));
        $this->set('findingId', $findingId);
    }

}