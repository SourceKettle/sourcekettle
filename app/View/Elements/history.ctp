<?php
/*
 * Stores the display preferences for the activity blocks
 */
$prefs = array(
    'Collaborator' => array('icon' => 'user', 'color' => 'warning'),
    'Time'         => array('icon' => 'time', 'color' => 'info'),
    'Source'       => array('icon' => 'pencil', 'color' => 'success'),
    'Task'         => array('icon' => 'file', 'color' => 'important'),
    'Milestone'    => array('icon' => 'road', 'color' => ''),
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

	$this->History->render($event);
}
