<?php

App::uses('Sanitize', 'Utility');

class ToursController extends AppController {

    var $name = 'Tours';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index', 'view', 'near', 'area', 'add', 'finding');
    }

    function index($foreignModel = null, $foreignId = 0, $keyword = '') {
        $title = '旅行社';
        $this->set('title_for_layout', $title);
    }

    function view($id = null) {
        $id = intval($id);
        if ($id <= 0 || !$this->request->data = $this->Tour->find('first', array(
            'conditions' => array('Tour.id' => $id),
            'contain' => array(
                'Area' => array('fields' => array('id')),
            ),
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        } else {
            if ($this->request->data['Area']['id']) {
                $this->set('areas', $this->Tour->Area->getPath($this->request->data['Area']['id'], array('id', 'name')));
            }
            $this->Tour->updateAll(
                    array('Tour.count_views' => 'Tour.count_views + 1'), array('Tour.id' => $id)
            );
            $title = array();
            foreach (array('title', 'title_en_us', 'title_zh_tw') AS $titleField) {
                if (!empty($this->request->data['Tour'][$titleField])) {
                    $title[] = $this->request->data['Tour'][$titleField];
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
                        if (!$scheduleLine = $this->ScheduleLine->getTour(array(
                            'ScheduleLine.id' => $scheduleLineId,
                            'ScheduleLine.model' => 'Tour',
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
                                'ScheduleDay.tour_name', 'ScheduleDay.id',
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
                $this->request->data['Tour']['is_active'] = 1;
                $this->Tour->create();
                if ($this->Tour->save($this->request->data)) {
                    $tourId = $this->Tour->getInsertID();
                    $this->request->data = Sanitize::clean($this->request->data, array(
                                'encode' => false,
                            ));
                    if (!isset($this->Submit)) {
                        $this->loadModel('Submit');
                    }
                    $this->Submit->create();
                    $now = date('Y-m-d H:i:s');
                    $this->Submit->save(array('Submit' => array(
                            'model' => 'Tour',
                            'foreign_key' => $tourId,
                            'member_id' => $this->loginMember['id'],
                            'member_name' => $this->loginMember['username'],
                            'is_new' => 1,
                            'data' => serialize($this->Tour->data),
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
                            if (!empty($this->request->data['Tour']['title_zh_tw'])) {
                                $tourTitle = $this->request->data['Tour']['title_zh_tw'];
                            } elseif (!empty($this->request->data['Tour']['title_en_us'])) {
                                $tourTitle = $this->request->data['Tour']['title_en_us'];
                            } else {
                                $tourTitle = $this->request->data['Tour']['title'];
                            }
                            $nameStack = array();
                            if (!empty($scheduleLine['ScheduleLine']['tour_name'])) {
                                $nameStack[] = Sanitize::clean($scheduleLine['ScheduleLine']['tour_name'], array(
                                            'encode' => false,
                                        ));
                            }
                            if (!empty($scheduleLine['ScheduleDay']['tour_name'])) {
                                $nameStack[] = Sanitize::clean($scheduleLine['ScheduleDay']['tour_name'], array(
                                            'encode' => false,
                                        ));
                            }
                            if (!empty($this->request->data['Tour']['title_zh_tw']) &&
                                    !in_array($this->request->data['Tour']['title_zh_tw'], $nameStack)
                            ) {
                                $nameStack[] = $this->request->data['Tour']['title_zh_tw'];
                            }
                            if (!empty($this->request->data['Tour']['title_en_us']) &&
                                    !in_array($this->request->data['Tour']['title_en_us'], $nameStack)
                            ) {
                                $nameStack[] = $this->request->data['Tour']['title_en_us'];
                            }
                            if (!empty($this->request->data['Tour']['title']) &&
                                    !in_array($this->request->data['Tour']['title'], $nameStack)
                            ) {
                                $nameStack[] = $this->request->data['Tour']['title'];
                            }
                            /*
                             * 更新 ScheduleLine
                             */
                            $this->ScheduleLine->updateAll(array(
                                'ScheduleLine.foreign_key' => $tourId,
                                'ScheduleLine.tour_name' => '\'' . $tourTitle . '\'',
                                'ScheduleLine.latitude' => $this->request->data['Tour']['latitude'],
                                'ScheduleLine.longitude' => $this->request->data['Tour']['longitude'],
                                    ), array(
                                'ScheduleLine.foreign_key' => 0,
                                'ScheduleLine.model' => 'Tour',
                                'ScheduleLine.schedule_day_id IN (' . implode(',', $scheduleDays) . ')',
                                'ScheduleLine.tour_name IN (\'' . implode('\',\'', $nameStack) . '\')',
                            ));

                            /*
                             * 更新 ScheduleDay
                             */
                            $this->ScheduleLine->ScheduleDay->updateAll(array(
                                'ScheduleDay.tour_id' => $tourId,
                                'ScheduleDay.tour_name' => '\'' . $tourTitle . '\'',
                                'ScheduleDay.latitude' => $this->request->data['Tour']['latitude'],
                                'ScheduleDay.longitude' => $this->request->data['Tour']['longitude'],
                                    ), array(
                                'ScheduleDay.tour_id' => 0,
                                'ScheduleDay.id IN (' . implode(',', $scheduleDays) . ')',
                                'ScheduleDay.tour_name IN (\'' . implode('\',\'', $nameStack) . '\')',
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
                        $this->redirect(array('action' => 'view', $tourId));
                    }
                } else {
                    $this->Session->setFlash('資料儲存失敗，請重試');
                }
            } elseif (!empty($scheduleLine)) {
                $this->request->data['Tour']['title'] = isset($scheduleLine['ScheduleLine']['tour_name']) ?
                        $scheduleLine['ScheduleLine']['tour_name'] : $scheduleLine['ScheduleDay']['tour_name'];
                $this->request->data['Tour']['latitude'] = isset($scheduleLine['ScheduleLine']['latitude']) ?
                        $scheduleLine['ScheduleLine']['latitude'] : $scheduleLine['ScheduleDay']['latitude'];
                $this->request->data['Tour']['longitude'] = isset($scheduleLine['ScheduleLine']['longitude']) ?
                        $scheduleLine['ScheduleLine']['longitude'] : $scheduleLine['ScheduleDay']['longitude'];
            }
            if (empty($this->request->data['Tour']['latitude'])) {
                $this->request->data['Tour']['latitude'] = '0';
                $this->request->data['Tour']['longitude'] = '0';
            }
            if (!empty($this->request->data['Tour']['area_id'])) {
                /*
                 * 取得選擇的區域
                 */
                $this->set('areaPath', $this->Tour->Area->getPath($this->request->data['Tour']['area_id'], array('name')));
            }
            $this->set('scheduleLineId', $scheduleLineId);
            $this->set('from', $from);
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
            $this->Tour->set($this->request->data);
            if ($this->Tour->validates()) {
                if (!isset($this->Submit)) {
                    $this->loadModel('Submit');
                }
                $this->Submit->create();
                $this->Submit->save(array('Submit' => array(
                        'model' => 'Tour',
                        'foreign_key' => $id,
                        'member_id' => $this->loginMember['id'],
                        'member_name' => $this->loginMember['username'],
                        'is_new' => 0,
                        'data' => serialize($this->Tour->data),
                        )));
                $this->Session->setFlash('您提供的資料我們已經收到，我們會儘快處理，感謝您的協助！');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        } else {
            $this->request->data = $this->Tour->find('first', array(
                'conditions' => array('Tour.id' => $id),
                    ));
        }
        $this->set('id', $id);
        $this->set('areaPath', $this->Tour->Area->getPath($this->request->data['Tour']['area_id'], array('name')));
        $this->set('title_for_layout', '編輯地點');
    }

    function near($foreignModel = '', $foreignId = 0, $offset = 0) {
        if ($foreignModel != 'Finding' || strlen($foreignId) != 40) {
            $foreignId = intval($foreignId);
        }
        if ($foreignModel == 'ScheduleLine' || $foreignModel == 'Finding') {
            $this->loadModel($foreignModel);
        } elseif ($foreignModel != 'Tour') {
            $foreignId = 0;
        }
        if ($offset < 0 || $offset % 12 != 0) {
            $offset = 0;
        }
        $items = array();
        if (!empty($foreignId) && $foreignTour = $this->$foreignModel->find('first', array(
            'fields' => array('latitude', 'longitude'),
            'conditions' => array(
                $foreignModel . '.id' => $foreignId
            ),
                ))) {
            if (empty($foreignTour[$foreignModel]['latitude']) ||
                    empty($foreignTour[$foreignModel]['longitude'])) {
                /*
                 * 如果取得的資料是空白的，試著查看看住址能否找到座標
                 */
            }
            $key = "/tours/near/{$foreignModel}/{$foreignId}/{$offset}";
            $items = Cache::read($key);
            if (false === $items) {
                $items = $this->Tour->find('near', array(
                    'fields' => array(
                        'id', 'title_zh_tw', 'title_en_us', 'title', 'latitude',
                        'longitude'
                    ),
                    'limit' => 12,
                    'distance' => 30,
                    'unit' => 'k',
                    'address' => array(
                        $foreignTour[$foreignModel]['latitude'],
                        $foreignTour[$foreignModel]['longitude'],
                    ),
                    'offset' => $offset,
                        ));
                foreach ($items AS $key => $val) {
                    $items[$key]['Tour']['rank'] = $this->Tour->Comment->field('rank', array(
                        'model' => 'Tour',
                        'foreign_key' => $val['Tour']['id'],
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
            'Tour.is_active' => 1);
        $contain = array();
        if ($areaId > 0 && $area = $this->Tour->Area->find('first', array(
            'fields' => array('lft', 'rght'),
            'conditions' => array(
                'Area.id' => $areaId,
            ),
                ))) {
            if ($area['Area']['rght'] - $area['Area']['lft'] == 1) {
                $scope['Tour.area_id'] = $areaId;
            } else {
                $contain['Area'] = array(
                    'fields' => array('id'),
                );
                $scope['Area.lft >='] = $area['Area']['lft'];
                $scope['Area.rght <='] = $area['Area']['rght'];
            }
        }
        $key = "/tours/area/{$areaId}/{$offset}";
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Tour->find('all', array(
                'contain' => $contain,
                'conditions' => $scope,
                'offset' => $offset,
                'limit' => 15,
                'order' => array(
                    'Tour.modified' => 'desc'
                ),
                    ));
            foreach($items AS $key => $item) {
                $items[$key]['Area'] = $this->Tour->Area->getPath($item['Tour']['area_id'], array('id', 'name'));
            }
            Cache::write($key, $items);
        }

        $this->set('url', array($areaId));
        $this->set('items', $items);
        $this->set('offset', $offset);
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
                    'Tour.title_zh_tw LIKE' => "%{$keyword}%",
                    'Tour.title_en_us LIKE' => "%{$keyword}%",
                    'Tour.title LIKE' => "%{$keyword}%",
                    'Tour.address_zh_tw LIKE' => "%{$keyword}%",
                    'Tour.address_en_us LIKE' => "%{$keyword}%",
                    'Tour.address LIKE' => "%{$keyword}%",
                    'Tour.website LIKE' => "%{$keyword}%",
                ),
            );
        }
        $this->set('url', array($keyword));
        $items = $this->Tour->find('all', array(
            'conditions' => $conditions,
            'offset' => $offset,
            'limit' => 12,
            'order' => array(
                'Tour.created' => 'desc'
            ),
                ));
        $this->set('items', $items);
        $this->set('offset', $offset);
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
                'Tour.title_zh_tw LIKE' => '%' . $keyword . '%',
                'Tour.title_en_us LIKE' => '%' . $keyword . '%',
                'Tour.title LIKE' => '%' . $keyword . '%',
                'Tour.telephone LIKE' => '%' . $keyword . '%',
                'Tour.website LIKE' => '%' . $keyword . '%',
                'Tour.email LIKE' => '%' . $keyword . '%',
            );
        }
        $this->Paginator->settings['Tour'] = array(
            'limit' => 20,
            'order' => array('modified' => 'desc'),
        );
        $this->set('items', $this->Paginator->paginate($this->Tour, $scope));
        $this->set('url', array($keyword));
        $this->set('keyword', $keyword);
    }

    function admin_view($id = null) {
        if (!$id || !$this->request->data = $this->Tour->read(null, $id)) {
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
            if ($this->Tour->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Tour');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Tour.data', $this->request->data);
                $this->Session->write('form.Tour.validationErrors', $this->Tour->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Tour->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Tour.data')) {
            $this->Tour->validationErrors = $this->Session->read('form.Tour.validationErrors');
            $this->Session->delete('form.Tour');
        }
        if ($id > 0) {
            $this->request->data = $this->Tour->find('first', array(
                'conditions' => array('Tour.id' => $id),
                'contain' => array(),
                    ));
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Tour'][$key])) {
                        $this->request->data['Tour'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
        if (!empty($this->request->data['Tour']['area_id'])) {
            /*
             * 取得選擇的區域
             */
            $this->set('areaPath', $this->Tour->Area->getPath($this->request->data['Tour']['area_id'], array('name')));
        }
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Tour->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect($this->referer());
    }

    function admin_add($id = 0) {
        $id = intval($id);
        if ($id > 0 && empty($this->request->data)) {
            $this->request->data = $this->Tour->read(null, $id);
        } elseif (!empty($this->request->data)) {
            if (isset($this->request->data['Tour']['id'])) {
                unset($this->request->data['Tour']['id']);
            }
            $this->Tour->create();
            if ($this->Tour->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Tour');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Tour.data', $this->request->data);
                $this->Session->write('form.Tour.validationErrors', $this->Tour->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Tour->read(null, $id);
    }

}