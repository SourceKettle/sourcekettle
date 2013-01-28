<?php
/**
 *
 * ImportXmlTask Task for the DevTrack system
 * A task for importing data in an XML format
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	DevTrack Development Team 2012
 * @link		http://github.com/SourceKettle/devtrack
 * @package		DevTrack.Console.Command.Task
 * @since		DevTrack v 1.0
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class ImportXmlTask extends Shell {
/**
 * fileParamNumber
 * The number that is used to fetch the
 * output file from input params
 *
 * @var int
 * @access private
 */
	private $fileParamNumber = 5;

/**
 * rootXMLElement
 * The root XML element
 *
 * @var string
 * @access private
 */
	private $rootXMLElement = 'codekettle';

/**
 * uses
 * The models which will be exported by this task
 *
 * @var mixed
 * @access public
 */
	public $uses = array(
		'Settings',
		'Users',
		'Projects',
		'Collaborators',
		'Times',
		'Milestones',
		'Tasks',
		'SshKeys',
		'ProjectHistories',
	);

/**
 * _checkStartupParams function.
 * Check the parameters that have been given to us
 *
 * @access private
 * @param mixed $params
 */
	private function _checkStartupParams($params) {
		if (isset($params['argv'][$this->fileParamNumber])) {
			$file = $params['argv'][$this->fileParamNumber];
			if (substr($file, 0, strlen('--'))=='--') {
				$this->_criticalError('Please specify a valid output file');
			}
		} else {
			$this->_criticalError('Please specify a valid output file');
		}
	}

/**
 * _critcalError function.
 * Prints a formatted error to the screen and exits the app
 *
 * @access private
 * @param string $reason reason for exiting
 */
	private function _criticalError($reason) {
		$this->out('<error>Error:</error> ' . $reason, 1, Shell::NORMAL);
		exit(1);
	}

/**
 * _genericGather function.
 * Gather the XML and insert into the database
 *
 * @param mixed $item The item to set the content as
 * @param mixed $items The items to iterate over
 * @param mixed $search The item to pull out of items
 * @return mixed array of result
 */
	private function _genericGather($item, $items, $search) {
		$insertions = $items[$item];
		if (is_array($insertions)) {
			if (isset($insertions['id'])) {
				$this->out('About to insert ' . $search . ' ' . $insertions['id'], 1, Shell::VERBOSE);
				if ($this->{$search}->save($insertions)) {
					$this->out('Inserted ' . $search . ' ' . $insertions['id'], 1, Shell::VERBOSE);
				} else {
					$this->out('<warning>Failed to insert ' . $search . ' ' . $insertions['id'] . '</warning>', 1, Shell::VERBOSE);
				}
			} else {
				foreach ($insertions as $insertion) {
					$this->out('About to insert ' . $search . ' ' . $insertion['id'], 1, Shell::VERBOSE);
					if ($this->{$search}->save($insertion)) {
						$this->out('Inserted ' . $search . ' ' . $insertion['id'], 1, Shell::VERBOSE);
					} else {
						$this->out('<warning>Failed to insert ' . $search . ' ' . $insertion['id'] . '</warning>', 1, Shell::VERBOSE);
					}
				}
			}
		}
	}
/**
 * execute function.
 * The main import function
 *
 * @access public
 * @param array $params (default: array())
 * @return void
 */
	public function execute($params = array()) {
		$this->_checkStartupParams($params);

		$inputFile = new File($params['argv'][$this->fileParamNumber]);
		if (!$inputFile->exists()) {
			$this->_criticalError('Input file does not exist');
		}
		$this->out('About to import from ' . $inputFile->path, 1, Shell::VERBOSE);

		$inputArray = array();
		try {
			$inputArray = Xml::toArray(Xml::build($inputFile->read()));
		} catch (Exception $e) {
			$this->_criticalError($e->getMessage());
		}

		foreach ($this->uses as $a => $from) {
			$toGroup = strtolower($from);
			$toSingle = Inflector::singularize($toGroup);
			if (isset($inputArray[$this->rootXMLElement][$toGroup])) {
				$this->_genericGather($toSingle, $inputArray[$this->rootXMLElement][$toGroup], $from);
			}
		}
	}
}
