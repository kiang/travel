<?php

App::uses('Sanitize', 'Utility');

class PointsController extends AppController {

    var $name = 'Points';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index', 'view', 'block_new',
            'block_hot', 'block_comment', 'near', 'area', 'add', 'finding',
            'auto_list', 'json_near');
    }

    function index($foreignModel = null, $foreignId = 0, $keyword = '') {
        $title = '地點';
        $this->set('title_for_layout', $title);
    }

    function block_new() {
        $this->set('items', $this->Point->find('all', array(
                    'fields' => array('Point.id', 'Point.title_zh_tw', 'Point.title_en_us', 'Point.title', 'Point.created'),
                    'limit' => 15,
                    'order' => array('created' => 'desc'),
                )));
    }

    function block_hot($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 15 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/points/block_hot/' . $offset;
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Point->find('all', array(
                'order' => array('Point.count_comments DESC'),
                'limit' => 15,
                'offset' => $offset,
                'conditions' => array('Point.count_comments > 0'),
                    ));
            foreach ($items AS $key => $item) {
                $comment = $this->Point->Comment->find('first', array(
                    'conditions' => array(
                        'Comment.model' => 'Point',
                        'Comment.is_active' => 1,
                        'Comment.foreign_key' => $item['Point']['id'],
                    ),
                    'order' => array('Comment.id DESC'),
                    'contain' => array(
                        'Member' => array(
                            'fields' => array('id', 'dirname', 'basename', 'gender')
                        ),
                    ),
                        ));
                $items[$key]['Comment'] = $comment['Comment'];
                $items[$key]['Member'] = $comment['Member'];
            }
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function block_comment($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 15 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/points/block_comment/' . $offset;
        $items = Cache::read($key);
        $items = false;
        if (false === $items) {

            $items = $this->Point->find('all', array(
                'fields' => array('Point.*'),
                'order' => array('Comment.id DESC'),
                'limit' => 15,
                'offset' => $offset,
                'group' => array('Point.id'),
                'conditions' => array('Point.count_comments > 0'),
                'joins' => array(
                    array(
                        'table' => 'comments',
                        'alias' => 'Comment',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Point.id = Comment.foreign_key',
                        ),
                    ),
                ),
                    ));
            foreach ($items AS $key => $item) {
                $comment = $this->Point->Comment->find('first', array(
                    'conditions' => array(
                        'Comment.model' => 'Point',
                        'Comment.is_active' => 1,
                        'Comment.foreign_key' => $item['Point']['id'],
                    ),
                    'order' => array('Comment.id DESC'),
                    'contain' => array(
                        'Member' => array(
                            'fields' => array('id', 'dirname', 'basename', 'gender')
                        ),
                    ),
                        ));
                $items[$key]['Comment'] = $comment['Comment'];
                $items[$key]['Member'] = $comment['Member'];
            }
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function view($id = null) {
        $id = intval($id);
        if ($id <= 0 || !$this->request->data = $this->Point->find('first', array(
            'conditions' => array('Point.id' => $id),
            'contain' => array(
                'Area' => array('fields' => array('id')),
            ),
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        } else {
            if ($this->request->data['Area']['id']) {
                $this->set('areas', $this->Point->Area->getPath($this->request->data['Area']['id'], array('id', 'name')));
            }
            $this->Point->updateAll(
                    array('Point.count_views' => 'Point.count_views + 1'), array('Point.id' => $id)
            );
            $title = array();
            foreach (array('title', 'title_en_us', 'title_zh_tw') AS $titleField) {
                if (!empty($this->request->data['Point'][$titleField])) {
                    $title[] = $this->request->data['Point'][$titleField];
                }
            }
            $this->set('title_for_layout', implode(' | ', $title));
        }
    }

    function add($scheduleLineId = 0, $from = 'ScheduleLine') {
        if (empty($this->loginMember['id'])) {
            $this->render('../Members/login');
            return;
        } else {
            $scheduleLineId = intval($scheduleLineId);
            if ($scheduleLineId > 0) {
                $this->loadModel('ScheduleLine');
                switch ($from) {
                    case 'ScheduleLine':
                        /*
                         * 確認目前操作中的使用者是否擁有這個行程資料
                         */
                        if (!$scheduleLine = $this->ScheduleLine->getPoint(array(
                            'ScheduleLine.id' => $scheduleLineId,
                            'ScheduleLine.model' => 'Point',
                            'Schedule.member_id' => $this->loginMember['id'],
                                ))) {
                            $scheduleLineId = 0;
                            $from = '';
                        }
                        break;
                    case 'ScheduleDay':
                        /*
                         * 確認目前操作中的使用者是否擁有這個行程資料
                         */
                        if (!$scheduleLine = $this->ScheduleLine->ScheduleDay->find('first', array(
                            'fields' => array(
                                'ScheduleDay.point_name', 'ScheduleDay.id',
                                'ScheduleDay.schedule_id', 'ScheduleDay.latitude',
                                'ScheduleDay.longitude'
                            ),
                            'conditions' => array(
                                'ScheduleDay.id' => $scheduleLineId,
                                'Schedule.member_id' => $this->loginMember['id'],
                            ),
                            'contain' => array('Schedule'),
                                ))) {
                            $scheduleLineId = 0;
                            $from = '';
                        }
                        break;
                    default:
                        $scheduleLineId = 0;
                        $from = '';
                }
            }

            if (!empty($this->request->data)) {
                $this->request->data['Point']['is_active'] = 1;
                $this->Point->create();
                if ($this->Point->save($this->request->data)) {
                    $pointId = $this->Point->getInsertID();
                    $this->request->data = Sanitize::clean($this->request->data, array(
                                'encode' => false,
                            ));
                    if (!isset($this->Submit)) {
                        $this->loadModel('Submit');
                    }
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
                    $this->Session->setFlash('資料已經儲存，感謝您的提供！');
                    if ($scheduleLineId > 0) {
                        /*
                         * 找出同一個行程表所有的 ScheduleDay.id
                         */
                        $scheduleDays = $this->ScheduleLine->ScheduleDay->find('all', array(
                            'conditions' => array(
                                'ScheduleDay.schedule_id' => $scheduleLine['ScheduleDay']['schedule_id']
                            ),
                            'fields' => array('ScheduleDay.id'),
                                ));
                        if (!empty($scheduleDays)) {
                            $scheduleDays = Set::extract('{n}.ScheduleDay.id', $scheduleDays);
                            if (!empty($this->request->data['Point']['title_zh_tw'])) {
                                $pointTitle = $this->request->data['Point']['title_zh_tw'];
                            } elseif (!empty($this->request->data['Point']['title_en_us'])) {
                                $pointTitle = $this->request->data['Point']['title_en_us'];
                            } else {
                                $pointTitle = $this->request->data['Point']['title'];
                            }
                            $nameStack = array();
                            if (!empty($scheduleLine['ScheduleLine']['point_name'])) {
                                $nameStack[] = Sanitize::clean($scheduleLine['ScheduleLine']['point_name'], array(
                                            'encode' => false,
                                        ));
                            }
                            if (!empty($scheduleLine['ScheduleDay']['point_name'])) {
                                $nameStack[] = Sanitize::clean($scheduleLine['ScheduleDay']['point_name'], array(
                                            'encode' => false,
                                        ));
                            }
                            if (!empty($this->request->data['Point']['title_zh_tw']) &&
                                    !in_array($this->request->data['Point']['title_zh_tw'], $nameStack)
                            ) {
                                $nameStack[] = $this->request->data['Point']['title_zh_tw'];
                            }
                            if (!empty($this->request->data['Point']['title_en_us']) &&
                                    !in_array($this->request->data['Point']['title_en_us'], $nameStack)
                            ) {
                                $nameStack[] = $this->request->data['Point']['title_en_us'];
                            }
                            if (!empty($this->request->data['Point']['title']) &&
                                    !in_array($this->request->data['Point']['title'], $nameStack)
                            ) {
                                $nameStack[] = $this->request->data['Point']['title'];
                            }
                            /*
                             * 更新 ScheduleLine
                             */
                            $this->ScheduleLine->updateAll(array(
                                'ScheduleLine.foreign_key' => $pointId,
                                'ScheduleLine.point_name' => '\'' . $pointTitle . '\'',
                                'ScheduleLine.latitude' => $this->request->data['Point']['latitude'],
                                'ScheduleLine.longitude' => $this->request->data['Point']['longitude'],
                                    ), array(
                                'ScheduleLine.foreign_key' => 0,
                                'ScheduleLine.model' => 'Point',
                                'ScheduleLine.schedule_day_id IN (' . implode(',', $scheduleDays) . ')',
                                'ScheduleLine.point_name IN (\'' . implode('\',\'', $nameStack) . '\')',
                            ));

                            /*
                             * 更新 ScheduleDay
                             */
                            $this->ScheduleLine->ScheduleDay->updateAll(array(
                                'ScheduleDay.point_id' => $pointId,
                                'ScheduleDay.point_name' => '\'' . $pointTitle . '\'',
                                'ScheduleDay.latitude' => $this->request->data['Point']['latitude'],
                                'ScheduleDay.longitude' => $this->request->data['Point']['longitude'],
                                    ), array(
                                'ScheduleDay.point_id' => 0,
                                'ScheduleDay.id IN (' . implode(',', $scheduleDays) . ')',
                                'ScheduleDay.point_name IN (\'' . implode('\',\'', $nameStack) . '\')',
                            ));
                            foreach ($scheduleDays AS $scheduleDay) {
                                /*
                                 * 逐日更新 summary
                                 */
                                $this->ScheduleLine->scheduleDayId = $scheduleDay;
                                $this->ScheduleLine->updateScheduleDay();
                            }
                        }
                        $this->redirect('/schedules/view/' .
                                $scheduleLine['ScheduleDay']['schedule_id'] . '/' .
                                $scheduleLine['ScheduleDay']['id']
                        );
                    } else {
                        $this->redirect(array('action' => 'view', $pointId));
                    }
                } else {
                    $this->Session->setFlash('資料儲存失敗，請重試');
                }
            } elseif (!empty($scheduleLine)) {
                $this->request->data['Point']['title'] = isset($scheduleLine['ScheduleLine']['point_name']) ?
                        $scheduleLine['ScheduleLine']['point_name'] : $scheduleLine['ScheduleDay']['point_name'];
                $this->request->data['Point']['latitude'] = isset($scheduleLine['ScheduleLine']['latitude']) ?
                        $scheduleLine['ScheduleLine']['latitude'] : $scheduleLine['ScheduleDay']['latitude'];
                $this->request->data['Point']['longitude'] = isset($scheduleLine['ScheduleLine']['longitude']) ?
                        $scheduleLine['ScheduleLine']['longitude'] : $scheduleLine['ScheduleDay']['longitude'];
            }
            if (empty($this->request->data['Point']['latitude'])) {
                $this->request->data['Point']['latitude'] = '0';
                $this->request->data['Point']['longitude'] = '0';
            }
            if (!empty($this->request->data['Point']['area_id'])) {
                /*
                 * 取得選擇的區域
                 */
                $this->set('areaPath', $this->Point->Area->getPath($this->request->data['Point']['area_id'], array('name')));
            }
            $this->set('scheduleLineId', $scheduleLineId);
            $this->set('from', $from);
            $this->set('pointTypes', $this->Point->PointType->find('list'));
        }
        $this->set('title_for_layout', '新增地點');
    }

    function edit($id = null) {
        $id = intval($id);
        if ($id <= 0) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        }
        if (!empty($this->request->data)) {
            $this->Point->set($this->request->data);
            if ($this->Point->validates()) {
                if (!isset($this->Submit)) {
                    $this->loadModel('Submit');
                }
                $this->Submit->create();
                $this->Submit->save(array('Submit' => array(
                        'model' => 'Point',
                        'foreign_key' => $id,
                        'member_id' => $this->loginMember['id'],
                        'member_name' => $this->loginMember['username'],
                        'is_new' => 0,
                        'data' => serialize($this->Point->data),
                        )));
                $this->Session->setFlash('您提供的資料我們已經收到，我們會儘快處理，感謝您的協助！');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        } else {
            $this->request->data = $this->Point->find('first', array(
                'conditions' => array('Point.id' => $id),
                'contain' => array('PointType')
                    ));
        }
        $this->set('id', $id);
        $this->set('areaPath', $this->Point->Area->getPath($this->request->data['Point']['area_id'], array('name')));
        $this->set('pointTypes', $this->Point->PointType->find('list'));
        $this->set('title_for_layout', '編輯地點');
    }

    function auto_list() {
        $keyword = Sanitize::clean($_GET['term']);
        $keyword = html_entity_decode(trim($keyword), ENT_QUOTES, 'UTF-8');
        $result = array();
        if (!empty($keyword)) {
            $items = $this->Point->find('all', array(
                'limit' => 10,
                'fields' => array('Point.id', 'Point.title_zh_tw',
                    'Point.title_en_us', 'Point.title', 'Point.latitude',
                    'Point.longitude'),
                'conditions' => array('OR' => array(
                        'Point.title_zh_tw LIKE' => '%' . $keyword . '%',
                        'Point.title_en_us LIKE' => '%' . $keyword . '%',
                        'Point.title LIKE' => '%' . $keyword . '%',
                )),
                    ));
            foreach ($items AS $key => $item) {
                $labels = array();
                foreach (array('title_zh_tw', 'title_en_us', 'title') AS $pKey) {
                    if (!empty($item['Point'][$pKey])) {
                        $labels[] = $item['Point'][$pKey];
                    }
                }
                $label = implode(' | ', $labels);
                $result[] = array(
                    'id' => $item['Point']['id'],
                    'latitude' => $item['Point']['latitude'],
                    'longitude' => $item['Point']['longitude'],
                    'label' => $label,
                    'value' => $label,
                );
            }
        }
        $this->set('items', $result);
    }

    function near($foreignModel = '', $foreignId = 0, $offset = 0) {
        if ($foreignModel != 'Finding' || strlen($foreignId) != 40) {
            $foreignId = intval($foreignId);
        }
        if ($foreignModel == 'ScheduleLine' || $foreignModel == 'Finding') {
            $this->loadModel($foreignModel);
        } elseif ($foreignModel != 'Point') {
            $foreignId = 0;
        }
        if ($offset < 0 || $offset % 12 != 0) {
            $offset = 0;
        }
        $items = array();
        if (!empty($foreignId) && $foreignPoint = $this->$foreignModel->find('first', array(
            'fields' => array('latitude', 'longitude'),
            'conditions' => array(
                $foreignModel . '.id' => $foreignId
            ),
                ))) {
            if (empty($foreignPoint[$foreignModel]['latitude']) ||
                    empty($foreignPoint[$foreignModel]['longitude'])) {
                /*
                 * 如果取得的資料是空白的，試著查看看住址能否找到座標
                 */
            }
            $key = "/points/near/{$foreignModel}/{$foreignId}/{$offset}";
            $items = Cache::read($key);
            if (false === $items) {
                $items = $this->Point->find('near', array(
                    'fields' => array(
                        'id', 'title_zh_tw', 'title_en_us', 'title', 'latitude',
                        'longitude'
                    ),
                    'limit' => 12,
                    'distance' => 30,
                    'unit' => 'k',
                    'address' => array(
                        $foreignPoint[$foreignModel]['latitude'],
                        $foreignPoint[$foreignModel]['longitude'],
                    ),
                    'offset' => $offset,
                        ));
                foreach ($items AS $key => $val) {
                    $items[$key]['Point']['rank'] = $this->Point->Comment->field('rank', array(
                        'model' => 'Point',
                        'foreign_key' => $val['Point']['id'],
                            ), array('Comment.id DESC'));
                }
                Cache::write($key, $items);
            }
        }
        $this->set('items', $items);
        $this->set('url', array($foreignModel, $foreignId));
        $this->set('offset', $offset);
    }

    function area($areaId = 0, $offset = 0) {
        $areaId = intval($areaId);
        $offset = intval($offset);
        if ($offset < 0 || $offset % 15 != 0) {
            $offset = 0;
        }
        $scope = array(
            'Point.is_active' => 1);
        $contain = array(
            'Comment' => array(
                'Member' => array('fields' => array('dirname', 'basename', 'username', 'gender'),),
                'limit' => 1,
                'order' => array('Comment.created DESC')
            )
        );
        if ($areaId > 0 && $area = $this->Point->Area->find('first', array(
            'fields' => array('lft', 'rght'),
            'conditions' => array(
                'Area.id' => $areaId,
            ),
                ))) {
            if ($area['Area']['rght'] - $area['Area']['lft'] == 1) {
                $scope['Point.area_id'] = $areaId;
            } else {
                $contain['Area'] = array(
                    'fields' => array('id'),
                );
                $scope['Area.lft >='] = $area['Area']['lft'];
                $scope['Area.rght <='] = $area['Area']['rght'];
            }
        }
        $key = "/points/area/{$areaId}/{$offset}";
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Point->find('all', array(
                'contain' => $contain,
                'conditions' => $scope,
                'offset' => $offset,
                'limit' => 15,
                'order' => array(
                    'Point.count_comments DESC',
                    'Point.modified' => 'desc'
                ),
                    ));
            Cache::write($key, $items);
        }

        $this->set('url', array($areaId));
        $this->set('items', $items);
        $this->set('offset', $offset);
    }

    function get_address($latitude, $longitude) {
        $address = '';
        $this->Point->geocode($latitude . ',' . $longitude);
        $result = $this->Point->getResult();
        if (!empty($result->Placemark[0]->address)) {
            $address = $result->Placemark[0]->address;
        }
        $this->set('address', $address);
    }

    function finding($keyword = '', $offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 12 != 0) {
            $offset = 0;
        }
        $conditions = array();
        if (!empty($keyword)) {
            $keyword = Sanitize::clean($keyword);
            $conditions = array(
                'OR' => array(
                    'Point.title_zh_tw LIKE' => "%{$keyword}%",
                    'Point.title_en_us LIKE' => "%{$keyword}%",
                    'Point.title LIKE' => "%{$keyword}%",
                    'Point.address_zh_tw LIKE' => "%{$keyword}%",
                    'Point.address_en_us LIKE' => "%{$keyword}%",
                    'Point.address LIKE' => "%{$keyword}%",
                    'Point.website LIKE' => "%{$keyword}%",
                ),
            );
        }
        $this->set('url', array($keyword));
        $items = $this->Point->find('all', array(
            'conditions' => $conditions,
            'offset' => $offset,
            'limit' => 12,
            'order' => array(
                'Point.created' => 'desc'
            ),
                ));
        $this->set('items', $items);
        $this->set('offset', $offset);
    }

    function json_near($lat = 0, $lng = 0) {
        $lat = floatval($lat);
        $lng = floatval($lng);
        $items = array();
        if (!empty($lng) && !empty($lat)) {
            $items = $this->Point->find('near', array(
                'fields' => array(
                    'id', 'title_zh_tw', 'title_en_us', 'title', 'latitude',
                    'longitude'
                ),
                'limit' => 12,
                'distance' => 1,
                'unit' => 'k',
                'address' => array(
                    $lat,
                    $lng,
                ),
                    ));
        }
        echo json_encode($items);
        exit();
    }

    function admin_index($keyword = '') {
        if (isset($this->request->query['keyword'])) {
            $keyword = $this->request->query['keyword'];
        }
        if (!empty($keyword)) {
            $keyword = Sanitize::clean($keyword);
        }
        $scope = array();
        if (!empty($keyword)) {
            $scope['OR'] = array(
                'Point.title_zh_tw LIKE' => '%' . $keyword . '%',
                'Point.title_en_us LIKE' => '%' . $keyword . '%',
                'Point.title LIKE' => '%' . $keyword . '%',
                'Point.address_zh_tw LIKE' => '%' . $keyword . '%',
                'Point.address_en_us LIKE' => '%' . $keyword . '%',
                'Point.address LIKE' => '%' . $keyword . '%',
                'Point.website LIKE' => '%' . $keyword . '%',
            );
        }
        $this->Paginator->settings['Point'] = array(
            'limit' => 20,
            'order' => array('modified' => 'desc'),
        );
        $this->set('items', $this->Paginator->paginate($this->Point, $scope));
        $this->set('url', array($keyword));
        $this->set('keyword', $keyword);
    }

    function admin_view($id = null) {
        if (!$id || !$this->request->data = $this->Point->read(null, $id)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->request->data)) {
            if ($this->Point->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Point');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Point.data', $this->request->data);
                $this->Session->write('form.Point.validationErrors', $this->Point->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Point->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Point.data')) {
            $this->Point->validationErrors = $this->Session->read('form.Point.validationErrors');
            $this->Session->delete('form.Point');
        }
        if ($id > 0) {
            $this->request->data = $this->Point->find('first', array(
                'conditions' => array('Point.id' => $id),
                'contain' => array(
                    'PointType' => array(
                        'fields' => array('id')
                    ),
                ),
                    ));
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Point'][$key])) {
                        $this->request->data['Point'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
        if (!empty($this->request->data['Point']['area_id'])) {
            /*
             * 取得選擇的區域
             */
            $this->set('areaPath', $this->Point->Area->getPath($this->request->data['Point']['area_id'], array('name')));
        }
        $this->set('pointTypes', $this->Point->PointType->find('list'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Point->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect($this->referer());
    }

    function admin_types() {
        $this->set('url', array());
        $this->set('items', $this->Paginator->paginate($this->Point->PointType));
    }

    function admin_type_add() {
        if (!empty($this->request->data)) {
            $this->Point->PointType->create();
            if ($this->Point->PointType->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'types'));
            } else {
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
    }

    function admin_type_edit($typeId = 0) {
        $typeId = intval($typeId);
        if (!empty($this->request->data)) {
            $this->request->data['PointType']['id'] = $typeId;
            if ($this->Point->PointType->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'types'));
            } else {
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->Point->PointType->read(null, $typeId);
        }
    }

    public function admin_loop() {
        if (!empty($this->request->data)) {
            if ($this->Point->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Point');
                $id = $this->Point->field('id', array(
                    'Point.area_id' => 0,
                        ), array(
                    'Point.id DESC'
                        ));
            } else {
                $this->Session->write('form.Point.data', $this->request->data);
                $this->Session->write('form.Point.validationErrors', $this->Point->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
                $id = $this->request->data['Point']['id'];
            }
        } else {
            $id = $this->Point->field('id', array(
                'Point.area_id' => 0,
                    ), array(
                'Point.latitude',
                'Point.longitude'
                    ));
        }
        $this->set('id', $id);
    }

    function admin_add($id = 0) {
        $id = intval($id);
        if ($id > 0 && empty($this->request->data)) {
            $this->request->data = $this->Point->read(null, $id);
        } elseif (!empty($this->request->data)) {
            if (isset($this->request->data['Point']['id'])) {
                unset($this->request->data['Point']['id']);
            }
            $this->Point->create();
            if ($this->Point->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Point');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Point.data', $this->request->data);
                $this->Session->write('form.Point.validationErrors', $this->Point->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Point->read(null, $id);
    }

    public function admin_kml_import() {
        $pointStack = array();
        if (!empty($this->request->data['Point']['kml']['size'])) {
            $content = file_get_contents($this->request->data['Point']['kml']['tmp_name']);
            $pos = 0;
            while ($pos = strpos($content, '<Placemark>', $pos)) {
                $posEnd = strpos($content, '</Placemark>', $pos) + 12;
                $point = @simplexml_load_string(substr($content, $pos, $posEnd - $pos));
                if (!empty($point->Point->coordinates) && !empty($point->name)) {
                    $name = str_replace(',', '', trim(strval($point->name)));
                    if ($this->Point->find('count', array(
                                'conditions' => array('OR' => array(
                                        'title' => $name,
                                        'title_zh_tw' => $name,
                                        'title_en_us' => $name,
                                ))
                            )) == 0) {
                        $coordinates = explode(',', strval($point->Point->coordinates));
                        $pointStack[] = array(
                            'title' => $name,
                            'latitude' => $coordinates[1],
                            'longitude' => $coordinates[0],
                        );
                    }
                }
                $pos = $posEnd;
            }
        }
        $this->set('pointStack', $pointStack);
        if (!empty($this->request->data['Point']['0'])) {
            foreach ($this->request->data['Point'] AS $point) {
                if (!empty($point['import'])) {
                    $this->Point->create();
                    $this->Point->save(array('Point' => array(
                            'is_active' => 1,
                            'title' => $point['title'],
                            'latitude' => $point['latitude'],
                            'longitude' => $point['longitude'],
                            'PointType' => array(
                                '2'
                            )
                            )));
                }
            }
        }
    }

}