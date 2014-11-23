<?php
/**
 *
 * Email template for admin created accounts for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Emails.text
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?=__("Dear %s,", $User['User']['name'])?>

<?=__("One of the wonderful Administrators over at %s has given you an account!", $this->Html->link($sourcekettle_config['UserInterface']['alias'], array('/')))?>
<?=__("Head over to the following URL to change your password and get started:")?>

<?= $this->Html->url('/users/reset_password/'.$Key['LostPasswordKey']['key'], true) ?>

<?=__("Regards,")?>
  <?=__("The %s Team", $sourcekettle_config['UserInterface']['alias'])?>
