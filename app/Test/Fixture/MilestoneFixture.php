<?php
/**
 * MilestoneFixture
 *
 */
class MilestoneFixture extends CakeTestFixture {

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
			'due' => '2013-05-24',
			'is_open' => true,
            'created' => '2012-06-02 20:05:59',
            'modified' => '2012-06-02 20:05:59'
        ),
    );

}
