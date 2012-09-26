<?php
/**
 *
 * Email template for password recovery for the DevTrack system
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

A request to reset your password was made, if this was by you then please click the link below within the next 30 minutes.

<?= $this->Html->url('/users/reset_password/'.$Key['LostPasswordKey']['key'], true) ?>

This definitely wasn't you?
Then please ignore this email and the key will expire shortly.

Not again?!
If this keeps happening then please reply to this email accordingly.

Regards,
  The Devtrack Team
