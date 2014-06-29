<?php
/**
 *
 * SshKeysController Controller for the SourceKettle system
 * Provides the hard-graft control of the ssh keys for users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link			http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Controller
 * @since		 SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

class SshKeysController extends AppController {

	public $uses = array('SshKey', 'Setting');

/**
 * Add an SSH key for the current user
 */
	public function add() {
		if ($this->request->is('post')) {

			$this->request->data['SshKey']['user_id'] = User::get('id'); //Set the key to belong to the current user

			if ($this->Flash->c($this->SshKey->save($this->request->data))) {
				$this->Setting->syncRequired(); // Update the sync required flag

				$this->log("[UsersController.addkey] sshkey[" . $this->SshKey->getLastInsertID() . "] added to user[" . User::get('id') . "]", 'sourcekettle');
				$this->redirect(array('action' => 'view'));
			}
		}

		$this->SshKey->User->id = User::get('id');
		$this->request->data = $this->SshKey->User->read();
		$this->request->data['User']['password'] = null;
	}

/**
 * Deletes a ssh key of the current user
 * @param type $id The id of the key to delete
 * @throws NotFoundException
 * @throws ForbiddenException
 */
	public function delete($id = null) {
		if ($this->request->is('post') && $id != null) {
			$this->SshKey->id = $id;

			if (!$this->SshKey->exists()) {
				throw new NotFoundException(__('Invalid SSH Key'));
			}

			if ($this->SshKey->field('user_id') != User::get('id')) {
				throw new ForbiddenException(__('Ownership required'));
			}

			$comment = $this->SshKey->field('comment');
			$this->Flash->setUp();
			if ($this->Flash->d($this->SshKey->delete(), $comment)) {
				$this->log("[UsersController.deletekey] sshkey[" . $id . "] deleted by user[" . $this->Auth->user('id') . "]", 'sourcekettle');
				$this->Setting->syncRequired(); // Update the sync required flag
			}
		}
		$this->redirect(array('action' => 'view'));
	}

/**
 * Displays the ssh keys of the current user
 */
	public function view() {
		$this->SshKey->User->id = User::get('id');
		$this->request->data = $this->SshKey->User->read();
		$this->request->data['User']['password'] = null;
	}

}
