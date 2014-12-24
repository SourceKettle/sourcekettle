<?php
/**
 *
 * Element for displaying the time topbar for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.View.Elements.Topbar
 * @since         SourceKettle v 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 $options = array(
    'links' => array(
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
);

$action = $this->request['action'];
if ($action != 'add' && $action != 'edit') {

   $logLink = array(
        'text' => __('Log time'),
		'icon-white' => 'pencil',
        'url' => array(
            'action' => 'add',
            'controller' => 'times',
        ),
		'active' => true,
		'pull-right' => true,
    );

	// If we have a start date available, pass a date through to the 'log time' page
	if(isset($startDate)){
		$logLink['url']['?'] = array('date' => $startDate->format('Y-m-d'));
	}

	$options['links'][] = $logLink;
	// Timesheet view - add a download link
	if(isset($thisYear) && isset($thisWeek)){
		$options['links'][] = array(
	        'text' => __('Get CSV'),
			'icon' => 'download',
	        'url' => array(
	            'controller' => 'times',
	            'action' => 'history',
				'year' => $thisYear,
				'week' => $thisWeek,
				'?' => array(
					'format' => 'csv'
				),
	        ),
			'pull-right' => true,
	    );
	}

}

echo $this->element('Topbar/pills', array('options' => $options));
