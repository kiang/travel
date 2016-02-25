<?php

class Activity extends AppModel {

    var $name = 'Activity';
    var $actsAs = array(
    );
    var $hasMany = array(
        'ScheduleLine' => array(
            'foreignKey' => 'activity_id',
            'dependent' => false,
            'className' => 'ScheduleLine',
        ),
    );

}