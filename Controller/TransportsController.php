<?php

class TransportsController extends AppController {

    var $name = 'Transports';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index');
    }

    function index() {
        $this->layout = 'ajax';
        $this->set('items', $this->Transport->find('all', array(
                    'order' => array('count DESC', 'id ASC')
                )));
    }

    function admin_index() {
        $this->Paginator->settings['Transport'] = array(
            'limit' => 20,
        );
        $this->set('items', $this->Paginator->paginate($this->Transport));
    }

    function admin_add() {
        if (!empty($this->request->data)) {
            $this->Transport->create();
            if ($this->Transport->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Transport');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Transport.data', $this->request->data);
                $this->Session->write('form.Transport.validationErrors', $this->Transport->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->request->data)) {
            if ($this->Transport->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Transport');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Transport.data', $this->request->data);
                $this->Session->write('form.Transport.validationErrors', $this->Transport->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Transport->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Transport.data')) {
            $this->Transport->validationErrors = $this->Session->read('form.Transport.validationErrors');
            $this->Session->delete('form.Transport');
        }
        if ($id > 0) {
            $this->request->data = $this->Transport->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Transport'][$key])) {
                        $this->request->data['Transport'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Transport->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}