<?php
/**
 * MilestoneFixture
 *
 */
class MilestoneFixture extends CakeTestFixture {

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
	public $import = array('model' => 'Milestone');

    public $records = array(
        array(
            'id' => 1,
			'project_id' => 2,
            'subject' => 'Sprint 1',
            'description' => 'Short description here',
			'starts' => '2013-01-02',
			'due' => '2013-01-24',
			'is_open' => true,
            'created' => '2012-06-02 20:05:59',
            'modified' => '2012-06-02 20:05:59'
        ),
        array(
            'id' => 2,
			'project_id' => 2,
            'subject' => 'Sprint 2',
            'description' => '<b>Foo</b>',
			'starts' => '2012-12-02',
			'due' => '2013-01-01',
			'is_open' => false,
            'created' => '2012-08-02 20:05:59',
            'modified' => '2012-09-02 20:05:59'
        ),
        array(
            'id' => 3,
			'project_id' => 2,
            'subject' => 'Longer <i>subject</i>',
            'description' => 'Short description here',
			'starts' => '2013-05-02',
			'due' => '2013-05-24',
			'is_open' => true,
            'created' => '2012-06-02 20:05:59',
            'modified' => '2012-06-02 20:05:59'
        ),
        array(
            'id' => 4,
			'project_id' => 1,
            'subject' => 'Longer <i>subject</i>',
            'description' => 'Short description here',
			'starts' => '2013-05-02',
			'due' => '2013-05-24',
			'is_open' => true,
            'created' => '2012-06-02 20:05:59',
            'modified' => '2012-06-02 20:05:59'
        ),
        array(
            'id' => 5,
			'project_id' => 1,
            'subject' => 'Longer <i>subject</i>',
            'description' => 'Short description here',
			'starts' => '2013-05-02',
			'due' => '2013-05-24',
			'is_open' => false,
            'created' => '2012-06-02 20:05:59',
            'modified' => '2012-06-02 20:05:59'
        ),
    );

}
