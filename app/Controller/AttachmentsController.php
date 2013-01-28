<?php
/**
 *
 * AttachmentsController for the DevTrack system
 * The controller to allow users to upload and view attachments
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 DevTrack Development Team 2012
 * @link		  http://github.com/SourceKettle/devtrack
 * @package	   DevTrack.Controller
 * @since		 DevTrack v 0.1
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppProjectController', 'Controller');

class AttachmentsController extends AppProjectController{

/**
 * The name of the controller
 *
 * @var String
 * @access public
 */
	public $name = 'Attachment';

	public $helpers = array('Time');

/**
 * index function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function index($project = null) {
		$this->__attachmentWithRestrictions($project);
	}

/**
 * image function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function image($project = null) {
		$this->__attachmentWithRestrictions($project, array('mime' => $this->Attachment->mimeImage));
	}

/**
 * video function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function video($project = null) {
		$this->__attachmentWithRestrictions($project, array('mime' => $this->Attachment->mimeVideo));
	}

/**
 * text function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function text($project = null) {
		$this->__attachmentWithRestrictions($project, array('mime' => $this->Attachment->mimeText));
	}

/**
 * other function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function other($project = null) {
		$this->__attachmentWithRestrictions($project, array('mime' => $this->Attachment->mimeOther));
	}

/**
 * view function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @param mixed $id (default: null)
 * @throws InternalErrorException
 * @return void
 */
	public function view($project = null, $id = null) {
		$project = $this->_projectCheck($project);
		$attachment = $this->Attachment->open($id);

		// Lets pretend we are serving a real file
		if (in_array('download', $this->request['pass']) || !$this->Attachment->renderable()) {
			$this->RequestHandler->renderAs($this, 'ajax', array('attachment' => $attachment['Attachment']['name']));
		} else {
			$this->RequestHandler->renderAs($this, 'ajax');
		}
		$this->RequestHandler->respondAs($attachment['Attachment']['mime']);

		if (md5($attachment['Attachment']['content']) != $attachment['Attachment']['md5']) {
			throw new InternalErrorException(__('Integrity check failed'));
		}

		$this->set('attachment', $attachment);
	}

/**
 * add function.
 *
 * @access public
 * @param mixed $project (default: null)
 * @return void
 */
	public function add($project = null) {
		$project = $this->_projectCheck($project, true);

		if (!empty($this->data)) {
			if ($this->Flash->c($this->Attachment->upload($this->data))) {
				$this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
			}
		}
	}

/**
 * delete method
 *
 * @param string $name project name
 * @param string $id
 * @throws MethodNotAllowedException
 * @return void
 */
	public function delete($project = null, $id = null) {
		$project = $this->_projectCheck($project, true);
		$attachment = $this->Attachment->open($id);

		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}

		$this->Flash->setUp();
		$this->Flash->d($this->Attachment->delete());
		$this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
	}

/**
 * __attachmentWithRestrictions function.
 *
 * @access private
 * @param mixed $project (default: null)
 * @param array $conditions (default: array())
 * @return void
 */
	private function __attachmentWithRestrictions($project = null, $conditions = array()) {
		$project = $this->_projectCheck($project);
		$conditions['Attachment.project_id'] = $project['Project']['id'];
		$conditions['Attachment.model'] = null;
		$attachments = $this->Attachment->find(
			'all',
			array(
				'conditions' => $conditions,
				'order' => array('Attachment.created DESC', 'Attachment.name ASC'),
			)
		);
		$this->set('attachments', $attachments);
		$this->render('index');
	}
}
