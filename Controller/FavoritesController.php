<?php

class FavoritesController extends AppController {

    var $name = 'Favorites';
    var $foreignControllers = array(
        'Point' => 'points',
        'Schedule' => 'schedules',
        'Member' => 'members',
    );

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index', 'member');
    }

    function index($memberId = 0, $foreignModel = null) {
        $memberId = intval($memberId);
        $modelCheck = array_key_exists($foreignModel, $this->foreignControllers);
        if ($memberId <= 0 || (!empty($foreignModel) && !$modelCheck)) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
        } else {
            $scope = array('Favorite.member_id' => $memberId);
            if ($modelCheck) {
                $scope['Favorite.model'] = $foreignModel;
            }
        }

        $this->Paginator->settings['Favorite'] = array(
            'limit' => 10,
            'order' => array('created' => 'desc'),
        );
        $items = $this->Paginator->paginate($this->Favorite, $scope);
        $titleStack = array();
        $subTitle = '我的最愛';
        if ($modelCheck) {
            switch ($foreignModel) {
                case 'Point':
                    $subTitle .= '::地點';
                    break;
                case 'Schedule':
                    $subTitle .= '::行程';
                    break;
                case 'Member':
                    $subTitle .= '::會員';
                    break;
            }
        }
        foreach ($items AS $key => $item) {
            if (!isset($this->$item['Favorite']['model'])) {
                $this->loadModel($item['Favorite']['model']);
            }
            $titleKey = $item['Favorite']['model'] . $item['Favorite']['foreign_key'];
            if (isset($titleStack[$titleKey])) {
                $items[$key]['Favorite']['foreignTitle'] = $titleStack[$titleKey];
            } else {
                $items[$key]['Favorite']['foreignTitle'] = $titleStack[$titleKey] =
                        $this->getForeignTitle($item['Favorite']['model'], $item['Favorite']['foreign_key']);
            }
        }
        $this->set('items', $items);
        $this->set('url', array($memberId, $foreignModel));
        $this->set('subTitle', $subTitle);
        $this->set('foreignControllers', $this->foreignControllers);
    }

    function add($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $modelCheck = array_key_exists($foreignModel, $this->foreignControllers);
        $inFavorite = false;
        if ($foreignId > 0 && $this->loginMember['id'] > 0 && $modelCheck) {
            $conditions = array(
                'model' => $foreignModel,
                'foreign_key' => $foreignId,
                'member_id' => $this->loginMember['id']
            );
            $count = $this->Favorite->find('count', array(
                'conditions' => $conditions
                    ));
            if ($op == 'del') {
                if ($count > 0) {
                    $this->Favorite->deleteAll($conditions);
                }
            } elseif ($op == 'add') {
                if ($count > 0) {
                    $inFavorite = true;
                } else {
                    $this->Favorite->create();
                    if ($this->Favorite->save(array('Favorite' => $conditions))) {
                        $inFavorite = true;
                    }
                }
            } elseif ($count > 0) {
                $inFavorite = true;
            }
            $this->set('url', array('action' => 'add', $foreignModel, $foreignId));
        }
        $this->set('inFavorite', $inFavorite);
    }

    function delete($id) {
        $id = intval($id);
        if ($id > 0 && $this->loginMember['id'] > 0) {
            $conditions = array(
                'id' => $id,
                'member_id' => $this->loginMember['id']
            );
            $this->Favorite->deleteAll($conditions);
            $this->Session->setFlash('操作已經完成！');
        } else {
            $this->Session->setFlash('請依照網頁指示操作！');
        }
        $this->redirect($this->referer());
    }

    function member($memberId = 0, $offset = 0) {
        $memberId = intval($memberId);
        $offset = intval($offset);
        if ($offset < 0) {
            $offset = 0;
        }
        if ($memberId <= 0) {
            $this->Session->setFlash('請依照網頁指示操作！');
            $this->redirect('/');
        }

        $items = $this->Favorite->find('all', array(
            'conditions' => array('Favorite.member_id' => $memberId),
            'limit' => 10,
            'order' => array('created' => 'desc'),
                ));
        foreach ($items AS $key => $item) {
            if (!isset($this->$item['Favorite']['model'])) {
                $this->loadModel($item['Favorite']['model']);
            }
            $items[$key]['Favorite']['foreignTitle'] = $this->getForeignTitle($item['Favorite']['model'], $item['Favorite']['foreign_key']);
        }
        $this->set('items', $items);
        $this->set('url', array($memberId));
        $this->set('memberId', $memberId);
        $this->set('foreignControllers', $this->foreignControllers);
        $this->set('offset', $offset);
    }

    function admin_index($foreignModel = null, $foreignId = 0, $op = null) {
        $foreignId = intval($foreignId);
        $foreignKeys = array();
        $foreignKeys = array(
            'Member' => 'member_id',
        );
        $scope = array();
        if (array_key_exists($foreignModel, $foreignKeys) && $foreignId > 0) {
            $scope['Favorite.' . $foreignKeys[$foreignModel]] = $foreignId;
        } else {
            $foreignModel = '';
        }
        $this->set('scope', $scope);
        $this->Paginator->settings['Favorite']['limit'] = 20;
        $items = $this->Paginator->paginate($this->Favorite, $scope);
        $this->set('items', $items);
        $this->set('foreignId', $foreignId);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Favorite->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}