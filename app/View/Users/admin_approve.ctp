<?php
/**
 *
 * View class for APP/users/admin_approve for the SourceKettle system
 * View will show a list of unapproved registrations
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2014
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Users
 * @since         SourceKettle v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div class="row-fluid">
    <?php
        $_approve_icon = $this->Bootstrap->icon('ok');
        $_deny_icon    = $this->Bootstrap->icon('remove');
    ?>
    <table class="well table table-striped">
        <thead>
            <tr>
                <th width="85%"><?=__("User name / email")?></th>
                <th><?=__("Actions")?></th>
            </tr>
        </thead>
        <tbody>
        <? foreach ( $users as $user ) : ?>
            <tr>
                <td>
                    <?= $this->Html->link($user['User']['name'], array('action' => 'view', $user['User']['id']))?>
                    &lt;<?= $this->Html->link($user['User']['email'], 'mailto:'.$user['User']['email']) ?>&gt;
                </td>
                <td>
                <?php

							$_approve_url = $this->Html->url(
								array(
										'controller' => 'users',
										'action' => 'admin_approve',
										$user['EmailConfirmationKey']['key']
									 ), true
							);

                        $_deny_url = $this->Html->url(
								array(
									'controller' => 'users',
									'action' => 'admin_delete',
									$user['User']['id']
								), true
							);


						echo "<div class='btn-group'>\n";
                    echo $this->Bootstrap->button_form(
							$_approve_icon, $_approve_url,
                        array('escape'=>false, 'size' => 'mini', 'class' => '', 'title' => __('Approve registration')),
                        __("Are you sure you want to approve")." " . h($user['User']['email']) . "?"
                    );

                    echo $this->Bootstrap->button_form(
							$_deny_icon, $_deny_url,
                        array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => '', 'title' => __('Deny registration')),
                        __("Are you sure you want to delete")." " . h($user['User']['email']) . "?"
                    );
						echo "</div>\n";
                ?>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</div>
