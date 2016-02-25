<?php

App::uses('AppModel', 'Model');

/**
 * Channel Model
 *
 * @property Member $Member
 * @property ChannelLink $ChannelLink
 */
class Channel extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'title';


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

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'ChannelLink' => array(
            'className' => 'ChannelLink',
            'foreignKey' => 'channel_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );
    public $hasOne = array(
        'FeedItem' => array(
            'className' => 'FeedItem',
            'foreignKey' => 'channel_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );

}