<?php
/**
*
* Email Confirmation Unit Tests for the DevTrack system
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
App::uses('SshKey', 'Model');

class SshKeyTestCase extends CakeTestCase {

	// Example RSA and DSA public keys, generated specifically for this test.
		private $dsa =	'ssh-dss AAAAB3NzaC1kc3MAAACBAMy3kgD8D2xr2HwRpz93bi/kVeusYlkz9NFXRQ6E0Ar8xiWBh1lXxWjxCfM+DSRIgdvVJA2AQi3coWfTMXTRiWJAlEJtf3eBWMvJhCeJZ40WChJfbnoRCEORsfwXoo98XigXnWrAMjfqU0xWCvCI+sdRnffUYdHXOgKwmaylPvgRAAAAFQD7AICGcDlVJCJeHu4PfAGmD4dppwAAAIEAr7gHxCATf+7N/3powMWhaJSU5fTWPtSDyDV/fTMOmwd1dvlzW2HToS42gqeg1eyw1C1FVCn/5ptB6u9Qaw3YG7Bvo79wFM1ACpDF0U4f+ZydKC8INhRt4FqUkEjJXgceKa3BAGJncLzLd0fX3TxRf2ZksQx5dV6KGFpyIa5Ii80AAACAS7fEUZgWWIJBklW18nW346jM6KAX5gdAd6j+BJ5QJ1nSxD7ZaYxKEHr7HSdNWOEyJMls4SEzX4opi+ZBVtI1shU7wclxYss0vLuBQV2DitChO9ouQsfRifRMMUB1n8KlSLNAtICjS6pa/TmlhZt9NY+oTYf76BTQFMpEsvjRP20= somebody@localhost';

		private $rsa =	'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCyEoCvRtgCa0+yPSuopvo2mFXnF6myTvljXujIBVVQkvk1uPWL8JU8tPBGp7xjS2nC6teHd6Ju2LItZmsLuH+U80BVuhdtGPPc4Iim8j2eEJATJRi7MDxdqlml7c1Ofa6SSwWPUsZFFlBn8KyOWn/V5EdCbBvdg8XLPWb5BhBKHBLm826tBewq7V1teCKNxS5viM7YZRibTzmuuBH7EN8Z0Qb1JMWTWnXQZM6cB9jNJfqyCOOKFWWiq+aBqtfAW58rAfISeIjxwETFYfi8DKIyGHn53MsdNwGBPH99SZnFGq43DVciYioa9t0ZX8MqDhxIhDuQOvAitORiEzmsPZ2F somebody@localhost';

    /**
     * fixtures - Populate the database with data of the following models
     */
    public $fixtures = array('app.ssh_key', 'app.user');

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
        $this->SshKey = ClassRegistry::init('SshKey');
    }

    /**
     * tearDown function.
     * Tear down all created data after the tests.
     *
     * @access public
     * @return void
     */
    public function tearDown() {
        unset($this->SshKey);

        parent::tearDown();
    }

    /**
     * test fixtures function.
     *
     * @access public
     * @return void
     */
    public function testFixture() {
        $this->SshKey->recursive = -1;
        $fixtures = array(
            array(
                'SshKey' => array(
                    'id' => "1",
                    'user_id' => "1",
                    'key' => '0b73478cee542c314e014b4e4e7200670b73478cee542c314e014b4e4e720067t',
                    'comment' => 'Lorem ipsum dolor sit amet',
                    'created' => '2012-06-01 12:49:40',
                    'modified' => '2012-06-01 12:49:40'
                ),
            ),
        );
        $fixturesB = $this->SshKey->find('all');
        $this->assertEquals($fixtures, $fixturesB, "Arrays were not equal");
    }

	public function testMissingUserID() {
		$saved = $this->SshKey->save(array(
			'comment' => 'some comment',
			'key' => $this->rsa
		));

		$this->assertFalse($saved, 'Save RSA key succeeded despite missing user ID');
		
	}

	public function testMissingKey() {
		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'comment' => 'some comment',
		));
		$this->assertFalse($saved, 'Save RSA key succeeded despite missing SSH key');
		
	}

	public function testInvalidKey() {
		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'comment' => 'some comment',
			'key' => 'moose??!!Wibble__ShoeBags',
		));
		$this->assertFalse($saved, 'Save RSA key succeeded despite missing SSH key');
		
	}

	public function testInvalidKeyType() {
		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'comment' => 'some comment',
			'key' => 'mooseWibbleShoeBags',
		));
		$this->assertFalse($saved, 'Save RSA key succeeded despite missing SSH key');
	}

	public function testAddKeyOnly() {

		// Remove comments so we just have the key part
		$rsa = preg_replace('/ somebody@localhost$/', '', $this->rsa);
		$dsa = preg_replace('/ somebody@localhost$/', '', $this->dsa);

		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'comment' => 'some comment',
			'key' => $rsa
		));

		$this->assertNotEqual($saved, null, 'Failed to save RSA SSH key');
		$this->assertNotEqual($saved['SshKey']['id'], null, 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['user_id'], 2, 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['key'], $rsa, 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['comment'], 'some comment', 'Failed to save RSA SSH key');

		$this->SshKey->create();

		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'comment' => 'some other DSA comment',
			'key' => $dsa
		));

		$this->assertNotEqual($saved, null, 'Failed to save DSA SSH key');
		$this->assertNotEqual($saved['SshKey']['id'], null, 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['user_id'], 2, 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['key'], $dsa, 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['comment'], 'some other DSA comment', 'Failed to save DSA SSH key');

	}


	public function testAddKeyWithComment() {
		$rsa = $this->rsa;
		$dsa = $this->dsa;

		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'key' => $rsa
		));

		$this->assertNotEqual($saved, null, 'Failed to save RSA SSH key');
		$this->assertNotEqual($saved['SshKey']['id'], null, 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['user_id'], 2, 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['key'], preg_replace('/ somebody@localhost$/', '', $this->rsa), 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['comment'], 'somebody@localhost', 'Failed to save RSA SSH key');

		$this->SshKey->create();

		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'key' => $dsa
		));

		$this->assertNotEqual($saved, null, 'Failed to save DSA SSH key');
		$this->assertNotEqual($saved['SshKey']['id'], null, 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['user_id'], 2, 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['key'], preg_replace('/ somebody@localhost$/', '', $this->dsa), 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['comment'], 'somebody@localhost', 'Failed to save DSA SSH key');
	}

	public function testAddKeyWithoutFormatType() {

		// Remove the leading format types
		$rsa = preg_replace('/^ssh-rsa /', '', $this->rsa);
		$dsa = preg_replace('/^ssh-dss /', '', $this->dsa);

		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'key' => $rsa
		));

		$this->assertNotEqual($saved, null, 'Failed to save RSA SSH key');
		$this->assertNotEqual($saved['SshKey']['id'], null, 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['user_id'], 2, 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['key'], preg_replace('/ somebody@localhost$/', '', $this->rsa), 'Failed to save RSA SSH key');
		$this->assertEqual($saved['SshKey']['comment'], 'somebody@localhost', 'Failed to save RSA SSH key');

		$this->SshKey->create();

		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'key' => $dsa
		));

		$this->assertNotEqual($saved, null, 'Failed to save DSA SSH key');
		$this->assertNotEqual($saved['SshKey']['id'], null, 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['user_id'], 2, 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['key'], preg_replace('/ somebody@localhost$/', '', $this->dsa), 'Failed to save DSA SSH key');
		$this->assertEqual($saved['SshKey']['comment'], 'somebody@localhost', 'Failed to save DSA SSH key');
		
	}
}
