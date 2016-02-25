<?php

class SubmitsController extends AppController {

    var $name = 'Submits';
    var $foreignControllers = array(
        'Point' => 'points',
    );

    function admin_index($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $scope = array();
        if (array_key_exists($foreignModel, $this->foreignControllers) && $foreignId > 0) {
            $scope = array(
                'Submit.model' => $foreignModel,
                'Submit.foreign_key' => $foreignId,
            );
        } else {
            $foreignModel = 'a';
        }
        $this->set('scope', $scope);
        $this->Paginator->settings['Submit'] = array(
            'limit' => 20,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('id', 'username', 'nickname')
                )
            )
        );
        $items = $this->Paginator->paginate($this->Submit, $scope);
        foreach ($items AS $key => $item) {
            $items[$key]['Submit']['foreign_title'] = $this->getForeignTitle($item['Submit']['model'], $item['Submit']['foreign_key']);
        }
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
        $this->set('url', array($foreignModel, $foreignId));
        $this->set('foreignControllers', $this->foreignControllers);
    }

    function admin_view($id) {
        if ($submitItem = $this->Submit->find('first', array(
            'conditions' => array(
                'Submit.id' => $id,
            ),
                ))) {
            if ($submitItem['Submit']['is_new'] != 1) {
                $targetModel = $submitItem['Submit']['model'];
                if (!isset($this->$targetModel)) {
                    $this->loadModel($targetModel);
                }
                $this->set('oldData', $this->$targetModel->read(null, $submitItem['Submit']['foreign_key']));
            }
            $this->set('data', unserialize($submitItem['Submit']['data']));
        }
    }

    function admin_edit($id) {
        $id = intval($id);
        if (!empty($this->request->data)) {
            $this->Submit->id = $id;
            $this->Submit->saveField('data', serialize($this->request->data));
            $this->Session->setFlash('資料已經更新');
            $this->redirect(array('action' => 'index'));
        } elseif ($submitItem = $this->Submit->find('first', array(
            'conditions' => array(
                'Submit.id' => $id,
                'Submit.accepted IS NULL'
            ),
                ))) {
            $this->set('id', $id);
            $this->set('data', unserialize($submitItem['Submit']['data']));
        } else {
            $this->Session->setFlash('選擇的項目不存在，或是已經接受');
        }
    }

    function admin_accept($id) {
        if ($submitItem = $this->Submit->find('first', array(
            'conditions' => array(
                'Submit.id' => $id,
                'Submit.accepted IS NULL'
            ),
                ))) {
            $targetModel = $submitItem['Submit']['model'];
            if (!isset($this->$targetModel)) {
                $this->loadModel($targetModel);
            }
            $data = unserialize($submitItem['Submit']['data']);
            if (!empty($submitItem['Submit']['is_new'])) {
                $this->$targetModel->create();
            } else {
                $this->$targetModel->id = $submitItem['Submit']['foreign_key'];
            }
            if ($this->$targetModel->save($data)) {
                $saveData['Submit']['id'] = $id;
                $saveData['Submit']['accepted'] = date('Y-m-d H:i:s');
                if ($submitItem['Submit']['is_new'] == 1) {
                    $saveData['Submit']['foreign_key'] = $this->$targetModel->getInsertID();
                }
                $this->Submit->save($saveData);
                $this->Session->setFlash('操作完成');
            } else {
                $this->Session->setFlash('操作時發生錯誤');
            }
        } else {
            $this->Session->setFlash('選擇的項目不存在，或是重複操作');
        }
        $this->redirect(array('action' => 'index'));
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Submit->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}