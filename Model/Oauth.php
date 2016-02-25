<?php

App::uses('AppModel', 'Model');

/**
 * Opauth Model
 *
 * @property Member $Member
 */
class Oauth extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'uid';


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'member_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
