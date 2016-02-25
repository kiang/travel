<?php

class MembersProgram extends AppModel {

    var $name = 'MembersProgram';
    var $belongsTo = array(
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id',
        ),
        'Program' => array(
            'className' => 'Program',
            'foreignKey' => 'program_id',
        )
    );

}