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
			'action'  => 'tree',
			'ajax'	  => false,
			'?' => array('branch'	=> $branch),
			$file
		), $full);
		return $url;
	}

	public function fetchRawUrl($repo, $branch, $file, $full = true) {
		$url = $this->Html->url(array(
			'project' => $repo,
			'action'	=> 'raw',
			'ajax'	=> false,
			'?' => array('branch'	=> $branch),
			$file
		), $full);
		return $url;
	}

	public function fetchHistoryUrl($repo, $branch, $file, $page = 1, $full = true) {
		$url = $this->Html->url(array(
			'project' => $repo,
			'action'	=> 'commits',
			'ajax'	=> false,
			'?' => array('branch'	=> $branch),
			'page' => $page,
			$file
		), $full);
		return $url;
	}

	// Given a string (such as a commit subject), find any occurrences of "#<number>"
	// and turn them into a link to a task, and escape the rest of the string.
	// Optionally, the rest of the string can be linked to $linkRest.
	public function linkStringToTasks($string, $project, $linkRest = null) {

		// Get anything that's not a hash followed by a number and escape those bits of the string
		$escapedParts = array_map('h', preg_split('/#[0-9]+/s', $string));

		if ($linkRest) {
			for ($i = 0; $i < count($escapedParts); $i++) {
				$escapedParts[$i] = $this->Html->link($escapedParts[$i], $linkRest, array('escape' => false));
			}
		}
		$linked = '';

		// Now loop over all occurrences of task links...
		while(preg_match('/^.*(#([0-9]+))(.*)$/s', $string, $matches)){

			// Pull out the task bit...
			$linkText =$matches[1];
			$taskId = $matches[2];

			// Convert to a link...
			$link = $this->Html->link($linkText, array(
				'project' => $project,
				'controller' => 'tasks',
				'action' => 'view',
				$taskId
			));

			// Replace the part before the link with its escaped equivalent,
			// then add it and the link to our final string
			$linked .= array_shift($escapedParts) . $link;

			// Next time round the loop, process anything after the link
			$string = $matches[3];
		}

		// If there's any escaped stuff left, add it on the end...
		$linked .= join("", $escapedParts);

		return $linked;
	}
}
