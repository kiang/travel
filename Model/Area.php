<?php

class Area extends AppModel {

    var $name = 'Area';
    var $actsAs = array('Tree');
    var $hasMany = array(
        'AreasModel' => array(
            'foreignKey' => 'area_id',
            'dependent' => true,
            'className' => 'AreasModel',
        ),
        'Point' => array(
            'foreignKey' => 'area_id',
            'dependent' => true,
            'className' => 'Point',
        ),
    );

    function getParents($areaId) {
        $parents = $this->getPath($areaId, array('id', 'name'));
        $parents = array_merge(array(0 => array(
                'Area' => array(
                    'id' => 0,
                    'name' => '全部',
            ))), $parents);
        return $parents;
    }

}