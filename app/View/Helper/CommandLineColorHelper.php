<?php
/**
 *
 * Helper class for Command line color translation for the SourceKettle system
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

class CommandLineColorHelper extends AppHelper {

	private $__foregroundColors = array();

	private $__backgroundColors = array();

	public function __construct() {
		// Set up shell colors
		$this->__foregroundColors['Black']		= '30';
		$this->__foregroundColors['Blue']		= '34';
		$this->__foregroundColors['Green']		= '32';
		$this->__foregroundColors['DarkGray']	= '36';
		$this->__foregroundColors['Red']		= '31';
		$this->__foregroundColors['Purple']		= '35';
		$this->__foregroundColors['Yellow']		= '33';
		$this->__foregroundColors['LightGray']	= '37';

		$this->__backgroundColors['black']		= '40';
		$this->__backgroundColors['red']		= '41';
		$this->__backgroundColors['green']		= '42';
		$this->__backgroundColors['yellow']		= '43';
		$this->__backgroundColors['blue']		= '44';
		$this->__backgroundColors['magenta']	= '45';
		$this->__backgroundColors['cyan']		= '46';
		$this->__backgroundColors['light_gray']	= '47';
	}

	public function translateColors($text) {
		foreach ($this->__foregroundColors as $color => $ascii) {
			$text = preg_replace('/\[' . $ascii . 'm(.*?)\[m/i', '<span style="color:' . $color . ';">${1}</span>', $text);
		}
		foreach ($this->__backgroundColors as $color => $ascii) {
			$text = preg_replace('/\[' . $ascii . 'm(.*?)\[m/i', '<span style="background-color:' . $color . ';">${1}</span>', $text);
		}
		return str_replace("[m", "", $text);
	}

}
