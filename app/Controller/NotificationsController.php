<?php
App::uses('AppController', 'Controller');
/**
 * Notifications Controller
 *
 * @property Notification $Notification
 */
class NotificationsController extends AppController {

	public function beforeFilter(){
		parent::beforeFilter();
	}


/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Notification->id = $id;
		if (!$this->Notification->exists()) {
			throw new NotFoundException(__('Invalid notification'));
		} else {
			$notification = $this->Notification->read(null, $id);
			if ($notification['Notification']['user_id'] != $this->Notification->_auth_user_id){
				throw new NotFoundException(__('Invalid notification'));
			} else {
				$this->Notification->delete();
				$this->redirect($notification['Notification']['url']);
			}
		}
	}

/**
 * dismiss method
 *
 * @param string $id
 * @return void
 */
	public function dismiss($id = null) {
		if (!$this->request->isPost() || !$this->request->is('ajax')) {
			throw new MethodNotAllowedException();
		}

		$this->Notification->id = $id;

		if (!$this->Notification->exists()) {
			throw new NotFoundException(__('Invalid notification'));
		} else {
			$notification = $this->Notification->read(null, $id);
			if ($notification['Notification']['user_id'] != $this->Notification->_auth_user_id){
				throw new NotFoundException(__('Invalid notification'));
			} else {
				// Delete
				$this->Notification->delete();
				$this->autoRender = false;
				$this->layout = 'ajax';
				echo '{"notification":' . $id . "}";
			}
		}
	}
}
