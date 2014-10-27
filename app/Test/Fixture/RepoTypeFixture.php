<?php
/**
 * RepoTypeFixture
 *
 */
class RepoTypeFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

	public $import = array('model' => 'RepoType');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'name' => 'None',
			'created' => '2012-06-01 12:46:57',
			'modified' => '2012-06-01 12:46:57'
		),
		array(
			'id' => 2,
			'name' => 'Git',
			'created' => '2012-06-01 12:46:57',
			'modified' => '2012-06-01 12:46:57'
		),
		array(
			'id' => 3,
			'name' => 'SVN',
			'created' => '2012-06-01 12:46:57',
			'modified' => '2012-06-01 12:46:57'
		),
	);
}
