<?php
/**
 *
 * Sources controller for the DevTrack system
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

class SourcesController extends AppController {

    private function _ls($name = null, $folder = '') {
        $repo = $this->_RepoForProject($name);

        $files = $repo->run('ls-files --cached');
        $pattern = '/^'.$folder.'/';

        return preg_grep($pattern, explode("\n", $files));
    }

    private function _RepoForProject($name = null) {
        $base = $this->devtrack_config['repo']['base'];
        if ($base[strlen($base)-1] != '/') {
            $base .= '/';
        }

        // Check for existant project
        $project = $this->Source->Project->getProject($name);
        if ( empty($project) ) throw new NotFoundException(__('Invalid project'));

        $base .= $project['Project']['name'];

        try {
            $repo = Git::open($base);
        } catch (Exception $e) {
            $repo = $this->_createRepo($base);
        }
        if (! Git::is_repo($repo)) {
            throw new Exception('Something has gone wrong with repo collection');
        }
        return $repo;
    }

    private function _createRepo($base = null) {
        if ($base == null) return null;

        if (!file_exists($base)) {
            mkdir($base, 0777);
        }

        return Git::create($base);
    }

}
