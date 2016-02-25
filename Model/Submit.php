<?php

class Submit extends AppModel {

    var $name = 'Submit';
    var $belongsTo = array(
        'Member' => array(
            'foreignKey' => 'member_id',
            'className' => 'Member',
        ),
    );

}