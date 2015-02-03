<?php
App::uses('Security', 'Utility');

class UserFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

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
        array(
            'id' => 10,
            'name' => 'Another user',
            'email' => 'another-user@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 11,
            'name' => 'A non-confirmed user',
            'email' => 'non-confirmed@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 0,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 12,
            'name' => 'A user',
            'email' => 'user-@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 13,
            'name' => 'A PHP developer',
            'email' => 'php-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 14,
            'name' => 'A Java developer',
            'email' => 'java-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 15,
            'name' => 'A Python developer',
            'email' => 'python-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 16,
            'name' => 'A PHP and Python developer',
            'email' => 'php-and-python-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 17,
            'name' => 'A PHP and Java developer',
            'email' => 'php-and-java-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 18,
            'name' => 'A Python and Java developer',
            'email' => 'python-and-java-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 19,
            'name' => 'A PHP, Python and Java developer',
            'email' => 'php-python-and-java-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 20,
            'name' => 'A Perl developer',
            'email' => 'perl-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 21,
            'name' => 'Another Perl developer',
            'email' => 'another-perl-dev@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 22,
            'name' => 'An inactive admin user',
            'email' => 'inactive-admin@example.com',
            'password' => 'Lorem ipsum dolor sit amet',
            'is_admin' => 1,
            'is_active' => 0,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
        array(
            'id' => 23,
            'name' => 'An external account',
            'email' => 'ldap-user@example.com',
            'password' => '', // e.g. LDAP account
            'is_admin' => 0,
            'is_active' => 1,
            'created' => '2012-06-01 12:50:08',
            'modified' => '2012-06-01 12:50:08'
        ),
    );

	public function init() {
		$this->records[] = array('id' => 24, 'name' => 'Account with real password', 'email' => 'realperson@example.com', 'password' => Security::hash('RealGoodPassword', 'sha256', true), 'is_admin' => 0, 'is_active' => 1, 'created' => '2012-06-01 12:50:08', 'modified' => '2012-06-01 12:50:08');
		$this->records[] = array('id' => 25, 'name' => 'Inactive account with real password', 'email' => 'inactiverealperson@example.com', 'password' => Security::hash('RealGoodPassword', 'sha256', true), 'is_admin' => 0, 'is_active' => 0, 'created' => '2012-06-01 12:50:08', 'modified' => '2012-06-01 12:50:08');
		parent::init();
	}
}
