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

    public $helpers = array('Geshi.Geshi');
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

        if ( !isset($this->params['pass'][1]) ) $this->redirect(array('project' => $name, 'action' => 'tree', 'master'));

        $this->GitCake->loadRepo($this->Source->RepoLocationOnFileSystem($name));

        $node = $this->GitCake->getNodeAtPath($this->params['pass'][1], $this->_buildPath());

        //$node = $this->_getCurrentNode($repo);

        $this->set("project", $project);
        $this->set("location", $this->params['pass']);
        $this->set('isAdmin', $this->Source->Project->isAdmin($this->Auth->user('id')));
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
     * _buildPath
     * Return the path the user is currently viewing
     *
     */
    private function _buildPath() {
        $route = $this->params['pass'];
        $url = '';
        for ($i = 2; $i <= sizeof($route)-1; $i++) {
            $url .= $route[$i] . '/';
        }
        if ($url == '') return $url;
        $url[strlen($url)-1] = '';
        return $url;
    }

}
