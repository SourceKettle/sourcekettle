<?php

class TimeFixture extends CakeTestFixture {

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
        'task_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 10),
        'mins' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
        'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
        'date' => array('type' => 'date', 'null' => false, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
    );

    public $records = array(
        array(
            'id' => 1,
            'project_id' => 1,
            'user_id' => 1,
            'task_id' => null,
            'mins' => 90,
            'description' => 'A description.',
            'date' => '2012-11-11',
            'created' => '2012-11-11 10:24:06',
            'modified' => '2012-11-11 10:24:06'
        ),
    );
}
