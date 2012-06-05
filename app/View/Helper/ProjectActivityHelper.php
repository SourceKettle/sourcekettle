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
        
        // Sort function for events
        // assumes $array{ $array{ 'modified' => 'date' }, ... }
        $cmp = function($a, $b) {
            if ($a['modified'] == $b['modified']) return 0;
            if (strtotime($a['modified']) < strtotime($b['modified'])) return 1; 
            return -1;
        };
        usort($events, $cmp);
        
        // Pick a date that cant have happened yet
        $currentDate = strtotime('+1 Day');
        
        foreach ( $events as $event ) {
        
            // If we change day, print out a date header
            if (date('Y-m-d', $currentDate) != date('Y-m-d', strtotime($event['modified']))) {
                $return .= '<p>'.date('l jS \of F Y', strtotime($event['modified'])).'</p>';
            }
            
            $user = $this->Html->link($event['user_name'], array('controller' => 'users', 'action' => 'view', $event['user_id']));
            if ($context) $project = $this->Html->link($event['project_name'], array('controller' => 'users', 'action' => 'view', $event['project_id']));
            
            // Print out specific content for events
            switch ($event['Type']) {
                case 'Collaborator':
                    $return .= '<p>' . $this->content_collaborator($event, $context) . ' - <small>'.$this->Time->timeAgoInWords($event['modified']).'</small></p>';
                    break;
                case 'Commit':
                    break;
                case 'Time':
                    break;
                case 'Task':
                    break;
                case 'Wiki':
                    break;
                case 'Download':
                    break;
            }
        }
        return $return;
    }
    
    /**
     * content_collaborator
     * Will coutput the content row for a collaborator block
     * 
     * @param $event array event to display
     * @param $context boolean do we need context
     *
     * @return string the element to print
     */
    private function content_collaborator ( $event, $context ) {
        $return = '';
        
        $user = $this->Html->link($event['user_name'], array('controller' => 'users', 'action' => 'view', $event['user_id']));
        $project = $this->Html->link($event['project_name'], array('controller' => 'users', 'action' => 'view', $event['project_id']));
            
        $return .= $this->Bootstrap->label("Collaborator ".$this->Bootstrap->icon($this->prefs['Collaborator']['icon'], "white"), $this->prefs['Collaborator']['color']);
        $return .= ' '.$user.' was added';
        if ($context) $return .= ' to '.$project;
        $return .= ' as a collaborator';
        
        return $return;
    }

}
