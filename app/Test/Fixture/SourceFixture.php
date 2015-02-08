<?php
/**
 * SourceFixture
 *
 */
class SourceFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

/**
 * Table name
 *
 * @var string
 */
	public $table = 'source';

	public $import = array('model' => 'Source');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'project_id' => 1
			
		),
	);
}
