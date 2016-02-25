<?php

App::uses('AppModel', 'Model');

/**
 * FeedItem Model
 *
 * @property Member $Member
 * @property FeedItemLink $FeedItemLink
 */
class FeedItem extends AppModel {

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
        'Feed' => array(
            'className' => 'Feed',
            'foreignKey' => 'feed_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Channel' => array(
            'className' => 'Channel',
            'foreignKey' => 'channel_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}