<?php
/**
 *
 * CookieInfo Element for the DevTrack system
 * Renders a message informing about the cookies DevTrack uses
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cookie = Configure::read('Session.cookie');

echo $this->Bootstrap->block(
    "<strong>" . $devtrack_config['global']['alias'] . " uses cookies!</strong> DevTrack requires cookies to work. We only set one cookie ('$cookie') which is required for you to use DevTrack. If you do not accept this, then please delete the cookie manually and leave the site.",
    array(
        "style" => "info",
        "closable" => true
    )
);
