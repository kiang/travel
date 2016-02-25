<?php

class ScheduleDay extends AppModel {

    var $name = 'ScheduleDay';
    var $summaryUpdated = false;
    var $disableSummary = false;
    var $belongsTo = array(
        'Schedule' => array(
            'foreignKey' => 'schedule_id',
            'className' => 'Schedule',
        ),
        'Point' => array(
            'foreignKey' => 'point_id',
            'className' => 'Point',
        ),
    );
    var $hasMany = array(
        'ScheduleLine' => array(
            'foreignKey' => 'schedule_day_id',
            'dependent' => false,
            'className' => 'ScheduleLine',
        ),
        'ScheduleNote' => array(
            'foreignKey' => 'schedule_day_id',
            'dependent' => false,
            'className' => 'ScheduleNote',
        ),
    );
    var $scheduleId;

    function beforeSave($options = array()) {
        parent::beforeSave();
        $intFields = array('transport_id', 'point_id', 'sort', 'count_lines');
        foreach ($intFields AS $field) {
            if (isset($this->data['ScheduleDay'][$field])) {
                $this->data['ScheduleDay'][$field] = intval($this->data['ScheduleDay'][$field]);
            }
        }
        if (empty($this->data['ScheduleDay']['id'])) {
            $this->data['ScheduleDay']['sort'] = $this->field('ScheduleDay.sort', array(
                        'ScheduleDay.schedule_id' => $this->data['ScheduleDay']['schedule_id'],
                            ), array(
                        'ScheduleDay.sort DESC'
                    )) + 1;
        } elseif (isset($this->data['ScheduleDay']['point_name']) &&
                $this->data['ScheduleDay']['point_name'] == ''
        ) {
            /*
             * 如果旅館名稱為空白，就將旅館參考編號設定為 0
             */
            $this->data['ScheduleDay']['point_id'] = 0;
        }
        return true;
    }

    function afterSave($created) {
        $scheduleId = isset($this->data['ScheduleDay']['schedule_id']) ?
                $this->data['ScheduleDay']['schedule_id'] : $this->field('schedule_id');
        if ($created) {
            $this->Schedule->updateAll(
                    array('Schedule.count_days' => 'Schedule.count_days + 1'), array('Schedule.id' => $scheduleId)
            );
        }

        /*
         * 可能旅館有異動，所以需要更新 summary 資料
         * 隨機取得一個 ScheduleLine.id
         */
        if (!$this->disableSummary && !$created && !$this->summaryUpdated) {
            $this->summaryUpdated = true;
            $this->ScheduleLine->scheduleDayId = $this->id;
            $this->ScheduleLine->updateScheduleDay();

            /*
             * 更新行程的異動時間
             */
            if (!empty($scheduleId)) {
                $this->Schedule->updateAll(array('modified' => 'now()'), array(
                    'Schedule.id' => $scheduleId,
                ));
                $this->Schedule->updateCountPoints($scheduleId);
            }
        }
        $this->Schedule->updateCountPoints($scheduleId);
        parent::afterSave($created);
    }
    
    function beforeDelete($cascade = true) {
        parent::beforeDelete($cascade);
        $this->scheduleId = $this->field('schedule_id');
        return true;
    }

    function afterDelete() {
        $this->ScheduleLine->deleteAll(array('ScheduleLine.schedule_day_id' => $this->id));
        if($this->scheduleId > 0) {
            $this->Schedule->updateCountPoints($this->scheduleId);
            $this->scheduleId = 0;
        }
        parent::afterDelete();
    }

}