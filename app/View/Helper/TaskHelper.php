<?php
/**
 *
 * Helper class for Tasks section of the DevTrack system
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

class TaskHelper extends AppHelper {

    /**
     * helpers
     *
     * @var string
     * @access public
     */
    var $helpers = array('Html', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'));

    /**
     * priority function.
     *
     * @access public
     * @param mixed $id
     * @return void
     */
    public function priority($id) {
        $text = array(
            1 => 'Minor',
            2 => 'Major',
            3 => 'Urgent',
            4 => 'Blocker',
        );
        $content = array(
            1 => 'download',
            2 => 'upload',
            3 => 'exclamation-sign',
            4 => 'ban-circle',
        );
        return $this->Bootstrap->label($text[$id].' '.$this->Bootstrap->icon($content[$id], "white"), "inverse");
    }

    /**
     * status function.
     *
     * @access public
     * @param mixed $id
     * @return void
     */
    public function status($id) {
        $text = array(
            1 => 'Open',
            2 => 'In Progress',
            3 => 'Resolved',
            4 => 'Closed',
        );
        $colour = array(
            1 => 'success',
            2 => 'primary',
            3 => 'info',
            4 => 'danger',
        );
        //return $this->Bootstrap->button($text[$id], array('class' => 'btn-mini disabled btn-'.$colour[$id]));
        return $text[$id];
    }

    /**
     * Get a label to show the status of a task
     * 
     * @access public
     * @param mixed $id
     * @return void
     */
    public function statusLabel($id) {
        $text = array(
            1 => 'Open',
            2 => 'In Progress',
            3 => 'Resolved',
            4 => 'Closed',
        );
        $colour = array(
            1 => 'important',
            2 => 'warning',
            3 => 'success',
            4 => '',
        );
        return $this->Bootstrap->label($text[$id], $colour[$id]);
    }

    /**
     * type function.
     *
     * @access public
     * @param mixed $id
     * @return void
     */
    public function type($id) {
        $colour = array(
            1 => 'important',
            2 => 'warning',
            3 => 'success',
            4 => '',
            5 => 'info',
            6 => 'inverse',
        );
        $text = array(
            1 => 'bug',
            2 => 'duplicate',
            3 => 'enhancement',
            4 => 'invalid',
            5 => 'question',
            6 => 'wontfix',
        );
        return $this->Bootstrap->label($text[$id], $colour[$id]);
    }
}
