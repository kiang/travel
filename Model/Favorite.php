<?php

class Favorite extends AppModel {

    var $name = 'Favorite';
    var $belongsTo = array(
        'Member' => array(
            'foreignKey' => 'member_id',
            'className' => 'Member',
        ),
    );

}