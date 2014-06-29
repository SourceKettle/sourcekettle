<?php
/**
 *
 * XmlShell Shell for the SourceKettle system
 * A shell for importing/exporting data in an XML format
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	SourceKettle Development Team 2012
 * @link		http://github.com/SourceKettle/sourcekettle
 * @package		SourceKettle.Console.Command
 * @since		SourceKettle v 1.0
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
		$this->ImportXml->execute($this->__collectParameters());
	}

/**
 * export function.
 * Export data
 *
 * @access public
 * @return void
 */
	public function export() {
		$this->ExportXml->execute($this->__collectParameters());
	}

/**
 * __collectParameters function.
 * Collect all of the variables needed for execution
 *
 * @access private
 * @return array of server params
 */
	private function __collectParameters() {
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
