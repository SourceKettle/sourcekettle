<?php
/**
*
* Login form for the DevTrack system
* Renders the form which users can use to login
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* 
* @copyright     DevTrack Development Team 2012
* @link          http://github.com/chrisbulmer/devtrack
* @package       DevTrack.View.Login
* @since         DevTrack v 0.1
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

echo $this->Session->flash('auth');
echo $this->Form->create('Login');
echo $this->Form->input('email');
echo $this->Form->input('password');
echo $this->Form->end('Login');

