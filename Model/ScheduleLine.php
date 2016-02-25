<?php

class ScheduleLine extends AppModel {

    var $name = 'ScheduleLine';
    var $scheduleDayId = 0;
    var $disableSummary = false;
    var $validate = array(
        'point_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '這個欄位必須輸入',
            ),
        ),
    );
    var $belongsTo = array(
        'ScheduleDay' => array(
            'foreignKey' => 'schedule_day_id',
            'className' => 'ScheduleDay',
        ),
        'Point' => array(
            'foreignKey' => 'foreign_key',
            'className' => 'Point',
        ),
        'Activity' => array(
            'foreignKey' => 'activity_id',
            'className' => 'Activity',
        ),
        'Transport' => array(
            'foreignKey' => 'transport_id',
            'className' => 'Transport',
        ),
    );
    var $hasMany = array(
        'ScheduleNote' => array(
            'foreignKey' => 'schedule_line_id',
            'dependent' => false,
            'className' => 'ScheduleNote',
        ),
    );

    function beforeSave($options = array()) {
        $intFields = array('foreign_key', 'activity_id', 'transport_id', 'sort');
        foreach ($intFields AS $field) {
            if (isset($this->data['ScheduleLine'][$field])) {
                $this->data['ScheduleLine'][$field] = intval($this->data['ScheduleLine'][$field]);
            }
        }
        if (isset($this->data['ScheduleLine']['point_name']) &&
                $this->data['ScheduleLine']['point_name'] == ''
        ) {
            /*
             * 如果地點名稱為空白，就將地點參考編號設定為 0
             */
            $this->data['ScheduleLine']['foreign_key'] = 0;
            $this->data['ScheduleLine']['model'] = 'Point';
        }
        if (isset($this->data['ScheduleLine']['minutes_stay'])) {
            if (FALSE !== strpos($this->data['ScheduleLine']['minutes_stay'], ':')) {
                $time = explode(':', $this->data['ScheduleLine']['minutes_stay']);
                $this->data['ScheduleLine']['minutes_stay'] = ($time[0] * 60) + $time[1];
            }
        }
        return true;
    }

    function parseMinutesStay($results) {
        if (isset($results['ScheduleLine']['minutes_stay'])) {
            $results['ScheduleLine']['minutes_stay'] = implode(':', array(
                str_pad(floor($results['ScheduleLine']['minutes_stay'] / 60), 2, '0', STR_PAD_LEFT),
                str_pad(floor($results['ScheduleLine']['minutes_stay'] % 60), 2, '0', STR_PAD_LEFT),
                '00'
                    ));
        } elseif (isset($results[0]['ScheduleLine']['minutes_stay'])) {
            foreach ($results AS $key => $val) {
                $results[$key]['ScheduleLine']['minutes_stay'] = implode(':', array(
                    str_pad(floor($val['ScheduleLine']['minutes_stay'] / 60), 2, '0', STR_PAD_LEFT),
                    str_pad(floor($val['ScheduleLine']['minutes_stay'] % 60), 2, '0', STR_PAD_LEFT),
                    '00'
                        ));
            }
        }
        return $results;
    }

    function afterSave($created) {
        if (!$this->disableSummary) {
            $this->updateScheduleDay();
        }
        parent::afterSave($created);
    }

    function afterDelete() {
        if (!$this->disableSummary) {
            $this->updateScheduleDay();
        }
        parent::afterDelete();
    }

    /*
     * When there's anything change related the day, update the summary and
     * counter fields. Summary field is used to be a keywords spool.
     */

    function updateScheduleDay($scheduleDayId = 0) {
        if(!empty($scheduleDayId)) {
            $this->scheduleDayId = $scheduleDayId;
        } elseif ($this->scheduleDayId == 0) {
            $this->scheduleDayId = $this->field('schedule_day_id');
        }
        $scheduleDay = $this->ScheduleDay->read(array('id', 'point_id', 'title', 'point_name'), $this->scheduleDayId);
        if (!empty($scheduleDay)) {
            $points = array($scheduleDay['ScheduleDay']['point_id']);
            $scheduleLines = $this->find('all', array(
                'conditions' => array(
                    'ScheduleLine.schedule_day_id' => $scheduleDay['ScheduleDay']['id']
                ),
                'fields' => array(
                    'ScheduleLine.foreign_key', 'ScheduleLine.point_name'
                ),
                    ));
            $scheduleDaySummary = array();
            foreach ($scheduleLines AS $scheduleLine) {
                $points[] = $scheduleLine['ScheduleLine']['foreign_key'];
                $scheduleDaySummary[] = $scheduleLine['ScheduleLine']['point_name'];
            }
            if (is_array($points)) {
                $points = array_unique($points);
            }
            $areaIds = $this->Point->find('list', array(
                'conditions' => array('id' => $points),
                'fields' => array('area_id', 'area_id'),
                    ));
            if (is_array($areaIds)) {
                $areaIds = array_unique($areaIds);
            }
            foreach ($areaIds AS $areaId) {
                if ($areaId > 0) {
                    $areas = $this->Point->Area->getPath($areaId, array('name'));
                    if (is_array($areas)) {
                        foreach ($areas AS $area) {
                            if (!isset($scheduleDaySummary[$area['Area']['name']])) {
                                $scheduleDaySummary[$area['Area']['name']] = $area['Area']['name'];
                            }
                        }
                    }
                }
            }
            $scheduleDaySummary[] = $scheduleDay['ScheduleDay']['point_name'];
            $scheduleDaySummary[] = $scheduleDay['ScheduleDay']['title'];
            $scheduleDayData['ScheduleDay']['id'] = $scheduleDay['ScheduleDay']['id'];
            $scheduleDayData['ScheduleDay']['count_lines'] = count($scheduleLines);
            $scheduleDayData['ScheduleDay']['summary'] = implode(' ', $scheduleDaySummary);
            $this->ScheduleDay->save($scheduleDayData);
        }
    }

    function getPoint($conditions) {
        return $this->find('first', array(
                    'fields' => array(
                        'ScheduleLine.point_name', 'ScheduleDay.id', 'ScheduleDay.schedule_id',
                        'ScheduleLine.latitude', 'ScheduleLine.longitude'
                    ),
                    'conditions' => $conditions,
                    'joins' => array(
                        array(
                            'table' => 'schedule_days',
                            'alias' => 'ScheduleDay',
                            'type' => 'INNER',
                            'conditions' => array(
                                'ScheduleDay.id = ScheduleLine.schedule_day_id'
                            ),
                        ),
                        array(
                            'table' => 'schedules',
                            'alias' => 'Schedule',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Schedule.id = ScheduleDay.schedule_id'
                            ),
                        ),
                    ),
                ));
    }

}