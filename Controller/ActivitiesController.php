<?php

class ActivitiesController extends AppController {

    var $name = 'Activities';
    var $helpers = array();

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index');
    }

    function index() {
        $this->layout = 'ajax';
        $key = '/activities/index';
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Activity->find('all', array(
                'order' => array('count DESC', 'id ASC')
                    ));
            Cache::write($key, $items);
        }
        $this->set('items', $items);
    }

    function admin_index() {
        $this->Paginator->settings['Activity'] = array(
            'limit' => 20,
        );
        $this->set('items', $this->Paginator->paginate($this->Activity));
    }

    function admin_add() {
        if (!empty($this->request->data)) {
            $this->Activity->create();
            if ($this->Activity->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Activity');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Activity.data', $this->request->data);
                $this->Session->write('form.Activity.validationErrors', $this->Activity->validationErrors);
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
            if ($this->Activity->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Activity');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Activity.data', $this->request->data);
                $this->Session->write('form.Activity.validationErrors', $this->Activity->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Activity->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Activity.data')) {
            $this->Activity->validationErrors = $this->Session->read('form.Activity.validationErrors');
            $this->Session->delete('form.Activity');
        }
        if ($id > 0) {
            $this->request->data = $this->Activity->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Activity'][$key])) {
                        $this->request->data['Activity'][$key] = $val;
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
        } else if ($this->Activity->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}