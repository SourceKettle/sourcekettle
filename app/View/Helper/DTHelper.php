<?php
/**
 *
 * Helper class for DevTrack Core config for the DevTrack system
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

class DTHelper extends AppHelper {

    var $_config;
    var $_lang = 'en';

    var $helpers = array('TwitterBootstrap.TwitterBootstrap');

    public function __construct($options = null) {
        parent::__construct($options);
        $this->_config = Configure::read('dtcore');
    }

    public function t($string, $overrides = array()) {
        $lang = (isset($overrides['lang']) != null) ? $overrides['lang'] : $this->_lang;

        $c = (isset($overrides['controller']) != null) ? $overrides['controller'] : $this->request['controller'];
        $a = (isset($overrides['action']) != null) ? $overrides['action'] : $this->request['action'];

        return $this->_config['pages'][$c][$a][$lang][$string];
    }

    public function pHeader($overrides = array()) {
        $lang = (isset($overrides['lang']) != null) ? $overrides['lang'] : $this->_lang;

        $r_before = array("{project}", "{text}");
        $r_after  = array($this->request['project'], $this->t('header.text', $overrides));

        $h = str_replace($r_before, $r_after, $this->_config['common']['header']['project']['format']);

        return $this->TwitterBootstrap->page_header($h);
    }
}
