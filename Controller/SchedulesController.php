<?php

App::uses('Sanitize', 'Utility');

/**
 * 行程管理
 *
 * 行程的天數 schedule_days 也許需要控制在 30 天以內，更多的資料也許分列行程比較洽當
 *
 * @author kiang
 *
 */
class SchedulesController extends AppController {

    var $name = 'Schedules';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index', 'view', 'member', 'add',
            'block', 'output', 'area', 'block_new', 'block_hot',
            'block_good', 'page_new', 'page_hot', 'cart_list', 'point', 'finding',
            'note', 'export', 'auto_list', 'blog_export', 'map_mode', 'edit',
            'import', 'import_list', 'delete', 'sort'
        );
    }

    function index($memberId = 0, $areaId = 0, $keyword = null) {
        $title = '行程';
        $scope = $this->Paginator->settings['Schedule']['joins'] = array();
        $scope = array('Schedule.is_draft' => 0);
        if ($memberId === 'noarea') {
            $this->Paginator->settings['Schedule']['joins'][] = array(
                'table' => 'areas_models',
                'alias' => 'AreasModel',
                'type' => 'LEFT',
                'conditions' => array(
                    'AreasModel.model' => 'Schedule',
                    'AreasModel.foreign_key = Schedule.id',
                ),
            );
            $scope[] = 'AreasModel.id IS NULL';
        } else {
            $memberId = intval($memberId);
            if ($memberId > 0) {
                $scope['Schedule.member_id'] = $memberId;
            }
        }
        $areaId = intval($areaId);
        $url = array($memberId, $areaId);
        if (isset($this->request->query['keyword'])) {
            $keyword = $this->request->query['keyword'];
        }
        if (!empty($keyword)) {
            $keyword = Sanitize::clean($keyword);
        }
        /*
         * 取得 left, right 數值
         */
        if ($areaId <= 0) {
            $this->set('areas', $this->Schedule->Area->find('all', array(
                        'fiels' => array('id', 'name', 'countSchedule'),
                        'conditions' => array(
                            'Area.parent_id' => 0,
                            'Area.countSchedule >' => 0,
                        ),
                        'order' => array('name' => 'asc'),
                    )));
        } elseif ($area = $this->Schedule->Area->find('first', array(
            'fields' => array('lft', 'rght'),
            'conditions' => array(
                'Area.id' => $areaId,
                'Area.countSchedule >' => 0,
            ),
                ))) {
            $this->Paginator->settings['Schedule']['joins'][] = array(
                'table' => 'areas_models',
                'alias' => 'AreasModel',
                'type' => 'INNER',
                'conditions' => array(
                    'AreasModel.model' => 'Schedule',
                    'AreasModel.foreign_key = Schedule.id',
                ),
            );
            $this->Paginator->settings['Schedule']['group'] = array('Schedule.id');
            if ($area['Area']['rght'] - $area['Area']['lft'] == 1) {
                $scope['AreasModel.area_id'] = $areaId;
            } else {
                $this->Paginator->settings['Schedule']['joins'][] = array(
                    'table' => 'areas',
                    'alias' => 'JoinArea',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AreasModel.area_id = JoinArea.id',
                        'JoinArea.lft >=' => $area['Area']['lft'],
                        'JoinArea.rght <=' => $area['Area']['rght'],
                    ),
                );
                $this->set('areas', $this->Schedule->Area->find('all', array(
                            'fiels' => array('id', 'name', 'countSchedule'),
                            'conditions' => array(
                                'Area.parent_id' => $areaId,
                                'Area.countSchedule >' => 0,
                            ),
                            'order' => array('name' => 'asc'),
                        )));
            }
            if ($parents = $this->Schedule->Area->getPath($areaId, array('id', 'name'))) {
                $parents = array_merge(array(0 => array('Area' => array(
                            'id' => 0,
                            'name' => '全部',
                    ))), $parents);
                $this->set('parents', $parents);
                $title = implode(' > ', Set::extract('{n}.Area.name', $parents)) . $title;
            }
        }

        if (!empty($keyword)) {
            $url[] = rawurlencode($keyword);
            $this->Paginator->settings['Schedule']['joins'][] = array(
                'table' => 'schedule_days',
                'alias' => 'ScheduleDay',
                'type' => 'LEFT',
                'conditions' => array('ScheduleDay.schedule_id = Schedule.id'),
            );
            $this->Paginator->settings['Schedule']['group'] = array('Schedule.id');
            $scope['OR'] = array(
                'Schedule.title LIKE' => '%' . $keyword . '%',
                'ScheduleDay.summary LIKE' => '%' . $keyword . '%',
            );
        }

        $this->Paginator->settings['Schedule']['limit'] = 20;
        $this->Paginator->settings['Schedule']['order'] = array('modified' => 'desc');
        $this->Paginator->settings['Schedule']['contain'] = array(
            'Member' => array('fields' => array('dirname', 'basename'),),
            'ScheduleDay' => array(
                'fields' => array('summary'),
                'order' => array('sort' => 'asc', 'id' => 'asc'),
                'limit' => 1,
            ),
            'Area' => array(
                'fields' => array('id', 'name'),
            ),
        );
        $this->set('items', $this->paginate($this->Schedule, $scope));
        $this->set('title_for_layout', $title);
        $this->set('url', $url);
        $this->set('memberId', $memberId);
        $this->set('keyword', $keyword);
    }

    function member($memberId = 0, $offset = 0) {
        $memberId = intval($memberId);
        $offset = intval($offset);
        if ($offset < 0) {
            $offset = 0;
        }
        if ($memberId > 0) {
            $conditions = array(
                'Schedule.member_id' => $memberId,
            );
            if ($this->loginMember['id'] != $memberId) {
                $conditions['Schedule.is_draft'] = '0';
            }
            $this->set('items', $this->Schedule->find('all', array(
                        'conditions' => $conditions,
                        'limit' => 10,
                        'fields' => array(
                            'Schedule.id', 'Schedule.title', 'Schedule.modified',
                            'Schedule.count_views', 'Schedule.is_draft'
                        ),
                        'offset' => $offset,
                        'order' => array('modified' => 'desc'),
                    )));
            $this->set('title_for_layout', '行程');
            $this->set('url', array($memberId));
            $this->set('memberId', $memberId);
            $this->set('offset', $offset);
        } else {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        }
    }

    function block_new($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 20 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/schedules/block_new/' . $offset;
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Schedule->find('all', array(
                'conditions' => array('Schedule.is_draft' => 0),
                'fields' => array('Schedule.id', 'Schedule.title',
                    'Schedule.member_id', 'Schedule.member_name'),
                'limit' => 20,
                'offset' => $offset,
                'order' => array('Schedule.created' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('id', 'dirname', 'basename', 'gender')
                    ),
                ),
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function block_hot($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 20 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/schedules/block_new/' . $offset;
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Schedule->find('all', array(
                'conditions' => array('Schedule.is_draft' => 0),
                'fields' => array('Schedule.id', 'Schedule.title',
                    'Schedule.member_id', 'Schedule.member_name'),
                'limit' => 20,
                'offset' => $offset,
                'order' => array('Schedule.count_views' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('id', 'dirname', 'basename', 'gender')
                    ),
                ),
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function block_good($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 20 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/schedules/block_good/' . $offset;
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Schedule->find('all', array(
                'conditions' => array('Schedule.is_draft' => 0),
                'fields' => array('Schedule.id', 'Schedule.title',
                    'Schedule.member_id', 'Schedule.member_name'),
                'limit' => 20,
                'offset' => $offset,
                'order' => array('Schedule.count_ranks' => 'desc', 'Schedule.created' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('id', 'dirname', 'basename', 'gender')
                    ),
                ),
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function block($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        if (in_array($foreignModel, array('Point')) && $foreignId > 0) {
            $this->loadModel($foreignModel);
            if (!$this->$foreignModel->hasAny(array(
                        $foreignModel . '.id' => $foreignId,
                        $foreignModel . '.is_active' => 1,
                    ))) {
                $foreignModel = '';
            } else {
                $this->Paginator->settings['Schedule'] = array(
                    'fields' => array(
                        'Schedule.id', 'Schedule.member_id', 'Schedule.title',
                        'Schedule.member_name', 'Schedule.created'
                    ),
                    'limit' => 20,
                    'order' => array('created' => 'desc'),
                );
                switch ($foreignModel) {
                    case 'Point':
                        $this->Paginator->settings['Schedule']['joins'] = array(
                            array(
                                'table' => 'schedule_days',
                                'alias' => 'ScheduleDay',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'ScheduleDay.schedule_id = Schedule.id'
                                ),
                            ),
                            array(
                                'table' => 'schedule_lines',
                                'alias' => 'ScheduleLine',
                                'type' => 'INNER',
                                'conditions' => array(
                                    'ScheduleLine.schedule_day_id = ScheduleDay.id',
                                    'ScheduleLine.model' => 'Point',
                                    'ScheduleLine.foreign_key' => $foreignId,
                                ),
                            ),
                        );
                        break;
                }
                $this->Paginator->settings['Schedule']['group'] = array('Schedule.id');
            }
        } else {
            $foreignModel = '';
        }
        if (empty($foreignModel) || $foreignId <= 0) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        } else {
            $this->set('items', $this->paginate($this->Schedule));
            $this->set('url', array($foreignModel, $foreignId));
        }
    }

    function view($id = 0, $scheduleDayId = 0) {
        $id = intval($id);
        $scheduleDayId = intval($scheduleDayId);
        $contain = array(
            'Member' => array(
                'fields' => array('dirname', 'basename', 'gender'),
            ),
            'ScheduleDay' => array(
                'fields' => array('id', 'title', 'point_id', 'point_name',
                    'count_lines', 'sort', 'summary'),
                'order' => array('sort ASC')
                ));
        if ($id <= 0 || !$this->request->data = $this->Schedule->find('first', array(
            'conditions' => array('Schedule.id' => $id),
            'contain' => $contain,
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        } else {
            $ableEdit = false;
            if (empty($this->loginMember['id'])) {
                $check = $this->Session->read('Guest.Schedules.' . $id);
                if (!empty($check)) {
                    $ableEdit = true;
                } elseif (!empty($this->request->data['Schedule']['is_draft'])) {
                    $this->redirect(array('action' => 'index'));
                }
            }

            if ($this->request->data['Schedule']['count_days'] == 1) {
                $scheduleDayId = $this->request->data['ScheduleDay'][0]['id'];
            }
            $this->set('scheduleDayId', $scheduleDayId);
            $this->set('dayPoints', $this->Schedule->getDayPoints($id));
            $this->set('title_for_layout', $this->request->data['Schedule']['title']);
            if ($this->request->data['Schedule']['member_id'] != $this->loginMember['id']) {
                $this->Schedule->updateAll(
                        array('Schedule.count_views' => 'Schedule.count_views + 1'), array('Schedule.id' => $id)
                );
            } elseif (!empty($this->loginMember['id'])) {
                $ableEdit = true;
            }
            $this->set('ableEdit', $ableEdit);
        }
    }

    function add($scheduleTaskId = 0) {
        $scheduleTaskId = intval($scheduleTaskId);
        if ($scheduleTaskId > 0) {
            $scheduleTask = $this->Schedule->ScheduleTask->find('first', array(
                'fields' => array('ScheduleTask.url', 'ScheduleTask.title'),
                'conditions' => array(
                    'ScheduleTask.id' => $scheduleTaskId,
                    'ScheduleTask.schedule_id' => 0,
                )
                    ));
        }
        $this->loadModel('Activity');
        $this->loadModel('Transport');
        $activities = Set::combine($this->Activity->find('all', array(
                            'fields' => array('id', 'class', 'name')
                        )), '{n}.Activity.id', '{n}.Activity');
        $transports = Set::combine($this->Transport->find('all', array(
                            'fields' => array('id', 'class', 'name')
                        )), '{n}.Transport.id', '{n}.Transport');
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Schedule']['member_id'])) {
                $this->Session->write('block', 1);
                exit();
            }
            if (empty($this->loginMember['id'])) {
                $this->request->data['Schedule']['is_draft'] = 1;
            }
            $this->request->data['Schedule']['member_id'] = $this->loginMember['id'];
            if (!empty($this->loginMember['nickname'])) {
                $this->request->data['Schedule']['member_name'] = $this->loginMember['nickname'];
            } else {
                $this->request->data['Schedule']['member_name'] = $this->loginMember['username'];
            }
            $this->Schedule->create();
            $countDays = $this->request->data['Schedule']['count_days'];
            $this->request->data['Schedule']['count_days'] = 0;
            if ($this->Schedule->save($this->request->data)) {
                $scheduleId = $this->Schedule->getInsertID();
                if (empty($this->loginMember['id'])) {
                    $this->Session->write('Guest.Schedules.' . $scheduleId, '1');
                }
                if (!empty($scheduleTask['ScheduleTask']['url'])) {
                    $this->loadModel('Link');
                    $this->Link->create();
                    $this->Link->save(array('Link' => array(
                            'member_id' => $this->loginMember['id'],
                            'member_name' => $this->request->data['Schedule']['member_name'],
                            'model' => 'Schedule',
                            'foreign_key' => $scheduleId,
                            'is_active' => 1,
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'url' => $scheduleTask['ScheduleTask']['url'],
                            'title' => $scheduleTask['ScheduleTask']['title'],
                            )));
                    $this->Schedule->ScheduleTask->id = $scheduleTaskId;
                    $this->Schedule->ScheduleTask->save(array('ScheduleTask' => array(
                            'schedule_id' => $scheduleId,
                            'dealt' => date('Y-m-d H:i:s'),
                            'dealer' => $this->loginMember['id'],
                            )));
                }
                $this->request->data['ScheduleDay']['schedule_id'] = $scheduleId;
                $this->request->data['ScheduleDay']['sort'] = 1;
                $this->Schedule->ScheduleDay->create();
                $this->Schedule->ScheduleDay->save($this->request->data);
                $scheduleDayId = $this->Schedule->ScheduleDay->getInsertID();
                if (!empty($scheduleDayId)) {
                    foreach ($this->request->data['ScheduleLine']['sort'] AS $key => $val) {
                        $minutesStay = 0;
                        if (!empty($this->request->data['ScheduleLine']['time_arrive'][$key]) && !empty($this->request->data['ScheduleLine']['time_leave'][$key])) {
                            $leaveTime = strtotime($this->request->data['ScheduleLine']['time_leave'][$key]);
                            $arriveTime = strtotime($this->request->data['ScheduleLine']['time_arrive'][$key]);
                            if ($leaveTime < $arriveTime) {
                                $leaveTime += 86400;
                            }
                            $minutesStay = round(($leaveTime - $arriveTime) / 60);
                        }
                        $activityName = $transportName = '';
                        if (isset($activities[$this->request->data['ScheduleLine']['activity_id'][$key]])) {
                            $activityName = $activities[$this->request->data['ScheduleLine']['activity_id'][$key]]['name'];
                        }
                        if (isset($transports[$this->request->data['ScheduleLine']['transport_id'][$key]])) {
                            $transportName = $transports[$this->request->data['ScheduleLine']['transport_id'][$key]]['name'];
                        }
                        $this->Schedule->ScheduleDay->ScheduleLine->create();
                        $this->Schedule->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
                                'model' => 'Point',
                                'foreign_key' => empty($this->request->data['ScheduleLine']['point_id'][$key]) ? '0' : $this->request->data['ScheduleLine']['point_id'][$key],
                                'schedule_day_id' => $scheduleDayId,
                                'sort' => $this->request->data['ScheduleLine']['sort'][$key],
                                'point_name' => $this->request->data['ScheduleLine']['point_name'][$key],
                                'longitude' => $this->request->data['ScheduleLine']['longitude'][$key],
                                'latitude' => $this->request->data['ScheduleLine']['latitude'][$key],
                                'activity_id' => $this->request->data['ScheduleLine']['activity_id'][$key],
                                'transport_id' => $this->request->data['ScheduleLine']['transport_id'][$key],
                                'time_arrive' => $this->request->data['ScheduleLine']['time_arrive'][$key],
                                'note' => $this->request->data['ScheduleLine']['note'][$key],
                                'transport_name' => $transportName,
                                'activity_name' => $activityName,
                                'minutes_stay' => $minutesStay,
                                )));
                    }
                } else {
                    
                }
                if ($countDays > 20) {
                    $countDays = 20;
                }
                for ($i = 2; $i <= $countDays; $i++) {
                    $this->Schedule->ScheduleDay->create();
                    $this->Schedule->ScheduleDay->save(array('ScheduleDay' => array(
                            'schedule_id' => $scheduleId,
                            'sort' => $i,
                            'title' => '',
                            )));
                }
                $this->Session->setFlash('資料已經儲存');
                $this->redirect(array('action' => 'map_mode', $scheduleId));
            } else {
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        if (!empty($scheduleTask['ScheduleTask']['title'])) {
            $this->Session->write('form.Schedule.data', array('Schedule' => array(
                    'title' => $scheduleTask['ScheduleTask']['title']
                    )));
        }
        $this->set('scheduleTaskId', $scheduleTaskId);
        $this->set('activities', $activities);
        $this->set('transports', $transports);
        $this->set('title_for_layout', '建立行程');
    }

    function edit($id = 0) {
        $id = intval($id);
        if (empty($this->loginMember['id'])) {
            $check = $this->Session->read('Guest.Schedules.' . $id);
            if (empty($check)) {
                $id = 0;
            }
        }
        if ($id > 0) {
            $schedule = $this->Schedule->find('first', array(
                'conditions' => array(
                    'id' => $id,
                    'member_id' => $this->loginMember['id'],
                ),
                    ));
        }
        if (empty($schedule)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect($this->referer());
        } else {
            if (!empty($this->request->data)) {
                if (!empty($this->request->data['Schedule']['member_id'])) {
                    $this->Session->write('block', 1);
                    exit();
                }
                if (empty($this->loginMember['id'])) {
                    $this->request->data['Schedule']['is_draft'] = 1;
                }
                $this->request->data['Schedule']['id'] = $id;
                if ($this->Schedule->save($this->request->data)) {
                    $this->Session->setFlash('資料已經儲存');
                    $this->redirect(array('action' => 'view', $id));
                } else {
                    $this->Session->setFlash('資料儲存失敗，請重試');
                }
            } else {
                $this->request->data = $schedule;
            }
            $this->set('id', $id);
        }
    }

    function delete($id = 0) {
        $id = intval($id);
        if (empty($this->loginMember['id'])) {
            $check = $this->Session->read('Guest.Schedules.' . $id);
            if (empty($check)) {
                $id = 0;
            }
        }
        if ($id > 0 && $this->Schedule->field('member_id', array(
                    'Schedule.id' => $id
                )) != $this->loginMember['id']) {
            $id = 0;
        }
        if ($id > 0 && $this->Schedule->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        } else {
            $this->Session->setFlash('請依據網頁指示操作');
        }
        $this->redirect(array('action' => 'index'));
    }

    function sort($id = 0) {
        $id = intval($id);
        if (empty($this->loginMember['id'])) {
            $check = $this->Session->read('Guest.Schedules.' . $id);
            if (empty($check)) {
                $id = 0;
            }
        }
        /*
         * 確認目前登入使用者擁有這個資料，以及傳送過來的資料都屬於指定的 ScheduleDay
         */
        if ($id > 0 && $this->Schedule->find('count', array(
                    'conditions' => array(
                        'Schedule.id' => $id,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                )) == 1 && $this->Schedule->ScheduleDay->find('count', array(
                    'conditions' => array(
                        'ScheduleDay.id' => array_keys($this->request['data']),
                        'ScheduleDay.schedule_id' => $id,
                    ),
                )) == count($this->request['data'])) {
            foreach ($this->request['data'] AS $scheduleDayId => $sort) {
                $this->Schedule->ScheduleDay->save(array('ScheduleDay' => array(
                        'id' => $scheduleDayId,
                        'sort' => $sort,
                        )));
            }
        }
    }

    function output($scheduleId = 0) {
        $this->layout = 'output';
        $scheduleId = intval($scheduleId);
        $conditions = array();
        if($this->loginMember['group_id'] != 1) {
            $conditions = array(
            'OR' => array(
                'Schedule.is_draft' => 0,
                array(
                    'Schedule.is_draft' => 1,
                    'Schedule.member_id' => $this->loginMember['id'],
                ),
            ),
                );
        }
        if ($scheduleId > 0 && $schedule = $this->Schedule->getFull($scheduleId, $conditions)) {
            $this->loadModel('Point');
            if ($schedule['Schedule']['point_id'] > 0) {
                $this->set('startPoint', $this->Point->find('first', array(
                            'fields' => array('address_zh_tw', 'address_en_us', 'address', 'telephone'),
                            'conditions' => array('Point.id' => $schedule['Schedule']['point_id']),
                        )));
            }
            $points = array();
            $countReference = 0;
            foreach ($schedule['ScheduleDay'] AS $keyDay => $scheduleDay) {
                foreach ($scheduleDay['ScheduleLine'] AS $keyLine => $scheduleLine) {
                    if (!empty($scheduleLine['foreign_key'])) {
                        $currentReference = 0;
                        switch ($scheduleLine['model']) {
                            case 'Point':
                            default:
                                if (!$currentReference = array_search($scheduleLine['foreign_key'], $points)) {
                                    ++$countReference;
                                    $currentReference = $countReference;
                                    $points[$currentReference] = $scheduleLine['foreign_key'];
                                }
                        }
                        if ($currentReference > 0) {
                            $schedule['ScheduleDay'][$keyDay]['ScheduleLine'][$keyLine]['point_name'] .= '___@' . $currentReference;
                        }
                    }
                }
                if (!empty($scheduleDay['point_id'])) {
                    $currentReference = 0;
                    if (!$currentReference = array_search($scheduleDay['point_id'], $points)) {
                        ++$countReference;
                        $currentReference = $countReference;
                        $points[$currentReference] = $scheduleDay['point_id'];
                    }
                    if ($currentReference > 0) {
                        $schedule['ScheduleDay'][$keyDay]['point_name'] .= '___@' . $currentReference;
                    }
                }
            }
            $references = array();
            if (!empty($points)) {
                $pointData = $this->Point->find('all', array(
                    'fields' => array('id', 'title_zh_tw', 'title_en_us', 'title', 'telephone',
                        'address_zh_tw', 'address_en_us', 'address'),
                    'conditions' => array('Point.id' => $points),
                        ));
                $pointReferences = array();
                foreach ($pointData AS $point) {
                    $stringStack = array();
                    if (!empty($point['Point']['title'])) {
                        $stringStack[] = '[原] ' . $point['Point']['title'];
                    }
                    if (!empty($point['Point']['title_zh_tw'])) {
                        $stringStack[] = '[中] ' . $point['Point']['title_zh_tw'];
                    }
                    if (!empty($point['Point']['title_en_us'])) {
                        $stringStack[] = '[英] ' . $point['Point']['title_en_us'];
                    }
                    $point['Point']['title_zh_tw'] = implode('<br />', $stringStack);
                    $stringStack = array();
                    if (!empty($point['Point']['address'])) {
                        $stringStack[] = '[原] ' . $point['Point']['address'];
                    }
                    if (!empty($point['Point']['address_zh_tw'])) {
                        $stringStack[] = '[中] ' . $point['Point']['address_zh_tw'];
                    }
                    if (!empty($point['Point']['address_en_us'])) {
                        $stringStack[] = '[英] ' . $point['Point']['address_en_us'];
                    }
                    $point['Point']['address_zh_tw'] = implode('<br />', $stringStack);
                    $pointReferences[$point['Point']['id']] = $point;
                }
                unset($pointData);
                foreach ($points AS $referenceNumber => $pointId) {
                    $references[$referenceNumber] = array(
                        'title' => $pointReferences[$pointId]['Point']['title_zh_tw'],
                        'address' => $pointReferences[$pointId]['Point']['address_zh_tw'],
                        'phone' => $pointReferences[$pointId]['Point']['telephone'],
                    );
                }
            }
            $notes = $this->Schedule->ScheduleNote->find('all', array(
                'conditions' => array(
                    'ScheduleNote.schedule_id' => $schedule['Schedule']['id'],
                ),
                'order' => array('ScheduleNote.sort ASC'),
            ));
            $scheduleNotes = array(
                'schedule' => array(),
                'day' => array(),
                'line' => array(),
            );
            foreach($notes AS $note) {
                if(!empty($note['ScheduleNote']['schedule_line_id'])) {
                    if(!isset($scheduleNotes['line'][$note['ScheduleNote']['schedule_line_id']])) {
                        $scheduleNotes['line'][$note['ScheduleNote']['schedule_line_id']] = array();
                    }
                    $scheduleNotes['line'][$note['ScheduleNote']['schedule_line_id']][] = $note['ScheduleNote'];
                } elseif(!empty($note['ScheduleNote']['schedule_day_id'])) {
                    if(!isset($scheduleNotes['day'][$note['ScheduleNote']['schedule_day_id']])) {
                        $scheduleNotes['day'][$note['ScheduleNote']['schedule_day_id']] = array();
                    }
                    $scheduleNotes['day'][$note['ScheduleNote']['schedule_day_id']][] = $note['ScheduleNote'];
                } else {
                    $scheduleNotes['schedule'][] = $note['ScheduleNote'];
                }
            }
            ksort($references);
            $this->set('schedule', $schedule);
            $this->set('scheduleNotes', $scheduleNotes);
            $this->set('references', $references);
            $this->set('title_for_layout', $schedule['Schedule']['title']);
        } else {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function copy($scheduleId = 0) {
        $scheduleId = intval($scheduleId);
        if ($scheduleId > 0) {
            $scheduleData = $this->Schedule->getFull($scheduleId, array(
                'OR' => array(
                    'Schedule.is_draft' => 0,
                    array(
                        'Schedule.is_draft' => 1,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                ),
                    ));
        }
        if (!empty($scheduleData)) {
            $schedule = $scheduleData['Schedule'];
            $schedule['count_days'] = count($scheduleData['ScheduleDay']);
            if ($schedule['count_days'] == 0) {
                $this->Session->setFlash('這個行程沒有資料可以複製');
                $this->redirect(array('action' => 'view', $scheduleId));
            } else {
                $today = date('Y-m-d H:i:s');
                $this->Schedule->ScheduleDay->disableSummary = true;
                $this->Schedule->ScheduleDay->ScheduleLine->disableSummary = true;
                $this->Schedule->create();
                $schedule['member_id'] = $this->loginMember['id'];
                if (!empty($this->loginMember['nickname'])) {
                    $schedule['member_name'] = $this->loginMember['nickname'];
                } else {
                    $schedule['member_name'] = $this->loginMember['username'];
                }
                $schedule['created'] = $schedule['modified'] = $today;
                $schedule['title'] .= '(複製版)';
                $schedule['is_draft'] = 1;
                $schedule['count_days'] = $schedule['count_views'] = $schedule['count_comments'] = $schedule['count_links'] = $schedule['count_ranks'] = 0;
                unset($schedule['id']);
                if ($this->Schedule->save(array('Schedule' => $schedule))) {
                    $newScheduleId = $this->Schedule->getInsertID();
                    foreach ($scheduleData['ScheduleDay'] AS $scheduleDay) {
                        $this->Schedule->ScheduleDay->create();
                        if ($this->Schedule->ScheduleDay->save(array('ScheduleDay' => array(
                                        'schedule_id' => $newScheduleId,
                                        'transport_id' => $scheduleDay['transport_id'],
                                        'transport_name' => $scheduleDay['transport_name'],
                                        'point_id' => $scheduleDay['point_id'],
                                        'point_name' => $scheduleDay['point_name'],
                                        'time_arrive' => $scheduleDay['time_arrive'],
                                        'sort' => $scheduleDay['sort'],
                                        'title' => $scheduleDay['title'],
                                        'count_lines' => count($scheduleDay['ScheduleLine']),
                                        'note' => $scheduleDay['note'],
                                        'summary' => $scheduleDay['summary'],
                                        )))) {
                            $newScheduleDayId = $this->Schedule->ScheduleDay->getInsertID();
                            foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                                unset($scheduleLine['id']);
                                $scheduleLine['schedule_day_id'] = $newScheduleDayId;
                                $this->Schedule->ScheduleDay->ScheduleLine->create();
                                $this->Schedule->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => $scheduleLine));
                            }
                        }
                    }
                    $this->Session->setFlash('資料已經處理完成，建議針對自己需求做適當的編輯');
                    $this->redirect(array('action' => 'view', $newScheduleId));
                } else {
                    $this->Session->setFlash('資料處理過程發生錯誤，請重試');
                    $this->redirect(array('action' => 'view', $scheduleId));
                }
            }
        } else {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function import($fromScheduleId = 0) {
        $fromScheduleId = intval($fromScheduleId);
        $toScheduleId = 0;
        /*
         * 選擇要匯入的 ScheduleDay
         */
        $days = array();
        if (!empty($this->request->data['Schedule']['days']) && !empty($this->request->data['Schedule']['to'])) {
            $days = array_unique($this->request->data['Schedule']['days']);
            $toScheduleId = intval($this->request->data['Schedule']['to']);
        }
        if ($toScheduleId > 0 && empty($this->loginMember['id'])) {
            $check = $this->Session->read('Guest.Schedules.' . $toScheduleId);
            if (empty($check)) {
                $toScheduleId = 0;
            }
        }
        if ($fromScheduleId > 0) {
            $fromSchedule = $this->Schedule->getFull($fromScheduleId, array(
                'OR' => array(
                    'Schedule.is_draft' => 0,
                    array(
                        'Schedule.is_draft' => 1,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                ),
                    ));
        }
        if ($toScheduleId > 0) {
            $toSchedule = $this->Schedule->find('first', array(
                'fields' => array('count_days'),
                'conditions' => array(
                    'Schedule.id' => $toScheduleId,
                    'Schedule.member_id' => $this->loginMember['id'],
                ),
                'contain' => array(
                    'ScheduleDay' => array(
                        'fields' => array('sort'),
                        'order' => array('sort' => 'desc'),
                        'limit' => 1,
                    ),
                ),
                    ));
        }
        /*
         * 匯入的對象必須是目前操作者的資料
         */
        if (!empty($fromSchedule) && !empty($toSchedule)) {
            $sortBase = $toSchedule['ScheduleDay'][0]['sort'] + 1;
            foreach ($fromSchedule['ScheduleDay'] AS $scheduleDay) {
                if (++$toSchedule['Schedule']['count_days'] > 30) {
                    break;
                }
                if (!in_array($scheduleDay['id'], $days)) {
                    continue;
                }
                $this->Schedule->ScheduleDay->create();
                if ($this->Schedule->ScheduleDay->save(array('ScheduleDay' => array(
                                'schedule_id' => $toSchedule['Schedule']['id'],
                                'transport_id' => $scheduleDay['transport_id'],
                                'transport_name' => $scheduleDay['transport_name'],
                                'point_id' => $scheduleDay['point_id'],
                                'point_name' => $scheduleDay['point_name'],
                                'time_arrive' => $scheduleDay['time_arrive'],
                                'sort' => $sortBase,
                                'title' => $scheduleDay['title'],
                                'count_lines' => count($scheduleDay['ScheduleLine']),
                                'note' => $scheduleDay['note'],
                                'summary' => $scheduleDay['summary'],
                                )))) {
                    ++$sortBase;
                    $newScheduleDayId = $this->Schedule->ScheduleDay->getInsertID();
                    foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                        unset($scheduleLine['id']);
                        $scheduleLine['schedule_day_id'] = $newScheduleDayId;
                        $this->Schedule->ScheduleDay->ScheduleLine->create();
                        $this->Schedule->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => $scheduleLine));
                    }
                }
            }
        } elseif (!empty($fromSchedule)) {
            /*
             * 取出目前使用者的所有行程，讓他選擇要匯入的對象
             */
            $this->Paginator->settings['Schedule'] = array(
                'limit' => 5,
                'order' => array('modified' => 'desc'),
            );
            if ($this->Schedule->find('count', array(
                        'conditions' => array('Schedule.member_id' => $this->loginMember['id'],)
                    )) > 0) {
                $this->set('fromSchedule', $fromSchedule);
                $this->set('url', array($fromScheduleId));
            } else {
                $this->Session->setFlash('至少要有一個行程才能夠執行匯入操作');
                $this->redirect(array('action' => 'view', $fromScheduleId));
            }
        } else {
            $this->Session->setFlash('請依照網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function area($areaId = 0, $offset = 0) {
        $areaId = intval($areaId);
        $offset = intval($offset);
        if ($offset < 0 || $offset % 10 != 0) {
            $offset = 0;
        }
        $joins = $scope = $group = array();
        $scope['Schedule.is_draft'] = '0';
        if ($areaId > 0 && $area = $this->Schedule->Area->find('first', array(
            'fields' => array('lft', 'rght'),
            'conditions' => array(
                'Area.id' => $areaId,
            ),
                ))) {
            $joins = array(
                array(
                    'table' => 'areas_models',
                    'alias' => 'AreasModel',
                    'type' => 'INNER',
                    'conditions' => array(
                        'AreasModel.model' => 'Schedule',
                        'AreasModel.foreign_key = Schedule.id',
                    ),
                ),
                array(
                    'table' => 'areas',
                    'alias' => 'Area',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Area.id = AreasModel.area_id',
                    ),
                ),
            );
            $group = array('Schedule.id');
            if ($area['Area']['rght'] - $area['Area']['lft'] == 1) {
                $scope['Area.id'] = $areaId;
            } else {
                $scope['Area.lft >='] = $area['Area']['lft'];
                $scope['Area.rght <='] = $area['Area']['rght'];
            }
        } else {
            $areaId = 0;
        }
        $this->set('url', array($areaId));
        $key = "/schedules/area/{$areaId}/{$offset}";
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Schedule->find('all', array(
                'joins' => $joins,
                'contain' => array(
                    'Member' => array('fields' => array('dirname', 'basename', 'gender'),),
                ),
                'conditions' => $scope,
                'offset' => $offset,
                'limit' => 10,
                'group' => $group,
                'order' => array(
                    'modified' => 'desc'
                ),
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
        $this->set('offset', $offset);
    }

    function page_new($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 20 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/schedules/page_new/' . $offset;
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Schedule->find('all', array(
                'conditions' => array('Schedule.is_draft' => 0),
                'fields' => array('Schedule.id', 'Schedule.title',
                    'Schedule.member_id', 'Schedule.member_name',
                    'Schedule.count_joins', 'Schedule.count_days',
                    'Schedule.created', 'Schedule.count_views',
                    'Schedule.count_points', 'Schedule.intro'),
                'limit' => 20,
                'offset' => $offset,
                'order' => array('Schedule.created' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('id', 'dirname', 'basename', 'gender')
                    ),
                ),
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function page_hot($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 20 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/schedules/page_hot/' . $offset;
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Schedule->find('all', array(
                'conditions' => array('Schedule.is_draft' => 0),
                'fields' => array('Schedule.id', 'Schedule.title',
                    'Schedule.member_id', 'Schedule.member_name',
                    'Schedule.count_joins', 'Schedule.count_days',
                    'Schedule.created', 'Schedule.count_views',
                    'Schedule.count_points', 'Schedule.intro'),
                'limit' => 20,
                'offset' => $offset,
                'order' => array('Schedule.count_views' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('id', 'dirname', 'basename', 'gender')
                    ),
                ),
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function update_status($scheduleId = 0, $status = 0) {
        $scheduleId = intval($scheduleId);
        $status = intval($status);
        if ($scheduleId > 0 && in_array($status, array(0, 1))) {
            $this->Schedule->updateAll(array('is_draft' => $status), array(
                'Schedule.id' => $scheduleId,
                'Schedule.member_id' => $this->loginMember['id'],
            ));
        }
    }

    function import_list($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0) {
            $offset = 0;
        }
        $conditions = array();
        if (empty($this->loginMember['id'])) {
            $schedules = $this->Session->read('Guest.Schedules');
            if (empty($schedules)) {
                $conditions['Schedule.id'] = 0;
            } else {
                $conditions['Schedule.id'] = array_keys($schedules);
            }
        }
        $conditions['Schedule.member_id'] = $this->loginMember['id'];
        $this->set('items', $this->Schedule->find('all', array(
                    'conditions' => $conditions,
                    'order' => array('modified' => 'desc'),
                    'limit' => 5,
                    'offset' => $offset,
                )));
        $this->set('offset', $offset);
    }

    function cart_list() {
        if ($this->loginMember['id'] > 0) {
            $this->set('items', $this->Schedule->find('all', array(
                        'conditions' => array(
                            'Schedule.member_id' => $this->loginMember['id']
                        ),
                        'order' => array('modified' => 'desc'),
                        'limit' => 5,
                    )));
        } else {
            $schedules = $this->Session->read('Guest.Schedules');
            if (!empty($schedules)) {
                $this->set('items', $this->Schedule->find('all', array(
                            'conditions' => array(
                                'Schedule.id' => array_keys($schedules)
                            ),
                            'order' => array('modified' => 'desc'),
                            'limit' => 5,
                        )));
            }
        }
    }

    function point($pointId = 0, $offset = 0) {
        $pointId = intval($pointId);
        $offset = intval($offset);
        if ($offset < 0 || $offset % 10 != 0) {
            $offset = 0;
        }
        if ($pointId > 0) {
            $point = $this->Schedule->Point->find('first', array(
                'fields' => array('id'),
                'conditions' => array(
                    'Point.id' => $pointId,
                ),
                    ));
        }
        $joins = $group = array();
        if (!empty($point)) {
            $joins = array(
                array(
                    'table' => 'schedule_days',
                    'alias' => 'ScheduleDay',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ScheduleDay.schedule_id = Schedule.id',
                    ),
                ),
                array(
                    'table' => 'schedule_lines',
                    'alias' => 'ScheduleLine',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'ScheduleLine.schedule_day_id = ScheduleDay.id',
                        'ScheduleLine.model' => 'Point',
                        'ScheduleLine.foreign_key' => $pointId,
                    ),
                ),
            );
            $group = array('Schedule.id');
        } else {
            $pointId = 0;
        }
        $this->set('url', array($pointId));
        //point($pointId = 0, $offset = 0) {
        $key = "/schedules/point/{$pointId}/{$offset}";
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Schedule->find('all', array(
                'joins' => $joins,
                'contain' => array(
                    'Member' => array('fields' => array('dirname', 'basename', 'gender'),),
                ),
                'conditions' => array(
                    'Schedule.is_draft' => 0,
                    'OR' => array(
                        'ScheduleDay.point_id' => $pointId,
                        'ScheduleLine.id IS NOT NULL'
                    ),
                ),
                'offset' => $offset,
                'limit' => 10,
                'group' => $group,
                'order' => array(
                    'Schedule.modified' => 'desc'
                ),
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
        $this->set('offset', $offset);
    }

    function finding($keyword = '', $offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 10 != 0) {
            $offset = 0;
        }
        $conditions = array('Schedule.is_draft' => 0);
        if (!empty($keyword)) {
            $keyword = Sanitize::clean($keyword);
            $conditions['OR'] = array(
                'Schedule.title LIKE' => "%{$keyword}%",
                'Schedule.point_text LIKE' => "%{$keyword}%",
                'Schedule.member_name LIKE' => "%{$keyword}%",
                'ScheduleDay.summary LIKE' => "%{$keyword}%",
            );
        }
        $this->set('url', array($keyword));
        $items = $this->Schedule->ScheduleDay->find('all', array(
            'contain' => array(
                'Schedule' => array(
                    'Member' => array('fields' => array('dirname', 'basename', 'gender'),),
                ),
            ),
            'conditions' => $conditions,
            'offset' => $offset,
            'limit' => 10,
            'group' => array('ScheduleDay.schedule_id'),
            'order' => array(
                'Schedule.modified' => 'desc'
            ),
                ));
        $this->set('items', $items);
        $this->set('offset', $offset);
    }

    function note($scheduleId = 0) {
        $this->layout = 'output';
        $scheduleId = intval($scheduleId);
        if ($scheduleId > 0) {
            $schedule = $this->Schedule->getFull($scheduleId, array(
                'OR' => array(
                    'Schedule.is_draft' => 0,
                    array(
                        'Schedule.is_draft' => 1,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                ),
                    ));
        }
        if (!empty($schedule)) {
            $this->loadModel('Point');
            if ($schedule['Schedule']['point_id'] > 0) {
                $this->set('startPoint', $this->Point->find('first', array(
                            'fields' => array('address_zh_tw', 'address_en_us', 'address', 'telephone'),
                            'conditions' => array('Point.id' => $schedule['Schedule']['point_id']),
                        )));
            }
            $points = array();
            $countReference = 0;
            $this->set('schedule', $schedule);
            $this->set('title_for_layout', $schedule['Schedule']['title']);
        } else {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function map_mode($id = 0, $scheduleDayId = 0) {
        $id = intval($id);
        $scheduleDayId = intval($scheduleDayId);
        if (empty($this->loginMember['id'])) {
            $check = $this->Session->read('Guest.Schedules.' . $id);
            if (empty($check)) {
                $id = 0;
            }
        }
        if ($id <= 0 || $this->Schedule->field('member_id', array(
                    'Schedule.id' => $id
                )) != $this->loginMember['id']) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        }
        $this->layout = 'map';
        $schedule = $this->Schedule->getFull($id);
        $scheduleDays = $scheduleDaysMap = $scheduleLines = array();
        foreach ($schedule['ScheduleDay'] AS $scheduleDay) {
            $scheduleDays[$scheduleDay['sort']] = $scheduleDay;
            $scheduleDaysMap[$scheduleDay['id']] = $scheduleDay['sort'];
            if (!isset($scheduleLines[$scheduleDay['id']])) {
                $scheduleLines[$scheduleDay['id']] = array();
            }
            foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                $scheduleLines[$scheduleDay['id']][$scheduleLine['id']] = $scheduleLine;
            }
            unset($scheduleDays[$scheduleDay['id']]['ScheduleLine']);
        }
        ksort($scheduleDays);
        if (!isset($scheduleDaysMap[$scheduleDayId])) {
            $scheduleDayId = key($scheduleDaysMap);
        }
        $this->set('schedule', $schedule);
        $this->set('scheduleDays', $scheduleDays);
        $this->set('scheduleDaysMap', $scheduleDaysMap);
        $this->set('scheduleLines', $scheduleLines);
        $this->set('scheduleDayId', $scheduleDayId);
        $this->set('title_for_layout', '行程地圖編輯');
    }

    function export($scheduleId = 0, $format = 'gpx') {
        Configure::write('debug', 1);
        $scheduleId = intval($scheduleId);
        $supportedFormats = array('gpx', 'kml', 'ov2');
        if ($scheduleId > 0) {
            $schedule = $this->Schedule->getFull($scheduleId, array(
                'OR' => array(
                    'Schedule.is_draft' => 0,
                    array(
                        'Schedule.is_draft' => 1,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                ),
                    ));
        }
        if (!in_array($format, $supportedFormats) || empty($schedule)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        } else {
            switch ($format) {
                case 'gpx':
                    $xmlWriter = new XMLWriter();
                    $xmlWriter->openMemory();
                    $xmlWriter->startDocument("1.0", "UTF-8");
                    $xmlWriter->startElement("gpx");
                    $xmlWriter->writeAttribute('xmlns', "http://www.topografix.com/GPX/1/1");
                    $xmlWriter->writeAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
                    $xmlWriter->writeAttribute('xsi:schemaLocation', "http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd");
                    $xmlWriter->writeAttribute("version", "1.1");
                    $xmlWriter->writeAttribute("creator", "就愛玩 - http://travel.olc.tw");
                    $xmlWriter->startElement("metadata");
                    $xmlWriter->startElement("link");
                    $xmlWriter->writeAttribute("href", "http://travel.olc.tw/schedules/view/" . $schedule['Schedule']['id']);
                    $xmlWriter->writeElement("text", $schedule['Schedule']['title']);
                    $xmlWriter->endElement();
                    $xmlWriter->endElement();
                    foreach ($schedule['ScheduleDay'] AS $scheduleDay) {
                        if ($schedule['Schedule']['count_days'] > 1) {
                            $scheduleDay['title'] = '第' . $scheduleDay['sort'] . '天 ' . $scheduleDay['title'];
                        } elseif (empty($scheduleDay['title'])) {
                            $scheduleDay['title'] = $schedule['Schedule']['title'];
                        }
                        $xmlWriter->startElement('rte');
                        $xmlWriter->writeElement('name', $scheduleDay['title']);
                        foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                            if (!empty($scheduleLine['latitude'])) {
                                $xmlWriter->startElement('rtept');
                                $xmlWriter->writeAttribute('lon', strval($scheduleLine['longitude']));
                                $xmlWriter->writeAttribute('lat', strval($scheduleLine['latitude']));
                                $xmlWriter->writeElement('name', $scheduleLine['point_name']);
                                $xmlWriter->endElement();
                            }
                        }
                        $xmlWriter->endElement();
                    }
                    $xmlWriter->endElement();
                    $content = $xmlWriter->flush();
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $schedule['Schedule']['title'] . '.gpx');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . strlen($content));
                    echo $content;
                    exit();
                    break;
                case 'kml':
                    $xmlWriter = new XMLWriter();
                    $xmlWriter->openMemory();
                    $xmlWriter->startDocument("1.0", "UTF-8");
                    $xmlWriter->startElement("kml");
                    $xmlWriter->writeAttribute('xmlns', 'http://www.opengis.net/kml/2.2');
                    $xmlWriter->startElement('Document');
                    $xmlWriter->writeElement('name', $schedule['Schedule']['title']);
                    foreach ($schedule['ScheduleDay'] AS $scheduleDay) {
                        if ($schedule['Schedule']['count_days'] > 1) {
                            $scheduleDay['title'] = 'Day ' . $scheduleDay['sort'] . ' - ' . $scheduleDay['title'];
                        } elseif (empty($scheduleDay['title'])) {
                            $scheduleDay['title'] = $schedule['Schedule']['title'];
                        }
                        $xmlWriter->startElement('Folder');
                        $xmlWriter->writeElement('name', $scheduleDay['title']);
                        $lineStrings = array();
                        foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                            if (!empty($scheduleLine['latitude'])) {
                                $coordinate = strval($scheduleLine['longitude']) . ',' . strval($scheduleLine['latitude']) . ',0';
                                $lineStrings[] = $coordinate;
                                $xmlWriter->startElement('Placemark');
                                $xmlWriter->writeElement('name', $scheduleLine['point_name']);
                                $xmlWriter->startElement('Point');
                                $xmlWriter->writeElement('coordinates', $coordinate);
                                $xmlWriter->endElement();
                                $xmlWriter->endElement();
                            }
                        }
                        if (!empty($scheduleDay['latitude'])) {
                            $coordinate = strval($scheduleDay['longitude']) . ',' . strval($scheduleDay['latitude']) . ',0';
                            $lineStrings[] = $coordinate;
                            $xmlWriter->startElement('Placemark');
                            $xmlWriter->writeElement('name', $scheduleDay['point_name']);
                            $xmlWriter->startElement('Point');
                            $xmlWriter->writeElement('coordinates', $coordinate);
                            $xmlWriter->endElement();
                            $xmlWriter->endElement();
                        }
                        if (!empty($lineStrings)) {
                            $xmlWriter->startElement('Placemark');
                            $xmlWriter->writeElement('name', 'Lines of ' . $scheduleDay['title']);
                            $xmlWriter->startElement('LineString');
                            $xmlWriter->writeElement('altitudeMode', 'absolute');
                            $xmlWriter->writeElement('coordinates', implode("\n", $lineStrings));
                            $xmlWriter->endElement();
                            $xmlWriter->endElement();
                        }
                        $xmlWriter->endElement();
                    }
                    $xmlWriter->endElement();
                    $xmlWriter->endElement();
                    $content = $xmlWriter->flush();
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $schedule['Schedule']['title'] . '.kml');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . strlen($content));
                    echo $content;
                    exit();
                    break;
                case 'ov2':
                    $content = '';
                    foreach ($schedule['ScheduleDay'] AS $scheduleDay) {
                        foreach ($scheduleDay['ScheduleLine'] AS $scheduleLine) {
                            if (!empty($scheduleLine['latitude'])) {
                                $content .= chr(0x02) .
                                        pack("V", strlen($scheduleLine['point_name']) + 14) .
                                        pack("V", round($scheduleLine['longitude'] * 100000)) .
                                        pack("V", round($scheduleLine['latitude'] * 100000)) .
                                        utf8_decode($scheduleLine['point_name']) .
                                        chr(0x00);
                            }
                        }
                    }
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . $schedule['Schedule']['title'] . '.ov2');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . strlen($content));
                    echo $content;
                    exit();
                    break;
            }
        }
    }

    function auto_list() {
        $keyword = Sanitize::clean($_GET['term']);
        $keyword = html_entity_decode(trim($keyword), ENT_QUOTES, 'UTF-8');
        $result = array();
        if (!empty($keyword)) {
            $items = $this->Schedule->find('all', array(
                'limit' => 10,
                'fields' => array('Schedule.id', 'Schedule.title'),
                'conditions' => array(
                    'Schedule.is_draft' => 0,
                    'Schedule.title LIKE' => '%' . $keyword . '%',
                ),
                    ));
            foreach ($items AS $key => $item) {
                $result[] = array(
                    'id' => $item['Schedule']['id'],
                    'label' => $item['Schedule']['title'],
                    'value' => $item['Schedule']['title'],
                );
            }
        }
        $this->set('items', $result);
    }

    function blog_export($scheduleId = 0) {
        $this->layout = 'output';
        $scheduleId = intval($scheduleId);
        if ($scheduleId > 0) {
            $schedule = $this->Schedule->getFull($scheduleId, array(
                'OR' => array(
                    'Schedule.is_draft' => 0,
                    array(
                        'Schedule.is_draft' => 1,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                ),
                    ));
        }
        if (!empty($schedule)) {
            $this->set('schedule', $schedule);
            $this->set('title_for_layout', $schedule['Schedule']['title']);
        } else {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        if ($foreignModel == 'recountDays') {
            $this->Schedule->query('UPDATE schedules AS Schedule
            SET count_days = (
            SELECT COUNT(*) FROM schedule_days AS ScheduleDay
            WHERE ScheduleDay.schedule_id = Schedule.id
            )');
            $this->Session->setFlash('資料已經更新');
            $this->redirect(array('action' => 'index'));
        }
        $foreignId = intval($foreignId);
        $foreignKeys = array();
        $foreignKeys = array(
            'Member' => 'member_id',
            'Point' => 'point_id',
        );
        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['Schedule.' . $foreignKeys[$foreignModel]] = $foreignId;
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->Paginator->settings['Schedule']['limit'] = 20;
        $this->Paginator->settings['Schedule']['order'] = array('modified' => 'desc');
        $items = $this->paginate($this->Schedule, $scope);
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_view($id = null) {
        if (!$id || !$this->request->data = $this->Schedule->read(null, $id)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'Member' => 'member_id',
            'Point' => 'point_id',
        );
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            if (!empty($this->request->data)) {
                $this->request->data['Schedule'][$foreignKeys[$foreignModel]] = $foreignId;
            }
        } else {
            $foreignModel = '';
        }
        if (!empty($this->request->data)) {
            $this->Schedule->create();
            if ($this->Schedule->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Schedule');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Schedule.data', $this->request->data);
                $this->Session->write('form.Schedule.validationErrors', $this->Schedule->validationErrors);
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
            if ($this->Schedule->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Schedule');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Schedule.data', $this->request->data);
                $this->Session->write('form.Schedule.validationErrors', $this->Schedule->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Schedule->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Schedule.data')) {
            $this->Schedule->validationErrors = $this->Session->read('form.Schedule.validationErrors');
            $this->Session->delete('form.Schedule');
        }
        if ($id > 0) {
            $this->request->data = $this->Schedule->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Schedule'][$key])) {
                        $this->request->data['Schedule'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
        $belongsToModels = array(
            'listMember' => array(
                'label' => '會員',
                'modelName' => 'Member',
                'foreignKey' => 'member_id',
            ),
            'listPoint' => array(
                'label' => '地點',
                'modelName' => 'Point',
                'foreignKey' => 'point_id',
            ),
        );
        $this->set('belongsToModels', $belongsToModels);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Schedule->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

    function admin_pull() {
        if (!file_exists(TMP . 'agency')) {
            mkdir(TMP . 'agency', 0777);
        }
        $scheduleId = 0;
        if (!empty($this->request->data)) {
            $this->request->data['Schedule']['point_id'] = 0;
            $this->request->data['Schedule']['member_id'] = 7;
            $this->request->data['Schedule']['member_name'] = '工讀生';
            $this->request->data['Schedule']['count_joins'] = 10;
            $this->request->data['Schedule']['count_days'] = 0;
            $this->request->data['Schedule']['intro'] = '本行程內容參考自鳳凰旅行社網站 , 從相關連結可以開啟原始網頁瀏覽';
            $this->Schedule->create();
            if ($this->Schedule->save($this->request->data)) {
                $this->loadModel('Link');
                $scheduleId = $this->Schedule->getInsertID();
                $this->Link->create();
                $this->Link->save(array('Link' => array(
                        'member_id' => 7,
                        'model' => 'Schedule',
                        'foreign_key' => $scheduleId,
                        'member_name' => '工讀生',
                        'title' => '鳳凰旅行社',
                        'url' => $this->request->data['Schedule']['source'],
                        'body' => $this->request->data['Schedule']['title'],
                        'is_active' => 1,
                        )));
                $daySort = 1;
                foreach ($this->request->data['ScheduleDay'] AS $day) {
                    if (empty($day['title']))
                        continue;
                    $this->Schedule->ScheduleDay->create();
                    if ($this->Schedule->ScheduleDay->save(array('ScheduleDay' => array(
                                    'schedule_id' => $scheduleId,
                                    'point_name' => $day['point_name'],
                                    'sort' => $daySort,
                                    'title' => $day['title'],
                                    )))) {
                        $scheduleDayId = $this->Schedule->ScheduleDay->getInsertID();
                        ++$daySort;
                        if (!empty($day['lines'])) {
                            $lines = explode("\n", $day['lines']);
                            $lineSort = 1;
                            $this->Schedule->ScheduleDay->ScheduleLine->disableSummary = true;
                            foreach ($lines AS $line) {
                                $this->Schedule->ScheduleDay->ScheduleLine->create();
                                if ($this->Schedule->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
                                                'schedule_day_id' => $scheduleDayId,
                                                'point_name' => $line,
                                                'sort' => $lineSort,
                                                )))) {
                                    ++$lineSort;
                                }
                            }
                            $this->Schedule->ScheduleDay->ScheduleLine->disableSummary = false;
                            $this->Schedule->ScheduleDay->ScheduleLine->scheduleDayId = $scheduleDayId;
                            $this->Schedule->ScheduleDay->ScheduleLine->updateScheduleDay();
                        }
                    }
                }
                $this->Schedule->updateCountPoints($scheduleId);
            }
        }
        //$key = TMP . 'agency' . DS . 'settour';
        //$source = APP . '/Console/Command/data/Settour_20121210';
        $source = APP . '/Console/Command/data/Phoenix_20121210';
        $records = unserialize(file_get_contents(APP . '/Console/Command/data/Phoenix_20121210'));
        $keyFile = TMP . 'agency' . DS . 'phoenix';
        $key = '';
        if (file_exists($keyFile)) {
            $key = file_get_contents($keyFile);
        } else {
            $key = key($records);
            file_put_contents($keyFile, $key);
        }
        if (!empty($this->request->data['ScheduleDay'])) {
            $idx = 0;
            foreach ($this->request->data['ScheduleDay'] AS $day) {
                $checkSum = md5($records[$key][$idx]['source']);
                if (!file_exists(TMP . 'agency' . DS . $checkSum)) {
                    $lines = explode("\n", $day['lines']);
                    file_put_contents(TMP . 'agency' . DS . $checkSum, serialize($lines));
                }
                ++$idx;
            }
        }
        if (!empty($scheduleId)) {
            $url = Router::url('/schedules/view/' . $scheduleId, true);
            $this->Session->setFlash('<a href="' . $url . '" target="_blank">' . $url . '</a>');
            while ($key != key($records)) {
                next($records);
            }
            next($records);
            $key = key($records);
            file_put_contents($keyFile, $key);
        }
        foreach ($records[$key] AS $rKey => $rVal) {
            switch ($rKey) {
                case 'source':
                case 'title':
                case 'date':
                    continue;
                    break;
                default:
                    if (!empty($rVal['source'])) {
                        $checkSum = md5($rVal['source']);
                        if (file_exists(TMP . 'agency' . DS . $checkSum)) {
                            $records[$key][$rKey]['points'] = unserialize(file_get_contents(TMP . 'agency' . DS . $checkSum));
                            $records[$key][$rKey]['red'] = 1;
                        }
                    }
            }
        }
        $this->set('record', $records[$key]);
    }

}