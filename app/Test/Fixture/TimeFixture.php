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
            'description' => 'A description.',
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
            'description' => 'A description of the second <b>task</b>.',
            'date' => '2012-11-11',
            'created' => '2012-11-11 10:24:06',
            'modified' => '2012-11-11 10:24:06',
			'deleted' => '0',
			'deleted_date' => null
        ),
    );
}
