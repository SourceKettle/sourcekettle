<?php
/**
 *
 * Element for displaying a collaborator in the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Element.Collaborator
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$_levels = array(
    '0' => 'Guest',
    '1' => 'User',
    '2' => 'Admin'
);

?>

<div class='span4 well'>
  <div class='row'>
    <div class='span1'>
      <?= $this->Gravatar->image(
          $collaborator['User']['email'],
          array('size' => 100),
          array('alt' => $collaborator['User']['name'])
      ) ?>
    </div>

    <div class='span3'>
      <?= $this->Html->link($collaborator['User']['name'], array('controller' => 'users', 'action' => 'view', $collaborator['User']['id']))?>
      <br>
      <?= h($collaborator['User']['email']) ?>
      <br>
      <span class='muted'><?= h($_levels[$collaborator['Collaborator']['access_level']]) ?></span>
    </div>
  </div>
</div>
