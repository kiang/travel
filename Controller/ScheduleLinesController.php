<?php

class ScheduleLinesController extends AppController {

    var $name = 'ScheduleLines';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('get_latlng', 'add', 'edit', 'delete');
    }

    function add($scheduleDayId = 0, $afterLineId = 0) {
        Configure::write('debug', 0);
        $scheduleDayId = intval($scheduleDayId);
        $toStay = false;
        if ($afterLineId === 'stay') {
            $toStay = true;
        }
        $afterLineId = intval($afterLineId);
        if ($scheduleDayId > 0) {
            $scheduleDay = $this->ScheduleLine->ScheduleDay->find('first', array(
                'conditions' => array(
                    'ScheduleDay.id' => $scheduleDayId,
                    'Schedule.member_id' => $this->loginMember['id'],
                ),
                'fields' => array('id'),
                'contain' => array(
                    'Schedule' => array(
                        'fields' => array('id')
                    ),
                ),
                    ));
            if (!empty($scheduleDay['ScheduleDay']['id'])) {
                $scheduleDayId = $scheduleDay['ScheduleDay']['id'];
                if ($this->loginMember['id'] == 0) {
                    $check = $this->Session->read('Guest.Schedules.' . $scheduleDay['Schedule']['id']);
                    if (empty($check)) {
                        $scheduleDayId = 0;
                    }
                }
            }
        }
        $this->set('scheduleDayId', $scheduleDayId);
        $this->set('afterLineId', $afterLineId);
        if (empty($scheduleDayId)) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
        } elseif (!empty($this->request->data)) {
            if (!empty($this->request->data['ScheduleLine']['schedule_day_id'])) {
                $this->Session->write('block', 1);
                exit();
            }
            if ($toStay) {
                $this->request->data['ScheduleDay']['point_id'] = intval($this->request->data['ScheduleDay']['point_id']);
                if ($this->ScheduleLine->ScheduleDay->save(array('ScheduleDay' => array(
                                'id' => $scheduleDayId,
                                'point_id' => $this->request->data['ScheduleDay']['point_id'],
                                'point_name' => $this->request->data['ScheduleDay']['point_name'],
                                'latitude' => $this->request->data['ScheduleDay']['latitude'],
                                'longitude' => $this->request->data['ScheduleDay']['longitude'],
                                )))) {
                    $this->set('scheduleLineMessage', 'ok');
                }
            } elseif (!empty($this->request->data['ScheduleLine']['id'])) {
                $targetLineId = $this->ScheduleLine->field('id', array(
                    'id' => intval($this->request->data['ScheduleLine']['id']),
                    'schedule_day_id' => $scheduleDayId,
                        ));
                $data = array(
                    'id' => $targetLineId,
                    'latitude' => $this->request->data['ScheduleLine']['latitude'],
                    'longitude' => $this->request->data['ScheduleLine']['longitude'],
                );
                if (!empty($this->request->data['ScheduleLine']['point_name'])) {
                    $data['point_name'] = $this->request->data['ScheduleLine']['point_name'];
                }
                if (!empty($targetLineId) && $this->ScheduleLine->save(array('ScheduleLine' => $data))) {
                    $this->set('scheduleLineMessage', 'ok');
                }
            } else {
                $this->ScheduleLine->create();
                if ($afterLineId > 0 && $afterLineSort = $this->ScheduleLine->field('sort', array(
                    'ScheduleLine.id' => $afterLineId,
                        ))) {
                    $this->request->data['ScheduleLine']['sort'] = $afterLineSort + 1;
                    $this->ScheduleLine->updateAll(array(
                        'ScheduleLine.sort' => 'ScheduleLine.sort + 1',
                            ), array(
                        'ScheduleLine.schedule_day_id' => $scheduleDayId,
                        'ScheduleLine.sort >' => $afterLineSort,
                    ));
                } else {
                    $this->request->data['ScheduleLine']['sort'] = $this->ScheduleLine->field('sort', array(
                                'ScheduleLine.schedule_day_id' => $scheduleDayId,
                                    ), array('ScheduleLine.sort DESC', 'ScheduleLine.id DESC')) + 1;
                }

                $this->request->data['ScheduleLine']['schedule_day_id'] = $scheduleDayId;
                if ($this->ScheduleLine->save($this->request->data)) {
                    $this->set('scheduleLineMessage', $this->ScheduleLine->getInsertID());
                }
            }
        }
    }

    function edit($scheduleDayId = 0, $id = 0) {
        $scheduleDayId = intval($scheduleDayId);
        if (empty($this->request->data['ScheduleLine']['point_name'])) {
            $scheduleDayId = 0;
        }
        if ($scheduleDayId > 0) {
            if ($this->loginMember['id'] == 0) {
                $check = $this->Session->read('Guest.Schedules.' . $scheduleDay['Schedule']['id']);
                if (empty($check)) {
                    $scheduleDay = array(
                        'ScheduleDay' => array('id' => 0)
                    );
                }
            } else {
                $scheduleDay = $this->ScheduleLine->ScheduleDay->find('first', array(
                    'fields' => array('id'),
                    'conditions' => array(
                        'ScheduleDay.id' => $scheduleDayId,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                    'contain' => array('Schedule' => array(
                            'fields' => array('id')
                    )),
                        ));
            }
            if (!empty($scheduleDay['ScheduleDay']['id'])) {
                $scheduleDayId = $scheduleDay['ScheduleDay']['id'];
            } else {
                $scheduleDayId = 0;
            }
        }
        if ($scheduleDayId > 0 && $id === 'day' . $scheduleDayId) {
            if($this->ScheduleLine->ScheduleDay->save(array('ScheduleDay' => array(
                'id' => $scheduleDayId,
                'point_name' => $this->request->data['ScheduleLine']['point_name'],
            )))) {
                echo 'ok';
            } else {
                echo 'error';
            }
        } else {
            $id = intval($id);
            if ($scheduleDayId > 0 && $id > 0) {
                $id = $this->ScheduleLine->field('id', array(
                    'ScheduleLine.id' => $id,
                    'ScheduleLine.schedule_day_id' => $scheduleDayId,
                        ));
                if ($id > 0 && $this->ScheduleLine->save(array('ScheduleLine' => array(
                                'id' => $id,
                                'point_name' => $this->request->data['ScheduleLine']['point_name'],
                                )))) {
                    echo 'ok';
                } else {
                    echo 'error';
                }
            }
        }
        exit();
    }

    function form($id = 0) {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.ScheduleLine.data')) {
            $this->ScheduleLine->validationErrors = $this->Session->read('form.ScheduleLine.validationErrors');
            $this->Session->delete('form.ScheduleLine');
        }
        if ($id > 0) {
            $this->request->data = $this->ScheduleLine->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['ScheduleLine'][$key])) {
                        $this->request->data['ScheduleLine'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }
        $this->request->data = $this->ScheduleLine->parseMinutesStay($this->request->data);
        $this->loadModel('Activity');
        $this->set('activities', $this->Activity->find('list'));
        $this->loadModel('Transport');
        $this->set('transports', $this->Transport->find('list'));
        $this->set('id', $id);
    }

    function delete($id = 0) {
        $id = intval($id);
        if ($id > 0) {
            $scheduleDayId = $this->ScheduleLine->field('schedule_day_id', array(
                'id' => $id,
                    ));
            if ($scheduleDayId > 0) {
                $scheduleDay = $this->ScheduleLine->ScheduleDay->find('first', array(
                    'fields' => array('id'),
                    'conditions' => array(
                        'ScheduleDay.id' => $scheduleDayId,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                    'contain' => array(
                        'Schedule' => array(
                            'fields' => array('id')
                        ),
                    )
                        ));
            }
        }
        if (empty($scheduleDay['ScheduleDay']['id'])) {
            echo 'error';
        } else {
            $this->ScheduleLine->scheduleDayId = $scheduleDay['ScheduleDay']['id'];
            $this->ScheduleLine->delete($id);
            echo 'ok';
        }
        exit();
    }

    /**
     * 將地點或旅館推到行程中
     * 
     * @param string $foreignModel
     * @param integer $foreignId
     */
    function push($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        if ($foreignModel == 'Point' && $foreignId > 0) {
            $title = $this->getForeignTitle($foreignModel, $foreignId);
            if (!empty($title)) {
                if (!empty($this->request->data['ScheduleLine']['schedule_day_id']) &&
                        ($this->ScheduleLine->ScheduleDay->find('count', array(
                            'conditions' => array(
                                'ScheduleDay.id' => $this->request->data['ScheduleLine']['schedule_day_id'],
                                'Schedule.member_id' => $this->loginMember['id'],
                            ),
                            'contain' => array('Schedule')
                        )) == 1)) {
                    if (!empty($this->request->data['ScheduleLine']['is_living'])) {
                        $this->ScheduleLine->ScheduleDay->save(array('ScheduleDay' => array(
                                'id' => $this->request->data['ScheduleLine']['schedule_day_id'],
                                'point_id' => $foreignId,
                                'point_name' => $title,
                                'latitude' => $data[$foreignModel]['latitude'],
                                'longitude' => $data[$foreignModel]['longitude'],
                                )));
                    } else {
                        $data = $this->$foreignModel->find('first', array(
                            'fields' => array('latitude', 'longitude'),
                            'conditions' => array($foreignModel . '.id' => $foreignId),
                                ));
                        $sort = $this->ScheduleLine->field('sort', array(
                            'ScheduleLine.schedule_day_id' => $this->request->data['ScheduleLine']['schedule_day_id'],
                                ), array('ScheduleLine.sort DESC', 'ScheduleLine.id DESC'));
                        if (empty($sort)) {
                            $sort = 0;
                        } else {
                            ++$sort;
                        }
                        if (empty($data[$foreignModel]['latitude'])) {
                            $data[$foreignModel]['latitude'] = '0';
                        }
                        if (empty($data[$foreignModel]['longitude'])) {
                            $data[$foreignModel]['longitude'] = '0';
                        }
                        $this->ScheduleLine->create();
                        $this->ScheduleLine->save(array('ScheduleLine' => array(
                                'model' => $foreignModel,
                                'foreign_key' => $foreignId,
                                'schedule_day_id' => $this->request->data['ScheduleLine']['schedule_day_id'],
                                'point_name' => $title,
                                'latitude' => $data[$foreignModel]['latitude'],
                                'longitude' => $data[$foreignModel]['longitude'],
                                'sort' => $sort,
                                )));
                    }
                }
                $this->set('foreignTitle', $title);
                $this->set('foreignModel', $foreignModel);
                $this->set('foreignId', $foreignId);
            }
        }
    }

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();
        $foreignKeys = array(
            'ScheduleDay' => 'schedule_day_id',
            'Activity' => 'activity_id',
            'Transport' => 'transport_id',
        );
        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['ScheduleLine.' . $foreignKeys[$foreignModel]] = $foreignId;
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->Paginator->settings['ScheduleLine']['limit'] = 20;
        $items = $this->Paginator->paginate($this->ScheduleLine, $scope);
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'ScheduleDay' => 'schedule_day_id',
            'Activity' => 'activity_id',
            'Transport' => 'transport_id',
        );
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            if (!empty($this->request->data)) {
                $this->request->data['ScheduleLine'][$foreignKeys[$foreignModel]] = $foreignId;
            }
        } else {
            $foreignModel = '';
        }
        if (!empty($this->request->data)) {
            $this->ScheduleLine->create();
            if ($this->ScheduleLine->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.ScheduleLine');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.ScheduleLine.data', $this->request->data);
                $this->Session->write('form.ScheduleLine.validationErrors', $this->ScheduleLine->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->request->data)) {
            if ($this->ScheduleLine->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.ScheduleLine');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.ScheduleLine.data', $this->request->data);
                $this->Session->write('form.ScheduleLine.validationErrors', $this->ScheduleLine->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->ScheduleLine->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.ScheduleLine.data')) {
            $this->ScheduleLine->validationErrors = $this->Session->read('form.ScheduleLine.validationErrors');
            $this->Session->delete('form.ScheduleLine');
        }
        if ($id > 0) {
            $this->request->data = $this->ScheduleLine->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['ScheduleLine'][$key])) {
                        $this->request->data['ScheduleLine'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }
        $this->request->data = $this->ScheduleLine->parseMinutesStay($this->request->data);

        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
        $belongsToModels = array(
            'listScheduleDay' => array(
                'label' => '單日行程',
                'modelName' => 'ScheduleDay',
                'foreignKey' => 'schedule_day_id',
            ),
            'listActivity' => array(
                'label' => '活動',
                'modelName' => 'Activity',
                'foreignKey' => 'activity_id',
            ),
            'listTransport' => array(
                'label' => '交通方式',
                'modelName' => 'Transport',
                'foreignKey' => 'transport_id',
            ),
        );

        foreach ($belongsToModels AS $key => $model) {
            if ($foreignModel == $model['modelName']) {
                unset($belongsToModels[$key]);
                continue;
            }
            $this->set($key, $this->ScheduleLine->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->ScheduleLine->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}