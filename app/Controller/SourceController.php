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

    public function index($name = null, $folder = null) {
         // Check for existant project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $this->set("project", $project);
        $this->set("source_files", $this->_ls($name, $folder));
    }

    private function _ls($name = null, $folder = '') {
        $repo = $this->Source->RepoForProject($name);

        $files = $repo->run('ls-tree HEAD');
        $pattern = '/^'.$folder.'/';

        return preg_grep($pattern, explode("\n", $files));
    }

}
