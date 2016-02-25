<?php

class FbFriend extends AppModel {

    var $name = 'FbFriend';
    var $belongsTo = array(
        'FbUser' => array(
            'foreignKey' => 'fb_user_id',
            'className' => 'FbUser',
        ),
    );

}