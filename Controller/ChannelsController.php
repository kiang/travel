<?php

App::uses('AppController', 'Controller');

/**
 * Channels Controller
 *
 * @property Channel $Channel
 */
class ChannelsController extends AppController {
    
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allowedActions = array('index');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index($offset = 0) {
        $offset = intval($offset);
        if ($offset < 0 || $offset % 5 != 0) {
            $offset = 0;
        }
        $this->set('offset', $offset);
        $key = '/channels/index/' . $offset;
        $items = Cache::read($key);
        if (false === $items) {
            $items = $this->Channel->find('all', array(
                'contain' => array(
                    'ChannelLink' => array(
                        'fields' => array('model', 'foreign_key', 'foreign_title'),
                        'order' => array('model ASC'),
                    ),
                ),
                'order' => array('Channel.the_date DESC', 'Channel.created DESC'),
                'limit' => 5,
                'offset' => $offset,
                    ));
            Cache::write($key, $items);
        }
        foreach ($items AS $key => $item) {
            foreach ($items[$key]['ChannelLink'] AS $link) {
                if (empty($link['model'])) {
                    continue;
                }
                if (!isset($items[$key][$link['model']])) {
                    $items[$key][$link['model']] = array();
                }
                $link['controller'] = $this->foreignControllers[$link['model']];
                $items[$key][$link['model']][] = $link;
            }
        }
        $this->set('items', $items);
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Channel->id = $id;
        if (!$this->Channel->exists()) {
            throw new NotFoundException(__('Invalid channel'));
        }
        $this->set('channel', $this->Channel->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Channel->create();
            if ($this->Channel->save($this->request->data)) {
                $this->Session->setFlash(__('The channel has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The channel could not be saved. Please, try again.'));
            }
        }
        $members = $this->Channel->Member->find('list');
        $this->set(compact('members'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->Channel->id = $id;
        if (!$this->Channel->exists()) {
            throw new NotFoundException(__('Invalid channel'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Channel->save($this->request->data)) {
                $this->Session->setFlash(__('The channel has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The channel could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Channel->read(null, $id);
        }
        $members = $this->Channel->Member->find('list');
        $this->set(compact('members'));
    }

    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Channel->id = $id;
        if (!$this->Channel->exists()) {
            throw new NotFoundException(__('Invalid channel'));
        }
        if ($this->Channel->delete()) {
            $this->Session->setFlash(__('Channel deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Channel was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->Paginator->settings = array(
            'order' => array('created' => 'DESC'),
        );
        $this->set('channels', $this->Paginator->paginate($this->Channel));
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        $this->Channel->id = $id;
        if (!$this->Channel->exists()) {
            throw new NotFoundException(__('Invalid channel'));
        }
        $this->set('channel', $this->Channel->read(null, $id));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Channel->create();
            if ($this->Channel->save($this->request->data)) {
                $this->loadModel('Link');
                $channelId = $this->Channel->getInsertID();
                $itemId = $this->Channel->FeedItem->field('id', array('FeedItem.url' => $this->request->data['Channel']['url']));
                $this->Channel->FeedItem->save(array('FeedItem' => array(
                        'id' => $itemId,
                        'channel_id' => $channelId
                        )));
                $this->Channel->FeedItem->updateAll(array('FeedItem.channel_id' => $channelId), array('FeedItem.url' => '\'' . $this->request->data['Channel']['url'] . '\''));
                foreach ($this->request->data['ChannelLink'] AS $model => $records) {
                    foreach ($records AS $key => $record) {
                        if (empty($record)) {
                            continue;
                        }
                        $this->Channel->ChannelLink->create();
                        $this->Channel->ChannelLink->save(array('ChannelLink' => array(
                                'member_id' => $this->loginMember['id'],
                                'channel_id' => $channelId,
                                'model' => $model,
                                'foreign_key' => $record,
                                'foreign_title' => $this->request->data[$model . 'Text'][$key],
                                )));
                        if ($model === 'Point') {
                            if (0 === $this->Link->find('count', array(
                                        'conditions' => array(
                                            'model' => 'Point',
                                            'foreign_key' => $record,
                                            'url' => $this->request->data['Channel']['url'],
                                        ),
                                    ))) {
                                $this->Link->create();
                                $this->Link->save(array('Link' => array(
                                        'member_id' => $this->loginMember['id'],
                                        'model' => 'Point',
                                        'foreign_key' => $record,
                                        'member_name' => $this->loginMember['nickname'],
                                        'url' => $this->request->data['Channel']['url'],
                                        'title' => $this->request->data['Channel']['title'],
                                        'is_active' => 1,
                                        'body' => $this->request->data['Channel']['summary'],
                                        )));
                            }
                        }
                    }
                }
                echo 'ok';
            } else {
                echo implode("\n", $this->Channel->validationErrors);
            }
            exit();
        }
        $this->set('formKey', mktime() . uniqid());
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->Channel->id = $id;
        if (!$this->Channel->exists()) {
            throw new NotFoundException(__('Invalid channel'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Channel->save($this->request->data)) {
                foreach($this->request->data['ChannelLink']['model'] AS $key => $val) {
                    if(empty($val)) {
                        $this->Channel->ChannelLink->delete($key);
                    } else {
                        $this->Channel->ChannelLink->id = $key;
                        $this->Channel->ChannelLink->save(array('ChannelLink' => array(
                            'model' => $this->request->data['ChannelLink']['model'][$key],
                            'foreign_key' => $this->request->data['ChannelLink']['foreign_key'][$key],
                            'foreign_title' => $this->request->data['ChannelLink']['foreign_title'][$key],
                        )));
                    }
                }
                $this->Session->setFlash(__('The channel has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The channel could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Channel->find('first', array(
                'conditions' => array('Channel.id' => $id),
                'contain' => array('ChannelLink')
            ));
        }
    }

    /**
     * admin_delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Channel->id = $id;
        if (!$this->Channel->exists()) {
            throw new NotFoundException(__('Invalid channel'));
        }
        if ($this->Channel->delete()) {
            $this->Session->setFlash(__('Channel deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Channel was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

    function admin_rss() {
        $feedItems = $this->Channel->FeedItem->find('all', array(
            'conditions' => array(
                'channel_id' => 0,
                'the_date >' => date('Y-m-d', strtotime('-2 months'))
            ),
            'order' => array('the_date DESC')
                ));
        $this->set('feedItems', $feedItems);
    }

    function admin_get_url() {
        if (!empty($_POST['url'])) {
            $content = file_get_contents($_POST['url']);
            $content = strip_tags($content, '<p><br><table><tr><td><div><span><a>');
            echo $content;
        }
        exit();
    }

}