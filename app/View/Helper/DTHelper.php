<?php
/**
 *
 * Helper class for SourceKettle Core config for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 SourceKettle Development Team 2012
 * @link			http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.View.Helper
 * @since		 SourceKettle v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class DTHelper extends AppHelper {

	private $__config;

	private $__lang = 'en';

	public $helpers = array('TwitterBootstrap.TwitterBootstrap');

/**
 * __construct function.
 *
 * @access public
 * @param mixed $options (default: null)
 * @return void
 */
	public function __construct($options = null) {
		parent::__construct($options);
		$this->__config = Configure::read('dtcore');
	}


/**
 * pHeader function.
 *
 * @access public
 * @param array $overrides (default: array())
 * @return void
 */
	public function pHeader($title) {

		$header = '<div class="page-header"><h1>'.h($this->request['project']).' <small>'.h($title).'</small></h1></div>';
		return $this->TwitterBootstrap->page_header($header);
	}

/**
 * parse function.
 *
 * @access public
 * @param string $text (default: '')
 * @return void
 */
	public function parse($text = '') {
		$rBefore	= array("\n");
		$rAfter	= array("<br>");

		return str_replace($rBefore, $rAfter, $text);
	}
}
