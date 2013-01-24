<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
/**
 * Notification Model
 *
 * @property User $User
 */
class Notification extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'text';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'text' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'url' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'url' => array(
				'rule' => array('url'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
     * create function
     *
     * Create a notifications for a user
     * @access public
     * @param int $user_id 
     * @param text $text 
     * @param text $url 
     * @return boolean whether the notification was created successfully.
     */
	public function create($user_id, $text, $url) {
    if ( $user_id == null || empty($text) || empty($url)) {
        return false;
    } else {

    	$notification = array(
    		'Notification' => array(
    			'user_id' => $user_id,
    			'text' => $text,
    			'url' => $url
  			)
  		);

    	$this->save($notification);
      $settings = ClassRegistry::init('NotificationSetting');
      $userSettings = $settings->find('first', array("conditions" => array('user_id' => $user_id)));

      if ($userSettings['NotificationSetting']['email_notifications']){
        $email = new CakeEmail('smtp');
        $email->to($userSettings['User']['email']);
        $email->subject("New notification");
        $email->send("Test message!");
      }
          
    }
	}

	/**
     * forUser function
     *
     * Get the notifications for a user
     * @access public
     * @param int $user_id 
     * @return array Notification
     */
	public function forUser($user_id) {
		if ( $user_id == null ) {
        $user_id = $this->_auth_user_id;
    }
    if ( $user_id == null ) {
        return false;
    }
    $this->recursive = -1;
    return $this->find('all', array('conditions' => array('user_id' => $user_id), 'order' => array('created DESC')));
	}
}
