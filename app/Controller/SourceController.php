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

    public function index($name = null, $folder = '') {
         // Check for existant project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));
        
        $this->set("project", $project);
        $this->set("source_files", $this->_lsFolder($this->Source->RepoForProject($name)));
    }

    private function _lsFolder($repo, $index = 1, $parent_hash = 'HEAD') {
        $route = $this->params['pass'];

        $files = $repo->run('ls-tree '.$parent_hash);
        $nodes = explode("\n", $files);

        if ( !isset($route[$index]) ) {
            $route[$index] = '.';

            unset($nodes[sizeof($nodes)-1]);
            $return = array();
            $url = array();
            for ($i = 1; $i <= $index-1; $i++) {
                $url[] = $route[$i];
            }

            foreach ( $nodes as $node ) {
                $node = preg_split('/\s+/', $node);
                $url[$index-1] = $node[3];
                $node = array(
                    'permissions' => $node[0],
                    'type'        => $node[1],
                    'hash'        => $node[2],
                    'name'        => $node[3],
                    'parent_hash' => $parent_hash,
                    'source_id'   => $this->Source->id,
                    'url'         => $url,
                );
                array_push($return, $node);
            }
            return $return;
        } else {
            foreach ( $nodes as $node ) {
                $node = preg_split('/\s+/', $node);
                if ( $node[3] == $route[$index] && $node[1] == 'tree' ) {
                    return $this->_lsFolder($repo, $index + 1, $node[2]);
                }
            }
        }
    }

}
