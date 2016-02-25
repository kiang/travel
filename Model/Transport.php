<?php

class Transport extends AppModel {

    var $name = 'Transport';
    var $actsAs = array(
    );
    var $hasMany = array(
        'ScheduleLine' => array(
            'foreignKey' => 'transport_id',
            'dependent' => false,
            'className' => 'ScheduleLine',
        ),
    );

}