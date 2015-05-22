<?php
App::uses('AppModel', 'Model');
/**
 * Story Model
 *
 * @property Creator $Creator
 * @property Task $Task
 */
class Story extends AppModel {

	public $actsAs = array(
		'CakeDCUtils.SoftDelete',
	);

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'subject' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'creator_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'points_estimate' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Creator' => array(
			'className' => 'User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'story_id',
			'dependent' => false,
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		// Get the DB table prefix from our database config, for if
		// we have multiple systems in the same DB or fixtures have a prefix
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$table_prefix = $db->config['prefix'];

		$this->virtualFields = array(
			'public_id' => "(SELECT ".
				"COUNT(`{$table_prefix}{$this->table}`.`id`) ".
			"FROM ".
				"`{$table_prefix}{$this->table}` ".
			"WHERE ".
				"`{$table_prefix}{$this->table}`.`id` <= `{$this->alias}`.`id` ".
			"AND ".
				"`{$table_prefix}{$this->table}`.`project_id` = `{$this->alias}`.`project_id`)",
		);
	}

	public function afterFind($results, $primary = false) {
		foreach ($results as $i => $story) {
			$results[$i] = $this->__parseDescription($story);
		}
		return $results;
	}

	private function __parseDescription($story) {
		if (isset($story['Story']['description']) && 
		preg_match("/\s*(?P<asatag>as an?\s+)(?P<asa>.+)(?P<iwanttag>(,|\s)+I(( want)|( would like)|('d like))\s+)(?P<iwant>.+)(?P<sothattag>(,|\s)+so that\s+)(?P<sothat>.+)\s*$/i", $story['Story']['description'], $matches)) {
			$story['Story']['as-a'] = trim($matches['asa']);
			$story['Story']['i-want'] = trim($matches['iwant']);
			$story['Story']['so-that'] = trim($matches['sothat']);
			$story['Story']['formatted'] = "<strong>".$matches['asatag']."</strong>".$matches['asa']."<strong>".$matches['iwanttag']."</strong>".$matches['iwant']."<strong>".$matches['sothattag']."</strong>".$matches['sothat'];
		} else {
			$story['Story']['as-a'] = null;
			$story['Story']['action'] = null;
			$story['Story']['reason'] = null;
		}
		return $story;
	}

	public function listStoryOptions() {

		$stories = $this->find('list', array(
			'conditions' => array(
				'project_id' => $this->Project->id,
			),
			'fields' => array(
				'Story.public_id',
				'Story.subject',
			),
			'contain' => array(),

		));
		$stories[0] = __('No assigned story');
		ksort($stories);
		return $stories;
	}
}
