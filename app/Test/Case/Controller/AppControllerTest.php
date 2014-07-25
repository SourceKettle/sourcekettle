<?php
// Heavily cribbed from Joshua Paling's answer to http://stackoverflow.com/questions/19455540/mocking-an-authed-user-in-cakephp

App::uses('User', 'Model');

/**
 * AppController Test Case
 * Holds common Fixture IDs and mocks for controllers
 */
class AppControllerTestCase extends ControllerTestCase {

    public $authUserId;

    public $authUser;

/**
 * setUp method
 *
 * @return void
 */
    public function setUp() {
        parent::setUp();
        $this->User = ClassRegistry::init('User');
    }

/**
 * tearDown method
 *
 * @return void
 */
    public function tearDown() {
        unset($this->User);
        parent::tearDown();
    }

    public function testPlaceholder() {
        // This just here so we don't get "Failed - no tests found in class AppControllerTest"
        $this->assertTrue(true);
    }

    protected function _generateMockWithAuthUserId($contollerName, $userId) {
        $this->authUserId = $userId;
        $this->authUser = $this->User->findById($this->authUserId);
        $this->controller = $this->generate($contollerName, array(
            'methods' => array(
                '_tryRememberMeLogin',
                '_checkSignUpProgress'
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
            )
        ));

        $this->controller->Auth
            ->expects($this->any())
            ->method('loggedIn')
            ->will($this->returnValue(true));

        $this->controller->Auth
            ->staticExpects($this->any())
            ->method('user')
            ->will($this->returnCallback(array($this, 'authUserCallback')));
    }

    public function authUserCallback($param) {
        if (empty($param)) {
            return $this->authUser['User'];
        } else {
            return $this->authUser['User'][$param];
        }
    }
}
