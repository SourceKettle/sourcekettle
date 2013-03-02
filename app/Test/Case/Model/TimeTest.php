<?php
/**
*
* Setting Unit Tests for the DevTrack system
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/SourceKettle/devtrack
* @package       DevTrack.Test.Case.Model
* @since         DevTrack v 1.0
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/
App::uses('Time', 'Model');

class TimeTestCase extends CakeTestCase {

	public $fixtures = array('app.time');

/**
 * setUp function.
 * Run before each unit test.
 * Corrrecly sets up the test environment.
 *
 * @access public
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Time =& ClassRegistry::init('Time');
	}

/**
 * tearDown function.
 * Tear down all created data after the tests.
 *
 * @access public
 * @return void
 */
	public function tearDown() {
		unset($this->Time);

		parent::tearDown();
	}

	public function testSplitMinsA() {
		$expectedResult = array(
			'd' => 0,
			'h' => 1,
			'm' => 0,
			's' => '1h 0m',
			't' => 60
		);
		$this->assertEquals($expectedResult, $this->Time->splitMins(60));
	}

	public function testSplitMinsB() {
		$expectedResult = array(
			'd' => 0,
			'h' => 0,
			'm' => 59,
			's' => '0h 59m',
			't' => 59
		);
		$this->assertEquals($expectedResult, $this->Time->splitMins(59));
	}

	public function testSplitMinsC() {
		$expectedResult = array(
			'd' => 0,
			'h' => 1,
			'm' => 1,
			's' => '1h 1m',
			't' => 61
		);
		$this->assertEquals($expectedResult, $this->Time->splitMins(61));
	}

	public function testSplitMinsD() {
		$expectedResult = array(
			'd' => 0,
			'h' => 1,
			'm' => 30,
			's' => '1h 30m',
			't' => 90
		);
		$this->assertEquals($expectedResult, $this->Time->splitMins(90));
	}

	public function testCurrentYear() {
		$this->assertEquals(date('Y'), $this->Time->currentYear(), "Wrong year returned");
	}

	public function testCurrentWeek() {
		$this->assertEquals(date('W'), $this->Time->currentWeek(), "Wrong week returned");
	}

	public function testDayOfWeek() {
		$this->assertEquals('1989-09-24', $this->Time->dayOfWeek(1989, 38, 7));
	}

	public function testGetMaxAllowedYear() {
		$this->Time->setMaxAllowedYear(2010);
		$this->assertEquals(2010, $this->Time->getMaxAllowedYear());
	}

	public function testGetMinAllowedYear() {
		$this->Time->setMinAllowedYear(2010);
		$this->assertEquals(2010, $this->Time->getMinAllowedYear());
	}

	public function testLastWeekOfYear52() {
		$this->assertEquals(52, $this->Time->lastWeekOfYear(2012));
		$this->assertEquals(52, $this->Time->lastWeekOfYear(2013));
		$this->assertEquals(52, $this->Time->lastWeekOfYear(2014));
	}

	public function testLastWeekOfYear53() {
		$this->assertEquals(53, $this->Time->lastWeekOfYear(2009));
		$this->assertEquals(53, $this->Time->lastWeekOfYear(2015));
	}

	public function testStartOfWeek() {
		$this->assertEquals('1989-09-17', $this->Time->startOfWeek(1989, 38));
	}

	public function testValidateYearTooLow() {
		$this->expectError('InvalidArgumentException');
		$this->Time->setMinAllowedYear(2009);
		$this->Time->setMaxAllowedYear(2010);

		$this->Time->validateYear(2008);
	}

	public function testValidateYearBottomRange() {
		$this->Time->setMinAllowedYear(2009);
		$this->Time->setMaxAllowedYear(2010);

		$this->assertEquals(2009, $this->Time->validateYear(2009), "Wrong year returned");
	}

	public function testValidateYearTopRange() {
		$this->Time->setMinAllowedYear(2009);
		$this->Time->setMaxAllowedYear(2010);

		$this->assertEquals(2010, $this->Time->validateYear(2010), "Wrong year returned");
	}

	public function testValidateYearNoneSupplied() {
		$this->Time->setMinAllowedYear(2009);
		$this->Time->setMaxAllowedYear(2010);

		$this->assertEquals(date('Y'), $this->Time->validateYear(), "Wrong year returned");
	}

	public function testValidateYearTooHigh() {
		$this->expectError('InvalidArgumentException');
		$this->Time->setMinAllowedYear(2009);
		$this->Time->setMaxAllowedYear(2010);

		$this->Time->validateYear(2011);
	}

	public function testValidateWeekTooLow() {
		$this->expectError('InvalidArgumentException');
		$this->Time->validateWeek(-1, 2010);
	}

	public function testValidateWeekGood() {
		$this->assertEquals(12, $this->Time->validateWeek(12, 2010));
	}

	public function testValidateWeekNoneSupplied() {
		$this->assertEquals(date('W'), $this->Time->validateWeek(), "Wrong week returned");
	}

	public function testValidateWeekTooHigh() {
		$this->expectError('InvalidArgumentException');
		$this->Time->validateWeek(54, 2010);
	}
}
