<?php
/**
 *
 * Helper class for DevTrack Core config for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 DevTrack Development Team 2012
 * @link			http://github.com/SourceKettle/devtrack
 * @package		DevTrack.View.Helper
 * @since		 DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class DTHelper extends AppHelper {

	private $__config;

	private $__lang = 'en';

	public $helpers = array('TwitterBootstrap.TwitterBootstrap');

/**
 * __lazyLoad function.
 *
 * @access private
 * @param mixed $c
 * @param mixed $lang
 * @return void
 */
	private function __lazyLoad($c, $lang) {
		//debug($c);
		if (!isset($this->__config['pages'][$c])) {
			Configure::load('Language/dt_core_' . $c . '_' . $lang);
			$this->__config = Configure::read('dtcore');
		}
	}

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
 * t function.
 *
 * @access public
 * @param mixed $string
 * @param array $overrides (default: array())
 * @return void
 */
	public function t($string, $overrides = array()) {
		$lang = (isset($overrides['lang']) != null) ? $overrides['lang'] : $this->__lang;

		$c = (isset($overrides['controller']) != null) ? $overrides['controller'] : $this->request['controller'];
		$a = (isset($overrides['action']) != null) ? $overrides['action'] : $this->request['action'];

		$this->__lazyLoad($c, $lang);

		return $this->__config['pages'][$c][$a][$lang][$string];
	}

/**
 * pHeader function.
 *
 * @access public
 * @param array $overrides (default: array())
 * @return void
 */
	public function pHeader($overrides = array()) {
		$lang = (isset($overrides['lang'])) ? $overrides['lang'] : $this->__lang;
		$text = (isset($overrides['text'])) ? $overrides['text'] : $this->t('header.text', $overrides);

		$rBefore	= array("{project}", "{text}");
		$rAfter	= array($this->request['project'], $text);

		$h = str_replace($rBefore, $rAfter, $this->__config['common']['header']['project']['format']);

		return $this->TwitterBootstrap->page_header($h);
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
