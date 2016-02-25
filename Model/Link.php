<?php

class Link extends AppModel {

    var $name = 'Link';
    var $validate = array(
        'url' => array(
            'urlFormat' => array(
                'rule' => 'url',
                'message' => '網址格式有誤',
                'allowEmpty' => false,
            ),
        ),
    );
    var $belongsTo = array(
        'Member' => array(
            'foreignKey' => 'member_id',
            'className' => 'Member',
        ),
    );
    var $oldLink = array();

    function beforeDelete($cascade = true) {
        if (empty($this->oldLink)) {
            $this->oldLink = $this->read(array('model', 'foreign_key'));
        }
        return true;
    }

    function afterDelete() {
        if (!empty($this->oldLink)) {
            $foreignModel = $this->oldLink['Link']['model'];
            $this->bindModel(array('belongsTo' => array(
                    $foreignModel => array(
                        'className' => $foreignModel,
                        'foreignKey' => 'foreign_key',
                    ),
                    )));
            $this->$foreignModel->updateAll(array(
                $foreignModel . '.count_links' => $foreignModel . '.count_links - 1'
                    ), array(
                $foreignModel . '.id' => $this->oldLink['Link']['foreign_key'],
                $foreignModel . '.count_links > 0',
            ));
        }
        parent::afterDelete();
    }

}