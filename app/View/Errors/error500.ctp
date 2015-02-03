<?php
/**
 *
 * APP/View/Error/error500 for the SourceKettle system
 * Shows an error when something has gone wrong
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Errors
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$this->layout = 'error';

?>
<h1>Oh Crikey, Batman! <small>An Internal Error Has Occurred!</small></h1>
<div class="row-fluid">
    <div class="span12">
        <div class="well">
            <h2>Darn! This is horribly embarrassing...</h2>
            <h4>Whatever you've requested has gone and caused a pesky error in the system.</h4>
            <h5>If the problem persists then contact your systems administrator</h5>
        </div>
    </div>
</div>
<?php
if (Configure::read('debug') > 0 ):
	echo $this->element('exception_stack_trace');
endif;
?>
