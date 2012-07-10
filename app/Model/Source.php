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

    public function __construct() {
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

    private function _repoLocation() {

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

    public function create() {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->createRepo($this->_repoLocation());
            case '3': return $this->SVNCake->createRepo($this->_repoLocation());
        }
    }

    public function branches() {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->branch();
            case '3': return $this->SVNCake->branch();
        }
    }

    public function tree($branch = 'master', $folderPath = '') {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->_gitTree($branch, $folderPath);
            case '3': return $this->SVNCake->tree($branch, $folderPath);
        }
    }

    public function log($branch = 'master', $limit = 10, $offset = 0, $filepath = '') {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->log($branch, $limit, $offset, $filepath);
            case '3': return $this->SVNCake->log($branch, $limit, $offset, $filepath);
        }
    }

    public function showCommit($hash) {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->showCommit($hash);
            case '3': return $this->SVNCake->showCommit($hash);
        }
    }

    public function hasBranch($hash) {
        switch ($this->Project->field('repo_type')) {
            case '1': return null;
            case '2': return $this->GitCake->hasTree($hash);
            case '3': return null;
        }
    }

    public function defaultBranch() {
        $branches = $this->branches();

        if (empty($branches))
            return null;

        if (in_array('master', $branches))
            return 'master';

        return $branches[0];
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
                $tree['content'][$t]['commit'] = trim($this->GitCake->exec("rev-list --all -n 1 $branch -- ".$tree['path']."/".$element['name']));
                $tree['content'][$t]['updated'] = trim($this->GitCake->exec("--no-pager show -s --format='%ci' ".$tree['content'][$t]['commit']));
                $tree['content'][$t]['message'] = trim($this->GitCake->exec("--no-pager show -s --format='%s' ".$tree['content'][$t]['commit']));
            }
        }
        return $tree;
    }
}
