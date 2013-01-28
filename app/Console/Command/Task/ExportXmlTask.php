<?php
/**
 *
 * ExportXmlTask Task for the DevTrack system
 * A task for exporting data in an XML format
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
class ExportXmlTask extends Shell {

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
		'ProjectHistories'
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
			if (substr($file, 0, strlen('--')) == '--') {
				$this->_criticalError('Please specify a valid output file');
			}
		} else {
			$this->_criticalError('Please specify a valid output file');
		}
	}

/**
 * _criticalError function.
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
 * Gather and refactor elements based on supplied arguments
 *
 * @access private
 * @param mixed $item The item to set the content as
 * @param mixed $items The items to iterate over
 * @param mixed $search The item to pull out of items
 * @return mixed array of result
 */
	private function _genericGather($item, $items, $search) {
		$outputArray = array(
			$item => array()
		);
		foreach ($items as $currentItem) {
			array_push($outputArray[$item], $currentItem[$search]);
		}
		return $outputArray;
	}

/**
 * execute function.
 * The main export function
 *
 * @access public
 * @param array $params (default: array())
 * @return void
 */
	public function execute($params = array()) {
		$this->_checkStartupParams($params);

		$outputFile = new File($params['argv'][$this->fileParamNumber]);
		if ($outputFile->exists()) {
			$this->_criticalError('Output file already exists');
		}
		$this->out('About to export into ' . $outputFile->path, 1, Shell::VERBOSE);

		if (!$outputFile->create() || !$outputFile->exists()) {
			$this->_criticalError('Output file could not be created');
		}

		$elements = array(
			$this->rootXMLElement => array()
		);

		foreach ($this->uses as $a => $from) {
			$toGroup = strtolower($from);
			$toSingle = Inflector::singularize($toGroup);
			$elements[$this->rootXMLElement][$toGroup] = $this->_genericGather($toSingle, $this->{$from}->find('all'), $from);
		}

		$xmlObject = Xml::fromArray($elements, array('format' => 'tags'));
		$dom = dom_import_simplexml($xmlObject)->ownerDocument;
		$dom->formatOutput = true;
		$output = $dom->saveXML();

		$this->out($output, 1, Shell::VERBOSE);
		$outputFile->write($output);
		$outputFile->close();

		$this->out('<info>Export Complete</info>', 1, Shell::NORMAL);
	}
}
