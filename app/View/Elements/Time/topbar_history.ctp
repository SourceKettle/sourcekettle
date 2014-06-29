<?php
/**
 *
 * Element for displaying the time topbar for the DevTrack system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     DevTrack Development Team 2012
 * @link          http://github.com/SourceKettle/devtrack
 * @package       DevTrack.View.Elements.Topbar
 * @since         DevTrack v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 $options = array(
    'left' => array(
        array(
            array(
                'text' => __('Project summary'),
                'url' => array(
                    'action' => 'users',
                    'controller' => 'times',
                ),
            ),
            array(
                'text' => __('Timesheets'),
                'url' => array(
                    'action' => 'history',
                    'controller' => 'times',
                ),
			),
        ),
    ),
    'right' => array(),
);

$action = $this->request['action'];
if ($action != 'add' && $action != 'edit') {
   $options['right'] = array(
   		array(
            array(
                'text' => $this->Bootstrap->icon('pencil', 'white').' '.__('Log time'),
                'url' => array(
                    'action' => 'add',
                    'controller' => 'times',
                ),
                'props' => array(
					'class' => 'btn-primary',
					'escape' => false
				),
            ),
        )
    );

	// If we have a start date available, pass a date through to the 'log time' page
	if(isset($startDate)){
		$options['right'][0][0]['url']['?'] = array('date' => $startDate->format('Y-m-d'));
	}


	// Timesheet view - add a download link
	if(isset($thisYear) && isset($thisWeek)){
		array_unshift(
			$options['right'][0],
	        array(
	            'text' => $this->Bootstrap->icon('download').' '.__('Get CSV'),
	            'url' => array(
	                'controller' => 'times',
	                'action' => 'history',
					'year' => $thisYear,
					'week' => $thisWeek,
					'?' => array(
						'format' => 'csv'
					),
	            ),
				'props' => array(
					'escape' => false
				)
	        )
		);
	
	}
}

echo $this->element('Topbar/button', array('options' => $options));
