<?php
/**
 *
 * XmlShell Shell for the DevTrack system
 * A shell for importing/exporting data in an XML format
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	DevTrack Development Team 2012
 * @link		http://github.com/SourceKettle/devtrack
 * @package		DevTrack.Console.Command
 * @since		DevTrack v 1.0
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class XmlShell extends AppShell {

	public $tasks = array('ImportXml', 'ExportXml');

/**
 * import function.
 * Import data
 *
 * @access public
 * @return void
 */
	public function import() {
		$this->ImportXml->execute($this->_collectParameters());
	}

/**
 * export function.
 * Export data
 *
 * @access public
 * @return void
 */
	public function export() {
		$this->ExportXml->execute($this->_collectParameters());
	}

/**
 * _collectParameters function.
 * Collect all of the variables needed for execution
 *
 * @access private
 * @return array of server params
 */
	private function _collectParameters() {
		return array_merge($_SERVER, $_ENV);
	}

/**
 * main function.
 *
 * @access public
 * @return void
 */
	public function main() {
		$this->out('<error>Error:</error> Please specify a method (import, export)', 1, Shell::NORMAL);
	}

}
