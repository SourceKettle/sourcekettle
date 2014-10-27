<?php

class LostPasswordKeyFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
        'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 25, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
    );

    public $records = array();

	public function __construct() {

		// We need to check that key expiry works, so we will create keys with
		// recent and expired creation timestamps on startup.

		// TODO hard coded expiration length
		$dateRecent  = new DateTime('now', new DateTimeZone('UTC'));
		$dateExpired = clone($dateRecent);
		$dateExpired->sub(new DateInterval('PT18005S'));

		$this->records[] = array(
            'id' => 1,
            'user_id' => 1,
            'key' => 'ab169f5ff7fbbcdd7db9bd077',
            'created' =>  $dateExpired->format('Y-m-d H:i:S'),
            'modified' => $dateExpired->format('Y-m-d H:i:S'),
		);

		$this->records[] = array(
            'id' => 2,
            'user_id' => 1,
            'key' => 'ab169f5ff7fbbcdd7db9bd078',
            'created' =>  $dateRecent->format('Y-m-d H:i:S'),
            'modified' => $dateRecent->format('Y-m-d H:i:S'),
		);
		parent::__construct();
	}
}
