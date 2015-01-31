<?php
/**
 *
 * APP/View/Error/error400 for the SourceKettle system
 * Shows an error when something is not found
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
<div class="page-header">
  <h1>Error, Will Robinson, ERROR!</h1>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="well error">
            <h2><?=h($name)?></h2>
            <h5>If the problem persists then contact your systems administrator</h5>
        </div>
    </div>
</div>
<?php
if (Configure::read('debug') > 0 ):
	echo $this->element('exception_stack_trace');
endif;
?>
