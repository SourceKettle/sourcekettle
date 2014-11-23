<?php
/**
 *
 * Email template for account registration for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          https://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Emails.text
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?=__("Dear %s,", $User['User']['name'])?>

<?=__("Thank you for registering with %s.", $sourcekettle_config['UserInterface']['alias'])?>
<?=__("In order to use your account, we require you to activate your account using the link below:")?>

<?= $this->Html->url('/activate/'.$Key, true) ?>

<?=__("We hope you enjoy using %s!", $sourcekettle_config['UserInterface']['alias'])?>

<?=__("Regards,")?>
  <?=__("The %s Team", $sourcekettle_config['UserInterface']['alias'])?>
