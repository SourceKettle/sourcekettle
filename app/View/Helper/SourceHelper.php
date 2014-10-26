<?php
/**
 *
 * Helper class for Source section of the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.View.Helper
 * @since		SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class SourceHelper extends AppHelper {

/**
 * helpers
 *
 * @var string
 * @access public
 */
	public $helpers = array('Html', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'));

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
		return '<i class="icon-' . $this->icons[$type] . '"></i>';
	}

	public function fetchTreeUrl($repo, $branch, $file, $full = true) {
		$url = $this->Html->url(array(
			'project' => $repo,
			'branch'	=> $branch,
			'action'	=> 'tree',
			'ajax'	=> false,
		), $full);
		return "{$url}/{$file}";
	}

	public function fetchRawUrl($repo, $branch, $file, $full = true) {
		$url = $this->Html->url(array(
			'project' => $repo,
			'branch'	=> $branch,
			'action'	=> 'raw',
			'ajax'	=> false,
		), $full);
		return "{$url}/{$file}";
	}

	public function fetchHistoryUrl($repo, $branch, $file, $page = 1, $full = true) {
		$url = $this->Html->url(array(
			'project' => $repo,
			'branch'	=> $branch,
			'action'	=> 'commits',
			'ajax'	=> false,
		), $full);
		return "{$url}/{$file}/page:$page";
	}
}
