<?php
/**
 *
 * Dashboards Controller for the DevTrack system
 * Provides methods for Dashboards to interact with their database object.
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

class DashboardController extends AppController {

    public $uses = array('Project', 'Task');

    public $helpers = array('Time');

    function index() {
        // Get the recent projects
        $this->set('projects', $this->getRecentProjects());

    }

    private function getRecentProjects(){
      $this->Project->Collaborator->recursive = 0;

        return $this->Project->Collaborator->find(
          'all', array(
            'conditions' => array('Collaborator.user_id' => $this->Project->_auth_user_id),
            'order' => array('Project.modified DESC'),
            'limit' => 5
          )
        );
    }

    private function getUserTasks(){

    }

}