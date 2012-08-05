<?php
/**
 *
 * Helper class for Project Activity for the DevTrack system
 * Helper class can output HTML based upon type of event
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

class ProjectActivityHelper extends AppHelper {

    var $helpers = array('Time', 'Html', 'Bootstrap' => array('className' => 'TwitterBootstrap.TwitterBootstrap'));

    /*
     * Stores the display preferences for the activity blocks
     */
    var $prefs = array(
        'Collaborator' => array('icon' => 'user', 'color' => 'warning'),
        'Time'         => array('icon' => 'time', 'color' => 'info'),
        'Source'       => array('icon' => 'pencil', 'color' => 'success'),
    );

    /**
     * displayActivity
     * Will coutput the content activity blocks
     *
     * @param $events array events to display
     * @param $context boolean do we need context
     *
     * @return string the elements to print
     */
    function displayActivity( $events = array(), $context = false ) {
        $return = '';

        // Pick a date that cant have happened yet
        $currentDate = strtotime('+1 Day');

        // Number of events shown
        $number = 0;

        foreach ( $events as $event ) {

            // If we change day, print out a date header
            $newDate = strtotime($event['modified']);
            if (date('Y-m-d', $currentDate) != date('Y-m-d', $newDate)) {
                $return .= '<p><strong>'.date('l jS \of F Y', strtotime($event['modified'])).'</strong></p>';
                $currentDate = $newDate;
            }

            $user = $this->Html->link($event['user_name'], array('controller' => 'users', 'action' => 'view', $event['user_id']));
            if ($context) $project = $this->Html->link($event['project_name'], array('controller' => 'users', 'action' => 'view', $event['project_id']));

            // Print out specific content for events
            switch ($event['Type']) {
                case 'Collaborator':
                    $return .= '<p>' . $this->content_collaborator($event, $context) . ' - <small>'.$this->Time->timeAgoInWords($event['modified']).'</small></p>';
                    break;
                case 'Commit':
                    $return .= '<p>' . $this->content_commit($event, $context) . ' - <small>'.$this->Time->timeAgoInWords($event['modified']).'</small></p>';
                    break;
                case 'Time':
                    $return .= '<p>' . $this->content_time($event, $context) . ' - <small>'.$this->Time->timeAgoInWords($event['modified']).'</small></p>';
                    break;
                case 'Task':
                    break;
                case 'Wiki':
                    break;
                case 'Download':
                    break;
            }
            // Number of events to display
            if (++$number >= 10) break;
        }
        return $return;
    }

    /**
     * content_collaborator
     * Will output the content row for a collaborator block
     *
     * @param $event array event to display
     * @param $context boolean do we need context
     *
     * @return string the element to print
     */
    private function content_collaborator ( $event, $context ) {
        $return = '';

        if ($event['user_id'] == 0) {
            $user = $event['user_name'];
        } else {
            $user = $this->Html->link($event['user_name'], array('controller' => 'users', 'action' => 'view', $event['user_id']));
        }
        $project = $this->Html->link($event['project_name'], array('project' => $event['project_name'], 'controller' => 'projects', 'action' => 'view'));

        $return .= $this->Bootstrap->label("Collaborator ".$this->Bootstrap->icon($this->prefs['Collaborator']['icon'], "white"), $this->prefs['Collaborator']['color']);
        $return .= ' '.$user.' was added';
        if ($context) $return .= ' to '.$project;
        $return .= ' as a collaborator';

        return $return;
    }

    /**
     * content_commit
     * Will output the content row for a commit block
     *
     * @param $event array event to display
     * @param $context boolean do we need context
     *
     * @return string the element to print
     */
    private function content_commit ( $event, $context ) {
        $return = '';

        if ($event['user_id'] == 0) {
            $user = $event['user_name'];
        } else {
            $user = $this->Html->link($event['user_name'], array('controller' => 'users', 'action' => 'view', $event['user_id']));
        }
        $project = $this->Html->link($event['project_name'], array('project' => $event['project_name'], 'controller' => 'projects', 'action' => 'view'));
        $commit = $this->Html->link('['.substr($event['message'], 0, 42).'...]', array('project' => $event['project_name'], 'controller' => 'source', 'action' => 'commit', $event['hash']));

        $return .= $this->Bootstrap->label("Source ".$this->Bootstrap->icon($this->prefs['Source']['icon'], "white"), $this->prefs['Source']['color']);
        $return .= ' '.$user.' commited';
        if ($context) $return .= ' to '.$project;
        $return .= ' '.$commit;

        return $return;
    }

    /**
     * content_time
     * Will output the content row for a time block
     *
     * @param $event array event to display
     * @param $context boolean do we need context
     *
     * @return string the element to print
     */
    private function content_time ( $event, $context ) {
        $return = '';

        $user = $this->Html->link($event['user_name'], array('controller' => 'users', 'action' => 'view', $event['user_id']));
        $project = $this->Html->link($event['project_name'], array('project' => $event['project_name'], 'controller' => 'projects', 'action' => 'view'));
        $time = $this->Html->link('time', array('project' => $event['project_name'], 'controller' => 'times', 'action' => 'view', $event['time_id']));
        $return .= $this->Bootstrap->label("Time ".$this->Bootstrap->icon($this->prefs['Time']['icon'], "white"), $this->prefs['Time']['color']);
        $return .= " $user logged some $time";
        if ($context) $return .= ' on '.$project;

        return $return;
    }

}
