<?php
/**
 *
 * View class for APP/collaborators/users for the DevTrack system
 * Shows a graph of user contribution to a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/chrisbulmer/devtrack
 * @package       DevTrack.View.Times
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cache = array();

$smallText = " <small>" . $project['Project']['name'] . " </small>";

echo $this->Bootstrap->page_header("Time Logged For The Project" . $smallText);?>

<div class="row">
    <div class="span2">
        <?= $this->element('Sidebar/project') ?>
    </div>
    <div class="row">
        <?= $this->element('Time/topbar') ?>
        <div class="span10">
            <div class="row">
                <div class="span10" style="text-align:center">
                    <h3>Time Contribution</h3>
<?php
$times = array();
$names = array();

foreach ($users as $user) {
    $times[] = $user['Time'];
    $names[] = $user['User']['name'];
}

echo $this->GoogleChart->create()
    ->setType('pie', array('3d'))
    ->setSize(600, 250)
    ->setMargins(0, 0, 50, 0)
    ->addData($times)
    ->setPieChartLabels($names);
?>
                </div>
<?php
    // Pointers
    $srt = 0;
    $end = 3;

    // Keys array to allow for incremental iteration
    $keys = array_keys($users);

    while ($srt <= sizeof($keys)) {
        echo '<div class="span10">';
        echo '<div class="row-fluid">';

        // Iterate between the pointers
        for ($i = $srt; $i <= $end; $i++){
            if (isset($keys[$i])) {
                $user = $users[$keys[$i]];
                echo $this->element('Time/user_block', array('id' => $keys[$i], 'time' => $user['Time'], 'email' => $user['User']['email'], 'name' => $user['User']['name']));
            }
        }

        echo '</div>';
        echo '</div>';

        $srt += 4;
        $end += 4;
    }
?>
            </div>
        </div>
    </div>
</div>
