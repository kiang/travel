<?php

class Comment extends AppModel {

    var $name = 'Comment';
    var $validate = array(
        'body' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '請輸入留言內容',
            ),
        ),
    );
    var $belongsTo = array(
        'Member' => array(
            'foreignKey' => 'member_id',
            'className' => 'Member',
        ),
    );
    var $oldComment = array();

    function beforeDelete($cascade = true) {
        if (empty($this->oldComment)) {
            $this->oldComment = $this->read(array('model', 'foreign_key'));
        }
        return true;
    }

    function afterDelete() {
        if (!empty($this->oldComment)) {
            $foreignModel = $this->oldComment['Comment']['model'];
            $this->bindModel(array('belongsTo' => array(
                    $foreignModel => array(
                        'className' => $foreignModel,
                        'foreignKey' => 'foreign_key',
                    ),
                    )));
            $this->$foreignModel->updateAll(array(
                $foreignModel . '.count_comments' => $foreignModel . '.count_comments - 1'
                    ), array(
                $foreignModel . '.id' => $this->oldComment['Comment']['foreign_key'],
                $foreignModel . '.count_comments > 0',
            ));
        }
        parent::afterDelete();
    }

}