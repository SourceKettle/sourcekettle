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

    public $helpers = array('Geshi.Geshi', 'Time', 'CommandLineColor');
    public $uses = array('Source', 'GitCake.GitCake');

    /*
     * tree
     * display an element in the tree
     *
     * @param $name string name of the project
     */
    public function tree($name = null) {
        // Check for existant project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        // Lock out those who are not guests
        $this->Source->Project->id = $project['Project']['id'];
        if ( !$this->Source->Project->isMember($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a member of this project'));

        // Load the repo into the GitCake Model
        $this->GitCake->loadRepo($this->Source->RepoLocationOnFileSystem($project['Project']['name']));

        // Fetch branch
        $branch = $this->_getBranch();
        $node = $this->GitCake->getNodeAtPath($branch, $this->_getPath());

        $this->set("project", $project);
        $this->set("location", $this->params['pass']);
        $this->set('isAdmin', $this->Source->Project->isAdmin($this->Auth->user('id')));
        $this->set('branches', $this->GitCake->listBranches());

        switch ($node['type']) {
            case 'tree':
                $this->set("source_files", $this->GitCake->lsFolder($node['hash']));
                $this->render('tree_folder');
                break;
            case 'blob':
                $this->set("source_files", $this->GitCake->lsFile($node['hash']));
                $this->render('tree_blob');
                break;
            default:
                $this->render('tree_oops');
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
        // Check for existant project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        // Lock out those who are not guests
        $this->Source->Project->id = $project['Project']['id'];
        if ( !$this->Source->Project->isMember($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a member of this project'));

        // Load the repo into the GitCake Model
        $this->GitCake->loadRepo($this->Source->RepoLocationOnFileSystem($project['Project']['name']));

        // Fetch branch
        $branch = $this->_getBranch();
        $path = $this->_getPath();
        $node = $this->GitCake->getNodeAtPath($branch, $path);

        if (!isset($node['type'])) {
            $this->set("project", $project);
            $this->render('tree_oops');
        } else {
            $this->set("source_files", $this->GitCake->lsFile($node['hash']));
        }
    }

    /*
     * commits
     * Load the commits for a user to view the history of a project
     *
     * @param $name string name of the project
     */
    public function commits($name = null) {
        // Check for existant project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        // Lock out those who are not guests
        $this->Source->Project->id = $project['Project']['id'];
        if ( !$this->Source->Project->isMember($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a member of this project'));

        // Load the repo into the GitCake Model
        $this->GitCake->loadRepo($this->Source->RepoLocationOnFileSystem($project['Project']['name']));

        // Fetch branch
        $branch = $this->_getBranch();

        $this->set("project", $project);
        $this->set("location", $this->params['pass']);
        $this->set('isAdmin', $this->Source->Project->isAdmin($this->Auth->user('id')));
        $this->set('branches', $this->GitCake->listBranches());
        $this->set("commits", $this->GitCake->listCommits($branch, 10));
    }

    /*
     * commit
     * Load a commit for a user to view
     *
     * @param $name string name of the project
     */
    public function commit($name = null, $hash = null) {
        // Check for existant project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        // Lock out those who are not guests
        $this->Source->Project->id = $project['Project']['id'];
        if ( !$this->Source->Project->isMember($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a member of this project'));

        // Load the repo into the GitCake Model
        $this->GitCake->loadRepo($this->Source->RepoLocationOnFileSystem($project['Project']['name']));

        $this->set("project", $project);
        $this->set("location", $this->params['pass']);
        $this->set('isAdmin', $this->Source->Project->isAdmin($this->Auth->user('id')));
        $this->set("commit", $this->GitCake->showCommit($hash, true));
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
            $this->redirect(array('project' => $this->params['pass'][0], 'action' => $this->request['action'], 'master'));
        }

        // Check valid branch is selected
        if ( !$this->GitCake->hasTree($this->params['pass'][1]) ) {
            $this->Session->setFlash(__("The tree '".$this->params['pass'][1]."' does not exist."), 'default', array(), 'error');
            $this->redirect(array('project' => $this->params['pass'][0], 'action' => $this->request['action'], 'master'));
        }

        return $this->params['pass'][1];
    }

}
