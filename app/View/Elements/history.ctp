<?php
/*
 * Stores the display preferences for the activity blocks
 */
$prefs = array(
    'Collaborator' => array('icon' => 'user', 'color' => 'warning'),
    'Time'         => array('icon' => 'time', 'color' => 'info'),
    'Source'       => array('icon' => 'pencil', 'color' => 'success'),
    'Task'         => array('icon' => 'file', 'color' => 'important'),
);

// Pick a date that cant have happened yet
$currentDate = strtotime('+1 Day');

// Number of events shown
$number = 0;

foreach ( $events as $event ) {

    // If we change day, print out a date header
    $newDate = strtotime($event['modified']);
    if (date('Y-m-d', $currentDate) != date('Y-m-d', $newDate)) {
        echo '<p>';
        echo '<strong>';
        echo date('l jS \of F Y', strtotime($event['modified']));
        echo '</strong>';
        echo '</p>';
        $currentDate = $newDate;
    }

    // Create Actioner String/Link if exists
    if ( $event['Actioner']['exists'] ) {
        $user = $this->Html->link($event['Actioner']['name'], array('controller' => 'users', 'action' => 'view', $event['Actioner']['id']));
    } else {
        $user = $event['Actioner']['name'];
    }

    // Create Subject String/Link if exists
    if ( $event['Subject']['exists'] ) {
        $subject = $this->Html->link(
            $event['Subject']['title'],
            (isset($event['url'])) ? $event['url'] : array(
                'project' => $event['Project']['name'],
                'controller' => Inflector::pluralize(strtolower($event['Type'])),
                'action' => 'view',
                $event['Subject']['id']
            )
        );
    } else {
        $subject = $event['Subject']['title'];
    }

    // Create Project Link
    $project = $this->Html->link($event['Project']['name'], array('controller' => 'users', 'action' => 'view', $event['Project']['id']));

    // Build the DT plugin request string
    $_dt_string = 'log.'.strtolower($event['Type']).'.';
    switch ( $event['Change']['field'] ) {
        case '+':
            $_dt_string .= 'created';
            break;
        case '-':
            $_dt_string .= 'deleted';
            break;
        default;
            $_dt_string .= 'updated';
    }
    $_dt_string .= ((isset($context_global)) ? '.context' : '');

    echo '<p>';

    echo $this->Gravatar->image($event['Actioner']['email'], array('size' => 30), array('alt' => $event['Actioner']['name']));

    echo ' ';

    echo $this->Bootstrap->label(
        $this->Bootstrap->icon($prefs[$event['Type']]['icon'], "white"),
        $prefs[$event['Type']]['color']
    );

    echo ' ';

    $find = array('{subject}','{actioner}','{project}','{field}','{old}','{new}');
    $repl = array($subject, $user, $project, $event['Change']['field'], $event['Change']['field_old'], $event['Change']['field_new']);
    echo str_replace($find, $repl, $this->DT->t($_dt_string, array('controller' => 'all', 'action' => 'history')));
    if (isset($event['detailString'])) {
        echo ' '.str_replace($find, $repl, $this->DT->t($event['detailString'], array('controller' => 'all', 'action' => 'history')));
    }
    echo ' - ';

    echo '<small>'.date('H:i', strtotime($event['modified'])).'</small>';

    echo '</p>';

    // Number of events to display
}
