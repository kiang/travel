<?php

class AreasModel extends AppModel {

    var $name = 'AreasModel';
    var $areaData = array();
    var $belongsTo = array(
        'Area' => array(
            'foreignKey' => 'area_id',
            'className' => 'Area',
        ),
    );

    function afterSave($created) {
        if ($created) {
            $areas = $this->Area->getPath($this->data['AreasModel']['area_id'], array('id'));
            $this->Area->updateAll(array(
                'Area.count' . $this->data['AreasModel']['model'] =>
                'Area.count' . $this->data['AreasModel']['model'] . ' + 1'
                    ), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
            ));
        }
        parent::afterSave($created);
    }

    function beforeDelete($cascade = true) {
        $this->areaData = $this->find('first', array('fields' => array('model', 'area_id')));
        return true;
    }

    function afterDelete() {
        if (!empty($this->areaData)) {
            $areas = $this->Area->getPath($this->areaData['AreasModel']['area_id'], array('id'));
            $this->Area->updateAll(array(
                'Area.count' . $this->areaData['AreasModel']['model'] =>
                'Area.count' . $this->areaData['AreasModel']['model'] . ' - 1'
                    ), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
                'Area.count' . $this->areaData['AreasModel']['model'] . ' > 0'
            ));
            $this->areaData = array();
        }
        parent::afterDelete();
    }

}