<?php

class UserFixture extends CakeTestFixture {

	public $import = array('model' => 'User');

    public $records = array(
        array(
            'id' => 1,
            'name' => 'Mr Smith',
            'email' => 'Mr.Smith@example.com',
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
            'is_admin' => 1,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 6,
            'name' => 'Sir Not-Appearing-In-This-Film',
            'email' => 'snaitf@example.com',
            'password' => '', // e.g. LDAP account
            'is_admin' => 0,
            'is_active' => 0,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 7,
            'name' => 'A Deletable User',
            'email' => 'deletable@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 8,
            'name' => 'An only-admin',
            'email' => 'only-admin@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 9,
            'name' => 'An admin with no projects',
            'email' => 'admin-no-projects@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 1,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
    );
}
