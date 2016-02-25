<?php

class ScheduleTasksController extends AppController {

    var $name = 'ScheduleTasks';

    /*
     * INSERT INTO schedule_tasks (
      schedule_id, url, title, creator, dealer, created, dealt
      ) SELECT
      foreign_key, url, title, 7, 7, created, created
      FROM links
      WHERE model = 'Schedule' AND member_id = '7'
     */

    function index() {
        $this->Paginator->settings['ScheduleTask'] = array(
            'limit' => 20,
            'order' => array('created' => 'desc'),
            'contain' => array('Creator'),
        );
        $this->set('items', $this->Paginator->paginate($this->ScheduleTask));
    }

    function add() {
        if (!empty($this->request->data)) {
            $this->ScheduleTask->create();
            $this->request->data['ScheduleTask']['creator'] = $this->loginMember['id'];
            if ($this->ScheduleTask->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.ScheduleTask');
                unset($this->request->data);
            } else {
                $this->Session->write('form.ScheduleTask.data', $this->request->data);
                $this->Session->write('form.ScheduleTask.validationErrors', $this->ScheduleTask->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
    }

    function edit($id = 0) {
        $id = intval($id);
        if ($id <= 0 || !$this->ScheduleTask->hasAny(array(
                    'ScheduleTask.id' => $id,
                    'ScheduleTask.creator' => $this->loginMember['id'],
                    'ScheduleTask.dealt IS NULL',
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        }
        if (!empty($this->request->data)) {
            if ($this->ScheduleTask->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.ScheduleTask');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.ScheduleTask.data', $this->request->data);
                $this->Session->write('form.ScheduleTask.validationErrors', $this->ScheduleTask->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->ScheduleTask->read(null, $id);
    }

    function form($id = 0) {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.ScheduleTask.data')) {
            $this->ScheduleTask->validationErrors = $this->Session->read('form.ScheduleTask.validationErrors');
            $this->Session->delete('form.ScheduleTask');
        }
        if ($id > 0 && $this->request->data = $this->ScheduleTask->find('first', array(
            'conditions' => array(
                'ScheduleTask.id' => $id,
                'ScheduleTask.creator' => $this->loginMember['id'],
                'ScheduleTask.dealt IS NULL',
            ),
                ))) {
            if (!empty($sessionFormData['ScheduleTask'])) {
                foreach ($sessionFormData['ScheduleTask'] AS $key => $val) {
                    if (isset($this->request->data['ScheduleTask'][$key])) {
                        $this->request->data['ScheduleTask'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }
        $this->set('id', $id);
    }

    function delete($id = null) {
        $id = intval($id);
        if ($id <= 0 || !$this->ScheduleTask->hasAny(array(
                    'ScheduleTask.id' => $id,
                    'ScheduleTask.creator' => $this->loginMember['id'],
                    'ScheduleTask.dealt IS NULL',
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } elseif ($this->ScheduleTask->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

    function admin_index() {
        $this->Paginator->settings['ScheduleTask']['limit'] = 20;
        $items = $this->Paginator->paginate($this->ScheduleTask);
        $this->set('items', $items);
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->request->data)) {
            if ($this->ScheduleTask->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.ScheduleTask');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.ScheduleTask.data', $this->request->data);
                $this->Session->write('form.ScheduleTask.validationErrors', $this->ScheduleTask->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->ScheduleTask->read(null, $id);
    }

    function admin_form($id = 0) {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.ScheduleTask.data')) {
            $this->ScheduleTask->validationErrors = $this->Session->read('form.ScheduleTask.validationErrors');
            $this->Session->delete('form.ScheduleTask');
        }
        if ($id > 0) {
            $this->request->data = $this->ScheduleTask->read(null, $id);
            if (!empty($sessionFormData['ScheduleTask'])) {
                foreach ($sessionFormData['ScheduleTask'] AS $key => $val) {
                    if (isset($this->request->data['ScheduleTask'][$key])) {
                        $this->request->data['ScheduleTask'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->ScheduleTask->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}