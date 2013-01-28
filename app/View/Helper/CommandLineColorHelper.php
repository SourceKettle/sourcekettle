<?php
/**
 *
 * Helper class for Command line color translation for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	DevTrack Development Team 2012
 * @link		http://github.com/SourceKettle/devtrack
 * @package		DevTrack.View.Helper
 * @since		DevTrack v 0.1
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class CommandLineColorHelper extends AppHelper {

	private $foreground_colors = array();
	private $background_colors = array();

	public function __construct() {
		// Set up shell colors
		$this->foreground_colors['Black'] = '30';
		$this->foreground_colors['Blue'] = '34';
		$this->foreground_colors['Green'] = '32';
		$this->foreground_colors['DarkGray'] = '36';
		$this->foreground_colors['Red'] = '31';
		$this->foreground_colors['Purple'] = '35';
		$this->foreground_colors['Yellow'] = '33';
		$this->foreground_colors['LightGray'] = '37';

		$this->background_colors['black'] = '40';
		$this->background_colors['red'] = '41';
		$this->background_colors['green'] = '42';
		$this->background_colors['yellow'] = '43';
		$this->background_colors['blue'] = '44';
		$this->background_colors['magenta'] = '45';
		$this->background_colors['cyan'] = '46';
		$this->background_colors['light_gray'] = '47';
	}

	public function translateColors($text) {
		foreach ($this->foreground_colors as $color => $ascii) {
			$text = preg_replace('/\['.$ascii.'m(.*?)\[m/i', '<span style="color:'.$color.';">${1}</span>', $text);
		}
		foreach ($this->background_colors as $color => $ascii) {
			$text = preg_replace('/\['.$ascii.'m(.*?)\[m/i', '<span style="background-color:'.$color.';">${1}</span>', $text);
		}
		return str_replace("[m", "", $text);
	}

}
