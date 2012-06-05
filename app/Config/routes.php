<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/setup', array('controller' => 'pages', 'action' => 'display', 'setup'));
        
        /*
         * Defined to make the logging in/out etc look less retarded that /login/logout
         */
        Router::connect('/logout', array('controller' => 'login', 'action' => 'logout'));
        Router::connect('/forgot_password', array('controller' => 'users', 'action' => 'forgot_password'));
        Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
        Router::connect('/activate/*', array('controller' => 'users', 'action' => 'activate'));
        
        /*
         * Define some more to make the footer pages work
         */
        Router::connect('/about', array('controller' => 'pages', 'action' => 'display', 'about'));
        Router::connect('/svn_help', array('controller' => 'pages', 'action' => 'display', 'svn_help'));
        Router::connect('/git_help', array('controller' => 'pages', 'action' => 'display', 'git_help'));
        
        /*
         * Defined urls to allow for projects to be referenced using a GitHub 'style' URL pattern
         * The first block of four routes define the controllers for given in the
         * URL (not the projects controller as it would normally route to.
         * 
         * The second block state routes for if the URL routes to the project controller
         */
        Router::connect('/project/:project/tasks/*', array('controller' => 'tasks'), array('pass' => array('project'), 'project' => '[\w]+'));
        Router::connect('/project/:project/time/*', array('controller' => 'time'), array('pass' => array('project'), 'project' => '[\w]+'));
        Router::connect('/project/:project/source/*', array('controller' => 'source'), array('pass' => array('project'), 'project' => '[\w]+'));
        Router::connect('/project/:project/collaborators/*', array('controller' => 'collaborators'), array('pass' => array('project'), 'project' => '[\w]+'));
        
        
        Router::connect('/project/:project/:action/*', array('controller' => 'projects'), array('pass' => array('project'), 'project' => '[\w]+'));
        Router::connect('/project/:project/*', array('controller' => 'projects', 'action' => 'view'), array('pass' => array('project'), 'project' => '[\w]+'));
         
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
