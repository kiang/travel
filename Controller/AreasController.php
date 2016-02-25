<?php

class AreasController extends AppController {

    var $name = 'Areas';
    var $helpers = array();

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index', 'getList', 'getForm');
    }

    function index($parentId = 0) {
        $title = '旅遊區';
        $parentId = intval($parentId);
        $parents = array();
        if (!isset($this->request->params['pass'][0])) {
            $parentId = $this->Session->read('preference.AreaIndexId');
        } else {
            $this->Session->write('preference.AreaIndexId', $parentId);
        }
        if ($parentId > 0) {
            $parents = $this->Area->getPath($parentId, array('id', 'name'));
            $title = implode(' > ', Set::extract('{n}.Area.name', $parents)) . $title;
        }
        $parents = array_merge(array(0 => array('Area' => array(
                    'id' => 0,
                    'name' => '全球',
            ))), $parents);
        $this->set('parents', $parents);
        $this->set('url', array($parentId));
        $this->set('title_for_layout', $title);
        $this->set('id', $parentId);
    }

    function getForm($model = null, $number = 1, $parentId = 0) {
        $parentId = intval($parentId);
        $models = array('Member', 'Schedule', 'Point', 'Topic', 'Tour');
        if (in_array($model, $models)) {
            if ($parentId > 0) {
                $this->Session->write('preference.AreaId', $parentId);
                $areaId = 0;
            } else {
                $areaId = $this->Session->read('preference.AreaId');
            }
            $this->set('model', $model);
            $this->set('number', $number);
            if ($areaId > 0 &&
                    ($areas = $this->Area->getPath($areaId, array('id', 'parent_id')))
            ) {
                $allAreas = array();
                foreach ($areas AS $area) {
                    $options = $this->Area->find('all', array(
                        'fields' => array('Area.id', 'Area.name'),
                        'conditions' => array(
                            'Area.parent_id' => $area['Area']['parent_id'],
                        ),
                        'order' => array('name' => 'asc'),
                            ));
                    if (!empty($options)) {
                        $allAreas[] = array(
                            'parent_id' => $area['Area']['parent_id'],
                            'options' => $options,
                            'selected' => $area['Area']['id'],
                        );
                    }
                }
                if (!empty($area['Area']['id']) && $options = $this->Area->find('all', array(
                    'fields' => array('Area.id', 'Area.name'),
                    'conditions' => array(
                        'Area.parent_id' => $area['Area']['id'],
                    ),
                    'order' => array('name' => 'asc'),
                        ))) {
                    $allAreas[] = array(
                        'parent_id' => $area['Area']['id'],
                        'options' => $options,
                        'selected' => 0,
                    );
                }
                if (!empty($allAreas)) {
                    $this->set('areas', $allAreas);
                }
            } elseif ($areas = $this->Area->find('all', array(
                'fields' => array('Area.id', 'Area.name'),
                'conditions' => array(
                    'Area.parent_id' => $parentId,
                ),
                'order' => array('name' => 'asc'),
                    ))) {
                $this->set('areas', array(0 => array(
                        'parent_id' => $parentId,
                        'options' => $areas,
                        'selected' => 0,
                        )));
            }
        }
    }

    function getList($model = null, $foreignKey = 0) {
        $foreignKey = intval($foreignKey);
        $models = array('Member', 'Schedule');
        if ($foreignKey > 0 && in_array($model, $models)) {
            $areas = $this->Area->AreasModel->find('all', array(
                'fields' => array('AreasModel.id', 'AreasModel.area_id'),
                'conditions' => array(
                    'AreasModel.model' => $model,
                    'AreasModel.foreign_key' => $foreignKey,
                ),
                    ));
            foreach ($areas AS $key => $area) {
                $areas[$key]['Area'] = $this->Area->getPath($area['AreasModel']['area_id'], array('id', 'name')
                );
            }
            $this->set('url', array($model, $foreignKey));
            $this->set('areas', $areas);
            $this->loadModel($model);
            $this->set('owner', $this->$model->field('member_id', array($model . '.id' => $foreignKey)));
        }
    }

    function add($model = null, $foreignKey = 0) {
        $foreignKey = intval($foreignKey);
        $models = array('Member', 'Schedule');
        if ($foreignKey > 0 && in_array($model, $models)) {
            $this->loadModel($model);
            $ownerCheck = false;
            if ($this->loginMember['group_id'] == 1) {
                $ownerCheck = true;
            }
            if (false === $ownerCheck) {
                $ownerCheck = $this->$model->field('member_id', array($model . '.id' => $foreignKey)) == $this->loginMember['id'];
            }
            if (!$ownerCheck) {
                /*
                 * 確認操作人是否擁有這筆資料
                 */
                $this->Session->setFlash('請依照網頁指示操作');
                $this->redirect('/');
            } elseif ($model == 'Schedule' && $this->Area->AreasModel->find('count', array(
                        'conditions' => array(
                            'AreasModel.model' => $model,
                            'AreasModel.foreign_key' => $foreignKey,
                        ),
                    )) > 10) {
                /*
                 * 計算指定行程是否已經達到 10 個區域
                 */
                $this->set('areaControlMessage', '每個行程最多只能夠設定 10 個區域');
            } elseif (!empty($this->request->data[$model]['area_id'])) {
                /*
                 * 確認沒有重複設定同樣區域
                 */
                if ($this->Area->AreasModel->find('count', array(
                            'conditions' => array(
                                'AreasModel.model' => $model,
                                'AreasModel.foreign_key' => $foreignKey,
                                'AreasModel.area_id' => $this->request->data[$model]['area_id'],
                            ),
                        )) == 0) {
                    $this->Area->AreasModel->create();
                    $this->Area->AreasModel->save(array('AreasModel' => array(
                            'model' => $model,
                            'foreign_key' => $foreignKey,
                            'area_id' => $this->request->data[$model]['area_id'],
                            )));
                    $this->set('areaControlMessage', 'done');
                } else {
                    $this->set('areaControlMessage', '設定了重複的區域');
                }
            }
            $this->set('foreignKey', $foreignKey);
            $this->set('model', $model);
        } else {
            $this->Session->setFlash('請依照網頁指示操作');
            $this->redirect('/');
        }
    }

    function del($areasModelId = 0) {
        $areasModelId = intval($areasModelId);
        $areaControlMessage = '';
        if ($areasModelId > 0 && $areasModel = $this->Area->AreasModel->find('first', array(
            'conditions' => array('AreasModel.id' => $areasModelId),
                ))) {
            $model = $areasModel['AreasModel']['model'];
            $this->loadModel($model);
            $ownerCheck = false;
            if ($this->loginMember['group_id'] == 1) {
                $ownerCheck = true;
            }
            if (false === $ownerCheck) {
                $ownerCheck = $this->$model->field('member_id', array($model . '.id' => $areasModel['AreasModel']['foreign_key'])) == $this->loginMember['id'];
            }
            if ($ownerCheck) {
                $this->Area->AreasModel->delete($areasModelId);
                $areaControlMessage = 'done';
            }
        }
        if (empty($areaControlMessage)) {
            $areaControlMessage = '刪除時出現錯誤';
        }
        $this->set('areaControlMessage', $areaControlMessage);
    }

    function admin_index($parentId = 0) {
        $parentId = intval($parentId);
        $this->Paginator->settings['Area']['limit'] = 20;
        $items = $this->Paginator->paginate($this->Area, array('Area.parent_id' => $parentId));
        $this->set('items', $items);
        $this->set('url', array($parentId));
        $this->set('parentId', $parentId);
        if ($parentId > 0) {
            $this->set('parents', $this->Area->getPath($parentId, array('id', 'name')));
        }
    }

    function admin_add($parentId = 0) {
        if (!empty($this->request->data)) {
            $areaNames = explode("\n", $this->request->data['Area']['name']);
            $counter = 0;
            foreach ($areaNames AS $areaName) {
                $areaName = trim($areaName);
                if (!empty($areaName)) {
                    $this->Area->create();
                    if ($this->Area->save(array('Area' => array(
                                    'parent_id' => $parentId,
                                    'name' => $areaName,
                                    )))) {
                        ++$counter;
                    }
                }
            }
            $this->Session->setFlash("新增了 {$counter} 筆資料");
            $this->redirect(array('action' => 'index', $parentId));
        }
        $this->set('parentId', $parentId);
    }

    function admin_edit($id = null) {
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash('請依據網頁指示操作');
            $this->redirect('/');
        }
        if (!empty($this->request->data)) {
            if ($this->Area->save($this->request->data)) {
                $this->Session->setFlash('資料已經儲存');
                $this->Session->delete('form.Area');
                $this->redirect(array('action' => 'index', $this->request->data['Area']['parent_id']));
            } else {
                $this->Session->write('form.Area.data', $this->request->data);
                $this->Session->write('form.Area.validationErrors', $this->Area->validationErrors);
                $this->Session->setFlash('資料儲存失敗，請重試');
            }
        }
        $this->set('id', $id);
        $this->request->data = $this->Area->read(null, $id);
    }

    function admin_form($id = 0, $foreignModel = '') {
        $id = intval($id);
        if ($sessionFormData = $this->Session->read('form.Area.data')) {
            $this->Area->validationErrors = $this->Session->read('form.Area.validationErrors');
            $this->Session->delete('form.Area');
        }
        if ($id > 0) {
            $this->request->data = $this->Area->read(null, $id);
            if (!empty($sessionFormData)) {
                foreach ($sessionFormData AS $key => $val) {
                    if (isset($this->request->data['Area'][$key])) {
                        $this->request->data['Area'][$key] = $val;
                    }
                }
            }
            $parents = $this->Area->generateTreeList(array('Area.id !=' => $id));
            $parents[0] = '最上層';
            $this->set('parents', $parents);
        } else if (!empty($sessionFormData)) {
            $this->request->data = $sessionFormData;
        }
        $this->set('id', $id);
        $this->set('foreignModel', $foreignModel);
    }

    function admin_delete($id = null) {
        if (!$id) {
            $this->Session->setFlash('請依據網頁指示操作');
        } else if ($this->Area->delete($id)) {
            $this->Session->setFlash('資料已經刪除');
        }
        $this->redirect(array('action' => 'index'));
    }

}