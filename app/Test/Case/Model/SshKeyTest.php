<?php
/**
 *
 * Email Confirmation Unit Tests for the SourceKettle system
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     SourceKettle Development Team 2012
 * @link          http://github.com/SourceKettle/sourcekettle
 * @package       SourceKettle.Test.Case.Model
 * @since         SourceKettle v 1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('SshKey', 'Model');

class SshKeyTestCase extends CakeTestCase {

	// Example RSA and DSA public keys, generated specifically for this test.
	private $dsa = 'ssh-dss AAAAB3NzaC1kc3MAAACBAMy3kgD8D2xr2HwRpz93bi/kVeusYlkz9NFXRQ6E0Ar8xiWBh1lXxWjxCfM+DSRIgdvVJA2AQi3coWfTMXTRiWJAlEJtf3eBWMvJhCeJZ40WChJfbnoRCEORsfwXoo98XigXnWrAMjfqU0xWCvCI+sdRnffUYdHXOgKwmaylPvgRAAAAFQD7AICGcDlVJCJeHu4PfAGmD4dppwAAAIEAr7gHxCATf+7N/3powMWhaJSU5fTWPtSDyDV/fTMOmwd1dvlzW2HToS42gqeg1eyw1C1FVCn/5ptB6u9Qaw3YG7Bvo79wFM1ACpDF0U4f+ZydKC8INhRt4FqUkEjJXgceKa3BAGJncLzLd0fX3TxRf2ZksQx5dV6KGFpyIa5Ii80AAACAS7fEUZgWWIJBklW18nW346jM6KAX5gdAd6j+BJ5QJ1nSxD7ZaYxKEHr7HSdNWOEyJMls4SEzX4opi+ZBVtI1shU7wclxYss0vLuBQV2DitChO9ouQsfRifRMMUB1n8KlSLNAtICjS6pa/TmlhZt9NY+oTYf76BTQFMpEsvjRP20= somebody@localhost';

	private $rsa = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCyEoCvRtgCa0+yPSuopvo2mFXnF6myTvljXujIBVVQkvk1uPWL8JU8tPBGp7xjS2nC6teHd6Ju2LItZmsLuH+U80BVuhdtGPPc4Iim8j2eEJATJRi7MDxdqlml7c1Ofa6SSwWPUsZFFlBn8KyOWn/V5EdCbBvdg8XLPWb5BhBKHBLm826tBewq7V1teCKNxS5viM7YZRibTzmuuBH7EN8Z0Qb1JMWTWnXQZM6cB9jNJfqyCOOKFWWiq+aBqtfAW58rAfISeIjxwETFYfi8DKIyGHn53MsdNwGBPH99SZnFGq43DVciYioa9t0ZX8MqDhxIhDuQOvAitORiEzmsPZ2F somebody@localhost';

	/**
	 * fixtures - Populate the database with data of the following models
	 */
	public $fixtures = array('app.ssh_key', 'app.user');

	/**
	 * @var SshKey
	 */
	private $SshKey;

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
			// This entry is invalid so should not be returned as a key
			/*array( 'SshKey' => array(
				'id' => '1',
				'user_id' => '1',
				'key' => '0b73478cee542c314e014b4e4e7200670b73478cee542c314e014b4e4e720067t',
				'comment' => 'Completely invalid key',
				'created' => '2012-06-01 12:49:40',
				'modified' => '2012-06-01 12:49:40'
			)),*/
			array(
				'SshKey' => array(
					'id' => '2',
					'user_id' => '3',
					'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
					'comment' => 'DSA key, correct, no embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '3',
					'user_id' => '2',
					'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
					'comment' => 'DSA key, correct, with embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '4',
					'user_id' => '2',
					'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
					'comment' => 'DSA key, no prefix, with embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '5',
					'user_id' => '2',
					'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
					'comment' => 'DSA key, no prefix, no embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '6',
					'user_id' => '2',
					'key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR',
					'comment' => 'RSA key, correct, no embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '7',
					'user_id' => '2',
					'key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR',
					'comment' => 'RSA key, correct, with embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '8',
					'user_id' => '2',
					'key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR',
					'comment' => 'RSA key, no prefix, with embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '9',
					'user_id' => '2',
					'key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR',
					'comment' => 'RSA key, no prefix, no embedded comment',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
			),
			array(
				'SshKey' => array(
					'id' => '10',
					'user_id' => '2',
					'key' => 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDGu20Fvn1KUo189Qg2/CVj9hXrrowPj0Fn8f8JgWuV44r4/uv35g2uIXh4xzKM194Gb98U6kZDxk2XktvwpimFdeaWKzJ2l2blB4j2UO8FettLbcCQZcb+LG0BMsfFG2d3gxuqCJlyqRZyHmvareXdkz/iuEXidSro3VsgDP6L91YcNVWlEHnbq/xiYtlOCmXVgixfZh+3zyCoF0sdEJbwz0u3ma+Gdp3drHh7IkfOL/QuYQ63lsJYUn60ptJNRWqBzwp7C+DDYHnZYObyidpzqY/zQOo8bpqQZGtIL7J9prtgRAwkumn7kFnD1wWSZD1XoSXG3vDaEPAtER4gREjR',
					'comment' => 'foobar@myhost',
					'created' => '2012-06-01 12:49:40',
					'modified' => '2012-06-01 12:49:40'
				)
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
		$this->assertFalse($saved, 'Save RSA key succeeded despite invalid SSH key');

	}

	public function testInvalidKeyType() {
		$saved = $this->SshKey->save(array(
			'user_id' => 2,
			'comment' => 'some comment',
			'key' => 'mooseWibbleShoeBags',
		));
		$this->assertFalse($saved, 'Save RSA key succeeded despite invalid SSH key');
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

	public function testAddingADuplicateKeyFails() {
		$arrayKeysToKeep = array_flip(array('user_id', 'key', 'comment'));

		$SshKeysInDb = $this->SshKey->find('all');
		$rawSshKeyToSave = $SshKeysInDb[0]['SshKey'];

		$keyToSave = array_intersect_key($rawSshKeyToSave, $arrayKeysToKeep);

		$saved = $this->SshKey->save($keyToSave);

		$this->assertFalse($saved, "Key was saved even though it already exists in the database");
	}
}
