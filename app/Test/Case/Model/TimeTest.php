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
	public function testFixture() {
        $this->Time->recursive = -1;
        $fixtures = array(
            array(
                'Time' => array(
                    'id' => '1',
                    'project_id' => '1',
                    'user_id' => '1',
                    'task_id' => '1',
					'mins' => '90',
                    'minutes' => array(
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
                    'modified' => '2012-11-11 10:24:06',
					'deleted' => '0',
					'deleted_date' => null,
                ),
            ),
            array(
                'Time' => array(
                    'id' => '2',
                    'project_id' => '1',
                    'user_id' => '1',
                    'task_id' => '2',
					'mins' => '900',
                    'minutes' => array(
                        'w' => '0',
                        'd' => '0',
                        'h' => '15',
                        'm' => '0',
                        's' => '15h 0m',
                        't' => '900',
                    ),
                    'description' => 'A description of the second <b>task</b>.',
                    'date' => '2012-11-12',
                    'created' => '2012-11-12 10:24:06',
                    'modified' => '2012-11-12 10:24:06',
					'deleted' => '0',
					'deleted_date' => null,
                ),
            ),
			array(
				'Time' => array(
					'id' => '3',
					'project_id' => '1',
					'user_id' => '2',
					'task_id' => '2',
					'mins' => '14',
					'description' => 'A description of the third <b>task</b>.',
					'date' => '2012-11-13',
					'created' => '2012-11-13 10:24:06',
					'modified' => '2012-11-13 10:24:06',
					'deleted' => '0',
					'deleted_date' => null,
					'minutes' => array(
						'w' => (int) 0,
						'd' => (int) 0,
						'h' => (int) 0,
						'm' => (int) 14,
						't' => (int) 14,
						's' => '0h 14m'
					)
				)
			),
			array(
				'Time' => array(
					'id' => '4',
					'project_id' => '1',
					'user_id' => '3',
					'task_id' => '1',
					'mins' => '19',
					'description' => 'A description of the fourth <b>task</b>.',
					'date' => '2012-11-11',
					'created' => '2012-11-11 10:24:06',
					'modified' => '2012-11-11 10:24:06',
					'deleted' => '0',
					'deleted_date' => null,
					'minutes' => array(
						'w' => (int) 0,
						'd' => (int) 0,
						'h' => (int) 0,
						'm' => (int) 19,
						't' => (int) 19,
						's' => '0h 19m'
					)
				)
			)
        );
        $fixturesB = $this->Time->find('all');
        $this->assertEquals($fixtures, $fixturesB, json_encode($fixturesB)."Arrays were not equal");
    }

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

    public function testValidateWeek() {
		$now = time();

		// Use null - it should pick the current week number
		try{
			$week = $this->Time->validateWeek(null, date('Y', $now));
			$this->assertEquals($week, date('W', $now), "Failed to validate null week as current week");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}

		// Use a string instead of an int - should fail
		try{
			$this->Time->validateWeek('shoes', date('Y', $now));
			$this->assertFalse(true, "Validated a string as a valid week number - should fail");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}

		// Try some out-of-bounds weeks
		try{
			$this->Time->validateWeek(0, 2011);
			$this->assertFalse(true, "Validated week 0 (should be invalid)");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}
		try{
			$this->Time->validateWeek($this->Time->lastWeekOfYear(2011)+1, 2011);
			$this->assertFalse(true, "Validated week past end of year (should be invalid)");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}

		// Now try something valid
		try{
			$week = $this->Time->validateWeek(30, 2011);
			$this->assertEquals($week, 30, "Failed to validate week 30 as valid week");
        } catch (Exception $e) {
            $this->assertTrue(false, "Unexpected exception thrown: ".$e->getMessage());
		}
	}

	public function testToString() {
		try{
			$this->Time->id = null;
			$this->Time->toString();
			$this->assertTrue(false, "No exception thrown when stringifying null time");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}

		$this->Time->id = 1;
		$this->Time->read();
		$this->assertEquals('1h 30m', $this->Time->toString(), 'Expected task ID 1 to be 1h 30m');

		$this->Time->id = 1000;
		$this->Time->read();

		try{
			$this->Time->toString();
			$this->assertTrue(false, "No exception thrown when stringifying nonexistent time");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}
	}

	public function testDayOfWeek() {
		$dow = $this->Time->dayOfWeek(2011, 1, 5);
		$this->assertEquals($dow, '2011-01-07', "Incorrect start of week 2011-01/5");
		$dow = $this->Time->dayOfWeek(2011, 12, 4);
		$this->assertEquals($dow, '2011-03-24', "Incorrect start of week 2011-12/4");
		$dow = $this->Time->dayOfWeek(2011, 52, 6);
		$this->assertEquals($dow, '2011-12-31', "Incorrect start of week 2011-52/6");
	}

	public function testStartOfWeek() {
		$sow = $this->Time->startOfWeek(2011, 1);
		$this->assertEquals($sow, '2011-01-02', "Incorrect start of week 2011-01");
		$sow = $this->Time->startOfWeek(2011, 12);
		$this->assertEquals($sow, '2011-03-20', "Incorrect start of week 2011-12");
		$sow = $this->Time->startOfWeek(2011, 52);
		$this->assertEquals($sow, '2011-12-25', "Incorrect start of week 2011-52");
	}

	public function testGetTitleForHistory() {
		$this->assertNull($this->Time->getTitleForHistory(null), "Got non-null title for null time ID");
		$this->assertEquals($this->Time->getTitleForHistory(1), "1h 30m", "Got incorrect title for time ID 1");
		$this->assertEquals($this->Time->getTitleForHistory(2), "15h 0m", "Got incorrect title for time ID 2");
		$this->assertEquals($this->Time->getTitleForHistory(3), "0h 14m", "Got incorrect title for null time ID 3");
	}

	public function testFetchTotalTimeForProject() {
		try{
			$totalTime = $this->Time->fetchTotalTimeForProject();
			$this->assertTrue(false, "Successfully fetched total time for null project");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}

		$totalTime = $this->Time->fetchTotalTimeForProject(1);
		$this->assertEquals($totalTime, array(
			'w' => (int) 0,
			'd' => (int) 0,
			'h' => (int) 17,
			'm' => (int) 3,
			't' => (int) 1023,
			's' => '17h 3m'
		), "Incorrect total time found for project ID 1");
	}

	public function testFetchUserTimesForProject() {
		try{
			$userSummary = $this->Time->fetchUserTimesForProject();
			$this->assertTrue(false, "Successfully fetched times for null project");
		} catch (InvalidArgumentException $e) {
			$this->assertTrue(true);
        } catch (Exception $e) {
            $this->assertTrue(false, "Wrong exception thrown: ".$e->getMessage());
		}

		$userSummary = $this->Time->fetchUserTimesForProject(1);
		$this->assertEquals($userSummary, array(
			array(
				0 => array(
					'total_mins' => '990',
				),
				'User' => array(
					'id' => '1',
					'name' => 'Mr Smith',
					'email' => 'Mr.Smith@example.com'
				),
				'Time' => array(
					'time' => array(
						'w' => (int) 0,
						'd' => (int) 0,
						'h' => (int) 16,
						'm' => (int) 30,
						't' => (int) 990,
						's' => '16h 30m'
					)
				),
			),

		 	array(
				'User' => array(
					'id' => '2',
					'name' => 'Mrs Smith',
					'email' => 'mrs.smith@example.com'
				),
				0 => array(
					'total_mins' => '14'
				),
				'Time' => array(
					'time' => array(
						'w' => (int) 0,
						'd' => (int) 0,
						'h' => (int) 0,
						'm' => (int) 14,
						't' => (int) 14,
						's' => '0h 14m'
					)
				)
			),

			2 => array(
				'User' => array(
					'id' => '3',
					'name' => 'Mrs Guest',
					'email' => 'mrs.guest@example.com'
				),
				0 => array(
					'total_mins' => '19'
				),
				'Time' => array(
					'time' => array(
						'w' => (int) 0,
						'd' => (int) 0,
						'h' => (int) 0,
						'm' => (int) 19,
						't' => (int) 19,
						's' => '0h 19m'
					)
				)
			)
		), "User time summary was not correct");
	}

	public function testTasksForWeek() {
		$this->Time->Project->id = 1;
		$tasks = $this->Time->tasksForWeek(2012, 46, false);
		$this->assertEqual($tasks, array(1, 2), "Incorrect list of task IDs for week 46");
		// TODO tasks for current user
	}
}
