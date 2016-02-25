<?php

App::uses('AppController', 'Controller');

/**
 * Feeds Controller
 *
 * @property Feed $Feed
 */
class FeedsController extends AppController {

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->Feed->recursive = 0;
        $this->set('feeds', $this->paginate());
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Feed->create();
            if ($this->Feed->save($this->request->data)) {
                $this->Session->setFlash(__('The feed has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The feed could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->Feed->id = $id;
        if (!$this->Feed->exists()) {
            throw new NotFoundException(__('Invalid feed'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Feed->save($this->request->data)) {
                $this->Session->setFlash(__('The feed has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The feed could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Feed->read(null, $id);
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
        $this->Feed->id = $id;
        if (!$this->Feed->exists()) {
            throw new NotFoundException(__('Invalid feed'));
        }
        if ($this->Feed->delete()) {
            $this->Session->setFlash(__('Feed deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Feed was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
