<?php
/**
 *
 * Email template for password recovery for the SourceKettle system
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

<?=__("A request to reset your password was made, if this was by you then please click the link below within the next 30 minutes.")?>

<?= $this->Html->url('/users/reset_password/'.$Key['LostPasswordKey']['key'], true) ?>

<?=__("This definitely wasn't you?")?>
<?=__("Then please ignore this email and the key will expire shortly.")?>

<?=__("Not again?!")?>
<?=__("If this keeps happening then please reply to this email accordingly.")?>

<?=__("Regards,")?>
  <?=__("The %s Team", $sourcekettle_config['UserInterface']['alias'])?>
