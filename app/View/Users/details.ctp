<?php
/**
 *
 * View class for APP/users/details for the SourceKettle system
 * Displays a form to let the user update their account details.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Users
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
echo $this->Bootstrap->page_header($this->request->data['User']['name']) ?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/users') ?>
    </div>
    <div class="span6 offset1">
        <?php
        echo $this->Form->create('User', array('class' => 'well form-horizontal', 'type' => 'post'));
        echo '<h3>Edit your details</h3>';
        echo $this->Bootstrap->input("name", array(
            "input" => $this->Form->text("name"),
        ));

        if ($current_user['__is_internal']) {
            echo $this->Bootstrap->input("email", array(
                "input" => $this->Form->text("email"),
            ));
        }
        echo $this->Bootstrap->button("Update", array("style" => "primary", "size" => "large", 'class' => 'controls'));

        echo $this->Form->end();
        ?>
    </div>
</div>
