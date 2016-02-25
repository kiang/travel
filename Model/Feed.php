<?php

App::uses('AppModel', 'Model');

/**
 * FeedItem Model
 *
 * @property Member $Member
 * @property FeedItemLink $FeedItemLink
 */
class Feed extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'title';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public $hasMany = array(
        'FeedItem' => array(
            'className' => 'FeedItem',
            'foreignKey' => 'feed_id',
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