<?php
/**
 *
 * Tempo display for APP/times/history for the SourceKettle system
 * Shows a table of time vs. tasks
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Time
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

//debug($weekTimes);
$headers = array(__('Task'), __('User'));
foreach ($weekTimes['dates'] as $daynum => $date){
	$headers[] = __($date->format('D M d Y'));
}
$headers[] = __('Total');

$body = array($headers);

$overallTotal = 0;
foreach ($weekTimes['tasks'] as $taskId => $taskDetails) {
	foreach ($taskDetails['users'] as $userId => $row) {
		$userDetails = $row['User'];
		$timeDetails = $row['times_by_day'];

		$line = array();

		if ($taskId == 0) {
			$line[] = __("No associated task");

		} else {
			$line[] = $taskDetails['Task']['subject'];
		}

		$line[] = $userDetails['name'];

		// 1=Mon, 7=Sun...
		$rowTotal = 0;
		for ($i = 1; $i <= 7; $i++) {
			if (array_key_exists($i, $timeDetails)) {
				$day_total = array_reduce($timeDetails[$i], function($total, $time) {$total += $time['Time']['mins']; return $total;});
				$line[] = $day_total;
				$rowTotal += $day_total;
			} else {
				$line[] = 0;
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

