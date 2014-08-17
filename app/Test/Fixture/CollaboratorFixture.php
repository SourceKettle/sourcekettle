<?php

class CollaboratorFixture extends CakeTestFixture {

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'project_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
        'access_level' => array('type' => 'integer', 'null' => false, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
    );

    public $records = array(
        array(
            'id' => 1,
            'project_id' => 1,
            'user_id' => 1,
            'access_level' => 2,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 2,
            'project_id' => 2,
            'user_id' => 5,
            'access_level' => 1,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 3,
            'project_id' => 1,
            'user_id' => 3,
            'access_level' => 0,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 4,
            'project_id' => 1,
            'user_id' => 4,
            'access_level' => 1,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 5,
            'project_id' => 1,
            'user_id' => 5,
            'access_level' => 2,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 6,
            'project_id' => 2,
            'user_id' => 1,
            'access_level' => 1,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 7,
            'project_id' => 3,
            'user_id' => 7,
            'access_level' => 2,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 8,
            'project_id' => 4,
            'user_id' => 8,
            'access_level' => 2,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 9,
            'project_id' => 2,
            'user_id' => 2,
            'access_level' => 2,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 10,
            'project_id' => 4,
            'user_id' => 1,
            'access_level' => 1,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 11,
            'project_id' => 1,
            'user_id' => 10,
            'access_level' => 1,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        ),
        array(
            'id' => 12,
            'project_id' => 2,
            'user_id' => 8,
            'access_level' => 0,
            'created' => '2012-06-01 12:32:15',
            'modified' => '2012-06-01 12:32:15'
        )
    );
}
