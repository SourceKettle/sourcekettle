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
		'app.setting',
		'app.user_setting',
		'app.project_setting',
		'app.project',
		'app.project_history',
		'app.repo_type',
		'app.collaborator',
		'app.user',
		'app.task',
		'app.task_type',
		'app.task_dependency',
		'app.task_comment',
		'app.task_status',
		'app.task_priority',
		'app.time',
		'app.attachment',
		'app.source',
		'app.milestone',
		'app.email_confirmation_key',
		'app.ssh_key',
		'app.api_key',
		'app.lost_password_key',
		'app.milestone_burndown_log',
		'app.project_burndown_log',
		'app.collaborating_team',
		'app.group_collaborating_team',
		'app.team',
		'app.teams_user',
		'app.project_group',
		'app.project_groups_project',
	);

	public function setUp($controllerName = null) {
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

	public function testAddKeyMissingKey() {
		$this->_fakeLogin(1);
		$postData = array('SshKey' => array(
			'comment' => 'A new SSH key',
		));

		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("SshKey '<strong>A new SSH key</strong>' could not be created. Please try again."), 'default', array(), 'error');
		$this->testAction('/account/sshkeys/add', array('method' => 'post', 'return' => 'view', 'data' => $postData));
		$this->assertAuthorized();
	}

	public function testAddKeyInternalOK() {
		$this->_fakeLogin(1);
		$postData = array('SshKey' => array(
			'key' => 'ssh-dss AAAAB3NzaC1kc3MAAACBAIL6C57bq8sk+yhZUi5UBb5uc0uWEdCvtQ8gKnbtBrp9DVzDVE/js+LwwiQZ2+t608Y0ImVT/hUc3+W2tD/33F9eyJnNEWPWgj2nzHRA0+Z65OFQDUjxduF1dp9xdKj3EsL29ggU5OKNPwN9V9EFZdnDiM7UH/tbW9gQSmwdjcyHAAAAFQDxiBX3Aq7mwB4jVesCl+AIHvaFxwAAAIBvMGEVYtYms6OabQwkdb7uz47z4kYGcEYTmn9htY9RwdWBSTt6h48NuivTClWcVTBylFS/h77RMGC42Og25xj6qrwBF+hjMNofkQScD0hhrcZMNo2cPnnBGZCpSwmVMw2WLbNKgqGdLmcbj09Lfgm9t82XTmOkCzxa+7nwna0P8wAAAIAcZuaHOM1g7Wnlohwl2OoOw+u1Wt5dCJt5uKJN8PIIXY9LoZYG1xTvmQJ/mb5FcW9Ewiz3YIAMPwHvj7A3ZcZs8PyFNjhP7i7Trtg9+PqTpASPm8HMpzge6QD2S2rgvKEbY9Pe4TgqQUmNqlS4Mofnh1voFnwdOedFjLHuVdFSvA==',
			'comment' => 'A new SSH key',
		));

		// TODO should contain the key comment in the flash message
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


	public function testDeleteNotLoggedIn() {
		try{
			$this->testAction('/account/sshkeys/delete/3', array('method' => 'post', 'return' => 'view'));
		} catch (ForbiddenException $e) {
			$this->assertTrue(true);
		}
	}

	public function testDeleteGetFail() {
		$this->_fakeLogin(3);
		$this->testAction('/account/sshkeys/delete/2', array('method' => 'get', 'return' => 'view'));
	    $this->assertAuthorized();
		$this->assertRedirect('/account/sshkeys/view');
	}

	public function testDeleteInvalidKey() {
		$this->_fakeLogin(3);
		try{
			$this->testAction('/account/sshkeys/delete/9001', array('method' => 'post', 'return' => 'view'));
		} catch (NotFoundException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			$this->assertFalse(true, "Wrong exception thrown");
		}
	    $this->assertAuthorized();
	}

	public function testDeleteNotOwner() {
		$this->_fakeLogin(3);
		try{
			$this->testAction('/account/sshkeys/delete/3', array('method' => 'post', 'return' => 'view'));
		} catch (ForbiddenException $e) {
			$this->assertTrue(true);
		} catch (Exception $e) {
			$this->assertFalse(true, "Wrong exception thrown");
		}
	    $this->assertAuthorized();
	}

	public function testDeleteOK() {
		$this->_fakeLogin(3);
		$this->controller->Session
			->expects($this->once())
			->method('setFlash')
			->with(__("SshKey '<strong>DSA key, correct, no embedded comment</strong>' has been deleted."), 'default', array(), 'success');
		$this->testAction('/account/sshkeys/delete/2', array('method' => 'post', 'return' => 'view'));
	    $this->assertAuthorized();
		$this->assertRedirect('/account/sshkeys/view');
		$retrieved = $this->controller->SshKey->findById(2);
		$this->assertEquals(array(), $retrieved);
	}


}
