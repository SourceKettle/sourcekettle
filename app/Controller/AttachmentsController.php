<?php
/**
*
* AttachmentsController for the DevTrack system
* The controller to allow users to upload and view attachments
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
        $this->_attachment_with_restrictions($project);
    }

    /**
     * image function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @return void
     */
    public function image($project = null) {
        $this->_attachment_with_restrictions($project, array('mime' => $this->Attachment->_mime_image));
    }

    /**
     * video function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @return void
     */
    public function video($project = null) {
        $this->_attachment_with_restrictions($project, array('mime' => $this->Attachment->_mime_video));
    }

    /**
     * text function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @return void
     */
    public function text($project = null) {
        $this->_attachment_with_restrictions($project, array('mime' => $this->Attachment->_mime_text));
    }

    /**
     * other function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @return void
     */
    public function other($project = null) {
        $this->_attachment_with_restrictions($project, array('mime' => $this->Attachment->_mime_other));
    }

    /**
     * view function.
     *
     * @access public
     * @param mixed $project (default: null)
     * @param mixed $id (default: null)
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

    public function add($project = null) {
        $project = $this->_projectCheck($project);

        if (!empty($this->data)) {
            if ($data = $this->Attachment->upload($this->data)) {
                $this->Session->setFlash(__('The attachment has been added successfully'), 'default', array(), 'success');
                 $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
            } else {
                $this->Session->setFlash(__('The attachment could not be saved. Please, try again.'), 'default', array(), 'error');
            }
        }
    }

    /**
     * delete method
     *
     * @param string $name project name
     * @param string $id
     * @return void
     */
    public function delete($project = null, $id = null) {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();

        $project = $this->_projectCheck($project, true);
        $attachment = $this->Attachment->open($id);

        if ($this->Attachment->delete()) {
            $this->Session->setFlash(__('Attachment deleted'), 'default', array(), 'success');
        } else {
            $this->Session->setFlash(__('Attachment was not deleted'), 'default', array(), 'error');
        }
        $this->redirect(array('project' => $project['Project']['name'], 'action' => '.'));
    }

    /**
     * _attachment_with_restrictions function.
     *
     * @access private
     * @param mixed $project (default: null)
     * @param array $conditions (default: array())
     * @return void
     */
    private function _attachment_with_restrictions($project = null, $conditions = array()) {
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
