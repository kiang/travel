<?php

class ScheduleTask extends AppModel {

    var $name = 'ScheduleTask';
    var $validate = array(
        'url' => array(
            'urlFormat' => array(
                'rule' => 'url',
                'message' => '網址格式有誤',
                'allowEmpty' => true,
            ),
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '這個欄位必須輸入',
            ),
            'unique' => array(
                'rule' => 'checkUnique',
                'message' => '這個網址已經處理過',
            ),
        ),
    );
    var $belongsTo = array(
        'Schedule' => array(
            'foreignKey' => 'schedule_id',
            'className' => 'Schedule',
        ),
        'Creator' => array(
            'foreignKey' => 'creator',
            'className' => 'Member',
        ),
        'Dealer' => array(
            'foreignKey' => 'dealer',
            'className' => 'Member',
        ),
    );

    function checkUnique($data) {
        foreach ($data AS $key => $value) {
            if (empty($value)) {
                return false;
            }
            if ($this->id) {
                return!$this->hasAny(array(
                    'id !=' => $this->id, $key . ' LIKE' => $value . '%',
                ));
            } else {
                return!$this->hasAny(array($key . ' LIKE' => $value . '%'));
            }
        }
    }

    function beforeSave() {
        if (!empty($this->data['ScheduleTask']['title'])) {
            $this->data['ScheduleTask']['title'] = trim($this->data['ScheduleTask']['title']);
        }
        return true;
    }

}