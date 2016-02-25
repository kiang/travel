<?php

class Schedule extends AppModel {

    var $name = 'Schedule';
    var $validate = array(
        'count_joins' => array(
            'numberFormat' => array(
                'rule' => 'numeric',
                'message' => '數字格式有誤',
                'allowEmpty' => true,
            ),
        ),
        'count_days' => array(
            'numberFormat' => array(
                'rule' => array('between', 1, 20),
                'message' => '活動天數必須介於 1 ~ 20 之間',
                'allowEmpty' => false,
            ),
        ),
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '這個欄位必須輸入',
            ),
        ),
    );
    var $belongsTo = array(
        'Member' => array(
            'foreignKey' => 'member_id',
            'className' => 'Member',
        ),
        'Point' => array(
            'foreignKey' => 'point_id',
            'className' => 'Point',
        ),
    );
    var $hasMany = array(
        'ScheduleDay' => array(
            'foreignKey' => 'schedule_id',
            'dependent' => false,
            'className' => 'ScheduleDay',
        ),
        'ScheduleNote' => array(
            'foreignKey' => 'schedule_id',
            'dependent' => false,
            'className' => 'ScheduleNote',
        ),
    );
    var $hasOne = array(
        'ScheduleTask' => array(
            'foreignKey' => 'schedule_id',
            'dependent' => false,
            'className' => 'ScheduleTask',
        ),
    );
    var $hasAndBelongsToMany = array(
        'Area' => array(
            'joinTable' => 'areas_models',
            'foreignKey' => 'foreign_key',
            'associationForeignKey' => 'area_id',
            'className' => 'Area',
            'conditions' => array('AreasModel.model' => 'Schedule'),
        ),
    );

    function beforeSave($options = array()) {
        parent::beforeSave();
        $intFields = array('point_id', 'member_id', 'count_joins', 'count_days');
        foreach ($intFields AS $field) {
            if (isset($this->data['Schedule'][$field])) {
                $this->data['Schedule'][$field] = intval($this->data['Schedule'][$field]);
            }
        }
        return true;
    }

    function beforeFind($queryData) {
        $loginMember = Configure::read('loginMember');
        if ($loginMember['group_id'] != 1) {
            $queryData['conditions'][] = array(
                'OR' => array(
                    'Schedule.member_id' => $loginMember['id'],
                    'Schedule.is_draft' => 0,
                )
            );
        }
        return $queryData;
    }

    function afterDelete() {
        $this->deleteDayLine($this->id);
        $this->deleteCommentLink($this->id);
        parent::afterDelete();
    }

    function deleteDayLine($id) {
        $this->query('DELETE ScheduleDay, ScheduleLine
	    FROM schedule_days AS ScheduleDay
	    LEFT JOIN schedule_lines AS ScheduleLine ON ScheduleLine.schedule_day_id = ScheduleDay.schedule_id
	    WHERE ScheduleDay.schedule_id = ' . $id);
    }

    function deleteCommentLink($id) {
        $this->query('DELETE Comment, Link
	    FROM comments AS Comment
	    LEFT JOIN links AS Link ON (Link.model = \'Schedule\' AND Link.foreign_key = Comment.foreign_key)
	    WHERE Comment.model = \'Schedule\' AND Comment.foreign_key = ' . $id);
    }

    function getFull($scheduleId, $conditions = array()) {
        $conditions = array_merge($conditions, array('Schedule.id' => $scheduleId));
        return $this->find('first', array(
                    'fields' => array(
                        'member_id', 'member_name', 'point_id', 'title', 'point_text',
                        'latitude', 'longitude', 'time_start', 'count_joins', 'count_days',
                        'count_points', 'created', 'modified', 'intro',
                    ),
                    'conditions' => $conditions,
                    'contain' => array(
                        'Member' => array(
                            'fields' => array('gender'),
                        ),
                        'ScheduleDay' => array(
                            'fields' => array(
                                'transport_id', 'transport_name', 'point_id',
                                'point_name', 'count_lines', 'latitude', 'longitude',
                                'time_arrive', 'sort', 'title', 'note', 'summary'
                            ),
                            'order' => array('sort' => 'asc'),
                            'ScheduleLine' => array(
                                'fields' => array(
                                    'model', 'foreign_key', 'activity_id', 'transport_id', 'transport_name',
                                    'point_name', 'activity_name', 'latitude', 'longitude', 'time_arrive',
                                    'minutes_stay', 'note', 'sort'
                                ),
                                'order' => array('sort' => 'asc'),
                            ),
                        ),
                    ),
                ));
    }

    function getDayPoints($scheduleId) {
        /*
         * 取得行程的所有 ScheduleDay
         */
        $result = array();
        if ($scheduleDays = $this->ScheduleDay->find('all', array(
            'fields' => array('id', 'title', 'latitude', 'longitude'),
            'conditions' => array(
                'ScheduleDay.schedule_id' => $scheduleId,
            ),
            'order' => array('sort' => 'asc', 'id' => 'asc'),
            'contain' => array(
                'ScheduleLine' => array(
                    'fields' => array('latitude', 'longitude'),
                    'conditions' => array(
                        'ScheduleLine.latitude !=' => 0,
                        'ScheduleLine.longitude !=' => 0,
                    ),
                    'limit' => 1,
                    'order' => array('sort' => 'asc', 'id' => 'asc'),
                ),
            ),
                ))) {
            $dayCount = 1;
            foreach ($scheduleDays AS $scheduleDay) {
                $title = '第' . $dayCount . '天';
                if (!empty($scheduleDay['ScheduleDay']['title'])) {
                    $title .= ',' . $scheduleDay['ScheduleDay']['title'];
                }
                if (!empty($scheduleDay['ScheduleDay']['latitude']) && !empty($scheduleDay['ScheduleDay']['longitude'])) {
                    $result[] = array(
                        'id' => '#sLine' . $scheduleDay['ScheduleDay']['id'],
                        'model' => 'ScheduleDay',
                        'key' => $scheduleDay['ScheduleDay']['id'],
                        'title' => $title,
                        'body' => '',
                        'latitude' => $scheduleDay['ScheduleDay']['latitude'],
                        'longitude' => $scheduleDay['ScheduleDay']['longitude'],
                    );
                } elseif (!empty($scheduleDay['ScheduleLine'][0]['latitude'])) {
                    $result[] = array(
                        'id' => '#sLine' . $scheduleDay['ScheduleDay']['id'],
                        'model' => 'ScheduleDay',
                        'key' => $scheduleDay['ScheduleDay']['id'],
                        'title' => $title,
                        'latitude' => $scheduleDay['ScheduleLine'][0]['latitude'],
                        'longitude' => $scheduleDay['ScheduleLine'][0]['longitude'],
                    );
                }
                ++$dayCount;
            }
        }
        return $result;
    }

    function updateCountPoints($scheduleId) {
        $this->query('UPDATE schedules SET count_points = (SELECT SUM(count_lines) FROM schedule_days WHERE schedule_id = ' . $scheduleId . ') WHERE id = ' . $scheduleId);
    }

}