<?php
/**
 * MilestoneBurndownLogFixture
 *
 */
class MilestoneBurndownLogFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'MilestoneBurndownLog');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 1,
			'open_task_count' => 1,
			'open_minutes_count' => 1,
			'open_points_count' => 1,
			'closed_task_count' => 1,
			'closed_minutes_count' => 1,
			'closed_points_count' => 1
		),
		array(
			'id' => 2,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 2,
			'open_task_count' => 2,
			'open_minutes_count' => 2,
			'open_points_count' => 2,
			'closed_task_count' => 2,
			'closed_minutes_count' => 2,
			'closed_points_count' => 2
		),
		array(
			'id' => 3,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 3,
			'open_task_count' => 3,
			'open_minutes_count' => 3,
			'open_points_count' => 3,
			'closed_task_count' => 3,
			'closed_minutes_count' => 3,
			'closed_points_count' => 3
		),
		array(
			'id' => 4,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 4,
			'open_task_count' => 4,
			'open_minutes_count' => 4,
			'open_points_count' => 4,
			'closed_task_count' => 4,
			'closed_minutes_count' => 4,
			'closed_points_count' => 4
		),
		array(
			'id' => 5,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 5,
			'open_task_count' => 5,
			'open_minutes_count' => 5,
			'open_points_count' => 5,
			'closed_task_count' => 5,
			'closed_minutes_count' => 5,
			'closed_points_count' => 5
		),
		array(
			'id' => 6,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 6,
			'open_task_count' => 6,
			'open_minutes_count' => 6,
			'open_points_count' => 6,
			'closed_task_count' => 6,
			'closed_minutes_count' => 6,
			'closed_points_count' => 6
		),
		array(
			'id' => 7,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 7,
			'open_task_count' => 7,
			'open_minutes_count' => 7,
			'open_points_count' => 7,
			'closed_task_count' => 7,
			'closed_minutes_count' => 7,
			'closed_points_count' => 7
		),
		array(
			'id' => 8,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 8,
			'open_task_count' => 8,
			'open_minutes_count' => 8,
			'open_points_count' => 8,
			'closed_task_count' => 8,
			'closed_minutes_count' => 8,
			'closed_points_count' => 8
		),
		array(
			'id' => 9,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 9,
			'open_task_count' => 9,
			'open_minutes_count' => 9,
			'open_points_count' => 9,
			'closed_task_count' => 9,
			'closed_minutes_count' => 9,
			'closed_points_count' => 9
		),
		array(
			'id' => 10,
			'timestamp' => '2014-10-26 16:20:53',
			'milestone_id' => 10,
			'open_task_count' => 10,
			'open_minutes_count' => 10,
			'open_points_count' => 10,
			'closed_task_count' => 10,
			'closed_minutes_count' => 10,
			'closed_points_count' => 10
		),
	);

}
