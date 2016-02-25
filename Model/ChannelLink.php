<?php

App::uses('AppModel', 'Model');

/**
 * ChannelLink Model
 *
 * @property Channel $Channel
 */
class ChannelLink extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'foreign_title';


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Channel' => array(
            'className' => 'Channel',
            'foreignKey' => 'channel_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

}
