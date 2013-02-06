<?php
/**
 *
 * CookieInfo Element for the SourceKettle system
 * Renders a message informing about the cookies SourceKettle uses
 * 
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       SourceKettle.View.Elements
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cookie = Configure::read('Session.cookie');

echo $this->Bootstrap->block(
    "<strong>" . $devtrack_config['global']['alias'] . " uses cookies!</strong> SourceKettle requires cookies to work. We only set one cookie ('$cookie') which is required for you to use SourceKettle. If you do not accept this, then please delete the cookie manually and leave the site.",
    array(
        "style" => "info",
        "closable" => true
    )
);
