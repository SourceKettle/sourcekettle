<?php
/**
 *
 * Tempo display for APP/times/history for the DevTrack system
 * Shows a table of time vs. tasks
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Time
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


$headers = array(__('Task'), __('User'));
foreach ($weekTimes['dates'] as $daynum => $date){
	$headers[] = __($date->format('D M d Y'));
}
$headers[] = __('Total');

$body = array($headers);

$overallTotal = 0;
foreach ($weekTimes['tasks'] as $taskId => $taskDetails) {
	foreach ($taskDetails['users'] as $userId => $userDetails) {
		$line = array();

		if ($taskId == 0) {
			$line[] = __("No associated task");

		} else {
			$line[] = $taskDetails['Task']['subject'];
		}

		$line[] = $userDetails['User']['name'];

		// 1=Mon, 7=Sun...
		$rowTotal = 0;
		for ($i = 1; $i <= 7; $i++) {
			if (array_key_exists($i, $userDetails['days'])) {
				$line[] = $userDetails['days'][$i];
				$rowTotal += $userDetails['days'][$i];
			} else {
				$line[] = '';
			}
		}

		$line[] = $rowTotal;
		$overallTotal += $rowTotal;

		$body[] = $line;
	}
}

$foot = array(__('Total'), '');
for ($i = 1; $i <= 7; $i++) {
	if (array_key_exists($i, $weekTimes['totals'])) {
		$foot[] = $weekTimes['totals'][$i];
	} else {
		$foot[] = '';
	}
}
$foot[] = $overallTotal;

$body[] = $foot;

$stdout = fopen('php://output', 'w');
foreach ($body as $line) {
	fputcsv($stdout, $line);
}

