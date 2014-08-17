<?php

class TimeFixture extends CakeTestFixture {

	public $import = array('model' => 'Time');

    public $records = array(
        array(
            'id' => 1,
            'project_id' => 1,
            'user_id' => 1,
            'task_id' => 1,
            'mins' => 90,
            'description' => 'A description for the first time.',
            'date' => '2012-11-11',
            'created' => '2012-11-11 10:24:06',
            'modified' => '2012-11-11 10:24:06',
			'deleted' => '0',
			'deleted_date' => null
        ),
        array(
            'id' => 2,
            'project_id' => 1,
            'user_id' => 1,
            'task_id' => 2,
            'mins' => 900,
            'description' => 'A description of the second <b>time</b>.',
            'date' => '2012-11-12',
            'created' => '2012-11-12 10:24:06',
            'modified' => '2012-11-12 10:24:06',
			'deleted' => '0',
			'deleted_date' => null
        ),
        array(
            'id' => 3,
            'project_id' => 1,
            'user_id' => 2,
            'task_id' => 2,
            'mins' => 14,
            'description' => 'A description of the third <b>time</b>.',
            'date' => '2012-11-13',
            'created' => '2012-11-13 10:24:06',
            'modified' => '2012-11-13 10:24:06',
			'deleted' => '0',
			'deleted_date' => null
        ),
        array(
            'id' => 4,
            'project_id' => 1,
            'user_id' => 3,
            'task_id' => 1,
            'mins' => 19,
            'description' => 'A description of the fourth <b>time</b>.',
            'date' => '2012-11-11',
            'created' => '2012-11-11 10:24:06',
            'modified' => '2012-11-11 10:24:06',
			'deleted' => '0',
			'deleted_date' => null
        ),
        array(
            'id' => 5,
            'project_id' => 3,
            'user_id' => 2,
            'task_id' => 1,
            'mins' => 15,
            'description' => 'A description of the fifth <b>time</b>.',
            'date' => '2012-11-11',
            'created' => '2012-11-11 10:24:06',
            'modified' => '2012-11-11 10:24:06',
			'deleted' => '0',
			'deleted_date' => null
        ),
    );
}
