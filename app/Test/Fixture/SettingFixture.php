<?php

class SettingFixture extends CakeTestFixture {

	public $import = array('model' => 'Setting');

    public $records = array(
        array(
            'id' => 1,
            'name' => 'sync_required',
            'value' => 0,
            'created' => '2012-06-02 20:05:59',
            'modified' => '2012-06-02 20:05:59'
        ),
    );
}
