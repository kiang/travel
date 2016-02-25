<?php

App::uses('AppModel', 'Model');

/**
 * ScheduleNote Model
 *
 * @property Schedule $Schedule
 * @property ScheduleDay $ScheduleDay
 * @property ScheduleLine $ScheduleLine
 */
class ScheduleNote extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'title' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => '請填寫這個欄位',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'body' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => '請填寫這個欄位',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Schedule' => array(
            'className' => 'Schedule',
            'foreignKey' => 'schedule_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'ScheduleDay' => array(
            'className' => 'ScheduleDay',
            'foreignKey' => 'schedule_day_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'ScheduleLine' => array(
            'className' => 'ScheduleLine',
            'foreignKey' => 'schedule_line_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
