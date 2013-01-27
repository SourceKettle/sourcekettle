<?php
/**
 *
 * APP/View/Error/not_found for the DevTrack system
 * Shows an error when something is not found
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Errors
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Ello Ello Ello<small> whats going on ere\' then?</small>');

?>
<div class="row">
    <div class="span12">
        <div class="well">
            <h2>Darn!</h2>
            <h3>This is not the location you are looking for...</h3>
            <h4>Whatever you've requested has gone and caused a pesky error in the system.</h4>
            <p>Dont worry! We're making sure it wasn't us by realigning our flux capacitors and what-not.</p>
        </div>
    </div>
</div>
