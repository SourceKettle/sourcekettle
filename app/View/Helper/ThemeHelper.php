<?php
/**
 *
 * Helper class for displaying the correct CSS theme
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2014
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.View.Helper
 * @since		SourceKettle v 1.5
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class ThemeHelper extends AppHelper {


/**
 * helpers
 *
 * @var string
 * @access public
 */
	public $helpers = array('Html', 'TwitterBootswatch');

	public function css() {
		// System theme
		$theme = $sourcekettle_config['UserInterface']['theme']['value'];

		// If theme is not locked and the user has specified a theme, use it
		if (!$sourcekettle_config['UserInterface']['theme']['locked'] && isset($current_user['Settings']['theme'])) {
			$theme = $current_user['Settings']['theme'];
		}

		return $this->TwitterBootswatch->cssForTheme($theme);
	}
	
}
