<?php

class UserFixture extends CakeTestFixture {

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
        'email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
        'password' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
        'is_admin' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
        'is_active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
    );

    public $records = array(
        array(
            'id' => 1,
            'name' => 'Mr Smith',
            'email' => 'mr.smith@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 2,
            'name' => 'Mrs Smith',
            'email' => 'mrs.smith@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 3,
            'name' => 'Mrs Guest',
            'email' => 'mrs.guest@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 4,
            'name' => 'Mr User',
            'email' => 'mr.user@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 5,
            'name' => 'Mr Admin',
            'email' => 'mr.admin@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
    );
}
