<?php
/**
 *
 * Helper class for Source section of the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Helper
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class SourceHelper extends AppHelper {

    /**
     * helpers
     *
     * @var string
     * @access public
     */
    var $helpers = array('Html', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'));

    public $icons = array(
        'blob' => 'file',
        'tree' => 'folder-open',
        'commit' => 'share',
        'error' => 'warning-sign'
    );

    public function fetchIcon($type) {
        if (!isset($this->icons[$type])) {
            $type = 'error';
        }
        return '<i class="icon-'.$this->icons[$type].'"></i>';
    }

    public function fetchTreeUrl($repo, $branch, $file) {
        $url = $this->Html->url(array(
            'project' => $repo,
            'branch'  => $branch,
            'action'  => 'tree',
        ), true);
        return "{$url}/{$file}";
    }

    public function fetchRawUrl($repo, $branch, $file) {
        $url = $this->Html->url(array(
            'project' => $repo,
            'branch'  => $branch,
            'action'  => 'raw',
        ), true);
        return "{$url}/{$file}";
    }

    public function fetchHistoryUrl($repo, $branch, $file, $page = 1) {
        $url = $this->Html->url(array(
            'project' => $repo,
            'branch'  => $branch,
            'action'  => 'commits',
        ), true);
        return "{$url}/{$file}/page:$page";
    }
}
