<?php

App::uses('Sanitize', 'Utility');

class ScheduleDaysController extends AppController {

    var $name = 'ScheduleDays';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('view', 'add', 'quick_day', 'edit',
            'delete', 'move_lines', 'remove_lines', 'sort_lines');
    }

    function view($id = 0) {
        $id = intval($id);
        if ($id <= 0 || !$this->request->data = $this->ScheduleDay->find('first', array(
            'conditions' => array('ScheduleDay.id' => $id),
            'contain' => array(
                'Schedule' => array(
                    'fields' => array('id', 'time_start')
                ),
                'ScheduleLine' => array(
                    'order' => array('sort' => 'asc'),
                ),
            ),
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } else {
            $this->set('title_for_layout', $this->request->data['ScheduleDay']['title']);
            $owner = $this->ScheduleDay->Schedule->field('member_id', array(
                'Schedule.id' => $this->request->data['ScheduleDay']['schedule_id']
                    ));
            $this->set('owner', $owner);
            if ($this->loginMember['id'] == $owner) {
                if ($otherDays = $this->ScheduleDay->find('list', array(
                    'conditions' => array(
                        'ScheduleDay.schedule_id' => $this->request->data['ScheduleDay']['schedule_id'],
                    ),
                    'order' => array('sort' => 'asc', 'id' => 'asc'),
                        ))) {
                    $countDay = 1;
                    foreach ($otherDays AS $key => $day) {
                        if ($key == $id) {
                            unset($otherDays[$key]);
                        } else {
                            $otherDays[$key] = array(
                                $countDay,
                                $day,
                            );
                        }
                        ++$countDay;
                    }
                }
                $this->set('otherDays', $otherDays);
            }
        }
        $activities = Set::combine($this->ScheduleDay->ScheduleLine->Activity->find('all', array(
                            'fields' => array('id', 'class', 'name')
                        )), '{n}.Activity.id', '{n}.Activity');
        $transports = Set::combine($this->ScheduleDay->ScheduleLine->Transport->find('all', array(
                            'fields' => array('id', 'class', 'name')
                        )), '{n}.Transport.id', '{n}.Transport');
        $notes = $this->ScheduleDay->ScheduleNote->find('all', array(
            'conditions' => array('schedule_day_id' => $id,),
            'order' => array('sort ASC'),
                ));
        $dayNotes = array();
        foreach ($notes AS $note) {
            if (!isset($dayNotes[$note['ScheduleNote']['schedule_line_id']])) {
                $dayNotes[$note['ScheduleNote']['schedule_line_id']] = array();
            }
            $dayNotes[$note['ScheduleNote']['schedule_line_id']][] = $note['ScheduleNote'];
        }
        $this->set('isAjax', !empty($this->request->params['isAjax']));
        $this->set('transports', $transports);
        $this->set('activities', $activities);
        $this->set('notes', $dayNotes);
    }

    function add($scheduleId = 0) {
        $scheduleId = intval($scheduleId);
        if (empty($this->loginMember['id'])) {
            $check = $this->Session->read('Guest.Schedules.' . $scheduleId);
            if (empty($check)) {
                $scheduleId = 0;
            }
        }
        if ($scheduleId > 0) {
            $schedule = $this->ScheduleDay->Schedule->find('first', array(
                'conditions' => array(
                    'Schedule.id' => $scheduleId,
                    'Schedule.member_id' => $this->loginMember['id'],
                ),
                    ));
        }
        if (empty($schedule)) {
            if (empty($this->request->params['isAjax'])) {
                $this->Session->setFlash('請依據網頁指示操作');
                $this->redirect('/');
            } else {
                echo '請依據網頁指示操作';
                exit();
            }
        } else {
            if (!empty($this->request->params['isAjax'])) {
                $sort = $this->ScheduleDay->field('sort', array(
                            'schedule_id' => $scheduleId), array(
                            'sort DESC'
                        )) + 1;
                $this->ScheduleDay->create();
                if ($this->ScheduleDay->save(array('ScheduleDay' => array(
                                'sort' => $sort,
                                'schedule_id' => $scheduleId,
                                )))) {
                    echo 'ok:' . $this->ScheduleDay->getInsertID();
                } else {
                    echo implode("\n", $this->ScheduleDay->validationErrors);
                }
                exit();
            }
            $this->loadModel('Activity');
            $this->loadModel('Transport');
            $activities = Set::combine($this->Activity->find('all', array(
                                'fields' => array('id', 'class', 'name')
                            )), '{n}.Activity.id', '{n}.Activity');
            $transports = Set::combine($this->Transport->find('all', array(
                                'fields' => array('id', 'class', 'name')
                            )), '{n}.Transport.id', '{n}.Transport');
            $this->set('scheduleId', $scheduleId);
            $this->set('activities', $activities);
            $this->set('transports', $transports);
            if (!empty($this->request->data)) {
                if (!empty($this->request->data['ScheduleDay']['schedule_id'])) {
                    $this->Session->write('block', 1);
                    exit();
                }
                $this->request->data['ScheduleDay']['sort'] = $this->ScheduleDay->field('sort', array(
                    'schedule_id' => $scheduleId), array(
                    'sort DESC'
                        ));
                if (empty($this->request->data['ScheduleDay']['sort'])) {
                    $this->request->data['ScheduleDay']['sort'] = 0;
                }
                $this->request->data['ScheduleDay']['schedule_id'] = $scheduleId;
                $this->request->data['ScheduleDay']['transport_id'] = intval($this->request->data['ScheduleDay']['transport_id']);
                $this->ScheduleDay->create();
                if ($this->ScheduleDay->save($this->request->data)) {
                    $scheduleDayId = $this->ScheduleDay->getInsertID();
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
                        $this->ScheduleDay->ScheduleLine->create();
                        $activityName = $transportName = '';
                        if (isset($activities[$this->request->data['ScheduleLine']['activity_id'][$key]])) {
                            $activityName = $activities[$this->request->data['ScheduleLine']['activity_id'][$key]]['name'];
                        }
                        if (isset($transports[$this->request->data['ScheduleLine']['transport_id'][$key]])) {
                            $transportName = $transports[$this->request->data['ScheduleLine']['transport_id'][$key]]['name'];
                        }
                        $this->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
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
                    $this->Session->setFlash('行程已經儲存');
                    $this->redirect(array('controller' => 'schedules', 'action' => 'view', $scheduleId, $scheduleDayId));
                } else {
                    $this->Session->setFlash('資料儲存失敗，請重試');
                }
            }
        }
    }

    function edit($id = 0) {
        $id = intval($id);
        if ($id > 0) {
            $scheduleDay = $this->ScheduleDay->find('first', array(
                'conditions' => array(
                    'ScheduleDay.id' => $id,
                    'Schedule.member_id' => $this->loginMember['id'],
                ),
                'contain' => array(
                    'ScheduleLine' => array(
                        'order' => array('ScheduleLine.sort ASC')
                    ),
                    'Schedule' => array(
                        'fields' => array('member_id')
                    ),
                ),
                    ));
            if (empty($this->loginMember['id'])) {
                $check = $this->Session->read('Guest.Schedules.' . $scheduleDay['ScheduleDay']['schedule_id']);
                if (empty($check)) {
                    $scheduleDay = array();
                }
            }
        }
        if (empty($scheduleDay)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } else {
            $this->loadModel('Activity');
            $this->loadModel('Transport');
            $activities = Set::combine($this->Activity->find('all', array(
                                'fields' => array('id', 'class', 'name')
                            )), '{n}.Activity.id', '{n}.Activity');
            $transports = Set::combine($this->Transport->find('all', array(
                                'fields' => array('id', 'class', 'name')
                            )), '{n}.Transport.id', '{n}.Transport');
            $this->set('activities', $activities);
            $this->set('transports', $transports);
            if (!empty($this->request->data)) {
                $this->request->data['ScheduleDay']['id'] = $id;
                if ($this->ScheduleDay->save($this->request->data)) {
                    $lineIds = Set::combine($scheduleDay['ScheduleLine'], '{n}.id', '{n}.id');
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
                        if (empty($this->request->data['ScheduleLine']['id'][$key])) {
                            $this->ScheduleDay->ScheduleLine->create();
                        } else {
                            if (isset($lineIds[$this->request->data['ScheduleLine']['id'][$key]])) {
                                unset($lineIds[$this->request->data['ScheduleLine']['id'][$key]]);
                            }
                            $this->ScheduleDay->ScheduleLine->id = $this->request->data['ScheduleLine']['id'][$key];
                        }
                        $activityName = $transportName = '';
                        if (isset($activities[$this->request->data['ScheduleLine']['activity_id'][$key]])) {
                            $activityName = $activities[$this->request->data['ScheduleLine']['activity_id'][$key]]['name'];
                        }
                        if (isset($transports[$this->request->data['ScheduleLine']['transport_id'][$key]])) {
                            $transportName = $transports[$this->request->data['ScheduleLine']['transport_id'][$key]]['name'];
                        }
                        $this->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
                                'model' => 'Point',
                                'foreign_key' => empty($this->request->data['ScheduleLine']['point_id'][$key]) ? '0' : $this->request->data['ScheduleLine']['point_id'][$key],
                                'schedule_day_id' => $id,
                                'sort' => $this->request->data['ScheduleLine']['sort'][$key],
                                'point_name' => $this->request->data['ScheduleLine']['point_name'][$key],
                                'longitude' => $this->request->data['ScheduleLine']['longitude'][$key],
                                'latitude' => $this->request->data['ScheduleLine']['latitude'][$key],
                                'activity_id' => $this->request->data['ScheduleLine']['activity_id'][$key],
                                'transport_id' => $this->request->data['ScheduleLine']['transport_id'][$key],
                                'time_arrive' => $this->request->data['ScheduleLine']['time_arrive'][$key],
                                'note' => $this->request->data['ScheduleLine']['note'][$key],
                                'activity_name' => $activityName,
                                'transport_name' => $transportName,
                                'minutes_stay' => $minutesStay,
                                )));
                    }
                    if (!empty($lineIds)) {
                        foreach ($lineIds AS $lineId) {
                            $this->ScheduleDay->ScheduleLine->delete($lineId);
                        }
                    }
                    $this->Session->setFlash('行程已經儲存');
                    $this->redirect(array(
                        'controller' => 'schedules',
                        'action' => 'view',
                        $scheduleDay['ScheduleDay']['schedule_id'],
                        $id));
                } else {
                    $this->Session->setFlash('資料儲存失敗，請重試');
                }
            }
            $this->set('id', $id);
            if (empty($this->request->data)) {
                $this->request->data = $scheduleDay;
                foreach ($this->request->data['ScheduleLine'] AS $key => $val) {
                    $this->request->data['ScheduleLine'][$key]['time_leave'] = '';
                    if (!empty($val['time_arrive'])) {
                        $this->request->data['ScheduleLine'][$key]['time_leave'] =
                                date('H:i:00', strtotime($val['time_arrive']) + $val['minutes_stay'] * 60);
                    }
                }
            }
        }
    }

    function delete($id = 0) {
        $id = intval($id);
        $scheduleDay = $scheduleDayOwner = false;
        if ($id > 0) {
            $scheduleDay = $this->ScheduleDay->read(array('schedule_id', 'sort'), $id);
            $scheduleDayOwner = $this->ScheduleDay->Schedule->field('member_id', array(
                'Schedule.id' => $scheduleDay['ScheduleDay']['schedule_id'],
                    ));
            if (empty($this->loginMember['id'])) {
                $check = $this->Session->read('Guest.Schedules.' . $scheduleDay['ScheduleDay']['schedule_id']);
                if (empty($check)) {
                    $id = 0;
                }
            }
        }
        if ($id <= 0 || !$scheduleDay || ($scheduleDayOwner != $this->loginMember['id'])) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } elseif ($this->ScheduleDay->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
            $this->ScheduleDay->updateAll(
                    array('ScheduleDay.sort' => 'ScheduleDay.sort - 1'), array(
                'ScheduleDay.schedule_id' => $scheduleDay['ScheduleDay']['schedule_id'],
                'ScheduleDay.sort >' => $scheduleDay['ScheduleDay']['sort'],
                    )
            );
            $this->ScheduleDay->Schedule->updateAll(
                    array('Schedule.count_days' => 'Schedule.count_days - 1'), array('Schedule.id' => $scheduleDay['ScheduleDay']['schedule_id'])
            );
            $this->redirect('/schedules/view/' . $scheduleDay['ScheduleDay']['schedule_id']);
        } else {
            $this->redirect($this->referer());
        }
    }

    function sort($id = 0) {
        $id = intval($id);
        /*
         * 確認目前登入使用者擁有這個資料，以及傳送過來的資料都屬於指定的 ScheduleDay
         */
        if ($id > 0 && $this->ScheduleDay->find('count', array(
                    'conditions' => array(
                        'ScheduleDay.id' => $id,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ),
                    'contain' => array('Schedule'),
                )) == 1 && $this->ScheduleDay->ScheduleLine->find('count', array(
                    'conditions' => array(
                        'ScheduleLine.id' => array_keys($_POST),
                        'ScheduleLine.schedule_day_id' => $id,
                    ),
                )) == count($_POST)) {
            foreach ($_POST AS $scheduleLineId => $sort) {
                $this->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
                        'id' => $scheduleLineId,
                        'sort' => $sort,
                        )));
            }
        }
    }

    function quick_day($scheduleDayId = 0) {
        $scheduleDayId = intval($scheduleDayId);
        if ($scheduleDayId > 0) {
            $scheduleDay = $this->ScheduleDay->find('first', array(
                'fields' => array('ScheduleDay.sort', 'Schedule.id',
                    'Schedule.time_start'),
                'conditions' => array(
                    'ScheduleDay.id' => $scheduleDayId,
                    'Schedule.member_id' => $this->loginMember['id']
                ),
                'contain' => array('Schedule'),
                    ));
            if (empty($this->loginMember['id'])) {
                $check = $this->Session->read('Guest.Schedules.' . $scheduleDay['Schedule']['id']);
                if (empty($check)) {
                    $scheduleDay = array();
                }
            }
        }
        /*
         * 確認目前操作者是否擁有該天資料
         */
        if (empty($scheduleDay)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } elseif (!empty($this->request->data['ScheduleDay']['lines'])) {
            $lines = explode(chr(10), $this->request->data['ScheduleDay']['lines']);
            $count = array(
                'empty' => 0,
                'long' => 0,
                'correct' => 0,
                'saved' => 0,
            );
            foreach ($lines AS $key => $line) {
                $line = strip_tags(trim($line));
                if ($line == '') {
                    ++$count['empty'];
                    unset($lines[$key]);
                } elseif (mb_strlen($line, 'utf8') > 128) {
                    ++$count['long'];
                    unset($lines[$key]);
                } else {
                    $lines[$key] = $line;
                }
            }
            $count['correct'] = count($lines);
            if ($count['long'] > 0) {
                $this->Session->setFlash('您所提供的資料中，有 ' . $count['long'] . ' 筆資料太長(超過 128 字)，請調整後重新送出');
            } elseif ($count['correct'] <= 0) {
                $this->Session->setFlash('您所提供的資料中，沒有可以建立的資料');
            } else {
                $this->loadModel('Point');
                $currentSort = $this->ScheduleDay->ScheduleLine->field('sort', array(
                    'ScheduleLine.schedule_day_id' => $scheduleDayId
                        ), array('ScheduleLine.sort DESC'));
                if (empty($currentSort)) {
                    $currentSort = 0;
                }
                foreach ($lines AS $line) {
                    ++$currentSort;
                    $scheduleLine = array(
                        'model' => 'Point',
                        'foreign_key' => 0,
                        'schedule_day_id' => $scheduleDayId,
                        'sort' => $currentSort,
                        'point_name' => $line,
                    );
                    /*
                     * 試著以標題取得地點資訊
                     */
                    if ($point = $this->Point->find('first', array(
                        'fields' => array('id', 'latitude', 'longitude'),
                        'conditions' => array('OR' => array(
                                'Point.title' => $line,
                                'Point.title_zh_tw' => $line,
                                'Point.title_en_us' => $line,
                        )),
                            ))) {
                        $scheduleLine['foreign_key'] = $point['Point']['id'];
                        $scheduleLine['latitude'] = $point['Point']['latitude'];
                        $scheduleLine['longitude'] = $point['Point']['longitude'];
                    }
                    $this->ScheduleDay->ScheduleLine->create();
                    if ($this->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => $scheduleLine))) {
                        ++$count['saved'];
                    }
                }
                if ($count['saved'] > 0) {
                    $this->ScheduleDay->id = $scheduleDayId;
                    $this->ScheduleDay->afterSave(false);
                }
                $this->set('scheduleDayMessage', 'done');
            }
        }
        $this->set('scheduleDay', $scheduleDay);
        $this->set('scheduleDayId', $scheduleDayId);
    }

    function choose($formField = '', $scheduleId = 0) {
        if (!empty($formField)) {
            $formField = trim(Sanitize::clean($formField));
            if ($scheduleId === 0) {
                $scheduleId = $this->Session->read('Schedule.selected');
                $this->set('url', array($formField, $scheduleId));
            } elseif ($scheduleId === 'schedules') {
                $this->set('url', array($formField, 'schedules'));
                $scheduleId = 0;
            } else {
                $scheduleId = intval($scheduleId);
            }

            if ($scheduleId > 0 && !$this->ScheduleDay->Schedule->hasAny(array(
                        'Schedule.id' => $scheduleId,
                        'Schedule.member_id' => $this->loginMember['id'],
                    ))) {
                $this->Session->write('Schedule.selected', 0);
                $scheduleId = 0;
            }

            if (empty($scheduleId)) {
                $this->Paginator->settings['Schedule'] = array(
                    'fields' => array('id', 'title'),
                    'order' => array('modified' => 'desc'),
                    'limit' => 5,
                );
                $this->set('schedules', $this->Paginator->paginate($this->ScheduleDay->Schedule, array(
                            'Schedule.member_id' => $this->loginMember['id'],
                        )));
            } else {
                $this->Session->write('Schedule.selected', $scheduleId);
                $this->set('scheduleId', $scheduleId);
                $this->set('scheduleTitle', $this->ScheduleDay->Schedule->field('title', array(
                            'id' => $scheduleId,
                        )));
                $this->set('scheduleDays', $this->ScheduleDay->find('list', array(
                            'conditions' => array(
                                'ScheduleDay.schedule_id' => $scheduleId,
                            ),
                            'order' => array('sort' => 'asc', 'id' => 'asc'),
                        )));
            }
            $this->set('formField', $formField);
        }
    }

    function move_lines($scheduleId = 0, $sourceDayId = 0, $targetDayId = 0) {
        $scheduleId = intval($scheduleId);
        $targetDayId = intval($targetDayId);
        $sourceDayId = intval($sourceDayId);
        //check owner of schedule
        $scheduleId = $this->ScheduleDay->Schedule->field('id', array(
            'id' => $scheduleId,
            'member_id' => $this->loginMember['id'],
                ));
        //check if source day existed in the schedule
        $sourceDayId = $this->ScheduleDay->field('id', array(
            'id' => $sourceDayId,
            'schedule_id' => $scheduleId,
                ));
        if ($targetDayId > 0) {
            $targetDayId = $this->ScheduleDay->field('id', array(
                'id' => $targetDayId,
                'schedule_id' => $scheduleId,
                    ));
        }
        if ($scheduleId > 0 && $sourceDayId > 0 && !empty($this->request->data)) {
            if (empty($targetDayId)) {
                $sort = $this->ScheduleDay->field('sort', array(
                    'ScheduleDay.schedule_id' => $scheduleId,
                        ), array('ScheduleDay.sort DESC'));
                $this->ScheduleDay->create();
                $this->ScheduleDay->save(array('ScheduleDay' => array(
                        'schedule_id' => $scheduleId,
                        'sort' => intval($sort) + 1,
                        'count_lines' => 0,
                        )));
                $targetDayId = $this->ScheduleDay->getInsertID();
            }
            if ($targetDayId > 0) {
                $this->request->data = Sanitize::clean($this->request->data);
                $lineCount = $this->ScheduleDay->ScheduleLine->find('count', array(
                    'conditions' => array(
                        'ScheduleLine.schedule_day_id' => $sourceDayId,
                        'ScheduleLine.id' => $this->request->data,
                    ),
                        ));
                if ($lineCount === count($this->request->data)) {
                    $this->ScheduleDay->ScheduleLine->updateAll(array(
                        'ScheduleLine.schedule_day_id' => $targetDayId
                            ), array(
                        'ScheduleLine.id IN (' . implode(',', $this->request->data) . ')',
                    ));
                    $this->ScheduleDay->ScheduleLine->updateScheduleDay($targetDayId);
                    $this->ScheduleDay->ScheduleLine->updateScheduleDay($sourceDayId);
                }
            }
        }
        exit();
    }

    function remove_lines($scheduleId = 0, $sourceDayId = 0) {
        $scheduleId = intval($scheduleId);
        $sourceDayId = intval($sourceDayId);
        //check owner of schedule
        $scheduleId = $this->ScheduleDay->Schedule->field('id', array(
            'id' => $scheduleId,
            'member_id' => $this->loginMember['id'],
                ));
        //check if source day existed in the schedule
        $sourceDayId = $this->ScheduleDay->field('id', array(
            'id' => $sourceDayId,
            'schedule_id' => $scheduleId,
                ));
        if ($scheduleId > 0 && $sourceDayId > 0 && !empty($this->request->data)) {
            $this->request->data = Sanitize::clean($this->request->data);
            $lineCount = $this->ScheduleDay->ScheduleLine->find('count', array(
                'conditions' => array(
                    'ScheduleLine.schedule_day_id' => $sourceDayId,
                    'ScheduleLine.id' => $this->request->data,
                ),
                    ));
            if ($lineCount === count($this->request->data)) {
                $this->ScheduleDay->ScheduleLine->deleteAll(array(
                    'ScheduleLine.id IN (' . implode(',', $this->request->data) . ')'
                ));
                $this->ScheduleDay->ScheduleLine->updateScheduleDay($targetDayId);
                $this->ScheduleDay->ScheduleLine->updateScheduleDay($sourceDayId);
                $this->ScheduleDay->Schedule->updateCountPoints($scheduleId);
            }
        }
        exit();
    }

    function sort_lines($scheduleId = 0, $sourceDayId = 0) {
        $scheduleId = intval($scheduleId);
        $sourceDayId = intval($sourceDayId);
        //check owner of schedule
        $scheduleId = $this->ScheduleDay->Schedule->field('id', array(
            'id' => $scheduleId,
            'member_id' => $this->loginMember['id'],
                ));
        //check if source day existed in the schedule
        $sourceDayId = $this->ScheduleDay->field('id', array(
            'id' => $sourceDayId,
            'schedule_id' => $scheduleId,
                ));
        if ($scheduleId > 0 && $sourceDayId > 0 && !empty($this->request->data)) {
            $this->request->data = Sanitize::clean($this->request->data);
            $lineCount = $this->ScheduleDay->ScheduleLine->find('count', array(
                'conditions' => array(
                    'ScheduleLine.id' => array_keys($this->request->data),
                    'ScheduleLine.schedule_day_id' => $sourceDayId,
                )
                    ));
            if ($lineCount === count($this->request->data)) {
                foreach($this->request->data AS $lineId => $lineSort) {
                    $this->ScheduleDay->ScheduleLine->save(array('ScheduleLine' => array(
                        'id' => $lineId,
                        'sort' => intval($lineSort),
                    )));
                }
                echo 'ok';
            }
        }
        exit();
    }

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();
        $foreignKeys = array(
            'Schedule' => 'schedule_id',
        );
        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['ScheduleDay.' . $foreignKeys[$foreignModel]] = $foreignId;
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->Paginator->settings['ScheduleDay']['limit'] = 20;
        $items = $this->Paginator->paginate($this->ScheduleDay, $scope);
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_view($id = null) {
        if (!$id || !$this->request->data = $this->ScheduleDay->read(null, $id)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect(array('action' => 'index'));
        }
    }

    function admin_add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $foreignKeys = array(
            'Schedule' => 'schedule_id',
        );
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            if (!empty($this->request->data)) {
                $this->request->data['ScheduleDay'][$foreignKeys[$foreignModel]] = $foreignId;
            }
        } else {
            $foreignModel = '';
        }
        if (!empty($this->request->data)) {
            $this->ScheduleDay->create();
            if ($this->ScheduleDay->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.ScheduleDay');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.ScheduleDay.data', $this->request->data);
                $this->Session->write('form.ScheduleDay.validationErrors', $this->ScheduleDay->validationErrors);
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
            if ($this->ScheduleDay->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.ScheduleDay');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.ScheduleDay.data', $this->request->data);
                $this->Session->write('form.ScheduleDay.validationErrors', $this->ScheduleDay->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->ScheduleDay->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.ScheduleDay.data')) {
            $this->ScheduleDay->validationErrors = $this->Session->read('form.ScheduleDay.validationErrors');
            $this->Session->delete('form.ScheduleDay');
        }
        if ($id > 0) {
            $this->request->data = $this->ScheduleDay->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['ScheduleDay'][$key])) {
                        $this->request->data['ScheduleDay'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
        $belongsToModels = array(
            'listSchedule' => array(
                'label' => '行程',
                'modelName' => 'Schedule',
                'foreignKey' => 'schedule_id',
            ),
        );

        foreach ($belongsToModels AS $key => $model) {
            if ($foreignModel == $model['modelName']) {
                unset($belongsToModels[$key]);
                continue;
            }
            $this->set($key, $this->ScheduleDay->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->ScheduleDay->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}