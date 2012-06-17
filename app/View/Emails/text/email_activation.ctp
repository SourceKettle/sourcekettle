<?php
/**
 *
 * Email template for account registration for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Emails.text
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
Dear <?= $User['User']['name'] ?>,

Thank you for registering with DevTrack. 
In order to use your account, we require you to activate your account using the link below:

<?= $this->Html->url('/activate/'.$Key, true) ?>

We hope you enjoy using DevTrack!

Regards,
  The Devtrack Team