<?php
/**
 *
 * CookieInfo Element for the SourceKettle system
 * Renders a message informing about the cookies SourceKettle uses
 * 
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cookie = Configure::read('Session.cookie');

echo $this->Bootstrap->block(
    __("<strong>%s uses cookies!</strong> This site requires cookies to work. We only set one cookie ('%s') which is required for you to use %s. If you do not accept this, then please delete the cookie manually and leave the site.",
	$sourcekettle_config['UserInterface']['alias']['value'],
	h($cookie),
	$sourcekettle_config['UserInterface']['alias']['value']
	),
    array(
        "style" => "info",
        "closable" => true
    )
);
