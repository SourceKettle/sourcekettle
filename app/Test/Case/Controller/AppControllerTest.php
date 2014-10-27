<?php
// Heavily cribbed from Joshua Paling's answer to http://stackoverflow.com/questions/19455540/mocking-an-authed-user-in-cakephp

App::uses('User', 'Model');

/**
 * AppController Test Case
 * Holds common Fixture IDs and mocks for controllers
 */
class AppControllerTest extends ControllerTestCase {

    public $authUserId;

    public $authUser;

/**
 * setUp method
 *
 * @return void
 */
    public function setUp($controllerName = null) {
        parent::setUp();
		if ($controllerName == null) {return;}
        $this->User = ClassRegistry::init('User');
		$this->authUserId = null;
		$this->authUser = null;
        $this->controller = $this->generate($controllerName, array(
            'methods' => array(
                '_tryRememberMeLogin',
                '_checkSignUpProgress',
            ),
            'components' => array(
                'Auth' => array(
                    'user',
                    'loggedIn',
                ),
                'Security' => array(
                    '_validateCsrf',
                ),
                'Session',
				'Email',
            )
        ));

        $this->controller->Auth
            ->expects($this->any())
            ->method('loggedIn')
            ->will($this->returnCallback(array($this, 'authLoggedInCallback')));

        $this->controller->Auth
            ->staticExpects($this->any())
            ->method('user')
            ->will($this->returnCallback(array($this, 'authUserCallback')));

    }

/**
 * tearDown method
 *
 * @return void
 */
    public function tearDown() {
        unset($this->User);
        unset($this->controller);
        parent::tearDown();
    }

    public function testPlaceholder() {
        // This just here so we don't get "Failed - no tests found in class AppControllerTest"
        $this->assertTrue(true);
    }

    protected function _fakeLogin($userId) {
        $this->authUserId = $userId;
        $this->authUser = $this->User->findById($this->authUserId);
		if (@$this->authUser['User']) {
			$this->authUser = $this->authUser['User'];
		}
    }

    public function authUserCallback($param = null) {
		if (!isset($this->authUser) || $this->authUser == null || empty($this->authUser)) {
			return null;
		}
        if (empty($param)) {
            return $this->authUser;
        } else {
            return $this->authUser[$param];
        }
    }

    public function authLoggedInCallback() {
		if (!isset($this->authUser) || $this->authUser == null || empty($this->authUser)) {
			return false;
		}
		return true;
    }

	// Helper to assert whether the user is authorized for the action that has just been performed
	public function assertAuthorized() {
		if (!isset($this->controller) || $this->controller == null) {
			return $this->assertTrue(false, "Should be authorized but no controller found");
		}
		return $this->assertTrue($this->controller->isAuthorized($this->authUser), "Should be authorized");
    }

	public function assertNotAuthorized() {
		if (!isset($this->controller) || $this->controller == null) {
			return $this->assertTrue(false, "Should not be authorized but no controller found");
		}
		return $this->assertFalse($this->controller->isAuthorized($this->authUser), "Should not be authorized");
    }

	public function assertRedirect($url) {
		$this->assertNotNull($this->headers, "Expected a redirect, but we have no headers");
		$this->assertNotNull(@$this->headers['Location'], "Expected a redirect, but we did not get one");
		$this->assertEquals(Router::url($url, true), $this->headers['Location']);
	}
}
