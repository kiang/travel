<?php

class CommentsController extends AppController {

    var $name = 'Comments';
    var $helpers = array('Media.Media');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index', 'member', 'block_new', 'all',
            'schedule', 'point', 'tour', 'member_log');
    }

    function index($foreignModel = null, $foreignId = 0) {
        $this->Session->setFlash('請依照網頁指示操作！');
        $this->redirect('/');
        $foreignId = intval($foreignId);
        if ($foreignId > 0) {
            $this->set('addLink', true);
        } else {
            $this->set('addLink', false);
        }
        $scope = array('Comment.is_active' => 1);
        $modelCheck = array_key_exists($foreignModel, $this->foreignControllers);
        if ($modelCheck && $foreignId > 0) {
            $this->loadModel($foreignModel);
            if (!$this->$foreignModel->hasAny(array(
                        $foreignModel . '.id' => $foreignId,
                    ))) {
                $foreignModel = '';
            } else {
                $scope['Comment.model'] = $foreignModel;
                $scope['Comment.foreign_key'] = $foreignId;
            }
        } elseif ($modelCheck) {
            $scope['Comment.model'] = $foreignModel;
        } else {
            $foreignModel = '';
        }
        if (empty($foreignModel)) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        }
        $this->Paginator->settings['Comment'] = array(
            'limit' => 5,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('dirname', 'basename'),
                ),
            ),
        );
        $items = $this->Paginator->paginate($this->Comment, $scope);
        if ($foreignId == 0) {
            foreach ($items AS $key => $item) {
                if (!isset($this->$item['Comment']['model'])) {
                    $this->loadModel($item['Comment']['model']);
                }
                $titleKey = $item['Comment']['model'] . $item['Comment']['foreign_key'];
                if (isset($titleStack[$titleKey])) {
                    $items[$key]['Comment']['foreignTitle'] = $titleStack[$titleKey];
                } else {
                    $items[$key]['Comment']['foreignTitle'] = $titleStack[$titleKey] =
                            $this->getForeignTitle($item['Comment']['model'], $item['Comment']['foreign_key']);
                }
            }
        }
        $this->set('items', $items);
        $this->set('url', array($foreignModel, $foreignId));
        $this->set('foreignControllers', $this->foreignControllers);
    }

    function block_new($foreignModel = null) {
        $modelCheck = array_key_exists($foreignModel, $this->foreignControllers);
        if ($modelCheck) {
            $this->set('model', $foreignModel);
            $this->set('items', $this->Comment->find('all', array(
                        'conditions' => array('Comment.model' => $foreignModel),
                        'fields' => array('Comment.foreign_key', 'Comment.body', 'Comment.member_name', 'Comment.created'),
                        'limit' => 7,
                        'order' => array('created' => 'desc'),
                    )));
            $this->set('foreignControllers', $this->foreignControllers);
        }
    }

    function member($memberId = 0, $offset = 0) {
        $memberId = intval($memberId);
        $offset = intval($offset);
        if ($offset < 0)
            $offset = 0;
        if ($memberId > 0) {
            $this->set('addLink', true);
        } else {
            $this->set('addLink', false);
        }
        $scope = array('Comment.is_active' => 1);
        if ($memberId > 0) {
            $this->loadModel('Member');
            if (!$this->Member->hasAny(array(
                        'Member.id' => $memberId,
                    ))) {
                
            } else {
                $scope['Comment.model'] = 'Member';
                $scope['Comment.foreign_key'] = $memberId;
            }
        } else {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        }

        $items = $this->Comment->find('all', array(
            'conditions' => $scope,
            'limit' => 5,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('dirname', 'basename', 'gender'),
                ),
            ),
            'offset' => $offset,
                ));
        $this->set('items', $items);
        $this->set('memberId', $memberId);
        $this->set('offset', $offset);
    }

    function add($foreignModel = null, $foreignId = 0) {
        $foreignId = intval($foreignId);
        if (array_key_exists($foreignModel, $this->foreignControllers) && $foreignId > 0) {
            if (!empty($this->request->data)) {
                $this->request->data['Comment']['model'] = $foreignModel;
                $this->request->data['Comment']['foreign_key'] = $foreignId;
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
            $this->set('commentControlMessage', '資料儲存失敗，請重試');
        } elseif (!empty($this->request->data)) {
            if (!empty($this->request->data['Comment']['member_id'])) {
                $this->Session->write('block', 1);
                exit();
            }
            $this->Comment->create();
            $this->request->data['Comment']['member_id'] = $this->loginMember['id'];
            if (empty($this->request->data['Comment']['rank'])) {
                $this->request->data['Comment']['rank'] = '0';
            }
            if (!empty($this->loginMember['nickname'])) {
                $this->request->data['Comment']['member_name'] = $this->loginMember['nickname'];
            } else {
                $this->request->data['Comment']['member_name'] = $this->loginMember['username'];
            }
            $this->request->data['Comment']['is_active'] = 1;
            $this->request->data['Comment']['ip'] = ip2long($_SERVER['REMOTE_ADDR']);
            if (empty($this->request->data['Comment']['ip'])) {
                $this->request->data['Comment']['ip'] = '0';
            }
            if ($this->Comment->save($this->request->data)) {
                $this->$foreignModel->updateAll(
                        array($foreignModel . '.count_comments' => $foreignModel . '.count_comments + 1'), array($foreignModel . '.id' => $foreignId)
                );
                $this->set('commentControlMessage', 'done');
            } else {
                $errorMessage = '';
                foreach ($this->Comment->validationErrors AS $errors) {
                    foreach ($errors AS $error) {
                        $errorMessage .= $error . '<br />';
                    }
                }
                $this->set('commentControlMessage', $errorMessage);
            }
        }
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function form($id = 0) {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Comment.data')) {
            $this->Comment->validationErrors = $this->Session->read('form.Comment.validationErrors');
            $this->Session->delete('form.Comment');
        }
        if ($id > 0) {
            $this->request->data = $this->Comment->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Comment'][$key])) {
                        $this->request->data['Comment'][$key] = $val;
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
        if ($id <= 0 || !$this->Comment->oldComment = $this->Comment->find('first', array(
            'conditions' => array(
                'Comment.id' => $id,
                'Comment.member_id' => $this->loginMember['id'],
            ),
            'fields' => array('model', 'foreign_key'),
                ))) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        } elseif ($this->Comment->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        } else {
            $this->Session->setFlash('資料刪除失敗');
        }
        switch ($from) {
            case 'model':
                switch ($this->Comment->oldComment['Comment']['model']) {
                    case 'Schedule':
                        $this->redirect('/schedules/view/' . $this->Comment->oldComment['Comment']['foreign_key'] . '#ui-tabs-1');
                        break;
                    case 'Member':
                        $this->redirect('/members/view/' . $this->Comment->oldComment['Comment']['foreign_key'] . '#ui-tabs-2');
                        break;
                    default:
                        $this->redirect('/' . $this->foreignControllers[$this->Comment->oldComment['Comment']['model']] .
                                '/view/' . $this->Comment->oldComment['Comment']['foreign_key']);
                }
                break;
            case 'member_log':
                $this->redirect('/members/view/' . $this->loginMember['id'] . '#ui-tabs-4');
                break;
        }
    }

    function all() {
        $this->Paginator->settings['Comment'] = array(
            'order' => array('created' => 'desc'),
            'limit' => 5,
            'contain' => array('Member' => array(
                    'fields' => array('dirname', 'basename'),
            )),
        );
        $comments = $this->Paginator->paginate($this->Comment);
        foreach ($comments AS $key => $comment) {
            $comments[$key]['Comment']['topic'] = $this->getForeignTitle(
                    $comment['Comment']['model'], $comment['Comment']['foreign_key']
            );
        }
        $this->set('comments', $comments);
        $this->set('foreignControllers', $this->foreignControllers);
    }

    function schedule($scheduleId = 0, $offset = 0) {
        $scheduleId = intval($scheduleId);
        $offset = intval($offset);
        if ($offset < 0)
            $offset = 0;
        if ($scheduleId > 0) {
            $this->set('addLink', true);
        } else {
            $this->set('addLink', false);
        }
        $scope = array('Comment.is_active' => 1);
        if ($scheduleId > 0) {
            $this->loadModel('Schedule');
            if (!$this->Schedule->hasAny(array(
                        'Schedule.id' => $scheduleId,
                    ))) {
                
            } else {
                $scope['Comment.model'] = 'Schedule';
                $scope['Comment.foreign_key'] = $scheduleId;
            }
        } else {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        }
        $items = $this->Comment->find('all', array(
            'conditions' => $scope,
            'limit' => 5,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('dirname', 'basename', 'gender'),
                ),
            ),
            'offset' => $offset,
                ));
        $this->set('items', $items);
        $this->set('scheduleId', $scheduleId);
        $this->set('offset', $offset);
    }

    function point($pointId = 0, $offset = 0) {
        $pointId = intval($pointId);
        $offset = intval($offset);
        if ($offset < 0)
            $offset = 0;
        if ($pointId > 0) {
            $this->set('addLink', true);
        } else {
            $this->set('addLink', false);
        }
        $scope = array('Comment.is_active' => 1);
        if ($pointId > 0) {
            $this->loadModel('Point');
            if (!$this->Point->hasAny(array(
                        'Point.id' => $pointId,
                    ))) {
                
            } else {
                $scope['Comment.model'] = 'Point';
                $scope['Comment.foreign_key'] = $pointId;
            }
        } else {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        }
        $items = $this->Comment->find('all', array(
            'conditions' => $scope,
            'limit' => 5,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('dirname', 'basename', 'gender'),
                ),
            ),
            'offset' => $offset,
                ));
        $this->set('items', $items);
        $this->set('pointId', $pointId);
        $this->set('offset', $offset);
    }

    function member_log($memberId = 0, $offset = 0) {
        $memberId = intval($memberId);
        $offset = intval($offset);
        if ($offset < 0) {
            $offset = 0;
        }
        if ($memberId > 0) {
            $items = $this->Comment->find('all', array(
                'conditions' => array(
                    'Comment.is_active' => 1,
                    'Comment.member_id' => $memberId,
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
        if ($tourId > 0) {
            $this->set('addLink', true);
        } else {
            $this->set('addLink', false);
        }
        $scope = array('Comment.is_active' => 1);
        if ($tourId > 0) {
            $this->loadModel('Tour');
            if (!$this->Tour->hasAny(array(
                        'Tour.id' => $tourId,
                    ))) {
                
            } else {
                $scope['Comment.model'] = 'Tour';
                $scope['Comment.foreign_key'] = $tourId;
            }
        } else {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
            exit();
        }
        $items = $this->Comment->find('all', array(
            'conditions' => $scope,
            'limit' => 5,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('dirname', 'basename', 'gender'),
                ),
            ),
            'offset' => $offset,
                ));
        $this->set('items', $items);
        $this->set('tourId', $tourId);
        $this->set('offset', $offset);
    }

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $scope = array();
        if (array_key_exists($foreignModel, $this->foreignControllers) && $foreignId > 0) {
            $scope = array(
                'Comment.model' => $foreignModel,
                'Comment.foreign_key' => $foreignId,
            );
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->Paginator->settings = array(
            'limit' => 20,
            'order' => array('created' => 'desc'),
            'contain' => array(
                'Member' => array(
                    'fields' => array('id', 'username', 'nickname')
                )
            )
        );
        $items = $this->Paginator->paginate($this->Comment, $scope);
        foreach ($items AS $key => $item) {
            $items[$key]['Comment']['foreign_title'] = $this->getForeignTitle($item['Comment']['model'], $item['Comment']['foreign_key']);
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
            if ($this->Comment->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Comment');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->write('form.Comment.data', $this->request->data);
                $this->Session->write('form.Comment.validationErrors', $this->Comment->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Comment->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Comment.data')) {
            $this->Comment->validationErrors = $this->Session->read('form.Comment.validationErrors');
            $this->Session->delete('form.Comment');
        }
        if ($id > 0) {
            $this->request->data = $this->Comment->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Comment'][$key])) {
                        $this->request->data['Comment'][$key] = $val;
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
            $this->set($key, $this->Comment->$model['modelName']->find('list'));
        }
        $this->set('belongsToModels', $belongsToModels);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Comment->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}