<?php
/**
 *
 * ProjectHistory model for the SourceKettle system
 * Stores the past for a project
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Model
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @extends AppModel
 */

App::uses('AppModel', 'Model');

class ProjectHistory extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'project_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'A valid project id was not entered',
			),
		),
		'model' => array(
			'inlist' => array(
				// TODO Bad idea - hard coded :-(
				'rule' => array('inlist', array('collaborator','task','milestone','source','time','projectattachment')),
			),
		),
		'model_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'A valid project id was not entered',
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function logC($model, $rowId, $rowTitle, $rowField, $rowFieldOld, $rowFieldNew, $userId, $userName) {

		$this->create();
		return $this->save(array(
			'ProjectHistory' => array(
				'project_id' => $this->Project->id,
				'model' => $model,
				'row_id' => $rowId,
				'row_title' => $rowTitle,
				'row_field' => $rowField,
				'row_field_old' => $rowFieldOld,
				'row_field_new' => $rowFieldNew,
				'user_id' => $userId,
				'user_name' => $userName,
			)
		));
	}

/**
 * fetchHistory function.
 *
 * @access public
 * @param string $project (default: '')
 * @param int $number (default: 10)
 * @param int $offset (default: 0)
 * @param float $user (default: -1)
 * @return void
 */
	public function fetchHistory($project = null, $number = 50, $offset = 0, $user = -1, $model = null) {
		$search = array(
			'conditions' => array(),
			'limit' => $number + $offset,
			'offset' => $offset,
			'order' => 'ProjectHistory.created DESC'
		);

		// If a user is specified
		if ($project == null && $user > 0) {
			$search['conditions']['project_id'] = array_values(
				$this->Project->Collaborator->find(
					'list',
					array(
						'conditions' => array('user_id' => $user),
						'fields' => array('project_id')
					)
				)
			);
		}

		if ($project != null && $project = $this->Project->getProject($project)) {
			$search['conditions']['project_id'] = $project['Project']['id'];
		}
		if ($model != null) {
			$search['conditions']['model'] = $model;
		}

		$events = array();

		// Fetch the objects in question
		$results = $this->find('all', $search);

		foreach ($results as $a => $result) {

			$events[$a] = array();
			$events[$a]['modified'] = $result['ProjectHistory']['modified'];
			$events[$a]['Type'] = $model = ucfirst($result['ProjectHistory']['model']);

			// Gather project details
			$events[$a]['Project']['id'] = $result['Project']['id'];
			$events[$a]['Project']['name'] = $result['Project']['name'];

			// Gather user details
			$events[$a]['Actioner']['id'] = $result['ProjectHistory']['user_id'];
			$events[$a]['Actioner']['name'] = $result['ProjectHistory']['user_name'];
			$events[$a]['Actioner']['email'] = 'test@example.com';
			$events[$a]['Actioner']['exists'] = false;

			// Gather subject details
			$events[$a]['Subject']['id'] = $result['ProjectHistory']['row_id'];
			$events[$a]['Subject']['title'] = $result['ProjectHistory']['row_title'];
			$events[$a]['Subject']['exists'] = false;

			// Gather change details
			$events[$a]['Change']['field'] = $result['ProjectHistory']['row_field'];
			$events[$a]['Change']['field_old'] = $result['ProjectHistory']['row_field_old'];
			$events[$a]['Change']['field_new'] = $result['ProjectHistory']['row_field_new'];

			// Check if the actioner exists
			$this->Project->Collaborator->User->id = $result['ProjectHistory']['user_id'];
			if ($this->Project->Collaborator->User->exists()) {
				$events[$a]['Actioner']['name'] = $this->Project->Collaborator->User->field('name');
				$events[$a]['Actioner']['email'] = $this->Project->Collaborator->User->field('email');
				$events[$a]['Actioner']['exists'] = true;
			}

			// Check if the subject exists
			$this->Project->{$model}->id = $result['ProjectHistory']['row_id'];
			if ($this->Project->{$model}->exists()) {
				$events[$a]['Subject']['title'] = $this->Project->{$model}->getTitleForHistory($result['ProjectHistory']['row_id']);
				$events[$a]['Subject']['exists'] = true;
			}

			// TODO surely the URL can be added/automated for all objects using the routing engine somehow?
			switch ($events[$a]['Type']) {
				case 'Collaborator':
					$this->Project->Collaborator->id = $events[$a]['Subject']['id'];
					if ($this->Project->Collaborator->exists()) {
						$events[$a]['url'] = array(
							'api' => false,
							'admin' => false,
							'controller' => 'users',
							'action' => 'view',
							$this->Project->Collaborator->field('user_id')
						);
					}
					break;
				case 'Time':
					if (!empty($events[$a]['Change']['field_old'])) {
						$events[$a]['Subject']['title'] = 'logged time';
					}
					break;

				// Tasks: swap out the real task ID for its public ID
				case 'Task':
					$events[$a]['Subject']['id'] = $this->Project->Task->field('public_id', array('id' => $events[$a]['Subject']['id']));
					break;
			}
		}

		return $events;
	}
}
