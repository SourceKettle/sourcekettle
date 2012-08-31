<?php
/**
 *
 * AppProjectController for the DevTrack system
 * The application wide controller for sub-components of a Project.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Modifications: DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Controller
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

class AppProjectController extends AppController {

    /*
     * _projectCheck
     * Space saver to ensure user can view content
     * Also sets commonly needed variables related to the project
     *
     * @access protected
     * @param mixed $name
     * @param bool $needWrite (default: false)
     * @return void
     */
    protected function _projectCheck($name, $needWrite = false) {
        if ( $this->modelClass == "Project" ) {
            $__model = $this->Project;
        } else {
            $__model = $this->{$this->modelClass}->Project;
        }
        // Check for existent project
        $project = $__model->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $__model->id = $project['Project']['id'];

        $this->set('project', $project);
        $this->set('isAdmin', $__model->isAdmin());

        // Lock out those who arnt allowed to write
        if ($needWrite && !$__model->hasWrite($this->Auth->user('id')) ) {
            throw new ForbiddenException(__('You do not have permissions to write to this project'));
        }

        return $project;
    }

}
