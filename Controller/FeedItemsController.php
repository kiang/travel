<?php

App::uses('AppController', 'Controller');

/**
 * FeedItems Controller
 *
 * @property FeedItem $FeedItem
 */
class FeedItemsController extends AppController {

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->FeedItem->recursive = 0;
        $this->set('feedItems', $this->paginate());
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->FeedItem->create();
            if ($this->FeedItem->save($this->request->data)) {
                $this->Session->setFlash(__('The feed item has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The feed item could not be saved. Please, try again.'));
            }
        }
        $feeds = $this->FeedItem->Feed->find('list');
        $channels = $this->FeedItem->Channel->find('list');
        $this->set(compact('feeds', 'channels'));
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->FeedItem->id = $id;
        if (!$this->FeedItem->exists()) {
            throw new NotFoundException(__('Invalid feed item'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->FeedItem->save($this->request->data)) {
                $this->Session->setFlash(__('The feed item has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The feed item could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->FeedItem->read(null, $id);
        }
        $feeds = $this->FeedItem->Feed->find('list');
        $channels = $this->FeedItem->Channel->find('list');
        $this->set(compact('feeds', 'channels'));
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
        $this->FeedItem->id = $id;
        if (!$this->FeedItem->exists()) {
            throw new NotFoundException(__('Invalid feed item'));
        }
        if ($this->FeedItem->delete()) {
            $this->Session->setFlash(__('Feed item deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Feed item was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
