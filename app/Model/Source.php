<?php
/**
*
* Source model for the DevTrack system
* Represents a source in the system
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/chrisbulmer/devtrack
* @package       DevTrack.Model
* @since         DevTrack v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::uses('AppModel', 'Model');

/**
 * Source Model
 *
 * @property Project $Project
 */
class Source extends AppModel {

    /**
     * useTable
     *
     * (default value: 'source')
     *
     * @var string
     * @access public
     */
    public $useTable = 'source';

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Project' => array(
            'className' => 'Project',
            'foreignKey' => 'project_id',
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'GitCake' => array(
            'className' => 'GitCake.GitCake',

        ),
        'SVNCake' => array(
            'className' => 'SVNCake.SVNCake',
        )
    );

    /**
     * init function.
     *
     * @access public
     * @return void
     */
    public function init() {
        switch ($this->Project->field('repo_type')) {
            case '1':
                break;
            case '2':
                $this->GitCake->loadRepo($this->_repoLocation());
                break;
            case '3':
                $this->SVNCake->loadRepo($this->_repoLocation());
                break;
        }
    }

    /**
     * _repoLocation function.
     *
     * @access public
     * @return void
     */
    public function _repoLocation() {
        $devtrack_config = Configure::read('devtrack');
        $base = $devtrack_config['repo']['base'];

        if ($base[strlen($base)-1] != '/') $base .= '/';

        $base .= $this->Project->field('name');

        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $base.'.git/';
            case '3': return $base.'.svn/';
        }
    }

    /**
     * create function.
     *
     * @access public
     * @param array $data (default: array())
     * @param bool $filterKey (default: false)
     * @return void
     */
    public function create($data = array(), $filterKey = false) {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->createRepo($this->_repoLocation(), 'g+rwX');
            case '3': return $this->SVNCake->createRepo($this->_repoLocation());
        }
    }

    /**
     * branches function.
     *
     * @access public
     * @return void
     */
    public function branches() {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->branch();
            case '3': return array('HEAD');
        }
    }

    /**
     * tree function.
     *
     * @access public
     * @param string $branch (default: 'master')
     * @param string $folderPath (default: '')
     * @return void
     */
    public function tree($branch = 'master', $folderPath = '') {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->_gitTree($branch, $folderPath);
            case '3': return $this->_svnTree($branch, $folderPath);
        }
    }

    /**
     * log function.
     *
     * @access public
     * @param string $branch (default: 'master')
     * @param int $limit (default: 10)
     * @param int $offset (default: 0)
     * @param string $filepath (default: '')
     * @return void
     */
    public function log($branch = 'master', $limit = 10, $offset = 0, $filepath = '') {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->log($branch, $limit, $offset, $filepath);
            case '3': return $this->SVNCake->log($branch, $limit, $offset, $filepath);
        }
    }

    /**
     * showCommit function.
     *
     * @access public
     * @param mixed $hash
     * @return void
     */
    public function showCommit($hash) {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->showCommit($hash);
            case '3': return $this->SVNCake->showCommit($hash);
        }
    }

    /**
     * hasBranch function.
     *
     * @access public
     * @param mixed $hash
     * @return void
     */
    public function hasBranch($hash) {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->hasTree($hash);
            case '3': return $this->SVNCake->hasTree($hash);
        }
    }

    /**
     * defaultBranch function.
     *
     * @access public
     * @return void
     */
    public function defaultBranch() {
        $branches = $this->branches();

        if (empty($branches))
            return null;

        switch ($this->Project->field('repo_type')) {
            case '2': $master = 'master';
            case '3': $master = 'HEAD';
        }

        if (in_array($master, $branches))
            return $master;

        return $branches[0];
    }

    /**
     * fetchHistory function.
     *
     * @access public
     * @param string $project (default: '')
     * @param int $number (default: 10)
     * @param int $offset (default: 0)
     * @param float $user (default: -1)
     * @param array $query (default: array())
     * @return void
     */
    public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
        $events = array();
        $branches = $this->branches();
        $project = $this->Project->getProject($project);

        if (!empty ($branches)) {
            foreach ($branches as $branch) {
                $log = $this->log($branch);

                if ($log) {
                    foreach ( $log as $a => $commit) {

                        $events[$a] = array();
                        $events[$a]['modified'] = $commit['Commit']['date'];
                        $events[$a]['Type'] = 'Source';

                        // Gather project details
                        $events[$a]['Project']['id'] = $project['Project']['id'];
                        $events[$a]['Project']['name'] = $project['Project']['name'];

                        // Gather user details
                        $events[$a]['Actioner']['id'] = -1;
                        $events[$a]['Actioner']['name'] = $commit['Commit']['author']['name'];
                        $events[$a]['Actioner']['email'] = $commit['Commit']['author']['email'];
                        $events[$a]['Actioner']['exists'] = false;

                        // Gather subject details
                        $events[$a]['Subject']['id'] = $commit['Commit']['hash'];
                        $events[$a]['Subject']['title'] = $commit['Commit']['subject'];
                        $events[$a]['Subject']['exists'] = true;

                        // Gather change details
                        $events[$a]['Change']['field'] = '+';
                        $events[$a]['Change']['field_old'] = null;
                        $events[$a]['Change']['field_new'] = null;

                        // Check if the actioner exists
                        $actioner = $this->Project->Collaborator->User->findByEmail('pw@thega.me.uk');
                        if($actioner) {
                            $events[$a]['Actioner']['id'] = $actioner['User']['id'];
                            $events[$a]['Actioner']['name'] = $actioner['User']['name'];
                            $events[$a]['Actioner']['exists'] = true;
                        }

                        // Store URL override
                        $events[$a]['url'] = array('project' => $project['Project']['name'], 'controller' => 'source', 'action' => 'commit', $commit['Commit']['hash']);
                    }
                }
            }
        }

        // Collect time events

        // Sort function for events
        // assumes $array{ $array{ 'modified' => 'date' }, ... }
        $cmp = function($a, $b) {
            if (strtotime($a['modified']) == strtotime($b['modified'])) return 0;
            if (strtotime($a['modified']) < strtotime($b['modified'])) return 1;
            return -1;
        };

        usort($events, $cmp);

        return array_slice($events, $offset, $number);
    }

    /**
     *
     ** Git Methods **
     *
     */

    private function _gitTree($branch, $folderPath) {
        $tree = $this->GitCake->tree($branch, $folderPath);
        if ($tree['type'] == 'tree') {
            foreach ($tree['content'] as $t => $element) {
                if ($element['type'] == 'commit') {
                    if (!isset($submodules)) $submodules = $this->GitCake->submodules($branch);
                    $tree['content'][$t]['remote'] = $submodules[$tree['path']."/".$element['name']]['remote'];
                }
                $tree['content'][$t]['commit'] = trim($this->GitCake->exec("rev-list --all -n 1 $branch -- ".$tree['path']."/".$element['name']));
                $tree['content'][$t]['updated'] = trim($this->GitCake->exec("--no-pager show -s --format='%ci' ".$tree['content'][$t]['commit']));
                $tree['content'][$t]['message'] = trim($this->GitCake->exec("--no-pager show -s --format='%s' ".$tree['content'][$t]['commit']));
            }
        }
        if ($tree['type'] == 'blob') {
            $tree['commit'] = trim($this->GitCake->exec("rev-list --all -n 1 $branch -- ".$tree['path']));
            $tree['updated'] = trim($this->GitCake->exec("--no-pager show -s --format='%ci' ".$tree['commit']));
            $tree['message'] = trim($this->GitCake->exec("--no-pager show -s --format='%s' ".$tree['commit']));
        }
        return $tree;
    }

    /**
     *
     ** SVN Methods **
     *
     */

    private function _svnTree($branch, $folderPath) {
        $tree = $this->SVNCake->tree($branch, $folderPath, $branch);

        $tree['type'] = $this->_svnTypeTranslate($tree['type']);

        if ($tree['type'] == 'tree' && isset($tree['content']) && !empty($tree['content'])) {
            foreach ($tree['content'] as $t => $element) {
                $tree['content'][$t]['type'] = $this->_svnTypeTranslate($element['type']);
            }
        }

        return $tree;
    }

    private function _svnTypeTranslate($type) {
        if ($type == 'dir') {
            return 'tree';
        } else if ($type == 'file') {
            return 'blob';
        }
        return $type;
    }
}
