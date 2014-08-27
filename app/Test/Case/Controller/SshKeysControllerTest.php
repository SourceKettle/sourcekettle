<?php
App::uses('SshKeysController', 'Controller');
require_once(__DIR__ . DS . 'AppControllerTest.php');

/**
 * SshKeysController Test Case
 *
 */
class SshKeysControllerTest extends AppControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'core.cake_session',
		'app.ssh_key',
		'app.user',
		'app.collaborator',
		'app.project',
		'app.repo_type',
		'app.task',
		'app.task_type',
		'app.task_status',
		'app.task_priority',
		'app.milestone',
		'app.task_comment',
		'app.time',
		'app.task_dependency',
		'app.source',
		'app.project_history',
		'app.attachment',
		'app.email_confirmation_key',
		'app.api_key',
		'app.lost_password_key',
		'app.setting'
	);

	public function setUp() {
		parent::setUp("SshKeys");
	}
/**
 * testAdd method
 *
 * @return void
 */
	public function testAddKeyNotLoggedIn() {
		try{
			$this->testAction('/account/sshkeys/add', array('method' => 'get', 'return' => 'vars'));
		} catch(Exception $e) {}
		$this->assertNotAuthorized();
	}

	public function testAddKeyForm() {
		$this->_fakeLogin(1);
		$this->testAction('/account/sshkeys/add', array('method' => 'get', 'return' => 'vars'));
		$this->assertAuthorized();
		$this->assertRegexp('|<form action=".*'.Router::url('/account/sshkeys/add').'"|', $this->view);
	}

	public function testAddKeyInternalOK() {
		$this->_fakeLogin(1);
		$postData = array('SshKey' => array(
			'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
			'comment' => 'A new SSH key',
		));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("SshKey '<strong></strong>' has been created."), 'default', array(), 'success');
		$this->testAction('/account/sshkeys/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
		$this->assertRedirect('/account/sshkeys/view');

		$retrieved = $this->controller->SshKey->find('first', array(
			'conditions' => array('user_id' => 1, 'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA=='),
			'fields' => array('key', 'comment'),
			'recursive' => -1,
		));

		$this->assertEquals($postData, $retrieved);
	}

	public function testAddKeyExternalOK() {
		$this->_fakeLogin(6);
		$postData = array('SshKey' => array(
			'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
			'comment' => 'A new SSH key',
		));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("SshKey '<strong></strong>' has been created."), 'default', array(), 'success');
		$this->testAction('/account/sshkeys/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
		$this->assertRedirect('/account/sshkeys/view');

		$retrieved = $this->controller->SshKey->find('first', array(
			'conditions' => array('user_id' => 6, 'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA=='),
			'fields' => array('key', 'comment'),
			'recursive' => -1,
		));

		$this->assertEquals($postData, $retrieved);
	}

	public function testAddKeySystemAdminOK() {
		$this->_fakeLogin(5);
		$postData = array('SshKey' => array(
			'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
			'comment' => 'A new SSH key',
		));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("SshKey '<strong></strong>' has been created."), 'default', array(), 'success');
		$this->testAction('/account/sshkeys/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
		$this->assertRedirect('/account/sshkeys/view');

		$retrieved = $this->controller->SshKey->find('first', array(
			'conditions' => array('user_id' => 5, 'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA=='),
			'fields' => array('key', 'comment'),
			'recursive' => -1,
		));

		$this->assertEquals($postData, $retrieved);
	}

	public function testViewNotLoggedIn() {
		try{
			$this->testAction('/account/sshkeys/view', array('method' => 'get', 'return' => 'view'));
		} catch (Exception $e){}
	    $this->assertNotAuthorized();
	}

	public function testViewNoKeys() {
		$this->_fakeLogin(4);
		$this->testAction('/account/sshkeys/view', array('method' => 'get', 'return' => 'view'));
	    $this->assertAuthorized();
		$this->assertContains('Nothing here yet! <a href="'.Router::url('/account/sshkeys/add').'">Add a key here...</a>', $this->view);
	}

	public function testViewWithKeys() {
		$this->_fakeLogin(3);
		$this->testAction('/account/sshkeys/view', array('method' => 'get', 'return' => 'view'));
	    $this->assertAuthorized();
		$this->assertContains('<td>DSA key, correct, no embedded comment</td>', $this->view);
	}


}
