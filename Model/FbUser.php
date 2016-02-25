<?php

class FbUser extends AppModel {

    var $name = 'FbUser';
    var $belongsTo = array(
        'Member' => array(
            'foreignKey' => 'member_id',
            'className' => 'Member',
        ),
    );
    var $hasMany = array(
        'FbFriend' => array(
            'foreignKey' => 'fb_user_id',
            'dependent' => true,
            'className' => 'FbFriend',
        ),
    );

}