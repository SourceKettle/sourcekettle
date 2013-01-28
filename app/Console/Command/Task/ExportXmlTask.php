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
 * __fileParamNumber
 * The number that is used to fetch the
 * output file from input params
 *
 * @var int
 * @access private
 */
	private $__fileParamNumber = 5;

/**
 * __rootXMLElement
 * The root XML element
 *
 * @var string
 * @access private
 */
	private $__rootXMLElement = 'codekettle';

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
 * __checkStartupParams function.
 * Check the parameters that have been given to us
 *
 * @access private
 * @param mixed $params
 */
	private function __checkStartupParams($params) {
		if (isset($params['argv'][$this->__fileParamNumber])) {
			$file = $params['argv'][$this->__fileParamNumber];
			if (substr($file, 0, strlen('--')) == '--') {
				$this->__criticalError('Please specify a valid output file');
			}
		} else {
			$this->__criticalError('Please specify a valid output file');
		}
	}

/**
 * __criticalError function.
 * Prints a formatted error to the screen and exits the app
 *
 * @access private
 * @param string $reason reason for exiting
 */
	private function __criticalError($reason) {
		$this->out('<error>Error:</error> ' . $reason, 1, Shell::NORMAL);
		exit(1);
	}

/**
 * __genericGather function.
 * Gather and refactor elements based on supplied arguments
 *
 * @access private
 * @param mixed $item The item to set the content as
 * @param mixed $items The items to iterate over
 * @param mixed $search The item to pull out of items
 * @return mixed array of result
 */
	private function __genericGather($item, $items, $search) {
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
		$this->__checkStartupParams($params);

		$outputFile = new File($params['argv'][$this->__fileParamNumber]);
		if ($outputFile->exists()) {
			$this->__criticalError('Output file already exists');
		}
		$this->out('About to export into ' . $outputFile->path, 1, Shell::VERBOSE);

		if (!$outputFile->create() || !$outputFile->exists()) {
			$this->__criticalError('Output file could not be created');
		}

		$elements = array(
			$this->__rootXMLElement => array()
		);

		foreach ($this->uses as $a => $from) {
			$toGroup = strtolower($from);
			$toSingle = Inflector::singularize($toGroup);
			$elements[$this->__rootXMLElement][$toGroup] = $this->__genericGather($toSingle, $this->{$from}->find('all'), $from);
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
