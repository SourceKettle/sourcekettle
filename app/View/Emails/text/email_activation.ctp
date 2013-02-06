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
Dear <?= $User['User']['name'] ?>,

Thank you for registering with SourceKettle. 
In order to use your account, we require you to activate your account using the link below:

<?= $this->Html->url('/activate/'.$Key, true) ?>

We hope you enjoy using SourceKettle!

Regards,
  The SourceKettle Team
