<?php
/**
 *
 * View class for APP/users/admin_add for the SourceKettle system
 * View allow admin to create a new user
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
?>

<div class="row-fluid">
    <?php
    echo $this->Form->create('User', array('class' => 'span7 well form-horizontal', 'url' => array('action' => 'invite')));

    echo '<h3>New users details</h3>';

    echo $this->Bootstrap->input("name", array(
        "input" => $this->Form->text("name", array("class" => "span11")),
    ));

    echo $this->Bootstrap->input("email", array(
        "input" => $this->Form->text("email", array("class" => "span11")),
    ));

    echo $this->Bootstrap->button("Create", array("style" => "primary", "size" => "large", 'class' => 'controls'));

    echo $this->Form->end();
    ?>
</div>
