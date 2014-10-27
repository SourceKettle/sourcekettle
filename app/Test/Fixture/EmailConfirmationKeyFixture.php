<?php

class EmailConfirmationKeyFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
        'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
    );

    public $records = array(
        array(
            'id' => 1,
            'user_id' => 1,
            'key' => '306f2dc5c9588616647fe32603fb3991',
            'created' => '2012-06-01 12:33:03',
            'modified' => '2012-06-01 12:33:03'
        ),
        array(
            'id' => 2,
            'user_id' => 11,
            'key' => 'ba6f23c5ce588f16647fe32603fb1593',
            'created' => '2012-06-01 12:33:03',
            'modified' => '2012-06-01 12:33:03'
        ),
    );
}
