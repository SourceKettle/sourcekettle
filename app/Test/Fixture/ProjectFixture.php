<?php

class ProjectFixture extends CakeTestFixture {

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
        'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
        'public' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
        'repo_type' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
    );

    public $records = array(
        array(
            'id' => 1,
            'name' => 'private',
            'description' => 'desc',
            'public' => 0,
            'repo_type' => 1,
            'created' => '2012-06-01 12:46:07',
            'modified' => '2012-06-01 12:46:07'
        ),
        array(
            'id' => 2,
            'name' => 'public',
            'description' => 'desc',
            'public' => 1,
            'repo_type' => 1,
            'created' => '2012-06-01 12:46:07',
            'modified' => '2012-06-01 12:46:07'
        ),
        array(
            'id' => 3,
            'name' => 'personal',
            'description' => 'Mr Smith\'s personal project',
            'public' => 0,
            'repo_type' => 1,
            'created' => '2012-06-01 12:46:07',
            'modified' => '2012-06-01 12:46:07'
        ),
    );
}
