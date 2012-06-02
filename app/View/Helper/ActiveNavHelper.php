<?php

/**
 *
 * ActiveNav helper for the DevTrack system
 * Allows for automatic marking of navigation bar items based on the current controller 
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Pages
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ActiveNavHelper extends AppHelper {
    public $helpers = array('Html');
    /**
     * Determines whether a given controller/action is the current page
     * @param type $controller The controller to test for
     * @param type $actions The action to test for
     * @return Boolean Whether the controller/action is active 
     */
    public function isActive($controller, $action = null) {
        if ($action == null){
            return ($controller == $this->params['controller']);
        } else {
            return ($controller == $this->params['controller'] && $action == $this->params['action']);
        }
    }
    
    /**
     * Adds the class 'active' if the link is the current page
     * @param type $controller The controller to link to
     * @param type $text The text to display on the link
     * @param type $action The action to link to
     */
    public function markLink($controller, $text, $action = null){
        if ($action == null){
            if ($this->isActive($controller)){
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo $this->Html->link($text, array('controller' => $controller));
            echo '</li>';
        } else {
            if ($this->isActive($controller, $action)){
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo $this->Html->link($text, array('controller' => $controller, 'action' => $action));
            echo '</li>';
        }
    }

}

?>
