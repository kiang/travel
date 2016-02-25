<?php

class PointType extends AppModel {

    var $name = 'PointType';
    var $displayField = 'name';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $hasMany = array(
        'PointTypeLink' => array(
            'className' => 'PointTypeLink',
            'foreignKey' => 'point_type_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    var $hasAndBelongsToMany = array(
        'Point' => array(
            'className' => 'Point',
            'joinTable' => 'point_type_links',
            'foreignKey' => 'point_type_id',
            'associationForeignKey' => 'point_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );

}