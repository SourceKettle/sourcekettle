<?php
/**
 *
 * Renders a message saying the user does not have any projects
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Elements.Project
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
  $config = Configure::read('dtcore');
?>

<div class="alert alert-info<?if(isset($span)){ echo ' span3';}?>">
  <?= $config['pages']['projects']['all']['en']['noprojects.text'] ?>
</div>