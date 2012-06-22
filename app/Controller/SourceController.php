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
App::import("Vendor", "Git", array("file"=>"Git/Git.php"));

class SourceController extends AppController {

    public $helpers = array('Geshi.Geshi');

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
        if ( !$this->Source->Project->isMember($this->Auth->user('id')) ) throw new ForbiddenException(__('You are not a admin of this project'));

        $repo = $this->Source->RepoForProject($name);

        $node = $this->_getCurrentNode($repo);

        $this->set("project", $project);
        $this->set("location", $this->params['pass']);
        $this->set('isAdmin', $this->Source->Project->isAdmin($this->Auth->user('id')));
        switch ($node['type']) {
            case 'tree':
                $this->set("source_files", $this->_lsFolder($repo, $node['hash']));
                $this->render('tree_folder');
                break;
            case 'blob':
                $this->set("source_files", $this->_lsFile($repo, $node['name']));
                $this->render('tree_blob');
                break;
        }
    }

    /*
     * _getCurrenctNode
     * Return the details of the current node
     *
     * @param $repo GitRepo the repo to examine
     */
    private function _getCurrentNode($repo) {
        $path = $this->_buildPath();

        // If we are looking at the root of the project
        if ($path == '') {
            return array(
                'type' => 'tree',
                'hash' => 'HEAD',
            );
        }

        $files = $repo->run('ls-tree HEAD ' . $path);

        $nodes = explode("\n", $files);

        return $this->_proccessNode($nodes[0]);
    }

    /*
     * _buildPath
     * Return the path the user is currently viewing
     *
     */
    private function _buildPath() {
        $route = $this->params['pass'];
        $url = '';
        for ($i = 1; $i <= sizeof($route)-1; $i++) {
            $url .= $route[$i] . '/';
        }
        if ($url == '') return $url;
        $url[strlen($url)-1] = '';
        return $url;
    }

    /*
     * _proccessNode
     * Return the details for the node in a linked list
     * Essentially converts git row output to array
     *
     * @param $node array the node details
     */
    private function _proccessNode($node) {
        $node = preg_split('/\s+/', $node);

        if ( !isset($node[0]) ||
             !isset($node[1]) ||
             !isset($node[2]) ||
             !isset($node[3]) ) {
            return null;
        }
        return array(
            'permissions' => $node[0],
            'type'        => $node[1],
            'hash'        => $node[2],
            'name'        => $node[3],
        );
    }

    /*
     * _lsFolder
     * Return the contents of a tree
     *
     * @param $repo GitRepo the repo to examine
     * @param $hash string the node to look up
     */
    private function _lsFolder($repo, $hash = 'HEAD') {
        $files = $repo->run('ls-tree ' . $hash);
        $nodes = explode("\n", $files);

        unset($nodes[sizeof($nodes)-1]);

        foreach ( $nodes as $node ) {
            $return[] = $this->_proccessNode($node);
        }
        return $return;
    }

    /*
     * _lsFile
     * Return the contents of a blob
     *
     * @param $repo GitRepo the repo to examine
     * @param $path string blob to look up
     */
    private function _lsFile($repo, $path) {
        $base = $this->Source->RepoLocationOnFileSystem($this->Source->Project->id);
        $nodes = file_get_contents($base.'/'.$path);
        return $nodes;
    }

}
