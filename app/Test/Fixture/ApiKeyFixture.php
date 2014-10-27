<?php
/**
 * ApiKeyFixture
 *
 */
class ApiKeyFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

	public $import = array('model' => 'ApiKey');
/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'key' => 'Lorem ipsum dolor ',
			'comment' => 'Lorem ipsum dolor sit amet',
			'created' => '2012-11-04 13:15:28',
			'modified' => '2012-11-04 13:15:28'
		),
	);
}
