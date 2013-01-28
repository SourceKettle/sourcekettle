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

	var $_config;
	var $_lang = 'en';

	var $helpers = array('TwitterBootstrap.TwitterBootstrap');

	/**
	 * _lazyLoad function.
	 *
	 * @access private
	 * @param mixed $c
	 * @param mixed $lang
	 * @return void
	 */
	private function _lazyLoad($c, $lang) {
		//debug($c);
		if (!isset($this->_config['pages'][$c])) {
			Configure::load('Language/dt_core_'.$c.'_'.$lang);
			$this->_config = Configure::read('dtcore');
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
		$this->_config = Configure::read('dtcore');
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
		$lang = (isset($overrides['lang']) != null) ? $overrides['lang'] : $this->_lang;

		$c = (isset($overrides['controller']) != null) ? $overrides['controller'] : $this->request['controller'];
		$a = (isset($overrides['action']) != null) ? $overrides['action'] : $this->request['action'];

		$this->_lazyLoad($c, $lang);

		return $this->_config['pages'][$c][$a][$lang][$string];
	}

	/**
	 * pHeader function.
	 *
	 * @access public
	 * @param array $overrides (default: array())
	 * @return void
	 */
	public function pHeader($overrides = array()) {
		$lang = (isset($overrides['lang'])) ? $overrides['lang'] : $this->_lang;
		$text = (isset($overrides['text'])) ? $overrides['text'] : $this->t('header.text', $overrides);

		$r_before 	= array("{project}", "{text}");
		$r_after	= array($this->request['project'], $text);

		$h = str_replace($r_before, $r_after, $this->_config['common']['header']['project']['format']);

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
		$r_before	= array("\\n");
		$r_after	= array("<br>");

		return str_replace($r_before, $r_after, $text);
	}
}
