<?php
/**
 *
 * View class for APP/users/admin_index for the DevTrack system
 * View will render lists of users
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Users
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo $this->Bootstrap->page_header('Administration <small>search for persons of interest</small>'); ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/admin') ?>
    </div>
    <div class="span10">
       <div class="row-fluid">
            <div class="well">
            <h3>Search for a User</h3>
            <form class="well form-search">
                <input type="text" class="span12 search-query" placeholder="Search for a user">
            </form>
            <table class="table table-striped">
                <tbody>
                <? foreach ( $users as $user ) : ?>
                    <tr>
                        <td>
                            <?= $this->Html->link($user['User']['name'], array('action' => 'view', $user['User']['id']))?>
                            &lt;<?= $this->Html->link($user['User']['email'], 'mailto:'.$user['User']['email']) ?>&gt;
                        </td>
                        <td>
                        <?php
                            echo $this->Bootstrap->button_form(
                                $this->Bootstrap->icon('eject', 'white')." Remove",
                                $this->Html->url(array('controller' => 'users', 'action' => 'admin_delete', $user['User']['id']), true),
                                array('escape'=>false, 'style' => 'danger', 'size' => 'mini', 'class' => 'pull-right'),
                                "Are you sure you want to delete " . $user['User']['email'] . "?"
                            );
                        ?>
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
            <?= $this->element('pagination') ?>
            </div>
        </div>
    </div>
</div>
