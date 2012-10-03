<?php
/**
 *
 * Useful Component for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.Controller.Component
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Component', 'Controller');
class UsefulComponent extends Component {

    /**
     * extractEmail function.
     * Hopefully pulls out an email address from any string
     *
     * @access public
     * @param string $string (default: '')
     * @return void
     */
    public function extractEmail($string = '') {
        preg_match('#[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}#', $string, $matches);
        if (!isset($matches[0]) || is_null($matches[0])) {
            return null;
        }
        return $matches[0];
    }
}

