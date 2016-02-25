<?php

class LinksController extends AppController {

    var $name = 'Links';
    var $foreignControllers = array(
        'Point' => 'points',
        'Schedule' => 'schedules',
        'Tour' => 'tours',
    );

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index', 'view', 'schedule', 'point',
            'tour', 'member');
    }

    function index($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        $scope = array('Link.is_active' => 1);
        if (array_key_exists($foreignModel, $this->foreignControllers) && $foreignId > 0) {

            $this->loadModel($foreignModel);
            if (!$this->$foreignModel->hasAny(array(
                        $foreignModel . '.id' => $foreignId,
                    ))) {
                $foreignModel = '';
            } else {
                $scope['Link.model'] = $foreignModel;
                $scope['Link.foreign_key'] = $foreignId;
            }
        } else {
            $foreignModel = '';
        }
        if (empty($foreignModel) || $foreignId <= 0) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        } else {
            $this->Paginator->settings['Link'] = array(
                'limit' => 20,
                'order' => array('created' => 'desc'),
            );
            $this->set('items', $this->Paginator->paginate($this->Link, $scope));
            $this->set('url', array($foreignModel, $foreignId));
        }
    }

    function add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        if (array_key_exists($foreignModel, $this->foreignControllers) && $foreignId > 0) {
            if (!empty($this->request->data)) {
                $this->request->data['Link']['model'] = $foreignModel;
                $this->request->data['Link']['foreign_key'] = $foreignId;
            }
            $this->loadModel($foreignModel);
            if (!$this->$foreignModel->hasAny(array(
                        $foreignModel . '.id' => $foreignId,
                    ))) {
                $foreignModel = '';
            }
        } else {
            $foreignModel = '';
        }
        if (empty($foreignModel) || $foreignId <= 0 || $this->loginMember['id'] <= 0) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
        }
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Link']['member_id'])) {
                $this->Session->write('block', 1);
                exit();
            }
            if (empty($this->request->data['Link']['title']) || empty($this->request->data['Link']['url'])) {
                $this->set('linkControlMessage', '請填入正確資料');
            } elseif ($this->Link->find('count', array(
                        'conditions' => array(
                            'model' => $foreignModel,
                            'foreign_key' => $foreignId,
                            'url' => $this->request->data['Link']['url'],
                        ),
                    )) > 0) {
                $this->set('linkControlMessage', '這個網址已經存在');
            } else {
                $this->Link->create();
                $this->request->data['Link']['member_id'] = $this->loginMember['id'];
                if (!empty($this->loginMember['nickname'])) {
                    $this->request->data['Link']['member_name'] = $this->loginMember['nickname'];
                } else {
                    $this->request->data['Link']['member_name'] = $this->loginMember['username'];
                }
                $this->request->data['Link']['title'] = trim($this->request->data['Link']['title']);
                if (empty($this->request->data['Link']['title'])) {
                    $this->request->data['Link']['title'] = $this->request->data['Link']['url'];
                }
                $this->request->data['Link']['is_active'] = 1;
                $this->request->data['Link']['ip'] = $_SERVER['REMOTE_ADDR'];
                if ($this->Link->save($this->request->data)) {
                    $this->Session->delete('form.Link');
                    $this->$foreignModel->updateAll(
                            array($foreignModel . '.count_links' => $foreignModel . '.count_links + 1'), array($foreignModel . '.id' => $foreignId)
                    );
                    $this->set('linkControlMessage', 'done');
                } else {
                    $errorMessages = array();
                    foreach ($this->Link->validationErrors AS $field => $messages) {
                        $errorMessages = array_merge($errorMessages, $messages);
                    }
                    $this->set('linkControlMessage', implode('<br />', $errorMessages));
                }
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function form($id = 0) {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Link.data')) {
            $this->Link->validationErrors = $this->Session->read('form.Link.validationErrors');
            $this->Session->delete('form.Link');
        }
        if ($id > 0) {
            $this->request->data = $this->Link->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Link'][$key])) {
                        $this->request->data['Link'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
    }

    function delete($id = 0, $from = 'model') {
        $id = intval($id);
        if ($id <= 0 || !$this->Link->oldLink = $this->Link->find('first', array(
            'conditions' => array(
                'Link.id' => $id,
                'Link.member_id' => $this->loginMember['id'],
            ),
            'fields' => array('model', 'foreign_key'),
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } elseif ($this->Link->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        } else {
            $this->Session->setFlash('資料刪除失敗');
        }
        switch ($from) {
            case 'model':
                switch ($this->Link->oldLink['Link']['model']) {
                    case 'Schedule':
                        $this->redirect('/schedules/view/' . $this->Link->oldLink['Link']['foreign_key'] . '#ui-tabs-2');
                        break;
                    default:
                        $this->redirect('/' . $this->foreignControllers[$this->Link->oldLink['Link']['model']] .
                                '/view/' . $this->Link->oldLink['Link']['foreign_key']);
                }
                break;
            case 'member':
                $this->redirect('/members/view/' . $this->loginMember['id'] . '#ui-tabs-5');
                break;
        }
    }

    function schedule($scheduleId = 0, $offset = 0) {
        $scheduleId = intval($scheduleId);
        $offset = intval($offset);
        if ($offset < 0)
            $offset = 0;
        $scope = array('Link.is_active' => 1);
        if ($scheduleId > 0) {
            $this->loadModel('Schedule');
            if (!$this->Schedule->hasAny(array(
                        'Schedule.id' => $scheduleId,
                    ))) {
                $scheduleId = 0;
            } else {
                $scope['Link.model'] = 'Schedule';
                $scope['Link.foreign_key'] = $scheduleId;
            }
        }
        if ($scheduleId <= 0) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        } else {
            $this->Paginator->settings['Link'] = array(
                'limit' => 5,
                'order' => array('created' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('dirname', 'basename', 'gender'),
                    ),
                ),
                'offset' => $offset,
            );
            $this->set('items', $this->Paginator->paginate($this->Link, $scope));
            $this->set('scheduleId', $scheduleId);
            $this->set('offset', $offset);
        }
    }

    function point($pointId = 0, $offset = 0) {
        $pointId = intval($pointId);
        $offset = intval($offset);
        if ($offset < 0)
            $offset = 0;
        $scope = array('Link.is_active' => 1);
        if ($pointId > 0) {
            $this->loadModel('Point');
            if (!$this->Point->hasAny(array(
                        'Point.id' => $pointId,
                    ))) {
                $pointId = 0;
            } else {
                $scope['Link.model'] = 'Point';
                $scope['Link.foreign_key'] = $pointId;
            }
        }
        if ($pointId <= 0) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        } else {
            $this->Paginator->settings['Link'] = array(
                'limit' => 5,
                'order' => array('created' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('dirname', 'basename', 'gender'),
                    ),
                ),
                'offset' => $offset,
            );
            $this->set('items', $this->Paginator->paginate($this->Link, $scope));
            $this->set('pointId', $pointId);
            $this->set('offset', $offset);
        }
    }

    function member($memberId = 0, $offset = 0) {
        $memberId = intval($memberId);
        $offset = intval($offset);
        if ($offset < 0) {
            $offset = 0;
        }
        if ($memberId > 0) {
            $items = $this->Link->find('all', array(
                'conditions' => array(
                    'Link.is_active' => 1,
                    'Link.member_id' => $memberId,
                ),
                'limit' => 5,
                'order' => array('created' => 'desc'),
                'offset' => $offset,
                    ));
            $this->set('foreignControllers', $this->foreignControllers);
            $this->set('items', $items);
            $this->set('url', array($memberId));
            $this->set('title_for_layout', '留言');
            $this->set('offset', $offset);
        } else {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        }
    }
    
    function tour($tourId = 0, $offset = 0) {
        $tourId = intval($tourId);
        $offset = intval($offset);
        if ($offset < 0)
            $offset = 0;
        $scope = array('Link.is_active' => 1);
        if ($tourId > 0) {
            $this->loadModel('Tour');
            if (!$this->Tour->hasAny(array(
                        'Tour.id' => $tourId,
                    ))) {
                $tourId = 0;
            } else {
                $scope['Link.model'] = 'Tour';
                $scope['Link.foreign_key'] = $tourId;
            }
        }
        if ($tourId <= 0) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        } else {
            $this->Paginator->settings['Link'] = array(
                'limit' => 5,
                'order' => array('created' => 'desc'),
                'contain' => array(
                    'Member' => array(
                        'fields' => array('dirname', 'basename', 'gender'),
                    ),
                ),
                'offset' => $offset,
            );
            $this->set('items', $this->Paginator->paginate($this->Link, $scope));
            $this->set('tourId', $tourId);
            $this->set('offset', $offset);
        }
    }

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $scope = array();
        if (array_key_exists($foreignModel, $this->foreignControllers) && $foreignId > 0) {
            $scope = array(
                'Link.foreign_key' => $foreignId,
                'Link.model' => $foreignModel,
            );
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->Paginator->settings['Link'] = array(
            'limit' => 20,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('id', 'username', 'nickname')
                )
            )
        );

        $items = $this->Paginator->paginate($this->Link, $scope);
        foreach ($items AS $key => $item) {
            $items[$key]['Link']['foreign_title'] = $this->getForeignTitle($item['Link']['model'], $item['Link']['foreign_key']);
        }
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
        $this->set('foreignControllers', $this->foreignControllers);
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect($this->referer());
        }
        if (!empty($this->request->data)) {
            if ($this->Link->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Link');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Link.data', $this->request->data);
                $this->Session->write('form.Link.validationErrors', $this->Link->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Link->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Link.data')) {
            $this->Link->validationErrors = $this->Session->read('form.Link.validationErrors');
            $this->Session->delete('form.Link');
        }
        if ($id > 0) {
            $this->request->data = $this->Link->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Link'][$key])) {
                        $this->request->data['Link'][$key] = $val;
                    }
                }
            }
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }

        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
        $belongsToModels = array(
            'listMember' => array(
                'label' => '會員',
                'modelName' => 'Member',
                'foreignKey' => 'member_id',
            ),
        );

        foreach ($belongsToModels AS $key => $model) {
            if ($foreignModel == $model['modelName']) {
                unset($belongsToModels[$key]);
                continue;
            }
            $this->set($key, $this->Link->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Link->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}