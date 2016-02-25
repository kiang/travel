<?php

App::uses('Sanitize', 'Utility');

class Point extends AppModel {

    var $name = 'Point';
    var $actsAs = array(
        'Geocode.Geocodable',
    );
    var $oldAreaId = 0;
    var $newAreaId = 0;
    var $validate = array(
        'area_id' => array(
            'notEmpty' => array(
                'rule' => array('comparison', '>', 0),
                'message' => '請選擇一個區域',
                'allowEmpty' => false,
            ),
        ),
        'postcode' => array(
            'alphaNumericFormat' => array(
                'rule' => 'alphaNumeric',
                'message' => '格式有誤',
                'allowEmpty' => true,
            ),
        ),
        'website' => array(
            'urlFormat' => array(
                'rule' => 'url',
                'message' => '網址格式有誤',
                'allowEmpty' => true,
            ),
        ),
        'latitude' => array(
            'numberFormat' => array(
                'rule' => 'numeric',
                'message' => '數字格式有誤',
                'allowEmpty' => true,
            ),
        ),
        'longitude' => array(
            'numberFormat' => array(
                'rule' => 'numeric',
                'message' => '數字格式有誤',
                'allowEmpty' => true,
            ),
        ),
    );
    var $belongsTo = array(
        'Area' => array(
            'foreignKey' => 'area_id',
            'className' => 'Area',
        ),
    );
    var $hasMany = array(
        'Schedule' => array(
            'foreignKey' => 'point_id',
            'dependent' => false,
            'className' => 'Schedule',
        ),
        'PointTypeLink' => array(
            'foreignKey' => 'point_id',
            'dependent' => true,
            'className' => 'PointTypeLink',
        ),
        'Comment' => array(
            'conditions' => array(
                'model' => 'Point',
            ),
            'foreignKey' => 'foreign_key',
            'dependent' => true,
            'className' => 'Comment',
        ),
    );
    var $hasAndBelongsToMany = array(
        'PointType' => array(
            'className' => 'PointType',
            'joinTable' => 'point_type_links',
            'foreignKey' => 'point_id',
            'associationForeignKey' => 'point_type_id',
        ),
    );

    function beforeValidate($options = array()) {
        $this->data = Sanitize::clean($this->data, array(
                    'encode' => false,
                    'escape' => false,
                ));
        if (!empty($this->data['Point']['area_id'])) {
            /*
             * 檢查 area_id 是否存在
             */
            if ($this->Area->find('count', array(
                        'conditions' => array(
                            'Area.id' => $this->data['Point']['area_id'],
                        ),
                    )) != 1) {
                $this->data['Point']['area_id'] = 0;
            } else {
                $this->newAreaId = $this->data['Point']['area_id'];
            }
            if (!empty($this->id)) {
                $this->oldAreaId = $this->field('area_id');
            }
        }
        /*
         * 檢查 3 個名稱至少要輸入一個
         */
        if (isset($this->data['Point']['title_zh_tw']) && isset($this->data['Point']['title_en_us']) &&
                isset($this->data['Point']['title']) && ('' === implode('', array(
                    $this->data['Point']['title_zh_tw'],
                    $this->data['Point']['title_en_us'],
                    $this->data['Point']['title']
                )))
        ) {
            $errorMessage = '至少輸入一個';
            $this->validationErrors = array(
                'title_zh_tw' => $errorMessage,
                'title_en_us' => $errorMessage,
                'title' => $errorMessage,
            );
        }

        if (empty($this->data['Point']['PointType'])) {
            $this->validationErrors['PointType'] = '請至少選擇一種類型';
        } else {
            $this->data['PointType']['PointType'] = $this->data['Point']['PointType'];
        }

        if (!empty($this->validationErrors)) {
            return false;
        } else {
            return true;
        }
    }

    function afterSave($created) {
        if (!empty($this->newAreaId)) {
            $areas = $this->Area->getPath($this->newAreaId, array('id'));
            $this->Area->updateAll(array('countPoint' => 'countPoint + 1'), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
            ));
            $this->newAreaId = 0;
        }
        if (!empty($this->oldAreaId)) {
            $areas = $this->Area->getPath($this->oldAreaId, array('id'));
            $this->Area->updateAll(array('countPoint' => 'countPoint - 1'), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
            ));
            $this->oldAreaId = 0;
        }
        parent::afterSave($created);
    }

    function beforeDelete($cascade = true) {
        $this->oldAreaId = $this->field('area_id');
        return true;
    }

    function afterDelete() {
        if (!empty($this->oldAreaId)) {
            $areas = $this->Area->getPath($this->oldAreaId, array('id'));
            $this->Area->updateAll(array('countPoint' => 'countPoint - 1'), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
            ));
            $this->oldAreaId = 0;
        }
        /*
         * 刪除相關評論、連結與評分
         */
        $this->query('DELETE Comment, Link FROM links AS Link
	    LEFT JOIN comments AS Comment ON (Comment.model = \'Point\' AND Comment.foreign_key = Link.foreign_key)
	    WHERE Link.model = \'Point\' AND Link.foreign_key = ' . $this->id);
        /*
         * 更新相關行程
         */
        $this->Schedule->updateAll(array('Schedule.point_id' => 0), array('Schedule.point_id' => $this->id,));
        $this->Schedule->ScheduleDay->ScheduleLine->updateAll(
                array('ScheduleLine.foreign_key' => 0), array(
            'ScheduleLine.model = \'Point\'',
            'ScheduleLine.foreign_key' => $this->id,
        ));
        parent::afterDelete();
    }

    public function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
        $findMethods = array_merge($this->findMethods, array('near' => true));
        $findType = (is_string($conditions) && $conditions != 'count' && array_key_exists($conditions, $findMethods) ? $conditions : null);
        if (empty($findType) && is_string($conditions) && $conditions == 'count' && !empty($fields['type']) && array_key_exists($fields['type'], $findMethods)) {
            $findType = $fields['type'];
            unset($fields['type']);
        }

        if ($findType == 'near' && $this->Behaviors->enabled('Geocodable')) {
            $type = ($conditions == 'near' ? 'all' : $conditions);
            $query = $fields;
            if (!empty($query['address'])) {
                foreach (array('address', 'unit', 'distance') as $field) {
                    $$field = isset($query[$field]) ? $query[$field] : null;
                    unset($query[$field]);
                }
                return $this->near($type, $address, $distance, $unit, $query);
            }
        }
        return parent::find($conditions, $fields, $order, $recursive);
    }

}