<?php
/*
 * Stores the display preferences for the activity blocks
 */
$prefs = array(
    'Collaborator' => array('icon' => 'user', 'color' => 'warning'),
    'Time'         => array('icon' => 'time', 'color' => 'info'),
    'Source'       => array('icon' => 'pencil', 'color' => 'success'),
);

$contextStrings = array(
    'Collaborator' => array(
        'updated'   => array("%s's role was updated in %s", "%s's role was updated"),
        'created' => array("%s was added to %s as a collaborator", "%s was added as a collaborator"),
    ),
    'Time' => array(
        'updated' => array("%s updated time spent [%s] on %s", "%s updated time spent [%s]"),
        'created'   => array("%s added time spent [%s] on %s", "%s added time [%s]"),
    ),
    'Source' => array(
        'created'   => array("%s submitted code [%s] on %s", "%s submitted code [%s]"),
    ),
);

// Pick a date that cant have happened yet
$currentDate = strtotime('+1 Day');

// Number of events shown
$number = 0;

foreach ( $events as $event ) {

    // If we change day, print out a date header
    $newDate = strtotime($event['modified']);
    if (date('Y-m-d', $currentDate) != date('Y-m-d', $newDate)) {
        ?>
        <p>
            <strong>
                <?= date('l jS \of F Y', strtotime($event['modified'])) ?>
            </strong>
        </p>
        <?
        $currentDate = $newDate;
    }

    $user = $this->Html->link($event['Actioner']['name'], array('controller' => 'users', 'action' => 'view', $event['Actioner']['id']));

    if (isset($context_global)) {
        $context = 0;
        $project = $this->Html->link($event['Project']['name'], array('controller' => 'users', 'action' => 'view', $event['Project']['id']));
    } else {
        $context = 1;
    }

    $detail = $this->Html->link(substr($event['detail'], 0, 75), $event['url']);
    ?>

    <p>
        <?= $this->Gravatar->image($event['Actioner']['email'], array('size' => 30), array('alt' => $event['Actioner']['name'])) ?>
        <?= $this->Bootstrap->label($this->Bootstrap->icon($prefs[$event['Type']]['icon'], "white"), $prefs[$event['Type']]['color']) ?>
        <? printf($contextStrings[$event['Type']][$event['action']][$context], $user, $detail, $project) ?>
        -
        <small>
            <?= date('H:i', strtotime($event['modified'])) ?>
        </small>
    </p>

    <?
    // Number of events to display
    if (++$number >= 10) break;
}
