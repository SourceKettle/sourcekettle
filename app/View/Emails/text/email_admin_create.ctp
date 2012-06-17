<?php
/**
 *
 * Email template for admin created accounts for the DevTrack system
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

One of the wonderful Administrators over at <?= $this->Html->url('/', true) ?> has given you an account!
Head over to the following URL to change your password and get started:

<?= $this->Html->url('/users/reset_password/'.$Key['LostPasswordKey']['key'], true) ?>

Regards,
  The Devtrack Team