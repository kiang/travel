<?php

class Member extends AppModel {

    var $name = 'Member';
    var $displayField = 'username';
    var $nickNameChanged = false;
    var $skipPasswordCheck = false;
    var $belongsTo = array(
        'Group' => array(
            'foreignKey' => 'group_id',
            'className' => 'Group',
        ),
        'Area' => array(
            'foreignKey' => 'area_id',
            'className' => 'Area',
        ),
    );
    var $hasMany = array(
        'Oauth' => array(
            'foreignKey' => 'member_id',
            'dependent' => false,
            'className' => 'Oauth',
        ),
    );
    var $actsAs = array(
        'Acl' => array('requester'),
        'Media.Transfer' => array(
            'trustClient' => false,
            'transferDirectory' => MEDIA_TRANSFER,
            'createDirectory' => true,
            'alternativeFile' => 100
        ),
        'Media.Generator' => array(
            'baseDirectory' => MEDIA_TRANSFER,
            'filterDirectory' => MEDIA_FILTER,
            'createDirectory' => true,
        ),
        'Media.Coupler' => array(
            'baseDirectory' => MEDIA_TRANSFER
        ),
        'Media.Meta' => array(
            'level' => 2
        )
    );
    var $validate = array(
        'username' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => '帳號必須輸入',
            ),
            'unique' => array(
                'rule' => 'checkUnique',
                'message' => '這個帳號已經有人使用',
                'allowEmpty' => true,
            ),
        ),
        'email' => array(
            'mailFormat' => array(
                'rule' => 'email',
                'message' => '請輸入正確的信箱',
                'allowEmpty' => false,
            ),
            'unique' => array(
                'rule' => 'checkUnique',
                'message' => '這個信箱已經有人使用',
                'allowEmpty' => true,
            ),
        ),
        'file' => array(
            'extension' => array(
                'rule' => array(
                    'checkExtension',
                    array(),
                    array('jpg', 'gif', 'png')
                ),
                'message' => '只接受 jpg, gif, png 等圖片格式',
                'allowEmpty' => true,
            ),
            'size' => array(
                'rule' => array('checkSize', '100K'),
                'message' => '檔案大小不能超過 100 KB',
                'allowEmpty' => true,
            ),
        ),
    );

    function parentNode() {
        if (!$this->id && empty($this->data)) {
            return null;
        }
        $data = $this->data;
        if (empty($this->data)) {
            $data = $this->read();
        }
        if (empty($data['Member']['group_id'])) {
            return null;
        } else {
            return array('Group' => array('id' => $data['Member']['group_id']));
        }
    }

    function beforeValidate($options = array()) {
        if (!empty($this->data['Member']['area_id'])) {
            /*
             * 檢查 area_id 是否存在
             */
            if ($this->Area->find('count', array(
                        'conditions' => array(
                            'Area.id' => $this->data['Member']['area_id'],
                        ),
                    )) != 1) {
                $this->data['Member']['area_id'] = 0;
            } else {
                $this->newAreaId = $this->data['Member']['area_id'];
            }
            if (!empty($this->id)) {
                $this->oldAreaId = $this->field('area_id');
            }
        } elseif (isset($this->data['Member']['area_id'])) {
            $this->data['Member']['area_id'] = 0;
        }
        if (!empty($this->data['Member']['password_new'])) {
            if (empty($this->data['Member']['password'])) {
                $this->validationErrors['password'] = '修改密碼需要輸入原始密碼';
            } else {
                $password = Security::hash(Configure::read('Security.salt') . $this->data['Member']['password']);
                if ($this->field('id', array(
                            'id' => $this->id,
                            'password' => $password,
                        )) != $this->id) {
                    $this->validationErrors['password'] = '輸入的密碼錯誤';
                }
            }
            if ($this->data['Member']['password_new'] !== $this->data['Member']['password_re']) {
                $this->validationErrors['password_re'] = '確認密碼需要與修改密碼一致';
            } else {
                $this->data['Member']['password'] = $this->data['Member']['password_new'];
            }
        } elseif (isset($this->data['Member']['password']) && !$this->skipPasswordCheck) {
            unset($this->data['Member']['password']);
        }
        if (false === $this->id) {
            if (!empty($this->data['Member']['bypass']) && $this->data['Member']['bypass'] === md5($this->data['Member']['email'] . 'e')) {
                $this->data['Member']['password'] = $this->data['Member']['bypass'];
            } else {
                if (empty($this->data['Member']['password'])) {
                    $this->validationErrors['password'] = '請輸入密碼';
                }
                if ($this->data['Member']['password'] !== $this->data['Member']['password_re']) {
                    $this->validationErrors['password_re'] = '確認密碼需要與密碼一致';
                }
            }
        }
        if (!empty($this->validationErrors)) {
            return false;
        }
        return true;
    }

    function beforeSave($options = array()) {
        if ($this->id && isset($this->data['Member']['nickname'])) {
            if ($this->data['Member']['nickname'] != $this->field('nickname')) {
                $this->nickNameChanged = true;
            }
        }
        if (isset($this->data['Member']['password'])) {
            $this->data['Member']['password'] = trim($this->data['Member']['password']);
            if (!empty($this->data['Member']['password'])) {
                $this->data['Member']['password'] = Security::hash(Configure::read('Security.salt') . $this->data['Member']['password']);
            } else {
                unset($this->data['Member']['password']);
            }
        }
        return true;
    }

    function beforeDelete($cascade = true) {
        $this->oldAreaId = $this->field('area_id');
        return true;
    }

    function afterSave($created, $options = array()) {
        if (!$created && $this->nickNameChanged) {
            $currentMember = $this->read(array('username', 'nickname'));
            if (empty($currentMember['Member']['nickname'])) {
                $newName = $currentMember['Member']['username'];
            } else {
                $newName = $currentMember['Member']['nickname'];
            }
            $sql = 'UPDATE %s SET member_name = \'' . $newName . '\' WHERE member_id = ' . $this->id;
            foreach (array('links', 'comments', 'schedules') AS $table) {
                $this->query(sprintf($sql, $table));
            }
        }
        if (!empty($this->newAreaId)) {
            $areas = $this->Area->getPath($this->newAreaId, array('id'));
            $this->Area->updateAll(array('countMember' => 'countMember + 1'), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
            ));
            $this->newAreaId = 0;
        }
        if (!empty($this->oldAreaId)) {
            $areas = $this->Area->getPath($this->oldAreaId, array('id'));
            $this->Area->updateAll(array('countMember' => 'countMember - 1'), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
            ));
            $this->oldAreaId = 0;
        }
        $this->nickNameChanged = false;
        parent::afterSave($created);
    }

    function afterDelete() {
        if (!empty($this->oldAreaId)) {
            $areas = $this->Area->getPath($this->oldAreaId, array('id'));
            $this->Area->updateAll(array('countMember' => 'countMember - 1'), array(
                'Area.id IN (' . implode(',', Set::extract('{n}.Area.id', $areas)) . ')',
            ));
            $this->oldAreaId = 0;
        }
        $this->query('DELETE Comment, Link FROM links AS Link
	    LEFT JOIN comments AS Comment ON (Comment.model = \'Member\' AND Comment.foreign_key = Link.foreign_key)
	    WHERE Link.model = \'Member\' AND Link.foreign_key = ' . $this->id);
        parent::afterDelete();
    }

    function checkUnique($data) {
        foreach ($data AS $key => $value) {
            if (empty($value)) {
                return false;
            }
            if ($this->id) {
                return !$this->hasAny(array(
                            'id !=' => $this->id, $key => $value,
                        ));
            } else {
                return !$this->hasAny(array($key => $value));
            }
        }
    }

}