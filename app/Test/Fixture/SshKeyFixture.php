<?php

class SshKeyFixture extends CakeTestFixture {

    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
        'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 512, 'collate' => 'utf8_bin', 'charset' => 'utf8'),
        'comment' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
    );

    public $records = array(
        array(
            'id' => 1,
            'user_id' => 1,
            'key' => '0b73478cee542c314e014b4e4e7200670b73478cee542c314e014b4e4e720067t',
            'comment' => 'Lorem ipsum dolor sit amet',
            'created' => '2012-06-01 12:49:40',
            'modified' => '2012-06-01 12:49:40'
        ),
    );
}
