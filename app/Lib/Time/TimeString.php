<?php

// Time parse/render functions for the Time and Task
// classes
class TimeString{

	// Given a time in minutes, render it as
	// a nicely formatted time interval e.g. "2h 6m"
	function renderTime($minutes, $field = 'all'){
		if ($minutes == null) {
			$minutes = 0;
		}

		if (!is_numeric($minutes)) {
			throw new InvalidArgumentException("Minutes must be an integer: ${minutes} given");
		}

		$weeks = 0;
		$days = 0;
		$hours = 0;
		$mins = (int)$minutes;

		while ($mins >= 60) {
			$hours += 1;
			$mins -= 60;
		}

		while ($hours >= 24) {
			$days += 1;
			$hours -= 24;
		}

		while ($days >= 7) {
			$weeks += 1;
			$days  -= 7;
		}

		// Output: array of individual fields for the parsed bits,
		// t is the original timestamp,
		// s is the full string
		$output = array(
			'w' => $weeks,
			'd' => $days,
			'h' => $hours,
			'm' => $mins,
			't' => (int)$minutes,
			's' => "${hours}h ${mins}m",
		);

		if ($weeks > 0) {
			$output['s'] = "${weeks}w ${days}d ${hours}h ${mins}m";
		} elseif ($days > 0) {
			$output['s'] = "${days}d ${hours}h ${mins}m";
		}

		if (array_key_exists($field, $output)) {
			return $output[$field];
		}

		return $output;

	}

	// Given a string representing a time interval,
	// parse it and return a number of minutes.
	function parseTime($timeString){

		// Easy case - just a number of minutes :-)
		if(is_numeric($timeString)){
			return (int)$timeString;
		}

		// Pick out bits of timey-wimey looking things.
		// Stick to minutes, hours, days and weeks (all known lengths).
		// Months are a bit variable, so let's pretend they don't exist.
		preg_match("#(?P<weeks>[0-9]+)\s*w(eeks?)?#", $timeString, $weeks);
		preg_match("#(?P<days>[0-9]+)\s*d(ays?)?#", $timeString, $days);
		preg_match("#(?P<hours>[0-9]+)\s*h(rs?|ours?)?#", $timeString, $hours);
		preg_match("#(?P<mins>[0-9]+)\s*m(ins?)?#", $timeString, $mins);

		$time = (int)0;
		$time += ((isset($weeks['weeks'])) ? 7 * 24 * 60 * (int)$weeks['weeks'] : 0);
		$time += ((isset($days['days']))   ? 24 * 60 * (int)$days['days']       : 0);
		$time += ((isset($hours['hours'])) ? 60 * (int)$hours['hours']          : 0);
		$time += ((isset($mins['mins']))   ? (int)$mins['mins']                 : 0);
		return $time;
	}

}
