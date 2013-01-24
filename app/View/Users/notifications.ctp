<?php
/**
 *
 * View class for APP/users/notifications for the DevTrack system
 * Displays a form to let the user update their notification preferences.
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
echo $this->Bootstrap->page_header($this->request->data['User']['name']); ?>


<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    
    <div class="span6">
        <?php
        echo $this->Form->create('NotificationSetting', array('class' => 'well form-horizontal', 'type' => 'post'));
        echo '<h3>Change your notification preferences</h3>';

        echo $this->Bootstrap->radio("email_notifications", array(
            "options" => array(
                true => "Yes",
                false => "No",
            )
        ));

        echo "For the projects I am a collaborator on, notify me for:";
        echo $this->Bootstrap->radio("all_notifications", array(
            "label" => "&nbsp;",
            "options" => array(
                true => "All events",
                false => "Events on objects I have interacted with",
            )
        ));

        echo $this->Bootstrap->button("Save", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
    <div class="span4">
        <h3>Notification preferences?!</h3>
        <p>
            When actions are performed by others, sometimes it's nice to know what they did. That's where notifications come in. We can generate notifications for a number of events such as tasks being created, tasks being commented on and a variety of other things. 
        </p>

        <p>
            We don't pretend to know what you want though, so here you can edit your notification preferences to receive the notifications you want and in the method you want.
        </p>

        <dl>
            <dt>
                All events
            </dt>
            <dd>
                Notify you for all events on a project.
            </dd>

            <dt>
                Events on objects I have interacted with
            </dt>
            <dd>
                Notify you for all events on a project on where you have been involved with the object (such as a task) before.
            </dd>
        </dl>
    </div>
</div>
