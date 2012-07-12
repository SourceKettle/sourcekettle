<?php
/**
 *
 * Source controller for the DevTrack system
 * Provides access to the code behind projects
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

class SourceController extends AppController {

    public $helpers = array('Time', 'CommandLineColor');

    /*
     * _projectCheck
     * Space saver to ensure user can view content
     * Also sets commonly needed variables related to the project
     *
     * @param $name string Project name
     */
    private function _projectCheck($name) {
        // Check for existent project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));
        $this->Source->Project->id = $project['Project']['id'];

        $user = $this->Auth->user('id');

        // Lock out those who are not guests
        if ( !$this->Source->Project->hasRead($user) ) throw new ForbiddenException(__('You are not a member of this project'));

        $this->Source->init();

        $this->set('project', $project);
        $this->set('isAdmin', $this->Source->Project->isAdmin($user));

        return $project;
    }

    public function index($name = null) {
        $this->redirect(array('action' => 'tree', 'project' => $name));
    }

    /*
     * tree
     * display an element in the tree
     *
     * @param $name string name of the project
     */
    public function tree($name = null) {
        $this->_projectCheck($name);

        $branches = $this->Source->branches();
        if(empty($branches)) {
            $this->redirect(array('project' => $name, 'controller' => 'source', 'action' => 'gettingStarted'));
        } else {
            // Fetch branch
            $branch = $this->_getBranch();
            $path = $this->_getPath();
            $node = $this->Source->tree($branch, $path);

            $this->set("branch", $branch);
            $this->set("path", $path);
            $this->set('branches', $branches);

            switch ($node['type']) {
                case 'tree':
                    $this->set("tree", $node);
                    break;
                case 'blob':
                    $this->set("tree", $node);
                    break;
                default:
                    $this->render('not_found');
            }
        }
    }

    public function gettingStarted($name = null) {
        $this->_projectCheck($name);
        $this->set('user', $this->Auth->user());
        switch ($this->Source->Project->field('repo_type')) {
            case '1':
                $this->render('GettingStarted/none');
                break;
            case '2':
                $this->render('GettingStarted/git');
                break;
            case '3':
                $this->render('GettingStarted/svn');
                break;
        }
    }

    /*
     * raw
     * Same as tree but the raw file
     *
     * @see tree
     * @param $name string name of the project
     */
    public function raw($name = null) {
        $this->_projectCheck($name);

        // Fetch branch
        $branch = $this->_getBranch();
        $path = $this->_getPath();
        $node = $this->Source->tree($branch, $path);

        if ($node['type'] != 'blob') {
            $this->set("branch", $branch);
            $this->set('branches', $this->Source->branches());
            $this->render('not_found');
        } else {
            $this->set("source_files", $node['content']);
        }
    }

    /*
     * commits
     * Load the commits for a user to view the history of a project
     *
     * @param $name string name of the project
     */
    public function commits($name = null) {
        $this->_projectCheck($name);

        $branches = $this->Source->branches();
        if(empty($branches)) {
            $this->redirect(array('project' => $name, 'controller' => 'source', 'action' => 'gettingStarted'));
        } else {
            // Fetch branch
            $branch = $this->_getBranch();

            $this->set("branch", $branch);
            $this->set('branches', $branches);
            $this->set("commits", $this->Source->log($branch, 10));
        }
    }

    /*
     * commit
     * Load a commit for a user to view
     *
     * @param $name string name of the project
     */
    public function commit($name = null, $hash = null) {
        $this->_projectCheck($name);

        $branches = $this->Source->branches();
        if(empty($branches)) {
            $this->redirect(array('project' => $name, 'controller' => 'source', 'action' => 'gettingStarted'));
        } else {
            $this->set("branch", null);
            $this->set('branches', $branches);
            $this->set("commit", $this->Source->showCommit($hash));
        }
    }

    /*
     * _getPath
     * Return the path the user is currently viewing
     *
     */
    private function _getPath() {
        $route = $this->params['pass'];
        unset($route[0]); // Project name
        unset($route[1]); // Branch name
        return implode('/',$route);
    }

    /*
     * _getBranch
     * Return the branch the user is currently viewing
     *
     */
    private function _getBranch() {
        // Check to see if a branch is set, if not redirect to master
        if ( !isset($this->params['pass'][1]) ) {
            $this->redirect(array('project' => $this->params['pass'][0], 'action' => $this->request['action'], $this->Source->defaultBranch()));
        }

        // Check valid branch is selected
        if ( !$this->Source->hasBranch($this->params['pass'][1]) ) {
            $this->Session->setFlash(__("The tree '".$this->params['pass'][1]."' does not exist."), 'default', array(), 'error');
            $this->redirect(array('project' => $this->params['pass'][0], 'action' => $this->request['action'], $this->Source->defaultBranch()));
        }

        return $this->params['pass'][1];
    }

}
