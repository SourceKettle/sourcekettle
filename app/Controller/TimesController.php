<?php
/**
 *
 * TimesController Controller for the DevTrack system
 * Provides the hard-graft control of the time segments
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Controller
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class TimesController extends AppController {

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->Time->recursive = 0;
        $this->set('times', $this->paginate());
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        $this->set('time', $this->Time->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->Time->create();
            if ($this->Time->save($this->request->data)) {
                $this->Session->setFlash(__('The time has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The time could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Time->Project->find('list');
        $users = $this->Time->User->find('list');
        $this->set(compact('projects', 'users'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Time->save($this->request->data)) {
                $this->Session->setFlash(__('The time has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The time could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Time->read(null, $id);
        }
        $projects = $this->Time->Project->find('list');
        $users = $this->Time->User->find('list');
        $this->set(compact('projects', 'users'));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Time->id = $id;
        if (!$this->Time->exists()) {
            throw new NotFoundException(__('Invalid time'));
        }
        if ($this->Time->delete()) {
            $this->Session->setFlash(__('Time deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Time was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
