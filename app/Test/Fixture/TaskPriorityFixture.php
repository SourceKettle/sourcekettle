<?php
/**
 * TaskPriorityFixture
 *
 */
class TaskPriorityFixture extends CakeTestFixture {

	// Force InnoDB table type so we can test transactions
	public function create($db) {
	    $this->fields['tableParameters']['engine'] = 'InnoDB';
	    return parent::create($db);
	}

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'TaskPriority', 'records' => true);
}
