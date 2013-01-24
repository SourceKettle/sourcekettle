<?php
/**
 *
 * Helper class for Notifications of the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Helper
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class NotificationHelper extends AppHelper {

    /**
     * helpers
     *
     * @var string
     * @access public
     */
    public $helpers = array('Html', 'Form', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'));


    public function navbarContent($notifications){
        $output = ''; // Output buffer
        $notificationCount = count($notifications);

        if ($notificationCount > 0){
            // If the user has notifications
            $output .= "<li class='dropdown notification-list'>";
            $output .= "<a href='#' id='notification-icon' class='dropdown-toggle' data-toggle='dropdown'>" . $this->Bootstrap->icon("bell", "white"). ' <span id="num-notifications">' . $notificationCount . "</span></a>";

            $output .= '<ul class="dropdown-menu notifications">';
                foreach ($notifications as $notification){
                    $output .= $this->notificationItem($notification);
                }
            $output .= '</ul>';

        }

        return $output;
    }

    private function notificationItem($notification){
        $notification = $notification['Notification'];
        $output = '<li id="notification-' . $notification['id'] . '"><div class="notification-actions">';
       
        $output .= $this->Bootstrap->button_link("View", array('controller' => 'notifications', 'action' => 'view', $notification['id']), array("style" => "info", "size" => "small"));

        $output .= $this->Form->create('Notification', array('type' => 'post', 'url' => array('controller' => 'notifications', 'action' => 'dismiss', $notification['id'])));
        $output .= $this->Form->hidden('id', array("value" => $notification['id']));
        $output .= $this->Bootstrap->button("Dismiss", array("style" => "inverse", "size" => "small"));
        $output .= $this->Form->end();


        $output .= '</div><div class="notification-description">' . $notification['text'] . '</div>';
        $output .= "<div class='clearfix'></div></li>";
        return $output;
    }

}
