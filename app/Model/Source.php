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
        'Blob' => array(
            'className' => 'GitCake.Blob',
        ),
        'Commit' => array(
            'className' => 'GitCake.Commit',
        ),
    );

    /**
     * getType function.
     *
     * @access private
     * @return void
     */
    public function getType() {
        $types = array(
            1 => null,
            2 => RepoTypes::Git,
            3 => RepoTypes::Subversion
        );
        $repoType = $this->Project->field('repo_type');

        // TODO Could throw a 'no repo' exception here if type is null

        return $types[$repoType];
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
        $type = $this->getType();
        $location = $this->getRepositoryLocation();

        if ($type == RepoTypes::Git) {
            App::uses('SourceGit', 'GitCake.Model');
            return SourceGit::create($location, 'g+rwX', 'group');
        } else if ($type == RepoTypes::Subversion) {
            App::uses('SourceSubversion', 'GitCake.Model');
            return SourceSubversion::create($location, 'g+rwX', 'group');
        } else {
            throw new NotFoundException(__("Repository type '$type' is unknown"));
        }
    }

    /**
     * branches function.
     *
     * @access public
     * @return void
     */
    public function getBranches() {
        return $this->Blob->getBranches();
    }

    /**
     * getDefaultBranch function.
     *
     * @access public
     * @return void
     */
    public function getDefaultBranch() {
        $branches = $this->getBranches();
        $type = $this->getType();

        if ($type == RepoTypes::Git) {
            $master = 'master';
        } else if ($type == RepoTypes::Subversion) {
            $master = 'HEAD';
        } else {
            $master = null;
        }

        if (empty($branches)) {
            return $master;
        } else if (in_array($master, $branches)) {
            return $master;
        } else {
            return $branches[0];
        }
    }

    /**
     * getRepositoryLocation function.
     *
     * @access public
     * @return void
     */
    public function getRepositoryLocation() {
        $devtrack_config = Configure::read('devtrack');
        $base = $devtrack_config['repo']['base'];

        if ($base[strlen($base)-1] != '/') $base .= '/';

        $name = $this->Project->field('name');
        $type = $this->getType();

        if ($type == RepoTypes::Git) {
            $type = 'git';
        } else if ($type == RepoTypes::Subversion) {
            $type = 'svn';
        } else {
            throw new NotFoundException(__("Repository type '$type' is unknown"));
        }

        return "{$base}{$name}.{$type}/";
    }

    /**
     * init function.
     *
     * @access public
     * @return void
     */
    public function init() {
        $type = $this->getType();
        $location = $this->getRepositoryLocation();

        // Need to create a Git singleton
        $this->Blob->open($type, $location);
        $this->Commit->open($type, $location);
    }

    public function fetchHistory($project = '', $number = 10, $offset = 0, $user = -1, $query = array()) {
        $events = array();

        return array();
    }
}
