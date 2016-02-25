<?php

App::uses('AppModel', 'Model');

/**
 * Tour Model
 *
 * @property Area $Area
 */
class Tour extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'title';
    var $actsAs = array(
        'Geocode.Geocodable',
    );


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Area' => array(
            'className' => 'Area',
            'foreignKey' => 'area_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    var $hasMany = array(
        'Comment' => array(
            'conditions' => array(
                'model' => 'Tour',
            ),
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'className' => 'Comment',
        ),
    );

}
