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
App::uses('TimeString', 'Time');

class TimeTestCase extends CakeTestCase {

    /**
     * setUp function.
     * Run before each unit test.
     * Corrrecly sets up the test environment.
     *
     * @access public
     * @return void
     */
    public $fixtures = array(
        'app.time',
        'app.project',
        'app.collaborator',
        'app.user',
		'app.task',
    );

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
        $this->Time = ClassRegistry::init('Time');
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

    /**
     * test fixtures function.
     *
     * @access public
     * @return void
     */
    // TODO for some moon-hooveringly bizarre reason, although all the fixtures
	// end up present and correct, this entire test case will read from the default
	// database, not the test DB :-/ I still can't work out why, other tests are fine.
	/*public function testFixture() {
        $this->Time->recursive = -1;
        $fixtures = array(
            array(
                'Time' => array(
                    'id' => '1',
                    'project_id' => '1',
                    'user_id' => '1',
                    'task_id' => '1',
                    'mins' => array(
                        'w' => '0',
                        'd' => '0',
                        'h' => '1',
                        'm' => '30',
                        's' => '1h 30m',
                        't' => '90',
                    ),
                    'description' => 'A description.',
                    'date' => '2012-11-11',
                    'created' => '2012-11-11 10:24:06',
                    'modified' => '2012-11-11 10:24:06'
                ),
            ),
        );
        $fixturesB = $this->Time->find('all');
        $this->assertEquals($fixtures, $fixturesB, json_encode($fixturesB)."Arrays were not equal");
    }*/

    /**
     * test TimeString->renderTime function.
     * Tests that mins are correctly split into hours and mins
     *
     * @access public
     * @return void
     */
    public function testRenderTime() {
        $expectedResult = array(
			'w' => 0,
			'd' => 0,
            'h' => 1,
            'm' => 0,
            's' => '1h 0m',
            't' => 60
        );
        $this->assertEquals(TimeString::renderTime(60), $expectedResult);

        $expectedResult = array(
			'w' => 0,
			'd' => 0,
            'h' => '0',
            'm' => '59',
            's' => '0h 59m',
            't' => '59'
        );
        $this->assertEquals(TimeString::renderTime(59), $expectedResult);

        $expectedResult = array(
			'w' => 0,
			'd' => 0,
            'h' => '1',
            'm' => '1',
            's' => '1h 1m',
            't' => '61'
        );
        $this->assertEquals(TimeString::renderTime(61), $expectedResult);

        $expectedResult = array(
			'w' => 0,
			'd' => 0,
            'h' => '1',
            'm' => '30',
            's' => '1h 30m',
            't' => '90'
        );
        $this->assertEquals(TimeString::renderTime(90), $expectedResult);
    }

    public function testParseTime() {
        $this->assertEquals(TimeString::parseTime('2m'), 2, 'Failed to parse 2m');
        $this->assertEquals(TimeString::parseTime('2h'), 120, 'Failed to parse 2h');
        $this->assertEquals(TimeString::parseTime(' 2h '), 120, 'Failed to parse 2h with whitespace');
        $this->assertEquals(TimeString::parseTime('1w'), 10080, 'Failed to parse 1w');
        $this->assertEquals(TimeString::parseTime('3d'), 4320, 'Failed to parse 3d');
        $this->assertEquals(TimeString::parseTime('4w 3d 9h 6m'), 45186, 'Failed to parse 4w 3d 9h 6m');
    }

    /**
     * test Time->validateYear function.
     * Tests that the validate year prevents times being allocated to
     * years outside of the specified range.
     *
     * @access public
     * @return void
     */
    public function testValidateYear() {
        $this->Time->setMinAllowedYear(2009);
        $this->Time->setMaxAllowedYear(2010);

        $this->assertEquals(2009, $this->Time->getMinAllowedYear(), "Wrong min year set");
        $this->assertEquals(2010, $this->Time->getMaxAllowedYear(), "Wrong max year set");

        try {
            $this->Time->validateYear(2008);
            $this->assertTrue(false, "Validate year allowed a year below minimum");
        } catch (InvalidArgumentException $e) {
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
        }

        $this->assertEquals(2009, $this->Time->validateYear(2009), "Wrong year returned");
        $this->assertEquals(2010, $this->Time->validateYear(2010), "Wrong year returned");

        $this->assertEquals(date('Y'), $this->Time->validateYear(), "Wrong year returned");

        try {
            $this->Time->validateYear(2011);
            $this->assertTrue(false, "Validate year allowed a year above maximum");
        } catch (InvalidArgumentException $e) {
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
        }
    }

	// TODO this one doesn't read from test DB either!
	/*public function testToString() {

		$times = $this->Time->find('list');

		debug(print_r($times, true));

		$this->Time->id = 1;
		$this->Time->read();
		debug($this->Time->toString());
	}*/

}
