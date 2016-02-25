<?php

App::uses('AppController', 'Controller');

/**
 * Oauths Controller
 *
 * @property Oauth $Oauth
 */
class OauthsController extends AppController {
    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->Paginator->settings['Oauth'] = array(
            'order' => array('id' => 'desc'),
            'contain' => array(
                'Member'
            ),
        );
        $this->set('oauths', $this->paginate());
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        $this->Oauth->id = $id;
        if (!$this->Oauth->exists()) {
            throw new NotFoundException(__('Invalid oauth'));
        }
        $this->set('oauth', $this->Oauth->read(null, $id));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->Oauth->create();
            if ($this->Oauth->save($this->request->data)) {
                $this->Session->setFlash(__('The oauth has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The oauth could not be saved. Please, try again.'));
            }
        }
        $members = $this->Oauth->Member->find('list');
        $this->set(compact('members'));
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->Oauth->id = $id;
        if (!$this->Oauth->exists()) {
            throw new NotFoundException(__('Invalid oauth'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Oauth->save($this->request->data)) {
                $this->Session->setFlash(__('The oauth has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The oauth could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Oauth->read(null, $id);
        }
        $members = $this->Oauth->Member->find('list');
        $this->set(compact('members'));
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
        $this->Oauth->id = $id;
        if (!$this->Oauth->exists()) {
            throw new NotFoundException(__('Invalid oauth'));
        }
        if ($this->Oauth->delete()) {
            $this->Session->setFlash(__('Oauth deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Oauth was not deleted'));
        $this->redirect(array('action' => 'index'));
    }

}
