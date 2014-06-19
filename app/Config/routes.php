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
	Router::connect('/', array('controller' => 'pages', 'action' => 'home'));

        /*
         * Defined to make the logging in/out etc look less retarded than /login/logout
         */
        Router::connect('/logout', array('controller' => 'login', 'action' => 'logout'));
        Router::connect('/lost_password', array('controller' => 'users', 'action' => 'lost_password'));
        Router::connect('/reset_password/*', array('controller' => 'users', 'action' => 'reset_password'));
        Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
        Router::connect('/activate/*', array('controller' => 'users', 'action' => 'activate'));
        Router::connect('/admin', array('controller' => 'admin', 'action' => 'index', 'admin' => true));

        /*
         * Define some more to make the footer pages work
         */
        Router::connect('/about', array('controller' => 'pages', 'action' => 'display', 'about'));

        /*
         * The below routes allow all projects to be accessed at APP/project/project_name/[controller_to_use/]?[action/]?[params]?
         *
         * Where controller_to_use allows another controller to be used whilst still appearing at APP/project/project_name/...
         * If this is blank (APP/project/project_name/action) then ProjectsController will be used
         * e.g. APP/project/project_name/tasks will route to the TasksController instead of the ProjectsController
         *
         * The action is the action to perform in the given controller. If no action is set, it will call index()
         * e.g. APP/project/project_name/tasks/add will call the add() function in TasksController
         *
         * The params are any additional params to be pass
         *
         */
        Router::connect('/project/:project/tasks/:action/*', array('controller' => 'tasks'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/tasks/*', array('controller' => 'tasks'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

        Router::connect('/project/:project/milestones/:action/*', array('controller' => 'milestones'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/milestones/*', array('controller' => 'milestones'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

        Router::connect('/project/:project/time/:action/:year/:week/*', array('controller' => 'times'), array('pass' => array('project', 'year', 'week'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/time/:action/*', array('controller' => 'times'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/time/*', array('controller' => 'times'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

        Router::connect('/project/:project/source/:action/:branch/*', array('controller' => 'source', 'action' => 'tree'), array('pass' => array('project', 'branch'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/source/:action/*', array('controller' => 'source'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/source/*', array('controller' => 'source'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

        Router::connect('/project/:project/collaborators/:action/*', array('controller' => 'collaborators'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/collaborators/*', array('controller' => 'collaborators'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

        Router::connect('/project/:project/attachment/:action/*', array('controller' => 'attachments'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/attachment/*', array('controller' => 'attachments'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

        /*
         * If no other controller is to be used, use the projects controller
         */
        Router::connect('/project/:project/:action/*', array('controller' => 'projects'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));
        Router::connect('/project/:project/*', array('controller' => 'projects', 'action' => 'view'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

		// This is a (fudgy!) fix to make submodules' links in the source view work. We may want to change this if we ever support HTTPS access to git.
		Router::connect('/projects/:project.git', array('controller' => 'projects', 'action' => 'view'), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

		/*
		 * Admin link for project names, this sorts out some issues with generating links from the admin pages
		 */
        Router::connect('/admin/projects/:project', array('controller' => 'projects', 'action' => 'view', 'admin' => true), array('pass' => array('project'), 'project' => '[0-9a-zA-Z_-]+'));

        /*
         * Add custom route for editing the sshkeys associated to a user
         */
        Router::connect('/account/sshkeys/:action/*', array('controller' => 'sshKeys'));

        /*
         * Route to make the 'account settings' addresses look nicer.
         */
        Router::connect('/account/:action/*', array('controller' => 'users'), array ('action' => 'index|delete|details|security|theme'));


/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

        Router::parseExtensions("json");

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
