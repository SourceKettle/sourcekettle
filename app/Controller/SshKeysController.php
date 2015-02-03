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

	public function isAuthorized($user) {
		// Must be logged in
		return (isset($user) && !empty($user));
	}

/**
 * Add an SSH key for the current user
 */
	public function add() {
		$current_user = $this->viewVars['current_user'];
		if ($this->request->is('post')) {


			$data = $this->_cleanPost(array("SshKey.key", "SshKey.comment"));
			$data['SshKey']['user_id'] = $current_user['id']; //Set the key to belong to the current user
			if ($this->Flash->c($this->SshKey->save($data))) {
				$this->Setting->syncRequired(); // Update the sync required flag

				$this->log("[UsersController.addkey] sshkey[" . $this->SshKey->getLastInsertID() . "] added to user[" . $current_user['id'] . "]", 'sourcekettle');
				return $this->redirect(array('action' => 'view'));
			}
		}

		$this->SshKey->User->id = $current_user['id'];
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
			$key = $this->SshKey->findById($id);
			if (empty($key)) {
				throw new NotFoundException(__('Invalid SSH Key'));
			}

			if ($key['SshKey']['user_id'] != $this->Auth->user('id')) {
				throw new ForbiddenException(__('Ownership required'));
			}

			$comment = $key['SshKey']['comment'];
			$this->Flash->setUp();
			$this->SshKey->id = $key['SshKey']['id'];
			if ($this->Flash->d($this->SshKey->delete(), $comment)) {
				$this->log("[UsersController.deletekey] sshkey[" . $id . "] deleted by user[" . $this->Auth->user('id') . "]", 'sourcekettle');
				$this->Setting->syncRequired(); // Update the sync required flag
			}
		}
		return $this->redirect(array('action' => 'view'));
	}

/**
 * Displays the ssh keys of the current user
 */
	public function view() {
		$this->SshKey->User->id = $this->Auth->user('id');
		$this->request->data = $this->SshKey->User->read();
		$this->request->data['User']['password'] = null;
	}

}
